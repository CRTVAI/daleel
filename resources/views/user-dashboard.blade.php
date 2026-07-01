@extends('layouts.user')

@section('title', 'Dashboard — Daleel AI')

@section('content')
<div class="dashboard-focus-modern pb-5">
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
    <!-- Top Row: Welcome Hero & Stats -->
    <!-- Premium Hero Section (Centered Design) -->
    <!-- Premium Hero Section (Two-Column Layout) -->
    <div class="hero-focus-card animate-slide-up rounded-5 overflow-hidden mb-5 border-0" 
         style="background: #ffffff; box-shadow: 0 40px 80px -20px rgba(0,0,0,0.03);">
        <div class="row g-0 align-items-center">
            <!-- Left Side: Search & Action Buttons -->
            <div class="col-lg-6 p-4 p-md-5 text-start border-end border-light">
                <div class="ps-lg-3">
                    <h1 class="mb-4" style="font-weight: 700; font-size: clamp(32px, 5vw, 48px); color: #0f172a; letter-spacing: -0.06em; line-height: 1.05;">
                        What do you want to <br> 
                        <span id="hero-typing-text" style="color: #4338ca; border-right: 4px solid #4338ca; padding-right: 8px;">learn?</span>
                    </h1>

                    <form action="{{ route('search.advanced') }}" method="GET" class="search-wrapper mb-5" style="max-width: 100%;">
                        <div class="input-group bg-white rounded-pill p-2 border border-primary border-opacity-25 transition-all" 
                             style="box-shadow: 0 25px 50px -12px rgba(67, 56, 202, 0.15), 0 0 15px rgba(67, 56, 202, 0.05) !important; border: 2px solid #eef2ff !important;">
                            <span class="input-group-text bg-transparent border-0 ps-4">
                                <i class="bi bi-search text-primary fs-5"></i>
                            </span>
                            <input type="text" name="search" id="dashboard-search-alt" class="form-control border-0 bg-transparent py-3 ps-2" 
                                   placeholder="Search for tools, skills, or topics..." 
                                   style="font-size: 19px; font-weight: 600; color: #1e1b4b; outline: none !important; box-shadow: none !important;">
                            <button type="submit" class="btn rounded-pill px-4 ms-2 d-flex align-items-center justify-content-center transition-all btn-search-premium shadow-sm" 
                                    style="width: 54px; height: 54px; background: #4338ca; border: none; color: #fff; box-shadow: 0 10px 15px -3px rgba(67, 56, 202, 0.3) !important;">
                                <i class="bi bi-arrow-right fs-4"></i>
                            </button>
                        </div>
                    </form>

                    <div class="hero-actions d-flex flex-wrap gap-2 gap-md-3">
                        <a href="{{ route('learn.explore') }}" class="btn-hero-pill-mini btn-hero-browse">
                            <i class="bi bi-grid-fill"></i> Browse Tools
                        </a>
                        <div role="button" data-bs-toggle="modal" data-bs-target="#newRoadmapModal" class="btn-hero-pill-mini btn-hero-create">
                            <i class="bi bi-plus-circle-fill"></i> Create New Path
                        </div>  
                        <a href="{{ route('ai.mentor') }}" class="btn-hero-pill-mini btn-hero-mentor">
                            <i class="bi bi-stars"></i> AI Mentor
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Side: Roadmap Card (Premium Design from Image) -->
            <div class="col-lg-6 p-4 p-md-5">
                <div class="roadmap-card bg-white rounded-5 shadow-sm p-4 p-md-5 border border-light transition-hover h-100" 
                     style="min-height: 400px; box-shadow: 0 15px 40px rgba(0,0,0,0.03) !important;">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                <i class="bi bi-map-fill fs-5"></i>
                            </div>
                            <span class="fw-800 text-dark" style="font-size: 19px; letter-spacing: -0.02em;">
                                {{ $currentRoadmap ? 'Current Journey' : 'Next Milestone' }}
                            </span>
                        </div>
                        @if($currentRoadmap)
                            <a href="{{ route('roadmap') }}" class="btn btn-link text-primary fw-800 text-decoration-none small">
                                All Paths <i class="bi bi-arrow-right"></i>
                            </a>
                        @endif
                    </div>

                    @if($currentRoadmap)
                        @php
                            $firstToolId = $currentRoadmap->tools[0] ?? null;
                            $firstTool = $firstToolId ? \App\Models\Tool::find($firstToolId) : null;
                        @endphp

                        <div class="d-flex align-items-center gap-4 mb-4 mt-2">
                            <div class="roadmap-icon-box bg-white rounded-4 d-flex align-items-center justify-content-center overflow-hidden shadow-sm p-3" style="width: 86px; height: 86px; border: 1px solid #f1f5f9;">
                                 <img src="{{ $firstTool ? asset($firstTool->logo) : asset('images/logo-placeholder.png') }}" class="w-100 h-100 object-fit-contain">
                            </div>
                            <div>
                                <h4 class="fw-900 text-dark mb-1 line-clamp-1" style="font-size: 22px; letter-spacing: -0.01em;">{{ $currentRoadmap->title }}</h4>
                                <p class="text-muted fw-700 small mb-0">Mastering {{ count($currentRoadmap->tools) }} critical tools</p>
                            </div>
                        </div>

                        <div class="mb-5">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="small fw-800 text-muted uppercase" style="font-size: 10px; letter-spacing: 0.5px;">OVERALL MASTERY</span>
                                <span class="fw-900 text-primary" style="font-size: 14px;">{{ $currentRoadmap->progress }}%</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 10px; background: #f1f5f9; border: 1px solid #f1f5f9;">
                                <div class="progress-bar bg-primary shadow-sm rounded-pill" style="width: {{ $currentRoadmap->progress }}%;"></div>
                            </div>
                        </div>

                        <a href="{{ route('roadmap.show', $currentRoadmap->id) }}" 
                           class="btn-roadmap-continue text-decoration-none w-100 rounded-pill py-3 fw-900 d-flex align-items-center justify-content-center gap-3 transition-hover shadow-lg">
                            Resume Journey <i class="bi bi-chevron-right fs-5"></i>
                        </a>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-4">
                                <div class="pulse-icon bg-light text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                                    <i class="bi bi-stars fs-1"></i>
                                </div>
                            </div>
                            <h5 class="fw-900 text-dark mb-2">No active roadmap</h5>
                            <p class="text-muted small mb-4 px-4">Let AI build you a personalized path to learn any tool or career goal.</p>
                            <a href="{{ route('learn.explore') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-800 shadow-sm border-0" style="background: var(--primary);">
                                Generate My Path
                            </a>
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
    <div class="mb-5 animate-slide-up delay-4 px-4 py-5 rounded-5" style="background: #eef2ff; border: 1px solid #f1f5f9;">
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
                                <img src="{{ $course->thumbnail }}" class="w-100 h-100 object-fit-cover">
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
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="swiper quotesSwiper pb-5">
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
                                        <p class="fw-800 text-dark mb-3" style="line-height: 1.5; font-size: 15px; min-height: 80px;">"{{ $quote['text'] }}"</p>
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
                    
                    <div class="row g-3">
                        @foreach($trendingCategories as $cat)
                        @php 
                            $style = $catStyles[$cat->category] ?? $catStyles['General'];
                        @endphp
                        <div class="col-6">
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

    .btn-hero-pill-mini {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px 20px;
        font-weight: 800;
        font-size: 13px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .btn-hero-browse { background: #f0f9ff; color: #0369a1; border-color: #e0f2fe; }
    .btn-hero-browse:hover { background: #e0f2fe; border-color: #bae6fd; transform: translateY(-2px); }
    
    .btn-hero-activity { background: #f0fdf4; color: #166534; border-color: #dcfce7; }
    .btn-hero-activity:hover { background: #dcfce7; border-color: #bbf7d0; transform: translateY(-2px); }
    
    .btn-hero-mentor { background: #f5f3ff; color: #5b21b6; border-color: #ede9fe; }
    .btn-hero-mentor:hover { background: #ede9fe; border-color: #ddd6fe; transform: translateY(-2px); }

    .btn-roadmap-continue {
        background: linear-gradient(to right, #4338ca, #6366f1);
        color: #ffffff;
        border: none;
        box-shadow: 0 10px 20px -5px rgba(67, 56, 202, 0.3);
    }
    .btn-roadmap-continue:hover {
        background: linear-gradient(to right, #3730a3, #4f46e5);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 15px 30px -5px rgba(67, 56, 202, 0.4);
    }

    /* Roadmap Card Focus */
    .roadmap-card {
        background: linear-gradient(135deg, #ffffff 0%, #f5f7ff 100%);
        border: 1px solid #eef2ff !important;
        transition: all 0.4s ease;
    }
    .roadmap-card:hover {
        transform: translateY(-4px);
        border-color: var(--focus-primary) !important;
        box-shadow: 0 20px 40px -15px rgba(67, 56, 202, 0.1);
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
            spaceBetween: 50,
            loop: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.quotes-pagination',
                clickable: true,
            },
        });

        // Main Recommended Swiper
        new Swiper('.mainRecommendedSwiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            navigation: {
                nextEl: '.swiper-next-main-recom',
                prevEl: '.swiper-prev-main-recom',
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 4 }
            }
        });

        // Recommended Videos Swiper (Behavioral)
        new Swiper('.recommendedVideosSwiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            navigation: {
                nextEl: '.swiper-next-recom-videos',
                prevEl: '.swiper-prev-recom-videos',
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 4 }
            }
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
    // Dynamic search titles replacement
    const searchInputAlt = document.getElementById('dashboard-search-alt');
    const heroTypingText = document.getElementById('hero-typing-text');

    // Animator for Hero Title
    if (heroTypingText) {
        const heroStrings = [
            "use Copilot in Outlook?", 
            "create a report from Excel?", 
            "use ChatGPT for emails?",
            "learn new AI skills?"
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
                
                <div class="text-center mt-4">
                    <span class="badge bg-light text-muted rounded-pill px-3 py-2 fw-700" style="font-size: 10px; letter-spacing: 1px; text-transform: uppercase;">Powered by GPT-4o Mini</span>
                </div>
            </div>
            <button type="button" class="btn-close position-absolute top-0 end-0 m-4 shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    </div>
</div>

<style>
    .btn-hero-create {
        background: #f8fafc;
        color: #4338ca !important;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        padding: 5px 20px !important;
        height: 42px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 8px !important;
    }
    .btn-hero-create:hover { background: #eef2ff; border-color: #4338ca; transform: translateY(-2px); }
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
</style>
@endsection
