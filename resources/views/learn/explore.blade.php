@extends('layouts.user')

@section('title', 'Learning Hub — Explore — Dallel AI')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    :root {
        --crtv-primary: #6366f1;
        --crtv-primary-hover: #4f46e5;
        --crtv-dark-bg: #0f172a;
        --crtv-border: #f1f5f9;
        --crtv-card-hover: rgba(99, 102, 241, 0.04);
    }
    
    .explore-hero-premium {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        border-radius: 24px;
        padding: 4.5rem 2rem;
        color: #ffffff;
        margin-bottom: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(49, 46, 129, 0.15);
    }
    
    .explore-hero-premium::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 60%;
        height: 200%;
        background: radial-gradient(circle, rgba(129, 140, 248, 0.15) 0%, transparent 60%);
        transform: rotate(-15deg);
        pointer-events: none;
    }

    .explore-hero-premium::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z'/%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }

    .explore-title {
        font-family: 'Outfit', 'Inter', sans-serif;
        font-weight: 900;
        letter-spacing: -0.04em;
        line-height: 1.1;
    }

    .search-container-premium {
        max-width: 680px;
        margin: 0 auto;
        position: relative;
        z-index: 10;
    }

    .search-input-premium {
        width: 100%;
        background: rgba(255, 255, 255, 0.95);
        border: 2px solid transparent;
        padding: 1.1rem 1.5rem 1.1rem 3.5rem;
        border-radius: 16px;
        color: #0f172a;
        font-size: 1.05rem;
        font-weight: 600;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .search-input-premium:focus {
        background: #ffffff;
        border-color: #818cf8;
        outline: none;
        box-shadow: 0 12px 35px rgba(99, 102, 241, 0.25);
    }

    .search-icon-premium {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.35rem;
        color: var(--crtv-primary);
        pointer-events: none;
    }

    .quick-tags {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1.2rem;
        z-index: 10;
        position: relative;
    }

    .quick-tag-label {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.6);
        align-self: center;
        font-weight: 700;
        margin-right: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .quick-tag-btn {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0.3rem 0.75rem;
        border-radius: 99px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
    }

    .quick-tag-btn:hover {
        background: #ffffff;
        color: var(--crtv-dark-bg);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.15);
    }

    /* Type Switches */
    .type-switcher {
        display: inline-flex;
        background: #f1f5f9;
        padding: 0.4rem;
        border-radius: 14px;
        margin-bottom: 2rem;
    }

    .type-switch-btn {
        padding: 0.55rem 1.4rem;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.85rem;
        text-decoration: none;
        color: #64748b;
        transition: all 0.2s;
        border: none;
    }

    .type-switch-btn.active {
        background: #ffffff;
        color: var(--crtv-dark-bg);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Category Navigation */
    .topic-slider {
        display: flex;
        gap: 0.6rem;
        overflow-x: auto;
        padding-bottom: 0.75rem;
        margin-bottom: 2rem;
        scrollbar-width: none;
    }

    .topic-slider::-webkit-scrollbar {
        display: none;
    }

    .topic-pill {
        white-space: nowrap;
        padding: 0.6rem 1.3rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 99px;
        font-weight: 800;
        font-size: 0.8rem;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s;
    }

    .topic-pill:hover {
        border-color: var(--crtv-primary);
        color: var(--crtv-primary);
        transform: translateY(-1px);
    }

    .topic-pill.active {
        background: var(--crtv-dark-bg);
        color: #ffffff;
        border-color: var(--crtv-dark-bg);
        box-shadow: 0 6px 15px rgba(15, 23, 42, 0.15);
    }

    /* Premium Lesson & Course Cards */
    .premium-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: inherit;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .premium-card:hover {
        transform: translateY(-6px);
        border-color: #818cf8;
        box-shadow: 0 20px 30px rgba(99, 102, 241, 0.08);
    }

    .premium-thumb {
        height: 170px;
        position: relative;
        overflow: hidden;
        background: #f8fafc;
    }

    .premium-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .premium-card:hover .premium-thumb img {
        transform: scale(1.05);
    }

    .premium-card-overlay {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        z-index: 5;
    }

    .premium-badge {
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        backdrop-filter: blur(8px);
        color: #ffffff;
        background: rgba(15, 23, 42, 0.75);
    }

    .tool-connection-badge {
        font-size: 0.68rem;
        font-weight: 800;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        backdrop-filter: blur(8px);
        color: #ffffff;
        background: rgba(99, 102, 241, 0.9);
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
    }

    .premium-bookmark-btn {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        transition: all 0.2s;
        z-index: 10;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .premium-bookmark-btn:hover {
        background: #ffffff;
        color: var(--crtv-dark-bg);
        transform: scale(1.08);
    }

    .premium-bookmark-btn.active {
        background: var(--crtv-dark-bg);
        color: #ffffff;
    }

    .premium-body {
        padding: 1.25rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .premium-title {
        font-family: 'Outfit', 'Inter', sans-serif;
        font-weight: 800;
        font-size: 0.92rem;
        color: #0f172a;
        margin-bottom: 0.5rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 40px;
    }

    .premium-desc {
        font-size: 0.78rem;
        color: #64748b;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 34px;
        line-height: 1.4;
    }

    .premium-footer {
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.72rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .watch-cta-btn {
        font-size: 0.72rem;
        font-weight: 800;
        color: var(--crtv-primary);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.2s;
    }

    .premium-card:hover .watch-cta-btn {
        color: var(--crtv-primary-hover);
        transform: translateX(2px);
    }

    /* Custom Pagination Grid */
    .pagination-wrapper {
        margin-top: 3.5rem;
    }
    
    .pagination {
        display: flex;
        gap: 0.4rem;
        justify-content: center;
        border: none;
    }

    .pagination .page-item .page-link {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 800;
        font-size: 0.82rem;
        padding: 0.6rem 1.1rem;
        transition: all 0.2s;
    }

    .pagination .page-item .page-link:hover {
        background: #fafafa;
        color: var(--crtv-primary);
        border-color: var(--crtv-primary);
    }

    .pagination .page-item.active .page-link {
        background: var(--crtv-dark-bg);
        color: #ffffff;
        border-color: var(--crtv-dark-bg);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
    }

    .pagination .page-item.disabled .page-link {
        background: #f8fafc;
        color: #cbd5e1;
        border-color: #f1f5f9;
    }
    
    /* Clean paginator detail hide */
    nav .d-none.flex-sm-fill.d-sm-flex > div:first-child,
    nav p.small.text-muted {
        display: none !important;
    }
    nav .d-sm-flex.justify-content-sm-between {
        justify-content: center !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid p-0">
    
    {{-- Search Banner Hero --}}
    <div class="explore-hero-premium animate-slide-up">
        <div class="text-center mb-4 position-relative" style="z-index: 10;">
            <h1 class="explore-title display-5 mb-2">What do you want to learn today?</h1>
            <p class="opacity-75 fs-6 fw-600">Access our premium catalog of video tutorials and complete masterclasses.</p>
        </div>

        <form method="GET" action="{{ route('learn.explore') }}" class="search-container-premium">
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="category" value="{{ request('category') }}">
            
            <i class="bi bi-search search-icon-premium"></i>
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="search-input-premium" placeholder="Search by title, category, tag, or connected browser tool...">
            
            @if(request('search'))
                <a href="{{ route('learn.explore', ['type' => $type, 'category' => request('category')]) }}" 
                   class="position-absolute end-0 top-50 translate-middle-y me-3 text-muted">
                    <i class="bi bi-x-circle-fill"></i>
                </a>
            @endif
        </form>

        {{-- Tool quick filters --}}
        <div class="quick-tags animate-slide-up delay-1">
            <span class="quick-tag-label">🔌 Connected Browser Tools:</span>
            @php
                $emojiMap = [
                    'chatgpt' => '🤖',
                    'notion' => '📝',
                    'slack' => '💬',
                    'zapier' => '⚡',
                    'gmail' => '📧',
                    'youtube' => '🎥',
                    'github' => '💻',
                    'figma' => '🎨',
                ];
            @endphp
            @foreach($connectedTools as $tool)
                @php
                    $lowerName = strtolower($tool->name);
                    $emoji = $emojiMap[$lowerName] ?? '🔌';
                @endphp
                <a href="{{ route('learn.explore', ['type' => $type, 'search' => $lowerName]) }}" class="quick-tag-btn">
                    {{ $emoji }} {{ $tool->name }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Layout Head / Filters --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        {{-- Mode Switcher Toggle --}}
        <div class="type-switcher animate-slide-up">
            <a href="{{ route('learn.explore', array_merge(request()->all(), ['type' => 'video'])) }}" 
               class="type-switch-btn {{ $type === 'video' ? 'active' : '' }}">
                <i class="bi bi-play-circle me-1"></i> Video Lessons
            </a>
            <a href="{{ route('learn.explore', array_merge(request()->all(), ['type' => 'course'])) }}" 
               class="type-switch-btn {{ $type === 'course' ? 'active' : '' }}">
                <i class="bi bi-journal-bookmark me-1"></i> Full Masterclasses
            </a>
        </div>
        
        <div class="text-muted small fw-800 animate-slide-up">{{ $items->total() }} items available</div>
    </div>

    {{-- Category Pills --}}
    <div class="topic-slider animate-slide-up delay-1">
        <a href="{{ route('learn.explore', ['type' => $type, 'search' => request('search')]) }}" 
           class="topic-pill {{ !request('category') ? 'active' : '' }}">All Subjects</a>
        @foreach($categories as $cat)
            <a href="{{ route('learn.explore', ['type' => $type, 'category' => $cat, 'search' => request('search')]) }}" 
               class="topic-pill {{ request('category') == $cat ? 'active' : '' }}">{{ $cat }}</a>
        @endforeach
    </div>

    {{-- SECTION 1: Personal Path Recommendations --}}
    @if($recommendedItems->isNotEmpty() && !request('search') && !request('category'))
    <div class="mb-5 animate-slide-up delay-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-800 text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-stars text-primary"></i> Recommended for Your Learning Path
            </h4>
            <div class="d-flex gap-2">
                <button class="swiper-prev-explore-recom btn btn-light rounded-circle shadow-sm p-0" style="width: 32px; height: 32px; border: 1px solid #eee;">
                    <i class="bi bi-chevron-left" style="font-size: 12px;"></i>
                </button>
                <button class="swiper-next-explore-recom btn btn-light rounded-circle shadow-sm p-0" style="width: 32px; height: 32px; border: 1px solid #eee;">
                    <i class="bi bi-chevron-right" style="font-size: 12px;"></i>
                </button>
            </div>
        </div>
        
        <div class="swiper exploreRecomSwiper" style="overflow: hidden; padding: 10px 4px;">
            <div class="swiper-wrapper">
                @foreach($recommendedItems as $item)
                <div class="swiper-slide h-auto">
                    <div class="premium-card h-100 d-flex flex-column">
                        <button class="premium-bookmark-btn {{ Auth::user()->bookmarkedContents()->where('content_id', $item->id)->exists() ? 'active' : '' }}" 
                                onclick="event.preventDefault(); toggleBookmark({{ $item->id }}, this)" 
                                title="Bookmark">
                            <i class="bi {{ Auth::user()->bookmarkedContents()->where('content_id', $item->id)->exists() ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                        </button>
                        <a href="{{ route('learn.watch', $item) }}" style="text-decoration:none;color:inherit;display:flex;flex-direction:column;height:100%">
                            <div class="premium-thumb">
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}">
                                <div class="premium-card-overlay">
                                    <span class="premium-badge">{{ $item->category }}</span>
                                    @if($item->connected_tools && is_array($item->connected_tools))
                                        @foreach($item->connected_tools as $tool)
                                            <span class="tool-connection-badge"><i class="bi bi-cpu"></i> {{ ucfirst($tool) }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="premium-body bg-white flex-grow-1 d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="premium-title">{{ $item->title }}</h6>
                                    <p class="premium-desc">{{ $item->description }}</p>
                                </div>
                                <div class="premium-footer mt-auto pt-3 border-top border-light">
                                    <span><i class="bi bi-clock me-1"></i> {{ $item->duration_label ?: '15m' }}</span>
                                    <span class="watch-cta-btn">WATCH LESSON <i class="bi bi-arrow-right"></i></span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <hr class="my-5 opacity-10">
    @endif

    {{-- SECTION 2: Catalog --}}
    <div class="mb-4 animate-slide-up delay-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-800 text-dark mb-0">
                @if(request('search'))
                    Search results for "{{ request('search') }}"
                @elseif(request('category'))
                    All {{ request('category') }} {{ $type === 'course' ? 'Courses' : 'Lessons' }}
                @else
                    All Available {{ $type === 'course' ? 'Courses' : 'Lessons' }}
                @endif
            </h4>
        </div>
        
        <div class="row g-4 mb-4">
            @forelse($items as $item)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    @if($type === 'course')
                        <div class="premium-card">
                            <button class="premium-bookmark-btn {{ Auth::user()->bookmarkedContents()->where('content_id', $item->id)->exists() ? 'active' : '' }}" 
                                    onclick="event.preventDefault(); toggleBookmark({{ $item->id }}, this)" 
                                    title="Bookmark">
                                <i class="bi {{ Auth::user()->bookmarkedContents()->where('content_id', $item->id)->exists() ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                            </button>
                            <a href="{{ route('course.view', $item) }}" style="text-decoration:none;color:inherit;display:flex;flex-direction:column;height:100%">
                                <div class="premium-thumb">
                                    <img src="{{ $item->thumbnail }}" alt="{{ $item->title }}">
                                    <div class="premium-card-overlay">
                                        <span class="premium-badge">{{ $item->category }}</span>
                                    </div>
                                </div>
                                <div class="premium-body">
                                    <h6 class="premium-title">{{ $item->title }}</h6>
                                    <p class="premium-desc">{{ $item->description }}</p>
                                    <div class="premium-footer">
                                        <span><i class="bi bi-layers-half me-1"></i> {{ $item->contents_count ?? $item->contents->count() }} Lessons</span>
                                        <span class="watch-cta-btn">VIEW CURRICULUM <i class="bi bi-arrow-right"></i></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @else
                        <div class="premium-card">
                            <button class="premium-bookmark-btn {{ Auth::user()->bookmarkedContents()->where('content_id', $item->id)->exists() ? 'active' : '' }}" 
                                    onclick="event.preventDefault(); toggleBookmark({{ $item->id }}, this)" 
                                    title="Bookmark">
                                <i class="bi {{ Auth::user()->bookmarkedContents()->where('content_id', $item->id)->exists() ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                            </button>
                            <a href="{{ route('learn.watch', $item) }}" style="text-decoration:none;color:inherit;display:flex;flex-direction:column;height:100%">
                                <div class="premium-thumb">
                                    <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}">
                                    <div class="premium-card-overlay">
                                        <span class="premium-badge">{{ $item->category }}</span>
                                        @if($item->connected_tools && is_array($item->connected_tools))
                                            @foreach($item->connected_tools as $tool)
                                                <span class="tool-connection-badge"><i class="bi bi-cpu"></i> {{ ucfirst($tool) }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="premium-body">
                                    <h6 class="premium-title">{{ $item->title }}</h6>
                                    <p class="premium-desc">{{ $item->description }}</p>
                                    <div class="premium-footer">
                                        <span><i class="bi bi-award me-1"></i> {{ $item->skill_level }}</span>
                                        <span class="watch-cta-btn">WATCH NOW <i class="bi bi-arrow-right"></i></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-search text-muted fs-2"></i>
                    </div>
                    <h4 class="fw-800 text-dark">No lessons found matching your filters</h4>
                    <p class="text-muted">Try removing your search term or checking a different category.</p>
                    <a href="{{ route('learn.explore', ['type' => $type]) }}" class="btn btn-dark rounded-pill px-4 mt-2">Reset Filters</a>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Pagination Grid --}}
    <div class="pagination-wrapper">
        {{ $items->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.querySelector('.exploreRecomSwiper')) {
            new Swiper('.exploreRecomSwiper', {
                slidesPerView: 1,
                spaceBetween: 24,
                navigation: {
                    nextEl: '.swiper-next-explore-recom',
                    prevEl: '.swiper-prev-explore-recom',
                },
                breakpoints: {
                    640: { slidesPerView: 2, spaceBetween: 20 },
                    1024: { slidesPerView: 3, spaceBetween: 24 },
                    1200: { slidesPerView: 4, spaceBetween: 24 }
                }
            });
        }
    });

    function toggleBookmark(contentId, btn) {
        const icon = btn.querySelector('i');
        const url = `/bookmarks/${contentId}/toggle`;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'added') {
                btn.classList.add('active');
                icon.classList.replace('bi-bookmark', 'bi-bookmark-fill');
                showToast('Added to bookmarks', 'success');
            } else {
                btn.classList.remove('active');
                icon.classList.replace('bi-bookmark-fill', 'bi-bookmark');
                showToast('Removed from bookmarks', 'info');
            }
        })
        .catch(err => console.error('Bookmark toggle failed', err));
    }
</script>
@endsection
