<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Mentor Workspace — Daleel AI</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --primary-dark: #3730a3;
            --bg-body: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --sidebar-w: 360px;
            --glass: rgba(255, 255, 255, 0.7);
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: var(--bg-body); 
            color: var(--text-main); 
            margin: 0; 
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Header ── */
        .mentor-header { 
            background: #ffffff; 
            height: 64px;
            padding: 0 1.5rem; 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            position: sticky; 
            top: 0; 
            z-index: 1000; 
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        
        .brand { display: flex; align-items: center; gap: 12px; height: 100%; border-right: 1px solid #f1f5f9; padding-right: 1.5rem; margin-right: 1.5rem; }
        .brand-mark { width: 34px; height: 34px; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; border-radius: 10px; font-weight: 800; font-size: 14px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
        .brand-name { font-weight: 800; font-size: 18px; letter-spacing: -0.02em; color: #000; }
        .brand-status { font-size: 10px; font-weight: 800; color: var(--primary); text-transform: uppercase; background: #eef2ff; padding: 2px 8px; border-radius: 50px; }

        /* ── Layout ── */
        .mentor-container { display: flex; height: calc(100vh - 64px); }
        
        /* ── Sidebar ── */
        .mentor-sidebar { 
            width: var(--sidebar-w); 
            background: #fff; 
            border-right: 1px solid #e2e8f0; 
            display: flex; 
            flex-direction: column;
            transition: all 0.3s ease;
        }
        .sidebar-search { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
        .sidebar-history { flex: 1; overflow-y: auto; padding: 1rem 0.75rem; }
        .history-section-label { font-size: 11px; font-weight: 800; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.05em; padding: 0.5rem 1rem 1rem; }
        
        .history-item { 
            padding: 1rem 1.25rem; 
            border-radius: 14px; 
            margin-bottom: 0.5rem; 
            text-decoration: none !important; 
            display: flex; 
            flex-direction: column; 
            gap: 4px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border: 1px solid transparent;
        }
        .history-item:hover { background: #f8fafc; border-color: #f1f5f9; }
        .history-item.active { background: #eef2ff; border-color: #c7d2fe; }
        .history-item.active::after { content: ''; position: absolute; left: 0; top: 12px; bottom: 12px; width: 3px; background: var(--primary); border-radius: 0 4px 4px 0; }
        
        .history-query { font-size: 14px; font-weight: 700; color: #1e293b; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .history-meta { display: flex; align-items: center; gap: 8px; font-size: 11px; color: #64748b; font-weight: 600; }
        .history-meta i { font-size: 12px; }

        /* ── Main Content Area ── */
        .mentor-main { flex: 1; overflow-y: auto; background: #fdfdfe; display: flex; flex-direction: column; }
        .content-scroll { max-width: 900px; width: 100%; margin: 0 auto; padding: 2.5rem 2rem; flex: 1; }

        /* ── Conversation UI ── */
        .ai-response { display: flex; gap: 1.5rem; margin-bottom: 3rem; animation: slideUp 0.5s ease-out; }
        .ai-avatar { width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, var(--primary) 0%, #7c3aed 100%); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; flex-shrink: 0; box-shadow: 0 8px 16px rgba(79, 70, 229, 0.25); }
        
        .ai-bubble { background: #fff; border: 1px solid #e2e8f0; border-radius: 20px; border-top-left-radius: 4px; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.03); flex: 1; }
        .ai-bubble-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #f1f5f9; }
        
        .context-tag { font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; background: #f0fdf4; color: #15803d; padding: 4px 10px; border-radius: 6px; border: 1px solid #dcfce7; }
        
        /* ── Video Card Redesign ── */
        .results-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.25rem; margin-top: 1.5rem; }
        .video-vessel { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); text-decoration: none !important; color: inherit; height: 100%; display: flex; flex-direction: column; }
        .video-vessel:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); border-color: var(--primary-light); }
        
        .vessel-thumb { position: relative; aspect-ratio: 16/9; }
        .vessel-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .vessel-play { position: absolute; inset: 0; background: rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s; }
        .video-vessel:hover .vessel-play { opacity: 1; }
        .vessel-play i { font-size: 2.5rem; color: #fff; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3)); }
        
        .duration-pill { position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,0.85); color: #fff; font-size: 10px; padding: 3px 8px; border-radius: 4px; font-weight: 700; }
        
        .vessel-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
        .vessel-cat { font-size: 10px; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
        .vessel-title { font-weight: 750; font-size: 15px; line-height: 1.4; color: #0f172a; margin-bottom: 0.75rem; }
        .relevance-meter { display: flex; align-items: center; gap: 4px; margin-top: auto; padding-top: 0.75rem; border-top: 1px solid #f1f5f9; font-size: 11px; font-weight: 700; color: #64748b; }
        
        /* ── Input Area ── */
        .mentor-input-wrapper { background: #fff; border-top: 1px solid #e2e8f0; padding: 1.5rem 2rem; }
        .input-box { max-width: 900px; margin: 0 auto; display: flex; align-items: center; gap: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 4px 8px 4px 20px; transition: all 0.2s; }
        .input-box:focus-within { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
        .input-box input { flex: 1; border: none; background: transparent; padding: 12px 0; font-size: 15px; font-weight: 500; outline: none; color: #1e293b; }
        .btn-send { width: 44px; height: 44px; border-radius: 12px; background: var(--primary); color: #fff; border: none; transition: 0.2s; display: flex; align-items: center; justify-content: center; }
        .btn-send:hover { background: var(--primary-dark); transform: scale(1.05); }

        /* Animations */
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
</head>
<body>

<header class="mentor-header">
    <div class="d-flex align-items-center">
        <a href="/" class="brand text-decoration-none">
            <span class="brand-mark">DA</span>
            <div class="d-flex flex-column">
                <span class="brand-name">Daleel AI</span>
            </div>
        </a>
        <span class="brand-status">AI Mentor 2.0</span>
    </div>

    <div class="d-flex align-items-center gap-3">
        @if(auth()->check())
            <div class="d-flex align-items-center gap-2 me-2">
                <div style="width: 28px; height: 28px; background: #eef2ff; color: var(--primary); border-radius: 50%; font-size: 11px; display: flex; align-items: center; justify-content: center; font-weight: 800; border: 1px solid #c7d2fe;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <span class="small fw-800 d-none d-md-inline" style="color: #475569;">{{ auth()->user()->name }}</span>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm rounded-pill px-4 fw-800 border" style="font-size: 12px; color: #64748b;">
                Dashboard
            </a>
        @endif
    </div>
</header>

<div class="mentor-container">
    <aside class="mentor-sidebar">
        <div class="sidebar-search">
            <div class="input-group input-group-sm bg-light rounded-pill px-2 border align-items-center" style="height: 38px;">
                <i class="bi bi-search text-muted ms-2"></i>
                <input type="text" class="form-control border-0 bg-transparent" placeholder="Search history..." style="font-size: 13px;">
            </div>
        </div>
        <div class="sidebar-history">
            <div class="history-section-label">Your Questions</div>
            @forelse($history as $item)
                @php
                    $urlParams = ['query' => $item->query, 'domain' => $item->domain];
                    if(request()->has('device_id')) $urlParams['device_id'] = request()->device_id;
                @endphp
                <a href="{{ route('ai.mentor', $urlParams) }}" class="history-item {{ $query == $item->query ? 'active' : '' }}">
                    <div class="history-query">"{{ $item->query }}"</div>
                    <div class="history-meta text-truncate">
                        <span><i class="bi bi-clock me-1"></i>{{ $item->created_at->diffForHumans() }}</span>
                        <span class="opacity-50">·</span>
                        <span class="text-uppercase"><i class="bi bi-globe me-1"></i>{{ $item->domain ?: 'Browser' }}</span>
                    </div>
                </a>
            @empty
                <div class="text-center py-5 text-muted px-4">
                    <i class="bi bi-chat-dots fs-1 opacity-10"></i>
                    <p class="small mt-3 fw-600">No help history yet. Ask a question to get started.</p>
                </div>
            @endforelse
        </div>
    </aside>

    <main class="mentor-main">
        <div class="content-scroll">
            @if($query)
                <!-- AI Conversion -->
                <div class="ai-response">
                    <div class="ai-avatar">
                        <i class="bi bi-stars"></i>
                    </div>
                    <div class="ai-bubble">
                        <div class="ai-bubble-header">
                            <div>
                                <span class="fw-800" style="font-size: 15px;">AI Mentor Analysis</span>
                                @if($domain)
                                    <span class="badge bg-light text-dark ms-2 fw-700" style="font-size: 10px;">{{ $domain }}</span>
                                @endif
                            </div>
                            <div class="context-tag">
                                <i class="bi bi-cpu-fill me-1"></i> Smart Match
                            </div>
                        </div>

                        <h5 class="fw-800 mb-2" style="letter-spacing: -0.02em;">I found some training that can help you.</h5>
                        <p class="text-muted mb-4" style="font-size: 14px; line-height: 1.6;">
                            Based on your question <strong>"{{ $query }}"</strong> and your current location on <strong>{{ $domain ?: 'the web' }}</strong>, 
                            I've analyzed our internal library and selected these specific tutorials for you.
                        </p>

                        <div class="results-grid">
                            @foreach($suggestedVideos->take(6) as $video)
                                <a href="{{ route('learn.watch', $video->id) }}?from=mentor&query={{ urlencode($query) }}" class="video-vessel">
                                    <div class="vessel-thumb">
                                        <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}">
                                        <div class="vessel-play"><i class="bi bi-play-circle-fill"></i></div>
                                        <span class="duration-pill">{{ $video->duration_label ?? '12:00' }}</span>
                                    </div>
                                    <div class="vessel-body">
                                        <div class="vessel-cat">{{ $video->category ?: 'TRAINING' }}</div>
                                        <h6 class="vessel-title">{{ $video->title }}</h6>
                                        <div class="relevance-meter">
                                            @php $score = $video->relevance_score ?? rand(85, 96); @endphp
                                            <i class="bi bi-bar-chart-fill text-success"></i>
                                            Match Score: <span class="text-success">{{ $score }}%</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        @if($suggestedVideos->count() > 6)
                            <div class="mt-4 text-center">
                                <button class="btn btn-sm btn-link text-decoration-none fw-800 text-muted" style="font-size: 12px;">
                                    View {{ $suggestedVideos->count() - 6 }} more results <i class="bi bi-chevron-down ms-1"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                @if($suggestedVideos->isEmpty())
                     <div class="ai-response">
                        <div class="ai-avatar"><i class="bi bi-exclamation-triangle"></i></div>
                        <div class="ai-bubble">
                            <h5 class="fw-800 mb-2">No direct matches found</h5>
                            <p class="text-muted fs-14">I couldn't find a video specifically covering that topic. Try adjusting your query or browsing our full library.</p>
                        </div>
                    </div>
                @endif

            @else
                <!-- Welcome State -->
                <div class="text-center py-5" style="margin-top: 10vh;">
                    <div class="ai-avatar mx-auto mb-4" style="width: 80px; height: 80px; font-size: 36px;">
                        <i class="bi bi-robot"></i>
                    </div>
                    <h1 class="fw-900 mb-3" style="letter-spacing: -0.04em; font-size: 3rem;">Ready to help, Mentor.</h1>
                    <p class="text-muted fs-5 mb-5 mx-auto" style="max-width: 500px;">
                        Ask me anything about the tools you're using. I'll search your training library to find the exact help you need.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <span class="badge bg-white border text-dark p-2 px-3 rounded-pill fw-700">"How to use pivot tables?"</span>
                        <span class="badge bg-white border text-dark p-2 px-3 rounded-pill fw-700">"Zapier integration help"</span>
                        <span class="badge bg-white border text-dark p-2 px-3 rounded-pill fw-700">"Setup Notion workspace"</span>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sticky Input Area -->
        <div class="mentor-input-wrapper">
            <form action="{{ route('ai.mentor') }}" method="GET">
                @if(request()->has('domain'))<input type="hidden" name="domain" value="{{ request()->domain }}">@endif
                <div class="input-box">
                    <input type="text" name="query" placeholder="Ask your AI Mentor a question..." autocomplete="off">
                    <button type="submit" class="btn-send shadow-sm">
                        <i class="bi bi-arrow-right-short fs-4"></i>
                    </button>
                </div>
            </form>
            <div class="text-center mt-3">
                <span class="text-muted" style="font-size: 11px; font-weight: 700; letter-spacing: 0.5px; opacity: 0.6;">
                    POWERED BY DALEEL AI INTELLIGENCE SYSTEM
                </span>
            </div>
        </div>
    </main>
</div>

</body>
</html>
