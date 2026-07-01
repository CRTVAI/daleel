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
        return view('roadmap.index', compact('roadmaps', 'pendingData'));
    }

    public function show(UserRoadmap $roadmap)
    {
        if ($roadmap->user_id !== Auth::id()) abort(403);
        $user = Auth::user();

        // 1. Build phases
        $phases = $roadmap->curriculum['phases'] ?? $roadmap->curriculum;

        $allVideoIds = collect($phases)->filter(fn($p) => is_array($p))
            ->pluck('videos')->flatten(1)->pluck('id')->unique()->values();

        // 2. Load ALL progress records in ONE query, keyed by content_id
        $progressMap = $user->videoProgress()
            ->whereIn('content_id', $allVideoIds)
            ->get()->keyBy('content_id');

        // 3. Build roadmapData with rich progress per content
        $roadmapData = collect($phases)->map(function($phase) use ($progressMap) {
            if (!is_array($phase)) return null;
            $tool     = Tool::where('name', $phase['tool_name'] ?? null)->first();
            $videoIds = collect($phase['videos'])->pluck('id')->toArray();
            $contents = Content::whereIn('id', $videoIds)->get()
                ->map(function($content) use ($progressMap) {
                    $p = $progressMap->get($content->id);
                    $content->progress_record = $p;
                    $content->watched_seconds = $p?->watched_seconds ?? 0;
                    $content->completion_pct  = $p ? round($p->completion_percent) : 0;
                    $content->is_completed    = $p?->completed ?? false;
                    $content->last_watched_at = $p?->last_watched_at;
                    return $content;
                });

            $totalCount     = $contents->count();
            if ($totalCount === 0) return null;
            $completedCount = $contents->where('is_completed', true)->count();

            return [
                'tool'      => $tool,
                'contents'  => $contents,
                'total'     => $totalCount,
                'completed' => $completedCount,
                'percent'   => round(($completedCount / $totalCount) * 100),
            ];
        })->filter()->values();

        // 4. Stats
        $totalLessons          = $allVideoIds->count();
        $lessonsCompleted      = $progressMap->where('completed', true)->count();
        $overallProgress       = $totalLessons > 0 ? round(($lessonsCompleted / $totalLessons) * 100) : 0;
        $totalWatchedSeconds   = $progressMap->sum('watched_seconds');
        $totalDurationSeconds  = Content::whereIn('id', $allVideoIds)->sum('duration_seconds');
        $remainingSeconds      = max(0, $totalDurationSeconds - $totalWatchedSeconds);

        // 5. Current active lesson: Find the first incomplete video in the roadmap sequence
        $currentLesson = null;
        $currentProgressRecord = null;

        foreach ($roadmapData as $phase) {
            foreach ($phase['contents'] as $content) {
                if (!$content->is_completed) {
                    $currentLesson = $content;
                    $currentProgressRecord = $progressMap->get($content->id);
                    break 2;
                }
            }
        }

        // Fallback: If all lessons are completed, default to the very first lesson
        if (!$currentLesson) {
            $firstPhase = $roadmapData->first();
            $currentLesson = $firstPhase ? $firstPhase['contents']->first() : null;
            if ($currentLesson) {
                $currentProgressRecord = $progressMap->get($currentLesson->id);
            }
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
            'roadmap'
        ));
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
        $level = $request->input('level');

        // 1. Clean the title with AI
        $title = $this->cleanTitleWithAi($goal);

        // 2. Build the curriculum
        $roadmapData = $this->buildFinalCurriculum($title, $tools, $focus, $level);

        // 3. Save to Database
        $userRoadmap = UserRoadmap::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'goal' => $goal,
            'tools' => $tools,
            'focus' => $focus,
            'level' => $level,
            'curriculum' => $roadmapData['phases'],
            'progress' => 0,
        ]);

        return response()->json([
            'id' => $userRoadmap->id,
            'title' => $title,
            'phases' => $roadmapData['phases'],
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
            // Find videos that have this tool name AND match the selected skill level
            $videos = Content::whereJsonContains('connected_tools', $tool->name)
                ->where('skill_level', $level) // Apply skill level filter
                ->active()
                ->get();
            
            if ($videos->count() > 0) {
                // Filter the list of videos to match the user's specific focus
                $filteredVideos = $this->filterVideosByFocusAndLevel($videos, $focus, $tool->name, $level);

                // Map to ensure we have the thumbnail_url accessor value for the frontend
                $videoData = $filteredVideos->map(function($v) {
                    return [
                        'id' => $v->id,
                        'title' => $v->title,
                        'thumbnail_url' => $v->thumbnail_url, // Uses Model Accessor
                    ];
                });

                $phases[] = [
                    'name' => "Mastering {$tool->name}",
                    'tool_name' => $tool->name,
                    'videos' => $videoData
                ];
            }
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
            $level = $roadmap->level ?: 'Beginner';
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
            $level = $user->experience_level ?: 'Beginner';
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
}
