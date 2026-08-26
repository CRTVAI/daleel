@extends('layouts.user')

@section('title', 'My Bookmarks — Daleel AI')

@section('content')
<div class="bookmarks-container">
    <div class="max-w-narrow mx-auto">
        <div class="bookmark-page-header">
            <span class="bookmark-eyebrow"><i class="bi bi-bookmark-star-fill"></i> Saved Library</span>
            <h2>My Bookmarks</h2>
            <p>Keep your most useful lessons close and resume learning without hunting through the library.</p>
        </div>

        <div class="search-wrap-simple">
            <form action="{{ route('bookmarks') }}" method="GET" class="position-relative">
                <i class="bi bi-search search-icon-simple"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="form-control-simple" placeholder="Search through your bookmarks...">
                @if(request('search'))
                    <a href="{{ route('bookmarks') }}" class="search-clear-simple" aria-label="Clear search">
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
                                <a href="{{ route('learn.watch', $item) }}" class="bookmark-watch-link">
                                    Watch lesson <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-bookmark-state">
                    <div class="empty-state-icon">
                        <i class="bi bi-bookmark text-muted"></i>
                    </div>
                    <h5 class="fw-800">Your collection is empty</h5>
                    <p class="text-muted small">Start exploring and save the lessons you want to revisit.</p>
                    <a href="{{ route('learn.explore') }}" class="btn btn-dark rounded-pill px-4 mt-3">Explore Library</a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="bookmark-pagination d-flex justify-content-center">
            {{ $bookmarks->appends(request()->input())->links() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .max-w-narrow { max-width: 980px; }
    .bookmarks-container {
        padding-top: 34px;
        padding-bottom: 80px;
    }

    .bookmark-page-header {
        text-align: center;
        max-width: 640px;
        margin: 0 auto 24px;
    }

    .bookmark-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 10px;
        border-radius: 10px;
        border: 1px solid #e0e7ff;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 12px;
    }

    .bookmark-page-header h2 {
        margin: 0 0 8px;
        color: #111827;
        font-size: 34px;
        font-weight: 900;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }

    .bookmark-page-header p {
        margin: 0;
        color: #667085;
        font-size: 14px;
        font-weight: 650;
        line-height: 1.6;
    }

    /* Simple Search */
    .search-wrap-simple {
        margin-bottom: 24px;
    }

    .search-icon-simple {
        position: absolute;
        top: 50%;
        left: 18px;
        transform: translateY(-50%);
        color: #98a2b3;
        z-index: 2;
    }

    .search-clear-simple {
        position: absolute;
        top: 50%;
        right: 18px;
        transform: translateY(-50%);
        color: #98a2b3;
        text-decoration: none;
    }

    .form-control-simple {
        width: 100%;
        background: #fff;
        border: 1px solid #dfe5ee;
        padding: 14px 46px 14px 48px;
        border-radius: 14px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.2s;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.035);
    }
    .form-control-simple:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
    }

    /* Simple Card */
    .bookmark-grid {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .bookmark-card-simple {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e4e9f0;
        margin-bottom: 0;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.035);
    }
    .bookmark-card-simple:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 36px rgba(15, 23, 42, 0.07);
        border-color: #d8dee8;
    }

    .card-thumb-link {
        display: block;
        position: relative;
        height: 100%;
        min-height: 196px;
        overflow: hidden;
        background: #111827;
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
        background: rgba(15, 23, 42, 0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 2rem;
        opacity: 0;
        transition: 0.3s;
    }
    .bookmark-card-simple:hover .play-overlay-simple { opacity: 1; }

    .duration-tag {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: rgba(17, 24, 39, 0.88);
        color: #fff;
        padding: 4px 10px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 800;
        z-index: 2;
    }

    .card-body-simple {
        padding: 22px;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .card-title-simple { margin-bottom: 12px; }
    .card-title-simple a {
        color: #0f172a;
        text-decoration: none;
        font-weight: 900;
        font-size: 18px;
        line-height: 1.35;
        letter-spacing: 0;
        transition: 0.2s;
    }
    .card-title-simple a:hover { color: #4f46e5; }

    .card-text-simple {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.6;
        margin-bottom: 18px;
    }

    .badge-simple {
        background: #eef2ff;
        color: #4f46e5;
        font-size: 10px;
        font-weight: 900;
        padding: 5px 10px;
        border-radius: 8px;
        border: 1px solid #e0e7ff;
    }

    .btn-remove-bookmark {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #f8fafc;
        border: 1px solid #e4e9f0;
        color: #4338ca;
        font-size: 1rem;
        padding: 0;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-remove-bookmark:hover {
        transform: none;
        color: #dc2626;
        background: #fef2f2;
        border-color: #fee2e2;
    }

    .card-footer-simple {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        font-weight: 800;
        color: #94a3b8;
        border-top: 1px solid #edf1f5;
        padding-top: 14px;
        margin-top: auto;
        gap: 12px;
    }

    .bookmark-watch-link {
        display: none;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-top: 12px;
        padding: 10px 12px;
        border-radius: 10px;
        background: #111827;
        color: #fff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 900;
    }

    .empty-bookmark-state {
        text-align: center;
        padding: 54px 20px;
        background: #fff;
        border: 1px solid #e4e9f0;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.035);
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
        margin-top: 28px;
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

    @media (max-width: 768px) {
        .main-container-neo {
            width: 100% !important;
            padding: 10px !important;
        }

        .bookmarks-container {
            padding-top: 12px;
            padding-bottom: 36px;
        }

        .bookmark-page-header {
            margin-bottom: 16px;
            padding: 0 4px;
        }

        .bookmark-eyebrow {
            display: none;
        }

        .bookmark-page-header h2 {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .bookmark-page-header p {
            font-size: 12px;
            line-height: 1.45;
        }

        .search-wrap-simple {
            margin-bottom: 14px;
        }

        .form-control-simple {
            border-radius: 11px;
            padding: 11px 42px 11px 40px;
            font-size: 13px;
            box-shadow: none;
        }

        .search-icon-simple {
            left: 14px;
            font-size: 13px;
        }

        .search-clear-simple {
            right: 14px;
        }

        .bookmark-grid {
            gap: 12px;
        }

        .bookmark-card-simple {
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.035);
        }

        .bookmark-card-simple:hover {
            transform: none;
        }

        .card-thumb-link {
            min-height: 0;
            height: auto;
            aspect-ratio: 16 / 9;
        }

        .bookmark-card-simple:hover .card-thumb-link img {
            transform: none;
        }

        .play-overlay-simple {
            opacity: 1;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.42), rgba(15, 23, 42, 0.08));
            font-size: 1.8rem;
        }

        .duration-tag {
            bottom: 9px;
            right: 9px;
            border-radius: 6px;
            font-size: 9px;
        }

        .card-body-simple {
            padding: 13px;
        }

        .card-body-simple .d-flex.justify-content-between.align-items-start {
            margin-bottom: 8px !important;
        }

        .badge-simple {
            font-size: 9px;
            padding: 4px 8px;
        }

        .btn-remove-bookmark {
            width: 32px;
            height: 32px;
            border-radius: 8px;
        }

        .card-title-simple {
            margin-bottom: 8px;
        }

        .card-title-simple a {
            display: block;
            font-size: 15px;
            line-height: 1.35;
            text-align: center;
        }

        .card-text-simple {
            font-size: 12px;
            line-height: 1.5;
            text-align: center;
            margin-bottom: 12px;
        }

        .card-footer-simple {
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            text-align: center;
            font-size: 10px;
            padding-top: 10px;
            gap: 8px;
        }

        .bookmark-watch-link {
            display: flex;
        }

        .empty-bookmark-state {
            border-radius: 12px;
            padding: 38px 16px;
        }

        .empty-state-icon {
            width: 62px;
            height: 62px;
            font-size: 1.5rem;
            margin-bottom: 14px;
        }

        .bookmark-pagination {
            margin-top: 18px !important;
        }

        .pagination {
            gap: 6px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination .page-item .page-link {
            border-radius: 9px;
            padding: 8px 11px;
            font-size: 12px;
        }
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
