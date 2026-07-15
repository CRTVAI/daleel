<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use App\Models\ExtensionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

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
        if ($request->filled('email')) {
            $request->merge(['email' => Str::lower($request->input('email'))]);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ], [
            'email.unique' => 'This email address already belongs to an existing user or student.',
        ]);

        // Verify department belongs to user if provided
        if ($data['department_id'] ?? null) {
            $dept = Department::find($data['department_id']);
            if ($dept->company_id !== Auth::id()) {
                abort(403, 'Invalid department');
            }
        }

        $connectionCode = $this->generateUniqueCode();
        
        $employee = User::create([
            'name'                      => $data['name'],
            'email'                     => $data['email'],
            'password'                  => Hash::make(Str::random(24)),
            'parent_id'                 => Auth::id(),
            'department_id'             => $data['department_id'] ?? null,
            'is_employee'               => true,
            'connection_code'           => $connectionCode,
            'connection_code_issued_at' => now(),
        ]);

        try {
            $this->sendEmployeeInviteEmail($employee, $connectionCode);
        } catch (\Throwable $e) {
            Log::error('Student invite email failed', [
                'employee_id' => $employee->id,
                'email' => $employee->email,
                'error' => $e->getMessage(),
            ]);

            return back()->with('warning', 'Student created, but the invite email could not be sent. Please share the connection key manually.');
        }

        return back()->with('success', 'Student created and invite email sent successfully.');
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

        $connectionCode = $this->generateUniqueCode();

        $employee->update([
            'connection_code'           => $connectionCode,
            'connection_code_issued_at' => now(),
        ]);

        try {
            $this->sendEmployeeInviteEmail($employee, $connectionCode, true);
        } catch (\Throwable $e) {
            Log::error('Student regenerated key email failed', [
                'employee_id' => $employee->id,
                'email' => $employee->email,
                'error' => $e->getMessage(),
            ]);

            return back()->with('warning', 'New connection key generated, but the email could not be sent. Please try again.');
        }

        return back()->with('success', 'New connection key generated and sent to the student email.');
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

    public function reportsIndex(Request $request)
    {
        $user = Auth::user();
        $range = $request->get('range', 'today');
        $departments = Department::where('company_id', $user->id)
            ->withCount('employees')
            ->get();
        return view('team.reports', compact('departments', 'range'));
    }

    public function inspectorReport(Request $request)
    {
        $user = Auth::user();
        [$range, $dateFilter] = $this->resolveRange($request);
        $deptId = $request->get('dept_id');

        // --- Base learner set ---
        $allEmployeeIds = User::where('parent_id', $user->id)->pluck('id');
        $departments    = Department::where('company_id', $user->id)->withCount('employees')->get();

        // Optionally filter to one department
        if ($deptId) {
            $dept = $departments->firstWhere('id', $deptId);
            $employeeIds = User::where('parent_id', $user->id)
                ->where('department_id', $deptId)->pluck('id');
        } else {
            $dept = null;
            $employeeIds = $allEmployeeIds;
        }

        $employees = User::where('parent_id', $user->id)
            ->when($deptId, fn($q) => $q->where('department_id', $deptId))
            ->with('department')
            ->orderBy('name')
            ->get();

        $totalLearners = $employees->count();

        // --- Engagement & time KPIs ---
        $ms = function ($v) {
            $v = max(0, intval($v));
            if ($v < 60000) return round($v / 1000) . 's';
            $m = floor($v / 60000);
            if ($m < 60) return $m . 'm';
            return floor($m / 60) . 'h ' . ($m % 60) . 'm';
        };

        $effectiveMsByUser = $this->effectiveActiveMsByUser($employeeIds, $dateFilter, $range);
        $totalActiveMs     = array_sum($effectiveMsByUser);
        $totalActiveHours  = round($totalActiveMs / 3600000, 1);

        // Active learners = those with any recorded active time
        $activeLearnerIds = collect($effectiveMsByUser)->filter(fn($ms) => $ms > 0)->keys();
        $activeLearners   = $activeLearnerIds->count();

        // Learners who have used tools (have at least one session)
        $startedCount = \App\Models\ExtensionSession::whereIn('user_id', $employeeIds)
            ->when($dateFilter && $range === 'today', fn($q) => $q->whereDate('started_at', $dateFilter))
            ->when($dateFilter && $range !== 'today', fn($q) => $q->where('started_at', '>=', $dateFilter))
            ->distinct('user_id')->count('user_id');

        // Engagement funnel
        $funnel = [
            'assigned'   => $totalLearners,
            'activated'  => $activeLearners,
            'started'    => $startedCount,
        ];

        // --- Work-behaviour: top tools ---
        $toolSites = $this->sessionTopSites($employeeIds, $dateFilter, $range, true);
        $topTools  = $toolSites->take(8);

        // --- Friction signals ---
        // We use a simple heuristic: frequent short sessions on the same domain = friction
        $sessionRows = \App\Models\ExtensionSession::whereIn('user_id', $employeeIds)
            ->when($dateFilter && $range === 'today', fn($q) => $q->whereDate('started_at', $dateFilter))
            ->when($dateFilter && $range !== 'today', fn($q) => $q->where('started_at', '>=', $dateFilter))
            ->select('user_id', 'platform_domain', 'active_ms', 'session_id_from_ext')
            ->get();

        $frictionData = $sessionRows
            ->groupBy('platform_domain')
            ->map(fn($rows, $domain) => [
                'domain'    => $domain,
                'frequency' => $rows->count(),
                'avg_ms'    => $rows->avg('active_ms'),
            ])
            ->filter(fn($d) => $d['domain'] !== 'Unknown' && $d['domain'] !== '')
            ->sortByDesc('frequency')
            ->take(5)
            ->values();

        // --- Weekly breakdown (activity by week in the period) ---
        $weeklyActivity = [];
        if ($range === '30days' || $range === 'all') {
            $weeks = \App\Models\ExtensionSession::whereIn('user_id', $employeeIds)
                ->when($dateFilter, fn($q) => $q->where('started_at', '>=', $dateFilter))
                ->selectRaw('YEARWEEK(started_at, 1) as yw, SUM(active_ms) as total_ms')
                ->groupBy('yw')
                ->orderBy('yw')
                ->get();
            $weeklyActivity = $weeks->map(fn($w, $i) => [
                'label'    => 'W' . ($i + 1),
                'total_ms' => (int) $w->total_ms,
                'hours'    => round($w->total_ms / 3600000, 1),
            ])->values()->toArray();
        }

        // --- Per-learner profiles ---
        $learnerProfiles = $employees->map(function ($emp) use ($effectiveMsByUser, $sessionRows, $ms, $dateFilter, $range) {
            $activeMs    = $effectiveMsByUser[$emp->id] ?? 0;
            $empSessions = $sessionRows->where('user_id', $emp->id);
            $topDomains  = $empSessions->groupBy('platform_domain')
                ->map(fn($rows) => (int) $rows->sum('active_ms'))
                ->sortDesc()->take(3)->keys()->values()->toArray();

            $lastSession = \App\Models\ExtensionSession::where('user_id', $emp->id)
                ->latest('started_at')->first();

            $engagementScore = min(100, (int) round(($activeMs / max(1, 3600000)) * 60));

            return [
                'id'               => $emp->id,
                'name'             => $emp->name,
                'email'            => $emp->email,
                'department'       => $emp->department?->name ?? 'No Group',
                'active_ms'        => $activeMs,
                'active_label'     => $ms($activeMs),
                'session_count'    => $empSessions->pluck('session_id_from_ext')->unique()->count(),
                'top_domains'      => $topDomains,
                'engagement_score' => $engagementScore,
                'last_seen'        => $lastSession?->started_at?->diffForHumans() ?? 'Never',
            ];
        })->sortByDesc('active_ms')->values();

        // median active time label
        $sortedMs  = $learnerProfiles->pluck('active_ms')->sort()->values();
        $medianMs  = $sortedMs->count() ? $sortedMs[$sortedMs->count() >> 1] : 0;
        $medianLabel = $ms($medianMs);

        $reportDate = now()->format('d M Y');

        return view('team.inspector-report', compact(
            'user', 'departments', 'dept', 'employees',
            'totalLearners', 'activeLearners', 'totalActiveHours',
            'funnel', 'topTools', 'frictionData', 'weeklyActivity',
            'learnerProfiles', 'medianLabel', 'reportDate',
            'range', 'deptId', 'ms'
        ));
    }

    public function inspectorReportDownloadPdf(Request $request)
    {
        $user = Auth::user();
        [$range, $dateFilter] = $this->resolveRange($request);
        $deptId = $request->get('dept_id');

        $allEmployeeIds = User::where('parent_id', $user->id)->pluck('id');
        $departments    = Department::where('company_id', $user->id)->withCount('employees')->get();

        if ($deptId) {
            $dept = $departments->firstWhere('id', $deptId);
            $employeeIds = User::where('parent_id', $user->id)
                ->where('department_id', $deptId)->pluck('id');
        } else {
            $dept = null;
            $employeeIds = $allEmployeeIds;
        }

        $employees = User::where('parent_id', $user->id)
            ->when($deptId, fn($q) => $q->where('department_id', $deptId))
            ->with('department')
            ->orderBy('name')
            ->get();

        $totalLearners = $employees->count();

        $ms = function ($v) {
            $v = max(0, intval($v));
            if ($v < 60000) return round($v / 1000) . 's';
            $m = floor($v / 60000);
            if ($m < 60) return $m . 'm';
            return floor($m / 60) . 'h ' . ($m % 60) . 'm';
        };

        $effectiveMsByUser = $this->effectiveActiveMsByUser($employeeIds, $dateFilter, $range);
        $totalActiveMs     = array_sum($effectiveMsByUser);
        $totalActiveHours  = round($totalActiveMs / 3600000, 1);

        $activeLearnerIds = collect($effectiveMsByUser)->filter(fn($ms) => $ms > 0)->keys();
        $activeLearners   = $activeLearnerIds->count();

        $startedCount = \App\Models\ExtensionSession::whereIn('user_id', $employeeIds)
            ->when($dateFilter && $range === 'today', fn($q) => $q->whereDate('started_at', $dateFilter))
            ->when($dateFilter && $range !== 'today', fn($q) => $q->where('started_at', '>=', $dateFilter))
            ->distinct('user_id')->count('user_id');

        $funnel = [
            'assigned'   => $totalLearners,
            'activated'  => $activeLearners,
            'started'    => $startedCount,
        ];

        $toolSites = $this->sessionTopSites($employeeIds, $dateFilter, $range, true);
        $topTools  = $toolSites->take(8);

        $sessionRows = \App\Models\ExtensionSession::whereIn('user_id', $employeeIds)
            ->when($dateFilter && $range === 'today', fn($q) => $q->whereDate('started_at', $dateFilter))
            ->when($dateFilter && $range !== 'today', fn($q) => $q->where('started_at', '>=', $dateFilter))
            ->select('user_id', 'platform_domain', 'active_ms', 'session_id_from_ext')
            ->get();

        $frictionData = $sessionRows
            ->groupBy('platform_domain')
            ->map(fn($rows, $domain) => [
                'domain'    => $domain,
                'frequency' => $rows->count(),
                'avg_ms'    => $rows->avg('active_ms'),
            ])
            ->filter(fn($d) => $d['domain'] !== 'Unknown' && $d['domain'] !== '')
            ->sortByDesc('frequency')
            ->take(5)
            ->values();

        $weeklyActivity = [];
        if ($range === '30days' || $range === 'all') {
            $weeks = \App\Models\ExtensionSession::whereIn('user_id', $employeeIds)
                ->when($dateFilter, fn($q) => $q->where('started_at', '>=', $dateFilter))
                ->selectRaw('YEARWEEK(started_at, 1) as yw, SUM(active_ms) as total_ms')
                ->groupBy('yw')
                ->orderBy('yw')
                ->get();
            $weeklyActivity = $weeks->map(fn($w, $i) => [
                'label'    => 'W' . ($i + 1),
                'total_ms' => (int) $w->total_ms,
                'hours'    => round($w->total_ms / 3600000, 1),
            ])->values()->toArray();
        }

        $learnerProfiles = $employees->map(function ($emp) use ($effectiveMsByUser, $sessionRows, $ms, $dateFilter, $range) {
            $activeMs    = $effectiveMsByUser[$emp->id] ?? 0;
            $empSessions = $sessionRows->where('user_id', $emp->id);
            $topDomains  = $empSessions->groupBy('platform_domain')
                ->map(fn($rows) => (int) $rows->sum('active_ms'))
                ->sortDesc()->take(3)->keys()->values()->toArray();

            $lastSession = \App\Models\ExtensionSession::where('user_id', $emp->id)
                ->latest('started_at')->first();

            $engagementScore = min(100, (int) round(($activeMs / max(1, 3600000)) * 60));

            return [
                'id'               => $emp->id,
                'name'             => $emp->name,
                'email'            => $emp->email,
                'department'       => $emp->department?->name ?? 'No Group',
                'active_ms'        => $activeMs,
                'active_label'     => $ms($activeMs),
                'session_count'    => $empSessions->pluck('session_id_from_ext')->unique()->count(),
                'top_domains'      => $topDomains,
                'engagement_score' => $engagementScore,
                'last_seen'        => $lastSession?->started_at?->diffForHumans() ?? 'Never',
            ];
        })->sortByDesc('active_ms')->values();

        $sortedMs  = $learnerProfiles->pluck('active_ms')->sort()->values();
        $medianMs  = $sortedMs->count() ? $sortedMs[$sortedMs->count() >> 1] : 0;
        $medianLabel = $ms($medianMs);

        $reportDate = now()->format('d M Y');

        $pdf = Pdf::loadView('team.inspector-report-pdf', compact(
            'user', 'departments', 'dept', 'employees',
            'totalLearners', 'activeLearners', 'totalActiveHours',
            'funnel', 'topTools', 'frictionData', 'weeklyActivity',
            'learnerProfiles', 'medianLabel', 'reportDate',
            'range', 'deptId', 'ms'
        ));

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'Helvetica',
            'isRemoteEnabled' => false,
            'isHtml5ParserEnabled' => true,
        ]);

        $group = $dept?->name ?? 'All-Groups';
        $filename = "inspector-report-{$group}-{$reportDate}.pdf";

        return $pdf->download($filename);
    }

    public function reportData(Request $request)
    {
        $user = Auth::user();
        [$range, $dateFilter] = $this->resolveRange($request);
        $type       = $request->get('type', 'group-tools');
        $deptId     = $request->get('dept_id');
        $employeeId = $request->get('employee_id');

        $rows = $this->buildReportRows($user, $type, $range, $dateFilter, $deptId, $employeeId);

        return view('team.partials.report-data', compact('rows', 'type'));
    }

    public function reportDownload(Request $request)
    {
        $user = Auth::user();
        [$range, $dateFilter] = $this->resolveRange($request);
        $type       = $request->get('type', 'group-tools');
        $deptId     = $request->get('dept_id');
        $employeeId = $request->get('employee_id');

        $rows = $this->buildReportRows($user, $type, $range, $dateFilter, $deptId, $employeeId);

        $msLabel = function ($v) {
            $v = max(0, intval($v));
            if ($v < 60000) return round($v / 1000) . 's';
            $m = floor($v / 60000);
            if ($m < 60) return $m . 'm';
            return floor($m / 60) . 'h ' . ($m % 60) . 'm';
        };

        $filename = $type . '-report-' . now()->format('Y-m-d') . '.csv';
        $headers  = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=\"{$filename}\""];

        $callback = function () use ($rows, $type, $msLabel) {
            $out = fopen('php://output', 'w');

            if (in_array($type, ['group-tools', 'group-sites'])) {
                fputcsv($out, ['Group', 'Members', 'Rank', str_contains($type, 'tools') ? 'Tool' : 'Domain', 'Category', 'AI Tool', 'Time Spent', 'Sessions', 'Usage %']);
                foreach ($rows as $group) {
                    foreach ($group['sites'] as $i => $site) {
                        $pct = $group['total_ms'] > 0 ? round(($site->active_ms / $group['total_ms']) * 100, 1) : 0;
                        fputcsv($out, [
                            $group['dept'],
                            $group['members'],
                            '#' . ($i + 1),
                            str_contains($type, 'tools') ? ($site->tool_name ?? $site->domain) : $site->domain,
                            $site->category ?: 'General',
                            $site->is_ai_tool ? 'Yes' : 'No',
                            $msLabel($site->active_ms),
                            $site->sessions_count,
                            $pct . '%',
                        ]);
                    }
                }
            } else {
                fputcsv($out, ['Student', 'Group', 'Rank', str_contains($type, 'tools') ? 'Tool' : 'Domain', 'Category', 'AI Tool', 'Time Spent', 'Sessions']);
                foreach ($rows as $row) {
                    foreach ($row['sites'] as $i => $site) {
                        fputcsv($out, [
                            $row['name'],
                            $row['dept'],
                            '#' . ($i + 1),
                            str_contains($type, 'tools') ? ($site->tool_name ?? $site->domain) : $site->domain,
                            $site->category ?: 'General',
                            $site->is_ai_tool ? 'Yes' : 'No',
                            $msLabel($site->active_ms),
                            $site->sessions_count,
                        ]);
                    }
                }
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function buildReportRows(User $user, string $type, string $range, $dateFilter, ?string $deptId = null, ?string $employeeId = null): array
    {
        $toolsOnly = in_array($type, ['group-tools', 'student-tools']);
        $rows = [];

        if (in_array($type, ['group-tools', 'group-sites'])) {
            $departments = Department::where('company_id', $user->id)
                ->when($deptId, fn ($query) => $query->where('id', $deptId))
                ->get();

            foreach ($departments as $dept) {
                $memberIds = User::where('department_id', $dept->id)
                    ->where('parent_id', $user->id)->pluck('id');
                if ($memberIds->isEmpty()) continue;

                $sites = $this->sessionTopSites($memberIds, $dateFilter, $range, false);
                if ($toolsOnly) {
                    $sites = $sites->filter(fn($s) => $s->is_ai_tool || ($s->category && $s->category !== 'General'))->values();
                }

                $rows[] = [
                    'dept'     => $dept->name,
                    'members'  => $memberIds->count(),
                    'total_ms' => (int) $sites->sum('active_ms'),
                    'sites'    => $sites->take(10)->values()->all(),
                ];
            }
        } else {
            $query = User::where('parent_id', $user->id)->with('department');
            if ($deptId) {
                $query->where('department_id', $deptId);
            }
            if ($employeeId) {
                $query->where('id', $employeeId);
            }
            $students = $query->orderBy('name')->get();

            foreach ($students as $student) {
                $sites = $this->sessionTopSites(collect([$student->id]), $dateFilter, $range, false);
                if ($toolsOnly) {
                    $sites = $sites->filter(fn($s) => $s->is_ai_tool || ($s->category && $s->category !== 'General'))->values();
                }
                if ($sites->isEmpty()) continue;

                $rows[] = [
                    'name'  => $student->name,
                    'dept'  => $student->department?->name ?? 'No Group',
                    'sites' => $sites->take(10)->values()->all(),
                ];
            }
        }

        return $rows;
    }

    private function generateUniqueCode()

    {
        do {
            $code = 'EMP-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        } while (User::where('connection_code', $code)->exists());

        return $code;
    }

    private function sendEmployeeInviteEmail(User $employee, string $connectionCode, bool $isRegenerated = false): void
    {
        $manager = Auth::user();
        $setupUrl = route('extension.install');
        $chromeExtensionUrl = 'https://chromewebstore.google.com/detail/daleel-mentor/bpkbkfdbanbdlfmkmgcdkhlobfdifhpi';
        $desktopAppUrl = 'https://evalia-audio-storage.s3.us-east-1.amazonaws.com/DaleelMentorSetup-0.1.0-x64.exe';

        Mail::send('emails.student-invite', [
            'studentName' => $employee->name,
            'managerName' => $manager?->name ?? 'Your manager',
            'connectionCode' => $connectionCode,
            'setupUrl' => $setupUrl,
            'chromeExtensionUrl' => $chromeExtensionUrl,
            'desktopAppUrl' => $desktopAppUrl,
            'isRegenerated' => $isRegenerated,
        ], function ($message) use ($employee, $isRegenerated) {
            $message->to($employee->email, $employee->name)
                ->subject($isRegenerated ? 'Your new Daleel Mentor connection key' : 'Your Daleel Mentor connection key');
        });
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
                    'tool_name' => $this->toolNameForDomain($domain),
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

    private function toolNameForDomain(?string $domain): ?string
    {
        $domain = strtolower((string) $domain);

        $knownTools = [
            'chat.openai.com' => 'ChatGPT',
            'chatgpt.com' => 'ChatGPT',
            'openai.com' => 'OpenAI',
            'excel.office.com' => 'Excel',
            'office.com' => 'Microsoft Office',
            'microsoft365.com' => 'Microsoft 365',
            'docs.google.com' => 'Google Docs',
            'sheets.google.com' => 'Google Sheets',
            'notion.so' => 'Notion',
            'slack.com' => 'Slack',
            'figma.com' => 'Figma',
            'canva.com' => 'Canva',
            'trello.com' => 'Trello',
            'asana.com' => 'Asana',
            'jira' => 'Jira',
            'github.com' => 'GitHub',
            'gitlab.com' => 'GitLab',
            'stackoverflow.com' => 'Stack Overflow',
            'w3schools.com' => 'W3Schools',
            'zapier.com' => 'Zapier',
            'make.com' => 'Make',
            'teams.microsoft.com' => 'Microsoft Teams',
            'meet.google.com' => 'Google Meet',
            'zoom.us' => 'Zoom',
            'gmail.com' => 'Gmail',
            'mail.google.com' => 'Gmail',
        ];

        foreach ($knownTools as $needle => $label) {
            if (str_contains($domain, $needle)) {
                return $label;
            }
        }

        return null;
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
