@extends('layouts.user')

@section('title', 'Library — Explore — Daleel AI')

@section('styles')
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
        align-items: center;
        margin-top: 1.2rem;
        z-index: 10;
        position: relative;
        overflow: hidden;
    }

    .quick-tags-track {
        display: flex;
        gap: 0.5rem;
        animation: qt-scroll 40s linear infinite;
        width: max-content;
    }

    .quick-tags-track:hover {
        animation-play-state: paused;
    }

    @keyframes qt-scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
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
        white-space: nowrap;
        transition: all 0.2s;
        flex-shrink: 0;
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

    /* View More Button for Mobile */
    .view-more-btn {
        display: none;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        margin-top: 16px;
        padding: 14px;
        background: #f8fafc;
        border: 1.5px dashed #cbd5e1;
        border-radius: 16px;
        color: #4338ca;
        font-weight: 800;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: -0.01em;
    }

    .view-more-btn:hover {
        background: #eef2ff;
        border-color: #4338ca;
        box-shadow: 0 4px 12px rgba(67, 56, 202, 0.08);
    }

    .view-more-btn i {
        font-size: 16px;
    }

    /* ==================== MOBILE OPTIMIZATIONS ==================== */
    @media (max-width: 768px) {
        .main-container-neo {
            width: 100% !important;
            padding: 10px !important;
        }

        /* Reduce hero padding */
        .explore-hero-premium {
            padding: 1.25rem 0.9rem !important;
            border-radius: 14px !important;
            margin-bottom: 1rem !important;
            background: #111827 !important;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.14) !important;
        }

        .explore-hero-premium::before,
        .explore-hero-premium::after {
            display: none !important;
        }

        /* Hero title adjustments */
        .explore-title {
            font-size: 1.35rem !important;
            margin-bottom: 0.35rem !important;
            letter-spacing: 0 !important;
        }

        .explore-hero-premium p {
            font-size: 0.78rem !important;
            margin-bottom: 0.75rem !important;
        }

        /* Search input compact */
        .search-input-premium {
            min-height: 40px !important;
            padding: 0.65rem 2.35rem 0.65rem 2.3rem !important;
            font-size: 0.78rem !important;
            border-radius: 10px !important;
            box-shadow: none !important;
        }

        .search-icon-premium {
            left: 0.85rem !important;
            font-size: 1.1rem !important;
        }

        /* Quick tags */
        .quick-tags {
            margin-top: 0.65rem !important;
            display: none !important;
        }

        .quick-tag-btn {
            padding: 0.25rem 0.6rem !important;
            font-size: 0.7rem !important;
        }

        /* Type switcher */
        .type-switcher {
            margin-bottom: 0.85rem !important;
            padding: 0.3rem !important;
            border-radius: 10px !important;
            width: 100% !important;
        }

        .type-switch-btn {
            min-height: 34px !important;
            padding: 0.45rem 0.5rem !important;
            font-size: 0.72rem !important;
            flex: 1 !important;
            text-align: center !important;
            border-radius: 8px !important;
            white-space: nowrap !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        /* Category pills */
        .topic-slider {
            gap: 0.45rem !important;
            margin: 0 -10px 0.95rem !important;
            padding: 0 10px 0.4rem !important;
            overflow-x: auto !important;
            scrollbar-width: none !important;
            -ms-overflow-style: none !important;
            scroll-padding-left: 10px;
            -webkit-overflow-scrolling: touch;
        }

        .topic-slider::-webkit-scrollbar {
            display: none !important;
            height: 0 !important;
        }

        .topic-pill {
            min-height: 32px !important;
            padding: 0.45rem 0.75rem !important;
            font-size: 0.68rem !important;
            border-radius: 9px !important;
            flex: 0 0 auto;
            box-shadow: none !important;
        }

        /* Card grid */
        .row.g-4 {
            --bs-gutter-y: 0.75rem !important;
            --bs-gutter-x: 0.75rem !important;
        }

        .col-xl-3.col-lg-4.col-md-6 {
            padding-left: 0.375rem !important;
            padding-right: 0.375rem !important;
        }

        /* Premium card adjustments */
        .premium-card {
            border-radius: 14px !important;
        }

        .premium-thumb {
            height: 130px !important;
        }

        .premium-body {
            padding: 0.9rem !important;
        }

        .premium-title {
            font-size: 0.8rem !important;
            height: 34px !important;
            margin-bottom: 0.3rem !important;
        }

        .premium-desc {
            font-size: 0.7rem !important;
            height: 30px !important;
            margin-bottom: 0.7rem !important;
        }

        .premium-footer {
            padding-top: 0.7rem !important;
            font-size: 0.65rem !important;
        }

        .watch-cta-btn {
            min-height: 30px !important;
            font-size: 0.64rem !important;
            line-height: 1 !important;
            padding: 0.55rem 0.65rem !important;
            border-radius: 8px !important;
            white-space: nowrap !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        /* Badge adjustments */
        .premium-badge {
            font-size: 0.6rem !important;
            padding: 0.2rem 0.45rem !important;
        }

        .tool-connection-badge {
            font-size: 0.6rem !important;
            padding: 0.2rem 0.45rem !important;
        }

        .premium-bookmark-btn {
            width: 28px !important;
            height: 28px !important;
            border-radius: 7px !important;
            top: 0.5rem !important;
            right: 0.5rem !important;
        }

        .premium-card-overlay {
            top: 0.5rem !important;
            left: 0.5rem !important;
            gap: 0.25rem !important;
        }

        /* Section header */
        .mb-4 h4.fw-800 {
            font-size: 1.1rem !important;
            margin-bottom: 0 !important;
        }

        .d-flex.justify-content-between.align-items-center.mb-4 {
            margin-bottom: 1rem !important;
        }

        /* Pagination */
        .pagination-wrapper {
            margin-top: 2rem !important;
        }

        .pagination .page-item .page-link {
            padding: 0.45rem 0.75rem !important;
            font-size: 0.72rem !important;
            border-radius: 8px !important;
        }

        /* Items count */
        .text-muted.small.fw-800 {
            font-size: 0.7rem !important;
        }

        /* View More Button - Show on mobile */
        .view-more-btn {
            display: flex !important;
            min-height: 38px !important;
            margin-top: 12px !important;
            padding: 0 12px !important;
            border-radius: 10px !important;
            font-size: 0.75rem !important;
            border-style: solid !important;
            white-space: nowrap !important;
        }

        /* Hide items beyond 6th on mobile when collapsed */
        .items-grid .col-xl-3.col-lg-4.col-md-6:nth-child(n+7) {
            display: none;
        }

        .items-grid .col-xl-3.col-lg-4.col-md-6.expanded {
            display: block !important;
        }
    }

    @media (max-width: 380px) {
        .explore-hero-premium {
            padding: 1.5rem 0.75rem !important;
        }

        .explore-title {
            font-size: 1.3rem !important;
        }

        .premium-thumb {
            height: 110px !important;
        }

        .premium-title {
            font-size: 0.72rem !important;
            height: 30px !important;
        }

        .type-switch-btn {
            padding: 0.45rem 0.5rem !important;
            font-size: 0.7rem !important;
        }
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
            <input type="hidden" name="category_id" value="{{ request('category_id') }}">
            
            <i class="bi bi-search search-icon-premium"></i>
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="search-input-premium" placeholder="Search by title, category, tag, or connected browser tool..." autocomplete="off">
            
            @if(request('search'))
                <a href="{{ route('learn.explore', ['type' => $type, 'category_id' => request('category_id')]) }}" 
                   class="position-absolute end-0 top-50 translate-middle-y me-3 text-muted">
                    <i class="bi bi-x-circle-fill"></i>
                </a>
            @endif
        </form>

        {{-- Tool quick filters --}}
        <div class="quick-tags animate-slide-up delay-1">
            <div class="quick-tags-track">
                @foreach($connectedTools as $tool)
                <a href="{{ route('learn.explore', ['type' => $type, 'search' => urlencode($tool->name)]) }}" class="quick-tag-btn">{{ $tool->name }}</a>
                @endforeach
                @foreach($connectedTools as $tool)
                <a href="{{ route('learn.explore', ['type' => $type, 'search' => urlencode($tool->name)]) }}" class="quick-tag-btn">{{ $tool->name }}</a>
                @endforeach
            </div>
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
           class="topic-pill {{ !request('category_id') ? 'active' : '' }}">All Subjects</a>
        @foreach($categories as $cat)
            <a href="{{ route('learn.explore', ['type' => $type, 'category_id' => $cat->id, 'search' => request('search')]) }}" 
               class="topic-pill {{ request('category_id') == $cat->id ? 'active' : '' }}">{{ $cat->name }}</a>
        @endforeach
    </div>

    {{-- SECTION: Catalog --}}
    <div class="mb-4 animate-slide-up delay-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-800 text-dark mb-0">
                @if(request('search'))
                    Search results for "{{ request('search') }}"
                @elseif(request('category_id'))
                    @php $currentCat = $categories->where('id', request('category_id'))->first(); @endphp
                    All {{ $currentCat->name ?? 'Category' }} {{ $type === 'course' ? 'Courses' : 'Lessons' }}
                @else
                    All Available {{ $type === 'course' ? 'Courses' : 'Lessons' }}
                @endif
            </h4>
        </div>
        
        <div class="row g-4 mb-4 items-grid">
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
                                    <img src="{{ $item->thumbnail ?? 'https://www.shutterstock.com/image-photo/online-learning-system-combining-digital-260nw-2700835217.jpg'}}" alt="{{ $item->title }}">
                                    <div class="premium-card-overlay">
                                        <span class="premium-badge">{{ $item->category_rel->name ?? $item->category }}</span>
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
                                        <span class="premium-badge">{{ $item->category_rel->name ?? $item->category }}</span>
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

        {{-- View More Button (Mobile Only) --}}
        <button class="view-more-btn" id="viewMoreBtn" onclick="toggleMoreItems()">
            <i class="bi bi-plus-circle"></i> View More Lessons
        </button>
    </div>

    {{-- Pagination Grid --}}
    <div class="pagination-wrapper">
        {{ $items->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
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

    // View More / Show Less functionality for mobile
    let isExpanded = false;

    function toggleMoreItems() {
        const itemsGrid = document.querySelector('.items-grid');
        const viewMoreBtn = document.getElementById('viewMoreBtn');
        
        if (!itemsGrid || !viewMoreBtn) return;
        
        const hiddenItems = itemsGrid.querySelectorAll('.col-xl-3.col-lg-4.col-md-6:nth-child(n+7)');
        
        isExpanded = !isExpanded;
        
        if (isExpanded) {
            hiddenItems.forEach(item => item.classList.add('expanded'));
            viewMoreBtn.innerHTML = '<i class="bi bi-dash-circle"></i> Show Less';
        } else {
            hiddenItems.forEach(item => item.classList.remove('expanded'));
            viewMoreBtn.innerHTML = '<i class="bi bi-plus-circle"></i> View More Lessons';
            
            // Scroll back to the grid
            itemsGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Hide view more button if there are 6 or fewer items
    document.addEventListener('DOMContentLoaded', function() {
        const itemsGrid = document.querySelector('.items-grid');
        const viewMoreBtn = document.getElementById('viewMoreBtn');
        
        if (itemsGrid && viewMoreBtn) {
            const totalItems = itemsGrid.querySelectorAll('.col-xl-3.col-lg-4.col-md-6').length;
            
            if (totalItems <= 6) {
                viewMoreBtn.style.display = 'none';
            }
        }
    });
</script>
@endsection
