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

class RoadmapController extends Controller
{
    public function index()
    {
        $roadmaps = UserRoadmap::where('user_id', Auth::id())->latest()->get();
        return view('roadmap.index', compact('roadmaps'));
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

        // 5. Current (last-watched) lesson in this roadmap
        $currentProgressRecord = $progressMap->sortByDesc(fn($p) => $p->last_watched_at)->first();
        $currentLesson         = $currentProgressRecord?->content;

        if (!$currentLesson) {
            $firstPhase    = $roadmapData->first();
            $currentLesson = $firstPhase ? $firstPhase['contents']->first() : null;
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

        // We can use GPT to generate 7 specific categories based on tools
        $prompt = "Goal: {$goal}. Tools: " . implode(', ', $toolNames) . ". 
                   Generate 7 short improvement categories for a learning roadmap. 
                   Return ONLY a JSON array of 7 strings.";

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                    'temperature' => 0.6,
                ]);

            $categories = json_decode($response->json('choices.0.message.content'), true);
            if (!is_array($categories) || count($categories) < 7) throw new \Exception("Invalid GPT format");
        } catch (\Throwable $e) {
            // Fallback
            $categories = [
                'Productivity & Speed', 'Advanced Office Skills', 'Data Analysis', 
                'Professional Documents', 'Presentation Skills', 'Communication & Email', 'Others'
            ];
        }

        return response()->json($categories);
    }

    /**
     * Final Generation (AJAX/Fetch)
     */
    public function generateRoadmap(Request $request)
    {
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
            $prompt = "GOAL: {$goal}\nTOOLS:\n{$toolList}\nSelect necessary tools. Reply ONLY with comma-separated IDs.";
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
                // Map to ensure we have the thumbnail_url accessor value for the frontend
                $videoData = $videos->map(function($v) {
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
}
