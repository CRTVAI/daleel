@extends('layouts.user')

@section('title', 'Dashboard — Daleel AI')

@section('content')
@if((session('show_tour') || !auth()->user()->has_seen_tour) && !auth()->user()->hasIncompleteProfile())
    <div id="tourOverlay" class="tour-overlay" style="display:none;">
        <div class="tour-panel" id="tourTop"></div>
        <div class="tour-panel" id="tourBottom"></div>
        <div class="tour-panel" id="tourLeft"></div>
        <div class="tour-panel" id="tourRight"></div>
        <div class="tour-tooltip" id="tourTooltip">
            <div class="tour-tooltip-arrow"></div>
            <div class="tour-tooltip-body" id="tourBody"></div>
        </div>
        <form id="tourDismissForm" action="{{ route('tour.dismiss') }}" method="POST" style="display:none;">@csrf</form>
    </div>
@endif
<div class="dashboard-focus-modern pb-5">
    @if(isset($pendingData) && $pendingData)
    
    <style>
        /* Roadmap-style "tool row" widget (reuses roadmap wizard visual) */
        .after-signin-tools-widget-title{font-weight:800;color:#1f2937;margin:0 0 14px 0;font-size:18px;letter-spacing:-0.02em;}
        .after-signin-tool-row{border:2px solid #e2e8f0;border-radius:18px;transition:all .2s ease;min-height:96px;height:96px;display:flex;align-items:center;justify-content:space-between;padding:14px 14px;gap:12px;cursor:pointer;background:#fff;overflow:hidden;}
        .after-signin-tool-row:hover{transform:translateY(-2px);border-color:#6366f1;box-shadow:0 6px 18px rgba(99,102,241,0.06);}
        .after-signin-tool-row.selected{border-color:#6366f1;background:#f5f3ff;}
        .after-signin-tool-row .logo{width:40px;height:40px;border:1px solid #e2e8f0;border-radius:14px;display:flex;align-items:center;justify-content:center;background:#f8fafc;flex-shrink:0;}
        .after-signin-tool-row .logo img{width:22px;height:22px;object-fit:contain;}
        .after-signin-tool-row .name{font-size:.95rem;font-weight:800;color:#0f172a;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:240px;}
        .after-signin-tool-row .hint{font-size:.72rem;font-weight:700;color:#64748b;}
        .after-signin-check{width:24px;height:24px;border:2px solid #cbd5e1;border-radius:50%;display:grid;place-items:center;color:transparent;font-size:.8rem;font-weight:800;transition:all .2s;flex-shrink:0;}
        .after-signin-tool-row.selected .after-signin-check{border-color:#6366f1;background:#6366f1;color:#fff;}
    </style>

    <script>
        function toggleAfterSigninTool(toolName){
            const el = document.getElementById('afterSigninTool-'+toolName);
            if(!el) return;
            const hidden = document.getElementById('afterSigninToolInput-'+toolName);
            const isSelected = el.classList.contains('selected');
            if(isSelected){
                el.classList.remove('selected');
                if(hidden) hidden.value = '0';
            }else{
                el.classList.add('selected');
                if(hidden) hidden.value = '1';
            }
        }
    </script>
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
    <!-- Top Row: Welcome Hero & Stats -->
    <!-- Premium Hero Section (Centered Design) -->
    <!-- Premium Hero Section (Two-Column Layout) -->
    <div class="hero-focus-card animate-slide-up rounded-5 overflow-hidden mb-5 border-0" 
         style="background: #ffffff; box-shadow: 0 40px 80px -20px rgba(0,0,0,0.03);">
        <div class="row g-0 align-items-stretch">
            <!-- Left Side: Search & Action Buttons -->
            <div class="col-lg-6 p-4 p-md-5 text-start d-flex flex-column justify-content-center hero-left-col">
                <div class="ps-lg-3">
                    {{-- Label badge --}}
                    <div class="mb-3">
                        <span class="hero-label-badge">AI-POWERED LEARNING</span>
                    </div>

                    {{-- Heading --}}
                    <h1 class="hero-heading mb-1">
                        What do you want to
                    </h1>
                    <h1 class="hero-heading-accent mb-4">
                        <span id="hero-typing-text">learn?</span><span class="hero-cursor">|</span>
                    </h1>

                    {{-- Search Bar --}}
                    <form action="{{ route('search.advanced') }}" method="GET" class="search-wrapper mb-4">
                        <div class="search-inner">
                            <span class="search-icon"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" id="dashboard-search-alt"
                                   class="search-input"
                                   placeholder="Search tools, skills or topics..." />
                            <button type="button" id="voiceSearchBtn" class="search-voice-btn" title="Voice search" onclick="startVoiceSearch()">
                                <i class="bi bi-mic"></i>
                            </button>
                            <button type="submit" class="search-submit-btn">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </form>

                    {{-- Action Buttons --}}
                    <div class="hero-actions d-flex flex-wrap gap-2 gap-md-3">
                        <a href="{{ route('learn.explore') }}" class="btn-hero-pill btn-hero-browse">
                            <i class="bi bi-grid-3x3-gap-fill"></i> Browse Tools
                        </a>
                        <div role="button" data-bs-toggle="modal" data-bs-target="#newRoadmapModal" class="btn-hero-pill btn-hero-create">
                            <i class="bi bi-plus-circle-fill"></i> New Path
                        </div>
                        <a href="{{ route('ai.mentor') }}" class="btn-hero-pill btn-hero-mentor">
                            <i class="bi bi-stars"></i> AI Mentor
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Side: Journey Card (Lovable Redesign) -->
            <div class="col-lg-6 p-4 p-md-5">
                <div class="journey-card h-100 position-relative overflow-hidden" style="border-radius: 28px; background: #fff; box-shadow: 0 20px 60px -15px rgba(0,0,0,0.04); border: 1px solid #f1f5f9;">
                    
                    @if($currentRoadmap)
                        @php
                            $firstToolId = $currentRoadmap->tools[0] ?? null;
                            $firstTool = $firstToolId ? \App\Models\Tool::find($firstToolId) : null;
                            $phaseCount = is_array($currentRoadmap->curriculum) ? (isset($currentRoadmap->curriculum['phases']) ? count($currentRoadmap->curriculum['phases']) : count($currentRoadmap->curriculum)) : 0;
                            $progress = $currentRoadmap->progress ?? 0;
                        @endphp

                        {{-- Decorative glow --}}
                        <div class="position-absolute" style="top: -80px; right: -80px; width: 240px; height: 240px; background: radial-gradient(circle, rgba(99,102,241,0.08) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
                        <div class="position-absolute" style="bottom: -60px; left: -60px; width: 180px; height: 180px; background: radial-gradient(circle, rgba(16,185,129,0.06) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>

                        <div class="p-3 p-md-4 position-relative">
                            {{-- Header --}}
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex align-items-center justify-content-center rounded-2" style="width: 30px; height: 30px; background: linear-gradient(135deg, #eef2ff, #e0e7ff); color: #4338ca;">
                                        <i class="bi bi-map-fill" style="font-size: 13px;"></i>
                                    </div>
                                    <span class="fw-800" style="font-size: 15px; color: #0f172a;">Current Journey</span>
                                </div>
                                <a href="{{ route('roadmap') }}" class="btn btn-sm fw-800 text-decoration-none px-3" style="background: #f8fafc; color: #4338ca; border: 1px solid #eef2ff; font-size: 10px; border-radius: 20px; white-space: nowrap; line-height: 1;">
                                    All Paths <i class="bi bi-arrow-right ms-1" style="font-size: 9px;"></i>
                                </a>
                            </div>

                            {{-- Tool spotlight --}}
                            <div class="d-flex align-items-center gap-3 mb-3 p-2 rounded-3" style="background: linear-gradient(135deg, #faf5ff 0%, #f0f4ff 100%); border: 1px solid rgba(99,102,241,0.08);">
                                <div class="rounded-3 d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0" style="width: 48px; height: 48px; background: #fff; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                                    <img src="{{ $firstTool ? asset($firstTool->logo) : asset('images/logo-placeholder.png') }}" class="w-100 h-100 object-fit-contain p-1">
                                </div>
                                <div style="min-width: 0;">
                                    <h4 class="fw-900 mb-0" style="font-size: 15px; color: #0f172a; line-height: 1.2;">{{ $currentRoadmap->title }}</h4>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mt-1">
                                        <span class="badge rounded-pill fw-700" style="font-size: 9px; background: rgba(99,102,241,0.1); color: #4338ca;">{{ $firstTool ? $firstTool->name : 'Custom' }}</span>
                                        <span style="font-size: 10px; font-weight: 600; color: #94a3b8;">{{ $phaseCount }} phases</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Ring + stats row --}}
                            <div class="d-flex align-items-stretch gap-3 mb-3">
                                <div class="position-relative flex-shrink-0 d-flex align-items-center">
                                    <svg width="52" height="52" viewBox="0 0 52 52">
                                        <circle cx="26" cy="26" r="22" fill="none" stroke="#f1f5f9" stroke-width="4"/>
                                        <circle cx="26" cy="26" r="22" fill="none" stroke="url(#progressGrad)" stroke-width="4" stroke-linecap="round" stroke-dasharray="{{ 2 * 3.14159 * 22 }}" stroke-dashoffset="{{ 2 * 3.14159 * 22 * (1 - $progress / 100) }}" transform="rotate(-90, 26, 26)" style="transition: stroke-dashoffset 1s ease;"/>
                                        <defs>
                                            <linearGradient id="progressGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                                <stop offset="0%" stop-color="#4338ca"/>
                                                <stop offset="100%" stop-color="#6366f1"/>
                                            </linearGradient>
                                        </defs>
                                    </svg>
                                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                                        <span class="fw-900" style="font-size: 12px; color: #0f172a;">{{ $progress }}%</span>
                                    </div>
                                </div>
                                <div class="d-flex" style="flex: 1; gap: 1px; background: #f1f5f9; border-radius: 10px; overflow: hidden;">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-2" style="flex: 1; background: #fff;">
                                        <span class="fw-900" style="font-size: 16px; color: #0f172a;">{{ $phaseCount }}</span>
                                        <span style="font-size: 9px; font-weight: 700; color: #94a3b8;">phases</span>
                                    </div>
                                    <div class="d-flex flex-column align-items-center justify-content-center py-2" style="flex: 1; background: #fff;">
                                        <span class="fw-900" style="font-size: 16px; color: #0f172a;">{{ count($currentRoadmap->tools) }}</span>
                                        <span style="font-size: 9px; font-weight: 700; color: #94a3b8;">tools</span>
                                    </div>
                                    <div class="d-flex flex-column align-items-center justify-content-center py-2" style="flex: 1; background: #fff;">
                                        <span class="fw-900" style="font-size: 16px; color: {{ $progress >= 100 ? '#10b981' : ($progress >= 50 ? '#f59e0b' : '#94a3b8') }};">
                                            @if($progress >= 100) <i class="bi bi-check-circle-fill"></i>
                                            @elseif($progress >= 50) <i class="bi bi-arrow-up-circle-fill"></i>
                                            @else <i class="bi bi-play-fill"></i>
                                            @endif
                                        </span>
                                        <span style="font-size: 9px; font-weight: 700; color: #94a3b8;">
                                            @if($progress >= 100) done
                                            @elseif($progress >= 50) active
                                            @else new @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- CTA --}}
                            <a href="{{ route('roadmap.show', $currentRoadmap->id) }}" 
                               class="btn text-decoration-none w-100 py-2 fw-800 d-flex align-items-center justify-content-center gap-2 rounded-pill transition-all shadow-sm border-0"
                               style="background: linear-gradient(135deg, #4338ca, #6366f1); color: #fff; box-shadow: 0 6px 16px -3px rgba(67,56,202,0.25); font-size: 13px;">
                                <i class="bi bi-play-fill"></i> Continue Learning
                            </a>
                        </div>
                    @else
                        {{-- Decorative glow --}}
                        <div class="position-absolute" style="top: -60px; right: -60px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(99,102,241,0.06) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
                        <div class="position-absolute" style="bottom: -40px; left: -40px; width: 140px; height: 140px; background: radial-gradient(circle, rgba(16,185,129,0.05) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>

                        <div class="p-4 p-md-5 text-center position-relative d-flex flex-column align-items-center justify-content-center" style="min-height: 420px;">
                            {{-- Floating stars --}}
                            <div class="position-relative mb-4">
                                <div class="d-flex align-items-center justify-content-center mx-auto" style="width: 96px; height: 96px; border-radius: 32px; background: linear-gradient(135deg, #eef2ff, #f5f3ff); box-shadow: 0 12px 32px -8px rgba(99,102,241,0.12);">
                                    <i class="bi bi-stars" style="font-size: 36px; color: #4338ca; animation: gentleFloat 3s ease-in-out infinite;"></i>
                                </div>
                                <span class="position-absolute" style="top: -8px; right: 10px; font-size: 20px; animation: gentleFloat 3s ease-in-out infinite 0.5s;">✨</span>
                                <span class="position-absolute" style="bottom: 4px; left: 6px; font-size: 14px; animation: gentleFloat 3s ease-in-out infinite 1s;">⭐</span>
                            </div>

                            <h4 class="fw-900 mb-2" style="font-size: 22px; color: #0f172a; letter-spacing: -0.02em;">Ready to begin?</h4>
                            <p class="mb-4 px-3" style="font-size: 14px; font-weight: 600; color: #64748b; line-height: 1.6; max-width: 320px;">Let AI craft a personalized learning path tailored to your goals and favorite tools.</p>

                            <a href="{{ route('learn.explore') }}" class="btn rounded-pill px-5 py-3 fw-800 d-inline-flex align-items-center gap-2 transition-all shadow-sm border-0" style="background: linear-gradient(135deg, #4338ca, #6366f1); color: #fff; box-shadow: 0 8px 24px -4px rgba(67,56,202,0.25); font-size: 15px;">
                                <i class="bi bi-magic fs-5"></i> Generate My Path
                            </a>

                            <div class="mt-4 d-flex align-items-center gap-3">
                                <span class="d-inline-block rounded-pill px-3 py-1 fw-700" style="font-size: 10px; background: #f1f5f9; color: #94a3b8;">No commitment</span>
                                <span class="d-inline-block rounded-pill px-3 py-1 fw-700" style="font-size: 10px; background: #f1f5f9; color: #94a3b8;">Free to start</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Premium Stats Bar -->
    <div class="stats-premium-bar animate-slide-up mb-5">
        <!-- Stat Segment: Completed -->
        <div class="stat-item-premium">
            <div class="icon-box bg-blue-subtle text-primary">
                <i class="bi bi-collection-play-fill"></i>
            </div>
            <div>
                <div class="stat-label-premium">VIDEOS COMPLETED</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="stat-value-premium">{{ $completedCount }}</span>
                    <span class="text-muted fw-800" style="font-size: 11px;">Level: Explorer</span>
                </div>
                <div class="text-primary fw-900" style="font-size: 9px; letter-spacing: 0.05em; margin-top: 2px;">{{ $inProgressCount }} IN PROGRESS</div>
            </div>
        </div>

        <!-- Stat Segment: Streak -->
        <div class="stat-item-premium">
            <div class="icon-box bg-orange-subtle text-orange">
                <i class="bi bi-fire"></i>
            </div>
            <div>
                <div class="stat-label-premium">ACTIVE STREAK</div>
                <div class="stat-value-premium">{{ auth()->user()->streak_count }} <span class="small fw-700 opacity-50" style="font-size: 14px;">days</span></div>
                <div class="progress rounded-pill mt-2" style="height: 4px; background: #f1f5f9; width: 100px;">
                    <div class="progress-bar bg-orange" style="width: {{ min((auth()->user()->streak_count / 7) * 100, 100) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Stat Segment: Watch Time -->
        <div class="stat-item-premium">
            <div class="icon-box bg-teal-subtle text-teal">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <div class="stat-label-premium">TOTAL WATCH TIME</div>
                <div class="stat-value-premium">{{ round($totalWatchSeconds / 3600, 1) }} <span class="small fw-700 opacity-50" style="font-size: 14px;">hrs</span></div>
                <div class="d-flex align-items-center gap-1 mt-1">
                    <div class="pulsing-dot-success" style="width: 6px; height: 6px;"></div>
                    <span class="text-muted fw-800" style="font-size: 9px;">Analytics Sync Active</span>
                </div>
            </div>
        </div>
    </div>



    @if($continueWatching->count() > 0)
    <!-- Continue Watching Row -->
    <div class="mb-5 animate-slide-up delay-4 px-4 py-5 rounded-5 continue-watching-section" style="background: #eef2ff; border: 1px solid #f1f5f9;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-800 mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-play-circle-fill text-primary"></i> Continue Watching
            </h5>
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="text-muted fw-800 small text-decoration-none d-none d-md-block">History</a>
                <div class="d-flex gap-2">
                    <button class="swiper-prev-watching btn btn-light rounded-circle shadow-sm p-0" style="width: 32px; height: 32px; border: 1px solid #eee;">
                        <i class="bi bi-chevron-left" style="font-size: 12px;"></i>
                    </button>
                    <button class="swiper-next-watching btn btn-light rounded-circle shadow-sm p-0" style="width: 32px; height: 32px; border: 1px solid #eee;">
                        <i class="bi bi-chevron-right" style="font-size: 12px;"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="swiper continueWatchingSwiper">
            <div class="swiper-wrapper">
                @foreach($continueWatching as $progress)
                <div class="swiper-slide">
                    <div class="card-focus p-3 d-flex gap-3 align-items-center h-100">
                        <div class="rounded-3 overflow-hidden shadow-sm" style="width: 80px; height: 60px; flex-shrink: 0;">
                            <img src="{{ $progress->content->thumbnail_url }}" class="w-100 h-100 object-fit-cover">
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="fw-800 text-dark mb-1 line-clamp-1" style="font-size: 13px;" title="{{ $progress->content->title }}">{{ Str::limit($progress->content->title, 30) }}</h6>
                            <div class="progress rounded-pill mb-1" style="height: 4px; background: #f1f5f9;">
                                <div class="progress-bar bg-primary" style="width: {{ $progress->completion_percent }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted fw-800" style="font-size: 9px;">{{ $progress->completion_percent }}% COMPLETE</span>
                                <a href="{{ route('learn.watch', $progress->content) }}" class="text-primary fw-800 small text-decoration-none" style="font-size: 10px;">RESUME <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif



    <!-- Recommended Videos Section (Based on Behavior) -->
    @if($behaviorRecommended->count() > 0)
    <div class="mb-5 animate-slide-up delay-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-800 mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-stars text-violet" style="color: #8b5cf6;"></i> Recommended Videos
            </h5>
            <div class="d-flex align-items-center gap-3">
                <span class="badge rounded-pill px-3 py-2 fw-bold small d-none d-md-inline-block" style="font-size: 11px; background: rgba(139, 92, 246, 0.08); color: #8b5cf6; border: 1px solid rgba(139, 92, 246, 0.15);">Based on your activity</span>
                <div class="d-flex gap-2">
                    <button class="swiper-prev-recom-videos btn btn-light rounded-circle shadow-sm p-0" style="width: 32px; height: 32px; border: 1px solid #eee;">
                        <i class="bi bi-chevron-left" style="font-size: 12px;"></i>
                    </button>
                    <button class="swiper-next-recom-videos btn btn-light rounded-circle shadow-sm p-0" style="width: 32px; height: 32px; border: 1px solid #eee;">
                        <i class="bi bi-chevron-right" style="font-size: 12px;"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="swiper recommendedVideosSwiper">
            <div class="swiper-wrapper">
                @foreach($behaviorRecommended as $content)
                <div class="swiper-slide">
                    <a href="{{ route('learn.watch', $content) }}" class="text-decoration-none h-100 d-block">
                        <div class="card-focus overflow-hidden h-100 transition-all hover-lift">
                            <div class="position-relative" style="height: 150px;">
                                <img src="{{ $content->thumbnail_url }}" class="w-100 h-100 object-fit-cover">
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold small shadow-sm">{{ $content->category }}</span>
                                </div>
                                <div class="position-absolute bottom-0 end-0 m-2">
                                    <span class="badge rounded-pill px-2 py-1 small" style="font-size: 10px; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); color: #ffffff;">
                                        <i class="bi bi-clock me-1"></i> {{ $content->duration_label ?: '12m' }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-3 bg-white">
                                <h6 class="fw-800 text-dark mb-2 line-clamp-2" style="font-size: 13px; height: 38px; line-height: 1.4;">{{ $content->title }}</h6>
                                <div class="d-flex align-items-center justify-content-between text-muted small fw-bold mt-2 pt-2 border-top border-light">
                                    <span style="font-size: 11px; color: #8b5cf6;">
                                        @if($content->connected_tools && is_array($content->connected_tools) && count($content->connected_tools) > 0)
                                            <i class="bi bi-cpu me-1"></i> {{ ucfirst($content->connected_tools[0]) }}
                                        @else
                                            <i class="bi bi-play-circle me-1"></i> Video
                                        @endif
                                    </span>
                                    <span style="font-size: 10px; color: #64748b;">{{ $content->skill_level }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Connected Tools Section -->
    <div class="mb-5 animate-slide-up delay-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-800 mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-grid-fill text-primary"></i> Learn Best Use With AI.
            </h5>
            <div class="d-flex align-items-center gap-3">
                @if($hasTrackedUsage)
                <span class="badge rounded-pill px-3 py-2 fw-bold small d-none d-md-inline-block" style="font-size: 11px; background: rgba(99, 102, 241, 0.08); color: var(--focus-primary); border: 1px solid rgba(99, 102, 241, 0.15);">Your Top Used Tools</span>
                @else
                <span class="badge rounded-pill px-3 py-2 fw-bold small d-none d-md-inline-block" style="font-size: 11px; background: rgba(99, 102, 241, 0.08); color: var(--focus-primary); border: 1px solid rgba(99, 102, 241, 0.15);">Recommended Connected Apps</span>
                @endif
                <div class="d-flex gap-2">
                    <button class="swiper-prev-tracks btn btn-light rounded-circle shadow-sm p-0" style="width: 32px; height: 32px; border: 1px solid #eee;">
                        <i class="bi bi-chevron-left" style="font-size: 12px;"></i>
                    </button>
                    <button class="swiper-next-tracks btn btn-light rounded-circle shadow-sm p-0" style="width: 32px; height: 32px; border: 1px solid #eee;">
                        <i class="bi bi-chevron-right" style="font-size: 12px;"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="swiper tracksSwiper">
            <div class="swiper-wrapper">
                @foreach($connectedTools as $tool)
                <div class="swiper-slide">
                    <a href="{{ route('learn.explore', ['search' => $tool->name]) }}" class="text-decoration-none h-100 d-block">
                        <div class="card-focus overflow-hidden h-100 transition-all hover-lift d-flex flex-column align-items-center text-center p-4">
                            <div class="tool-logo-container mb-3 d-flex align-items-center justify-content-center position-relative" style="width: 80px; height: 80px; background: #f8fafc; border-radius: 24px; border: 1px solid #e2e8f0; transition: all 0.3s ease;">
                                <img src="{{ asset($tool->logo) }}" class="tool-logo" style="width: 44px; height: 44px; object-fit: contain;" alt="{{ $tool->name }} logo">
                                @if($hasTrackedUsage && isset($tool->usage_score) && $tool->usage_score > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success border border-white" style="font-size: 9px; padding: 0.35em 0.6em;" title="Active Track">
                                    <i class="bi bi-activity"></i>
                                </span>
                                @endif
                            </div>
                            <h6 class="fw-800 text-dark mb-1">{{ $tool->name }}</h6>
                            <p class="text-muted small fw-600 mb-0 line-clamp-2" style="font-size: 11px;">{{ $tool->description }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Recommended Courses Grid -->

    <!-- Shorts Grid -->
    @if($shorts->count() > 0)
    <div class="mb-5 animate-slide-up delay-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-800 mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-lightning-fill text-sky"></i> Learning Shorts
            </h5>
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="text-muted fw-800 small text-decoration-none d-none d-md-block">View all</a>
                <div class="d-flex gap-2">
                    <button class="swiper-prev-shorts btn btn-light rounded-circle shadow-sm p-0" style="width: 32px; height: 32px; border: 1px solid #eee;">
                        <i class="bi bi-chevron-left" style="font-size: 12px;"></i>
                    </button>
                    <button class="swiper-next-shorts btn btn-light rounded-circle shadow-sm p-0" style="width: 32px; height: 32px; border: 1px solid #eee;">
                        <i class="bi bi-chevron-right" style="font-size: 12px;"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="swiper shortsSwiper">
            <div class="swiper-wrapper">
                @foreach($shorts as $short)
                <div class="swiper-slide">
                    <div class="short-card-focus rounded-4 overflow-hidden position-relative shadow-sm" style="height: 240px;">
                        <img src="{{ $short->thumbnail_url }}" class="w-100 h-100 object-fit-cover">
                        <div class="short-overlay-focus">
                            <h6 class="text-white fw-800 mb-2 line-clamp-2" style="font-size: 11px;">{{ $short->title }}</h6>
                            <a href="{{ route('learn.watch', $short) }}" class="btn-short-play"><i class="bi bi-play-fill"></i></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Courses Row -->
    @if($courses->count() > 0)
    <div class="mb-5 animate-slide-up delay-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-800 mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-collection-play-fill text-primary"></i> Masterclasses & Courses
            </h5>
            <div class="d-flex align-items-center gap-2">
                <button class="swiper-prev-courses btn btn-light rounded-circle shadow-sm p-0" style="width: 32px; height: 32px; border: 1px solid #eee;">
                    <i class="bi bi-chevron-left" style="font-size: 12px;"></i>
                </button>
                <button class="swiper-next-courses btn btn-light rounded-circle shadow-sm p-0" style="width: 32px; height: 32px; border: 1px solid #eee;">
                    <i class="bi bi-chevron-right" style="font-size: 12px;"></i>
                </button>
            </div>
        </div>
        
        <div class="swiper coursesSwiper">
            <div class="swiper-wrapper">
                @foreach($courses as $course)
                <div class="swiper-slide">
                    <a href="{{ route('course.view', $course) }}" class="text-decoration-none h-100 d-block">
                        <div class="card-focus overflow-hidden h-100 transition-all hover-lift">
                            <div class="position-relative" style="height: 160px;">
                                <img src="{{ $course->thumbnail ?? 'https://img.freepik.com/free-vector/cheerful-woman-studying-internet-watching-webinar-computer-taking-online-course-vector-illustration-knowledge-education-distance-learning-concept_778687-3129.jpg?semt=ais_hybrid&w=740&q=80' }}" class="w-100 h-100 object-fit-cover">
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-bold small shadow-sm">{{ $course->category }}</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h6 class="fw-800 text-dark mb-2 line-clamp-2">{{ $course->title }}</h6>
                                <div class="d-flex align-items-center gap-2 text-muted small fw-bold">
                                    <i class="bi bi-play-btn-fill"></i>
                                    {{ $course->contents_count ?? $course->contents->count() }} Lessons
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif


    <!-- Career Readiness & Trending Row -->
    <div class="row mb-5" style="background: #f8fafc; padding: 20px; border-radius: 20px;">
        <div class="col-lg-6">
            <div class="card-focus shadow-sm h-100 animate-slide-up delay-5 border-0 overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);">
                <div class="row g-0 h-100">
                    <div class="col-md-5 d-none d-md-block position-relative">
                        <img src="{{ asset('images/dashboard/interview.png') }}" class="w-100 h-100 object-fit-cover">
                        <div class="position-absolute top-1 start-1 m-3">
                            <div class="interview-live-badge-mini"><span class="pulse-dot"></span> DAILY INSIGHTS</div>
                        </div>
                    </div>
                    <div class="col-md-7 p-4 p-lg-5 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-800 mb-0 d-flex align-items-center gap-3">
                                <div class="icon-focus-circle" style="width: 40px; height: 40px; background: #eff6ff; color: #2563eb; border-radius: 12px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-lightning-charge-fill"></i></div>
                                Daily Insights
                            </h5>
                        </div>
                        <div class="flex-grow-1">
                            <div class="swiper quotesSwiper pb-4">
                                <div class="swiper-wrapper">
                                    @php
                                        $quotes = [
                                            ['text' => "AI is probably the most important thing humanity has ever worked on.", 'author' => "Sundar Pichai", 'title' => "CEO, Google"],
                                            ['text' => "People who use AI will replace people who don't.", 'author' => "Jensen Huang", 'title' => "CEO, NVIDIA"],
                                            ['text' => "The advance of AI is not going to stop. Training yourself to work is critical.", 'author' => "Satya Nadella", 'title' => "CEO, Microsoft"]
                                        ];
                                    @endphp
                                    
                                    @foreach($quotes as $quote)
                                    <div class="swiper-slide">
                                        <p class="fw-800 text-dark mb-3" style="line-height: 1.5; font-size: 15px;">"{{ $quote['text'] }}"</p>
                                        <div class="d-flex align-items-center gap-3 mt-2">
                                            <div class="bg-primary text-white rounded-pill px-3 py-1 fw-800" style="font-size: 9px; letter-spacing: 0.05em;">{{ strtoupper($quote['author']) }}</div>
                                            <span class="text-muted fw-bold small" style="font-size: 10px;">{{ $quote['title'] }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination quotes-pagination" style="bottom: 0px !important;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-focus shadow-sm h-100 animate-slide-up delay-5 border-0" style="background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);">
                <div class="d-flex justify-content-between align-items-center p-4">
                    <h5 class="fw-800 mb-0 d-flex align-items-center gap-3">
                        <div class="icon-focus-circle" style="width: 40px; height: 40px; background: #fff1f2; color: #e11d48; border-radius: 12px; display: flex; align-items: center; justify-content: center;"><i class="bi bi-fire"></i></div>
                        Trending Categories
                    </h5>
                    <a href="{{ route('learn.explore') }}" class="btn-text-focus text-decoration-none fw-800 text-primary small">Explore All</a>
                </div>
                <div class="px-4 pb-4">
                    @php
                        $trendingCategories = \App\Models\Content::select('category', \DB::raw('count(*) as count'))
                            ->groupBy('category')
                            ->orderByDesc('count')
                            ->limit(4)
                            ->get();
                        
                        $catStyles = [
                            'AI' => ['icon' => 'bi-cpu', 'bg' => '#eef2ff', 'text' => '#4f46e5'],
                            'Design' => ['icon' => 'bi-palette', 'bg' => '#fff7ed', 'text' => '#ea580c'],
                            'Development' => ['icon' => 'bi-code-slash', 'bg' => '#f0fdf4', 'text' => '#16a34a'],
                            'Business' => ['icon' => 'bi-briefcase', 'bg' => '#faf5ff', 'text' => '#9333ea'],
                            'General' => ['icon' => 'bi-grid', 'bg' => '#f0f9ff', 'text' => '#0284c7']
                        ];
                    @endphp
                    
                    <div class="row g-3 trending-categories-row">
                        @foreach($trendingCategories as $cat)
                        @php 
                            $style = $catStyles[$cat->category] ?? $catStyles['General'];
                        @endphp
                        <div class="col-6 trending-cat-col">
                            <a href="{{ route('learn.explore', ['category' => $cat->category]) }}" class="cat-card-neo p-3 rounded-4 border border-light bg-white d-block text-decoration-none transition-all">
                                <div class="d-flex flex-column gap-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="cat-icon-box" style="width: 38px; height: 38px; background: {{ $style['bg'] }}; color: {{ $style['text'] }}; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                            <i class="bi {{ $style['icon'] }}"></i>
                                        </div>
                                        <span class="badge bg-light text-dark border rounded-pill fw-800" style="font-size: 9px;">{{ $cat->count }}</span>
                                    </div>
                                    <div>
                                        <h6 class="fw-800 text-dark mb-0" style="font-size: 14px;">{{ $cat->category }}</h6>
                                        <p class="text-muted mb-0" style="font-size: 10px; font-weight: 600;">Interactive Hub</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    :root {
        --focus-primary: #4338ca;
        --focus-primary-light: #eef2ff;
        --focus-bg: #f8fafc;
        --focus-card-bg: #ffffff;
        --focus-text: #1e293b;
        --focus-border: #e2e8f0;
        --focus-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05);
    }

    body { background-color: var(--focus-bg); color: var(--focus-text); }
    .dashboard-focus-modern { background-color: var(--focus-bg); }

    .text-dark { color: #334155 !important; }
    .fw-800.text-dark, .fw-900.text-dark { color: #1e293b !important; }

    /* Hero Card Refinement */
    .hero-focus-card {
        background: var(--focus-card-bg);
        border: 1px solid #f1f5f9;
        border-radius: 40px;
        box-shadow: 0 40px 80px -20px rgba(0,0,0,0.03);
    }

    /* Left hero column refined background + divider */
    .hero-left-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(160deg, #f8faff 0%, #ffffff 60%);
        pointer-events: none;
        z-index: 0;
    }
    .hero-focus-card .col-lg-6:first-child::after {
        content: '';
        position: absolute;
        right: 0;
        top: 10%;
        height: 80%;
        width: 1px;
        background: linear-gradient(to bottom, transparent 0%, #e2e8f0 30%, #e2e8f0 70%, transparent 100%);
        pointer-events: none;
    }

    /* ── Hero Label Badge ── */
    .hero-label-badge {
        display: inline-block;
        background: #eef2ff;
        color: #4338ca;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.08em;
        padding: 5px 14px;
        border-radius: 6px;
        text-transform: uppercase;
    }

    /* ── Hero Heading ── */
    .hero-heading {
        font-size: clamp(26px, 3.6vw, 40px);
        font-weight: 800;
        color: #64748b;
        letter-spacing: -0.02em;
        line-height: 1.15;
        margin: 0;
    }
    .hero-heading-accent {
        font-size: clamp(28px, 4vw, 46px);
        font-weight: 900;
        color: #0f172a;
        letter-spacing: -0.035em;
        line-height: 1.1;
        margin: 0;
        min-height: 1.1em; /* FIX 3: Maintain height when typing text is empty */
    }
    .hero-heading-accent span {
        color: #4338ca;
        display: inline-block;
        min-height: 1.1em; /* FIX 3: Maintain height for typing text span */
    }
    .hero-cursor {
        color: #4338ca;
        animation: blink-cursor 0.8s step-end infinite;
        margin-left: 2px;
    }
    @keyframes blink-cursor {
        0%, 100% { opacity: 1; }
        50% { opacity: 0; }
    }

    /* ── Search Bar ── */
    .search-wrapper { max-width: 100%; }
    .search-inner {
        display: flex;
        align-items: center;
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 6px 6px 6px 16px;
        box-shadow: 0 4px 24px -8px rgba(67, 56, 202, 0.12);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-inner:focus-within {
        border-color: #a5b4fc;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1), 0 4px 24px -8px rgba(67, 56, 202, 0.14);
    }
    .search-icon {
        color: #94a3b8;
        font-size: 16px;
        flex-shrink: 0;
        margin-right: 10px;
    }
    .search-input {
        flex: 1;
        border: none;
        outline: none;
        background: transparent;
        font-size: 15px;
        font-weight: 600;
        color: #1e1b4b;
        padding: 8px 0;
        box-shadow: none !important;
    }
    .search-input::placeholder { color: #94a3b8; font-weight: 500; }
    .search-voice-btn {
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 16px;
        padding: 6px 8px;
        cursor: pointer;
        transition: color 0.2s;
        flex-shrink: 0;
    }
    .search-voice-btn:hover { color: #4338ca; }
    .search-submit-btn {
        background: #0f172a;
        border: none;
        color: #fff;
        border-radius: 10px;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        transition: background 0.2s, transform 0.15s;
        cursor: pointer;
    }
    .search-submit-btn:hover { background: #4338ca; transform: scale(1.05); }

    /* ── Hero Action Pills — Unified, professional, search-bar-aligned ── */
    .btn-hero-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 20px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #1e293b;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s, transform 0.15s;
        cursor: pointer;
        white-space: nowrap;
        user-select: none;
        flex: 1;
        min-width: 0;
    }
    .btn-hero-pill:hover {
        border-color: #a5b4fc;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1), 0 4px 12px rgba(0,0,0,0.04);
        background: #f8faff;
        transform: translateY(-1px);
    }
    .btn-hero-pill i {
        font-size: 16px;
        color: #64748b;
        transition: color 0.2s;
    }
    .btn-hero-pill:hover i {
        color: #4338ca;
    }

    .hero-actions {
        display: flex;
        gap: 8px;
    }

    /* Journey Card */
    .journey-card {
        background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
    }

    /* New Stats Bar */
    .stats-premium-bar {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 40px;
        display: flex;
        flex-wrap: wrap;
        padding: 10px;
        box-shadow: 0 20px 40px -20px rgba(0,0,0,0.02);
    }
    .stat-item-premium {
        flex: 1;
        min-width: 250px;
        padding: 15px 30px;
        display: flex;
        align-items: center;
        gap: 20px;
        position: relative;
    }
    .stat-item-premium:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 25%;
        height: 50%;
        width: 1px;
        background: #f1f5f9;
    }
    .stat-item-premium .icon-box {
        width: 64px;
        height: 64px;
        border-radius: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        flex-shrink: 0;
    }
    .stat-label-premium {
        font-size: 10px;
        font-weight: 900;
        color: #94a3b8;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    .stat-value-premium {
        font-size: 28px;
        font-weight: 900;
        color: #1e1b4b;
        line-height: 1;
    }
    .bg-blue-subtle { background: #eff6ff; color: #3b82f6; }
    .bg-orange-subtle { background: #fff7ed; color: #f97316; }
    .bg-teal-subtle { background: #f0fdfa; color: #14b8a6; }
    .text-orange { color: #f97316; }
    .bg-orange { background: #f97316; }

    .animate-float-slow {
        animation: float-slow 8s ease-in-out infinite;
    }
    @keyframes float-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    .btn-white:hover {
        background: #f8fafc !important;
    }
    .focus-pill-tag {
        background: var(--focus-primary); color: #fff; font-size: 10px; font-weight: 900;
        padding: 5px 12px; border-radius: 8px; letter-spacing: 0.1em;
    }

    .recom-focus-card { background: #fff; transition: 0.3s; }
    .recom-thumb-focus { position: relative; background: #000; }
    .recom-thumb-focus img { 
        transition: 0.5s ease; 
        filter: brightness(0.85) saturate(0.8); 
    }
    .recom-focus-card:hover .recom-thumb-focus img { 
        filter: brightness(1.1) saturate(1.1); 
    }
    .recom-play-overlay {
        position: absolute; inset: 0; background: rgba(0,0,0,0.2);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 24px; opacity: 0; transition: 0.3s;
    }
    .recom-focus-card:hover { transform: translateY(-5px); border-color: var(--focus-primary); }
    .recom-focus-card:hover .recom-play-overlay { opacity: 1; }

    .btn-focus-link {
        color: var(--focus-primary); font-weight: 900; text-decoration: none;
        font-size: 14px; display: inline-flex; align-items: center;
    }
    .btn-focus-link:hover { transform: translateX(5px); }

    /* Stats */
    .stat-focus-card { background: #fff; border: 1px solid var(--focus-border); border-radius: 24px; transition: 0.3s; }
    .stat-focus-card:hover { border-color: var(--focus-primary); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
    .stat-icon-square {
        width: 52px; height: 52px; border-radius: 16px; display: flex;
        align-items: center; justify-content: center; font-size: 24px;
    }
    .pulsing-dot-success {
        width: 8px; height: 8px; background: #10b981; border-radius: 50%; display: inline-block;
        box-shadow: 0 0 0 rgba(16, 185, 129, 0.4); animation: pulse-green 2s infinite;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
    .bg-light-soft { background: #f8fafc; }
    .bg-white-soft { background: #ffffff; }
    .transition-hover:hover { transform: translateY(-3px); transition: all 0.3s ease; }
    .bg-sky { background: #f0f9ff; color: #0369a1; }
    .bg-blue { background: #eff6ff; color: #2563eb; }
    .bg-teal { background: #f0fdfa; color: #0d9488; }

    /* Shorts */
    .short-card-focus { transition: 0.3s; background: #000; }
    .short-card-focus img { 
        transition: 0.5s ease; 
        filter: brightness(0.8) grayscale(0.2); 
    }
    .short-card-focus:hover img { 
        filter: brightness(1.1) grayscale(0); 
        transform: scale(1.1); 
    }
    .short-card-focus:hover { transform: scale(1.05); z-index: 10; }
    .short-overlay-focus {
        position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        padding: 15px; display: flex; flex-direction: column; justify-content: flex-end;
    }
    .btn-short-play {
        width: 32px; height: 32px; background: #fff; border-radius: 50%;
        display: flex; align-items: center; justify-content: center; color: #000;
        font-size: 16px; text-decoration: none; align-self: flex-end; transition: 0.2s;
    }
    .btn-short-play:hover { background: var(--focus-primary); color: #fff; }

    /* Cards & Grids */
    .card-focus { background: #fff; border: 1px solid var(--focus-border); border-radius: 24px; transition: 0.3s; }
    .course-card-focus { background: #fff; border: 1px solid var(--focus-border); border-radius: 32px; overflow: hidden; transition: 0.3s; }
    .course-card-focus:hover { transform: translateY(-10px); border-color: var(--focus-primary); box-shadow: var(--focus-shadow); }
    .course-thumb-focus { height: 180px; background: #000; }
    .course-thumb-focus img { 
        transition: 0.5s cubic-bezier(0.4, 0, 0.2, 1); 
        filter: brightness(0.85) saturate(0.8);
        opacity: 0.9;
    }
    .course-card-focus:hover .course-thumb-focus img { 
        filter: brightness(1.1) saturate(1.1) scale(1.05); 
        opacity: 1;
    }
    .thumb-badge-focus {
        position: absolute; top: 15px; left: 15px; color: #fff; font-weight: 900;
        font-size: 9px; padding: 5px 12px; border-radius: 8px; letter-spacing: 0.05em;
    }
    .badge-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }

    .interview-live-badge-mini {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444; font-size: 9px; font-weight: 900; padding: 4px 10px; border-radius: 30px;
        display: flex; align-items: center; gap: 6px;
    }
    .pulse-dot { width: 6px; height: 6px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 10px #ef4444; animation: pulse-red 1.5s infinite; }
    @keyframes pulse-red { 0% { opacity: 0.4; } 50% { opacity: 1; } 100% { opacity: 0.4; } }

    .btn-dark-focus { background: #000; color: #fff; border: none; transition: 0.2s; }
    .btn-dark-focus:hover { background: #334155; transform: scale(1.02); }

    /* Global Animations */
    .animate-slide-up { opacity: 0; animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }
    .delay-5 { animation-delay: 0.5s; }

    @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .floating-hero-img { animation: float-neo 6s ease-in-out infinite; }
    @keyframes float-neo { 0%, 100% { transform: translateY(0) scale(1); } 50% { transform: translateY(-15px) scale(1.02); } }

    .shadow-2xl { box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.1); }

    /* Swiper Premium Customization */
    .swiper-button-disabled {
        opacity: 0.3 !important;
        cursor: not-allowed !important;
    }
    .swiper-prev-watching:hover, .swiper-next-watching:hover,
    .swiper-prev-shorts:hover, .swiper-next-shorts:hover,
    .swiper-prev-recommended:hover, .swiper-next-recommended:hover {
        background-color: var(--focus-primary) !important;
        color: #fff !important;
        border-color: var(--focus-primary) !important;
    }
    .swiper-slide {
        height: auto;
    }
    /* Browser Intelligence Styles */
    .domain-stat-item { padding: 5px 0; }
    .shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06); }
    .card-focus {
        background: #fff;
        border: 1px solid var(--focus-border);
        border-radius: 24px;
        transition: 0.3s;
    }
    .hover-lift:hover { transform: translateY(-5px); }
    .transition-all { transition: all 0.3s ease; }

    /* ==================== ULTRA PREMIUM MOBILE OPTIMIZATION ==================== */
    @media (max-width: 768px) {
        /* Global mobile container reset */
        .dashboard-focus-modern {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        .dashboard-focus-modern > .alert,
        .dashboard-focus-modern > .hero-focus-card,
        .dashboard-focus-modern > .stats-premium-bar,
        .dashboard-focus-modern > .continue-watching-section,
        .dashboard-focus-modern > div[class*="mb-5"] {
            margin-left: 16px !important;
            margin-right: 16px !important;
        }

        /* ========== HERO CARD - MOBILE ========== */
        .hero-focus-card {
            margin: 0 0 24px 0 !important;
            border-radius: 24px !important;
            box-shadow: 0 2px 24px rgba(0,0,0,0.04) !important;
        }
        .hero-focus-card .col-lg-6:first-child::after {
            display: none !important;
        }
        .hero-focus-card .col-lg-6:first-child {
            padding: 24px 20px 0 !important;
        }
        .hero-focus-card h1 {
            font-size: 24px !important;
            text-align: center !important;
            margin-bottom: 16px !important;
            line-height: 1.2 !important;
            letter-spacing: -0.04em !important;
        }
        .hero-focus-card h1 br {
            display: none;
        }
        #hero-typing-text {
            display: inline;
        }
        .hero-cursor { display: none !important; }

        /* Search - Compact */
        .search-wrapper { margin-bottom: 14px !important; }
        .search-inner {
            border-radius: 12px !important;
            padding: 5px 5px 5px 14px !important;
        }
        .search-input {
            font-size: 14px !important;
            padding: 6px 0 !important;
        }
        .search-submit-btn {
            width: 38px !important;
            height: 38px !important;
            border-radius: 9px !important;
            font-size: 16px !important;
        }
        .search-voice-btn { font-size: 14px !important; padding: 4px 6px !important; }

        /* Action Buttons - mobile compact pills */
        .hero-actions {
            flex-wrap: nowrap !important;
            gap: 6px !important;
        }
        .btn-hero-pill {
            font-size: 12px !important;
            padding: 10px 12px !important;
            border-radius: 12px !important;
            gap: 5px !important;
            flex: 1 1 0 !important;
            justify-content: center !important;
        }
        .btn-hero-pill i {
            font-size: 14px !important;
        }

        /* Journey Card */
        .hero-focus-card .col-lg-6:last-child {
            padding: 16px 20px 20px !important;
        }
        .journey-card {
            border-radius: 20px !important;
        }
        .journey-card .p-md-4 {
            padding: 16px !important;
        }
        .journey-card .d-flex.justify-content-between.align-items-center.mb-2 {
            margin-bottom: 12px !important;
        }
        .journey-card .d-flex.align-items-center.gap-3.mb-3 {
            gap: 10px !important;
            margin-bottom: 12px !important;
            padding: 10px !important;
            border-radius: 12px !important;
        }
        .journey-card .d-flex.align-items-center.gap-3.mb-3 .rounded-3 {
            width: 40px !important;
            height: 40px !important;
            border-radius: 10px !important;
        }
        .journey-card h4 {
            font-size: 14px !important;
        }
        .journey-card .d-flex.align-items-stretch.gap-3.mb-3 {
            gap: 10px !important;
            margin-bottom: 12px !important;
        }
        .journey-card svg[width="52"] {
            width: 42px !important;
            height: 42px !important;
        }
        .journey-card .fw-900[style*="font-size: 16px"] {
            font-size: 13px !important;
        }
        .journey-card .btn.rounded-pill {
            font-size: 12px !important;
            padding: 10px !important;
        }

        /* Empty Journey State */
        .journey-card .p-md-5[style*="min-height: 420px"] {
            min-height: auto !important;
            padding: 32px 20px !important;
        }
        .journey-card .d-flex.align-items-center.justify-content-center.mx-auto {
            width: 72px !important;
            height: 72px !important;
            border-radius: 24px !important;
        }
        .journey-card .d-flex.align-items-center.justify-content-center.mx-auto i {
            font-size: 28px !important;
        }
        .journey-card h4.fw-900 {
            font-size: 18px !important;
        }
        .journey-card p.mb-4.px-3 {
            font-size: 13px !important;
            max-width: 100% !important;
        }

        /* ========== STATS BAR - MOBILE GRID (FIX 4: Icons on top, grid layout) ========== */
        .stats-premium-bar {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 8px !important;
            overflow-x: visible !important;
            padding: 12px !important;
            border-radius: 20px !important;
        }
        .stat-item-premium {
            flex: none !important;
            min-width: auto !important;
            max-width: none !important;
            padding: 12px 8px !important;
            gap: 0 !important;
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
            border-right: none !important;
            scroll-snap-align: none !important;
        }
        .stat-item-premium::after {
            display: none !important;
        }
        .stat-item-premium .icon-box {
            width: 44px !important;
            height: 44px !important;
            border-radius: 14px !important;
            font-size: 20px !important;
            margin-bottom: 8px !important;
        }
        .stat-label-premium {
            font-size: 7px !important;
            letter-spacing: 0.08em !important;
            margin-bottom: 2px !important;
        }
        .stat-value-premium {
            font-size: 20px !important;
        }
        .stat-item-premium .text-muted.fw-800 {
            font-size: 8px !important;
            display: block !important;
        }
        .stat-item-premium .text-primary.fw-900 {
            font-size: 7px !important;
        }
        .stat-item-premium .progress {
            width: 60px !important;
            margin: 4px auto 0 !important;
        }
        .stat-item-premium .d-flex.align-items-baseline.gap-2 {
            flex-direction: column !important;
            align-items: center !important;
            gap: 2px !important;
        }
        .stat-item-premium .pulsing-dot-success {
            display: none !important;
        }

        /* ========== SECTION HEADERS - MOBILE ========== */
        .dashboard-focus-modern h5.fw-800 {
            font-size: 15px !important;
        }
        .dashboard-focus-modern .d-flex.justify-content-between.align-items-center.mb-4 {
            margin-bottom: 12px !important;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* ========== CONTINUE WATCHING - MOBILE ========== */
        .continue-watching-section {
            padding: 20px 16px !important;
            border-radius: 20px !important;
            margin-left: 16px !important;
            margin-right: 16px !important;
        }
        .continue-watching-section .d-none.d-md-block {
            display: none !important;
        }
        .continueWatchingSwiper .swiper-slide {
            width: 85% !important;
        }
        .continueWatchingSwiper .card-focus.p-3 {
            padding: 12px !important;
            gap: 12px !important;
            border-radius: 16px !important;
        }
        .continueWatchingSwiper .card-focus .rounded-3 {
            width: 64px !important;
            height: 48px !important;
            border-radius: 10px !important;
        }
        .continueWatchingSwiper h6 {
            font-size: 12px !important;
        }
        .continueWatchingSwiper .progress {
            height: 4px !important;
            margin-bottom: 4px !important;
        }
        .continueWatchingSwiper .text-muted.fw-800,
        .continueWatchingSwiper .text-primary.fw-800 {
            font-size: 9px !important;
        }

        /* ========== RECOMMENDED VIDEOS - MOBILE ========== */
        .recommendedVideosSwiper .swiper-slide {
            width: 72% !important;
        }
        .recommendedVideosSwiper .card-focus {
            border-radius: 18px !important;
        }
        .recommendedVideosSwiper .position-relative[style*="height: 150px"] {
            height: 130px !important;
        }
        .recommendedVideosSwiper .p-3.bg-white {
            padding: 12px !important;
        }
        .recommendedVideosSwiper h6 {
            font-size: 12px !important;
            height: 34px !important;
        }
        .recommendedVideosSwiper .badge.bg-white {
            font-size: 8px !important;
            padding: 4px 10px !important;
            margin: 8px !important;
        }

        /* ========== CONNECTED TOOLS - MOBILE 2x2 GRID ========== */
        .tracksSwiper .swiper-wrapper {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px !important;
            transform: none !important;
        }
        .tracksSwiper .swiper-slide {
            width: 100% !important;
            margin-right: 0 !important;
        }
        .tracksSwiper .card-focus.p-4 {
            padding: 16px 12px !important;
            border-radius: 18px !important;
        }
        .tracksSwiper .tool-logo-container {
            width: 56px !important;
            height: 56px !important;
            border-radius: 16px !important;
            margin-bottom: 10px !important;
        }
        .tracksSwiper .tool-logo {
            width: 32px !important;
            height: 32px !important;
        }
        .tracksSwiper h6 {
            font-size: 13px !important;
        }
        .tracksSwiper p {
            font-size: 10px !important;
        }
        .swiper-prev-tracks,
        .swiper-next-tracks {
            display: none !important;
        }

        /* ========== SHORTS - MOBILE 2x2 GRID ========== */
        .shortsSwiper .swiper-wrapper {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px !important;
            transform: none !important;
        }
        .shortsSwiper .swiper-slide {
            width: 100% !important;
            margin-right: 0 !important;
        }
        .short-card-focus {
            height: 180px !important;
            border-radius: 16px !important;
        }
        .short-overlay-focus {
            padding: 12px !important;
        }
        .short-overlay-focus h6 {
            font-size: 10px !important;
        }
        .btn-short-play {
            width: 28px !important;
            height: 28px !important;
            font-size: 14px !important;
        }
        .swiper-prev-shorts,
        .swiper-next-shorts {
            display: none !important;
        }

        /* ========== COURSES - MOBILE: Show 4 + View More Button (FIX 2) ========== */
        .coursesSwiper .swiper-wrapper {
            display: grid !important;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px !important;
            transform: none !important;
        }
        .coursesSwiper .swiper-slide {
            width: 100% !important;
            margin-right: 0 !important;
        }
        /* Hide courses beyond the 4th on mobile */
        .coursesSwiper .swiper-slide:nth-child(n+5) {
            display: none !important;
        }
        .coursesSwiper .swiper-slide.expanded-mobile {
            display: block !important;
        }
        .coursesSwiper .card-focus {
            border-radius: 18px !important;
        }
        .coursesSwiper .position-relative[style*="height: 160px"] {
            height: 120px !important;
        }
        .coursesSwiper .p-4 {
            padding: 12px !important;
        }
        .coursesSwiper h6 {
            font-size: 12px !important;
            margin-bottom: 6px !important;
        }
        .coursesSwiper .text-muted.small {
            font-size: 10px !important;
        }
        .swiper-prev-courses,
        .swiper-next-courses {
            display: none !important;
        }
        /* View More Button for Courses */
        .courses-view-more-btn {
            display: none !important;
        }
        @media (max-width: 768px) {
            .courses-view-more-btn {
                display: flex !important;
                align-items: center;
                justify-content: center;
                gap: 6px;
                width: 100%;
                margin-top: 12px;
                padding: 12px;
                background: #f1f5f9;
                border: 1.5px dashed #cbd5e1;
                border-radius: 14px;
                color: #4338ca;
                font-weight: 800;
                font-size: 13px;
                cursor: pointer;
                transition: all 0.2s;
            }
            .courses-view-more-btn:hover {
                background: #eef2ff;
                border-color: #4338ca;
            }
        }

        /* ========== CAREER & TRENDING - MOBILE (FIX 1: No slider for quotes) ========== */
        .dashboard-focus-modern .row.mb-5[style*="background"] {
            padding: 12px !important;
            border-radius: 20px !important;
            margin-left: 16px !important;
            margin-right: 16px !important;
        }
        .dashboard-focus-modern .row.mb-5[style*="background"] .col-lg-6 {
            padding: 0 !important;
        }
        .dashboard-focus-modern .row.mb-5[style*="background"] .col-lg-6:first-child {
            margin-bottom: 12px !important;
        }
        .dashboard-focus-modern .row.mb-5[style*="background"] .col-lg-6:first-child .card-focus {
            min-height: auto !important;
            height: auto !important;
        }
        .dashboard-focus-modern .row.mb-5[style*="background"] .col-md-7 {
            min-height: 180px !important;
            padding: 16px !important;
        }
        
        /* REMOVE SLIDER for Quotes - show static stacked content */
        .quotesSwiper .swiper-wrapper {
            display: block !important;
            transform: none !important;
        }
        .quotesSwiper .swiper-slide {
            display: block !important;
            width: 100% !important;
            margin-bottom: 16px !important;
        }
        .quotesSwiper .swiper-slide:not(:first-child) {
            display: none !important; /* Show only first quote on mobile */
        }
        .quotes-pagination {
            display: none !important; /* Hide pagination dots */
        }
        .quotesSwiper .swiper-slide p {
            font-size: 13px !important;
            line-height: 1.5 !important;
            margin-bottom: 10px !important;
        }
        .quotesSwiper .swiper-slide .bg-primary {
            font-size: 7px !important;
            padding: 3px 8px !important;
        }
        .quotesSwiper .swiper-slide .text-muted {
            font-size: 8px !important;
        }

        /* Trending Categories - Horizontal scroll cards */
        .trending-categories-row {
            display: flex !important;
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            scroll-snap-type: x mandatory;
            gap: 10px !important;
            padding-bottom: 4px;
        }
        .trending-categories-row::-webkit-scrollbar {
            display: none;
        }
        .trending-cat-col {
            flex: 0 0 75% !important;
            max-width: 75% !important;
            scroll-snap-align: start;
            padding: 0 !important;
        }
        .cat-card-neo.p-3 {
            padding: 14px !important;
            border-radius: 16px !important;
        }
        .cat-card-neo .cat-icon-box {
            width: 36px !important;
            height: 36px !important;
            border-radius: 10px !important;
            font-size: 16px !important;
        }
        .cat-card-neo h6 {
            font-size: 13px !important;
        }

        /* ========== NAVIGATION BUTTONS - HIDE ON MOBILE ========== */
        .swiper-prev-watching,
        .swiper-next-watching,
        .swiper-prev-recom-videos,
        .swiper-next-recom-videos {
            width: 28px !important;
            height: 28px !important;
        }
        .swiper-prev-watching i,
        .swiper-next-watching i,
        .swiper-prev-recom-videos i,
        .swiper-next-recom-videos i {
            font-size: 10px !important;
        }

        /* Badge adjustments */
        .d-none.d-md-inline-block {
            display: none !important;
        }
    }

    /* ========== EXTRA SMALL DEVICES (iPhone SE, etc.) ========== */
    @media (max-width: 380px) {
        .stats-premium-bar {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 6px !important;
            padding: 10px 6px !important;
        }
        .stat-item-premium {
            padding: 8px 4px !important;
        }
        .stat-item-premium .icon-box {
            width: 36px !important;
            height: 36px !important;
            font-size: 16px !important;
        }
        .stat-value-premium {
            font-size: 16px !important;
        }
        .hero-actions {
            flex-wrap: wrap !important;
        }
        .btn-hero-pill {
            font-size: 11px !important;
            padding: 8px 10px !important;
        }
        .tracksSwiper .swiper-wrapper,
        .shortsSwiper .swiper-wrapper,
        .coursesSwiper .swiper-wrapper {
            gap: 8px !important;
        }
        .continueWatchingSwiper .swiper-slide {
            width: 88% !important;
        }
        .recommendedVideosSwiper .swiper-slide {
            width: 80% !important;
        }
    }

    #voiceSearchBtn.listening {
        color: #dc2626 !important;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.15); }
        100% { transform: scale(1); }
    }
    @keyframes gentleFloat {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    /* Journey card refinements */
    .journey-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .journey-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 30px 70px -20px rgba(67, 56, 202, 0.08) !important;
        border-color: rgba(99, 102, 241, 0.12) !important;
    }
</style>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Tracks Swiper
        new Swiper('.tracksSwiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            navigation: {
                nextEl: '.swiper-next-tracks',
                prevEl: '.swiper-prev-tracks',
            },
            breakpoints: {
                640: { slidesPerView: 3, spaceBetween: 24 },
                1024: { slidesPerView: 5, spaceBetween: 24 },
            },
        });

        // Continue Watching Swiper
        if (document.querySelector('.continueWatchingSwiper')) {
            new Swiper('.continueWatchingSwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.swiper-next-watching',
                    prevEl: '.swiper-prev-watching',
                },
                breakpoints: {
                    640: { slidesPerView: 2, spaceBetween: 20 },
                    1024: { slidesPerView: 3, spaceBetween: 24 },
                },
            });
        }

        // Recommended Videos Swiper
        if (document.querySelector('.recommendedVideosSwiper')) {
            new Swiper('.recommendedVideosSwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.swiper-next-recom-videos',
                    prevEl: '.swiper-prev-recom-videos',
                },
                breakpoints: {
                    640: { slidesPerView: 2, spaceBetween: 20 },
                    1024: { slidesPerView: 4, spaceBetween: 24 },
                },
            });
        }

        // Shorts Swiper
        new Swiper('.shortsSwiper', {
            slidesPerView: 2,
            spaceBetween: 15,
            navigation: {
                nextEl: '.swiper-next-shorts',
                prevEl: '.swiper-prev-shorts',
            },
            breakpoints: {
                640: { slidesPerView: 3, spaceBetween: 20 },
                1024: { slidesPerView: 6, spaceBetween: 20 },
            },
        });

        // Quotes Swiper
        new Swiper('.quotesSwiper', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            autoHeight: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.quotes-pagination',
                clickable: true,
            },
        });

        // Courses Swiper
        new Swiper('.coursesSwiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            navigation: {
                nextEl: '.swiper-next-courses',
                prevEl: '.swiper-prev-courses',
            },
            breakpoints: {
                640: { slidesPerView: 2, spaceBetween: 24 },
                1024: { slidesPerView: 4, spaceBetween: 24 },
            },
        });

        // FIX 2: Courses View More functionality for mobile
        const coursesWrapper = document.querySelector('.coursesSwiper .swiper-wrapper');
        if (coursesWrapper && window.innerWidth <= 768) {
            // Create "View More" button
            const viewMoreBtn = document.createElement('div');
            viewMoreBtn.className = 'courses-view-more-btn';
            viewMoreBtn.innerHTML = '<i class="bi bi-plus-circle"></i> View More Courses';
            
            // Insert after swiper
            const coursesSwiperEl = document.querySelector('.coursesSwiper');
            if (coursesSwiperEl) {
                coursesSwiperEl.parentNode.insertBefore(viewMoreBtn, coursesSwiperEl.nextSibling);
                
                let isExpanded = false;
                viewMoreBtn.addEventListener('click', function() {
                    isExpanded = !isExpanded;
                    const hiddenSlides = coursesWrapper.querySelectorAll('.swiper-slide:nth-child(n+5)');
                    
                    if (isExpanded) {
                        hiddenSlides.forEach(slide => slide.classList.add('expanded-mobile'));
                        viewMoreBtn.innerHTML = '<i class="bi bi-dash-circle"></i> Show Less';
                    } else {
                        hiddenSlides.forEach(slide => slide.classList.remove('expanded-mobile'));
                        viewMoreBtn.innerHTML = '<i class="bi bi-plus-circle"></i> View More Courses';
                    }
                });
            }
        }

        // Dynamic search titles replacement
        const searchInputAlt = document.getElementById('dashboard-search-alt');
        const heroTypingText = document.getElementById('hero-typing-text');

        // Animator for Hero Title
        if (heroTypingText) {
            const heroStrings = [
                "Use Copilot in Outlook?", 
                "Create a report from Excel?", 
                "Use ChatGPT for emails?",
                "Learn new AI skills?"
            ];
            let heroIdx = 0;
            let heroCharIdx = 0;
            let heroIsDeleting = false;

            function typeHeroTitle() {
                const fullStr = heroStrings[heroIdx];
                heroTypingText.textContent = fullStr.substring(0, heroCharIdx);
                
                if (heroIsDeleting) {
                    heroCharIdx--;
                } else {
                    heroCharIdx++;
                }

                if (!heroIsDeleting && heroCharIdx > fullStr.length) {
                    heroIsDeleting = true;
                    setTimeout(typeHeroTitle, 2500); // Hold on the full string
                } else if (heroIsDeleting && heroCharIdx < 0) {
                    heroIsDeleting = false;
                    heroIdx = (heroIdx + 1) % heroStrings.length;
                    setTimeout(typeHeroTitle, 500);
                } else {
                    setTimeout(typeHeroTitle, heroIsDeleting ? 40 : 70);
                }
            }
            typeHeroTitle();
        }

        // Voice Search
        let voiceRecognition = null;

        window.startVoiceSearch = function() {
            const btn = document.getElementById('voiceSearchBtn');
            const input = document.getElementById('dashboard-search-alt');

            // Toggle off if already listening
            if (btn.classList.contains('listening')) {
                if (voiceRecognition) {
                    voiceRecognition.stop();
                }
                return;
            }

            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (!SpeechRecognition) {
                alert('Voice search requires Chrome, Edge, or Safari. Please try a different browser.');
                return;
            }
            voiceRecognition = new SpeechRecognition();
            voiceRecognition.lang = 'en-US';
            voiceRecognition.interimResults = true;
            voiceRecognition.continuous = true;
            voiceRecognition.maxAlternatives = 1;

            btn.classList.add('listening');
            btn.innerHTML = '<i class="bi bi-mic-fill" style="color:#dc2626;"></i>';
            btn.style.animation = 'pulse 1s infinite';
            input.placeholder = 'Listening... Speak now';

            voiceRecognition.onresult = function(e) {
                let transcript = '';
                for (let i = e.resultIndex; i < e.results.length; i++) {
                    transcript += e.results[i][0].transcript;
                }
                input.value = transcript;
                input.placeholder = transcript;
            };

            voiceRecognition.onerror = function(e) {
                console.error('Speech error:', e.error);
                btn.classList.remove('listening');
                btn.innerHTML = '<i class="bi bi-mic"></i>';
                btn.style.animation = '';
                input.placeholder = 'Search for tools, skills, or topics...';
                if (e.error === 'not-allowed') {
                    alert('Microphone access was denied. Please allow microphone access and try again.');
                } else if (e.error === 'no-speech') {
                    alert('No speech detected. Try speaking louder or check your microphone.');
                } else {
                    alert('Voice search error: ' + e.error + '. Try Chrome or disable Brave Shields for this site.');
                }
            };

            voiceRecognition.onend = function() {
                const transcript = input.value.trim();
                btn.classList.remove('listening');
                btn.innerHTML = '<i class="bi bi-mic"></i>';
                btn.style.animation = '';
                input.placeholder = 'Search for tools, skills, or topics...';
                if (transcript) {
                    input.form.submit();
                }
            };

            try {
                voiceRecognition.start();
            } catch (e) {
                console.error('Speech start error:', e);
                btn.classList.remove('listening');
                btn.innerHTML = '<i class="bi bi-mic"></i>';
                btn.style.animation = '';
                input.placeholder = 'Search for tools, skills, or topics...';
                alert('Voice search failed to start. Try disabling Brave Shields (lion icon in address bar) or use Chrome.');
            }
        };
    });
</script>

{{-- Create Roadmap Modal --}}
<div class="modal fade" id="newRoadmapModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg position-relative overflow-hidden" style="border-radius: 35px; background: #ffffff;">
            {{-- Decorative Grid --}}
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
                            <input type="text" name="query" class="form-control form-control-xl bg-light border-0 ps-5 py-4 fw-700" 
                                   style="border-radius: 20px; font-size: 1.1rem; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);" 
                                   placeholder="e.g. Become a Senior AI Architect" required autocomplete="off">
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
    .blueprint-icon-wrapper {
        width: 70px; height: 70px; background: #ffffff; border-radius: 20px;
        display: flex; align-items: center; justify-content: center; position: relative;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05); color: #94a3b8; transition: all 0.3s ease;
    }
    .pulse-aura {
        position: absolute; width: 100%; height: 100%; border-radius: inherit;
        background: rgba(79, 70, 229, 0.15); animation: pulse-grow 2s infinite; z-index: -1;
    }
    @keyframes pulse-grow {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(1.6); opacity: 0; }
    }
    .btn-launch-journey { background: linear-gradient(135deg, #4338ca 0%, #7c3aed 100%); border: none; letter-spacing: 1px; }
    .opacity-05 { opacity: 0.05; }

    .tour-overlay {
        position: fixed; inset: 0; z-index: 9999; display: flex;
        align-items: flex-start; justify-content: center;
    }
    .tour-panel {
        position: fixed; background: rgba(15, 23, 42, 0.6);
        transition: all 0.45s cubic-bezier(0.16, 1, 0.3, 1);
    }
    #tourTop    { top: 0; left: 0; right: 0; }
    #tourBottom { bottom: 0; left: 0; right: 0; }
    #tourLeft   { top: 0; bottom: 0; left: 0; }
    #tourRight  { top: 0; bottom: 0; right: 0; }
    .tour-tooltip {
        position: fixed; z-index: 10000;
        transition: left 0.5s cubic-bezier(0.16, 1, 0.3, 1),
                    top 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .tour-tooltip-body {
        background: #fff; border-radius: 20px; padding: 24px 28px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15); max-width: 360px;
        text-align: center; position: relative;
        transition: opacity 0.25s ease;
    }
    .tour-tooltip-arrow {
        width: 16px; height: 16px; background: #fff;
        position: absolute; top: -8px; left: 50%; margin-left: -8px;
        transform: rotate(45deg); border-radius: 3px;
    }
    .tour-tooltip-icon {
        width: 52px; height: 52px; margin: -8px auto 12px;
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        border-radius: 16px; display: flex; align-items: center;
        justify-content: center; font-size: 24px; color: #4338ca;
    }
    .tour-tooltip-title {
        font-weight: 800; font-size: 17px; color: #0f172a;
        margin-bottom: 6px; letter-spacing: -0.02em;
    }
    .tour-tooltip-text {
        font-size: 13.5px; color: #475569; line-height: 1.5;
        margin-bottom: 16px;
    }
    .tour-got-it-btn {
        background: linear-gradient(135deg, #4338ca, #7c3aed);
        color: #fff; border: none; padding: 10px 28px;
        border-radius: 30px; font-weight: 700; font-size: 14px;
        cursor: pointer; transition: transform 0.15s, box-shadow 0.15s;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .tour-got-it-btn:hover {
        transform: translateY(-1px); box-shadow: 0 6px 20px rgba(67,56,202,0.25);
    }
</style>

<script>
(function() {
    var overlay = document.getElementById('tourOverlay');
    if (!overlay) return;

    var topPanel = document.getElementById('tourTop');
    var bottomPanel = document.getElementById('tourBottom');
    var leftPanel = document.getElementById('tourLeft');
    var rightPanel = document.getElementById('tourRight');
    var tooltip = document.getElementById('tourTooltip');
    var body = document.getElementById('tourBody');
    var dismissForm = document.getElementById('tourDismissForm');

    var steps = [
        {
            selector: '.search-wrapper',
            icon: 'bi-search',
            title: 'AI-Powered Search',
            text: 'Ask AI anything \u2014 instantly find courses, tools, and videos on any topic you want to learn.'
        },
        {
            selector: ".nav-link-neo[href*='learn/explore']",
            icon: 'bi-collection-play',
            title: 'Content Library',
            text: 'Browse our full library \u2014 explore all videos, courses, and tutorials on any skill or tool.'
        },
        {
            selector: ".nav-link-neo[href*='roadmap']",
            icon: 'bi-graph-up-arrow',
            title: 'Your Progress',
            text: 'Monitor your learning journey \u2014 completed videos, course progress, and roadmap milestones all in one place.'
        },
        {
            selector: ".nav-link-neo[href*='extension']",
            icon: 'bi-puzzle',
            title: 'AI Extension',
            text: 'Install the browser extension to sync your browsing activity across devices and get personalized recommendations.'
        },
        {
            selector: '.navbar-actions .icon-btn-neo',
            icon: 'bi-map',
            title: 'Your Roadmaps',
            text: 'Quick access to your learning paths \u2014 continue your current roadmap or start a new journey from here.'
        }
    ];

    var currentStep = 0;

    function positionSpotlight(selector) {
        var el = document.querySelector(selector);
        if (!el) return;

        var rect = el.getBoundingClientRect();
        var pad = 24;

        topPanel.style.height = (rect.top - pad) + 'px';
        bottomPanel.style.top = (rect.bottom + pad) + 'px';
        leftPanel.style.width = (rect.left - pad) + 'px';
        leftPanel.style.top = (rect.top - pad) + 'px';
        leftPanel.style.height = (rect.bottom - rect.top + pad * 2) + 'px';
        rightPanel.style.left = (rect.right + pad) + 'px';
        rightPanel.style.top = (rect.top - pad) + 'px';
        rightPanel.style.height = (rect.bottom - rect.top + pad * 2) + 'px';

        var tooltipWidth = 360;
        var tooltipLeft = rect.left + rect.width / 2 - tooltipWidth / 2;
        tooltipLeft = Math.max(16, Math.min(tooltipLeft, window.innerWidth - tooltipWidth - 16));
        tooltip.style.left = tooltipLeft + 'px';
        tooltip.style.top = (rect.bottom + pad + 16) + 'px';
    }

    function renderStep(index) {
        var step = steps[index];
        if (!step) return;

        var isLast = index === steps.length - 1;
        var wasFirst = index === 0;

        if (wasFirst) {
            body.innerHTML =
                '<div class="tour-tooltip-icon"><i class="bi ' + step.icon + '"></i></div>' +
                '<h5 class="tour-tooltip-title">' + step.title + '</h5>' +
                '<p class="tour-tooltip-text">' + step.text + '</p>' +
                '<button class="tour-got-it-btn" id="tourActionBtn">' +
                (isLast ? 'Got it! \u2728' : 'Next \u2192') +
                '</button>';
            body.style.opacity = '1';
            positionSpotlight(step.selector);
            setTimeout(function() { positionSpotlight(step.selector); }, 120);
            setTimeout(function() { positionSpotlight(step.selector); }, 450);
            document.getElementById('tourActionBtn').onclick = actionClick;
            return;
        }

        body.style.opacity = '0';

        setTimeout(function() {
            body.innerHTML =
                '<div class="tour-tooltip-icon"><i class="bi ' + step.icon + '"></i></div>' +
                '<h5 class="tour-tooltip-title">' + step.title + '</h5>' +
                '<p class="tour-tooltip-text">' + step.text + '</p>' +
                '<button class="tour-got-it-btn" id="tourActionBtn">' +
                (isLast ? 'Got it! \u2728' : 'Next \u2192') +
                '</button>';

            positionSpotlight(step.selector);

            setTimeout(function() {
                body.style.opacity = '1';
            }, 60);

            document.getElementById('tourActionBtn').onclick = actionClick;
        }, 280);
    }

    function actionClick() {
        var isLast = currentStep === steps.length - 1;
        if (isLast) {
            document.body.style.overflow = '';
            dismissForm.submit();
        } else {
            currentStep++;
            renderStep(currentStep);
        }
    }

    function startTour() {
        document.body.style.overflow = 'hidden';
        var panelTransition = topPanel.style.transition;
        topPanel.style.transition = 'none';
        bottomPanel.style.transition = 'none';
        leftPanel.style.transition = 'none';
        rightPanel.style.transition = 'none';
        tooltip.style.transition = 'none';
        positionSpotlight(steps[0].selector);
        overlay.style.display = '';
        overlay.getBoundingClientRect();
        topPanel.style.transition = panelTransition;
        bottomPanel.style.transition = panelTransition;
        leftPanel.style.transition = panelTransition;
        rightPanel.style.transition = panelTransition;
        tooltip.style.transition = '';
        renderStep(0);
        currentStep = 0;
    }

    var heroCard = document.querySelector('.hero-focus-card');
    var hasAnim = heroCard && typeof heroCard.getAnimations === 'function';
    var anims = hasAnim ? heroCard.getAnimations() : [];
    if (anims.length) {
        Promise.all(anims.map(function(a) { return a.finished; })).then(function() {
            setTimeout(startTour, 60);
        });
    } else {
        if (document.readyState === 'complete') {
            startTour();
        } else {
            window.addEventListener('load', startTour);
        }
    }

    window.addEventListener('scroll', function() {
        if (!steps[currentStep]) return;
        clearTimeout(window._tourScroll);
        window._tourScroll = setTimeout(function() {
            positionSpotlight(steps[currentStep].selector);
        }, 50);
    }, {passive: true});

    var resizeTimer;
    window.addEventListener('resize', function() {
        if (!steps[currentStep]) return;
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            positionSpotlight(steps[currentStep].selector);
        }, 100);
    });
})();
</script>
@endsection