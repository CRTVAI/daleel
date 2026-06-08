<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BrowsingHistory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the admin dashboard.
     */
    public function adminIndex(Request $request)
    {
        $user = Auth::user();
        
        // Paginated Users with Deep Analytics Data
        $users = User::where('is_admin', false)
            ->with(['videoProgress.content'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Aggregate Data for Charts
        $categoryStats = \App\Models\Content::select('category', \DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get();

        $totalViews = \App\Models\UserVideoProgress::count();
        $avgCompletion = \App\Models\UserVideoProgress::avg('completion_percent') ?? 0;
        $totalWatchTime = \App\Models\UserVideoProgress::sum('watched_seconds');
        $uniqueViewers = \App\Models\UserVideoProgress::distinct('user_id')->count();

        // Old Browsing History Stats (Keeping for compatibility)
        $total_records = BrowsingHistory::count();
        $recent_activity = BrowsingHistory::with('user')->orderBy('timestamp', 'desc')->limit(10)->get();

        return view('admin-dashboard', compact(
            'users', 
            'categoryStats', 
            'totalViews', 
            'avgCompletion', 
            'totalWatchTime', 
            'uniqueViewers',
            'total_records',
            'recent_activity'
        ));
    }

    /**
     * Show the user dashboard.
     */
    public function userIndex(Request $request)
    {
        $user = Auth::user();

        // Featured content for hero
        $featured = \App\Models\Content::where('is_featured', true)->latest()->first();

        // Shorts
        $shorts = \App\Models\Content::where('type', 'short')->active()->latest()->limit(12)->get();

        $continueWatching = $user->videoProgress()
            ->has('content')
            ->with('content')
            ->where('completed', false)
            ->where('watched_seconds', '>', 0)
            ->orderByDesc('last_watched_at')
            ->limit(10)
            ->get();

        // Personalized feed: match user interests to content categories
        $recommended = \App\Models\Content::forUser($user)
            ->where('type', 'video')
            ->where('is_featured', false)
            ->whereNotIn('id', $user->videoProgress()->pluck('content_id'))
            ->latest()
            ->limit(12)
            ->get();

        // Fallback: if no interests set or no matching content, show latest active
        if ($recommended->isEmpty()) {
            $recommended = \App\Models\Content::where('type', 'video')->where('is_featured', false)->active()->latest()->limit(12)->get();
        }

        // --- Behavioral Recommendations based on user Browsing History ---
        $visitedDomains = \App\Models\BrowsingHistory::where('user_id', $user->id)
            ->distinct()
            ->pluck('domain')
            ->toArray();

        $domainToToolsMap = [
            'chatgpt.com' => ['chatgpt', 'openai'],
            'youtube.com' => ['youtube'],
            'slack.com' => ['slack'],
            'notion.so' => ['notion'],
            'github.com' => ['github'],
            'figma.com' => ['figma'],
            'canva.com' => ['canva'],
            'linkedin.com' => ['linkedin'],
            'gmail.com' => ['gmail', 'google'],
            'sheets.google.com' => ['sheets', 'google sheets'],
            'instagram.com' => ['instagram'],
            'facebook.com' => ['facebook', 'meta'],
        ];

        $behaviorTags = [];
        foreach ($visitedDomains as $domain) {
            $domainClean = strtolower(trim($domain));
            if (isset($domainToToolsMap[$domainClean])) {
                $behaviorTags = array_merge($behaviorTags, $domainToToolsMap[$domainClean]);
            }
        }
        $behaviorTags = array_unique($behaviorTags);

        $behaviorRecommended = collect();
        if (!empty($behaviorTags)) {
            $behaviorRecommended = \App\Models\Content::active()
                ->where('type', 'video')
                ->where(function($q) use ($behaviorTags) {
                    foreach ($behaviorTags as $tag) {
                        $q->orWhere('tags', 'like', "%{$tag}%")
                          ->orWhere('connected_tools', 'like', "%{$tag}%");
                    }
                })
                ->whereNotIn('id', $user->videoProgress()->pluck('content_id'))
                ->latest()
                ->limit(12)
                ->get();
        }

        if ($behaviorRecommended->isEmpty()) {
            $behaviorRecommended = \App\Models\Content::forUser($user)
                ->where('type', 'video')
                ->whereNotIn('id', $user->videoProgress()->pluck('content_id'))
                ->inRandomOrder()
                ->limit(12)
                ->get();
        }

        if ($behaviorRecommended->isEmpty()) {
            $behaviorRecommended = \App\Models\Content::active()
                ->where('type', 'video')
                ->whereNotIn('id', $user->videoProgress()->pluck('content_id'))
                ->latest()
                ->limit(12)
                ->get();
        }

        // Stats
        $totalWatchSeconds  = $user->totalWatchSeconds();
        $completedCount     = $user->completedVideosCount();
        $inProgressCount    = $user->videoProgress()->where('completed', false)->where('watched_seconds', '>', 0)->count();

        // Extension Metrics
        $latestSnapshot = \App\Models\ExtensionMetricsSnapshot::where('user_id', $user->id)
            ->latest('captured_at')
            ->first();
            
        $focusScore = $latestSnapshot ? $latestSnapshot->focus_score : 0;
        $productivityScore = $latestSnapshot ? $latestSnapshot->productivity_score : 0;
        
        $todayActiveMs = \App\Models\ExtensionSession::where('user_id', $user->id)
            ->whereDate('started_at', today())
            ->sum('active_ms');
        
        $extensionStats = [
            'focus_score' => $focusScore,
            'productivity_score' => $productivityScore,
            'active_hours_today' => round($todayActiveMs / (1000 * 60 * 60), 1)
        ];

        // Learning Activity Chart Data (last 7 days)
        $dailyActivity = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $seconds = $user->videoProgress()
                ->whereDate('updated_at', $date)
                ->sum('watched_seconds'); 
            
            $dailyActivity[] = [
                'day' => now()->subDays($i)->format('D'),
                'minutes' => round($seconds / 60)
            ];
        }

        // Active Courses
        $courses = \App\Models\Course::where('status', 'active')->latest()->limit(8)->get();

        // Connected Tools ranked by dynamic usage scores from user browsing history & sessions
        $allTools = \App\Models\Tool::where('status', 'active')->get();
        
        $sessionStats = \App\Models\ExtensionSession::where('user_id', $user->id)
            ->selectRaw('platform_domain as domain, sum(active_ms) as total_active_ms')
            ->groupBy('platform_domain')
            ->get()
            ->keyBy(function($item) {
                return strtolower(trim($item->domain));
            });

        $browsingStats = \App\Models\BrowsingHistory::where('user_id', $user->id)
            ->selectRaw('domain, sum(duration) as total_duration')
            ->groupBy('domain')
            ->get()
            ->keyBy(function($item) {
                return strtolower(trim($item->domain));
            });

        $toolDomainMap = [
            'ChatGPT' => ['chatgpt.com'],
            'Notion'  => ['notion.so', 'notion.com'],
            'Slack'   => ['slack.com'],
            'Zapier'  => ['zapier.com'],
            'Gmail'   => ['gmail.com', 'mail.google.com'],
            'YouTube' => ['youtube.com'],
            'GitHub'  => ['github.com'],
            'Figma'   => ['figma.com'],
        ];

        $toolsWithScores = $allTools->map(function($tool) use ($sessionStats, $browsingStats, $toolDomainMap) {
            $totalSeconds = 0;
            $toolName = $tool->name;
            $mappedDomains = $toolDomainMap[$toolName] ?? [strtolower($toolName) . '.com'];

            foreach ($mappedDomains as $domain) {
                if (isset($sessionStats[$domain])) {
                    $totalSeconds += ($sessionStats[$domain]->total_active_ms / 1000);
                }
                if (isset($browsingStats[$domain])) {
                    $totalSeconds += $browsingStats[$domain]->total_duration;
                }
            }

            $tool->usage_seconds = $totalSeconds;
            $tool->usage_score = $totalSeconds; // Alias for safety
            return $tool;
        });

        $hasTrackedUsage = $toolsWithScores->sum('usage_seconds') > 0;
        $hasBrowsingHistory = \App\Models\BrowsingHistory::where('user_id', $user->id)->exists() || 
                              \App\Models\ExtensionSession::where('user_id', $user->id)->exists();

        if ($hasTrackedUsage) {
            // Get tools actually used (usage_seconds > 0), sorted descending
            $usedTools = $toolsWithScores->filter(function($tool) {
                return $tool->usage_seconds > 0;
            })->sortByDesc('usage_seconds')->values();

            // Get tools NOT used, sorted alphabetically
            $unusedTools = $toolsWithScores->filter(function($tool) {
                return $tool->usage_seconds == 0;
            })->sortBy('name')->values();

            // Merge them so used ones are first, and the rest are related unused tools, capped at exactly 6
            $connectedTools = $usedTools->merge($unusedTools)->take(6)->values();
        } else {
            // Fallback for new accounts: show top 6 active tools by default
            $connectedTools = $allTools->take(6)->values();
        }

        return view('user-dashboard', compact(
            'featured',
            'shorts',
            'continueWatching',
            'recommended',
            'courses',
            'totalWatchSeconds',
            'completedCount',
            'inProgressCount',
            'dailyActivity',
            'extensionStats',
            'connectedTools',
            'behaviorRecommended',
            'hasBrowsingHistory',
            'hasTrackedUsage'
        ))->with([
            'learningGoals' => \App\Models\LearningGoal::orderBy('title')->get(),
            'experienceLevels' => \App\Models\ExperienceLevel::orderBy('title')->get(),
            'tools' => \App\Models\Tool::where('status', 'active')->orderBy('name')->get(),
            'interestsList' => \App\Models\Content::distinct()->whereNotNull('category')->pluck('category')->toArray(),
        ]);
    }

    public function activityHistory(Request $request)
    {
        $user = Auth::user();
        $query = BrowsingHistory::query()->with('user');

        // Enforcement: Non-admins only see their own data
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        } else {
            // Admin filters
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }

        // Common filters
        if ($request->filled('domain')) {
            $query->where('domain', $request->domain);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function($q) use ($s) {
                $q->where('title', 'like', "%$s%")
                  ->orWhere('url', 'like', "%$s%")
                  ->orWhere('domain', 'like', "%$s%")
                  ->orWhere('search_query', 'like', "%$s%");
            });
        }

        // Capture filtered count before pagination
        $filtered_count = $query->count();
        
        // Final results
        $history = $query->orderBy('timestamp', 'desc')->paginate(50)->withQueryString();

        // Data for dropdowns (only non-admins)
        $users = User::where('is_admin', false)->orderBy('name')->get();
        $domains = BrowsingHistory::when(!$user->is_admin, fn($q) => $q->where('user_id', $user->id))
            ->distinct()->pluck('domain')->sort();

        return view('activity-history', compact('history', 'filtered_count', 'users', 'domains'));
    }

    /**
     * Show the extension setup page.
     */
    public function extensionSetup(Request $request)
    {
        $user = Auth::user();
        $devices = \App\Models\ExtensionDevice::where('user_id', $user->id)
            ->whereNull('revoked_at')
            ->orderBy('last_active_at', 'desc')
            ->get();

        // Data Viewer data (embedded in setup page)
        $sessions = \App\Models\ExtensionSession::where('user_id', $user->id)
            ->orderBy('started_at', 'desc')->get();

        $snapshots = \App\Models\ExtensionMetricsSnapshot::where('user_id', $user->id)
            ->orderBy('captured_at', 'desc')->get();

        $rollups = \App\Models\ExtensionDailyRollup::where('user_id', $user->id)
            ->orderBy('date', 'desc')->get();

        $recommendations = \App\Models\ExtensionRecommendation::where('user_id', $user->id)
            ->with(['events' => fn($q) => $q->orderBy('occurred_at', 'desc')])
            ->orderBy('created_at', 'desc')->get();

        $helpRequestsQuery = \App\Models\ExtensionHelpRequest::query();
        
        if (!$user->is_admin) {
            $helpRequestsQuery->where('user_id', $user->id);
        }
        
        $helpRequests = $helpRequestsQuery->with('user')->orderBy('created_at', 'desc')->get();

        $todayActiveMs = \App\Models\ExtensionSession::where('user_id', $user->id)
            ->whereDate('started_at', today())->sum('active_ms');

        $domainGroups = $sessions
            ->groupBy(fn($s) => $s->platform_domain ?: 'Unknown')
            ->map(fn($g, $d) => [
                'domain'    => $d,
                'count'     => $g->count(),
                'active_ms' => $g->sum('active_ms'),
                'ai'        => (bool) $g->where('is_ai_tool', true)->count(),
                'category'  => $g->first()->platform_category ?? null,
                'sessions'  => $g->sortByDesc('started_at')->values(),
            ])->sortByDesc('count')->values();

        $uniqueRecommendedCount = $recommendations->unique('content_id')->count();

        return view('extension-setup', compact(
            'devices', 'sessions', 'snapshots', 'rollups', 'recommendations',
            'helpRequests', 'todayActiveMs', 'domainGroups', 'uniqueRecommendedCount'
        ));
    }

    /**
     * Show the raw extension data (Sessions and Metrics).
     */
    public function extensionData()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        $sessions = \App\Models\ExtensionSession::where('user_id', $user->id)
            ->orderBy('started_at', 'desc')
            ->get();
            
        $snapshots = \App\Models\ExtensionMetricsSnapshot::where('user_id', $user->id)
            ->orderBy('captured_at', 'desc')
            ->get();

        $rollups = \App\Models\ExtensionDailyRollup::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        $recommendations = \App\Models\ExtensionRecommendation::where('user_id', $user->id)
            ->with(['events' => function($q) {
                $q->orderBy('occurred_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Today's active time only
        $todayActiveMs = \App\Models\ExtensionSession::where('user_id', $user->id)
            ->whereDate('started_at', today())
            ->sum('active_ms');

        // Domain-grouped sessions for Sessions tab
        $domainGroups = $sessions
            ->groupBy(fn($s) => $s->platform_domain ?: 'Unknown')
            ->map(fn($g, $d) => [
                'domain'    => $d,
                'count'     => $g->count(),
                'active_ms' => $g->sum('active_ms'),
                'ai'        => (bool) $g->where('is_ai_tool', true)->count(),
                'category'  => $g->first()->platform_category ?? null,
                'sessions'  => $g->sortByDesc('started_at')->values(),
            ])
            ->sortByDesc('count')
            ->values();

        // Unique content recommendations (deduplicated)
        $uniqueRecommendedCount = $recommendations->unique('content_id')->count();
            
        return view('extension-data', compact(
            'sessions', 'snapshots', 'rollups', 'recommendations',
            'todayActiveMs', 'domainGroups', 'uniqueRecommendedCount'
        ));
    }

    /**
     * Delete all extension tracked data (Sessions, Snapshots, Rollups, Recs) for the logged-in user.
     */
    public function resetExtensionData()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Delete Recommendation Events
        $recIds = \App\Models\ExtensionRecommendation::where('user_id', $user->id)->pluck('id');
        \App\Models\ExtensionRecommendationEvent::whereIn('ext_recommendation_id', $recIds)->delete();

        // Delete Recommendations
        \App\Models\ExtensionRecommendation::where('user_id', $user->id)->delete();

        // Delete Daily Rollups
        \App\Models\ExtensionDailyRollup::where('user_id', $user->id)->delete();

        // Delete Snapshots
        \App\Models\ExtensionMetricsSnapshot::where('user_id', $user->id)->delete();

        // Delete Sessions
        \App\Models\ExtensionSession::where('user_id', $user->id)->delete();

        return redirect()->route('extension.data')->with('success', 'All browser extension data wiped successfully.');
    }

    public function revokeDevice(\App\Models\ExtensionDevice $device)
    {
        if ($device->user_id !== auth()->id()) {
            abort(403);
        }
        $device->update(['revoked_at' => now()]);
        return response()->json(['data' => ['unlinked' => true]]);
    }
}
