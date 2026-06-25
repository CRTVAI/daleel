<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Course;
use App\Models\UserVideoProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['watch', 'explore']);
    }

    /**
     * Show the in-platform video player for a given content.
     */
    public function watch(Request $request, Content $content)
    {
        $user = Auth::user();
        $query = $request->get('query');
        $progress = null;

        if ($user) {
            // Get or create progress record
            $progress = UserVideoProgress::firstOrCreate(
                ['user_id' => $user->id, 'content_id' => $content->id],
                ['watched_seconds' => 0, 'duration_seconds' => $content->duration_seconds ?? 0, 'completion_percent' => 0]
            );
        }

        // If part of a course, get course context
        $course = null;
        if ($content->course_id) {
            $course = $content->course()->with('contents')->first();
        }

        // Recommended videos
        $recommended = collect();

        // If coming from AI Mentor with a query, search for related videos
        if ($query) {
            $keywords = array_filter(explode(' ', strtolower($query)), function($k) {
                return strlen($k) > 2;
            });

            $recommended = Content::active()
                ->where('id', '!=', $content->id)
                ->where(function ($q) use ($query, $keywords) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('tags', 'like', "%{$query}%");
                    
                    foreach ($keywords as $word) {
                        $q->orWhere('title', 'like', "%{$word}%")
                          ->orWhere('tags', 'like', "%{$word}%");
                    }
                })
                ->limit(10)
                ->get();
        }

        // Fallback recommendations
        if ($recommended->isEmpty()) {
            if ($user) {
                $watchedIds = $user->videoProgress()->pluck('content_id');
                $recommended = Content::active()
                    ->where('category_id', $content->category_id)
                    ->where('id', '!=', $content->id)
                    ->whereNotIn('id', $watchedIds)
                    ->limit(5)
                    ->get();
            }

            if ($recommended->isEmpty()) {
                $recommended = Content::active()
                    ->where(function($q) use ($content) {
                        $q->where('category_id', $content->category_id);
                        if ($content->tags) $q->orWhere('tags', 'like', "%{$content->tags}%");
                    })
                    ->where('id', '!=', $content->id)
                    ->inRandomOrder()
                    ->limit(5)
                    ->get();
            }

            // Final safety fallback: just get any active videos if still empty
            if ($recommended->isEmpty()) {
                $recommended = Content::active()
                    ->where('id', '!=', $content->id)
                    ->inRandomOrder()
                    ->limit(5)
                    ->get();
            }
        }

        return view('learn.watch', compact('content', 'progress', 'recommended', 'course'));
    }

    /**
     * Show Course detail and curriculum.
     */
    public function courseView(Course $course)
    {
        $course->load('contents');
        return view('learn.course-view', compact('course'));
    }

    /**
     * Save video progress via AJAX (called by the YouTube IFrame API).
     */
    public function saveProgress(Request $request)
    {
        $request->validate([
            'content_id'      => 'required|exists:contents,id',
            'watched_seconds' => 'required|integer|min:0',
            'duration_seconds'=> 'required|integer|min:0',
        ]);

        $user = Auth::user();
        $watched  = (int) $request->watched_seconds;
        $duration = (int) $request->duration_seconds;
        $percent  = min(100, round(($watched / $duration) * 100, 2));
        $completed = $percent >= 90; // ≥90% counts as completed

        UserVideoProgress::updateOrCreate(
            ['user_id' => $user->id, 'content_id' => $request->content_id],
            [
                'watched_seconds'  => $watched,
                'duration_seconds' => $duration,
                'completion_percent' => $percent,
                'completed'        => $completed,
                'last_watched_at'  => now(),
            ]
        );

        $user->recordActivity();

        return response()->json(['status' => 'ok', 'completion_percent' => $percent]);
    }

    public function explore(Request $request)
    {
        // --- SMART REDIRECT: Catch old search requests and upgrade to AI Search ---
        if ($request->filled('search')) {
            return redirect()->route('search.advanced', ['search' => $request->search]);
        }

        $user = Auth::user();
        $type = $request->get('type', 'video'); // default to videos
        
        // 1. Get Recommendations (Most watched globally among user's most used tools)
        $sessionStats = collect();
        $browsingStats = collect();

        if ($user) {
            $sessionStats = \App\Models\ExtensionSession::where('user_id', $user->id)
                ->selectRaw('platform_domain as domain, sum(active_ms) as total_active_ms')
                ->groupBy('platform_domain')
                ->get()
                ->keyBy(fn($item) => strtolower(trim($item->domain)));

            $browsingStats = \App\Models\BrowsingHistory::where('user_id', $user->id)
                ->selectRaw('domain, sum(duration) as total_duration')
                ->groupBy('domain')
                ->get()
                ->keyBy(fn($item) => strtolower(trim($item->domain)));
        }

        $allTools = \App\Models\Tool::where('status', 'active')->get();
        $toolDomainMap = [
            'ChatGPT' => ['chatgpt', 'chatgpt.com', 'openai'],
            'Notion'  => ['notion', 'notion.so', 'notion.com'],
            'Slack'   => ['slack', 'slack.com'],
            'Zapier'  => ['zapier', 'zapier.com'],
            'Gmail'   => ['gmail', 'gmail.com', 'mail.google.com'],
            'YouTube' => ['youtube', 'youtube.com'],
            'GitHub'  => ['github', 'github.com'],
            'Figma'   => ['figma', 'figma.com'],
        ];

        $toolsWithScores = $allTools->map(function($tool) use ($sessionStats, $browsingStats, $toolDomainMap) {
            $totalSeconds = 0;
            $toolName = strtolower($tool->name);
            $mappedDomains = $toolDomainMap[$tool->name] ?? [$toolName . '.com'];

            foreach ($mappedDomains as $domain) {
                if (isset($sessionStats[$domain])) {
                    $totalSeconds += ($sessionStats[$domain]->total_active_ms / 1000);
                }
                if (isset($browsingStats[$domain])) {
                    $totalSeconds += $browsingStats[$domain]->total_duration;
                }
            }
            $tool->usage_seconds = $totalSeconds;
            return $tool;
        });

        // Get names of tools used by this specific user
        $usedToolNames = $toolsWithScores->filter(fn($t) => $t->usage_seconds > 0)
            ->sortByDesc('usage_seconds')
            ->map(fn($t) => strtolower($t->name))
            ->toArray();

        // Get most watched video content IDs globally
        $popularContentIds = \App\Models\UserVideoProgress::selectRaw('content_id, count(*) as watch_count')
            ->groupBy('content_id')
            ->orderByDesc('watch_count')
            ->pluck('content_id')
            ->toArray();

        $recommendedItems = collect();

        if ($user && !empty($usedToolNames)) {
            // Find active videos connected to the user's top used tools that they haven't watched yet
            $matchedContents = Content::active()
                ->where('type', 'video')
                ->where(function($q) use ($usedToolNames) {
                    foreach ($usedToolNames as $toolName) {
                        $q->orWhere('tags', 'like', "%{$toolName}%")
                          ->orWhere('connected_tools', 'like', "%{$toolName}%");
                    }
                })
                ->whereNotIn('id', $user->videoProgress()->pluck('content_id'))
                ->get();

            // Sort matched videos by global popularity (most watched globally first)
            $recommendedItems = $matchedContents->sortBy(function($content) use ($popularContentIds) {
                $pos = array_search($content->id, $popularContentIds);
                return $pos === false ? 999999 : $pos;
            })->values()->take(20);
        }

        // Fallback/Padded items to guarantee 20 recommendations: match declared onboarding interests sorted by popularity
        if ($recommendedItems->count() < 20) {
            $interests = $user ? (array) ($user->interests ?? []) : [];
            $fallbackQuery = Content::active()
                ->where('type', 'video');
            
            if ($user) {
                $fallbackQuery->whereNotIn('id', $user->videoProgress()->pluck('content_id'));
            }

            if (!empty($interests)) {
                $fallbackQuery->where(function($q) use ($interests) {
                    foreach ($interests as $interest) {
                        $q->orWhere('category', 'like', "%{$interest}%")
                          ->orWhere('tags', 'like', "%{$interest}%");
                    }
                });
            }

            $fallbackItems = $fallbackQuery->get()->sortBy(function($content) use ($popularContentIds) {
                $pos = array_search($content->id, $popularContentIds);
                return $pos === false ? 999999 : $pos;
            })->values();

            $recommendedItems = $recommendedItems->merge($fallbackItems)->unique('id')->take(20)->values();
        }

        // Absolute fallback if still under 20 items: most popular videos overall
        if ($recommendedItems->count() < 20) {
            $allActiveVideos = Content::active()->where('type', 'video')->get();
            $absoluteFallback = $allActiveVideos->sortBy(function($content) use ($popularContentIds) {
                $pos = array_search($content->id, $popularContentIds);
                return $pos === false ? 999999 : $pos;
            })->values();

            $recommendedItems = $recommendedItems->merge($absoluteFallback)->unique('id')->take(20)->values();
        }

        // 2. All Items (Filtered, Ranked, and Paginated)
        $query = ($type === 'course') ? Course::where('status', 'active') : Content::active();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('level') && $type === 'video') {
            $query->where('skill_level', $request->level);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s, $type) {
                $q->where('title', 'like', "%$s%")
                  ->orWhere('description', 'like', "%$s%");

                // Only search Content-specific columns if we are searching videos
                if ($type === 'video') {
                    $q->orWhere('connected_tools', 'like', "%$s%")
                      ->orWhere('tags', 'like', "%$s%")
                      ->orWhere('language', 'like', "%$s%")
                      ->orWhere('video_url_ar', 'like', "%$s%");

                    $term = strtolower($s);
                    if ($term === 'arabic' || $term === 'ar') {
                        $q->orWhereIn('language', ['ar', 'both']);
                    } elseif ($term === 'english' || $term === 'en') {
                        $q->orWhereIn('language', ['en', 'both']);
                    }
                }
            });
        }

        // Retrieve and dynamically rank items (YouTube trending style based on usage and popularity)
        $itemsCollection = $query->get();

        if ($type === 'video') {
            $itemsCollection = $itemsCollection->sortBy(function($content) use ($usedToolNames, $popularContentIds) {
                // 1. Tool Usage Rank: smaller value = higher used tool = higher rank
                $toolRank = 999;
                if (!empty($usedToolNames) && $content->connected_tools) {
                    foreach ($usedToolNames as $index => $toolName) {
                        if (in_array($toolName, array_map('strtolower', (array)$content->connected_tools)) ||
                            str_contains(strtolower($content->tags), $toolName)) {
                            $toolRank = $index;
                            break;
                        }
                    }
                }

                // 2. Global Watch Rank: smaller value = more watched globally = higher rank
                $watchPos = array_search($content->id, $popularContentIds);
                $watchRank = $watchPos === false ? 999999 : $watchPos;

                // ToolRank takes first priority (weighted by millions), WatchRank takes second
                return ($toolRank * 1000000) + $watchRank;
            })->values();
        } else {
            // For courses, sort by course latest status
            $itemsCollection = $itemsCollection->sortByDesc('created_at')->values();
        }

        // Manual Laravel Pagination over sorted Collection
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 16;
        $currentPageItems = $itemsCollection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $items = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $itemsCollection->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath()]
        );
        $items->withQueryString();

        $categories = \App\Models\Category::active()->orderBy('name')->get();
        $connectedTools = \App\Models\Tool::where('status', 'active')->orderBy('name')->get();

        if (\Illuminate\Support\Facades\Route::currentRouteName() === 'videos.public') {
            return view('videos', compact('items', 'categories', 'type', 'recommendedItems', 'connectedTools'));
        }

        return view('learn.explore', compact('items', 'categories', 'type', 'recommendedItems', 'connectedTools'));
    }

    /**
     * Show the detailed learning roadmap.
     */
    public function roadmap()
    {
        $user = Auth::user();
        
        // 1. Get usage stats from extension for sorting
        $sessionStats = collect();
        $browsingStats = collect();

        if ($user) {
            $sessionStats = \App\Models\ExtensionSession::where('user_id', $user->id)
                ->selectRaw('platform_domain as domain, sum(active_ms) as total_active_ms')
                ->groupBy('platform_domain')
                ->get()
                ->keyBy(fn($item) => strtolower(trim($item->domain)));

            $browsingStats = \App\Models\BrowsingHistory::where('user_id', $user->id)
                ->selectRaw('domain, sum(duration) as total_duration')
                ->groupBy('domain')
                ->get()
                ->keyBy(fn($item) => strtolower(trim($item->domain)));
        }

        $allTools = \App\Models\Tool::where('status', 'active')->get();
        $toolDomainMap = [
            'ChatGPT' => ['chatgpt', 'chatgpt.com', 'openai'],
            'Notion'  => ['notion', 'notion.so', 'notion.com'],
            'Slack'   => ['slack', 'slack.com'],
            'Zapier'  => ['zapier', 'zapier.com'],
            'Gmail'   => ['gmail', 'gmail.com', 'mail.google.com'],
            'YouTube' => ['youtube', 'youtube.com'],
            'GitHub'  => ['github', 'github.com'],
            'Figma'   => ['figma', 'figma.com'],
        ];

        // 2. Map and score tools by usage
        $toolsWithScores = $allTools->map(function($tool) use ($sessionStats, $browsingStats, $toolDomainMap) {
            $totalSeconds = 0;
            $toolName = strtolower($tool->name);
            $mappedDomains = $toolDomainMap[$tool->name] ?? [$toolName . '.com'];

            foreach ($mappedDomains as $domain) {
                if (isset($sessionStats[$domain])) {
                    $totalSeconds += ($sessionStats[$domain]->total_active_ms / 1000);
                }
                if (isset($browsingStats[$domain])) {
                    $totalSeconds += $browsingStats[$domain]->total_duration;
                }
            }
            $tool->usage_seconds = $totalSeconds;
            return $tool;
        })->sortByDesc('usage_seconds');

        // 3. Build roadmap data based on sorted tools
        $roadmapData = $toolsWithScores->map(function($tool) use ($user) {
            $contents = Content::whereJsonContains('connected_tools', $tool->name)
                ->where('type', 'video')
                ->orderBy('created_at', 'asc')
                ->get();
            
            $totalCount = $contents->count();
            if ($totalCount === 0) return null;

            $completedIds = $user->videoProgress()->where('completed', true)->pluck('content_id')->toArray();
            $completedCount = $contents->whereIn('id', $completedIds)->count();
            
            $contents = $contents->map(function($content) use ($completedIds) {
                $content->is_completed = in_array($content->id, $completedIds);
                return $content;
            });

            return [
                'tool' => $tool,
                'contents' => $contents,
                'total' => $totalCount,
                'completed' => $completedCount,
                'percent' => round(($completedCount / $totalCount) * 100)
            ];
        })->filter()->values();

        // Overall stats
        $allContents = Content::where('type', 'video')->get();
        $totalLessons = $allContents->count();
        $lessonsCompleted = $user->videoProgress()->where('completed', true)->count();
        $overallProgress = $totalLessons > 0 ? round(($lessonsCompleted / $totalLessons) * 100) : 0;

        // Current Lesson (last one interacted with OR next in roadmap)
        $lastProgress = $user->videoProgress()->has('content')->with('content')->orderByDesc('last_watched_at')->first();
        
        if ($lastProgress) {
            $currentLesson = $lastProgress->content;
        } else {
            // Find the very first lesson of the first tool as a default
            $firstTool = $roadmapData->first();
            $currentLesson = $firstTool ? $firstTool['contents']->first() : Content::active()->first();
        }

        // Find the specific Tool model for the current lesson focus
        $currentTool = null;
        if ($currentLesson && !empty($currentLesson->connected_tools)) {
            $toolName = is_array($currentLesson->connected_tools) ? $currentLesson->connected_tools[0] : json_decode($currentLesson->connected_tools)[0] ?? null;
            if ($toolName) {
                $currentTool = \App\Models\Tool::where('name', $toolName)->first();
            }
        }

        return view('roadmap', compact('roadmapData', 'overallProgress', 'lessonsCompleted', 'totalLessons', 'currentLesson', 'currentTool'));
    }
}
