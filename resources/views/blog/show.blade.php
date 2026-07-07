@extends('layouts.public')

@section('title', $seo['title'])
@section('meta_description', $seo['meta_description'])
@section('meta_keywords', $seo['meta_keywords'])
@section('canonical', $seo['canonical'])
@section('og_image', $seo['og_image'])

@section('styles')
<style>
    :root {
        --primary: #4f46e5;
        --primary-hover: #4338ca;
        --dark-bg: #0f172a;
        --card-border: rgba(79, 70, 229, 0.08);
        --bg-glass: rgba(255, 255, 255, 0.85);
    }

    /* Reading Progress Scrollbar */
    #reading-progress {
        position: fixed;
        top: 0;
        left: 0;
        height: 4px;
        background: linear-gradient(135deg, var(--primary), #8b5cf6);
        z-index: 9999;
        width: 0%;
        transition: width 0.1s ease-out;
    }

    .article-wrap {
        padding: 80px 0;
        background: #ffffff;
    }

    .article-hero {
        background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.04), transparent 60%), #ffffff;
        padding-top: 140px;
        padding-bottom: 60px;
        border-bottom: 1px solid #f1f5f9;
    }

    .meta-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(79, 70, 229, 0.05);
        color: var(--primary);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 11px;
        padding: 6px 14px;
        border-radius: 30px;
    }

    .article-title-large {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: clamp(30px, 4.5vw, 48px);
        line-height: 1.25;
        color: var(--dark-bg);
        margin: 20px 0;
        letter-spacing: -1.5px;
    }

    .article-image-box {
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid var(--card-border);
        box-shadow: 0 20px 48px rgba(0, 0, 0, 0.04);
        margin-bottom: 48px;
    }

    .article-body-content {
        font-family: 'Inter', sans-serif;
        font-size: 18px;
        line-height: 1.8;
        color: #334155;
    }

    .article-body-content h1, .article-body-content h2, .article-body-content h3 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--dark-bg);
        font-weight: 800;
        margin-top: 40px;
        margin-bottom: 20px;
        letter-spacing: -0.5px;
    }

    .article-body-content h2 { font-size: 28px; }
    .article-body-content p { margin-bottom: 24px; }
    
    .article-body-content blockquote {
        background: #f8fafc;
        border-left: 4px solid var(--primary);
        padding: 24px 30px;
        border-radius: 0 16px 16px 0;
        margin: 32px 0;
        font-style: italic;
        color: #475569;
        font-size: 19px;
        line-height: 1.6;
    }

    .article-body-content pre {
        background: #0f172a;
        color: #f8fafc;
        padding: 20px;
        border-radius: 12px;
        overflow-x: auto;
        font-size: 15px;
        margin: 30px 0;
    }

    .share-pill {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
        cursor: pointer;
    }

    .share-pill:hover {
        background: var(--primary);
        color: #ffffff;
        border-color: var(--primary);
        transform: translateY(-2px);
    }

    /* Small author profile block */
    .author-bio-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        margin-top: 50px;
    }

    .sticky-sidebar {
        position: sticky;
        top: 120px;
    }

    .related-card-mini {
        background: #ffffff;
        border: 1px solid var(--card-border);
        border-radius: 14px;
        padding: 16px;
        transition: all 0.2s;
        text-decoration: none;
        display: block;
        margin-bottom: 16px;
    }

    .related-card-mini:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.02);
        border-color: rgba(99,102,241,0.15);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .article-hero { padding-top: 100px; padding-bottom: 40px; }
        .article-body-content { font-size: 17px; }
        .article-wrap { padding: 48px 0; }
        .sticky-sidebar { margin-top: 40px !important; }
        .sticky-sidebar .card { padding: 1.25rem !important; }
    }

    @media (max-width: 768px) {
        .article-hero { padding-top: 80px; padding-bottom: 32px; }
        .article-title-large { font-size: 26px; }
        .article-hero .meta-badge { font-size: 10px; padding: 4px 12px; }

        .article-image-box { margin-bottom: 28px; border-radius: 16px; }
        .article-image-box img { max-height: 280px; }

        .article-body-content { font-size: 16px; line-height: 1.7; }
        .article-body-content h2 { font-size: 22px; }
        .article-body-content h3 { font-size: 18px; }
        .article-body-content blockquote { padding: 16px 20px; font-size: 16px; margin: 24px 0; }
        .article-body-content pre { padding: 14px; font-size: 13px; }

        .article-wrap { padding: 32px 0; }

        .author-bio-card { flex-direction: column !important; gap: 12px !important; padding: 16px; }
        .author-bio-card .d-none { display: flex !important; width: 48px !important; height: 48px !important; font-size: 18px !important; }
        .author-bio-card h5 { font-size: 15px; }
        .author-bio-card p { font-size: 13px; }

        .share-pill { width: 40px; height: 40px; }
        .related-card-mini { padding: 12px; }
        .related-card-mini h6 { font-size: 13px; }
    }

    @media (max-width: 480px) {
        .article-hero { padding-top: 60px; padding-bottom: 24px; }
        .article-title-large { font-size: 22px; }
        .article-hero .d-flex.flex-wrap { flex-direction: column; align-items: flex-start !important; }

        .article-image-box { border-radius: 12px; }
        .article-image-box img { max-height: 200px; }
        .article-image-box .bg-primary { height: 200px !important; }
        .article-image-box .bg-primary i { font-size: 64px; }

        .article-body-content { font-size: 15px; }
        .article-body-content h2 { font-size: 20px; margin-top: 28px; }
        .article-body-content blockquote { padding: 12px 16px; font-size: 15px; }
        .article-body-content pre { padding: 12px; font-size: 12px; border-radius: 8px; }

        .author-bio-card { padding: 12px; border-radius: 12px; }

        .sticky-sidebar { margin-top: 32px !important; }
        .sticky-sidebar .card { padding: 1rem !important; border-radius: 12px !important; }
        .sticky-sidebar h5 { font-size: 14px; }
    }
</style>

{{-- SEO Schema JSON-LD --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "NewsArticle",
  "headline": "{{ $blog->title }}",
  "image": [
    "{{ $blog->cover_image ? asset($blog->cover_image) : asset('images/default-blog.jpg') }}"
  ],
  "datePublished": "{{ $blog->published_at ? $blog->published_at->toIso8601String() : $blog->created_at->toIso8601String() }}",
  "dateModified": "{{ $blog->updated_at->toIso8601String() }}",
  "author": [{
      "@@type": "Person",
      "name": "{{ $blog->author_name }}",
      "jobTitle": "AI Mentor & Instructional Designer"
    }]
}
</script>
@endsection

@section('content')
<div id="reading-progress"></div>

<article class="article-hero">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="meta-badge">{{ $blog->category ?? 'General' }}</span>
                    <span class="text-muted small">&bull;</span>
                    <span class="text-muted fw-bold small"><i class="bi bi-clock me-1"></i>{{ $blog->read_time_minutes }} min read</span>
                </div>
                <h1 class="article-title-large">{{ $blog->title }}</h1>
                
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mt-4">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--primary); color: #fff; font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                            {{ strtoupper(substr($blog->author_name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">{{ $blog->author_name }}</h6>
                            <span class="text-muted small">Published {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<div class="article-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="article-image-box">
                    @if($blog->cover_image)
                        <img src="{{ $blog->cover_image }}" alt="{{ $blog->title }}" class="w-100" style="max-height: 480px; object-fit: cover;">
                    @else
                        <div class="w-100 bg-primary bg-opacity-5 d-flex align-items-center justify-content-center" style="height: 320px;">
                            <i class="bi bi-file-earmark-richtext-fill text-primary text-opacity-30" style="font-size: 120px;"></i>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="article-body-content">
                    {!! $blog->content !!}
                </div>

                @if($blog->tags)
                    <div class="mt-5">
                        <h6 class="fw-bold text-muted small uppercase mb-3">Topic Tags</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(explode(',', $blog->tags) as $tag)
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-3 fw-bold small">#{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="author-bio-card d-flex gap-4 align-items-start mt-5">
                    <div class="d-none d-sm-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 60px; border-radius: 50%; background: var(--primary); color: #fff; font-weight: 800; font-size: 22px;">
                        {{ strtoupper(substr($blog->author_name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Written by {{ $blog->author_name }}</h5>
                        <p class="text-muted small mb-0">Instructional designer and learning strategy lead at Daleel AI. Focuses on employee AI training, smart automation systems, and high-frequency workplace tasks.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 offset-lg-1">
                <div class="sticky-sidebar mt-5 mt-lg-0">
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" style="background: #fafbfc;">
                        <h5 class="fw-bold text-dark mb-3">Share Post</h5>
                        <div class="d-flex gap-2">
                            <a class="share-pill" title="Copy Article URL" onclick="copyLink()">
                                <i class="bi bi-link-45deg fs-5"></i>
                            </a>
                            <a class="share-pill" target="_blank" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" title="Share on LinkedIn">
                                <i class="bi bi-linkedin"></i>
                            </a>
                            <a class="share-pill" target="_blank" href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(request()->url()) }}" title="Share on X">
                                <i class="bi bi-twitter-x"></i>
                            </a>
                        </div>
                    </div>

                    @if($related->count() > 0)
                        <div>
                            <h5 class="fw-bold text-dark mb-3">Related Publications</h5>
                            @foreach($related as $rel)
                                <a href="{{ route('public.blog.show', $rel->slug) }}" class="related-card-mini">
                                    <span class="text-primary fw-bold text-uppercase" style="font-size: 10px;">{{ $rel->category }}</span>
                                    <h6 class="fw-bold text-dark mb-0 mt-1" style="font-size: 14px; line-height: 1.4;">{{ $rel->title }}</h6>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Reading Progress Indicator
    window.addEventListener('scroll', () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        document.getElementById('reading-progress').style.width = scrolled + '%';
    });

    // Copy Link Helper
    function copyLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Article URL copied to clipboard!');
        }).catch(err => {
            alert('Error copying link to clipboard: ' + err);
        });
    }
</script>
@endsection
