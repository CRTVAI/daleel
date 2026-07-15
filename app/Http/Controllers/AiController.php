<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;

class AiController extends Controller
{
    /**
     * Show the AI Mentor workspace with smart video search and history.
     */
    public function mentor(Request $request)
    {
        $query = $request->get('query');
        $domain = $request->get('domain');
        $deviceId = $request->get('device_id');
        $url = $request->get('url'); // Capture the full URL if sent (smarter)
        
        $history = collect();
        // ... (History and internalDeviceId logic remains identical)
        $internalDeviceId = null;
        if ($deviceId) {
            $internalDeviceId = \App\Models\ExtensionDevice::where('device_id', $deviceId)->value('id');
        }

        if (auth()->check()) {
            $history = \App\Models\ExtensionHelpRequest::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($internalDeviceId) {
            $history = \App\Models\ExtensionHelpRequest::where('extension_device_id', $internalDeviceId)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Auto-detect domain/url from the most recent help request if not provided in URL
        if (!$domain && $query) {
            $hrQuery = \App\Models\ExtensionHelpRequest::where('query', $query)->latest();
            if (auth()->check()) { $hrQuery->where('user_id', auth()->id()); }
            elseif ($internalDeviceId) { $hrQuery->where('extension_device_id', $internalDeviceId); }
            
            $hrRecord = $hrQuery->first();
            $domain = $hrRecord->domain ?? null;
            $url = $hrRecord->url ?? null;
        }

        // ── Smart Context-Driven Search ────────────────────────────────
        $searchContext = $url ?: $domain; // Prefer URL for path-aware detection

        if ($query && $searchContext) {
            $keywords = $this->extractKeywords($searchContext);
            
            // Step 1: Professional SQL pre-filtering
            $availableLessons = Content::where('status', 'active')
                ->where(function($q) use ($keywords, $searchContext) {
                    foreach ($keywords as $kw) {
                        $q->orWhere('title', 'like', "%{$kw}%")
                          ->orWhere('connected_tools', 'like', "%{$kw}%")
                          ->orWhere('tags', 'like', "%{$kw}%")
                          ->orWhere('category', 'like', "%{$kw}%");
                    }
                    // Also match literal context
                    $q->orWhere('title', 'like', "%{$searchContext}%");
                })
                ->latest()
                ->limit(100)
                ->get(['id', 'title', 'description']);

            if ($availableLessons->isNotEmpty()) {
                // ... (GPT relevance ranking logic remains identical)
                $selectedIds = [];
                $apiKey = env('OPENAI_API_KEY');
                if ($apiKey) {
                    try {
                        $lessonContext = $availableLessons->map(fn($l) => "ID: {$l->id} | Title: {$l->title} | Description: " . \Illuminate\Support\Str::limit($l->description ?? '', 100))->implode("\n");
                        $systemPrompt = \App\Models\AiPrompt::getPrompt('ai_mentor_system', [
                            'search_context' => $searchContext
                        ]);
                        $userPrompt = \App\Models\AiPrompt::getPrompt('ai_mentor_user', [
                            'query' => $query,
                            'lesson_context' => $lessonContext
                        ]);

                        $response = \Illuminate\Support\Facades\Http::withToken($apiKey)->timeout(12)->post('https://api.openai.com/v1/chat/completions', [
                            'model' => 'gpt-4o-mini',
                            'messages' => [
                                ['role' => 'system', 'content' => $systemPrompt],
                                ['role' => 'user', 'content' => $userPrompt]
                            ],
                            'response_format' => ['type' => 'json_object'],
                        ]);
                        if ($response->successful()) {
                            $gptResult = json_decode($response->json()['choices'][0]['message']['content'], true);
                            $selectedIds = $gptResult['ids'] ?? [];
                        }
                    } catch (\Exception $e) {}
                }
                $suggestedVideos = !empty($selectedIds) 
                    ? Content::whereIn('id', $selectedIds)->get()
                    : Content::whereIn('id', $availableLessons->pluck('id'))->get();

                return view('ai-mentor', compact('suggestedVideos', 'history', 'query', 'domain', 'url'));
            }
        }
        // ... (Fallback logic handles standard cases)

        $suggestedVideos = collect();

        if ($query) {
            // Stopwords to filter out low-value keywords
            $stopwords = ['which', 'what', 'where', 'how', 'when', 'that', 'this', 'there', 'their', 'with', 'from', 'about', 'some', 'your', 'will', 'been', 'batter', 'better'];
            
            $keywords = collect(explode(' ', $query))
                ->map(fn($w) => strtolower(trim(preg_replace('/[^A-Za-z0-9]/', '', $w))))
                ->filter(fn($w) => strlen($w) > 2 && !in_array($w, $stopwords))
                ->values();

            $suggestedVideos = Content::where('status', 'active')
                ->where(function ($q) use ($keywords, $query) {
                    // Full phrase match check
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                    
                    foreach ($keywords as $word) {
                        $q->orWhere('title', 'like', "%{$word}%")
                          ->orWhere('tags', 'like', "%{$word}%")
                          ->orWhere('category', 'like', "%{$word}%")
                          ->orWhere('description', 'like', "%{$word}%");
                    }
                })
                ->limit(12)
                ->get()
                ->map(function($content) use ($keywords, $query) {
                    // Custom relevance scoring
                    $score = 0;
                    $title = strtolower($content->title);
                    $desc = strtolower($content->description);
                    $q = strtolower($query);

                    if (stripos($title, $q) !== false) $score += 20;
                    if (stripos($desc, $q) !== false) $score += 10;

                    foreach ($keywords as $word) {
                        if (stripos($title, $word) !== false) $score += 5;
                        if (stripos($desc, $word) !== false) $score += 2;
                        if (stripos($content->tags, $word) !== false) $score += 3;
                    }

                    $content->relevance_score = $score;
                    return $content;
                })
                ->sortByDesc('relevance_score');
        } else {
            // Fallback: Show latest videos if no query
            $suggestedVideos = Content::where('status', 'active')
                ->latest()
                ->limit(12)
                ->get();
        }

        return view('ai-mentor', compact('suggestedVideos', 'history', 'query', 'domain'));
    }

    /**
     * Extract a priority-ordered list of keywords from a domain and context.
     */
    private function extractKeywords(string $domain): array
    {
        $domain = strtolower(trim($domain));
        
        // Remove protocols/whitespace if any accidentally leaked
        $domain = preg_replace('/^https?:\/\//', '', $domain);
        $domain = explode('/', $domain)[0]; // Focus on the host for extraction

        $keywords = [];

        // 1. Precise Pattern Matching (Highest Priority)
        // This handles subdomains and paths like docs.google.com/spreadsheets
        $patterns = [
            // Google Suite
            'sheets.google.com' => ['google sheets', 'sheets', 'spreadsheet', 'google'],
            'docs.google.com'   => ['google docs', 'docs', 'writing', 'google'],
            'mail.google.com'   => ['gmail', 'email', 'google'],
            'drive.google.com'  => ['google drive', 'drive', 'storage', 'google'],
            'meet.google.com'   => ['google meet', 'meet', 'video', 'google'],
            
            // Dev Tools
            'github.com'        => ['github', 'git', 'repository', 'code', 'coding'],
            'gitlab.com'        => ['gitlab', 'git', 'coding'],
            'bitbucket.org'     => ['bitbucket', 'git'],
            'stackoverflow.com' => ['stackoverflow', 'programming', 'code', 'help'],
            'npmjs.com'         => ['npm', 'javascript', 'node'],
            
            // AI Platforms
            'chatgpt.com'       => ['chatgpt', 'openai', 'ai', 'chat'],
            'chat.openai.com'   => ['chatgpt', 'openai', 'ai'],
            'claude.ai'         => ['claude', 'anthropic', 'ai'],
            'perplexity.ai'     => ['perplexity', 'ai', 'search'],
            
            // Design & Productivity
            'figma.com'         => ['figma', 'design', 'ui', 'ux'],
            'canva.com'         => ['canva', 'design', 'graphics'],
            'notion.so'         => ['notion', 'notes', 'docs', 'workspace'],
            'slack.com'         => ['slack', 'chat', 'messaging'],
            'trello.com'        => ['trello', 'kanban', 'project management'],
            'zoom.us'           => ['zoom', 'video'],
        ];

        foreach ($patterns as $pattern => $mapped) {
            if (str_contains($domain, $pattern)) {
                $keywords = array_merge($keywords, $mapped);
            }
        }

        // 2. Generic Domain Parsing (Middle Priority)
        // Extracts "github" from "github.com" or "tracking" from "tracking.app"
        $clean = preg_replace('/^www\./', '', $domain);
        $clean = preg_replace('/\.(com|org|net|io|ai|co|app|dev|edu|gov|so|us|me)(\.[a-z]{2})?$/', '', $clean);
        $parts = explode('.', $clean);

        foreach ($parts as $part) {
            if (strlen($part) > 2) {
                $keywords[] = $part;
            }
        }
        
        $keywords[] = $clean; // Full domain word (e.g., "chatgpt")
        $keywords[] = $domain; // Full host (e.g., "chatgpt.com")

        // 3. Fallback/Contextual Expansion
        // Adds common industry terms if certain keywords exist
        if (in_array('git', $keywords)) $keywords[] = 'version control';
        if (in_array('ai', $keywords))  $keywords[] = 'artificial intelligence';

        return array_values(array_unique($keywords));
    }

    public function index()
    {
        return redirect()->route('ai.mentor');
    }
}
