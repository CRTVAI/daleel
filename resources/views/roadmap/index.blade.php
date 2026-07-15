@extends('layouts.user')

@section('title', 'My Learning Journeys — Daleel AI')

@section('styles')
<style>
    :root {
        --primary: #4f46e5;
        --primary-light: #818cf8;
        --bg-body: #f8fafc;
    }

    .page-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        border-radius: 24px;
        padding: 3rem 2.5rem;
        color: #fff;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px -10px rgba(30, 27, 75, 0.3);
    }
    .page-header::after {
        content: ''; position: absolute; top: -50%; right: -10%; width: 400px; height: 400px;
        background: rgba(99, 102, 241, 0.1); border-radius: 50%;
    }

    .roadmap-card {
        background: #fff;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .roadmap-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
        border-color: var(--primary-light);
    }

    .roadmap-card-header {
        padding: 1.5rem;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .tool-stack {
        display: flex;
        align-items: center;
    }
    .tool-circle {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex; align-items: center; justify-content: center;
        margin-left: -12px;
        position: relative;
    }
    .tool-circle:first-child { margin-left: 0; }

    .progress-pill {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.5px;
        padding: 4px 12px;
        border-radius: 50px;
    }

    .btn-journey {
        background: var(--primary);
        color: #fff;
        font-weight: 700;
        border-radius: 12px;
        padding: 12px 0;
        text-align: center;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
    }
    .btn-journey:hover {
        background: var(--primary-dark);
        color: #fff;
        transform: scale(1.02);
    }

    .create-card {
        border: 2px dashed #e2e8f0;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 300px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .create-card:hover {
        border-color: var(--primary);
        background: #f5f3ff;
    }

    @keyframes pulse-soft {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    .pulse-icon { animation: pulse-soft 3s infinite ease-in-out; }
    .bg-roadmap-primary { background: var(--primary) !important; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    @if(isset($pendingData) && $pendingData)
        <div class="alert alert-info border-0 shadow-lg p-4 rounded-4 mb-4" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-left: 5px solid #2563eb !important;">
            <div class="d-flex align-items-center flex-wrap flex-md-nowrap gap-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center p-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                    <i class="bi bi-stars text-warning fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <h5 class="fw-800 text-dark mb-1">New Tool Usage Detected!</h5>
                    <p class="text-secondary mb-0 small text-muted">We noticed you have been active on <strong>{{ $pendingData['tool']->name }}</strong>. We have matching tutorial content and video lessons. Would you like to append it to your Auto-Generated Roadmap?</p>
                </div>
                <div class="d-flex gap-2 ms-md-auto mt-3 mt-md-0">
                    <form action="{{ route('roadmap.auto.add-tool') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="roadmap_id" value="{{ $pendingData['roadmap_id'] }}">
                        <input type="hidden" name="tool_id" value="{{ $pendingData['tool']->id }}">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-800 btn-sm">Yes, Add to Path</button>
                    </form>
                    <form action="{{ route('roadmap.auto.dismiss-tool') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="roadmap_id" value="{{ $pendingData['roadmap_id'] }}">
                        <input type="hidden" name="tool_id" value="{{ $pendingData['tool']->id }}">
                        <button type="submit" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-bold btn-sm">Dismiss</button>
                    </form>
                </div>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3">
            <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center gap-3">
            <i class="bi bi-patch-check-fill fs-5 text-success"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <span class="badge bg-white bg-opacity-20 rounded-pill px-3 py-1 mb-3 fw-700" style="font-size: 10px;">MY LEARNING ECOSYSTEM</span>
                <h1 class="fw-900 display-5 mb-2">Smart Roadmaps</h1>
                <p class="opacity-75 lead mb-0">Track your progress and master the tools required for your goals.</p>
            </div>
            <div class="col-lg-4 text-center text-lg-end mt-3 mt-lg-0">
                @if(auth()->user()->account_type === 'Free Plan' && $manualRoadmapsCount >= 2)
                    <a href="{{ url('/pricing') }}" class="btn btn-danger rounded-pill px-4 py-3 fw-900 shadow-lg border-0" style="background: linear-gradient(135deg, #ef4444 0%, #f97316 100%); text-decoration: none;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Upgrade for More Roadmaps
                    </a>
                @else
                    <a href="#" data-bs-toggle="modal" data-bs-target="#newRoadmapModal" class="btn btn-primary rounded-pill px-4 py-3 fw-900 shadow-lg border-0" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); text-decoration: none;">
                        <i class="bi bi-rocket-takeoff-fill me-2"></i> Launch New AI Curriculum
                    </a>
                @endif
            </div>
        </div>
    </div>    {{-- ===== CONTINUE LEARNING ===== --}}
    @php $hasAnyContinue = $continueCourses->isNotEmpty() || $roadmaps->isNotEmpty() || $continueWatching->isNotEmpty(); @endphp
    @if($hasAnyContinue)
    <div class="mb-5" id="continue-watching-section">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h5 class="fw-800 text-dark mb-1" style="font-size:1.1rem;letter-spacing:-0.02em;">
                    <i class="bi bi-play-circle-fill me-2" style="color:#4f46e5;"></i>Continue Learning
                </h5>
                <p class="text-muted mb-0" style="font-size:0.8rem;font-weight:500;">Pick up right where you left off &mdash; progress is saved automatically.</p>
            </div>
            <a href="{{ route('learn.explore') }}" class="btn btn-sm rounded-pill fw-700 px-3" style="background:#f1f5f9;color:#475569;font-size:0.78rem;border:none;">Browse All <i class="bi bi-arrow-right ms-1"></i></a>
        </div>

        {{-- Filter tabs --}}
        <div class="d-flex align-items-center gap-2 mb-3 overflow-x-auto" id="cw-filter-tabs" style="scrollbar-width:thin;">
            <button class="cw-tab active" onclick="filterCW('all',this)">All ({{ $continueCourses->count()+$roadmaps->count()+$continueWatching->count() }})</button>
            <button class="cw-tab" onclick="filterCW('roadmap',this)">Roadmaps ({{ $roadmaps->count() }})</button>
            <button class="cw-tab" onclick="filterCW('course',this)">Courses ({{ $continueCourses->count() }})</button>
            <button class="cw-tab" onclick="filterCW('individual',this)">Videos ({{ $continueWatching->count() }})</button>
        </div>

        <div class="row g-3" id="cw-grid">

            {{-- COURSE CARDS --}}
            @foreach($continueCourses as $entry)
            @php
                $course  = $entry['course'];
                $nv      = $entry['next_video'];
                $thumb   = $course->thumbnail ? asset('storage/'.$course->thumbnail)
                         : ($nv ? ($nv->thumbnail_url ?: 'https://img.youtube.com/vi/'.$nv->youtube_id.'/hqdefault.jpg') : null);
                $rUrl    = $nv ? route('learn.watch',[$nv,'course_id'=>$course->id]) : route('learn.explore');
            @endphp
            <div class="col-xl-4 col-md-6 col-12" data-type="course">
                <div class="cc-card">
                    <a href="{{ $rUrl }}" class="cc-thumb-wrap">
                        @if($thumb)<img src="{{ $thumb }}" alt="{{ $course->title }}" class="cc-thumb">
                        @else<div class="cc-no-thumb"><i class="bi bi-collection-play-fill"></i></div>@endif
                        <div class="cc-overlay"><div class="cc-play-btn"><i class="bi bi-play-fill"></i></div></div>
                        <div class="cc-vid-badge"><i class="bi bi-camera-video-fill me-1"></i>{{ $entry['watched'] }}/{{ $entry['total'] }}</div>
                        <div class="cc-bar"><div class="cc-bar-fill" style="width:{{ $entry['progress_pct'] }}%"></div></div>
                    </a>
                    <div class="cc-body">
                        <div class="d-flex align-items-center gap-2">
                            <span class="cw-pill cw-pill-course"><i class="bi bi-collection me-1"></i>Course</span>
                            @if($entry['last_watched_at'])<span class="cw-time">{{ $entry['last_watched_at']->diffForHumans() }}</span>@endif
                        </div>
                        <div class="cc-title" title="{{ $course->title }}">{{ $course->title }}</div>
                        @if($nv)
                        <div class="cc-next"><i class="bi bi-arrow-right-circle-fill" style="color:#0ea5e9;flex-shrink:0;"></i><span class="text-truncate" style="min-width:0;">Next: {{ $nv->title }}</span></div>
                        @endif
                        <div class="cw-prog-row">
                            <div class="cw-prog-bar"><div class="cw-prog-fill cc-fill" style="width:{{ $entry['progress_pct'] }}%"></div></div>
                            <span class="cw-prog-pct cc-pct">{{ $entry['progress_pct'] }}%</span>
                        </div>
                        <a href="{{ $rUrl }}" class="cw-btn cc-btn">
                            <i class="bi bi-play-fill me-1"></i>{{ $entry['watched']===0 ? 'Start Course' : 'Continue Course' }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- ROADMAP CARDS --}}
            @foreach($roadmaps as $item)
            @php
                $toolsCount = count($item->tools);
                $isCompleted = $item->progress >= 100;
            @endphp
            <div class="col-xl-4 col-md-6 col-12" data-type="roadmap">
                <div class="roadmap-card">
                    <div class="roadmap-card-header">
                        <div class="tool-stack">
                            @foreach(array_slice($item->tools, 0, 3) as $toolId)
                                @php $tool = \App\Models\Tool::find($toolId); @endphp
                                <div class="tool-circle" title="{{ $tool?->name }}">
                                    @if($tool?->logo)
                                        <img src="{{ asset($tool->logo) }}" width="18">
                                    @else
                                        <i class="bi bi-box-seam-fill text-muted" style="font-size: 10px;"></i>
                                    @endif
                                </div>
                            @endforeach
                            @if($toolsCount > 3)
                                <div class="tool-circle small fw-900 text-muted" style="font-size: 10px;">+{{ $toolsCount - 3 }}</div>
                            @endif
                        </div>
                        <div class="ms-auto d-flex align-items-center gap-2">
                            <span class="text-muted small fw-700">{{ $item->created_at->format('M d, Y') }}</span>
                            <button type="button" class="btn p-1 border-0 delete-roadmap-btn" 
                                    data-id="{{ $item->id }}" 
                                    data-title="{{ $item->title }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteRoadmapModal" 
                                    title="Delete Roadmap"
                                    style="font-size:1rem;line-height:1;background:#fee2e2;color:#dc2626;border-radius:6px;">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-4 d-flex flex-column h-100">
                        <h4 class="fw-800 text-dark mb-3 line-clamp-2" style="font-size: 1.25rem;">
                            {{ $item->title }}
                            @if($item->is_auto_generated)
                                <span class="badge bg-info text-white fw-bold rounded-pill ms-2" style="font-size: 10px; background-color: #0ea5e9 !important; padding: 4px 8px; vertical-align: middle;"><i class="bi bi-cpu-fill"></i> Auto-Generated</span>
                            @endif
                        </h4>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-700 text-muted uppercase">CURRICULUM PROGRESS</span>
                                <span class="badge {{ $isCompleted ? 'bg-success' : 'bg-primary' }} bg-opacity-10 {{ $isCompleted ? 'text-success' : 'text-primary' }} progress-pill">
                                    {{ $item->progress }}%
                                </span>
                            </div>
                            <div class="progress" style="height: 6px; border-radius: 10px; background: #f1f5f9;">
                                <div class="progress-bar rounded-pill bg-roadmap-primary" style="width: {{ $item->progress }}%;" role="progressbar"></div>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded-3 text-center">
                                        <div class="text-muted small fw-700" style="font-size: 9px; text-transform: uppercase;">Focus</div>
                                        <div class="small fw-800 text-dark">{{ Str::limit($item->focus, 12) }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-light rounded-3 text-center">
                                        <div class="text-muted small fw-700" style="font-size: 9px; text-transform: uppercase;">Level</div>
                                        <div class="small fw-800 text-dark">{{ ucfirst($item->level) }}</div>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('roadmap.show', $item->id) }}" class="btn btn-journey w-100">
                                <i class="bi bi-play-fill me-1"></i> Continue Journey
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- INDIVIDUAL VIDEO CARDS --}}
            @foreach($continueWatching as $item)
            @php
                $wUrl  = route('learn.watch',[$item]);
                $thumb = $item->thumbnail_url ?: 'https://img.youtube.com/vi/'.$item->youtube_id.'/hqdefault.jpg';
            @endphp
            <div class="col-xl-4 col-md-6 col-12" data-type="individual">
                <div class="cw-card">
                    <a href="{{ $wUrl }}" class="cw-thumb-wrap">
                        <img src="{{ $thumb }}" alt="{{ $item->title }}" class="cw-thumb">
                        <div class="cc-overlay"><div class="cw-play-btn"><i class="bi bi-play-fill"></i></div></div>
                        <div class="cw-pct-badge">{{ $item->completion_pct }}%</div>
                        <div class="cc-bar"><div class="cw-bar-fill" style="width:{{ $item->completion_pct }}%"></div></div>
                    </a>
                    <div class="cw-body">
                        <div class="cw-title" title="{{ $item->title }}">{{ $item->title }}</div>
                        <div class="cw-meta"><i class="bi bi-clock me-1"></i>{{ $item->duration_label_local }} &middot; {{ $item->last_watched_at?->diffForHumans() ?? 'Recently' }}</div>
                        <div class="cw-prog-row">
                            <div class="cw-prog-bar"><div class="cw-prog-fill" style="width:{{ $item->completion_pct }}%"></div></div>
                            <span class="cw-prog-pct">{{ $item->completion_pct }}%</span>
                        </div>
                        <div class="d-flex gap-2 mt-1">
                            <a href="{{ $wUrl }}" class="cw-btn flex-grow-1 text-center" data-start="{{ $item->resume_seconds }}"><i class="bi bi-play-fill me-1"></i>Resume</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
        <div id="cw-empty-msg" style="display:none;" class="text-center py-4">
            <i class="bi bi-play-circle" style="font-size:36px;color:#cbd5e1;display:block;margin-bottom:8px;"></i>
            <p style="font-weight:600;color:#64748b;font-size:14px;margin:0;">No in-progress items in this category</p>
        </div>
    </div>

    <style>
        .cw-tab { background:#f1f5f9;color:#475569;border:none;border-radius:50px;padding:5px 14px;font-size:.78rem;font-weight:700;cursor:pointer;white-space:nowrap;transition:all .2s; }
        .cw-tab:hover { background:#e2e8f0; }
        .cw-tab.active { background:#4f46e5;color:#fff; }

        /* Shared card base */
        .cc-card,.cw-card { background:#fff;border-radius:18px;overflow:hidden;display:flex;flex-direction:column;transition:all .25s cubic-bezier(.4,0,.2,1);height:100%; }
        .cc-card { border:1px solid #e0f2fe; }
        .cw-card { border:1px solid #e8edf5; }
        .cc-card:hover { transform:translateY(-5px);box-shadow:0 16px 40px rgba(14,165,233,.12);border-color:#7dd3fc; }
        .cw-card:hover { transform:translateY(-5px);box-shadow:0 16px 40px rgba(15,23,42,.1);border-color:#c7d2fe; }

        /* Thumbnails */
        .cc-thumb-wrap,.cw-thumb-wrap { position:relative;display:block;aspect-ratio:16/9;overflow:hidden;text-decoration:none;flex-shrink:0; }
        .cc-thumb-wrap { background:#0c4a6e; }
        .cw-thumb-wrap { background:#1e1b4b; }
        .cc-thumb,.cw-thumb { width:100%;height:100%;object-fit:cover;transition:transform .3s;display:block; }
        .cc-card:hover .cc-thumb,.cw-card:hover .cw-thumb { transform:scale(1.04); }
        .cc-no-thumb { width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;color:rgba(255,255,255,.3); }
        .cc-overlay { position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.35);opacity:0;transition:opacity .2s; }
        .cc-card:hover .cc-overlay,.cw-card:hover .cc-overlay { opacity:1; }
        .cc-play-btn,.cw-play-btn { width:48px;height:48px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.2rem;transform:scale(.85);transition:transform .2s;box-shadow:0 4px 16px rgba(0,0,0,.25); }
        .cc-play-btn { color:#0ea5e9; }
        .cw-play-btn { color:#4f46e5; }
        .cc-card:hover .cc-play-btn,.cw-card:hover .cw-play-btn { transform:scale(1); }
        .cc-vid-badge { position:absolute;top:8px;left:8px;background:rgba(0,0,0,.65);color:#fff;font-size:.68rem;font-weight:800;border-radius:8px;padding:2px 10px;backdrop-filter:blur(4px); }
        .cw-pct-badge { position:absolute;top:8px;right:8px;background:rgba(0,0,0,.65);color:#fff;font-size:.68rem;font-weight:800;border-radius:8px;padding:2px 8px;backdrop-filter:blur(4px); }
        .cc-bar { position:absolute;bottom:0;left:0;right:0;height:3px;background:rgba(255,255,255,.2); }
        .cc-bar-fill,.cw-bar-fill { height:100%;border-radius:0 2px 2px 0; }
        .cc-bar-fill { background:#0ea5e9; }
        .cw-bar-fill { background:#4f46e5; }
        .rm-fill { background:#7c3aed; }

        /* Bodies */
        .cc-body,.cw-body { padding:14px 16px 16px;display:flex;flex-direction:column;gap:7px;flex:1; }
        .cc-title,.cw-title { font-weight:700;font-size:.9rem;color:#0f172a;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
        .cc-next { display:flex;align-items:center;gap:6px;font-size:.75rem;color:#475569;font-weight:500;overflow:hidden; }
        .cw-meta { font-size:.72rem;color:#94a3b8;font-weight:500; }
        .cw-time { font-size:.68rem;color:#94a3b8;font-weight:500; }

        /* Pills */
        .cw-pill { border-radius:8px;padding:2px 8px;font-size:.65rem;font-weight:700;flex-shrink:0; }
        .cw-pill-course { background:#e0f2fe;color:#0369a1; }
        .cw-pill-roadmap { background:#ede9fe;color:#5b21b6; }

        /* Progress bars */
        .cw-prog-row { display:flex;align-items:center;gap:8px; }
        .cw-prog-bar { flex:1;height:5px;background:#f1f5f9;border-radius:5px;overflow:hidden; }
        .cw-prog-fill { height:100%;border-radius:5px;transition:width .6s;background:linear-gradient(90deg,#4f46e5,#818cf8); }
        .cc-fill { background:linear-gradient(90deg,#0ea5e9,#38bdf8); }
        .rm-prog { background:linear-gradient(90deg,#7c3aed,#a78bfa); }
        .cw-prog-pct { font-size:.7rem;font-weight:800;color:#4f46e5;min-width:32px;text-align:right; }
        .cc-pct { color:#0ea5e9; }
        .rm-pct { color:#7c3aed; }

        /* Buttons */
        .cw-btn { display:flex;align-items:center;justify-content:center;border-radius:10px;padding:9px 0;font-weight:700;font-size:.85rem;text-decoration:none;transition:all .2s;background:#4f46e5;color:#fff;margin-top:2px; }
        .cw-btn:hover { background:#4338ca;color:#fff;transform:scale(1.02);box-shadow:0 6px 16px rgba(79,70,229,.25); }
        .cc-btn { background:#0ea5e9; }
        .cc-btn:hover { background:#0284c7;box-shadow:0 6px 16px rgba(14,165,233,.3); }
        .rm-btn { background:#7c3aed; }
        .rm-btn:hover { background:#6d28d9;box-shadow:0 6px 16px rgba(124,58,237,.25); }

        @media (max-width:768px) {
            .cc-card,.cw-card { border-radius:14px; }
            .cc-body,.cw-body { padding:10px 12px 12px;gap:5px; }
            .cc-title,.cw-title { font-size:.84rem; }
            .cw-btn { font-size:.8rem;padding:8px 0; }
        }
    </style>
    @endif
</div>

<!-- Create Roadmap Modal -->
<div class="modal fade" id="newRoadmapModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg position-relative overflow-hidden" style="border-radius: 35px; background: #ffffff;">
            {{-- Decorative Background Elements --}}
            <div class="position-absolute top-0 start-0 w-100 h-100 opacity-05 pointer-events-none" style="background-image: radial-gradient(#4f46e5 1px, transparent 1px); background-size: 20px 20px;"></div>
            
            <div class="modal-body p-5 position-relative">
                <div class="text-center mb-5">
                    <div class="blueprint-icon-wrapper pulse-blue mx-auto mb-4">
                        <div class="pulse-aura"></div>
                        <i class="bi bi-rocket-takeoff-fill fs-1 text-primary"></i>
                    </div>
                    <h2 class="fw-900 text-dark mb-2" style="letter-spacing: -0.03em;">Dream your goal</h2>
                    <p class="text-muted px-4">Type your career objective or the skill you want to master. AI will blueprint the perfect path for you.</p>
                </div>

                <form action="{{ route('roadmap.wizard') }}" method="GET">
                    <div class="mb-4">
                        <div class="goal-input-wrapper position-relative">
                            <i class="bi bi-bullseye position-absolute top-50 start-0 translate-middle-y ms-4 text-primary opacity-50"></i>
                            <input type="text" 
                                   name="query" 
                                   class="form-control form-control-xl bg-light border-0 ps-5 py-4 fw-700" 
                                   style="border-radius: 20px; font-size: 1.1rem; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);" 
                                   placeholder="e.g. Become a Senior AI Architect"
                                   required
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="d-grid mt-2">
                        <button type="submit" class="btn btn-primary rounded-pill py-3 fw-900 shadow-lg btn-launch-journey transition-all">
                            LAUNCH MY JOURNEY <i class="bi bi-arrow-right-short ms-2 fs-4"></i>
                        </button>
                    </div>
                </form>
            </div>
            <button type="button" class="btn-close position-absolute top-0 end-0 m-4 shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    </div>
</div>

<style>
    .architect-create-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        border: none;
        border-radius: 30px;
        position: relative;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .architect-create-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 30px 60px -12px rgba(15, 23, 42, 0.5), 0 0 40px rgba(79, 70, 229, 0.1);
    }
    .architect-icon-circle {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        box-shadow: 0 10px 25px rgba(6, 182, 212, 0.3);
        transition: all 0.4s ease;
    }
    .architect-create-card:hover .architect-icon-circle {
        transform: rotate(90deg) scale(1.1);
        box-shadow: 0 15px 35px rgba(6, 182, 212, 0.5);
    }
    .architect-glow {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at center, rgba(6, 182, 212, 0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .architect-create-card:hover .architect-glow {
        opacity: 1;
    }
    
    .btn-launch-journey {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        letter-spacing: 1px;
    }
    .btn-launch-journey:hover {
        transform: scale(1.02);
        box-shadow: 0 15px 30px rgba(79, 70, 229, 0.3) !important;
    }
    
    .opacity-05 { opacity: 0.05; }
</style>

<!-- Delete Roadmap Modal -->
<div class="modal fade" id="deleteRoadmapModal" tabindex="-1" aria-labelledby="deleteRoadmapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-body p-4 text-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle mb-3" style="width: 54px; height: 54px;">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                </div>
                <h5 class="fw-800 text-dark mb-2">Delete Roadmap?</h5>
                <p class="text-secondary small mb-4" id="deleteRoadmapName" style="line-height: 1.5;">Are you sure you want to permanently delete this roadmap curriculum?</p>
                <form id="deleteRoadmapForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light w-100 fw-bold py-2 rounded-3" data-bs-dismiss="modal" style="font-size: 0.9rem;">Cancel</button>
                        <button type="submit" class="btn btn-danger w-100 fw-bold py-2 rounded-3" style="font-size: 0.9rem;">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteForm = document.getElementById('deleteRoadmapForm');
        const deleteName = document.getElementById('deleteRoadmapName');

        document.querySelectorAll('.delete-roadmap-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id    = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                deleteForm.action = `{{ url('/roadmap') }}/${id}`;
                deleteName.innerHTML = `Are you sure you want to permanently delete <strong class="text-dark">"${title}"</strong>? This action cannot be undone.`;
            });
        });
    });

    // ── Tab filter ──────────────────────────────────────────────────────────
    window.filterCW = function(type, btn) {
        document.querySelectorAll('#cw-filter-tabs .cw-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const grid  = document.getElementById('cw-grid');
        const empty = document.getElementById('cw-empty-msg');
        if (!grid) return;

        let visible = 0;
        grid.querySelectorAll('[data-type]').forEach(card => {
            const match = type === 'all' || card.getAttribute('data-type') === type;
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        if (empty) empty.style.display = visible === 0 ? 'block' : 'none';
    };

    // ── Reset progress ──────────────────────────────────────────────────────
    window.resetIndexProgress = function(event, contentId, button) {
        event.preventDefault();
        event.stopPropagation();
        if (!confirm('Reset progress for this video?')) return;

        fetch("{{ route('learn.progress.reset') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}", 'Accept': 'application/json' },
            body: JSON.stringify({ content_id: contentId })
        })
        .then(r => r.json())
        .then(() => {
            const card = button.closest('[data-type]');
            if (card) {
                card.style.transition = 'all .4s ease';
                card.style.opacity    = '0';
                card.style.transform  = 'scale(0.9)';
                setTimeout(() => {
                    card.remove();
                    const grid = document.getElementById('cw-grid');
                    if (grid && grid.querySelectorAll('[data-type]:not([style*="display: none"])').length === 0) {
                        const section = document.getElementById('continue-watching-section');
                        if (section) section.remove();
                    }
                }, 400);
            }
        })
        .catch(() => alert('Could not reset progress. Please try again.'));
    };
</script>
@endsection
