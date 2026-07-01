@extends('layouts.user')

@section('title', 'Create Your Roadmap — Daleel AI')

@section('content')
<div class="container py-3" id="roadmap-wizard">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            
            {{-- WIZARD HEADER --}}
            <div id="wizard-header" class="text-center mb-4">
                <div id="step-count" class="text-uppercase text-primary fw-bold mb-2 ls-1" style="font-size: 0.75rem;">Step 1 of 3</div>
                <h2 id="wizard-title" class="fw-900 text-dark" style="letter-spacing: -0.03em;">Which tools do you want to learn?</h2>
                <div class="progress mt-3 mx-auto" style="height: 6px; width: 200px; border-radius: 10px; background: #e2e8f0;">
                    <div id="progress-bar" class="progress-bar transition-all" role="progressbar" style="width: 33%; background: #6366f1;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

            {{-- STEP 1: TOOL SELECTION (Rows layout) --}}
            <div id="step-1" class="wizard-step">
                
                {{-- Tool Search Bar --}}
                <div class="mb-4">
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
                            style="box-shadow: none !important; font-size: 0.9rem;"
                        >
                    </div>
                </div>

                {{-- Vertical list of rows --}}
                <div class="d-flex flex-column gap-2" id="tools-grid">
                    @foreach($allTools->sortByDesc(fn($t) => in_array($t->id, $selectedIds)) as $index => $tool)
                        @php 
                            $isRecommended = in_array($tool->id, @$selectedIds ?? []);
                            $isVisible = ($index < 12 || $isRecommended);
                        @endphp
                        <div class="tool-item {{ $isVisible ? '' : 'd-none' }}" 
                             data-name="{{ strtolower($tool->name) }}"
                             data-recommended="{{ $isRecommended ? '1' : '0' }}">
                            
                            <input type="checkbox" name="tools[]" value="{{ $tool->id }}" 
                                   id="tool-{{ $tool->id }}" 
                                   data-name="{{ $tool->name }}" 
                                   class="tool-checkbox d-none" 
                                   {{ $isRecommended ? 'checked' : '' }}>
                            
                            <label for="tool-{{ $tool->id }}" class="card tool-row-card p-3 border-2 cursor-pointer d-flex flex-row align-items-center justify-content-between rounded-4 shadow-sm mb-0">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="flex-shrink-0 bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border: 1px solid #e2e8f0;">
                                        @if($tool->logo)
                                            <img src="{{ asset($tool->logo) }}" style="width: 26px; height: 26px; object-fit: contain;">
                                        @else
                                            <i class="bi bi-tools text-muted"></i>
                                        @endif
                                    </div>
                                    <div class="text-start">
                                        <h6 class="fw-800 mb-1 text-dark" style="font-size: 0.95rem; margin-bottom: 0;">{{ $tool->name }}</h6>
                                        @if($isRecommended)
                                            <span class="badge bg-primary bg-opacity-10 text-primary fw-800" style="font-size: 0.65rem; text-transform: uppercase;">Suggested</span>
                                        @else
                                            <span class="text-muted small fw-600" style="font-size: 0.72rem;">Include in roadmap</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="tool-select-indicator d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid #cbd5e1; color: transparent; font-size: 0.8rem; font-weight: 800; transition: all 0.2s;">✓</div>
                            </label>
                        </div>
                    @endforeach
                </div>

                {{-- Browse All Toggle --}}
                <div class="text-center mt-4" id="show-more-tools-wrapper">
                    <button type="button" class="btn btn-sm btn-light rounded-pill px-4 fw-bold text-muted border shadow-sm" onclick="showAllTools()">
                        <i class="bi bi-plus-circle me-1"></i> Browse All Tools
                    </button>
                </div>

                <div class="mt-4 pt-2">
                    <button type="button" onclick="goToStep(2)" class="btn btn-dark w-100 py-3 fw-bold rounded-4 shadow">
                        Step 2: Focus & Level <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>

            {{-- STEP 2: FOCUS SELECTION (Rows layout) --}}
            <div id="step-2" class="wizard-step d-none opacity-0 transition-all">
                <h5 class="text-muted mb-4 fw-600 text-center" style="font-size: 0.95rem;">Select your primary improvement goal:</h5>
                
                <div id="focus-container" class="d-flex flex-column gap-2">
                    {{-- Generated via JS --}}
                    <div class="text-center py-5" id="focus-loader">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-3 text-muted fw-600 small">AI is generating goals based on your tools...</p>
                    </div>
                </div>
                
                <div class="mt-4 pt-2 d-flex gap-2">
                    <button type="button" onclick="goToStep(1)" class="btn btn-outline-secondary py-3 rounded-4 fw-bold" style="flex: 1;">Back</button>
                    <button type="button" id="btn-next-step-3" onclick="goToStep(3)" class="btn btn-primary py-3 rounded-4 fw-bold d-none" style="flex: 2;">Next Step <i class="bi bi-arrow-right ms-2"></i></button>
                </div>
            </div>

            {{-- STEP 3: SKILL LEVEL (Rows layout) --}}
            <div id="step-3" class="wizard-step d-none opacity-0 transition-all text-center">
                <h5 class="text-muted mb-4 fw-600 text-center" style="font-size: 0.95rem;">What is your current skill level?</h5>
                
                <div class="d-flex flex-column gap-2 text-start">
                    @foreach(['Beginner' => 'I am new to these tools.', 'Intermediate' => 'I have some basic experience.', 'Advanced' => 'I want to master advanced features.'] as $level => $desc)
                    <div class="col-12">
                        <label class="w-100">
                            <input type="radio" name="level" value="{{ strtolower($level) }}" class="level-radio d-none">
                            <div class="card level-row-card p-3 transition-all border-2 cursor-pointer d-flex flex-row align-items-center gap-3 rounded-4 shadow-sm" style="border: 2px solid #e2e8f0; background: #fff;">
                                <div class="level-circle-select d-flex align-items-center justify-content-center flex-shrink-0" style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid #cbd5e1; transition: all 0.2s;"></div>
                                <div>
                                    <h6 class="fw-800 mb-1 text-dark" style="font-size: 0.95rem; margin-bottom: 0;">{{ $level }}</h6>
                                    <p class="text-muted small mb-0 fw-600" style="font-size: 0.78rem;">{{ $desc }}</p>
                                </div>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4 pt-2 d-flex gap-2">
                    <button type="button" onclick="goToStep(2)" class="btn btn-outline-secondary py-3 rounded-4 fw-bold" style="flex: 1;">Back</button>
                    <button type="button" id="btn-generate" onclick="generateRoadmap()" class="btn btn-success py-3 rounded-4 fw-bold shadow d-none" style="flex: 2;">
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
    .transition-all { transition: all 0.25s ease; }
    .ls-1 { letter-spacing: 1.5px; }

    /* Custom Row Cards Stylings */
    .tool-row-card, .focus-row-card, .level-row-card {
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        transition: all 0.2s ease;
    }
    .tool-row-card:hover, .focus-row-card:hover, .level-row-card:hover {
        transform: translateY(-2px);
        border-color: #6366f1;
        box-shadow: 0 6px 18px rgba(99,102,241,0.06);
    }
    
    /* Checked tool checkbox styling */
    .tool-checkbox:checked + .tool-row-card {
        border-color: #6366f1 !important;
        background: #f5f3ff !important;
    }
    .tool-checkbox:checked + .tool-row-card .tool-select-indicator {
        border-color: #6366f1 !important;
        background: #6366f1 !important;
        color: #fff !important;
    }

    /* Checked focus radio styling */
    .focus-radio:checked + .focus-row-card {
        border-color: #6366f1 !important;
        background: #f5f3ff !important;
    }
    .focus-radio:checked + .focus-row-card .focus-bullet-circle {
        border-color: #6366f1 !important;
        background: #6366f1 !important;
        color: #fff !important;
    }

    /* Checked level radio styling */
    .level-radio:checked + .level-row-card {
        border-color: #6366f1 !important;
        background: #f5f3ff !important;
    }
    .level-radio:checked + .level-row-card .level-circle-select {
        border-color: #6366f1 !important;
        background: #6366f1 !important;
        box-shadow: inset 0 0 0 4px #f5f3ff;
    }

    .cursor-pointer { cursor: pointer; }
    .btn-primary { background: #6366f1 !important; border: none !important; color: #fff !important; }
    .btn-primary:hover { background: #4f46e5 !important; }
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
            2: "What is your main goal?", 
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
                if (isRec) {
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

    function handleFocusSelection(radio) {
        const textarea = document.getElementById('custom-focus');
        if (textarea) textarea.value = '';
        document.getElementById('btn-next-step-3').classList.remove('d-none');
    }

    function handleCustomFocusInput(textarea) {
        document.querySelectorAll('.focus-radio').forEach(r => r.checked = false);
        const nextBtn = document.getElementById('btn-next-step-3');
        if (textarea.value.trim().length > 0) {
            nextBtn.classList.remove('d-none');
        } else {
            nextBtn.classList.add('d-none');
        }
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
                    <div class="col-12 mb-2">
                        <label class="w-100">
                            <input type="radio" name="focus" value="${cat}" class="focus-radio d-none" onchange="handleFocusSelection(this)">
                            <div class="card focus-row-card p-3 border-2 transition-all cursor-pointer d-flex flex-row align-items-center justify-content-between rounded-4 shadow-sm mb-0">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="focus-bullet-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; border-radius: 50%; border: 2px solid #cbd5e1; color: transparent; font-size: 0.8rem; font-weight: 800; transition: all 0.2s;">✓</div>
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">${cat}</h6>
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </div>
                        </label>
                    </div>`;
            });
            
            // Append custom focus text field
            html += `
                <div class="col-12 mt-4 text-start">
                    <div class="card p-3 border border-dashed rounded-4">
                        <h6 class="fw-bold mb-2 small text-muted">Or write a custom learning goal:</h6>
                        <textarea 
                            id="custom-focus" 
                            class="form-control border shadow-none" 
                            rows="2" 
                            placeholder="Describe what specific skills you want to learn... (e.g., 'I want to master financial modeling')" 
                            onkeyup="handleCustomFocusInput(this)"
                            style="font-size: 0.9rem;"
                        ></textarea>
                    </div>
                </div>`;
                
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
        
        let focus = '';
        const customText = document.getElementById('custom-focus') ? document.getElementById('custom-focus').value.trim() : '';
        const checkedRadio = document.querySelector('input[name="focus"]:checked');
        
        if (customText.length > 0) {
            focus = customText;
        } else if (checkedRadio) {
            focus = checkedRadio.value;
        }
        
        if (!focus) {
            alert('Please select a focus or type a custom one.');
            return;
        }
        
        const level = document.querySelector('input[name="level"]:checked').value;
        
        goToStep(4);
        const resultContainer = document.getElementById('roadmap-result');
        resultContainer.innerHTML = `
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden p-5 text-center bg-white">
                <div class="py-4">
                    <div class="spinner-grow text-primary mb-4" style="width: 3.5rem; height: 3.5rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h3 class="fw-bold text-dark mb-2">Building Your Personal Curriculum</h3>
                    <p class="text-muted fs-6 mb-0">Our AI is structuring the path patterns and setting up your journey...</p>
                </div>
            </div>`;

        try {
            const resp = await fetch("{{ route('roadmap.api.generate') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ goal, tools, focus, level })
            });
            const data = await resp.json();
            
            if (data.redirect_url) {
                resultContainer.innerHTML = `
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden p-5 text-center bg-white">
                        <div class="py-4">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px; display: inline-flex !important;">
                                <i class="bi bi-check-lg" style="font-size: 2.2rem;"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-2">Roadmap Created!</h3>
                            <p class="text-muted fs-6 mb-4">Taking you to your new journey...</p>
                            <div class="progress mx-auto" style="height: 4px; width: 120px; border-radius: 10px; background-color: #e9ecef;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 100%;"></div>
                            </div>
                        </div>
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
    .focus-radio:checked + .focus-row-card { border-color: #6366f1; background: #f5f3ff; }
</style>
@endsection
