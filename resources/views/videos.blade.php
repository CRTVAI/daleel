@extends('layouts.public')

@section('title', 'Video Lessons | Daleel AI')

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
        padding: 4.5rem 2rem;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        margin-bottom: 2.5rem;
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
        cursor: pointer;
    }

    .topic-pill:hover, .topic-pill.active {
        background: var(--crtv-dark-bg);
        color: #ffffff;
        border-color: var(--crtv-dark-bg);
    }

    /* Premium Lesson Cards */
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
</style>
@endsection

@section('content')
<div class="explore-hero-premium">
    <div class="container text-center position-relative" style="z-index: 10;">
        <h1 class="explore-title display-5 mb-2 text-white">Master AI Workflows</h1>
        <p class="opacity-75 fs-6 fw-600 mb-4">Practical video lessons to help you save time and automate your work.</p>
        
        <form method="GET" action="{{ route('videos.public') }}" class="search-container-premium">
            <i class="bi bi-search search-icon-premium"></i>
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="search-input-premium" placeholder="Search prompts, tools, or roles..." autocomplete="off">
            @if(request('search'))
                <a href="{{ route('videos.public') }}" class="position-absolute end-0 top-50 translate-middle-y me-3 text-muted">
                    <i class="bi bi-x-circle-fill"></i>
                </a>
            @endif
        </form>

        <div class="quick-tags">
            <div class="quick-tags-track">
                @foreach($connectedTools as $tool)
                <a href="{{ route('videos.public', ['search' => urlencode($tool->name)]) }}" class="quick-tag-btn">{{ $tool->name }}</a>
                @endforeach
                @foreach($connectedTools as $tool)
                <a href="{{ route('videos.public', ['search' => urlencode($tool->name)]) }}" class="quick-tag-btn">{{ $tool->name }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    {{-- Category Pills --}}
    <div class="topic-slider">
        <a href="{{ route('videos.public', ['search' => request('search')]) }}" 
           class="topic-pill {{ !request('category') ? 'active' : '' }}">All Subjects</a>
        @foreach($categories as $cat)
            <a href="{{ route('videos.public', ['category_id' => $cat->id, 'search' => request('search')]) }}" 
               class="topic-pill {{ request('category_id') == $cat->id ? 'active' : '' }}">{{ $cat->name }}</a>
        @endforeach
    </div>

    {{-- Video Grid --}}
    <div class="row g-4 mb-5">
        @forelse($items as $item)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <a href="{{ route('learn.watch', $item->id) }}" class="premium-card">
                    <div class="premium-thumb">
                        <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}">
                        <div class="premium-card-overlay">
                            <span class="premium-badge">{{ $item->category }}</span>
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
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-search display-1 text-muted opacity-25 mb-4"></i>
                <h3>No lessons found matching your filters</h3>
                <p class="text-muted">Try removing your search term or checking a different category.</p>
                <a href="{{ route('videos.public') }}" class="btn btn-dark rounded-pill px-4 mt-2">Reset Filters</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="pagination-wrapper">
        {{ $items->links() }}
    </div>

    {{-- Final CTA --}}
    <div class="text-center py-5">
        <div class="card bg-dark text-white p-5 rounded-5" style="background: linear-gradient(135deg, #0F172A, #1E293B); border: none;">
            <h2 class="fw-bold mb-3">Unlock the Full Learning Experience</h2>
            <p class="opacity-75 mb-4">Create a free account to track your progress, save bookmarks, and get personalized recommendations.</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">Join Daleel AI Today</a>
        </div>
    </div>
</div>
@endsection
