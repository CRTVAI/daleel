<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use App\Models\ExtensionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        

        $range = $request->get('range', 'today');
        $dateFilter = null;
        if ($range === 'today') {
            $dateFilter = today();
        } elseif ($range === '7days') {
            $dateFilter = today()->subDays(7);
        } elseif ($range === '30days') {
            $dateFilter = today()->subDays(30);
        }

        // Paginate employees list for scalability
        $employees = User::where('parent_id', $user->id)
            ->with('department')
            ->orderBy('name')
            ->paginate(15, ['*'], 'employees_page');
            
        $employeeIds = User::where('parent_id', $user->id)->pluck('id');
        
        $departments = Department::where('company_id', $user->id)
            ->withCount('employees')
            ->get();

        // Overall Team KPIs. Team telemetry uses per-session employee records as the source of truth.
        $totalActiveMs = $this->effectiveActiveMs($employeeIds, $dateFilter, $range);
        
        // aiActiveMs currently relies on sessions for AI tool flag
        $aiActiveMs = $this->dedupedActiveMs($employeeIds, $dateFilter, $range, true);
        $aiAdoptionRate = $totalActiveMs > 0 ? round(($aiActiveMs / $totalActiveMs) * 100, 1) : 0;

        // 2. Department-wise total active times
        $deptActiveMs = [];
        foreach ($departments as $dept) {
            $deptEmpIds = User::where('department_id', $dept->id)->where('parent_id', $user->id)->pluck('id');
            $deptActiveMs[$dept->id] = $this->effectiveActiveMs($deptEmpIds, $dateFilter, $range);
        }

        // 3. Employee-wise total active times
        $empActiveMs = [];
        $paginatedEmpIds = $employees->pluck('id');

        $effectiveEmpMs = $this->effectiveActiveMsByUser($paginatedEmpIds, $dateFilter, $range);
        
        $activeMsLogs = DB::query()
            ->fromSub($this->dedupedSessionQuery($paginatedEmpIds, $dateFilter, $range), 'deduped_sessions')
            ->selectRaw('user_id, sum(active_ms) as total_active_ms, sum(open_ms) as total_open_ms')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');
        
        $empActiveRatios = [];
        $empLastSyncedAt = [];
        
        foreach ($employees as $emp) {
            $sessionUsage = $activeMsLogs[$emp->id] ?? null;
            $empActiveMs[$emp->id] = $effectiveEmpMs[$emp->id] ?? 0;
            
            // For active ratio, calculate using effective active ms over total open ms if possible
            $openMs = $sessionUsage->total_open_ms ?? 0;
            if ($empActiveMs[$emp->id] > $openMs) {
                $openMs = $empActiveMs[$emp->id]; // Ensure ratio doesn't exceed 100% due to missing open_ms from snapshots
            }
            
            $empActiveRatios[$emp->id] = $openMs > 0
                ? round(($empActiveMs[$emp->id] / $openMs) * 100)
                : 0;
            $lastVal = collect([
                \App\Models\ExtensionDevice::where('user_id', $emp->id)->max('last_active_at'),
                ExtensionSession::where('user_id', $emp->id)->max('updated_at'),
                \App\Models\ExtensionMetricsSnapshot::where('user_id', $emp->id)->max('updated_at'),
                \App\Models\ExtensionDailyRollup::where('user_id', $emp->id)->max('updated_at'),
            ])->filter()->sort()->last();
            
            $empLastSyncedAt[$emp->id] = $lastVal ? \Illuminate\Support\Carbon::parse($lastVal) : null;
        }

        return view('team.index', compact(
            'departments',
            'employees',
            'deptActiveMs',
            'empActiveMs',
            'empActiveRatios',
            'empLastSyncedAt',
            'totalActiveMs',
            'aiAdoptionRate',
            'range'
        ));
    }

    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Department::create([
            'company_id' => Auth::id(),
            'name' => $request->name,
        ]);

        return back()->with('success', 'Department created successfully.');
    }

    public function destroyDepartment(Department $department)
    {
        if ($department->company_id !== Auth::id()) {
            abort(403);
        }

        $department->delete();

        return back()->with('success', 'Department deleted successfully.');
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        // Verify department belongs to user if provided
        if ($request->department_id) {
            $dept = Department::find($request->department_id);
            if ($dept->company_id !== Auth::id()) {
                abort(403, 'Invalid department');
            }
        }

        // Generate a random placeholder email since they don't log in
        $randomEmail = 'emp_' . Str::random(10) . '@crtvai.local';
        
        $employee = User::create([
            'name'                      => $request->name,
            'email'                     => $randomEmail,
            'password'                  => Hash::make(Str::random(24)),
            'parent_id'                 => Auth::id(),
            'department_id'             => $request->department_id,
            'is_employee'               => true,
            'connection_code'           => $this->generateUniqueCode(),
            'connection_code_issued_at' => now(),
        ]);

        return back()->with('success', 'Employee created successfully.');
    }

    public function destroyEmployee(User $employee)
    {
        if ($employee->parent_id !== Auth::id()) {
            abort(403);
        }

        $employee->delete();

        return back()->with('success', 'Employee deleted successfully.');
    }

    public function regenerateCode(User $employee)
    {
        if ($employee->parent_id !== Auth::id()) {
            abort(403);
        }

        $employee->update([
            'connection_code'           => $this->generateUniqueCode(),
            'connection_code_issued_at' => now(),
        ]);

        return back()->with('success', 'Connection code regenerated successfully.');
    }

    public function employeeTopSites(Request $request, User $employee)
    {
        if ($employee->parent_id !== Auth::id()) {
            abort(403);
        }

        [$range, $dateFilter] = $this->resolveRange($request);

        $ms = function($v) {
            $v = max(0, intval($v));
            if ($v < 60000) return round($v/1000).'s';
            $m = floor($v/60000);
            if ($m < 60) return $m.'m';
            return floor($m/60).'h '.($m%60).'m';
        };

        $employeeIds = collect([$employee->id]);
        $totalActiveMs = $this->dedupedActiveMs($employeeIds, $dateFilter, $range);
        $sites = $this->sessionTopSites($employeeIds, $dateFilter, $range, false);

        return view('team.partials.top-sites', compact('sites', 'totalActiveMs', 'ms'));
    }
    public function employeeHelpRequests(User $employee)
    {
        if ($employee->parent_id !== Auth::id()) {
            abort(403);
        }

        $helpRequests = \App\Models\ExtensionHelpRequest::where('user_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('team.partials.help-requests', compact('helpRequests'));
    }

    public function departmentTopSites(Request $request, Department $department)
    {
        if ($department->company_id !== Auth::id()) {
            abort(403);
        }

        [$range, $dateFilter] = $this->resolveRange($request);

        $ms = function($v) {
            $v = max(0, intval($v));
            if ($v < 60000) return round($v/1000).'s';
            $m = floor($v/60000);
            if ($m < 60) return $m.'m';
            return floor($m/60).'h '.($m%60).'m';
        };

        $memberIds = User::where('department_id', $department->id)
            ->where('parent_id', Auth::id())
            ->pluck('id');

        $totalActiveMs = $this->dedupedActiveMs($memberIds, $dateFilter, $range);
        $sites = $this->sessionTopSites($memberIds, $dateFilter, $range, true);

        return view('team.partials.top-sites', compact('sites', 'totalActiveMs', 'ms'));
    }

    public function overallTopSites(Request $request)
    {
        $user = Auth::user();
        $employeeIds = User::where('parent_id', $user->id)->pluck('id');
        [$range, $dateFilter] = $this->resolveRange($request);

        $ms = function($v) {
            $v = max(0, intval($v));
            if ($v < 60000) return round($v/1000).'s';
            $m = floor($v/60000);
            if ($m < 60) return $m.'m';
            return floor($m/60).'h '.($m%60).'m';
        };

        $totalActiveMs = $this->dedupedActiveMs($employeeIds, $dateFilter, $range);
        $sites = $this->sessionTopSites($employeeIds, $dateFilter, $range, true);

        return view('team.partials.top-sites', compact('sites', 'totalActiveMs', 'ms'));
    }

    private function generateUniqueCode()

    {
        do {
            $code = 'EMP-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        } while (User::where('connection_code', $code)->exists());

        return $code;
    }

    private function resolveRange(Request $request): array
    {
        $range = $request->get('range', 'today');
        $dateFilter = null;

        if ($range === 'today') {
            $dateFilter = today();
        } elseif ($range === '7days') {
            $dateFilter = today()->subDays(7);
        } elseif ($range === '30days') {
            $dateFilter = today()->subDays(30);
        }

        return [$range, $dateFilter];
    }

    private function dedupedActiveMs($userIds, $dateFilter = null, string $range = 'all', ?bool $aiOnly = null): int
    {
        $query = $this->dedupedSessionQuery($userIds, $dateFilter, $range, $aiOnly);

        return (int) DB::query()
            ->fromSub($query, 'deduped_sessions')
            ->sum('active_ms');
    }

    private function sessionTopSites($userIds, $dateFilter = null, string $range = 'all', bool $includeVisitors = false)
    {
        $rows = DB::query()
            ->fromSub($this->dedupedSiteSessionQuery($userIds, $dateFilter, $range), 'deduped_sites')
            ->selectRaw('user_id, domain, category, is_ai_tool, active_ms')
            ->get();

        return $rows
            ->groupBy('domain')
            ->map(function ($group, $domain) use ($includeVisitors) {
                $primary = $group->sortByDesc('active_ms')->first();
                $site = (object) [
                    'domain' => $domain,
                    'category' => $primary->category ?: 'General',
                    'is_ai_tool' => $group->contains(fn ($row) => (bool) $row->is_ai_tool),
                    'active_ms' => (int) $group->sum('active_ms'),
                    'sessions_count' => $group->count(),
                ];

                if ($includeVisitors) {
                    $site->visitors_count = $group->pluck('user_id')->unique()->count();
                }

                return $site;
            })
            ->sortByDesc('active_ms')
            ->take(20)
            ->values();
    }

    private function effectiveActiveMs($userIds, $dateFilter = null, string $range = 'all'): int
    {
        return array_sum($this->effectiveActiveMsByUser($userIds, $dateFilter, $range));
    }

    private function effectiveActiveMsByUser($userIds, $dateFilter = null, string $range = 'all'): array
    {
        $ids = collect($userIds)->filter()->values();

        if ($ids->isEmpty()) {
            return [];
        }

        // Source of truth: deduplicated session data keyed by user
        $sessionTotals = DB::query()
            ->fromSub($this->dedupedSessionQuery($ids, $dateFilter, $range), 'deduped_sessions')
            ->selectRaw('user_id, sum(active_ms) as active_ms')
            ->groupBy('user_id')
            ->pluck('active_ms', 'user_id')
            ->toArray();

        // Fallback: daily rollups — used ONLY for users who have no sessions at all
        // (e.g., older data or rollup-only syncs). Never added on top of sessions.
        $usersWithNoSessions = $ids->filter(fn($id) => empty($sessionTotals[$id]))->values();
        $rollupTotals = [];

        if ($usersWithNoSessions->isNotEmpty()) {
            $rollupQuery = \App\Models\ExtensionDailyRollup::whereIn('user_id', $usersWithNoSessions);
            if ($dateFilter && $range === 'today') {
                $rollupQuery->whereDate('date', $dateFilter);
            } elseif ($dateFilter) {
                $rollupQuery->where('date', '>=', $dateFilter);
            }
            $rollupTotals = $rollupQuery
                ->selectRaw('user_id, sum(total_active_ms) as active_ms')
                ->groupBy('user_id')
                ->pluck('active_ms', 'user_id')
                ->toArray();
        }

        return $ids
            ->mapWithKeys(function ($userId) use ($sessionTotals, $rollupTotals) {
                $sessionMs = (int) ($sessionTotals[$userId] ?? 0);
                // Only use rollup when we truly have zero session granularity for this user
                $fallbackMs = $sessionMs === 0 ? (int) ($rollupTotals[$userId] ?? 0) : 0;
                return [$userId => $sessionMs + $fallbackMs];
            })
            ->toArray();

    }

    private function effectiveTopSites($userIds, $dateFilter = null, string $range = 'all', bool $includeVisitors = false)
    {
        $ids = collect($userIds)->filter()->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        $rows = collect();

        foreach ($ids as $userId) {
            $sessionRows = DB::query()
                ->fromSub($this->dedupedSiteSessionQuery(collect([$userId]), $dateFilter, $range), 'deduped_sites')
                ->selectRaw('user_id, domain, category, is_ai_tool, sum(active_ms) as active_ms, count(*) as sessions_count')
                ->groupBy('user_id', 'domain', 'category', 'is_ai_tool')
                ->get();

            $sessionTotal = (int) $sessionRows->sum('active_ms');

            $snapshotQuery = \App\Models\ExtensionMetricsSnapshot::where('user_id', $userId);
            if ($dateFilter && $range === 'today') {
                $snapshotQuery->whereDate('captured_at', $dateFilter);
            } elseif ($dateFilter) {
                $snapshotQuery->where('captured_at', '>=', $dateFilter);
            }

            $snapshot = $snapshotQuery->latest('captured_at')->first();

            if ($snapshot && (int) $snapshot->active_ms > $sessionTotal && !empty($snapshot->top_platforms)) {
                $domainMeta = $sessionRows->keyBy('domain');

                foreach ($snapshot->top_platforms as $platform) {
                    $domain = $platform['domain'] ?? 'Unknown';
                    $meta = $domainMeta->get($domain);

                    $rows->push((object) [
                        'user_id' => $userId,
                        'domain' => $domain,
                        'category' => $meta->category ?? 'General',
                        'is_ai_tool' => (bool) ($meta->is_ai_tool ?? false),
                        'active_ms' => (int) ($platform['active_ms'] ?? 0),
                        'sessions_count' => (int) ($platform['sessions_count'] ?? 1),
                    ]);
                }
            } else {
                $sessionRows->each(fn ($row) => $rows->push($row));
            }
        }

        return $rows
            ->groupBy(fn ($row) => $row->domain . '|' . $row->category . '|' . (int) $row->is_ai_tool)
            ->map(function ($group) use ($includeVisitors) {
                $first = $group->first();
                $site = (object) [
                    'domain' => $first->domain,
                    'category' => $first->category,
                    'is_ai_tool' => (bool) $first->is_ai_tool,
                    'active_ms' => (int) $group->sum('active_ms'),
                    'sessions_count' => (int) $group->sum('sessions_count'),
                ];

                if ($includeVisitors) {
                    $site->visitors_count = $group->pluck('user_id')->unique()->count();
                }

                return $site;
            })
            ->sortByDesc('active_ms')
            ->take(20)
            ->values();
    }

    private function dedupedSessionQuery($userIds, $dateFilter = null, string $range = 'all', ?bool $aiOnly = null)
    {
        $query = ExtensionSession::query()
            ->whereIn('user_id', collect($userIds)->filter()->values())
            ->selectRaw('user_id, session_id_from_ext, max(active_ms) as active_ms, max(open_ms) as open_ms')
            ->groupBy('user_id', 'session_id_from_ext');

        $this->applySessionDateFilter($query, $dateFilter, $range);

        if ($aiOnly !== null) {
            $query->where('is_ai_tool', $aiOnly);
        }

        return $query;
    }

    private function dedupedSiteSessionQuery($userIds, $dateFilter = null, string $range = 'all')
    {
        $query = ExtensionSession::query()
            ->whereIn('user_id', collect($userIds)->filter()->values())
            ->selectRaw('
                user_id,
                session_id_from_ext,
                COALESCE(platform_domain, "Unknown") as domain,
                COALESCE(platform_category, "General") as category,
                is_ai_tool,
                max(active_ms) as active_ms
            ')
            ->groupBy('user_id', 'session_id_from_ext', 'platform_domain', 'platform_category', 'is_ai_tool');

        $this->applySessionDateFilter($query, $dateFilter, $range);

        return $query;
    }

    private function applySessionDateFilter($query, $dateFilter, string $range): void
    {
        if ($dateFilter && $range === 'today') {
            $query->whereDate('started_at', $dateFilter);
        } elseif ($dateFilter) {
            $query->where('started_at', '>=', $dateFilter);
        }
    }
}
