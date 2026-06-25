@extends('layouts.user')

@section('title', 'Create Your Roadmap — Daleel AI')

@section('content')
<div class="container py-3" id="roadmap-wizard">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            {{-- WIZARD HEADER --}}
            <div id="wizard-header" class="text-center mb-3">
                <div id="step-count" class="text-uppercase text-primary fw-bold mb-2 ls-1" style="font-size: 0.75rem;">Step 1 of 3</div>
                <h2 id="wizard-title" class="fw-900 text-dark">Which tools do you want to learn?</h2>
                <div class="progress mt-4 mx-auto" style="height: 6px; width: 240px; border-radius: 10px; background: #e2e8f0;">
                    <div id="progress-bar" class="progress-bar transition-all" role="progressbar" style="width: 33%; background: #4f46e5;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            {{-- STEP 1: TOOL SELECTION --}}
            <div id="step-1" class="wizard-step">
                
                {{-- Tool Search Bar --}}
                <div class="mb-4 mx-auto" style="max-width: 500px;">
                    <div class="input-group bg-white rounded-pill p-1 border shadow-sm" style="border: 2px solid #e2e8f0 !important;">
                        <span class="input-group-text border-0 bg-transparent ps-3 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input 
                            type="text" 
                            id="tool-search" 
                            class="form-control border-0 bg-transparent" 
                            placeholder="Search tools (e.g. GPT, Zapier, Excel...)"
                            onkeyup="filterTools()"
                            style="box-shadow: none !important;"
                        >
                    </div>
                </div>

                {{-- Compressed Tool Grid --}}
                <div class="row g-2 justify-content-center" id="tools-grid">
                    @foreach($allTools->sortByDesc(fn($t) => in_array($t->id, $selectedIds)) as $index => $tool)
                        @php 
                            $isRecommended = in_array($tool->id, @$selectedIds ?? []);
                            $isVisible = ($index < 12 || $isRecommended);
                        @endphp
                        <div class="col-6 col-md-3 tool-item {{ $isVisible ? '' : 'd-none' }}" 
                             data-name="{{ strtolower($tool->name) }}"
                             data-recommended="{{ $isRecommended ? '1' : '0' }}">
                            <div class="h-100">
                                <input type="checkbox" name="tools[]" value="{{ $tool->id }}" 
                                       id="tool-{{ $tool->id }}" 
                                       data-name="{{ $tool->name }}" 
                                       class="tool-checkbox d-none" 
                                       {{ $isRecommended ? 'checked' : '' }}>
                                <label for="tool-{{ $tool->id }}" class="card tool-card h-100 border transition-all rounded-4 p-2 border-2 cursor-pointer position-relative shadow-sm d-block">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="flex-shrink-0 bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                            @if($tool->logo)
                                                <img src="{{ asset($tool->logo) }}" style="width: 24px; height: 24px; object-fit: contain;">
                                            @else
                                                <i class="bi bi-tools text-muted small"></i>
                                            @endif
                                        </div>
                                        <div class="text-start overflow-hidden">
                                            <h6 class="fw-bold mb-0 text-truncate small" style="font-size: 13px;">{{ $tool->name }}</h6>
                                            @if($isRecommended)
                                                <div class="text-primary fw-bold" style="font-size: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Suggested</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="selection-overlay position-absolute top-0 end-0 p-1">
                                        <i class="bi bi-check-circle-fill text-primary d-none" style="font-size: 14px;"></i>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Browse All Toggle --}}
                <div class="text-center mt-4" id="show-more-tools-wrapper">
                    <button type="button" class="btn btn-sm btn-light rounded-pill px-4 fw-bold text-muted border shadow-sm" onclick="showAllTools()">
                        <i class="bi bi-plus-circle me-1"></i> Browse All Tools
                    </button>
                </div>

                <div class="text-center mt-3 pt-2">
                    <button type="button" onclick="goToStep(2)" class="btn btn-dark rounded-pill px-5 py-3 fw-bold shadow">
                        Step 2: Focus & Level <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
>

            {{-- STEP 2: FOCUS SELECTION --}}
            <div id="step-2" class="wizard-step d-none opacity-0 transition-all">
                <h4 class="text-center mb-4 fw-bold">Select your primary improvement focus:</h4>
                <div id="focus-container" class="row g-3 justify-content-center">
                    {{-- Generated via JS --}}
                    <div class="col-12 text-center py-5" id="focus-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-3 text-muted">AI is generating categories based on your tools...</p>
                    </div>
                </div>
                <div class="text-center mt-3 pt-2 d-flex justify-content-center gap-3">
                    <button type="button" onclick="goToStep(1)" class="btn btn-outline-secondary px-4 py-3 rounded-pill">Back</button>
                    <button type="button" id="btn-next-step-3" onclick="goToStep(3)" class="btn btn-primary px-5 py-3 rounded-pill fw-bold d-none">Next Step <i class="bi bi-arrow-right ms-2"></i></button>
                </div>
            </div>

            {{-- STEP 3: SKILL LEVEL --}}
            <div id="step-3" class="wizard-step d-none opacity-0 transition-all text-center">
                <h4 class="mb-5 fw-bold text-center">What is your current skill level?</h4>
                <div class="row justify-content-center g-4">
                    @foreach(['Beginner' => 'I am new to these tools.', 'Intermediate' => 'I have some basic experience.', 'Advanced' => 'I want to master advanced features.'] as $level => $desc)
                    <div class="col-md-4">
                        <label class="w-100 h-100">
                            <input type="radio" name="level" value="{{ strtolower($level) }}" class="level-radio d-none">
                            <div class="card level-card p-4 transition-all border-2 cursor-pointer shadow-sm">
                                <h5 class="fw-bold mb-2">{{ $level }}</h5>
                                <p class="text-muted small mb-0">{{ $desc }}</p>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
                <div class="text-center mt-3 pt-2 d-flex justify-content-center gap-3">
                    <button type="button" onclick="goToStep(2)" class="btn btn-outline-secondary px-4 py-3 rounded-pill">Back</button>
                    <button type="button" id="btn-generate" onclick="generateRoadmap()" class="btn btn-success px-5 py-3 rounded-pill fw-bold shadow-lg d-none">
                        Generate My Roadmap <i class="bi bi-magic ms-2"></i>
                    </button>
                </div>
            </div>

            {{-- STEP 4: FINAL RESULTS --}}
            <div id="step-4" class="wizard-step d-none opacity-0 transition-all">
                <div id="roadmap-result">
                    {{-- Full Roadmap Rendered Here --}}
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .transition-all { transition: all 0.4s ease; }
    .ls-1 { letter-spacing: 1.5px; }
    .tool-card, .level-card { border: 2px solid #f1f5f9; position: relative; border-radius: 20px; }
    .tool-card:hover, .level-card:hover { transform: translateY(-3px); border-color: #4f46e5; }
    .tool-checkbox:checked + .tool-card, .level-radio:checked + .level-card { border-color: #4f46e5 !important; background: #f5f3ff !important; box-shadow: 0 10px 20px rgba(79, 70, 229, 0.1) !important; }
    .tool-checkbox:checked + .tool-card .bi-check-circle-fill { display: block !important; }
    .cursor-pointer { cursor: pointer; }
    .cursor-pointer { cursor: pointer; }
    .btn-primary { background: #4f46e5 !important; border: none !important; color: #fff !important; }
    .btn-primary:hover { background: #3730a3 !important; }
    .btn-success { background: #10b981 !important; border: none !important; color: #fff !important; }
    .btn-success:hover { background: #059669 !important; }
    .btn-dark { background: #0f172a !important; border: none !important; color: #fff !important; }
</style>

@endsection

@section('scripts')
<script>
    let currentStep = 1;
    const goal = "{{ $goal }}";

    function goToStep(step) {
        document.getElementById(`step-${currentStep}`).classList.add('d-none', 'opacity-0');
        document.getElementById(`step-${step}`).classList.remove('d-none');
        setTimeout(() => document.getElementById(`step-${step}`).classList.remove('opacity-0'), 10);
        
        currentStep = step;
        updateHeader(step);

        if(step === 2) loadFocusCategories();
    }

    function updateHeader(step) {
        const header = document.getElementById('wizard-header');
        if (step === 4) {
            header.classList.add('d-none');
            return;
        }
        
        const titles = { 
            1: "Which tools do you want to learn?", 
            2: "What is your main focus?", 
            3: "What is your skill level?" 
        };
        document.getElementById('wizard-title').innerText = titles[step];
        document.getElementById('step-count').innerText = `Step ${step} of 3`;
        document.getElementById('progress-bar').style.width = (step * 33) + "%";
        header.classList.remove('d-none');
    }

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
            tools.forEach((el, index) => {
                const isRec = el.getAttribute('data-recommended') === '1';
                // Find visible items based on initial logic (index < 12 or recommended)
                // Note: Sorting happens in PHP, so we just check recommended status
                if (isRec) {
                    el.classList.remove('d-none');
                } else {
                    // This is simplified; showAllTools handles full restoration
                    // We'll keep them hidden by default if not recommended
                }
            });
        }
    }

    function showAllTools() {
        document.querySelectorAll('.tool-item').forEach(el => el.classList.remove('d-none'));
        document.getElementById('show-more-tools-wrapper').classList.add('d-none');
        document.getElementById('tool-search').placeholder = "Search across all tools...";
    }

    async function loadFocusCategories() {
        const tools = Array.from(document.querySelectorAll('.tool-checkbox:checked')).map(el => el.getAttribute('data-name'));
        const container = document.getElementById('focus-container');
        document.getElementById('focus-loader').classList.remove('d-none');
        
        try {
            const resp = await fetch("{{ route('roadmap.api.categories') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ goal, tool_names: tools })
            });
            const categories = await resp.json();
            
            let html = '';
            categories.forEach(cat => {
                html += `
                    <div class="col-md-6">
                        <label class="w-100">
                            <input type="radio" name="focus" value="${cat}" class="focus-radio d-none" onchange="document.getElementById('btn-next-step-3').classList.remove('d-none')">
                            <div class="card p-3 border-2 transition-all cursor-pointer text-center">
                                <h6 class="mb-0 fw-semibold">${cat}</h6>
                            </div>
                        </label>
                    </div>`;
            });
            container.innerHTML = html;
            document.getElementById('focus-loader').classList.add('d-none');
        } catch(e) { console.error(e); }
    }

    // Level listener for step 3
    document.addEventListener('change', (e) => {
        if(e.target.classList.contains('level-radio')) {
            document.getElementById('btn-generate').classList.remove('d-none');
        }
    });

    async function generateRoadmap() {
        const tools = Array.from(document.querySelectorAll('.tool-checkbox:checked')).map(el => el.value);
        const focus = document.querySelector('input[name="focus"]:checked').value;
        const level = document.querySelector('input[name="level"]:checked').value;
        
        goToStep(4);
        const resultContainer = document.getElementById('roadmap-result');
        resultContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-3">Building your personal curriculum...</p></div>';

        try {
            const resp = await fetch("{{ route('roadmap.api.generate') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ goal, tools, focus, level })
            });
            const data = await resp.json();
            
            if (data.redirect_url) {
                resultContainer.innerHTML = `
                    <div class="text-center py-5">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-check-lg fs-1"></i>
                        </div>
                        <h3 class="fw-bold">Roadmap Created!</h3>
                        <p class="text-muted">Taking you to your new journey...</p>
                    </div>`;
                
                setTimeout(() => {
                    window.location.href = data.redirect_url;
                }, 1000);
            } else {
                renderRoadmap(data);
            }
        } catch(e) { console.error(e); }
    }

    function renderRoadmap(data) {
        let html = `<div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5">
            <div class="card-header bg-dark p-4 text-white">
                <h4 class="mb-0">Your Custom Roadmap: ${data.title}</h4>
                <div class="mt-2 opacity-75 small">Focus: ${data.focus} | Level: ${data.level.toUpperCase()}</div>
            </div>
            <div class="card-body p-4">`;
        
        data.phases.forEach(phase => {
            html += `<h5 class="fw-bold mt-4 mb-3 text-primary">${phase.name}</h5><div class="row g-3">`;
            phase.videos.forEach(v => {
                html += `
                    <div class="col-md-4">
                        <div class="card border h-100 shadow-sm">
                            <img src="${v.thumbnail_url || 'https://via.placeholder.com/300x180'}" class="card-img-top">
                            <div class="card-body p-3">
                                <h6 class="fw-bold small mb-2">${v.title}</h6>
                                <a href="/learn/${v.id}" class="btn btn-sm btn-outline-primary w-100">Watch Lesson</a>
                            </div>
                        </div>
                    </div>`;
            });
            html += `</div>`;
        });

        html += `</div></div>`;
        document.getElementById('roadmap-result').innerHTML = html;
    }
</script>

<style>
    .focus-radio:checked + .card { border-color: #7c6fff; background: #7c6fff10; }
</style>
@endsection
