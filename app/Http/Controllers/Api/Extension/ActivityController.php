<?php

namespace App\Http\Controllers\Api\Extension;

use App\Http\Controllers\Controller;
use App\Models\ExtensionDevice;
use App\Models\ExtensionMetricsSnapshot;
use App\Models\ExtensionSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ActivityController extends Controller
{
    /**
     * POST /api/extension/sessions
     * Save a session summary sent from the extension
     */
    public function storeSession(Request $request)
    {
        Log::info('Extension Session API hit', $request->all());
        
        $user = $request->user();
        
        // Sanctum tokens can tell us the device ID if we set it up, but for now 
        // we can fetch the active device for the user. (Ideally, the token ID or
        // token abilities relate to the specific device. Let's find the current device).
        // A simple fallback: take the first active device or require it in headers/payload.
        
        // For security and simplicity, assume the user only has 1 active extension or find the latest
        $device = ExtensionDevice::where('user_id', $user->id)
            ->whereNull('revoked_at')
            ->orderBy('last_active_at', 'desc')
            ->first();

        if (!$device) {
            return response()->json(['message' => 'No active extension device found for user.'], 403);
        }

        $validated = $request->validate([
            'session_id' => 'required|string',
            'started_at' => 'required',
            'ended_at' => 'required',
            'platform.type' => 'nullable|string',
            'platform.domain' => 'nullable|string',
            'platform.category' => 'nullable|string',
            'platform.is_ai_tool' => 'boolean',
            'usage.active_ms' => 'integer',
            'usage.idle_ms' => 'integer',
            'usage.open_ms' => 'integer',
            'usage.click_count' => 'integer',
            'usage.interaction_count' => 'integer',
            'usage.page_count' => 'integer',
            'usage.tab_switch_count' => 'integer',
            'pages' => 'nullable|array',
            'local_signals' => 'nullable|array',
            'recommended_content_tags' => 'nullable|array',
        ]);

        // Convert potential numeric timestamps to datetime strings
        $started_at = is_numeric($validated['started_at']) ? date('Y-m-d H:i:s', $validated['started_at'] > 9999999999 ? $validated['started_at'] / 1000 : $validated['started_at']) : $validated['started_at'];
        $ended_at = is_numeric($validated['ended_at']) ? date('Y-m-d H:i:s', $validated['ended_at'] > 9999999999 ? $validated['ended_at'] / 1000 : $validated['ended_at']) : $validated['ended_at'];

        $session = ExtensionSession::updateOrCreate(
            [
                'user_id' => $user->id,
                'extension_device_id' => $device->id,
                'session_id_from_ext' => $validated['session_id'],
            ],
            [
                'started_at' => $started_at,
                'ended_at' => $ended_at,
                'platform_type' => $validated['platform']['type'] ?? null,
                'platform_domain' => $validated['platform']['domain'] ?? null,
                'platform_category' => $validated['platform']['category'] ?? null,
                'is_ai_tool' => $validated['platform']['is_ai_tool'] ?? false,
                'active_ms' => $validated['usage']['active_ms'] ?? 0,
                'idle_ms' => $validated['usage']['idle_ms'] ?? 0,
                'open_ms' => $validated['usage']['open_ms'] ?? 0,
                'click_count' => $validated['usage']['click_count'] ?? 0,
                'interaction_count' => $validated['usage']['interaction_count'] ?? 0,
                'page_count' => $validated['usage']['page_count'] ?? 0,
                'tab_switch_count' => $validated['usage']['tab_switch_count'] ?? 0,
                'pages' => $validated['pages'] ?? null,
                'local_signals' => $validated['local_signals'] ?? null,
                'recommended_content_tags' => $validated['recommended_content_tags'] ?? null,
            ]
        );

        return response()->json([
            'data' => [
                'saved' => true,
                'internal_id' => $session->id
            ]
        ], 201);
    }

    /**
     * POST /api/extension/metrics-snapshots
     * Save a metrics snapshot sent from the extension
     */
    public function storeMetricsSnapshot(Request $request)
    {
        Log::info('Extension Metrics Snapshot API hit', $request->all());

        $user = $request->user();
        
        $device = ExtensionDevice::where('user_id', $user->id)
            ->whereNull('revoked_at')
            ->orderBy('last_active_at', 'desc')
            ->first();

        if (!$device) {
            return response()->json(['message' => 'No active extension device found for user.'], 403);
        }

        $validated = $request->validate([
            'captured_at' => 'required',
            'window_minutes' => 'integer',
            'metrics.focus_score' => 'integer|nullable',
            'metrics.productivity_score' => 'integer|nullable',
            'metrics.ai_adoption_score' => 'integer|nullable',
            'metrics.workflow_efficiency_score' => 'integer|nullable',
            'metrics.active_ms' => 'integer',
            'metrics.idle_ms' => 'integer',
            'metrics.context_switch_count' => 'integer',
            'metrics.tab_switches_per_hour' => 'integer',
            'top_platforms' => 'nullable|array',
            'detected_patterns' => 'nullable|array',
        ]);

        $captured_at = is_numeric($validated['captured_at']) ? date('Y-m-d H:i:s', $validated['captured_at'] > 9999999999 ? $validated['captured_at'] / 1000 : $validated['captured_at']) : $validated['captured_at'];

        $snapshot = ExtensionMetricsSnapshot::updateOrCreate(
            [
                'user_id' => $user->id,
                'extension_device_id' => $device->id,
                'captured_at' => $captured_at,
            ],
            [
                'window_minutes' => $validated['window_minutes'] ?? 60,
                'focus_score' => $validated['metrics']['focus_score'] ?? null,
                'productivity_score' => $validated['metrics']['productivity_score'] ?? null,
                'ai_adoption_score' => $validated['metrics']['ai_adoption_score'] ?? null,
                'workflow_efficiency_score' => $validated['metrics']['workflow_efficiency_score'] ?? null,
                'active_ms' => $validated['metrics']['active_ms'] ?? 0,
                'idle_ms' => $validated['metrics']['idle_ms'] ?? 0,
                'context_switch_count' => $validated['metrics']['context_switch_count'] ?? 0,
                'tab_switches_per_hour' => $validated['metrics']['tab_switches_per_hour'] ?? 0,
                'top_platforms' => $validated['top_platforms'] ?? null,
                'detected_patterns' => $validated['detected_patterns'] ?? null,
            ]
        );

        // Optional: Update device's last active at
        $device->update(['last_active_at' => now()]);

        return response()->json([
            'data' => [
                'saved' => true,
                'snapshot_id' => $snapshot->id
            ]
        ], 201);
    }
    public function storeDailyRollup(Request $request)
    {
        Log::info('Extension Daily Rollup API hit', $request->all());
        $user = $request->user();
        
        $device = ExtensionDevice::where('user_id', $user->id)
            ->whereNull('revoked_at')->orderBy('last_active_at', 'desc')->first();

        if (!$device) {
            return response()->json(['message' => 'No active extension device found for user.'], 403);
        }

        $validated = $request->validate([
            'date' => 'required',
            'timezone' => 'nullable|string',
            'summary.total_active_ms' => 'integer',
            'summary.total_idle_ms' => 'integer',
            'summary.total_open_ms' => 'integer',
            'summary.sessions_count' => 'integer',
            'summary.focus_score_avg' => 'integer|nullable',
            'summary.productivity_score_avg' => 'integer|nullable',
            'summary.ai_adoption_score' => 'integer|nullable',
            'top_platforms' => 'nullable|array',
            'top_ai_tools' => 'nullable|array',
            'student_learning_needs' => 'nullable|array',
        ]);

        $rollup = \App\Models\ExtensionDailyRollup::updateOrCreate(
            ['user_id' => $user->id, 'date' => $validated['date']],
            [
                'extension_device_id' => $device->id,
                'timezone' => $validated['timezone'] ?? null,
                'total_active_ms' => $validated['summary']['total_active_ms'] ?? 0,
                'total_idle_ms' => $validated['summary']['total_idle_ms'] ?? 0,
                'total_open_ms' => $validated['summary']['total_open_ms'] ?? 0,
                'sessions_count' => $validated['summary']['sessions_count'] ?? 0,
                'focus_score_avg' => $validated['summary']['focus_score_avg'] ?? null,
                'productivity_score_avg' => $validated['summary']['productivity_score_avg'] ?? null,
                'ai_adoption_score' => $validated['summary']['ai_adoption_score'] ?? null,
                'top_platforms' => $validated['top_platforms'] ?? null,
                'top_ai_tools' => $validated['top_ai_tools'] ?? null,
                'student_learning_needs' => $validated['student_learning_needs'] ?? null,
            ]
        );

        return response()->json(['data' => ['saved' => true, 'rollup_id' => $rollup->id]], 201);
    }

    public function generateContextualRecommendation(Request $request)
    {
        Log::info('Extension Recommendation Request API hit', $request->all());
        $user = $request->user();
        $device = ExtensionDevice::where('user_id', $user->id)->whereNull('revoked_at')->orderBy('last_active_at', 'desc')->first();

        $validated = $request->validate([
            'trigger.type'   => 'nullable|string',
            'current_context'=> 'nullable|array',
            'recent_behavior'=> 'nullable|array',
            'local_signals'  => 'nullable|array',
        ]);

        // ── Domain → Keywords extraction ─────────────────────────────────
        // youtube.com      → ['youtube']
        // app.slack.com    → ['slack', 'app.slack.com']
        // chatgpt.com      → ['chatgpt', 'openai']
        // mail.google.com  → ['gmail', 'google']
        $domain  = $validated['current_context']['domain'] ?? null;
        $content = null;

        if ($domain) {
            $keywords = $this->extractKeywords($domain);
            Log::info("Recommendation domain={$domain}, keywords=" . implode(',', $keywords));

            // Exclude already completed/watched videos
            $completedIds = \App\Models\UserVideoProgress::where('user_id', $user->id)
                ->where('completed', true)
                ->pluck('content_id');

            // Exclude recently recommended videos (last 5) to keep suggestions fresh
            $recentRecIds = \App\Models\ExtensionRecommendation::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->pluck('content_id');

            // Try each keyword in priority order
            foreach ($keywords as $keyword) {
                // Try 1: Fresh matches (unwatched & not recently suggested)
                $content = \App\Models\Content::where('status', 'active')
                    ->where('connected_tools', 'like', "%{$keyword}%")
                    ->whereNotIn('id', $completedIds)
                    ->whereNotIn('id', $recentRecIds)
                    ->inRandomOrder()
                    ->first();

                // Try 2: Fallback (unwatched but allowed recently suggested)
                if (!$content) {
                    $content = \App\Models\Content::where('status', 'active')
                        ->where('connected_tools', 'like', "%{$keyword}%")
                        ->whereNotIn('id', $completedIds)
                        ->inRandomOrder()
                        ->first();
                }

                // Try 3: Absolute fallback (any tool video)
                if (!$content) {
                    $content = \App\Models\Content::where('status', 'active')
                        ->where('connected_tools', 'like', "%{$keyword}%")
                        ->inRandomOrder()
                        ->first();
                }

                if ($content) {
                    Log::info("Matched content '{$content->title}' via keyword '{$keyword}'");
                    break;
                }
            }
        }

        // Fallback: return any active content if no tool match found
        if (!$content) {
            $completedIds = \App\Models\UserVideoProgress::where('user_id', $user->id)->where('completed', true)->pluck('content_id');
            $content = \App\Models\Content::where('status', 'active')
                ->whereNotIn('id', $completedIds)
                ->inRandomOrder()
                ->first();

            if (!$content) {
                $content = \App\Models\Content::where('status', 'active')->inRandomOrder()->first();
            }
            Log::info('No tool-specific match, using random fallback');
        }

        $rec = \App\Models\ExtensionRecommendation::create([
            'user_id'             => $user->id,
            'extension_device_id' => $device ? $device->id : null,
            'content_id'          => $content ? $content->id : null,
            'trigger_type'        => $validated['trigger']['type'] ?? null,
            'current_context'     => $validated['current_context'] ?? null,
            'recent_behavior'     => $validated['recent_behavior'] ?? null,
            'local_signals'       => $validated['local_signals'] ?? null,
        ]);

        return response()->json([
            'data' => [
                'recommendation_id' => $rec->id,
                'lesson'            => $content ? [
                    'id'           => $content->id,
                    'title'        => $content->title,
                    'url'          => route('learn.watch', $content),
                    'thumbnail'    => $content->thumbnail_url ?? null,
                    'matched_domain'=> $domain,
                ] : null,
            ]
        ], 200);
    }

    /**
     * Extract a priority-ordered list of keywords from a domain so we try
     * the most specific match first before falling back to broader terms.
     */
    private function extractKeywords(string $domain): array
    {
        $domain   = strtolower(trim($domain));
        $keywords = [];

        $clean = preg_replace('/^www\./', '', $domain);
        $clean = preg_replace('/\.(com|org|net|io|ai|co|app|dev|edu|gov)(\.[a-z]{2})?$/', '', $clean);
        $parts = explode('.', $clean);

        // Prefer the 2nd-to-last part (root domain), e.g. "slack" from "app.slack"
        if (count($parts) >= 2) {
            $keywords[] = end($parts);
            $keywords[] = $parts[0];
        } else {
            $keywords[] = $parts[0];
        }

        $keywords[] = $clean;
        $keywords[] = $domain;

        // Well-known domain aliases for better matching
        $aliases = [
            'mail.google'     => ['gmail', 'google'],
            'docs.google'     => ['google docs', 'google'],
            'sheets.google'   => ['google sheets', 'google'],
            'drive.google'    => ['google drive', 'google'],
            'meet.google'     => ['google meet', 'google'],
            'teams.microsoft' => ['teams', 'microsoft'],
            'outlook'         => ['email', 'microsoft'],
            'chatgpt'         => ['chatgpt', 'openai'],
            'claude'          => ['claude', 'anthropic'],
            'perplexity'      => ['perplexity'],
            'youtube'         => ['youtube'],
            'linkedin'        => ['linkedin'],
            'notion'          => ['notion'],
            'slack'           => ['slack'],
            'zoom'            => ['zoom'],
            'canva'           => ['canva'],
            'grammarly'       => ['grammarly'],
            'github'          => ['github'],
            'figma'           => ['figma'],
        ];

        foreach ($aliases as $pattern => $mapped) {
            if (str_contains($clean, $pattern)) {
                $keywords = array_merge($mapped, $keywords);
                break;
            }
        }

        return array_values(array_unique($keywords));
    }

    public function storeRecommendationEvent(Request $request)
    {
        Log::info('Extension Recommendation Event API hit', $request->all());
        
        $validated = $request->validate([
            'recommendation_id' => 'required|exists:extension_recommendations,id',
            'event' => 'required|string',
            'timestamp' => 'required',
            'context' => 'nullable|array',
        ]);

        $timestamp = is_numeric($validated['timestamp']) ? date('Y-m-d H:i:s', $validated['timestamp'] > 9999999999 ? $validated['timestamp'] / 1000 : $validated['timestamp']) : $validated['timestamp'];

        \App\Models\ExtensionRecommendationEvent::create([
            'ext_recommendation_id' => $validated['recommendation_id'],
            'event_type' => $validated['event'],
            'occurred_at' => $timestamp,
            'context' => $validated['context'] ?? null,
        ]);

        return response()->json(['data' => ['saved' => true]], 201);
    }

    public function askHelp(Request $request)
    {
        $user = $request->user();
        $deviceId = $request->header('X-Extension-Device-Id');
        
        Log::info("Mentor Help Request - User ID: {$user->id}, Is Employee: " . ($user->is_employee ? 'Yes' : 'No') . ", Header Device ID: {$deviceId}");
        
        $device = ExtensionDevice::where('user_id', $user->id)
            ->when($deviceId, function($q) use ($deviceId) {
                return $q->where('device_id', $deviceId);
            })
            ->whereNull('revoked_at')
            ->orderBy('last_active_at', 'desc')
            ->first();

        Log::info("Mentor Help Request - Device Found: " . ($device ? $device->id : 'NOT FOUND'));

        $validated = $request->validate([
            'query' => 'required|string',
            'url' => 'nullable|string',
            'domain' => 'nullable|string',
        ]);

        $helpRequest = \App\Models\ExtensionHelpRequest::create([
            'user_id' => $user->id,
            'extension_device_id' => $device ? $device->id : null,
            'query' => $validated['query'],
            'url' => $validated['url'],
            'domain' => $validated['domain'] ?? null,
        ]);

        // ── GPT-4o-mini Integration for Suggested Lessons ────────────────
        $suggestedLessons = [];
        $domain = $validated['domain'] ?? null;
        
        if ($domain) {
            $keywords = $this->extractKeywords($domain);
            
            // Filter lessons by domain keywords first
            $availableLessons = \App\Models\Content::where('status', 'active')
                ->where(function($q) use ($keywords) {
                    foreach ($keywords as $kw) {
                        $q->orWhere('connected_tools', 'like', "%{$kw}%")
                          ->orWhere('tags', 'like', "%{$kw}%")
                          ->orWhere('category', 'like', "%{$kw}%")
                          ->orWhere('title', 'like', "%{$kw}%");
                    }
                })
                ->latest() // Prioritize newer content
                ->limit(100) // Increased limit to provide better variety to GPT
                ->get(['id', 'title', 'description']);

            if ($availableLessons->isNotEmpty()) {
                $apiKey = env('OPENAI_API_KEY');
                
                if ($apiKey) {
                    try {
                        $lessonContext = $availableLessons->map(fn($l) => "ID: {$l->id} | Title: {$l->title}")->implode("\n");
                        
                        $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
                            ->timeout(10)
                            ->post('https://api.openai.com/v1/chat/completions', [
                                'model' => 'gpt-4o-mini',
                                'messages' => [
                                    ['role' => 'system', 'content' => "You are an AI Mentor. Analyze the user's question and select the most relevant lessons from the provided list. Return a JSON object with a key 'ids' containing an array of relevant lesson IDs. Example: {\"ids\": [1, 2, 3]}. If none are relevant, return {\"ids\": []}."],
                                    ['role' => 'user', 'content' => "Domain: {$domain}\nUser Question: {$validated['query']}\n\nAvailable Lessons:\n{$lessonContext}"]
                                ],
                                'response_format' => ['type' => 'json_object']
                            ]);

                        if ($response->successful()) {
                            $data = $response->json();
                            $content = json_decode($data['choices'][0]['message']['content'], true);
                            $ids = $content['ids'] ?? $content; // Handle different JSON structures GPT might return
                            
                            if (is_array($ids)) {
                                $suggestedLessons = \App\Models\Content::whereIn('id', $ids)
                                    ->limit(5)
                                    ->get(['id', 'title', 'thumbnail_url', 'category', 'duration_label'])
                                    ->map(fn($l) => [
                                        'id' => $l->id,
                                        'title' => $l->title,
                                        'thumbnail' => $l->thumbnail_url,
                                        'category' => $l->category,
                                        'duration' => $l->duration_label,
                                        'url' => route('learn.watch', $l)
                                    ]);
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("GPT Help Request Error: " . $e->getMessage());
                    }
                }

                // Fallback to keyword relevance if AI fails or no key
                if (empty($suggestedLessons)) {
                    $suggestedLessons = $availableLessons->take(3)->map(fn($l) => [
                        'id' => $l->id,
                        'title' => $l->title,
                        'url' => route('learn.watch', $l)
                    ]);
                }
            }
        }

        return response()->json([
            'data' => [
                'saved' => true,
                'help_request_id' => $helpRequest->id,
                'suggested_lessons' => $suggestedLessons
            ]
        ], 201);
    }
}
