@extends(auth()->check() ? 'layouts.user' : 'layouts.mentor')

@section('title', $content->title . ' — Dallel AI Mentor')

@section('content')
<div class="watch-page">
    <div class="row g-4">
        <!-- Video Player Column -->
        <div class="col-lg-8">
            <!-- Plyr Player Container -->
            <div class="player-wrapper mb-4">
                <div id="player-wrap">
                    <div id="player"
                         data-plyr-provider="youtube"
                         data-plyr-embed-id="{{ $content->youtube_id }}">
                    </div>
                </div>
            </div>

            @if(auth()->check())
            <!-- Progress Bar (Users Only) -->
            <div class="watch-progress-bar mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="small fw-bold text-muted">Your progress</span>
                    <span class="small fw-bold" id="progress-label">{{ $progress ? round($progress->completion_percent) : 0 }}%</span>
                </div>
                <div class="progress rounded-pill" style="height: 6px; background: #f1f3f5;">
                    <div class="progress-bar bg-dark rounded-pill" id="progress-bar" style="width: {{ $progress ? $progress->completion_percent : 0 }}%; transition: width 1s ease;"></div>
                </div>
                @if($progress && $progress->completed)
                <div class="mt-2 text-success small fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Completed</div>
                @endif
            </div>
            @endif

            <!-- Video Meta -->
            <div class="video-meta-card p-4 mb-4">
                <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                    <div>
                        <span class="badge bg-light text-dark border me-2 px-3 py-2 rounded-pill fw-bold small">{{ $content->category }}</span>
                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-bold small">{{ $content->skill_level }}</span>
                        
                        @if($content->video_url_ar)
                        <div class="lang-switcher-wrapper ms-3">
                            <input type="checkbox" id="langToggle" class="lang-checkbox">
                            <label for="langToggle" class="lang-slider">
                                <span class="lang-label en">English</span>
                                <span class="lang-label ar">العربية</span>
                                <div class="lang-knob">
                                    <i class="bi bi-translate"></i>
                                </div>
                            </label>
                        </div>
                        @endif
                    </div>
                    @if($content->duration_label)
                    <span class="text-muted small fw-bold"><i class="bi bi-clock me-1"></i>{{ $content->duration_label }}</span>
                    @endif
                </div>
                <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                    <h1 class="h4 fw-bold mb-0" style="line-height: 1.35; color:#000;">{{ $content->title }}</h1>
                    @if(auth()->check())
                    <button class="btn btn-bookmark {{ auth()->user()->bookmarkedContents()->where('content_id', $content->id)->exists() ? 'active' : '' }}" 
                            onclick="toggleBookmark({{ $content->id }}, this)" 
                            title="Bookmark this video">
                        <i class="bi {{ auth()->user()->bookmarkedContents()->where('content_id', $content->id)->exists() ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
                    </button>
                    @endif
                </div>
                @if($content->description)
                <p class="text-muted mb-0" style="line-height: 1.7;">{{ $content->description }}</p>
                @endif
                @if($content->tags)
                <div class="mt-3 d-flex flex-wrap gap-2">
                    @foreach(array_map('trim', explode(',', $content->tags)) as $tag)
                        @if($tag)
                        <span class="badge bg-light text-secondary border px-2 py-1 small">{{ $tag }}</span>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar: Curriculum or Up Next -->
        <div class="col-lg-4">
            <div class="sidebar-section">
                @if(auth()->check() && $course && request('from') !== 'mentor')
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-dark text-white py-3 px-4">
                            <h6 class="fw-bold mb-1">Course Curriculum</h6>
                            <div class="small opacity-75">{{ $course->title }}</div>
                        </div>
                        <div class="card-body p-0">
                            <div class="accordion accordion-flush" id="curriculumAccordion">
                                @foreach($course->grouped_contents as $sectionLabel => $lessons)
                                    <div class="accordion-item border-0">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button py-3 px-4 fw-bold small text-uppercase letter-spacing-1 {{ $content->section_part_label != $sectionLabel ? 'collapsed' : '' }}" 
                                                    type="button" data-bs-toggle="collapse" data-bs-target="#section-{{ Str::slug($sectionLabel ?: 'general') }}">
                                                {{ $sectionLabel ?: 'General' }}
                                            </button>
                                        </h2>
                                        <div id="section-{{ Str::slug($sectionLabel ?: 'general') }}" 
                                             class="accordion-collapse collapse {{ $content->section_part_label == $sectionLabel ? 'show' : '' }}" 
                                             data-bs-parent="#curriculumAccordion">
                                            <div class="accordion-body p-0">
                                                @foreach($lessons as $lesson)
                                                    <a href="{{ route('learn.watch', $lesson) }}" 
                                                       class="curriculum-item d-flex align-items-center gap-3 py-3 px-4 text-decoration-none {{ $lesson->id == $content->id ? 'active' : '' }}">
                                                        <div class="lesson-num-circle">
                                                            @if($lesson->id == $content->id)
                                                                <i class="bi bi-play-fill"></i>
                                                            @else
                                                                {{ $loop->iteration }}
                                                            @endif
                                                        </div>
                                                        <div class="overflow-hidden">
                                                            <div class="fw-bold small line-clamp-2">{{ $lesson->title }}</div>
                                                            <div class="text-muted" style="font-size:10px;">
                                                                Part {{ $lesson->sort_order }} · {{ $lesson->duration_label ?: '5m' }}
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recommended Videos (Always show or show below curriculum) -->
                <div class="mt-5 mb-3 d-flex align-items-center gap-2">
                    <div style="width: 4px; height: 18px; background: #6366f1; border-radius: 10px;"></div>
                    <h6 class="fw-800 mb-0" style="color:#000; letter-spacing:-0.02em;">Suggested Lessons</h6>
                </div>
                <div class="d-flex flex-column gap-3">
                    @forelse($recommended as $rec)
                    <a href="{{ route('learn.watch', $rec) }}{{ request('from') === 'mentor' ? '?from=mentor' : '' }}{{ request('query') ? '&query=' . urlencode(request('query')) : '' }}" class="next-video-card text-decoration-none">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="next-thumb flex-shrink-0">
                                <img src="{{ $rec->thumbnail_url }}" alt="{{ $rec->title }}">
                                <div class="next-play"><i class="bi bi-play-fill"></i></div>
                            </div>
                            <div class="overflow-hidden">
                                <div class="fw-bold small text-dark line-clamp-2" style="font-size: 0.85rem;">{{ $rec->title }}</div>
                                @if(auth()->check())
                                    <div class="text-muted" style="font-size:11px; margin-top:4px;">{{ $rec->category }} · {{ $rec->skill_level }}</div>
                                    @if($rec->duration_label)
                                    <div class="text-muted" style="font-size:10px;"><i class="bi bi-clock me-1"></i>{{ $rec->duration_label }}</div>
                                    @endif
                                @else
                                    <div class="text-muted" style="font-size: 10px; margin-top:4px; font-weight: 700; text-transform: uppercase;">{{ $rec->category }}</div>
                                @endif
                            </div>
                        </div>
                    </a>
                    @empty
                        <p class="text-muted small px-3">No other related videos found yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Plyr CSS -->
@section('styles')
<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
<style>
    .watch-page { animation: fadeIn 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

    /* Plyr Player Wrapper */
    .player-wrapper {
        position: relative;
        background: #000;
        border-radius: 20px;
        overflow: hidden;
        aspect-ratio: 16 / 9;
    }
    .yt-header-blocker {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 60px;
        z-index: 10;
        background: transparent;
        pointer-events: auto;
    }
    .player-wrapper #player-wrap,
    .player-wrapper .plyr,
    .player-wrapper .plyr__video-wrapper,
    .player-wrapper iframe {
        width: 100% !important;
        height: 100% !important;
        border-radius: 0;
    }

    /* Plyr theme customization — pure dark, no YouTube feel */
    :root {
        --plyr-color-main: #6366f1;
        --plyr-range-fill-background: #6366f1;
        --plyr-video-background: #000;
        --plyr-menu-background: #1e293b;
        --plyr-menu-color: #f8fafc;
        --plyr-tooltip-background: #1e293b;
        --plyr-tooltip-color: #f8fafc;
        --plyr-font-family: 'Inter', sans-serif;
        --plyr-control-icon-size: 18px;
        --plyr-control-spacing: 10px;
    }
    .plyr--video .plyr__controls {
        background: linear-gradient(transparent, rgba(0,0,0,0.85)) !important;
        padding: 20px 16px 14px !important;
    }
    /* Hide the large centered overlay play button — we only use the control bar */
    .plyr__control--overlaid { display: none !important; }

    .video-meta-card {
        background: #fff;
        border: 1px solid #f1f3f5;
        border-radius: 16px;
    }

    /* Next videos */
    .next-video-card {
        padding: 12px;
        border-radius: 14px;
        border: 1px solid #f1f3f5;
        background: #fff;
        transition: all 0.2s;
    }
    .next-video-card:hover {
        border-color: #000;
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .next-thumb {
        position: relative;
        width: 90px;
        height: 58px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
    }
    .next-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .next-play {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #fff;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .next-video-card:hover .next-play { opacity: 1; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .sidebar-section { position: sticky; top: 90px; }

    /* Curriculum Sidebar */
    .curriculum-item {
        color: #475569;
        transition: all 0.2s;
        border-bottom: 1px solid #f8fafc;
    }
    .curriculum-item:hover { background: #f8fafc; color: #6366f1; }
    .curriculum-item.active {
        background: rgba(99, 102, 241, 0.05);
        color: #6366f1;
        border-left: 3px solid #6366f1;
    }
    .lesson-num-circle {
        width: 28px; height: 28px;
        border-radius: 8px;
        background: #f1f5f9;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700; flex-shrink: 0;
    }
    .curriculum-item.active .lesson-num-circle { background: #6366f1; color: #fff; }
    .accordion-button:not(.collapsed) { background: #fff; color: #000; box-shadow: none; }
    .accordion-button:focus { box-shadow: none; border-color: rgba(0,0,0,0.1); }
    .letter-spacing-1 { letter-spacing: 1px; }

    /* Bookmark Button */
    .btn-bookmark {
        width: 42px; height: 42px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; color: #64748b;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .btn-bookmark:hover { background: #fff; border-color: #000; color: #000; transform: scale(1.05); }
    .btn-bookmark.active { background: #000; border-color: #000; color: #fff; }
    .btn-bookmark.active:hover { background: #334155; border-color: #334155; }

    /* Premium Language Switcher */
    .lang-switcher-wrapper {
        display: inline-block;
        vertical-align: middle;
    }
    .lang-checkbox { display: none; }
    .lang-slider {
        position: relative;
        display: flex;
        align-items: center;
        width: 160px;
        height: 38px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 4px;
        user-select: none;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
    .lang-label {
        width: 50%;
        text-align: center;
        font-size: 10px;
        font-weight: 800;
        z-index: 1; /* Below the knob */
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        pointer-events: none;
        line-height: 28px; /* Match knob height */
    }
    .lang-label.en { color: #6366f1; }
    .lang-label.ar { color: #94a3b8; }
    
    .lang-knob {
        position: absolute;
        left: 4px;
        top: 4px;
        width: 78px;
        height: 28px;
        background: #fff;
        border-radius: 20px;
        z-index: 2; /* On top of labels */
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .lang-knob i {
        font-size: 14px;
        color: #6366f1;
    }

    .lang-checkbox:checked + .lang-slider .lang-knob {
        transform: translateX(74px);
    }
    .lang-checkbox:checked + .lang-slider .lang-label.en { color: #94a3b8; opacity: 1; }
    .lang-checkbox:checked + .lang-slider .lang-label.ar { color: #6366f1; opacity: 0; } /* Hide label under knob */
    .lang-checkbox:not(:checked) + .lang-slider .lang-label.en { opacity: 0; } /* Hide label under knob */
    .lang-checkbox:not(:checked) + .lang-slider .lang-label.ar { opacity: 1; }
    
    .lang-checkbox:checked + .lang-slider { background: #fef2f2; border-color: #fee2e2; }
    .lang-checkbox:checked + .lang-slider .lang-knob i { color: #ef4444; } /* Reddish for Arabic toggle */
</style>
@endsection

@section('scripts')
<!-- Plyr JS -->
<script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
<script>
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    const contentId       = {{ $content->id }};
    const savedSeconds    = {{ $progress ? (int) $progress->watched_seconds : 0 }};
    const youtubeIdEn     = "{{ $content->youtube_id }}";
    const youtubeIdAr     = "{{ $content->youtube_id_ar }}";
    const saveUrl         = "{{ route('learn.progress.save') }}";
    const csrfToken       = "{{ csrf_token() }}";

    // Initialize Plyr with YouTube provider
    const player = new Plyr('#player', {
        youtube: {
            noCookie: true,
            rel: 0,
            showinfo: 0,
            iv_load_policy: 3,
            modestbranding: 1,
            controls: 0,
            disablekb: 1,
        },
        controls: [
            'play',
            'progress',
            'current-time',
            'duration',
            'mute',
            'volume',
            'settings',
            'fullscreen',
        ],
        settings: ['speed'],
        hideControls: true,
        resetOnEnd: false,
        clickToPlay: true,
        keyboard: { focused: true, global: false },
        tooltips: { controls: true, seek: true },
        captions: { active: false },
    });

    // Seek to saved position once player is ready
    player.on('ready', () => {
        if (isAuthenticated && savedSeconds > 10) {
            player.currentTime = savedSeconds - 5;
        }

        // Append the header blocker so it works in fullscreen too
        const plyrContainer = player.elements.container;
        if (plyrContainer) {
            const blocker = document.createElement('div');
            blocker.className = 'yt-header-blocker';
            plyrContainer.appendChild(blocker);
        }

        // Only start auto-saving for authenticated users
        if (isAuthenticated) {
            setInterval(saveProgress, 10000);
        }
    });

    // Language Toggle Logic
    const langToggle = document.getElementById('langToggle');
    if (langToggle) {
        langToggle.addEventListener('change', function() {
            const currentTime = player.currentTime;
            const isPlaying = player.playing;
            const newId = this.checked ? youtubeIdAr : youtubeIdEn;
            
            if (newId) {
                player.source = {
                    type: 'video',
                    sources: [{
                        src: newId,
                        provider: 'youtube',
                    }],
                };

                // Wait for the new source to be ready before seeking back
                player.once('ready', () => {
                    player.currentTime = currentTime;
                    if (isPlaying) player.play();
                });
            }
        });
    }

    // Only bind progress events for authenticated users
    if (isAuthenticated) {
        player.on('pause',  () => saveProgress());
        player.on('ended',  () => saveProgress(true));
        window.addEventListener('beforeunload', () => saveProgress());
    }

    function saveProgress(forceComplete = false) {
        if (!isAuthenticated) return;
        const duration = player.duration || 0;
        if (duration < 1) return;
        const watched = forceComplete ? Math.floor(duration) : Math.floor(player.currentTime);

        fetch(saveUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ content_id: contentId, watched_seconds: watched, duration_seconds: Math.floor(duration) })
        })
        .then(r => r.json())
        .then(data => {
            const bar   = document.getElementById('progress-bar');
            const label = document.getElementById('progress-label');
            if (bar)   bar.style.width      = data.completion_percent + '%';
            if (label) label.textContent     = Math.round(data.completion_percent) + '%';
        })
        .catch(() => {}); // Silently ignore network errors
    }

    function toggleBookmark(contentId, btn) {
        const icon = btn.querySelector('i');
        fetch(`/bookmarks/${contentId}/toggle`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'added') {
                btn.classList.add('active');
                icon.classList.replace('bi-bookmark', 'bi-bookmark-fill');
            } else {
                btn.classList.remove('active');
                icon.classList.replace('bi-bookmark-fill', 'bi-bookmark');
            }
            btn.style.transform = 'scale(1.2)';
            setTimeout(() => { btn.style.transform = ''; }, 200);
        });
    }
</script>
@endsection
