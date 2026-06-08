@extends('layouts.user')

@section('title', 'My Bookmarks — Dallel AI')

@section('content')
<div class="bookmarks-container">
    <div class="max-w-narrow mx-auto">
        <!-- Simplified Header -->
        <div class="text-center mb-5">
            <h2 class="fw-800 display-6 mb-2" style="letter-spacing: -0.04em;">My Bookmarks</h2>
            <p class="text-muted fw-600">All your saved learning materials in one place.</p>
        </div>

        <!-- Single Search Bar -->
        <div class="search-wrap-simple mb-5">
            <form action="{{ route('bookmarks') }}" method="GET" class="position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-4 text-muted"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="form-control-simple" placeholder="Search through your bookmarks...">
                @if(request('search'))
                    <a href="{{ route('bookmarks') }}" class="position-absolute top-50 end-0 translate-middle-y me-4 text-muted">
                        <i class="bi bi-x-circle-fill"></i>
                    </a>
                @endif
            </form>
        </div>

        <!-- Bookmark List -->
        <div class="bookmark-grid">
            @forelse($bookmarks as $item)
                <div class="bookmark-card-simple" id="bookmark-{{ $item->id }}">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <a href="{{ route('learn.watch', $item) }}" class="card-thumb-link">
                                <img src="{{ $item->thumbnail_url }}" alt="{{ $item->title }}">
                                @if($item->duration_label)
                                    <span class="duration-tag">{{ $item->duration_label }}</span>
                                @endif
                                <div class="play-overlay-simple">
                                    <i class="bi bi-play-fill"></i>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body-simple">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge-simple">{{ strtoupper($item->category) }}</span>
                                    <button class="btn-remove-bookmark" onclick="toggleBookmark({{ $item->id }}, this)" title="Remove Bookmark">
                                        <i class="bi bi-bookmark-fill"></i>
                                    </button>
                                </div>
                                <h5 class="card-title-simple">
                                    <a href="{{ route('learn.watch', $item) }}">{{ $item->title }}</a>
                                </h5>
                                <p class="card-text-simple line-clamp-2">{{ $item->description }}</p>
                                <div class="card-footer-simple">
                                    <span><i class="bi bi-clock-history me-1"></i> Saved {{ $item->pivot->created_at->diffForHumans() }}</span>
                                    <span>{{ $item->skill_level }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="empty-state-icon">
                        <i class="bi bi-bookmark text-muted"></i>
                    </div>
                    <h5 class="fw-800">Your collection is empty</h5>
                    <p class="text-muted small">Start exploring and bookmarking elite content today.</p>
                    <a href="{{ route('learn.explore') }}" class="btn btn-dark rounded-pill px-4 mt-3">Explore Hub</a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $bookmarks->appends(request()->input())->links() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .max-w-narrow { max-width: 900px; }
    .bookmarks-container { padding-top: 40px; padding-bottom: 80px; }

    /* Simple Search */
    .form-control-simple {
        width: 100%;
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 16px 25px 16px 55px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 1.05rem;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .form-control-simple:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 10px 25px rgba(79, 70, 229, 0.1);
    }

    /* Simple Card */
    .bookmark-card-simple {
        background: #fff;
        border-radius: 24px;
        border: 1px solid #f1f5f9;
        margin-bottom: 20px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .bookmark-card-simple:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.06);
        border-color: #e2e8f0;
    }

    .card-thumb-link {
        display: block;
        position: relative;
        height: 100%;
        min-height: 180px;
        overflow: hidden;
    }
    .card-thumb-link img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.5s;
    }
    .bookmark-card-simple:hover .card-thumb-link img { transform: scale(1.05); }

    .play-overlay-simple {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 2.5rem;
        opacity: 0;
        transition: 0.3s;
    }
    .bookmark-card-simple:hover .play-overlay-simple { opacity: 1; }

    .duration-tag {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: rgba(0,0,0,0.7);
        color: #fff;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 800;
        z-index: 2;
    }

    .card-body-simple { padding: 25px; }
    
    .card-title-simple { margin-bottom: 12px; }
    .card-title-simple a {
        color: #0f172a;
        text-decoration: none;
        font-weight: 800;
        font-size: 1.2rem;
        letter-spacing: -0.02em;
        transition: 0.2s;
    }
    .card-title-simple a:hover { color: #4f46e5; }

    .card-text-simple {
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .badge-simple {
        background: #f8fafc;
        color: #475569;
        font-size: 10px;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .btn-remove-bookmark {
        background: none;
        border: none;
        color: #4338ca;
        font-size: 1.2rem;
        padding: 0;
        transition: 0.2s;
    }
    .btn-remove-bookmark:hover { transform: scale(1.1); color: #dc2626; }

    .card-footer-simple {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        border-top: 1px solid #f8fafc;
        padding-top: 15px;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: #f8fafc;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 20px;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Custom Pagination Styling */
    .pagination {
        display: flex;
        gap: 10px;
        margin-top: 40px;
        border: none;
    }
    .pagination .page-item .page-link {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-weight: 700;
        padding: 10px 18px;
        transition: all 0.2s;
    }
    .pagination .page-item .page-link:hover {
        background: #f8fafc;
        color: #4f46e5;
        border-color: #4f46e5;
    }
    .pagination .page-item.active .page-link {
        background: #4f46e5;
        color: #fff;
        border-color: #4f46e5;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }
    .pagination .page-item.disabled .page-link {
        background: #f8fafc;
        color: #cbd5e1;
        border-color: #f1f5f9;
    }

    /* Hide Pagination Info Text (Showing X to Y...) */
    nav .d-none.flex-sm-fill.d-sm-flex > div:first-child,
    nav p.small.text-muted {
        display: none !important;
    }
    /* Ensure pagination alignment */
    nav .d-sm-flex.justify-content-sm-between {
        justify-content: center !important;
    }
</style>
@endsection

@section('scripts')
<script>
    function toggleBookmark(contentId, btn) {
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
            if (data.status === 'removed') {
                showToast('Bookmark removed', 'info');
                const item = document.getElementById(`bookmark-${contentId}`);
                if (item) {
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(10px)';
                    setTimeout(() => {
                        item.remove();
                        if (document.querySelectorAll('.bookmark-card-simple').length === 0) {
                            window.location.reload();
                        }
                    }, 300);
                }
            }
        })
        .catch(err => console.error('Bookmark toggle failed', err));
    }
</script>
@endsection
