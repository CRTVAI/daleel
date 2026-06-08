@extends('layouts.user')

@section('title', $course->title . ' — Dallel AI')

@section('styles')
<style>
    .compact-hero {
        background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
        border-radius: 32px;
        padding: 40px;
        color: #020617;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
    }
    .hero-glass-bg {
        position: absolute;
        top: -50%;
        right: -10%;
        width: 60%;
        height: 200%;
        background: radial-gradient(circle, rgba(79, 70, 229, 0.05) 0%, transparent 70%);
        transform: rotate(-15deg);
        pointer-events: none;
    }
    .compact-thumb {
        width: 180px;
        height: 120px;
        border-radius: 20px;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.2);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .hero-stat-pill {
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 6px 16px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
    }
    .curriculum-grid {
        max-width: 1100px;
        margin: 0 auto;
    }
    .neo-curriculum-card {
        background: #fff;
        border-radius: 28px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 40px rgba(0,0,0,0.02);
        overflow: hidden;
    }
    .neo-section-header {
        background: #f8fafc;
        padding: 16px 25px;
        color: #1e293b;
        font-weight: 800;
        font-size: 13px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #f1f5f9;
    }
    .neo-lesson-item {
        padding: 15px 25px;
        display: flex;
        align-items: center;
        gap: 18px;
        transition: all 0.25s ease;
        text-decoration: none;
        color: #64748b;
        border-bottom: 1px solid #f8fafc;
    }
    .neo-lesson-item:hover {
        background: #f8fafc;
        color: #4f46e5;
        padding-left: 32px;
    }
    .neo-lesson-item:last-child {
        border-bottom: none;
    }
    .neo-num-badge {
        width: 32px;
        height: 32px;
        background: #f1f5f9;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: #94a3b8;
        font-size: 12px;
        transition: 0.3s;
    }
    .neo-lesson-item:hover .neo-num-badge {
        background: #4f46e5;
        color: #fff;
    }
    .neo-play-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        transition: 0.2s;
    }
    .neo-lesson-item:hover .neo-play-btn {
        border-color: #4f46e5;
        color: #4f46e5;
        transform: scale(1.1);
    }
    .neo-bookmark-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        transition: 0.2s;
    }
    .neo-bookmark-btn:hover {
        background: #fff;
        border-color: #000;
        color: #000;
        transform: scale(1.05);
    }
    .neo-bookmark-btn.active {
        background: #000;
        border-color: #000;
        color: #fff;
    }
</style>
@endsection

@section('content')
<div class="compact-hero animate-slide-up">
    <div class="hero-glass-bg"></div>
    <div class="d-flex flex-column flex-md-row align-items-center gap-5">
        <div class="flex-shrink-0">
            <img src="{{ $course->thumbnail }}" class="compact-thumb" alt="{{ $course->title }}">
        </div>
        <div class="flex-grow-1 text-center text-md-start">
            <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2 mb-3">
                <span class="badge bg-primary rounded-pill px-3 py-2 fw-800" style="font-size: 10px; letter-spacing: 0.5px;">{{ strtoupper($course->category) }}</span>
                <div class="hero-stat-pill"><i class="bi bi-play-btn-fill me-2 opacity-50"></i>{{ $course->contents->count() }} LESSONS</div>
                <div class="hero-stat-pill"><i class="bi bi-clock-fill me-2 opacity-50"></i>5.5 HOURS</div>
            </div>
            <h1 class="fw-800 mb-2" style="font-size: 32px; letter-spacing: -0.03em;">{{ $course->title }}</h1>
            <p class="opacity-75 small fw-600 mb-0 line-clamp-1" style="max-width: 600px;">{{ $course->description }}</p>
        </div>
        <div class="flex-shrink-0 d-none d-xl-block">
            @php $firstLesson = $course->contents->sortBy('sort_order')->first(); @endphp
            @if($firstLesson)
                <a href="{{ route('learn.watch', $firstLesson) }}" class="btn btn-primary rounded-pill px-4 py-2 fw-800 small">CONTINUE LEARNING <i class="bi bi-arrow-right ms-2"></i></a>
            @endif
        </div>
    </div>
</div>

<div class="curriculum-grid">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h5 class="fw-800 text-dark mb-0">Course Content</h5>
            <p class="text-muted small fw-700 mb-0">Step-by-step curriculum</p>
        </div>
        <div class="text-end">
             <div class="d-flex align-items-center gap-2">
                <span class="text-muted small fw-800">EXPAND ALL</span>
                <div class="bg-light p-1 rounded-2"><i class="bi bi-chevron-expand"></i></div>
             </div>
        </div>
    </div>

    <div class="neo-curriculum-card animate-slide-up delay-2">
        @php $globalIndex = 1; @endphp
        @forelse($course->grouped_contents as $sectionLabel => $lessons)
            <div class="section-block">
                <div class="neo-section-header">
                    <span>{{ $sectionLabel ?: 'Fundamentals' }}</span>
                    <span class="opacity-50 fw-700" style="font-size: 11px;">{{ count($lessons) }} Lessons</span>
                </div>
                @foreach($lessons as $lesson)
                    <div class="d-flex align-items-center gap-2 pe-3">
                        <a href="{{ route('learn.watch', $lesson) }}" class="neo-lesson-item flex-grow-1">
                            <div class="neo-num-badge">{{ $globalIndex++ }}</div>
                            <div class="flex-grow-1">
                                <div class="fw-800 text-dark mb-0 small">{{ $lesson->title }}</div>
                                <div class="small fw-700 opacity-40" style="font-size: 10px;">Part {{ $lesson->sort_order ?: ($loop->index + 1) }} · Tutorial Video</div>
                            </div>
                            <div class="neo-play-btn">
                                <i class="bi bi-play-fill"></i>
                            </div>
                        </a>
                        <button class="neo-bookmark-btn {{ Auth::user()->bookmarkedContents()->where('content_id', $lesson->id)->exists() ? 'active' : '' }}" 
                                onclick="toggleBookmark({{ $lesson->id }}, this)" 
                                title="Bookmark">
                            <i class="bi {{ Auth::user()->bookmarkedContents()->where('content_id', $lesson->id)->exists() ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="p-5 text-center">
                <p class="text-muted fw-800 mb-0">No lessons available for this course yet.</p>
            </div>
        @endforelse
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
                showToast('Lesson bookmarked!', 'success');
            } else {
                btn.classList.remove('active');
                icon.classList.replace('bi-bookmark-fill', 'bi-bookmark');
                showToast('Bookmark removed', 'info');
            }
        })
        .catch(err => console.error('Bookmark toggle failed', err));
    }
</script>
@endsection
