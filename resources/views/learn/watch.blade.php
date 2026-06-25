@extends(auth()->check() ? 'layouts.user' : 'layouts.public')

@section('title', $content->title . ' — Daleel AI')

@section('styles')
@if(!auth()->check())
<style>
    /* ==================== PUBLIC WATCH PAGE DESIGN ==================== */
    :root {
        --primary: #6366F1;
        --primary-light: #EEF2FF;
        --bg-alt: #F8FAFC;
        --glass: rgba(255, 255, 255, 0.8);
    }

    .public-watch-container {
        padding: 80px 0 160px;
        background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.03) 0%, transparent 50%);
        display: block;
        position: relative;
    }

    .watch-hero {
        margin-bottom: 48px;
    }

    .player-shadow {
        box-shadow: 0 30px 60px -12px rgba(15, 23, 42, 0.15), 0 18px 36px -18px rgba(15, 23, 42, 0.2);
        border-radius: 24px;
        overflow: hidden;
        background: #000;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .video-info-box {
        padding: 40px;
        background: white;
        border-radius: 24px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }

    .info-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 800;
        color: var(--primary);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 16px;
    }

    .video-title-main {
        font-size: 32px;
        font-weight: 900;
        line-height: 1.2;
        letter-spacing: -0.02em;
        margin-bottom: 20px;
        color: #0F172A;
    }

    .meta-chip-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
    }

    .meta-chip {
        padding: 6px 14px;
        background: var(--bg-alt);
        border: 1px solid #E2E8F0;
        border-radius: 100px;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
    }

    .video-desc-text {
        font-size: 17px;
        color: #475569;
        line-height: 1.7;
    }

    /* Suggested Sidebar */
    .sidebar-label {
        font-size: 14px;
        font-weight: 800;
        color: #0F172A;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .rec-card-mini {
        display: flex;
        gap: 16px;
        padding: 12px;
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        transition: all 0.2s;
        text-decoration: none;
        color: inherit;
        margin-bottom: 12px;
    }

    .rec-card-mini:hover {
        border-color: var(--primary);
        transform: translateX(4px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
    }

    .rec-thumb-mini {
        width: 110px;
        height: 70px;
        border-radius: 10px;
        object-fit: cover;
    }

    .rec-body-mini h4 {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 4px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .rec-body-mini span {
        font-size: 11px;
        font-weight: 800;
        color: var(--primary);
        text-transform: uppercase;
    }

    /* ==================== PREMIUM LOGIN MODAL ==================== */
    .premium-modal .modal-content {
        border: none;
        border-radius: 32px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        box-shadow: 0 40px 80px rgba(15, 23, 42, 0.3);
    }

    .modal-glow {
        position: absolute;
        top: -100px;
        right: -100px;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
        z-index: -1;
    }

    .modal-icon-wrap {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, var(--primary) 0%, #8B5CF6 100%);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 32px;
        color: white;
        font-size: 40px;
        box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.5);
    }

    .premium-modal h2 {
        font-size: 32px;
        font-weight: 900;
        letter-spacing: -0.02em;
        margin-bottom: 16px;
        color: #0F172A;
    }

    .premium-modal p {
        font-size: 17px;
        color: #64748B;
        max-width: 400px;
        margin: 0 auto 40px;
    }

    .btn-premium {
        padding: 16px 32px;
        border-radius: 100px;
        font-weight: 800;
        font-size: 16px;
        transition: all 0.3s;
    }

    .btn-premium.primary {
        background: var(--primary);
        color: white;
        box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.4);
    }

    .btn-premium.primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px -5px rgba(99, 102, 241, 0.5);
    }
</style>
@else
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
<style>
    /* Legacy Internal Styles for Users */
    .watch-page { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .player-wrapper { position: relative; background: #000; border-radius: 20px; overflow: hidden; aspect-ratio: 16 / 9; }
    .yt-header-blocker { position: absolute; top: 0; left: 0; width: 100%; height: 60px; z-index: 10; background: transparent; pointer-events: auto; }
    .player-wrapper #player-wrap, .player-wrapper .plyr, .player-wrapper .plyr__video-wrapper, .player-wrapper iframe { width: 100% !important; height: 100% !important; border-radius: 0; }
    :root { --plyr-color-main: #6366f1; --plyr-range-fill-background: #6366f1; }
    .plyr__control--overlaid { display: none !important; }
    .video-meta-card { background: #fff; border: 1px solid #f1f3f5; border-radius: 16px; }
    .next-video-card { padding: 12px; border-radius: 14px; border: 1px solid #f1f3f5; background: #fff; transition: all 0.2s; }
    .next-video-card:hover { border-color: #000; transform: translateX(4px); box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
    .next-thumb { position: relative; width: 90px; height: 58px; border-radius: 10px; overflow: hidden; flex-shrink: 0; }
    .next-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .next-play { position: absolute; inset: 0; background: rgba(0,0,0,0.35); display: flex; align-items: center; justify-content: center; font-size: 18px; color: #fff; opacity: 0; transition: opacity 0.2s; }
    .next-video-card:hover .next-play { opacity: 1; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .sidebar-section { position: sticky; top: 90px; }
    .curriculum-item { color: #475569; transition: all 0.2s; border-bottom: 1px solid #f8fafc; }
    .curriculum-item:hover { background: #f8fafc; color: #6366f1; }
    .curriculum-item.active { background: rgba(99, 102, 241, 0.05); color: #6366f1; border-left: 3px solid #6366f1; }
    .lesson-num-circle { width: 28px; height: 28px; border-radius: 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0; }
    .btn-bookmark { width: 42px; height: 42px; border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #64748b; transition: all 0.2s; }
    .btn-bookmark.active { background: #000; border-color: #000; color: #fff; }

    /* Like/Dislike & Report Buttons */
    .interaction-controls {
        display: flex;
        gap: 0.5rem;
    }

    .btn-interact {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #64748b;
        transition: all 0.2s;
    }

    .btn-interact:hover {
        background: #fff;
        color: var(--plyr-color-main);
        border-color: var(--plyr-color-main);
        transform: translateY(-2px);
    }

    .btn-like.active { background: #12B76A; border-color: #12B76A; color: #fff; }
    .btn-dislike.active { background: #F04438; border-color: #F04438; color: #fff; }

    .btn-report-outdated {
        font-size: 0.75rem;
        font-weight: 700;
        color: #94a3b8;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 1.5rem;
        transition: color 0.2s;
    }

    .btn-report-outdated:hover {
        color: #F04438;
    }

    /* Lesson Info Sections */
    .lesson-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #f1f5f9;
    }

    .lesson-meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .meta-label {
        font-weight: 800;
        color: #1e1b4b;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .meta-label i {
        color: var(--plyr-color-main);
        font-size: 1rem;
    }

    .meta-value {
        color: #475569;
        font-size: 0.9rem;
        line-height: 1.5;
        font-weight: 500;
    }
</style>
@endif
@endsection

@section('content')
@if(!auth()->check())
<!-- ==================== PUBLIC GUEST VIEW ==================== -->
<div class="public-watch-container">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="watch-hero">
                    <div class="player-shadow">
                        @if($content->video_url && (str_contains($content->video_url, 'amazonaws.com') || str_ends_with($content->video_url, '.mp4')))
                            <video id="player" playsinline controls data-poster="{{ $content->thumbnail_url }}">
                                <source src="{{ $content->video_url }}" type="video/mp4" />
                            </video>
                        @else
                            <div id="player" data-plyr-provider="youtube" data-plyr-embed-id="{{ $content->youtube_id }}"></div>
                        @endif
                    </div>
                </div>

                <div class="video-info-box">
                    <div class="info-eyebrow">
                        <i class="bi bi-stars"></i> Free Lesson
                    </div>
                    <h1 class="video-title-main">{{ $content->title }}</h1>
                    
                    <div class="meta-chip-row">
                        <div class="meta-chip"><i class="bi bi-tag-fill me-2"></i>{{ $content->category }}</div>
                        <div class="meta-chip"><i class="bi bi-award-fill me-2"></i>{{ $content->skill_level }}</div>
                        @if($content->duration_label)
                        <div class="meta-chip"><i class="bi bi-clock-fill me-2"></i>{{ $content->duration_label }}</div>
                        @endif
                    </div>

                    <p class="video-desc-text">{{ $content->description }}</p>
                    
                    @if($content->tags)
                    <div class="mt-4 d-flex flex-wrap gap-2">
                        @foreach(array_map('trim', explode(',', $content->tags)) as $tag)
                            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill small">{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                @if($course)
                    <div class="sidebar-label">Course Curriculum</div>
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-dark text-white py-3 px-3">
                            <div class="small opacity-75 fw-bold text-uppercase" style="font-size: 10px; letter-spacing: 0.05em;">Playing from Course</div>
                            <h6 class="fw-bold mb-0 text-truncate" title="{{ $course->title }}">{{ $course->title }}</h6>
                        </div>
                        <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                            @foreach($course->contents as $lesson)
                                <a href="{{ route('learn.watch', $lesson) }}" class="rec-card-mini border-0 rounded-0 m-0 border-bottom {{ $lesson->id == $content->id ? 'bg-light' : '' }}" style="padding: 15px;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="lesson-num-circle @if($lesson->id == $content->id) bg-primary text-white @endif">{{ $loop->iteration }}</div>
                                        <div>
                                            <h4 class="small fw-bold mb-0 {{ $lesson->id == $content->id ? 'text-primary' : '' }}">{{ $lesson->title }}</h4>
                                            @if($lesson->duration_label)<span class="text-muted" style="font-size: 10px;">{{ $lesson->duration_label }}</span>@endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="sidebar-label">Up Next</div>
                    @foreach($recommended as $rec)
                    <a href="{{ route('learn.watch', $rec) }}" class="rec-card-mini">
                        <img src="{{ $rec->thumbnail_url }}" class="rec-thumb-mini" alt="{{ $rec->title }}">
                        <div class="rec-body-mini">
                            <h4>{{ $rec->title }}</h4>
                            <span>{{ $rec->category }}</span>
                        </div>
                    </a>
                    @endforeach
                @endif

                <div class="card bg-dark text-white p-4 rounded-4 mt-5 border-0 shadow-lg mb-5" style="height: 375px; background: linear-gradient(135deg, #1E1B4B, #312E81);">
                    <h5 class="fw-bold mb-3">Track your progress</h5>
                    <p class="small opacity-75 mb-4">Join 15,000+ members who are mastering AI through personalized learning paths.</p>
                    <a href="{{ route('register') }}" class="btn btn-primary w-100 rounded-pill fw-bold">Join Now — It's Free</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PROFESSIONAL LOGIN MODAL -->
<div class="modal fade premium-modal" id="loginReminderModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content overflow-hidden">
            <div class="modal-glow"></div>
            <div class="p-5 text-center">
                <div class="modal-icon-wrap">
                    <i class="bi bi-rocket-takeoff"></i>
                </div>
                <h2>Level up your learning</h2>
                <p>You've unlocked the basics. Create a free account to track your progress and get AI recommendations tailored to your workflow.</p>
                <div class="d-flex flex-column gap-3">
                    <a href="{{ route('register') }}" class="btn btn-premium primary">Create Free Account</a>
                    <a href="{{ route('login') }}" class="btn btn-premium btn-link text-dark text-decoration-none">Already a member? Login</a>
                </div>
                <button type="button" class="btn btn-link text-muted small mt-4" data-bs-dismiss="modal">Continue watching for now</button>
            </div>
        </div>
    </div>
</div>
@else
<!-- ==================== INTERNAL USER VIEW ==================== -->
<div class="watch-page">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="player-wrapper mb-4">
                <div id="player-wrap">
                    @if($content->video_url && (str_contains($content->video_url, 'amazonaws.com') || str_ends_with($content->video_url, '.mp4')))
                        <video id="player" playsinline controls data-poster="{{ $content->thumbnail_url }}">
                            <source src="{{ $content->video_url }}" type="video/mp4" />
                        </video>
                    @else
                        <div id="player" data-plyr-provider="youtube" data-plyr-embed-id="{{ $content->youtube_id }}"></div>
                    @endif
                </div>
            </div>

            <div class="watch-progress-bar mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-bold text-muted">Your progress</span>
                    <span class="small fw-bold" id="progress-label">{{ $progress ? round($progress->completion_percent) : 0 }}%</span>
                </div>
                <div class="progress rounded-pill" style="height: 6px; background: #f1f3f5;">
                    <div class="progress-bar bg-dark rounded-pill" id="progress-bar" style="width: {{ $progress ? $progress->completion_percent : 0 }}%;"></div>
                </div>
            </div>

            <div class="video-meta-card p-4 mb-4">
                <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                    <h1 class="h4 fw-bold mb-0">{{ $content->title }}</h1>
                    <div class="d-flex gap-2">
                        <div class="interaction-controls">
                            <button class="btn-interact btn-like" onclick="toggleLike({{ $content->id }}, 'like', this)" title="Like">
                                <i class="bi bi-hand-thumbs-up"></i>
                            </button>
                            <button class="btn-interact btn-dislike" onclick="toggleLike({{ $content->id }}, 'dislike', this)" title="Dislike">
                                <i class="bi bi-hand-thumbs-down"></i>
                            </button>
                        </div>
                        <button class="btn-bookmark {{ auth()->user()->bookmarkedContents()->where('content_id', $content->id)->exists() ? 'active' : '' }}" onclick="toggleBookmark({{ $content->id }}, this)">
                            <i class="bi {{ auth()->user()->bookmarkedContents()->where('content_id', $content->id)->exists() ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                        </button>
                    </div>
                </div>
                <p class="text-muted mb-0">{{ $content->description }}</p>

                <div class="lesson-meta-grid">
                    <div class="lesson-meta-item">
                        <span class="meta-label"><i class="bi bi-briefcase"></i> Use Case</span>
                        <span class="meta-value">Automating recurring professional workflows using specialized AI interactions and real-time behavioral mapping.</span>
                    </div>
                    <div class="lesson-meta-item">
                        <span class="meta-label"><i class="bi bi-person-badge"></i> Role Relevance</span>
                        <span class="meta-value">Essential for professionals looking to minimize cognitive load during tool-switching and repetitive digital tasks.</span>
                    </div>
                    <div class="lesson-meta-item">
                        <span class="meta-label"><i class="bi bi-journal-check"></i> Lesson Outcome</span>
                        <span class="meta-value">Competency in deploying modern AI strategies to save at least 15-20% of daily digital operation time.</span>
                    </div>
                </div>

                <a href="#" class="btn-report-outdated" onclick="event.preventDefault(); reportOutdated({{ $content->id }})">
                    <i class="bi bi-flag"></i> Report this content as outdated
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sidebar-section">
                @if($course)
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-dark text-white py-3 px-4">
                            <div class="small opacity-75 fw-bold text-uppercase mb-1" style="font-size: 11px;">Current Course</div>
                            <h6 class="fw-bold mb-0 text-truncate" title="{{ $course->title }}">{{ $course->title }}</h6>
                        </div>
                        <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                            @foreach($course->contents as $lesson)
                                <a href="{{ route('learn.watch', $lesson) }}" class="curriculum-item d-flex align-items-center gap-3 py-3 px-4 text-decoration-none {{ $lesson->id == $content->id ? 'active' : '' }}">
                                    <div class="lesson-num-circle {{ $lesson->id == $content->id ? 'bg-primary text-white' : '' }}">{{ $loop->iteration }}</div>
                                    <div class="fw-bold small text-truncate">{{ $lesson->title }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <h6 class="fw-800 mb-3">Suggested Lessons</h6>
                    @foreach($recommended as $rec)
                    <a href="{{ route('learn.watch', $rec) }}" class="next-video-card text-decoration-none d-block mb-3">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="next-thumb"><img src="{{ $rec->thumbnail_url }}"></div>
                            <div class="fw-bold small text-dark line-clamp-2">{{ $rec->title }}</div>
                        </div>
                    </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
<script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
<script>
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    const isYoutube = document.querySelector('#player').dataset.plyrProvider === 'youtube';
    const player = new Plyr('#player', {
        youtube: { noCookie: true, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1 }
    });

    if (!isAuthenticated) {
        let playbackStartTime = 0;
        let totalPlaybackTime = 0;
        const LIMIT_INTERVAL = 3 * 60;
        let nextLimit = LIMIT_INTERVAL;

        player.on('playing', () => { playbackStartTime = Date.now(); });
        player.on('pause', () => {
            if (playbackStartTime > 0) {
                totalPlaybackTime += (Date.now() - playbackStartTime) / 1000;
                playbackStartTime = 0;
            }
        });

        player.on('timeupdate', () => {
            if (player.playing && playbackStartTime > 0) {
                const currentTime = totalPlaybackTime + (Date.now() - playbackStartTime) / 1000;
                if (currentTime >= nextLimit) {
                    player.pause();
                    new bootstrap.Modal(document.getElementById('loginReminderModal')).show();
                    nextLimit += LIMIT_INTERVAL;
                }
            }
        });
    } else {
        // Progress Saving Logic
        setInterval(() => {
            if (player.playing) {
                fetch("{{ route('learn.progress.save') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                    body: JSON.stringify({ content_id: {{ $content->id }}, watched_seconds: Math.floor(player.currentTime), duration_seconds: Math.floor(player.duration || 0) })
                })
                .then(r => r.json())
                .then(data => {
                    const bar = document.getElementById('progress-bar');
                    const label = document.getElementById('progress-label');
                    if (bar) bar.style.width = data.completion_percent + '%';
                    if (label) label.textContent = Math.round(data.completion_percent) + '%';
                });
            }
        }, 10000);
    }

    function toggleBookmark(contentId, btn) {
        fetch(`/bookmarks/${contentId}/toggle`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}", 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            const icon = btn.querySelector('i');
            if (data.status === 'added') {
                btn.classList.add('active');
                icon.classList.replace('bi-bookmark', 'bi-bookmark-fill');
            } else {
                btn.classList.remove('active');
                icon.classList.replace('bi-bookmark-fill', 'bi-bookmark');
            }
        });
    }
    function toggleLike(contentId, type, btn) {
        const controls = btn.closest('.interaction-controls');
        const likeBtn = controls.querySelector('.btn-like');
        const dislikeBtn = controls.querySelector('.btn-dislike');
        
        if (type === 'like') {
            likeBtn.classList.toggle('active');
            dislikeBtn.classList.remove('active');
            // Simulated sentiment toast
            if (typeof showToast !== 'undefined') showToast(likeBtn.classList.contains('active') ? 'Liked' : 'Removed Like', 'success');
        } else {
            dislikeBtn.classList.toggle('active');
            likeBtn.classList.remove('active');
            if (typeof showToast !== 'undefined') showToast(dislikeBtn.classList.contains('active') ? 'Disliked' : 'Removed Dislike', 'info');
        }
    }

    function reportOutdated(contentId) {
        if (typeof showToast !== 'undefined') {
            showToast('Thank you for reporting. Our team will review this lesson.', 'success');
        } else {
            alert('Thank you for reporting. Our team will review this lesson.');
        }
    }
</script>
@endsection
