<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Content;
use App\Models\UserRoadmap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RoadmapController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Auto-detect tools and either initialize roadmap or check for pending tools
        $pendingData = $this->detectAndTrackPendingTools($user);

        $roadmaps = UserRoadmap::where('user_id', Auth::id())->latest()->get();
        $manualRoadmapsCount = $roadmaps->where('is_auto_generated', false)->count();

        // ── Build the full set of roadmap video IDs + lookup map ──────────
        $roadmapVideoIds   = collect();
        $roadmapIdByVideoId = []; // videoId => roadmapId
        foreach ($roadmaps as $rm) {
            $phases = $rm->curriculum['phases'] ?? $rm->curriculum ?? [];
            $ids = collect($phases)
                ->filter(fn($ph) => is_array($ph))
                ->flatMap(fn($ph) => collect($ph['videos'] ?? [])->pluck('id'));
            foreach ($ids as $vid) {
                $roadmapIdByVideoId[$vid] = $rm->id;
            }
            $roadmapVideoIds = $roadmapVideoIds->merge($ids);
        }
        $roadmapVideoIds = $roadmapVideoIds->unique()->values();

        // ── All started video progress records (include completed) ───────────
        $allProgress = \App\Models\UserVideoProgress::where('user_id', $user->id)
            ->where('completion_percent', '>', 0)
            ->orderByDesc('last_watched_at')
            ->with('content')
            ->get()
            ->filter(fn($p) => $p->content && $p->content->status === 'active');

        // ── Collect all active course IDs for this user's in-progress items ──
        // We identify course membership via the content's course_id column (if it exists)
        // or via the Course relationship.
        $courseMap = []; // content_id => course_id

        // Pre-load courses for all in-progress content
        $inProgressContentIds = $allProgress->pluck('content.id')->filter()->unique()->values();
        $courseContents = \App\Models\Course::whereHas('contents', fn($q) => $q->whereIn('contents.id', $inProgressContentIds))
            ->with(['contents' => fn($q) => $q->whereIn('contents.id', $inProgressContentIds)])
            ->get();
        foreach ($courseContents as $course) {
            foreach ($course->contents as $c) {
                $courseMap[$c->id] = $course->id;
            }
        }

        // Build a map of ALL content IDs belonging to ANY active course (for full course stats)
        $allCoursesForUser = \App\Models\Course::active()
            ->whereHas('contents', fn($q) => $q->whereIn('contents.id', $inProgressContentIds))
            ->with('contents')
            ->get();

        // ── CONTINUE COURSES ───────────────────────────────────────────────
        // Group in-progress videos by course and build course-level cards
        $continueCourses = collect();
        $processedCourseIds = [];

        foreach ($allProgress as $p) {
            $contentId = $p->content->id;
            $cid = $courseMap[$contentId] ?? null;
            if (!$cid || in_array($cid, $processedCourseIds)) continue;
            $processedCourseIds[] = $cid;

            $course = $allCoursesForUser->firstWhere('id', $cid);
            if (!$course) continue;

            // All videos in this course (ordered by sort_order)
            $allCourseVideos = $course->contents()->orderBy('sort_order')->get();
            $totalVideos = $allCourseVideos->count();
            if ($totalVideos === 0) continue;

            // Get progress for all videos in this course
            $courseVideoIds = $allCourseVideos->pluck('id');
            $courseProgressMap = \App\Models\UserVideoProgress::where('user_id', $user->id)
                ->whereIn('content_id', $courseVideoIds)
                ->get()->keyBy('content_id');

            $watchedCount = 0;
            $nextVideo = null;
            $lastWatchedAt = null;

            foreach ($allCourseVideos as $video) {
                $prog = $courseProgressMap->get($video->id);
                $isWatched = $prog && ($prog->completed || $prog->completion_percent >= 90);
                if ($isWatched) {
                    $watchedCount++;
                    if (!$lastWatchedAt || ($prog->last_watched_at && $prog->last_watched_at > $lastWatchedAt)) {
                        $lastWatchedAt = $prog->last_watched_at;
                    }
                } elseif (!$nextVideo) {
                    // This is the next video to watch
                    $nextVideo = $video;
                    if ($prog) {
                        $nextVideo->resume_seconds = (int) $prog->watched_seconds;
                        $nextVideo->completion_pct = round($prog->completion_percent);
                    } else {
                        $nextVideo->resume_seconds = 0;
                        $nextVideo->completion_pct = 0;
                    }
                }
            }

            $progressPct = $totalVideos > 0 ? round(($watchedCount / $totalVideos) * 100) : 0;

            $continueCourses->push([
                'course'        => $course,
                'total'         => $totalVideos,
                'watched'       => $watchedCount,
                'progress_pct'  => $progressPct,
                'next_video'    => $nextVideo,
                'last_watched_at' => $lastWatchedAt,
                'all_videos'    => $allCourseVideos,
                'progress_map'  => $courseProgressMap,
            ]);
        }

        // Sort courses by last activity
        $continueCourses = $continueCourses->sortByDesc('last_watched_at')->values();

        // ── ROADMAP WATCHING (individual video progress inside roadmaps) ──
        $roadmapWatching = $allProgress
            ->filter(fn($p) =>
                !isset($courseMap[$p->content->id]) &&
                $roadmapVideoIds->contains($p->content->id) &&
                $p->completion_percent > 0
            )
            ->take(24)
            ->map(function ($p) use ($roadmapIdByVideoId) {
                $content = $p->content;
                $content->resume_seconds       = (int) $p->watched_seconds;
                $content->completion_pct       = round($p->completion_percent);
                $content->last_watched_at      = $p->last_watched_at;
                $content->duration_label_local = $p->duration_seconds > 0
                    ? floor($p->duration_seconds / 60) . 'm'
                    : ($content->duration_label ?? '—');
                $content->roadmap_id = $roadmapIdByVideoId[$content->id] ?? null;
                $content->course_id  = null;
                return $content;
            })
            ->values();

        // ── CONTINUE WATCHING (individual only — no course, no roadmap) ────
        $continueWatching = $allProgress
            ->filter(fn($p) =>
                !isset($courseMap[$p->content->id]) &&
                !$roadmapVideoIds->contains($p->content->id) &&
                $p->completion_percent > 0
            )
            ->take(24)
            ->map(function ($p) {
                $content = $p->content;
                $content->resume_seconds       = (int) $p->watched_seconds;
                $content->completion_pct       = round($p->completion_percent);
                $content->last_watched_at      = $p->last_watched_at;
                $content->duration_label_local = $p->duration_seconds > 0
                    ? floor($p->duration_seconds / 60) . 'm'
                    : ($content->duration_label ?? '—');
                $content->roadmap_id = null;
                $content->course_id  = null;
                return $content;
            })
            ->values();

        return view('roadmap.index', compact(
            'roadmaps', 'pendingData', 'manualRoadmapsCount',
            'continueWatching', 'continueCourses', 'roadmapWatching'
        ));
    }

    public function show(UserRoadmap $roadmap)
    {
        if ($roadmap->user_id !== Auth::id()) abort(403);
        $user = Auth::user();

        // 1. Build phases
        $phases = $roadmap->curriculum['phases'] ?? $roadmap->curriculum;
        $phases = $this->healRoadmapCurriculum($roadmap, $phases);

        $allVideoIds = collect($phases)->filter(fn($p) => is_array($p))
            ->pluck('videos')->flatten(1)->pluck('id')->filter()->unique()->values();

        // 2. Load ALL progress records in ONE query, keyed by content_id
        $progressMap = $user->videoProgress()
            ->whereIn('content_id', $allVideoIds)
            ->get()->keyBy('content_id');

        // 3. Build roadmapData with rich progress per content
        $roadmapData = collect($phases)->map(function($phase) use ($progressMap) {
            if (!is_array($phase)) return null;
            $tool     = Tool::where('name', $phase['tool_name'] ?? null)->first();
            $videoIds = collect($phase['videos'])->pluck('id')->filter()->values();
            $contents = Content::whereIn('id', $videoIds)->get()
                ->sortBy(fn($content) => $videoIds->search($content->id))
                ->values()
                ->map(function($content) use ($progressMap) {
                    $p = $progressMap->get($content->id);
                    $duration = max((int) ($content->resolved_duration_seconds ?? 0), (int) ($p?->duration_seconds ?? 0));
                    $watched = min((int) ($p?->watched_seconds ?? 0), $duration > 0 ? $duration : (int) ($p?->watched_seconds ?? 0));
                    $completion = $duration > 0 ? min(100, round(($watched / $duration) * 100, 2)) : (float) ($p?->completion_percent ?? 0);
                    $isCompleted = ($p?->completed ?? false) || $completion >= 90;
                    if ($isCompleted && $duration > 0) {
                        $watched = $duration;
                        $completion = 100;
                    }

                    $content->progress_record = $p;
                    $content->duration_seconds = $duration;
                    $content->watched_seconds = $watched;
                    $content->remaining_seconds = max(0, $duration - $watched);
                    $content->completion_pct  = round($completion);
                    $content->is_completed    = $isCompleted;
                    $content->last_watched_at = $p?->last_watched_at;
                    return $content;
                });

            $totalCount     = $contents->count();
            if ($totalCount === 0) return null;
            $completedCount = $contents->where('is_completed', true)->count();
            $durationTotal  = $contents->sum('duration_seconds');
            $watchedTotal   = $contents->sum('watched_seconds');
            $phasePercent   = $durationTotal > 0
                ? round(($watchedTotal / $durationTotal) * 100)
                : round($contents->avg('completion_pct') ?? 0);

            return [
                'tool'      => $tool,
                'contents'  => $contents,
                'total'     => $totalCount,
                'completed' => $completedCount,
                'percent'   => min(100, $phasePercent),
                'duration_seconds' => $durationTotal,
                'watched_seconds' => $watchedTotal,
                'remaining_seconds' => max(0, $durationTotal - $watchedTotal),
            ];
        })->filter()->values();

        // 4. Stats
        $totalLessons          = $allVideoIds->count();
        $allContents           = $roadmapData->flatMap(fn($phase) => $phase['contents'])->unique('id')->values();
        $lessonsCompleted      = $allContents->where('is_completed', true)->count();
        $totalWatchedSeconds   = $allContents->sum('watched_seconds');
        $totalDurationSeconds  = $allContents->sum('duration_seconds');
        $remainingSeconds      = max(0, $totalDurationSeconds - $totalWatchedSeconds);
        $overallProgress       = $totalDurationSeconds > 0 ? min(100, round(($totalWatchedSeconds / $totalDurationSeconds) * 100)) : 0;

        $hasKnownDurations      = $totalDurationSeconds > 0;
        $formattedTotalDuration = $hasKnownDurations ? $this->formatDuration($totalDurationSeconds) : 'Duration pending';
        $formattedWatchedTime   = $this->formatDuration($totalWatchedSeconds);
        $formattedRemainingTime = $hasKnownDurations ? $this->formatDuration($remainingSeconds) : 'Pending';

        // 5. Sidebar lesson = the LAST WATCHED lesson (most recent last_watched_at)
        //    CTA lesson    = the NEXT lesson to watch (first incomplete in sequence)
        $currentLesson = null;
        $currentProgressRecord = null;
        $nextIncompleteLesson = null;

        // Find the last-watched lesson across all phases (by last_watched_at timestamp)
        $lastWatchedContent = null;
        $lastWatchedAt = null;
        foreach ($roadmapData as $phase) {
            foreach ($phase['contents'] as $content) {
                $watchedAt = $content->last_watched_at;
                if ($watchedAt && ($lastWatchedAt === null || $watchedAt > $lastWatchedAt)) {
                    $lastWatchedAt = $watchedAt;
                    $lastWatchedContent = $content;
                }
            }
        }

        // Find the first incomplete lesson for the CTA "Continue" button
        foreach ($roadmapData as $phase) {
            foreach ($phase['contents'] as $content) {
                if (!$content->is_completed) {
                    $nextIncompleteLesson = $content;
                    break 2;
                }
            }
        }

        // Sidebar shows last-watched lesson; fallback to first incomplete if nothing watched
        if ($lastWatchedContent) {
            $currentLesson = $lastWatchedContent;
            $currentProgressRecord = $progressMap->get($currentLesson->id);
        } elseif ($nextIncompleteLesson) {
            $currentLesson = $nextIncompleteLesson;
            $currentProgressRecord = $progressMap->get($currentLesson->id);
        } else {
            // All done — show first lesson
            $firstPhase = $roadmapData->first();
            $currentLesson = $firstPhase ? $firstPhase['contents']->first() : null;
            if ($currentLesson) {
                $currentProgressRecord = $progressMap->get($currentLesson->id);
            }
        }

        // CTA target: first incomplete, fallback to last-watched
        if (!$nextIncompleteLesson) {
            $nextIncompleteLesson = $currentLesson;
        }

        $currentTool = null;
        if ($currentLesson && !empty($currentLesson->connected_tools)) {
            $toolName = is_array($currentLesson->connected_tools)
                ? $currentLesson->connected_tools[0]
                : (json_decode($currentLesson->connected_tools)[0] ?? null);
            if ($toolName) $currentTool = Tool::where('name', $toolName)->first();
        }

        return view('roadmap', compact(
            'roadmapData', 'overallProgress', 'lessonsCompleted', 'totalLessons',
            'currentLesson', 'currentTool', 'currentProgressRecord',
            'totalWatchedSeconds', 'totalDurationSeconds', 'remainingSeconds', 'progressMap',
            'roadmap', 'formattedTotalDuration', 'formattedWatchedTime', 'formattedRemainingTime',
            'hasKnownDurations', 'nextIncompleteLesson'
        ));
    }

    private function formatDuration(int $seconds): string
    {
        $seconds = max(0, $seconds);
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m";
    }

    private function healRoadmapCurriculum(UserRoadmap $roadmap, array $phases): array
    {
        $changed = false;

        $healedPhases = collect($phases)->map(function($phase) use (&$changed) {
            if (!is_array($phase)) {
                return $phase;
            }

            $videos = collect($phase['videos'] ?? [])->filter(fn($video) => is_array($video))->values();
            if ($videos->isEmpty()) {
                return $phase;
            }

            $videoIds = $videos->pluck('id')->filter()->values();
            $existingCount = Content::whereIn('id', $videoIds)->count();
            if ($existingCount === $videoIds->count()) {
                return $phase;
            }

            $toolName = $phase['tool_name'] ?? null;
            if (!$toolName) {
                return $phase;
            }

            $replacementCount = max($videoIds->count(), 5);
            $replacementVideos = Content::whereJsonContains('connected_tools', $toolName)
                ->where('type', 'video')
                ->active()
                ->latest('id')
                ->get()
                ->unique('title')
                ->take($replacementCount)
                ->values();

            if ($replacementVideos->isEmpty()) {
                return $phase;
            }

            $phase['videos'] = $replacementVideos->map(fn($content) => [
                'id' => $content->id,
                'title' => $content->title,
                'thumbnail_url' => $content->thumbnail_url,
            ])->values()->all();

            $changed = true;

            return $phase;
        })->values()->all();

        if ($changed) {
            $curriculum = $roadmap->curriculum;
            if (is_array($curriculum) && array_key_exists('phases', $curriculum)) {
                $curriculum['phases'] = $healedPhases;
                $roadmap->update(['curriculum' => $curriculum]);
            } else {
                $roadmap->update(['curriculum' => $healedPhases]);
            }
        }

        return $healedPhases;
    }

    public function wizard(Request $request)
    {
        $goal = $request->input('query', $request->input('goal'));

        if (!$goal) return redirect()->route('learn.explore');

        // Check roadmap creation limit for Free users (limit: 2)
        $user = auth()->user();
        if ($user->account_type === 'Free Plan') {
            $existing = \App\Models\UserRoadmap::where('user_id', $user->id)->where('is_auto_generated', false)->count();
            if ($existing >= 2) {
                return redirect()->route('roadmap')->with('error', 'You have reached the limit of 2 roadmaps on the Free Plan. Please upgrade to Pro to create more.');
            }
        }

        // 1. Get all tools and AI matches (Initial state for Step 1)
        $allTools = Tool::active()->get(['id', 'name', 'description', 'logo']);
        $selectedIds = $this->askGptToMatchTools($goal, $allTools);

        return view('roadmap.wizard', [
            'goal' => $goal,
            'allTools' => $allTools,
            'selectedIds' => $selectedIds,
        ]);
    }

    /**
     * AJAX Helper to get focus categories based on selected tools.
     */
    public function getFocusCategories(Request $request)
    {
        $goal = $request->input('goal');
        $toolNames = $request->input('tool_names', []);

        // We can use GPT to generate 3 specific categories based on tools
        $prompt = "Goal: {$goal}. Tools: " . implode(', ', $toolNames) . ".
                   Generate exactly 3 short improvement categories for a learning roadmap.
                   Return ONLY a JSON array of 3 strings.";

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                    'temperature' => 0.6,
                ]);

            $categories = json_decode($response->json('choices.0.message.content'), true);
            if (!is_array($categories) || count($categories) < 3) throw new \Exception("Invalid GPT format");
        } catch (\Throwable $e) {
            // Fallback
            $categories = [
                'Core Productivity & Performance',
                'Advanced Features & Tools Usage',
                'Data Management & Analysis'
            ];
        }

        return response()->json($categories);
    }

    /**
     * Final Generation (AJAX/Fetch)
     */
    public function generateRoadmap(Request $request)
    {
        // Check roadmap creation limit for Free users
        $user = auth()->user();
        if ($user->account_type === 'Free Plan') {
            $existing = \App\Models\UserRoadmap::where('user_id', $user->id)->where('is_auto_generated', false)->count();
            if ($existing >= 2) {
                return response()->json([
                    'error' => 'limit_reached',
                    'message' => 'Free Plan limit reached! You can only create up to 2 roadmaps.',
                    'redirect_url' => url('/pricing')
                ], 403);
            }
        }

        $goal = $request->input('goal');
        $tools = $request->input('tools', []);
        $focus = $request->input('focus');
        $level = $this->normalizeLevel($user->experience_level);

        // 1. Clean the title with AI
        $title = $this->cleanTitleWithAi($goal);

        // 2. Build the curriculum
        $roadmapData = $this->buildFinalCurriculum($title, $tools, $focus, $level);

        // 3. Check if any phases with videos were generated
        $phasesWithVideos = array_values(array_filter($roadmapData['phases'], function($phase) {
            return !empty($phase['videos']) && count($phase['videos']) > 0;
        }));

        if (empty($phasesWithVideos)) {
            return response()->json([
                'error' => 'no_videos',
                'message' => 'No learning videos found for the selected tools and skill level. Please try different tools or adjust your skill level.',
            ], 422);
        }

        // 4. Save to Database
        $userRoadmap = UserRoadmap::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'goal' => $goal,
            'tools' => $tools,
            'focus' => $focus,
            'level' => $level,
            'curriculum' => $phasesWithVideos,
            'progress' => 0,
        ]);

        return response()->json([
            'id' => $userRoadmap->id,
            'title' => $title,
            'phases' => $phasesWithVideos,
            'focus' => $focus,
            'level' => $level,
            'redirect_url' => route('roadmap.show', $userRoadmap->id),
        ]);
    }

    private function cleanTitleWithAi($goal)
    {
        $prompt = "Correct any spelling or grammar errors and turn this search query into a professional learning roadmap title: '{$goal}'.
                   Reply ONLY with the new title.";

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->timeout(30) // Explicit 30s timeout for title cleaning
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                    'temperature' => 0.3,
                ]);
            return trim($response->json('choices.0.message.content'), '" ');
        } catch (\Throwable $e) {
            Log::error('Roadmap Title Cleaning Failed: ' . $e->getMessage());
            return ucfirst($goal);
        }
    }

    private function normalizeLevel(?string $level): string
    {
        $level = strtolower(trim((string) $level));

        return match ($level) {
            'intermediate' => 'intermediate',
            'advanced' => 'advanced',
            default => 'beginner',
        };
    }

    // =========================================================================
    // PRIVATE LOGIC
    // =========================================================================

    private function askGptToMatchTools($goal, $tools)
    {
        $toolList = $tools->map(fn($t) => "ID:{$t->id} | Name:{$t->name}")->implode("\n");
        $cacheKey = 'roadmap_tools:' . md5($goal);

        return Cache::remember($cacheKey, 3600, function() use ($goal, $toolList) {
            $prompt = "GOAL: {$goal}\nTOOLS:\n{$toolList}\nSelect only the tools that are directly mentioned or absolutely essential to achieving the goal. Avoid selecting tangentially related tools (e.g. do not select Python for an Excel goal unless Python is explicitly mentioned or requested). Reply ONLY with the comma-separated IDs.";
            try {
                $response = Http::withToken(config('services.openai.key'))
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-4o-mini',
                        'messages' => [['role' => 'user', 'content' => $prompt]],
                    ]);
                $ids = explode(',', preg_replace('/[^0-9,]/', '', $response->json('choices.0.message.content')));
                return array_map('intval', $ids);
            } catch (\Throwable $e) { return []; }
        });
    }

    private function filterVideosByFocusAndLevel($videos, string $focus, string $toolName, string $level)
    {
        if ($videos->count() <= 6) {
            return $videos; // No need to filter if there are already few videos
        }

        // Format a list of candidates: ID and Title
        $candidateList = $videos->map(fn($v) => "ID:{$v->id} | {$v->title}")->implode("\n");

        $prompt = <<<PROMPT
We are building a learning roadmap phase for the tool "{$toolName}" at "{$level}" level.
The user's primary focus/learning goal is: "{$focus}".

Here is the list of available lessons:
{$candidateList}

TASK:
1. Select the top 5 to 7 most relevant lessons from the list above that align best with the user's focus and level.
2. Order them from easiest to hardest to form a progressive curriculum.
3. If they are equally relevant, pick the best tutorials.

Return ONLY a comma-separated list of the selected IDs in order (e.g. 102,45,67). No text, no markdown.
PROMPT;

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->timeout(20)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                    'temperature' => 0.2,
                ]);

            $content = trim($response->json('choices.0.message.content'), '" ');
            $ids = array_map('intval', array_filter(explode(',', preg_replace('/[^0-9,]/', '', $content))));

            if (empty($ids)) {
                return $videos->take(6); // Fallback
            }

            // Return the selected videos in the order specified by GPT
            return collect($ids)->map(fn($id) => $videos->firstWhere('id', $id))->filter()->values();
        } catch (\Throwable $e) {
            Log::error("Roadmap Video Focus Filtering failed for {$toolName}: " . $e->getMessage());
            return $videos->take(6); // Fallback
        }
    }

    private function buildFinalCurriculum($title, $toolIds, $focus, $level)
    {
        $phases = [];
        $tools = Tool::whereIn('id', $toolIds)->get();

        foreach ($tools as $tool) {
            // Fetch ALL active videos for this tool — no skill_level filter
            $videos = Content::whereJsonContains('connected_tools', $tool->name)
                ->active()
                ->get();

            // Skip this tool entirely if no videos found
            if ($videos->count() === 0) {
                continue;
            }

            // Let AI pick the best-matching videos for the user's focus and level
            $filteredVideos = $this->filterVideosByFocusAndLevel($videos, $focus, $tool->name, $level);

            // Map to ensure we have the thumbnail_url accessor value for the frontend
            $videoData = $filteredVideos->map(function($v) {
                return [
                    'id' => $v->id,
                    'title' => $v->title,
                    'thumbnail_url' => $v->thumbnail_url,
                ];
            });

            $phases[] = [
                'name' => "Mastering {$tool->name}",
                'tool_name' => $tool->name,
                'videos' => $videoData,
            ];
        }

        // Fallback for broad goal videos - look at titles
        $genericVideos = Content::whereNull('course_id')
            ->where('title', 'like', "%{$title}%")
            ->active()
            ->get();

        if ($genericVideos->count() > 0) {
            $genericData = $genericVideos->map(function($v) {
                return [
                    'id' => $v->id,
                    'title' => $v->title,
                    'thumbnail_url' => $v->thumbnail_url,
                ];
            });

            $phases[] = [
                'name' => "Core Concepts & Strategy",
                'tool_name' => null,
                'videos' => $genericData
            ];
        }

        return [
            'title' => $title,
            'focus' => $focus,
            'level' => $level,
            'phases' => $phases
        ];
    }

    /**
     * Delete the specified roadmap.
     */
    public function destroy(UserRoadmap $roadmap)
    {
        if ($roadmap->user_id !== Auth::id()) {
            abort(403);
        }

        $roadmap->delete();

        return redirect()->route('roadmap')->with('success', 'Roadmap deleted successfully.');
    }

    public function addAutoTool(Request $request)
    {
        $request->validate([
            'roadmap_id' => 'required|exists:user_roadmaps,id',
            'tool_id' => 'required|exists:tools,id',
        ]);

        $roadmap = UserRoadmap::findOrFail($request->roadmap_id);
        if ($roadmap->user_id !== Auth::id()) abort(403);

        $tool = Tool::findOrFail($request->tool_id);
        $toolIds = is_array($roadmap->tools) ? $roadmap->tools : [];

        if (!in_array($tool->id, $toolIds)) {
            $toolIds[] = $tool->id;

            // Re-generate curriculum for all aggregated tools
            $level = $this->normalizeLevel($roadmap->level);
            $focus = $roadmap->focus ?: 'General Productivity';
            $curriculumData = $this->buildFinalCurriculum($roadmap->title, $toolIds, $focus, $level);

            $roadmap->update([
                'tools' => $toolIds,
                'curriculum' => $curriculumData['phases']
            ]);
        }

        return redirect()->back()->with('success', "🎉 Added {$tool->name} to your Auto-Generated Roadmap!");
    }

    public function dismissAutoTool(Request $request)
    {
        $request->validate([
            'roadmap_id' => 'required|exists:user_roadmaps,id',
            'tool_id' => 'required|exists:tools,id',
        ]);

        $roadmap = UserRoadmap::findOrFail($request->roadmap_id);
        if ($roadmap->user_id !== Auth::id()) abort(403);

        $meta = $roadmap->metadata ?: [];
        $dismissed = isset($meta['dismissed_tools']) ? (array) $meta['dismissed_tools'] : [];

        if (!in_array($request->tool_id, $dismissed)) {
            $dismissed[] = (int) $request->tool_id;
            $meta['dismissed_tools'] = $dismissed;
            $roadmap->update(['metadata' => $meta]);
        }

        return redirect()->back()->with('info', 'Tool recommendation dismissed.');
    }

    public function detectAndTrackPendingTools($user)
    {
        if (!$user) return null;

        $toolSeconds = [];

        // 1. Process Browsing History URLs
        $histories = \App\Models\BrowsingHistory::where('user_id', $user->id)
            ->whereNotNull('url')
            ->get(['url', 'duration']);

        foreach ($histories as $history) {
            $detected = \App\Services\ToolDetector::detect($history->url);
            $toolName = $detected['tool_name'];
            if ($toolName && $toolName !== 'Unknown') {
                if (!isset($toolSeconds[$toolName])) {
                    $toolSeconds[$toolName] = 0;
                }
                $toolSeconds[$toolName] += $history->duration;
            }
        }

        // 2. Process Extension Sessions domains and nested pages URLs
        $sessions = \App\Models\ExtensionSession::where('user_id', $user->id)
            ->get(['platform_domain', 'active_ms', 'pages']);

        foreach ($sessions as $session) {
            $hasAggregatedFromPages = false;
            if ($session->pages && is_array($session->pages)) {
                foreach ($session->pages as $page) {
                    $url = $page['url'] ?? null;
                    $pageMs = $page['active_ms'] ?? 0;
                    if ($url && $pageMs > 0) {
                        $detected = \App\Services\ToolDetector::detect($url);
                        $toolName = $detected['tool_name'];
                        if ($toolName && $toolName !== 'Unknown') {
                            if (!isset($toolSeconds[$toolName])) {
                                $toolSeconds[$toolName] = 0;
                            }
                            $toolSeconds[$toolName] += ($pageMs / 1000);
                            $hasAggregatedFromPages = true;
                        }
                    }
                }
            }

            // Fallback to platform_domain if pages didn't yield anything
            if (!$hasAggregatedFromPages && $session->platform_domain) {
                // Construct a URL for the detector
                $url = 'https://' . $session->platform_domain;
                $detected = \App\Services\ToolDetector::detect($url);
                $toolName = $detected['tool_name'];
                if ($toolName && $toolName !== 'Unknown') {
                    if (!isset($toolSeconds[$toolName])) {
                        $toolSeconds[$toolName] = 0;
                    }
                    $toolSeconds[$toolName] += ($session->active_ms / 1000);
                }
            }
        }

        // 3. Match detected tool names against database active tools
        $activeTools = Tool::active()->get();
        $detectedTools = collect();

        foreach ($toolSeconds as $toolName => $seconds) {
            if ($seconds < 3600) continue; // Minimum active duration threshold of 1 hour (3600 seconds)

            $matchedTool = $activeTools->first(function($dbTool) use ($toolName) {
                $dbNameLower = strtolower($dbTool->name);
                $detectedLower = strtolower($toolName);

                return $dbNameLower === $detectedLower
                    || str_contains($detectedLower, $dbNameLower)
                    || str_contains($dbNameLower, $detectedLower);
            });

            if ($matchedTool) {
                $existing = $detectedTools->firstWhere('id', $matchedTool->id);
                if ($existing) {
                    $existing->usage_seconds += $seconds;
                } else {
                    $matchedTool->usage_seconds = $seconds;
                    $detectedTools->push($matchedTool);
                }
            }
        }

        if ($detectedTools->isEmpty()) return null;

        // Sort by usage time descending, so most used tools are prioritized
        $detectedTools = $detectedTools->sortByDesc('usage_seconds')->values();

        // Check if an auto-generated roadmap exists
        $autoRoadmap = UserRoadmap::where('user_id', $user->id)
            ->where('is_auto_generated', true)
            ->first();

        if (!$autoRoadmap) {
            // Auto-create initial roadmap using detected tools
            $toolIds = $detectedTools->pluck('id')->toArray();
            $level = $this->normalizeLevel($user->experience_level);
            $focus = $user->learning_goal ?: 'General Productivity';

            $curriculumData = $this->buildFinalCurriculum("Auto-Generated Career path", $toolIds, $focus, $level);

            UserRoadmap::create([
                'user_id' => $user->id,
                'title' => 'Auto-Generated Learning Path',
                'goal' => 'Auto-Generated based on extension history',
                'tools' => $toolIds,
                'focus' => $focus,
                'level' => $level,
                'curriculum' => $curriculumData['phases'],
                'progress' => 0,
                'is_auto_generated' => true,
                'metadata' => ['dismissed_tools' => []]
            ]);

            return null; // Initialized!
        }

        // Auto roadmap exists, check for pending tools (tools the user has used but aren't in this roadmap yet, nor dismissed)
        $existingToolIds = is_array($autoRoadmap->tools) ? $autoRoadmap->tools : [];
        $meta = $autoRoadmap->metadata ?: [];
        $dismissedToolIds = isset($meta['dismissed_tools']) ? (array) $meta['dismissed_tools'] : [];

        foreach ($detectedTools as $tool) {
            if (!in_array($tool->id, $existingToolIds) && !in_array($tool->id, $dismissedToolIds)) {
                // We found a pending tool! Let's return this first one to ask the user.
                return [
                    'roadmap_id' => $autoRoadmap->id,
                    'tool' => $tool
                ];
            }
        }

        return null;
    }

    /**
     * Generate an initial auto-generated roadmap for a user immediately after onboarding.
     */
    public function generateAutoOnboardingRoadmap($user)
    {
        // Check if an auto-generated roadmap already exists to prevent duplicate creation
        $existing = UserRoadmap::where('user_id', $user->id)
            ->where('is_auto_generated', true)
            ->exists();

        if ($existing) {
            return;
        }

        // Get matching tool IDs from user's connections
        $connections = is_array($user->connections) ? $user->connections : [];
        $selectedTools = Tool::whereIn('name', $connections)
            ->where('status', 'active')
            ->get();

        $toolIds = $selectedTools->pluck('id')->toArray();
        $level = $this->normalizeLevel($user->experience_level);
        $focus = $user->learning_goal ?: 'General Productivity';

        // Build final curriculum
        $curriculumData = $this->buildFinalCurriculum("Auto-Generated Career path", $toolIds, $focus, $level);

        UserRoadmap::create([
            'user_id' => $user->id,
            'title' => 'Auto-Generated Learning Path',
            'goal' => 'Auto-Generated based on onboarding profile',
            'tools' => $toolIds,
            'focus' => $focus,
            'level' => $level,
            'curriculum' => $curriculumData['phases'],
            'progress' => 0,
            'is_auto_generated' => true,
            'metadata' => ['dismissed_tools' => []]
        ]);
    }
}
