<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Mentor Workspace - Dallel AI</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #1e293b; margin: 0; }
        .mentor-header { background: #000; color: #fff; padding: 0.75rem 2rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 1000; border-bottom: 1px solid rgba(255,255,255,0.1); }
        
        /* Branding sync from site.css */
        .brand { display: flex; align-items: center; gap: 10px; color: #fff !important; }
        .brand-mark { width: 32px; height: 32px; background: #6366f1; color: white; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-weight: 900; font-size: 14px; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4); }
        .brand-copy { display: flex; flex-direction: column; line-height: 1; }
        .brand-copy strong { font-size: 16px; letter-spacing: -0.02em; }
        .brand-copy small { font-size: 10px; opacity: 0.6; text-transform: uppercase; font-weight: 700; margin-top: 2px; }

        .mentor-container { display: flex; height: calc(100vh - 57px); }
        
        /* Sidebar */
        .mentor-sidebar { width: 350px; background: #fff; border-right: 1px solid #e2e8f0; display: flex; flex-direction: column; }
        .sidebar-head { padding: 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fff; }
        .sidebar-history { flex: 1; overflow-y: auto; padding: 1rem; }
        .history-card { padding: 1.25rem; border-radius: 12px; border: 1px solid #f1f5f9; cursor: pointer; transition: all 0.2s; margin-bottom: 0.75rem; background: #fff; text-decoration: none; display: block; }
        .history-card:hover { border-color: #4f46e5; background: #f5f3ff; }
        .history-card.active { background: #4f46e5; border-color: #4f46e5; color: #fff !important; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3); }
        .history-card.active .text-muted { color: rgba(255,255,255,0.7) !important; }
        
        /* Main Content */
        .mentor-main { flex: 1; overflow-y: auto; padding: 2rem; }
        .search-hero { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); border-radius: 24px; padding: 3rem 2rem; margin-bottom: 2.5rem; color: #fff; position: relative; overflow: hidden; }
        .search-hero::after { content: ''; position: absolute; top: -50%; right: -10%; width: 300px; height: 300px; background: rgba(255,255,255,0.1); border-radius: 50%; }
        
        .video-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; }
        .video-card { background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); height: 100%; display: flex; flex-direction: column; text-decoration: none; }
        .video-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); border-color: #4f46e5; }
        .video-thumb { position: relative; aspect-ratio: 16/9; overflow: hidden; }
        .video-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .video-duration { position: absolute; bottom: 8px; right: 8px; background: rgba(0,0,0,0.8); color: #fff; font-size: 11px; padding: 2px 6px; border-radius: 4px; font-weight: 700; }
        .video-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
        .video-title { font-weight: 800; font-size: 1rem; color: #1e293b; line-height: 1.4; margin-bottom: 0.5rem; }
        .video-meta { font-size: 0.75rem; color: #64748b; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }
        
        .badge-ai { background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); color: #fff; padding: 0.4rem 1rem; border-radius: 100px; font-size: 0.75rem; font-weight: 800; border: 1px solid rgba(255,255,255,0.3); }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body>

<header class="mentor-header">
    <div class="d-flex align-items-center gap-3">
        <a href="/" class="brand text-decoration-none">
            <span class="brand-mark">DA</span>
            <span class="brand-copy d-none d-sm-inline"><strong>Dallel AI</strong><small>AI Mentor</small></span>
        </a>
        <div class="vr mx-2 bg-white opacity-25 d-none d-md-block" style="height: 24px;"></div>
        <span class="fw-800 text-uppercase small tracking-widest d-none d-md-inline" style="letter-spacing: 0.1em; color: rgba(255,255,255,0.6);">WORKSPACE</span>
    </div>

    <div class="nav-actions d-flex align-items-center gap-2">
        @if(auth()->check())
            <div class="d-flex align-items-center gap-3">
                <div class="user-pill d-none d-lg-flex align-items-center gap-2 px-3 py-1 bg-white bg-opacity-10 rounded-pill border border-white border-opacity-10">
                    <div style="width: 20px; height: 20px; background: #6366f1; border-radius: 50%; font-size: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #fff;">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <span class="small fw-600 text-white">{{ auth()->user()->name }}</span>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm rounded-pill px-4 fw-800">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-link text-white text-decoration-none fw-700 small px-3">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-800 shadow-sm" style="background: #6366f1; border: none;">Start Free</a>
        @endif
    </div>
</header>

<div class="mentor-container">
    <aside class="mentor-sidebar">
        <div class="sidebar-head">
            <h6 class="fw-800 text-dark mb-1"><i class="bi bi-clock-history me-2 text-primary"></i>Your Intelligence Feed</h6>
            <p class="text-muted small mb-0">Previous help requests across your tools.</p>
        </div>
        <div class="sidebar-history">
            @forelse($history as $item)
                @php
                    $urlParams = ['query' => $item->query, 'domain' => $item->domain];
                    if(request()->has('device_id')) $urlParams['device_id'] = request()->device_id;
                @endphp
                <a href="{{ route('ai.mentor', $urlParams) }}" class="history-card {{ $query == $item->query ? 'active' : '' }}">
                    <div class="fw-700 small mb-1" style="line-height: 1.3;">"{{ $item->query }}"</div>
                    <div class="text-muted" style="font-size: 10px;">
                        {{ $item->created_at->diffForHumans() }} · <span class="text-uppercase">{{ $item->domain ?: 'Browser' }}</span>
                    </div>
                </a>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-chat-left-dots fs-1 opacity-25"></i>
                    <p class="small mt-2 px-4">Your help history will appear here once you ask the mentor a question.</p>
                </div>
            @endforelse
        </div>
    </aside>

    <main class="mentor-main">
        @if($query)
            <div class="search-hero">
                <div class="badge-ai mb-3 d-inline-block">Dallel AI INTELLIGENCE ENGINE</div>
                <h1 class="fw-800 mb-2" style="letter-spacing: -0.04em; font-size: 2.5rem;">Intelligence Analysis</h1>
                <p class="opacity-75 fs-5">Matching your request: <strong>"{{ $query }}"</strong></p>
                <div class="mt-4 d-flex gap-3">
                   <span class="badge bg-white text-dark rounded-pill px-3 py-2 fw-800 shadow-sm">{{ $suggestedVideos->count() }} Training Videos Found</span>
                   @if($suggestedVideos->first() && $suggestedVideos->first()->relevance_score > 15)
                    <span class="badge bg-success text-white rounded-pill px-3 py-2 fw-800 shadow-sm border-0"><i class="bi bi-stars me-1"></i> HIGH RELEVANCE MATCH</span>
                   @endif
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <div style="font-size: 4rem;" class="mb-3">✨</div>
                <h1 class="fw-800 text-dark" style="letter-spacing: -0.04em;">Ready to help, Mentor.</h1>
                <p class="text-muted fs-5">Select a request from the sidebar or ask via the extension.</p>
            </div>
        @endif

        <div class="video-grid">
            @forelse($suggestedVideos as $video)
                <a href="{{ route('learn.watch', $video->id) }}?from=mentor&query={{ urlencode($query) }}" class="video-card">
                    <div class="video-thumb">
                        <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}">
                        <span class="video-duration">{{ $video->duration_label }}</span>
                    </div>
                    <div class="video-body">
                        <div class="video-meta">
                            <span class="badge bg-light text-dark border">{{ strtoupper($video->category ?: 'TRAINING') }}</span>
                            @if($video->skill_level)
                                <span class="text-primary fw-800">{{ strtoupper($video->skill_level) }}</span>
                            @endif
                        </div>
                        <h6 class="video-title mb-2">{{ $video->title }}</h6>
                        @if($video->description)
                            <p class="text-muted small mb-3 text-truncate" style="opacity: 0.8; -webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden; white-space: normal;">
                                {{ $video->description }}
                            </p>
                        @endif
                        <div class="mt-auto d-flex align-items-center text-primary fw-800 small">
                            <i class="bi bi-play-circle-fill me-2 fs-5"></i> START LEARNING
                        </div>
                    </div>
                </a>
            @empty
                @if($query)
                    <div class="col-12 text-center py-5 bg-white rounded-4 border">
                        <i class="bi bi-search text-muted opacity-25" style="font-size: 3rem;"></i>
                        <h4 class="fw-800 text-dark mt-3">No specific matches found</h4>
                        <p class="text-muted">I've searched your training library but didn't find exact matches for this query.</p>
                    </div>
                @endif
            @endforelse
        </div>
    </main>
</div>

</body>
</html>
