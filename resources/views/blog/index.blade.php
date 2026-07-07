@extends('layouts.public')

@section('title', $seo['title'])
@section('meta_description', $seo['meta_description'])
@section('meta_keywords', $seo['meta_keywords'])
@section('canonical', $seo['canonical'])

@section('styles')
<style>
    :root {
        --primary: #4f46e5;
        --primary-hover: #4338ca;
        --primary-light: #eef2ff;
        --dark-bg: #0f172a;
        --card-border: rgba(79, 70, 229, 0.08);
        --glow-shadow: 0 10px 30px -10px rgba(79, 70, 229, 0.15);
        --text-primary: #0f172a;
        --text-secondary: #475569;
        --text-muted: #94a3b8;
        --border-light: #e2e8f0;
        --bg-light: #f8fafc;
    }

    /* Hero Section */
    .blog-hero {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, #eef2ff 100%);
        padding: 100px 0 80px;
        border-bottom: 1px solid var(--border-light);
        position: relative;
        overflow: hidden;
    }

    .blog-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(79, 70, 229, 0.04) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--primary-light);
        color: var(--primary);
        padding: 8px 20px;
        border-radius: 100px;
        font-weight: 700;
        font-size: 13px;
        letter-spacing: 0.5px;
        border: 1px solid rgba(79, 70, 229, 0.1);
    }

    .hero-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: clamp(32px, 5vw, 56px);
        letter-spacing: -1.5px;
        color: var(--text-primary);
        line-height: 1.1;
    }

    /* Category Pills */
    .category-pill {
        display: inline-block;
        padding: 8px 18px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 13px;
        color: var(--text-secondary);
        background: var(--bg-light);
        border: 1px solid var(--border-light);
        text-decoration: none;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
    }

    .category-pill:hover {
        background: var(--primary-light);
        color: var(--primary);
        border-color: rgba(79, 70, 229, 0.2);
        transform: translateY(-1px);
    }

    .category-pill.active {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        transform: translateY(-1px);
    }

    /* Search Card */
    .search-card {
        background: #ffffff;
        border: 1px solid rgba(79, 70, 229, 0.08);
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
    }

    .search-input {
        border: 1.5px solid var(--border-light);
        border-radius: 14px;
        padding: 14px 18px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        outline: none;
    }

    .search-btn {
        background: var(--primary);
        border: none;
        border-radius: 14px;
        padding: 14px 24px;
        font-weight: 700;
        transition: all 0.3s;
    }

    .search-btn:hover {
        background: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    /* Featured Post */
    .featured-card {
        background: #ffffff;
        border: 1px solid var(--card-border);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 60px;
        position: relative;
    }

    .featured-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 48px rgba(79, 70, 229, 0.1);
        border-color: rgba(99, 102, 241, 0.2);
    }

    .featured-img-container {
        aspect-ratio: 16/10;
        background: #f1f5f9;
        overflow: hidden;
        height: 100%;
        min-height: 320px;
        position: relative;
    }

    .featured-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s ease;
    }

    .featured-card:hover .featured-img-container img {
        transform: scale(1.05);
    }

    .featured-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        color: var(--primary);
        font-weight: 800;
        font-size: 11px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 6px 16px;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .featured-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    /* Standard Blog Cards */
    .article-card {
        background: #ffffff;
        border: 1px solid var(--card-border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
    }

    .article-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(79, 70, 229, 0.1);
        border-color: rgba(99, 102, 241, 0.2);
    }

    .card-img-container {
        aspect-ratio: 16/9;
        background: linear-gradient(135deg, #e2e8f0 0%, #f1f5f9 100%);
        overflow: hidden;
        position: relative;
    }

    .card-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .article-card:hover .card-img-container img {
        transform: scale(1.08);
    }

    .card-meta-category {
        position: absolute;
        top: 16px;
        left: 16px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        color: var(--primary);
        font-weight: 700;
        font-size: 11px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        z-index: 2;
    }

    .card-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .article-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 18px;
        line-height: 1.45;
        color: var(--text-primary);
        margin-bottom: 12px;
        transition: color 0.2s;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .article-card:hover .article-title {
        color: var(--primary);
    }

    .article-excerpt {
        color: var(--text-secondary);
        font-size: 14px;
        line-height: 1.6;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .author-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);
        color: #ffffff;
        font-weight: 800;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
        flex-shrink: 0;
    }

    .read-more-link {
        color: var(--primary);
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .read-more-link:hover {
        gap: 8px;
        color: var(--primary-hover);
    }

    .read-more-link i {
        transition: transform 0.2s;
    }

    .read-more-link:hover i {
        transform: translateX(3px);
    }

    /* Empty State */
    .empty-state {
        background: #ffffff;
        border-radius: 24px;
        border: 2px dashed var(--border-light);
        padding: 60px 40px;
    }

    /* Newsletter Section */
    .newsletter-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #312e81 100%);
        border-radius: 24px;
        padding: 56px 48px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 24px 48px rgba(15, 23, 42, 0.2);
        margin-top: 80px;
    }

    .newsletter-banner::before {
        content: '';
        position: absolute;
        top: -200px;
        right: -200px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .newsletter-banner::after {
        content: '';
        position: absolute;
        bottom: -150px;
        left: -150px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .newsletter-input {
        background: rgba(255, 255, 255, 0.95);
        border: 2px solid transparent;
        border-radius: 16px;
        padding: 16px 20px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s;
        height: 58px;
    }

    .newsletter-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.15);
        outline: none;
    }

    .newsletter-btn {
        background: var(--primary);
        border: none;
        border-radius: 16px;
        padding: 16px 32px;
        font-weight: 700;
        font-size: 15px;
        height: 58px;
        transition: all 0.3s;
        white-space: nowrap;
    }

    .newsletter-btn:hover {
        background: #6366f1;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4);
    }

    /* Pagination Styling */
    .pagination {
        gap: 8px;
    }

    .page-link {
        border-radius: 12px !important;
        padding: 10px 16px;
        font-weight: 600;
        font-size: 14px;
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
        transition: all 0.2s;
    }

    .page-link:hover {
        background: var(--primary-light);
        color: var(--primary);
        border-color: var(--primary);
    }

    .page-item.active .page-link {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .blog-hero .row { gap: 2rem !important; }
        .search-card { padding: 20px; }
        .featured-card .p-md-5 { padding: 1.5rem !important; }
        .featured-card h2 { font-size: 24px; }
        .newsletter-banner h3 { font-size: 26px; }
    }

    @media (max-width: 768px) {
        .blog-hero {
            padding: 50px 0 30px;
        }
        .hero-title { font-size: 28px; }
        .blog-hero .lead { font-size: 15px !important; }
        .category-filters { overflow-x: auto; flex-wrap: nowrap; -webkit-overflow-scrolling: touch; scrollbar-width: none; padding-bottom: 4px; }
        .category-filters::-webkit-scrollbar { display: none; }
        .category-pill { font-size: 12px; padding: 6px 14px; flex-shrink: 0; }

        .search-card { padding: 16px; }
        .search-card .d-flex { flex-direction: column; }
        .search-card .d-flex .btn { width: 100%; }

        .featured-card { margin-bottom: 36px; }
        .featured-img-container { min-height: 180px; }
        .featured-badge { top: 12px; left: 12px; font-size: 10px; padding: 4px 12px; }
        .featured-card .p-md-5 { padding: 1.25rem !important; }
        .featured-card h2 { font-size: 20px; }
        .featured-card .btn { width: 100%; text-align: center; justify-content: center; }

        .article-card { border-radius: 16px; }
        .card-body { padding: 16px; }
        .article-title { font-size: 16px; }
        .article-excerpt { font-size: 13px; -webkit-line-clamp: 2; }

        .section-header { flex-direction: column; align-items: flex-start !important; gap: 8px; }
        .section-header h3 { font-size: 18px; }

        .newsletter-banner { padding: 28px 20px; margin-top: 48px; border-radius: 16px; }
        .newsletter-banner h3 { font-size: 22px; }

        .empty-state { padding: 36px 20px; }
        .pagination .page-link { padding: 8px 12px; font-size: 13px; }
    }

    @media (max-width: 480px) {
        .blog-hero { padding: 36px 0 24px; }
        .hero-title { font-size: 24px; }
        .hero-badge { font-size: 11px; padding: 6px 14px; }
        .blog-hero .lead { font-size: 14px !important; }

        .featured-img-container { min-height: 140px; }
        .featured-card h2 { font-size: 18px; }
        .featured-badge { font-size: 9px; padding: 3px 10px; }

        .article-card { border-radius: 12px; }
        .card-img-container { min-height: 100px; }
        .card-body { padding: 14px; }
        .article-title { font-size: 15px; }
        .read-more-link { font-size: 13px; }

        .newsletter-banner { padding: 24px 16px; }
        .newsletter-banner h3 { font-size: 18px; }
        .newsletter-banner p { font-size: 14px; }

        .pagination { gap: 4px; }
        .pagination .page-link { padding: 6px 10px; font-size: 12px; border-radius: 8px !important; }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="blog-hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="hero-badge mb-3">
                    <i class="bi bi-journal-text"></i> RESOURCE CENTER
                </span>
                <h1 class="hero-title mb-4">
                    Guides, Insights &<br>Training Resources
                </h1>
                <p class="lead text-secondary mb-4" style="max-width: 560px; font-size: 18px; line-height: 1.6;">
                    Explore verified tool workflows, prompt blueprints, AI readiness strategies, and operational frameworks created by our instructional design experts.
                </p>
                
                <!-- Category Filters -->
                <div class="d-flex flex-wrap gap-2 mb-4 category-filters">
                    <a href="{{ route('public.blog') }}" class="category-pill {{ !request('category') ? 'active' : '' }}">
                        <i class="bi bi-grid-fill me-1"></i> All Articles
                    </a>
                    @foreach($allCategories as $cat)
                        <a href="{{ route('public.blog', ['category' => $cat]) }}" 
                           class="category-pill {{ request('category') === $cat ? 'active' : '' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
                
                <!-- Article Count -->
                <p class="text-muted small mb-0">
                    <i class="bi bi-dot"></i>
                    <strong>{{ $blogs->total() }}</strong> article{{ $blogs->total() !== 1 ? 's' : '' }} available
                    @if(request('search'))
                        for "<strong>{{ request('search') }}</strong>"
                    @endif
                    @if(request('category'))
                        in "<strong>{{ request('category') }}</strong>"
                    @endif
                </p>
            </div>
            
            <!-- Search Widget -->
            <div class="col-lg-5">
                <div class="search-card">
                    <h5 class="fw-bold mb-3 text-dark">
                        <i class="bi bi-search text-primary me-2"></i>Search Resources
                    </h5>
                    <form action="{{ route('public.blog') }}" method="GET">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <div class="d-flex gap-2">
                            <input type="text" 
                                   name="search" 
                                   class="search-input form-control" 
                                   placeholder="Search keywords..." 
                                   value="{{ request('search') }}"
                                   aria-label="Search articles">
                            <button class="search-btn btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        @if(request('search') || request('category'))
                            <a href="{{ route('public.blog') }}" 
                               class="d-inline-flex align-items-center gap-1 text-danger fw-semibold small text-decoration-none mt-3">
                                <i class="bi bi-x-circle"></i> Clear all filters
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Listing -->
<section class="py-5" style="background: #fafbfc;">
    <div class="container">
        
        {{-- ====== FEATURED POST ====== --}}
        @if($featured && !request('search') && !request('category'))
            <div class="featured-card">
                <div class="row g-0 align-items-stretch">
                    <div class="col-lg-6">
                        <div class="featured-img-container">
                            <span class="featured-badge">
                                <i class="bi bi-star-fill" style="font-size: 10px;"></i> Featured Post
                            </span>
                            @if($featured->cover_image)
                                <img src="{{ $featured->cover_image }}" 
                                     alt="{{ $featured->title }}"
                                     loading="eager">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-opacity-5">
                                    <div class="text-center">
                                        <i class="bi bi-file-earmark-richtext text-primary opacity-50" style="font-size: 80px;"></i>
                                        <p class="text-primary fw-bold mt-2">Featured Article</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex align-items-center p-4 p-md-5">
                        <div>
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-800 rounded-pill px-3 py-2 mb-3 text-uppercase small">
                                {{ $featured->category ?? 'General' }}
                            </span>
                            <h2 class="fw-800 text-dark mb-3" style="font-size: 28px; line-height: 1.35; font-family: 'Plus Jakarta Sans', sans-serif;">
                                <a href="{{ route('public.blog.show', $featured->slug) }}" 
                                   class="text-dark text-decoration-none hover-primary">
                                    {{ $featured->title }}
                                </a>
                            </h2>
                            <p class="text-muted mb-4" style="line-height: 1.6;">
                                {{ $featured->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($featured->content), 180) }}
                            </p>
                            
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="author-avatar">
                                        {{ strtoupper(substr($featured->author_name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold small text-dark">{{ $featured->author_name }}</h6>
                                        <span class="text-muted" style="font-size: 12px;">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ $featured->published_at ? $featured->published_at->format('M d, Y') : $featured->created_at->format('M d, Y') }}
                                            · {{ $featured->read_time_minutes }} min read
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('public.blog.show', $featured->slug) }}" 
                                   class="btn btn-primary rounded-pill px-4 fw-bold">
                                    Read Article <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ====== Section Header ====== --}}
        <div class="d-flex align-items-center justify-content-between mb-4 section-header">
            <h3 class="fw-800 text-dark mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                @if(request('search'))
                    <i class="bi bi-search text-primary me-2"></i>
                    Results for "{{ request('search') }}"
                @elseif(request('category'))
                    <i class="bi bi-folder2-open text-primary me-2"></i>
                    {{ request('category') }}
                @else
                    <i class="bi bi-clock-history text-primary me-2"></i>
                    Latest Publications
                @endif
            </h3>
            
            @if(!request('search') && !request('category'))
                <a href="{{ route('public.blog') }}" class="text-primary fw-bold text-decoration-none small">
                    View All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            @endif
        </div>
        
        {{-- ====== Article Grid ====== --}}
        <div class="row g-4">
            @forelse($blogs as $blog)
                <div class="col-xl-4 col-md-6">
                    <article class="article-card">
                        <div class="card-img-container">
                            <span class="card-meta-category">{{ $blog->category ?? 'General' }}</span>
                            @if($blog->cover_image)
                                <img src="{{ $blog->cover_image }}" 
                                     alt="{{ $blog->title }}"
                                     loading="lazy">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary bg-opacity-5">
                                    <div class="text-center">
                                        <i class="bi bi-file-earmark-text text-primary opacity-50" style="font-size: 48px;"></i>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">
                                    <i class="bi bi-clock me-1"></i>{{ $blog->read_time_minutes }} min read
                                </span>
                                <span style="font-size: 12px; color: var(--text-muted);">·</span>
                                <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">
                                    {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}
                                </span>
                            </div>
                            
                            <h4 class="article-title">
                                <a href="{{ route('public.blog.show', $blog->slug) }}" 
                                   class="text-dark text-decoration-none">
                                    {{ \Illuminate\Support\Str::limit($blog->title, 70) }}
                                </a>
                            </h4>
                            
                            <p class="article-excerpt mb-4">
                                {{ $blog->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($blog->content), 120) }}
                            </p>

                            <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-auto">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="author-avatar" style="width: 30px; height: 30px; font-size: 11px;">
                                        {{ strtoupper(substr($blog->author_name ?? 'A', 0, 1)) }}
                                    </div>
                                    <span class="fw-semibold small text-dark">{{ $blog->author_name }}</span>
                                </div>
                                <a href="{{ route('public.blog.show', $blog->slug) }}" 
                                   class="read-more-link">
                                    Read <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state text-center">
                        <i class="bi bi-journal-x fs-1 text-muted opacity-50 mb-3 d-block"></i>
                        <h4 class="fw-bold text-dark">No articles found</h4>
                        <p class="text-muted mb-4">
                            @if(request('search'))
                                No results matching "<strong>{{ request('search') }}</strong>". Try different keywords.
                            @else
                                No articles in this category yet. Check back soon!
                            @endif
                        </p>
                        <a href="{{ route('public.blog') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="bi bi-arrow-left me-1"></i> View All Articles
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- ====== Pagination ====== --}}
        @if($blogs->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $blogs->onEachSide(2)->links() }}
            </div>
        @endif

        {{-- ====== Newsletter CTA ====== --}}
        <div class="newsletter-banner">
            <div class="row align-items-center g-4">
                <div class="col-lg-6 position-relative" style="z-index: 1;">
                    <h3 class="fw-900 text-white mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 32px;">
                        <i class="bi bi-envelope-paper-heart me-2"></i>
                        Stay Ahead of the Curve
                    </h3>
                    <p class="text-white mb-0" style="opacity: 0.85; font-size: 16px; line-height: 1.6;">
                        Join <strong>15,000+ workplace AI innovators</strong> and get hands-on training playbooks, prompt setups, and extension guides delivered to your inbox every Friday.
                    </p>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@section('scripts')
<script>
    // Optional: Add smooth scroll behavior for category pills
    document.querySelectorAll('.category-pill').forEach(pill => {
        pill.addEventListener('click', function(e) {
            // Add a subtle loading effect
            this.style.opacity = '0.7';
            setTimeout(() => {
                this.style.opacity = '1';
            }, 300);
        });
    });
    
    // Add intersection observer for lazy loading animations
    if ('IntersectionObserver' in window) {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '50px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.article-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = `all 0.5s ease ${index * 0.05}s`;
            observer.observe(card);
        });
    }
</script>
@endsection