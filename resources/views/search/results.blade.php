@extends('layouts.user')

@section('title', 'Search Results — Daleel AI')

@section('styles')
<style>
    /* ── Hero ── */
    .results-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        border-radius: 24px;
        padding: 4rem 2rem;
        color: #fff;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }

    /* ── Course / Folder card ── */
    .course-folder-wrap {
        position: relative;
        padding-top: 20px;
        margin-bottom: 50px;
    }
    .folder-stack-layer {
        position: absolute;
        width: 96%;
        height: 100%;
        background: #f1f3f5;
        border: 1px solid #dee2e6;
        border-radius: 35px;
        left: 2%;
        z-index: 1;
        transition: all 0.4s ease;
    }
    .folder-layer-1 { top: -12px; transform: scale(0.98); opacity: 0.7; }
    .folder-layer-2 { top: -24px; transform: scale(0.96); opacity: 0.4; }

    .folder-main {
        position: relative;
        z-index: 5;
        background: #fff;
        border-radius: 35px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .folder-tab {
        position: absolute;
        top: 25px; left: 25px;
        background: rgba(0,0,0,0.85);
        color: #fff;
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 1.2px;
        z-index: 10;
        backdrop-filter: blur(8px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    .folder-counter {
        position: absolute;
        bottom: 25px; right: 25px;
        background: #4f46e5;
        color: #fff;
        padding: 10px 20px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 800;
        box-shadow: 0 10px 25px rgba(79,70,229,0.4);
        z-index: 10;
    }
    .folder-syllabus-preview {
        box-shadow: inset 0 2px 10px rgba(0,0,0,0.02);
    }
    .folder-main:hover .folder-thumbnail-side img {
        transform: scale(1.08);
    }
    .folder-thumbnail-side img {
        transition: transform 0.6s cubic-bezier(0.165,0.84,0.44,1);
    }

    /* ── Glow CTA button ── */
    .glow-button {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;
        border: none !important;
        animation: subtle-glow 3s infinite;
    }
    @keyframes subtle-glow {
        0%   { box-shadow: 0 0 0 0   rgba(79,70,229,0.6); }
        70%  { box-shadow: 0 0 0 15px rgba(79,70,229,0);   }
        100% { box-shadow: 0 0 0 0   rgba(79,70,229,0);   }
    }

    /* ── Single video card ── */
    .video-result-card {
        background: #fff;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        overflow: hidden;
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }
    .video-result-card:hover {
        box-shadow: 0 12px 40px rgba(79,70,229,0.15);
        transform: translateY(-3px);
    }
    .video-thumbnail-wrap {
        position: relative;
        width: 320px;
        min-height: 180px;
        flex-shrink: 0;
        overflow: hidden;
    }
    .video-thumbnail-wrap img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .video-result-card:hover .video-thumbnail-wrap img {
        transform: scale(1.05);
    }
    .video-duration-badge {
        position: absolute;
        bottom: 10px; right: 10px;
        background: rgba(0,0,0,0.8);
        color: #fff;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        backdrop-filter: blur(4px);
    }
    .play-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(79,70,229,0);
        transition: background 0.3s ease;
    }
    .video-result-card:hover .play-overlay {
        background: rgba(79,70,229,0.25);
    }
    .play-overlay i {
        font-size: 3rem;
        color: rgba(255,255,255,0);
        transition: color 0.3s ease, transform 0.3s ease;
        filter: drop-shadow(0 2px 8px rgba(0,0,0,0.4));
    }
    .video-result-card:hover .play-overlay i {
        color: rgba(255,255,255,0.95);
        transform: scale(1.1);
    }

    /* ── Course playlist rows ── */
    .playlist-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 16px;
        border-radius: 12px;
        transition: background 0.2s ease;
        text-decoration: none;
        color: inherit;
    }
    .playlist-item:hover {
        background: #f5f3ff;
    }
    .playlist-item-num {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #ede9fe;
        color: #4f46e5;
        font-size: 12px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .playlist-item-thumb {
        width: 80px;
        height: 48px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
        background: #e2e8f0;
    }
    .playlist-item-title {
        font-size: 14px;
        font-weight: 600;
        color: #1e1b4b;
        line-height: 1.4;
        flex-grow: 1;
    }
    .playlist-item-duration {
        font-size: 12px;
        color: #94a3b8;
        flex-shrink: 0;
    }

    /* ── No-result state ── */
    .empty-state {
        padding: 5rem 2rem;
        text-align: center;
    }
    .empty-icon {
        width: 100px; height: 100px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        font-size: 2.5rem;
        color: #cbd5e1;
    }

    @media (max-width: 768px) {
        .video-thumbnail-wrap { width: 100%; min-height: 200px; }
        .video-result-card { flex-direction: column !important; }
        .folder-thumbnail-side { width: 100% !important; height: 220px; }
        .folder-main { flex-direction: column !important; }
    }
</style>
@section('scripts')
<script>
    let currentStep = 1;
    const goal = "{{ $query }}";

    function goToStep(step) {
        document.getElementById(`step-${currentStep}`).classList.add('d-none', 'opacity-0');
        document.getElementById(`step-${step}`).classList.remove('d-none');
        setTimeout(() => document.getElementById(`step-${step}`).classList.remove('opacity-0'), 10);
        currentStep = step;
        updateHeader(step);
        if(step === 2) loadFocusCategories();
    }

    function updateHeader(step) {
        const titles = { 1: "Which tools do you want to learn?", 2: "What is your main focus?", 3: "What is your skill level?", 4: "Your Learning Roadmap" };
        document.getElementById('wizard-title').innerText = titles[step];
        
        if(step < 4) {
            document.getElementById('step-count').innerText = `Step ${step} of 3`;
            document.getElementById('progress-bar').style.width = (step * 33) + "%";
        } else {
            document.getElementById('step-count').innerHTML = '<span class="badge bg-success rounded-pill px-3 py-1">Roadmap Generated!</span>';
            document.getElementById('progress-bar').style.width = "100%";
        }
    }

    async function loadFocusCategories() {
        const tools = Array.from(document.querySelectorAll('.tool-checkbox:checked')).map(el => el.getAttribute('data-name'));
        const container = document.getElementById('focus-container');
        document.getElementById('focus-loader').classList.remove('d-none');
        
        try {
            const controller = new AbortController();
            const id = setTimeout(() => controller.abort(), 15000); // 15s JS timeout

            const resp = await fetch("{{ route('roadmap.api.categories') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ goal, tool_names: tools }),
                signal: controller.signal
            });
            clearTimeout(id);
            
            if (!resp.ok) throw new Error("API failed");
            const categories = await resp.json();
            
            let html = '';
            categories.forEach(cat => {
                html += `<div class="col-md-6"><label class="w-100 cursor-pointer"><input type="radio" name="focus" value="${cat}" class="focus-radio d-none" onchange="document.getElementById('btn-next-3').classList.remove('d-none')"><div class="card p-3 border-2 transition-all rounded-4 text-center"><h6 class="mb-0 fw-600">${cat}</h6></div></label></div>`;
            });
            container.innerHTML = html;
        } catch(e) { 
            console.error(e); 
            container.innerHTML = '<div class="col-12 text-center text-danger py-4"><i class="bi bi-exclamation-triangle-fill fs-1 mb-2"></i><p>The AI is overloaded right now. Please try again in 10 seconds.</p><button onclick="loadFocusCategories()" class="btn btn-sm btn-outline-danger rounded-pill">Retry</button></div>';
        }
    }

    // Restore the listener for the skill level step
    document.addEventListener('change', (e) => {
        if(e.target.classList.contains('level-radio')) {
            document.getElementById('btn-gen').classList.remove('d-none');
        }
    });

    function filterTools() {
        const query = document.getElementById('tool-search').value.toLowerCase();
        const tools = document.querySelectorAll('.tool-item');
        const showMoreBtn = document.getElementById('show-more-tools-wrapper');
        
        if (query.length > 0) {
            showMoreBtn.classList.add('d-none');
            tools.forEach(el => {
                const name = el.getAttribute('data-name');
                el.classList.toggle('d-none', !name.includes(query));
            });
        } else {
            showMoreBtn.classList.remove('d-none');
            const totalVisible = 0;
            tools.forEach((el, index) => {
                const isRec = el.getAttribute('data-recommended') === '1';
                if (index < 12 || isRec) {
                    el.classList.remove('d-none');
                } else {
                    el.classList.add('d-none');
                }
            });
        }
    }

    function showAllTools() {
        document.querySelectorAll('.tool-item').forEach(el => el.classList.remove('d-none'));
        document.getElementById('show-more-tools-wrapper').classList.add('d-none');
        document.getElementById('tool-search').placeholder = "Search across all tools...";
    }

    async function generateRoadmap() {
        const tools = Array.from(document.querySelectorAll('.tool-checkbox:checked')).map(el => el.value);
        const focus = document.querySelector('input[name="focus"]:checked').value;
        const level = document.querySelector('input[name="level"]:checked').value;
        
        goToStep(4);
        const res = document.getElementById('roadmap-result');
        res.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-3">Building your personal curriculum...</p></div>';

        try {
            const controller = new AbortController();
            const id = setTimeout(() => controller.abort(), 40000); // Increased to 40s

            const resp = await fetch("{{ route('roadmap.api.generate') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ goal, tools, focus, level }),
                signal: controller.signal
            });
            clearTimeout(id);
            if (!resp.ok) throw new Error("Generation failed");

            const data = await resp.json();
            // Redirect to the professional details page
            window.location.href = `/roadmap/${data.id}`;
        } catch(e) { 
            console.error(e); 
            res.innerHTML = '<div class="text-center text-danger py-5"><h5>Failed to generate roadmap.</h5><p>OpenAI or your internet connection might be slow.</p><button onclick="generateRoadmap()" class="btn btn-primary rounded-pill px-4 mt-2">Try Again</button></div>';
        }
    }

    function renderRoadmap(data) {
        let html = `<div class="p-2"><div class="badge bg-primary bg-opacity-10 text-primary mb-3">FOCUS: ${data.focus}</div>`;
        data.phases.forEach(ph => {
            html += `<h5 class="fw-bold mb-3 mt-4">${ph.name}</h5><div class="row g-3">`;
            ph.videos.forEach(v => {
                html += `<div class="col-md-4"><div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden"><img src="${v.thumbnail_url || '/placeholder.jpg'}" class="card-img-top"><div class="card-body p-3"><h6 class="fw-bold small mb-2">${v.title}</h6><a href="/learn/${v.id}" class="btn btn-sm btn-dark w-100 rounded-pill">Watch Now</a></div></div></div>`;
            });
            html += `</div>`;
        });
        html += `</div>`;
        document.getElementById('roadmap-result').innerHTML = html;
    }
</script>

<style>
    .transition-all { transition: all 0.2s ease; }
    .tool-card:hover, .level-card:hover { border-color: #4f46e5; background: #f8fafc; }
    .tool-checkbox:checked + .tool-card, .level-radio:checked + .card, .focus-radio:checked + .card { border-color: #4f46e5; background: #eef2ff; box-shadow: 0 4px 12px rgba(79,70,229,0.1); }
    .tool-checkbox:checked + .tool-card .selection-overlay { opacity: 1; }
    .fw-600 { font-weight: 600; }
    .cursor-pointer { cursor: pointer; }
    .tool-item.d-none { display: none !important; }
</style>
@endsection

@section('content')
<div class="container py-4">

    {{-- ── Hero Banner ── --}}
    <div class="results-hero text-center">
        <div class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 mb-3 fw-700" style="font-size: 11px; letter-spacing: 1px;">
            AI SMART SEARCH
        </div>
        <h1 class="fw-900 display-5 mb-3">Results for "{{ $query }}"</h1>

        @if(!empty($intent))
            <p class="opacity-75 lead mb-4" style="max-width: 600px; margin-inline: auto;">{{ $intent }}</p>
        @endif

        {{-- Search again --}}
        <div class="mx-auto" style="max-width: 600px;">
            <form action="{{ route('search.advanced') }}" method="GET">
                <div class="input-group bg-white rounded-pill p-2 shadow-lg">
                    <input
                        type="text"
                        name="search"
                        class="form-control border-0 bg-transparent ps-4"
                        placeholder="Search another tool or topic..."
                        value="{{ $query }}"
                    >
                    <button class="btn btn-primary rounded-pill px-4" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    {{-- ── AI LOGIC DEBUG (Step-by-Step) ── --}}
    <!-- @if(config('app.debug') && isset($debug))
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-dark text-white py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-800 text-uppercase" style="font-size: 11px; letter-spacing: 2px;">
                                <i class="bi bi-robot me-2"></i> AI SEARCH TRACE
                            </h6>
                            <span class="badge bg-success rounded-pill px-3 py-1 fw-700" style="font-size: 9px;">LIVE DEBUG ACTIVE</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="row g-0">
                            {{-- Step 1: Intent --}}
                            <div class="col-md-4 border-end p-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 12px; font-weight: 900;">1</div>
                                    <h6 class="mb-0 fw-800 text-muted small text-uppercase">Detected Intent</h6>
                                </div>
                                @php 
                                    $displayIntent = $debug['intent'] ?? $intent ?? 'unknown'; 
                                @endphp
                                <div class="p-3 rounded-3 {{ $displayIntent === 'course' ? 'bg-primary' : 'bg-success' }} text-white">
                                    <div class="small opacity-75 fw-600">The AI decided this is:</div>
                                    <div class="h4 mb-0 fw-900 text-capitalize">
                                        <i class="bi {{ $displayIntent === 'course' ? 'bi-collection-play' : 'bi-play-circle' }} me-2"></i>
                                        {{ $displayIntent }}
                                    </div>
                                </div>
                            </div>

                            {{-- Step 2: The List --}}
                            <div class="col-md-4 border-end p-4 bg-light">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 12px; font-weight: 900;">2</div>
                                    <h6 class="mb-0 fw-800 text-muted small text-uppercase">The Titles Used</h6>
                                </div>
                                <p class="small text-muted mb-2">GPT searched through <strong>{{ $debug['candidates_count'] ?? 0 }}</strong> titles from your DB:</p>
                                <div class="p-2 rounded-3 bg-white border font-monospace" style="font-size: 10px; max-height: 120px; overflow-y: auto;">
                                    {{ $debug['full_list'] ?? 'List empty' }}
                                </div>
                            </div>

                            {{-- Step 3: Result --}}
                            <div class="col-md-4 p-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 12px; font-weight: 900;">3</div>
                                    <h6 class="mb-0 fw-800 text-muted small text-uppercase">The Winner</h6>
                                </div>
                                <div class="mb-2">
                                    <span class="small text-muted">GPT picked ID:</span>
                                    <span class="h5 mb-0 fw-900 ms-1">#{{ $debug['picked_id'] ?? '0' }}</span>
                                </div>
                                <div class="small">
                                    <span class="text-muted">Raw AI Response:</span>
                                    <code class="d-block mt-1 p-2 bg-light border rounded">"{{ $debug['raw_gpt'] ?? 'N/A' }}"</code>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(isset($debug['error']))
                        <div class="card-footer bg-warning-subtle border-0 py-2 px-4">
                            <span class="small fw-700 text-warning-emphasis"><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $debug['error'] }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif -->


    {{-- ── Results ── --}}
    <div class="row justify-content: center">
        <div class="col-lg-10 mx-auto">

            {{-- ════════════════════════════════════════
                 PATH A: ROADMAP WIZARD
            ════════════════════════════════════════ --}}
            @if($type === 'roadmap')
                <div id="roadmap-wizard" class="bg-white rounded-4 shadow-sm p-4 p-lg-5 mb-5 border">
                    <div class="text-center mb-5">
                        <div id="step-count" class="text-uppercase text-primary fw-bold mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Step 1 of 3</div>
                        <h2 id="wizard-title" class="fw-bold h2 mb-3">Which tools do you want to learn?</h2>
                        <p id="wizard-subtitle" class="text-muted">For your goal: <span class="text-dark fw-bold italic">"{{ $query }}"</span></p>
                        
                        <div class="progress mt-4 mx-auto" style="height: 6px; width: 240px; border-radius: 10px; background: #f1f5f9;">
                            <div id="progress-bar" class="progress-bar transition-all" role="progressbar" style="width: 33%; background: linear-gradient(135deg, #7c6fff 0%, #a78bfa 100%);"></div>
                        </div>
                    </div>

                    {{-- STEPS --}}
                    <div id="step-1" class="wizard-step">
                        
                        <!-- Tool Search Box -->
                        <div class="mb-4 mx-auto" style="max-width: 500px;">
                            <div class="input-group bg-light rounded-pill p-1 border shadow-sm">
                                <span class="input-group-text border-0 bg-transparent ps-3 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input 
                                    type="text" 
                                    id="tool-search" 
                                    class="form-control border-0 bg-transparent fs-14" 
                                    placeholder="Search tools (e.g. GPT, Zapier, Notion...)"
                                    onkeyup="filterTools()"
                                >
                            </div>
                        </div>

                        <!-- Compressed Tool Grid -->
                        <div class="row g-2 justify-content-center mb-4" id="tools-grid">
                            @foreach($allTools->sortByDesc(fn($t) => in_array($t->id, $selectedIds)) as $index => $tool)
                                @php 
                                    $isRecommended = in_array($tool->id, $selectedIds);
                                    $isVisible = ($index < 12 || $isRecommended);
                                @endphp
                                <div class="col-6 col-md-3 tool-item {{ $isVisible ? '' : 'd-none' }}" 
                                     data-name="{{ strtolower($tool->name) }}"
                                     data-recommended="{{ $isRecommended ? '1' : '0' }}">
                                    <label class="w-100 h-100 position-relative cursor-pointer">
                                        <input type="checkbox" name="tools[]" value="{{ $tool->id }}" data-name="{{ $tool->name }}" class="tool-checkbox d-none" {{ $isRecommended ? 'checked' : '' }}>
                                        <div class="card tool-card h-100 border transition-all rounded-3 p-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="flex-shrink-0 bg-light rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                    @if($tool->logo)
                                                        <img src="{{ asset($tool->logo) }}" style="width: 20px; height: 20px; object-fit: contain;">
                                                    @else
                                                        <i class="bi bi-tools text-muted small"></i>
                                                    @endif
                                                </div>
                                                <div class="text-start overflow-hidden">
                                                    <h6 class="fw-bold mb-0 text-truncate small" style="font-size: 13px;">{{ $tool->name }}</h6>
                                                    @if($isRecommended)
                                                        <div class="text-primary fw-bold" style="font-size: 8px; text-transform: uppercase; letter-spacing: 0.5px;">AI Suggested</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="selection-overlay position-absolute top-0 end-0 p-1 opacity-0">
                                                <i class="bi bi-check-circle-fill text-primary" style="font-size: 12px;"></i>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Show All Toggle -->
                        <div class="text-center mt-2 mb-4" id="show-more-tools-wrapper">
                            <button type="button" class="btn btn-sm btn-light rounded-pill px-4 fw-bold text-muted border" onclick="showAllTools()">
                                <i class="bi bi-plus-circle me-1"></i> Browse All Available Tools
                            </button>
                        </div>

                        <div class="text-center mt-5 border-top pt-4">
                            <button type="button" onclick="goToStep(2)" class="btn btn-dark rounded-pill px-5 py-3 fw-bold shadow-sm">
                                Confirm Selection & Next <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <div id="step-2" class="wizard-step d-none opacity-0 transition-all">
                        <div id="focus-container" class="row g-3 justify-content-center">
                            <div class="text-center py-5" id="focus-loader">
                                <div class="spinner-border text-primary"></div>
                                <p class="mt-3 text-muted">AI is generating focus categories...</p>
                            </div>
                        </div>
                        <div class="text-center mt-5 d-flex justify-content-center gap-3">
                            <button type="button" onclick="goToStep(1)" class="btn btn-link text-muted">Back</button>
                            <button type="button" id="btn-next-3" onclick="goToStep(3)" class="btn btn-dark rounded-pill px-5 py-3 d-none">Next Step</button>
                        </div>
                    </div>

                    <div id="step-3" class="wizard-step d-none opacity-0 transition-all">
                        <div class="row g-4 justify-content-center text-center">
                            @foreach(['Beginner' => 'I am starting out', 'Intermediate' => 'I have some skills', 'Advanced' => 'I want to master it'] as $l => $d)
                                <div class="col-md-4">
                                    <label class="w-100 h-100 cursor-pointer">
                                        <input type="radio" name="level" value="{{ strtolower($l) }}" class="level-radio d-none">
                                        <div class="card p-4 border-2 transition-all rounded-4">
                                            <h6 class="fw-bold mb-1">{{ $l }}</h6>
                                            <div class="small text-muted">{{ $d }}</div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-5 d-flex justify-content-center gap-3">
                            <button type="button" onclick="goToStep(2)" class="btn btn-link text-muted">Back</button>
                            <button type="button" id="btn-gen" onclick="generateRoadmap()" class="btn btn-primary rounded-pill px-5 py-3 fw-bold d-none shadow-sm" style="background: linear-gradient(135deg, #7c6fff 0%, #a78bfa 100%); border:none;">Generate My Roadmap</button>
                        </div>
                    </div>

                    <div id="step-4" class="wizard-step d-none opacity-0 transition-all">
                        <div id="roadmap-result"></div>
                    </div>
                </div>

            {{-- ════════════════════════════════════════
                 PATH B: SINGLE VIDEO
            ════════════════════════════════════════ --}}
            @elseif($type === 'video')

                <div class="d-flex align-items-center gap-2 mb-4">
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-700">
                        <i class="bi bi-play-circle-fill me-1"></i> BEST MATCH VIDEO
                    </span>
                    <span class="text-muted small">We found the most relevant lesson for your search</span>
                </div>

                <a href="{{ route('learn.watch', $video->id) }}" class="text-decoration-none">
                    <div class="video-result-card d-flex">

                        {{-- Thumbnail --}}
                        <div class="video-thumbnail-wrap">
                            <img
                                src="{{ $video->thumbnail_url ?? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800' }}"
                                alt="{{ $video->title }}"
                            >
                            <div class="play-overlay">
                                <i class="bi bi-play-circle-fill"></i>
                            </div>
                            @if($video->duration_label ?? false)
                                <div class="video-duration-badge">{{ $video->duration_label }}</div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="p-4 p-lg-5 flex-grow-1 d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 fw-700" style="font-size: 11px;">
                                        SINGLE LESSON
                                    </span>
                                    @if($video->course)
                                        <span class="text-muted small">
                                            <i class="bi bi-collection-play me-1"></i>
                                            Part of: {{ $video->course->title }}
                                        </span>
                                    @endif
                                </div>

                                <h2 class="fw-800 mb-3" style="color: #1e1b4b; font-size: 1.5rem; line-height: 1.3;">
                                    {{ $video->title }}
                                </h2>

                                @if($video->description)
                                    <p class="text-muted mb-4" style="font-size: 0.95rem; line-height: 1.7;">
                                        {{ Str::limit($video->description, 160) }}
                                    </p>
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <span class="btn btn-dark rounded-pill px-5 py-2 fw-800 shadow-sm">
                                    Watch Now <i class="bi bi-play-fill ms-1"></i>
                                </span>
                                @if($video->course)
                                    <a
                                        href="{{ route('course.view', $video->course->id) }}"
                                        class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-700 small"
                                        onclick="event.stopPropagation();"
                                    >
                                        <i class="bi bi-collection-play me-1"></i> View Full Course
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                </a>

            {{-- ════════════════════════════════════════
                 PATH B: FULL COURSE (Playlist)
            ════════════════════════════════════════ --}}
            @elseif($type === 'course')

                <div class="d-flex align-items-center gap-2 mb-4">
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-700">
                        <i class="bi bi-collection-play-fill me-1"></i> COURSE FOUND
                    </span>
                    <span class="text-muted small">Complete course with all lessons — like a YouTube playlist</span>
                </div>

                <div class="course-folder-wrap">
                    <div class="folder-stack-layer folder-layer-1"></div>
                    <div class="folder-stack-layer folder-layer-2"></div>

                    <div class="folder-main d-flex align-items-stretch shadow-lg" style="flex-direction: row;">

                        {{-- Left: Thumbnail --}}
                        <div class="folder-thumbnail-side position-relative" style="width: 360px; flex-shrink: 0;">
                            <img
                                src="{{ $course->thumbnail ?? 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800' }}"
                                class="w-100 h-100 object-fit-cover"
                                alt="{{ $course->title }}"
                            >
                            <div class="folder-tab">COURSE BUNDLE</div>
                            <div class="folder-counter">
                                <i class="bi bi-collection-play-fill me-1"></i>
                                {{ $course->contents->count() }} VIDEOS
                            </div>
                        </div>

                        {{-- Right: Course meta + playlist --}}
                        <div class="p-4 p-lg-5 flex-grow-1 bg-white d-flex flex-column">

                            <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                                <span class="badge bg-dark rounded-pill px-3 py-1 fw-700" style="font-size: 10px; letter-spacing: 0.1em;">
                                    MASTERCLASS SERIES
                                </span>
                                <span class="text-muted small">{{ $course->contents->count() * 15 }}+ min of content</span>
                            </div>

                            <h2 class="fw-900 mb-2" style="color: #1e1b4b; font-size: 1.75rem;">
                                {{ $course->title }}
                            </h2>

                            @if($course->description)
                                <p class="text-muted mb-4" style="font-size: 0.95rem; line-height: 1.7;">
                                    {{ Str::limit($course->description, 160) }}
                                </p>
                            @endif

                            {{-- ── Playlist preview ── --}}
                            <div class="folder-syllabus-preview flex-grow-1 mb-4 p-3 rounded-4 bg-light border"
                                 style="max-height: 300px; overflow-y: auto; border-color: #e9ecef !important;">

                                <p class="text-uppercase fw-800 text-muted mb-3 px-2"
                                   style="font-size: 10px; letter-spacing: 1px;">
                                    <i class="bi bi-list-ul me-1"></i> Course Playlist — {{ $course->contents->count() }} Lessons
                                </p>

                                @foreach($course->contents as $index => $lesson)
                                    <a href="{{ route('learn.watch', $lesson->id) }}" class="playlist-item">
                                        <div class="playlist-item-num">{{ $index + 1 }}</div>
                                        @if($lesson->thumbnail_url ?? false)
                                            <img
                                                src="{{ $lesson->thumbnail_url }}"
                                                class="playlist-item-thumb"
                                                alt="{{ $lesson->title }}"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="playlist-item-thumb d-flex align-items-center justify-content-center bg-light border rounded-2">
                                                <i class="bi bi-play-circle text-muted" style="font-size: 1.2rem;"></i>
                                            </div>
                                        @endif
                                        <span class="playlist-item-title">{{ $lesson->title }}</span>
                                        <span class="playlist-item-duration">
                                            {{ $lesson->duration_label ?? '~15m' }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>

                            {{-- CTA --}}
                            <div class="d-flex gap-3 align-items-center flex-wrap">
                                <a href="{{ route('course.view', $course->id) }}"
                                   class="btn btn-primary rounded-pill px-5 py-3 fw-900 shadow-lg glow-button"
                                   style="font-size: 1.05rem;">
                                    Start This Course <i class="bi bi-folder-check ms-2"></i>
                                </a>
                                <div class="text-muted small fw-600">
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    {{ $course->contents->count() }} lessons included
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            {{-- ════════════════════════════════════════
                 PATH C: NO RESULTS
            ════════════════════════════════════════ --}}
            @elseif($type === 'none')

                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <h2 class="fw-800 mb-3" style="color: #1e1b4b;">No matches found for "{{ $query }}"</h2>
                    <p class="text-muted mb-2" style="max-width: 480px; margin-inline: auto;">
                        Our AI couldn't find a relevant video or course for this search.
                        Try broader terms or browse the full library.
                    </p>
                    <p class="text-muted small mb-5">
                        Try: <strong>ChatGPT</strong>, <strong>Email automation</strong>, <strong>Notion AI</strong>, <strong>Marketing</strong>
                    </p>
                    <a href="{{ route('learn.explore') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-800 shadow">
                        <i class="bi bi-compass me-2"></i>Explore the Full Library
                    </a>
                </div>

            @endif

        </div>
    </div>

</div>
@endsection