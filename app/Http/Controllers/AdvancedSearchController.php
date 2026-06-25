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
     * Maximum items to send to GPT in a single request.
     */
    private const MAX_GPT_CANDIDATES = 50;

    /**
     * Signal words that strongly indicate a COURSE intent.
     */
    private const COURSE_SIGNALS = [
        'learn', 'master', 'mastery', 'masterclass', 'course', 'guide',
        'foundations', 'foundation', 'fundamentals', 'introduction', 'intro',
        'beginner', 'basics', 'basic', 'bootcamp', 'curriculum', 'series',
        'path', 'complete', 'full', 'training',
        'from scratch', 'zero to hero', 'deep dive', 'comprehensive',
    ];

    /**
     * Signal words that strongly indicate a ROADMAP intent.
     */
    private const ROADMAP_SIGNALS = [
        'roadmap', 'become', 'pro', 'grow', 'succeed', 'career',
        'improve my', 'automation agency', 'business', 'entrepreneur',
        'skill path', 'learning path', 'journey',
    ];

    /**
     * Signal words that strongly indicate a single VIDEO intent.
     */
    private const VIDEO_SIGNALS = [
        'how to', 'how do', 'what is', 'what are', 'why is', 'why does',
        'fix', 'solve', 'error', 'issue', 'problem', 'connect', 'integrate',
        'create', 'build', 'make', 'write', 'generate', 'use', 'using',
        'setup', 'set up', 'install', 'configure', 'difference between',
        'tutorial', 'walkthrough', 'step by step', 'quick tip', 'explain',
    ];

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
     * Main search handler with caching for the search data only (not the view).
     */
    public function search(Request $request)
    {
        $query = trim($request->input('query') ?: $request->input('search', ''));

        if ($query === '') {
            return redirect()->route('learn.explore');
        }

        // Don't cache if debug mode or development
        if (config('app.debug') || app()->environment('local')) {
            $searchData = $this->performSearch($query);
        } else {
            // Cache only the search result DATA, not the view
            $cacheKey = 'search_result:' . md5(strtolower($query));
            
            $searchData = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($query) {
                return $this->performSearch($query);
            });
        }

        // Handle Redirects (Roadmap intent)
        if ($searchData instanceof \Illuminate\Http\RedirectResponse) {
            return $searchData;
        }

        // Build the view with the cached data
        return $this->buildView($searchData);
    }

    /**
     * Core search logic that returns data array or a redirect (cacheable).
     */
    private function performSearch(string $query)
    {
        // Reset tracking variables
        $this->lastMatchMethod = null;
        $this->lastGptResponse = null;

        // ── STEP 1: Detect intent ──
        $intent = $this->detectIntent($query);

        Log::info('SmartSearch: Intent detected', [
            'query' => $query,
            'intent' => $intent,
        ]);

        // Get all titles for debug display
        $allCourses = Course::active()->get(['id', 'title']);
        $allVideos = Content::active()->whereNull('course_id')->get(['id', 'title']);

        // ── STEP 2: Branch based on intent ──
        if ($intent === 'roadmap') {
            return $this->handleRoadmap($query, $intent);
        }

        if ($intent === 'course') {
            return $this->handleCourse($query, $intent, $allCourses, $allVideos);
        }

        return $this->handleVideo($query, $intent, $allVideos, $allCourses);
    }

    /**
     * Build the view from cached data.
     */
    private function buildView(array $data): \Illuminate\View\View
    {
        return view('search.results', $data);
    }

    // =========================================================================
    // INTENT DETECTION
    // =========================================================================

    /**
     * Detect search intent using signal words first, then GPT for ambiguous queries.
     */
    private function detectIntent(string $query): string
    {
        $lowerQuery = strtolower($query);
        $matches = [];

        // Check for Roadmap signals
        foreach (self::ROADMAP_SIGNALS as $signal) {
            if (str_contains($lowerQuery, $signal)) {
                $matches[] = 'roadmap';
                break;
            }
        }

        // Check for Course signals
        foreach (self::COURSE_SIGNALS as $signal) {
            if (str_contains($lowerQuery, $signal)) {
                $matches[] = 'course';
                break;
            }
        }

        // Check for Video signals
        foreach (self::VIDEO_SIGNALS as $signal) {
            if (str_contains($lowerQuery, $signal)) {
                $matches[] = 'video';
                break;
            }
        }

        // 1. If only one intent matched, return it immediately (fast/free)
        if (count(array_unique($matches)) === 1) {
            return $matches[0];
        }

        // 1.5 Conflict Resolution: "How to learn/master [X]" is almost always a COURSE intent
        if (count($matches) >= 2 && in_array('course', $matches) && in_array('video', $matches)) {
            if (preg_match('/\b(learn|master)\b/i', $lowerQuery)) {
                return 'course';
            }
        }

        // 2. If NO signals matched OR MULTIPLE signals matched (conflict), use GPT
        Log::info('SmartSearch: Conflict or unknown query, using GPT', [
            'query' => $query,
            'matches' => $matches,
        ]);

        return $this->askGptForIntent($query);
    }

    /**
     * Uses GPT to classify the intent into 'video', 'course', or 'roadmap'.
     */
    private function askGptForIntent(string $query): string
    {
        $prompt = <<<PROMPT
USER QUERY: "{$query}"

TASK: Classify this intent into ONE of these three categories:

1. 'video': (Specific technical task) - User wants to do ONE small thing right now. (e.g., "how to connect excel", "fix css", "how to use filters").
2. 'course': (Topic Mastery) - User wants to learn a specific tool/course deeply. (e.g., "Excel Masterclass", "Learn Python", "How to learn GPT").
3. 'roadmap': (Broad Growth/Goal) - User has a "Big Picture" career or productivity goal. (e.g., "How to grow in office", "Become a pro", "Succeed in my career", "learning path for automation").

CRITICAL RULES: 
- If the query is "How to learn [Tool Name]" or "How to master [Tool Name]", it is a 'course'.
- If the query is about "GROWTH", "SUCCESS", "PRODUCTIVITY", "CAREER", or "BECOMING A PRO" (generalized, not tool-specific), it is a 'roadmap'.
- When in doubt between 'course' and 'roadmap', pick 'course' if a single specific tool is mentioned.

Reply with ONLY the word: 'video', 'course', or 'roadmap'.
PROMPT;

        try {
            $response = Http::withToken(config('services.openai.key'))
                ->timeout(15)
                ->retry(2, 100)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'       => 'gpt-4o-mini',
                    'max_tokens'  => 10,
                    'temperature' => 0,
                    'messages'    => [
                        ['role' => 'system', 'content' => 'You are a search intent classifier. Reply with only one word.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);

            $answer = strtolower(trim($response->json('choices.0.message.content', 'video')));
            
            if (str_contains($answer, 'roadmap')) return 'roadmap';
            if (str_contains($answer, 'course')) return 'course';
            return 'video';

        } catch (\Throwable $e) {
            return 'video';
        }
    }

    /**
     * Handle Roadmap intent directly on the search results page.
     */
    private function handleRoadmap(string $query, string $intent): array
    {
        $allTools = Tool::active()->get(['id', 'name', 'description', 'logo']);
        $selectedIds = $this->askGptToMatchTools($query, $allTools);

        return [
            'type'   => 'roadmap',
            'query'  => $query,
            'intent' => $intent,
            'allTools' => $allTools,
            'selectedIds' => $selectedIds,
            'debug'  => [
                'type' => 'Roadmap Wizard Triggered',
                'matched_tools_count' => count($selectedIds),
            ],
        ];
    }

    private function askGptToMatchTools($goal, $tools)
    {
        $toolList = $tools->map(fn($t) => "ID:{$t->id} | Name:{$t->name}")->implode("\n");
        $cacheKey = 'roadmap_tools:' . md5($goal);

        return Cache::remember($cacheKey, 3600, function() use ($goal, $toolList) {
            $prompt = "GOAL: {$goal}\nTOOLS:\n{$toolList}\nSelect necessary tools to achieve the goal. Reply ONLY with comma-separated IDs.";
            try {
                $response = Http::withToken(config('services.openai.key'))
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-4o-mini',
                        'messages' => [['role' => 'user', 'content' => $prompt]],
                        'temperature' => 0,
                    ]);
                $ids = explode(',', preg_replace('/[^0-9,]/', '', $response->json('choices.0.message.content')));
                return array_map('intval', $ids);
            } catch (\Throwable $e) { return []; }
        });
    }

    // =========================================================================
    // COURSE HANDLER
    // =========================================================================

    /**
     * Handle course-intent searches.
     */
    private function handleCourse(string $query, string $intent, $allCourses, $allVideos): array
    {
        // Format all course titles for debug display
        $allCourseTitles = $allCourses->map(function ($course) {
            return "ID:{$course->id} | {$course->title}";
        })->implode("\n");

        $debugBase = [
            'intent' => $intent,
            'full_list' => $allCourseTitles ?: 'No courses in database',
            'candidates_count' => $allCourses->count(),
            'timestamp' => now()->toDateTimeString(),
        ];

        if ($allCourses->isEmpty()) {
            return [
                'type'   => 'none',
                'query'  => $query,
                'intent' => $intent,
                'debug'  => array_merge($debugBase, [
                    'error' => 'No active courses in database',
                    'raw_gpt' => 'N/A - Empty database',
                    'picked_id' => 0,
                ]),
            ];
        }

        // Try hybrid matching
        $pickedId = $this->hybridPick($query, $allCourses, 'course');

        if (!$pickedId) {
            // No course matched — try video as fallback
            Log::info('SmartSearch: No course match, falling back to video', [
                'query' => $query,
            ]);
            return $this->handleVideo($query, $intent, $allVideos, $allCourses);
        }

        // Fetch with relationships
        try {
            $course = Course::with([
                'contents' => fn ($q) => $q->active()->orderBy('sort_order')->orderBy('id'),
            ])->findOrFail($pickedId);
        } catch (ModelNotFoundException $e) {
            Log::warning('SmartSearch: Course deleted between search and fetch', [
                'course_id' => $pickedId,
                'query' => $query,
            ]);
            return $this->handleVideo($query, $intent, $allVideos, $allCourses);
        }

        Log::info('SmartSearch: Course matched successfully', [
            'course_id' => $pickedId,
            'course_title' => $course->title,
            'query' => $query,
        ]);

        return [
            'type'   => 'course',
            'query'  => $query,
            'course' => $course,
            'intent' => $intent,
            'debug'  => array_merge($debugBase, [
                'picked_id' => $pickedId,
                'method' => $this->lastMatchMethod ?? 'unknown',
                'raw_gpt' => $this->lastGptResponse ?? 'N/A',
            ]),
        ];
    }

    // =========================================================================
    // VIDEO HANDLER
    // =========================================================================

    /**
     * Handle video-intent searches.
     */
    private function handleVideo(string $query, string $intent, $allVideos, $allCourses = null): array
    {
        // Format all video titles for debug display
        $allVideoTitles = $allVideos->map(function ($video) {
            return "ID:{$video->id} | {$video->title}";
        })->implode("\n");

        $debugBase = [
            'intent' => $intent,
            'full_list' => $allVideoTitles ?: 'No standalone videos in database',
            'candidates_count' => $allVideos->count(),
            'timestamp' => now()->toDateTimeString(),
        ];

        if ($allVideos->isEmpty()) {
            return [
                'type'   => 'none',
                'query'  => $query,
                'intent' => $intent,
                'debug'  => array_merge($debugBase, [
                    'error' => 'No standalone videos in database',
                    'raw_gpt' => 'N/A - Empty database',
                    'picked_id' => 0,
                ]),
            ];
        }

        // Try hybrid matching
        $pickedId = $this->hybridPick($query, $allVideos, 'video');

        if (!$pickedId) {
            return [
                'type'   => 'none',
                'query'  => $query,
                'intent' => $intent,
                'debug'  => array_merge($debugBase, [
                    'error' => 'No matching video found',
                    'raw_gpt' => $this->lastGptResponse ?? 'N/A',
                    'picked_id' => 0,
                ]),
            ];
        }

        // Fetch with relationships
        try {
            $video = Content::with('course')->findOrFail($pickedId);
        } catch (ModelNotFoundException $e) {
            Log::warning('SmartSearch: Video deleted between search and fetch', [
                'video_id' => $pickedId,
                'query' => $query,
            ]);
            return [
                'type'   => 'none',
                'query'  => $query,
                'intent' => $intent,
                'debug'  => array_merge($debugBase, [
                    'error' => 'Selected video no longer available',
                    'raw_gpt' => $this->lastGptResponse ?? 'N/A',
                    'picked_id' => $pickedId,
                ]),
            ];
        }

        Log::info('SmartSearch: Video matched successfully', [
            'video_id' => $video->id,
            'video_title' => $video->title,
            'query' => $query,
        ]);

        return [
            'type'   => 'video',
            'query'  => $query,
            'video'  => $video,
            'intent' => $intent,
            'debug'  => array_merge($debugBase, [
                'picked_id' => $pickedId,
                'method' => $this->lastMatchMethod ?? 'unknown',
                'raw_gpt' => $this->lastGptResponse ?? 'N/A',
            ]),
        ];
    }

    // =========================================================================
    // HYBRID MATCHING
    // =========================================================================

    /**
     * Hybrid matching strategy:
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

        // Strategy 1: Exact match (case-insensitive)
        $exactMatch = $items->first(function ($item) use ($lowerQuery) {
            return strtolower($item->title) === $lowerQuery;
        });

        if ($exactMatch) {
            $this->lastMatchMethod = 'exact';
            Log::info('SmartSearch: Exact match found', [
                'query' => $query,
                'matched' => $exactMatch->title,
            ]);
            return $exactMatch->id;
        }

        // Strategy 2: Contains match (query is substring of title or vice versa)
        $containsMatch = $items->first(function ($item) use ($lowerQuery) {
            $lowerTitle = strtolower($item->title);
            return str_contains($lowerTitle, $lowerQuery) || str_contains($lowerQuery, $lowerTitle);
        });

        if ($containsMatch) {
            $this->lastMatchMethod = 'contains';
            Log::info('SmartSearch: Contains match found', [
                'query' => $query,
                'matched' => $containsMatch->title,
            ]);
            return $containsMatch->id;
        }

        // Strategy 3: Levenshtein distance for obvious typos (distance < 3)
        $closestMatch = null;
        $closestDistance = PHP_INT_MAX;

        foreach ($items as $item) {
            $itemTitle = strtolower($item->title);
            
            // Skip if length difference is too large (optimization)
            if (abs(strlen($lowerQuery) - strlen($itemTitle)) > 5) {
                continue;
            }

            $distance = levenshtein($lowerQuery, $itemTitle);
            
            if ($distance < 3 && $distance < $closestDistance) {
                $closestMatch = $item;
                $closestDistance = $distance;
            }
        }

        if ($closestMatch) {
            $this->lastMatchMethod = 'levenshtein';
            Log::info('SmartSearch: Levenshtein match found', [
                'query' => $query,
                'matched' => $closestMatch->title,
                'distance' => $closestDistance,
            ]);
            return $closestMatch->id;
        }

        // Strategy 4: Fall back to GPT for semantic matching
        $this->lastMatchMethod = 'gpt';
        
        // Format list for GPT
        $formattedList = $items->map(fn($item) => "ID:{$item->id} | {$item->title}")->implode("\n");
        
        // Truncate if too large
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
        $lines = explode("\n", $formattedList);
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
        
        // Sort by relevance score and take top N
        usort($scoredLines, fn($a, $b) => $b['score'] <=> $a['score']);
        
        $topLines = array_slice($scoredLines, 0, self::MAX_GPT_CANDIDATES);
        
        return implode("\n", array_column($topLines, 'line'));
    }

    // =========================================================================
    // GPT PICKER
    // =========================================================================

    /**
     * Sends a list of titles to GPT for semantic matching.
     */
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
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);

            $answer = trim($response->json('choices.0.message.content', '0'));
            
            // Clean the answer - extract just the number
            $answer = trim(preg_replace('/[^0-9]/', '', $answer));
            
            if ($answer === '' || $answer === '0') {
                return ['id' => null, 'raw' => '0 (No match found)'];
            }
            
            $id = (int) $answer;
            
            Log::info('SmartSearch: GPT picked ID', [
                'query' => $query,
                'type' => $type,
                'picked_id' => $id,
                'candidates_count' => substr_count($list, "\n") + 1,
            ]);
            
            return ['id' => $id, 'raw' => $answer];

        } catch (\Throwable $e) {
            Log::error('SmartSearch: GPT pick failed', [
                'query' => $query,
                'type' => $type,
                'error' => $e->getMessage(),
            ]);
            
            return ['id' => null, 'raw' => "Error: " . $e->getMessage()];
        }
    }

    // =========================================================================
    // CACHE MANAGEMENT
    // =========================================================================

    /**
     * Clear cached search results (useful after content updates).
     */
    public function clearCache(Request $request)
    {
        $query = $request->input('query');
        
        if ($query) {
            $cacheKey = 'search_result:' . md5(strtolower($query));
            Cache::forget($cacheKey);
            
            return response()->json([
                'success' => true,
                'message' => "Cache cleared for query: {$query}",
            ]);
        }
        
        Cache::flush();
        
        return response()->json([
            'success' => true,
            'message' => 'All search caches cleared',
        ]);
    }
}