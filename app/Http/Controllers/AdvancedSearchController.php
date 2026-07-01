<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Course;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdvancedSearchController extends Controller
{
    /**
     * Cache duration in seconds for search results data.
     */
    private const CACHE_TTL = 300; // 5 minutes

    /**
     * Maximum items to send to GPT in step 2.
     */
    private const MAX_GPT_CANDIDATES = 50;

    /**
     * Tracks which matching method was used for debugging.
     */
    private ?string $lastMatchMethod = null;

    /**
     * Store last GPT response for debugging.
     */
    private ?string $lastGptResponse = null;

    // =========================================================================
    // ENTRY POINT
    // =========================================================================

    /**
     * Main search handler.
     */
    public function search(Request $request)
    {
        $query = trim($request->input('query') ?: $request->input('search', ''));

        if ($query === '') {
            return redirect()->route('learn.explore');
        }

        // Don't cache in local/debug mode
        if (config('app.debug') || app()->environment('local')) {
            $searchData = $this->performSearch($query);
        } else {
            $cacheKey = 'search_result:' . md5(strtolower($query));
            $searchData = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($query) {
                return $this->performSearch($query);
            });
        }

        if ($searchData instanceof \Illuminate\Http\RedirectResponse) {
            return $searchData;
        }

        return $this->buildView($searchData);
    }

    /**
     * Core search logic.
     */
    private function performSearch(string $rawQuery)
    {
        $this->lastMatchMethod = null;
        $this->lastGptResponse = null;

        // ── STEP 1: GPT Query Processor ──
        // Load all active tool names so GPT only extracts known tools
        $allToolNames = Tool::active()->pluck('name')->toArray();

        $parsed = $this->gptParseQuery($rawQuery, $allToolNames);

        Log::info('SmartSearch: Step 1 GPT parsed query', [
            'raw_query'      => $rawQuery,
            'fixed_query'    => $parsed['fixed_query'],
            'query_type'     => $parsed['query_type'],
            'tools'          => $parsed['tools_mentioned'],
            'confidence'     => $parsed['confidence_score'],
        ]);

        $fixedQuery     = $parsed['fixed_query'];
        $queryType      = $parsed['query_type'];      // 'single_video' | 'course' | 'roadmap'
        $toolsMentioned = $parsed['tools_mentioned']; // e.g. ['Laravel', 'VS Code']

        // ── STEP 2: Branch based on query type ──
        if ($queryType === 'roadmap') {
            return $this->handleRoadmap($fixedQuery, $rawQuery, $toolsMentioned, $parsed);
        }

        if ($queryType === 'course') {
            return $this->handleCourse($fixedQuery, $rawQuery, $toolsMentioned, $parsed);
        }

        // default: single_video
        return $this->handleVideo($fixedQuery, $rawQuery, $toolsMentioned, $parsed);
    }

    /**
     * Build view from data array.
     */
    private function buildView(array $data): \Illuminate\View\View
    {
        return view('search.results', $data);
    }

    // =========================================================================
    // STEP 1 — GPT QUERY PROCESSOR
    // =========================================================================

    /**
     * Sends the raw query + full tool list to GPT.
     * GPT fixes typos, classifies type, and extracts tools from the known list.
     *
     * Returns:
     * [
     *   'fixed_query'      => string,
     *   'query_type'       => 'single_video'|'course'|'roadmap',
     *   'tools_mentioned'  => string[],
     *   'goals_intent'     => string,
     *   'confidence_score' => float,
     * ]
     */
    private function gptParseQuery(string $rawQuery, array $allToolNames): array
    {
        $toolListStr = implode(', ', $allToolNames);

        $systemPrompt = <<<SYSTEM
You are an intelligent search query processor for a video learning platform.
Your job is to understand the user's intent and return a structured JSON response.
SYSTEM;

        $userPrompt = <<<PROMPT
USER QUERY: "{$rawQuery}"

AVAILABLE TOOLS IN OUR PLATFORM:
{$toolListStr}

YOUR TASKS:
1. Fix any spelling or typo mistakes in the query.
2. Classify the request into ONE type:
   - "single_video": User wants ONE specific tutorial/how-to video (e.g. "how to connect excel", "fix css error", "what is an API").
   - "course": User wants to deeply learn a tool or topic (e.g. "Excel Masterclass", "learn Python", "how to master Laravel").
   - "roadmap": User has a broad career/productivity goal (e.g. "become a pro", "grow my career", "learning path for automation").
3. Extract ONLY tool names from the AVAILABLE TOOLS list that the user mentioned or clearly implied.
4. Summarize the user's goal/intent in one short sentence.
5. Give a confidence score (0.0–1.0) for your classification.

CRITICAL RULES:
- "How to learn [Tool]" or "How to master [Tool]" → always "course".
- Broad career/productivity goals (not tool-specific) → always "roadmap".
- Specific how-to tasks → "single_video".
- Only extract tools from the AVAILABLE TOOLS list. If no tool matches, return an empty array.

Respond ONLY with valid JSON in this exact format (no markdown, no explanation):
{
  "fixed_query": "corrected query text",
  "query_type": "single_video|course|roadmap",
  "tools_mentioned": ["ToolA", "ToolB"],
  "goals_intent": "short description of what the user wants",
  "confidence_score": 0.95
}
PROMPT;

        $fallback = [
            'fixed_query'      => $rawQuery,
            'query_type'       => 'single_video',
            'tools_mentioned'  => [],
            'goals_intent'     => $rawQuery,
            'confidence_score' => 0.5,
        ];

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->timeout(20)
                ->retry(2, 100)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'       => 'gpt-4o-mini',
                    'max_tokens'  => 200,
                    'temperature' => 0,
                    'messages'    => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user',   'content' => $userPrompt],
                    ],
                ]);

            $content = trim($response->json('choices.0.message.content', ''));

            // Strip markdown code fences if GPT wraps response
            $content = preg_replace('/^```(?:json)?\s*/i', '', $content);
            $content = preg_replace('/\s*```$/', '', $content);

            $parsed = json_decode($content, true);

            if (!is_array($parsed) || !isset($parsed['query_type'])) {
                Log::warning('SmartSearch: Step 1 GPT returned invalid JSON', ['raw' => $content]);
                return $fallback;
            }

            // Sanitize query_type
            if (!in_array($parsed['query_type'], ['single_video', 'course', 'roadmap'])) {
                $parsed['query_type'] = 'single_video';
            }

            // Ensure tools_mentioned is an array
            if (!isset($parsed['tools_mentioned']) || !is_array($parsed['tools_mentioned'])) {
                $parsed['tools_mentioned'] = [];
            }

            // Ensure fixed_query is a non-empty string
            if (empty($parsed['fixed_query'])) {
                $parsed['fixed_query'] = $rawQuery;
            }

            $parsed['confidence_score'] = (float) ($parsed['confidence_score'] ?? 0.5);
            $parsed['goals_intent']     = $parsed['goals_intent'] ?? $rawQuery;

            return $parsed;

        } catch (\Throwable $e) {
            Log::error('SmartSearch: Step 1 GPT failed', ['error' => $e->getMessage()]);
            return $fallback;
        }
    }

    // =========================================================================
    // ROADMAP HANDLER
    // =========================================================================

    private function handleRoadmap(string $fixedQuery, string $rawQuery, array $toolsMentioned, array $parsed): array
    {
        $allTools = Tool::active()->get(['id', 'name', 'description', 'logo']);
        $selectedIds = $this->askGptToMatchTools($fixedQuery, $allTools);

        return [
            'type'        => 'roadmap',
            'query'       => $fixedQuery,
            'intent'      => 'roadmap',
            'allTools'    => $allTools,
            'selectedIds' => $selectedIds,
            'debug'       => [
                'type'                 => 'Roadmap Wizard Triggered',
                'raw_query'            => $rawQuery,
                'fixed_query'          => $fixedQuery,
                'tools_mentioned'      => $toolsMentioned,
                'goals_intent'         => $parsed['goals_intent'] ?? '',
                'confidence'           => $parsed['confidence_score'] ?? 0,
                'matched_tools_count'  => count($selectedIds),
            ],
        ];
    }

    private function askGptToMatchTools($goal, $tools): array
    {
        $toolList = $tools->map(fn($t) => "ID:{$t->id} | Name:{$t->name}")->implode("\n");
        $cacheKey = 'roadmap_tools:' . md5($goal);

        return Cache::remember($cacheKey, 3600, function () use ($goal, $toolList) {
            $prompt = "GOAL: {$goal}\nTOOLS:\n{$toolList}\nSelect only the tools that are directly mentioned or absolutely essential to achieving the goal. Avoid selecting tangentially related tools (e.g. do not select Python for an Excel goal unless Python is explicitly mentioned or requested). Reply ONLY with the comma-separated IDs.";
            try {
                $response = Http::withToken(config('services.openai.key'))
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model'       => 'gpt-4o-mini',
                        'messages'    => [['role' => 'user', 'content' => $prompt]],
                        'temperature' => 0,
                    ]);
                $ids = explode(',', preg_replace('/[^0-9,]/', '', $response->json('choices.0.message.content')));
                return array_map('intval', array_filter($ids));
            } catch (\Throwable $e) {
                return [];
            }
        });
    }

    // =========================================================================
    // COURSE HANDLER
    // =========================================================================

    private function handleCourse(string $fixedQuery, string $rawQuery, array $toolsMentioned, array $parsed): array
    {
        // ── Filter courses by extracted tools (if any) ──
        $coursesQuery = Course::active();

        if (!empty($toolsMentioned)) {
            $coursesQuery->where(function ($q) use ($toolsMentioned) {
                foreach ($toolsMentioned as $tool) {
                    $q->orWhere('connected_tools', 'like', '%' . $tool . '%');
                }
            });
        }

        $filteredCourses = $coursesQuery->get(['id', 'title', 'connected_tools']);

        // Fallback: if tool-filter yields nothing, search all courses
        if ($filteredCourses->isEmpty() && !empty($toolsMentioned)) {
            Log::info('SmartSearch: No tool-filtered courses found, using all courses', [
                'tools' => $toolsMentioned,
            ]);
            $filteredCourses = Course::active()->get(['id', 'title', 'connected_tools']);
        }

        $allCourseTitles = $filteredCourses->map(fn($c) => "ID:{$c->id} | {$c->title}")->implode("\n");

        $debugBase = [
            'intent'           => 'course',
            'raw_query'        => $rawQuery,
            'fixed_query'      => $fixedQuery,
            'tools_mentioned'  => $toolsMentioned,
            'goals_intent'     => $parsed['goals_intent'] ?? '',
            'confidence'       => $parsed['confidence_score'] ?? 0,
            'full_list'        => $allCourseTitles ?: 'No courses in database',
            'candidates_count' => $filteredCourses->count(),
            'timestamp'        => now()->toDateTimeString(),
        ];

        if ($filteredCourses->isEmpty()) {
            return [
                'type'   => 'none',
                'query'  => $fixedQuery,
                'intent' => 'course',
                'debug'  => array_merge($debugBase, [
                    'error'    => 'No active courses in database',
                    'raw_gpt'  => 'N/A - Empty database',
                    'picked_id'=> 0,
                ]),
            ];
        }

        // ── Step 2: GPT picks the best match from filtered list ──
        $pickedId = $this->hybridPick($fixedQuery, $filteredCourses, 'course');

        if (!$pickedId) {
            // Fallback to video
            Log::info('SmartSearch: No course match, falling back to video', ['query' => $fixedQuery]);
            $allVideos = Content::active()->whereNull('course_id')->get(['id', 'title', 'connected_tools']);
            return $this->handleVideo($fixedQuery, $rawQuery, $toolsMentioned, $parsed, $allVideos);
        }

        try {
            $course = Course::with([
                'contents' => fn($q) => $q->active()->orderBy('sort_order')->orderBy('id'),
            ])->findOrFail($pickedId);
        } catch (ModelNotFoundException $e) {
            Log::warning('SmartSearch: Course deleted between search and fetch', ['course_id' => $pickedId]);
            $allVideos = Content::active()->whereNull('course_id')->get(['id', 'title', 'connected_tools']);
            return $this->handleVideo($fixedQuery, $rawQuery, $toolsMentioned, $parsed, $allVideos);
        }

        Log::info('SmartSearch: Course matched successfully', [
            'course_id'    => $pickedId,
            'course_title' => $course->title,
            'query'        => $fixedQuery,
        ]);

        return [
            'type'   => 'course',
            'query'  => $fixedQuery,
            'course' => $course,
            'intent' => 'course',
            'debug'  => array_merge($debugBase, [
                'picked_id' => $pickedId,
                'method'    => $this->lastMatchMethod ?? 'unknown',
                'raw_gpt'   => $this->lastGptResponse ?? 'N/A',
            ]),
        ];
    }

    // =========================================================================
    // VIDEO HANDLER
    // =========================================================================

    private function handleVideo(
        string  $fixedQuery,
        string  $rawQuery,
        array   $toolsMentioned,
        array   $parsed,
        $preloadedVideos = null
    ): array {
        // ── Filter videos by extracted tools (if any) ──
        if ($preloadedVideos !== null) {
            $filteredVideos = $preloadedVideos;
        } else {
            $videosQuery = Content::active()->whereNull('course_id');

            if (!empty($toolsMentioned)) {
                $videosQuery->where(function ($q) use ($toolsMentioned) {
                    foreach ($toolsMentioned as $tool) {
                        $q->orWhere('connected_tools', 'like', '%' . $tool . '%');
                    }
                });
            }

            $filteredVideos = $videosQuery->get(['id', 'title', 'connected_tools']);

            // Fallback: if tool-filter yields nothing, search all videos
            if ($filteredVideos->isEmpty() && !empty($toolsMentioned)) {
                Log::info('SmartSearch: No tool-filtered videos found, using all videos', [
                    'tools' => $toolsMentioned,
                ]);
                $filteredVideos = Content::active()->whereNull('course_id')->get(['id', 'title', 'connected_tools']);
            }
        }

        $allVideoTitles = $filteredVideos->map(fn($v) => "ID:{$v->id} | {$v->title}")->implode("\n");

        $debugBase = [
            'intent'           => 'single_video',
            'raw_query'        => $rawQuery,
            'fixed_query'      => $fixedQuery,
            'tools_mentioned'  => $toolsMentioned,
            'goals_intent'     => $parsed['goals_intent'] ?? '',
            'confidence'       => $parsed['confidence_score'] ?? 0,
            'full_list'        => $allVideoTitles ?: 'No standalone videos in database',
            'candidates_count' => $filteredVideos->count(),
            'timestamp'        => now()->toDateTimeString(),
        ];

        if ($filteredVideos->isEmpty()) {
            return [
                'type'   => 'none',
                'query'  => $fixedQuery,
                'intent' => 'single_video',
                'debug'  => array_merge($debugBase, [
                    'error'    => 'No standalone videos in database',
                    'raw_gpt'  => 'N/A - Empty database',
                    'picked_id'=> 0,
                ]),
            ];
        }

        // ── Step 2: GPT picks best match from filtered list ──
        $pickedId = $this->hybridPick($fixedQuery, $filteredVideos, 'video');

        if (!$pickedId) {
            return [
                'type'   => 'none',
                'query'  => $fixedQuery,
                'intent' => 'single_video',
                'debug'  => array_merge($debugBase, [
                    'error'    => 'No matching video found',
                    'raw_gpt'  => $this->lastGptResponse ?? 'N/A',
                    'picked_id'=> 0,
                ]),
            ];
        }

        try {
            $video = Content::with('course')->findOrFail($pickedId);
        } catch (ModelNotFoundException $e) {
            Log::warning('SmartSearch: Video deleted between search and fetch', ['video_id' => $pickedId]);
            return [
                'type'   => 'none',
                'query'  => $fixedQuery,
                'intent' => 'single_video',
                'debug'  => array_merge($debugBase, [
                    'error'    => 'Selected video no longer available',
                    'raw_gpt'  => $this->lastGptResponse ?? 'N/A',
                    'picked_id'=> $pickedId,
                ]),
            ];
        }

        Log::info('SmartSearch: Video matched successfully', [
            'video_id'    => $video->id,
            'video_title' => $video->title,
            'query'       => $fixedQuery,
        ]);

        return [
            'type'   => 'video',
            'query'  => $fixedQuery,
            'video'  => $video,
            'intent' => 'single_video',
            'debug'  => array_merge($debugBase, [
                'picked_id' => $pickedId,
                'method'    => $this->lastMatchMethod ?? 'unknown',
                'raw_gpt'   => $this->lastGptResponse ?? 'N/A',
            ]),
        ];
    }

    // =========================================================================
    // HYBRID MATCHING (Step 2 inside video/course handlers)
    // =========================================================================

    /**
     * Hybrid matching strategy on a pre-filtered list:
     * 1. Exact match (free)
     * 2. Contains match (free)
     * 3. Levenshtein distance for typos (free)
     * 4. GPT semantic matching (paid)
     */
    private function hybridPick(string $query, $items, string $type): ?int
    {
        $lowerQuery = strtolower($query);
        $this->lastMatchMethod = 'none';
        $this->lastGptResponse = null;

        // Strategy 1: Exact match
        $exactMatch = $items->first(fn($item) => strtolower($item->title) === $lowerQuery);
        if ($exactMatch) {
            $this->lastMatchMethod = 'exact';
            Log::info('SmartSearch: Exact match found', ['query' => $query, 'matched' => $exactMatch->title]);
            return $exactMatch->id;
        }

        // Strategy 2: Contains match
        $containsMatch = $items->first(function ($item) use ($lowerQuery) {
            $lowerTitle = strtolower($item->title);
            return str_contains($lowerTitle, $lowerQuery) || str_contains($lowerQuery, $lowerTitle);
        });
        if ($containsMatch) {
            $this->lastMatchMethod = 'contains';
            Log::info('SmartSearch: Contains match found', ['query' => $query, 'matched' => $containsMatch->title]);
            return $containsMatch->id;
        }

        // Strategy 3: Levenshtein for typos (distance < 3)
        $closestMatch    = null;
        $closestDistance = PHP_INT_MAX;
        foreach ($items as $item) {
            $itemTitle = strtolower($item->title);
            if (abs(strlen($lowerQuery) - strlen($itemTitle)) > 5) continue;
            $distance = levenshtein($lowerQuery, $itemTitle);
            if ($distance < 3 && $distance < $closestDistance) {
                $closestMatch    = $item;
                $closestDistance = $distance;
            }
        }
        if ($closestMatch) {
            $this->lastMatchMethod = 'levenshtein';
            Log::info('SmartSearch: Levenshtein match found', [
                'query'   => $query,
                'matched' => $closestMatch->title,
                'distance'=> $closestDistance,
            ]);
            return $closestMatch->id;
        }

        // Strategy 4: GPT semantic matching
        $this->lastMatchMethod = 'gpt';
        $formattedList = $items->map(fn($item) => "ID:{$item->id} | {$item->title}")->implode("\n");

        if ($items->count() > self::MAX_GPT_CANDIDATES) {
            Log::warning('SmartSearch: List too large for GPT, pre-filtering', [
                'total_items' => $items->count(),
                'max_allowed' => self::MAX_GPT_CANDIDATES,
            ]);
            $formattedList = $this->keywordPreFilter($lowerQuery, $formattedList);
        }

        $picked = $this->gptPick($query, $formattedList, $type);
        $this->lastGptResponse = $picked['raw'] ?? 'No Response';

        return $picked['id'] ?? null;
    }

    /**
     * Pre-filter a large list using keyword matching before sending to GPT.
     */
    private function keywordPreFilter(string $query, string $formattedList): string
    {
        $lines    = explode("\n", $formattedList);
        $keywords = explode(' ', $query);

        $scoredLines = [];
        foreach ($lines as $line) {
            $lowerLine = strtolower($line);
            $score = 0;
            foreach ($keywords as $keyword) {
                if (strlen($keyword) > 2 && str_contains($lowerLine, $keyword)) {
                    $score++;
                }
            }
            if ($score > 0) {
                $scoredLines[] = ['line' => $line, 'score' => $score];
            }
        }

        usort($scoredLines, fn($a, $b) => $b['score'] <=> $a['score']);
        $topLines = array_slice($scoredLines, 0, self::MAX_GPT_CANDIDATES);

        return implode("\n", array_column($topLines, 'line'));
    }

    // =========================================================================
    // GPT PICKER (Step 2 — pick best ID from a list)
    // =========================================================================

    private function gptPick(string $query, string $list, string $type): array
    {
        $typeLabel = $type === 'course' ? 'course' : 'video lesson';

        $prompt = <<<PROMPT
User is searching for a {$typeLabel}: "{$query}"

Available {$typeLabel}s:
{$list}

TASK: Find the single best matching ID.

RULES:
- Match by meaning, not just exact words (e.g., "AI" matches "Artificial Intelligence")
- Handle common typos (e.g., "excell" matches "Excel", "javscript" matches "JavaScript")
- Consider topic relevance and content scope
- If nothing is a reasonable match, respond with 0
- Reply ONLY with the ID number

Best match ID:
PROMPT;

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->timeout(10)
                ->retry(2, 100)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'       => 'gpt-4o-mini',
                    'max_tokens'  => 15,
                    'temperature' => 0,
                    'messages'    => [
                        ['role' => 'system', 'content' => 'You are a precise search matching system. Reply with only a number.'],
                        ['role' => 'user',   'content' => $prompt],
                    ],
                ]);

            $answer = trim($response->json('choices.0.message.content', '0'));
            $answer = trim(preg_replace('/[^0-9]/', '', $answer));

            if ($answer === '' || $answer === '0') {
                return ['id' => null, 'raw' => '0 (No match found)'];
            }

            $id = (int) $answer;

            Log::info('SmartSearch: GPT picked ID', [
                'query'            => $query,
                'type'             => $type,
                'picked_id'        => $id,
                'candidates_count' => substr_count($list, "\n") + 1,
            ]);

            return ['id' => $id, 'raw' => $answer];

        } catch (\Throwable $e) {
            Log::error('SmartSearch: GPT pick failed', [
                'query' => $query,
                'type'  => $type,
                'error' => $e->getMessage(),
            ]);
            return ['id' => null, 'raw' => 'Error: ' . $e->getMessage()];
        }
    }

    // =========================================================================
    // CACHE MANAGEMENT
    // =========================================================================

    public function clearCache(Request $request)
    {
        $query = $request->input('query');

        if ($query) {
            $cacheKey = 'search_result:' . md5(strtolower($query));
            Cache::forget($cacheKey);
            return response()->json(['success' => true, 'message' => "Cache cleared for query: {$query}"]);
        }

        Cache::flush();
        return response()->json(['success' => true, 'message' => 'All search caches cleared']);
    }
}