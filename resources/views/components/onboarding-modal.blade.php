<div id="onboardingModal" class="onboarding-overlay" style="display: none;">
    <!-- Video Player Modal -->
    <div id="videoModal" class="video-modal-overlay" style="display: none;">
        <div class="video-modal-content">
            <button class="close-video" onclick="closeOnboardingVideo()">&times;</button>
            <div class="video-wrapper">
                <iframe id="onboardingIframe" width="100%" height="100%" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <div class="onboarding-card">
        <div id="onboardingSteps">
            <!-- Step 0: Welcome -->
            <div class="onboarding-step active" data-step="0">
                <div class="onboarding-content text-center">
                    <h2 class="fw-800 mb-3">Welcome to Daleel AI</h2>
                    <p class="text-muted mb-5 px-lg-5">Complete this brief configuration to unlock a learning experience tailored specifically to your professional goals.</p>

                    <!-- Professional Estimation Tag -->
                    <div class="mb-5">
                        <div class="d-inline-flex align-items-center gap-3 bg-light border rounded-pill px-4 py-2">
                            <span class="d-flex align-items-center gap-2 text-primary fw-800 small">
                                <i class="bi bi-clock"></i> Est. 1 min
                            </span>
                            <div class="vr opacity-25"></div>
                            <span class="text-muted small fw-700">5 Simple Steps</span>
                        </div>
                    </div>

                    <!-- Intro Video Preview -->
                    <div class="video-preview-container mb-5">
                        <div class="aspect-ratio-16-9 rounded-4 overflow-hidden shadow-sm border position-relative" style="max-width: 400px; margin: 0 auto;">
                            <img src="https://img.youtube.com/vi/KcbXKUR7-a0/maxresdefault.jpg" class="w-100 h-100 object-fit-cover opacity-75" alt="Introduction Video">
                            <div class="overlay-play bg-dark bg-opacity-25">
                                <button class="play-btn shadow-sm" onclick="openOnboardingVideo()">
                                    <i class="bi bi-play-fill"></i>
                                </button>
                            </div>
                        </div>
                        <p class="small text-muted mt-3 fw-medium">
                            <i class="bi bi-info-circle me-1"></i>
                            Quick overview: How your AI Mentor works
                        </p>
                    </div>

                    <div class="d-grid pt-2">
                        <button class="btn btn-primary btn-lg rounded-pill fw-800 py-3 step-next shadow-lg">
                            Get Started
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 1: Learning Topics -->
            <div class="onboarding-step" data-step="1">
                <div class="onboarding-content">
                    <div class="mb-4">
                        <div class="onboarding-progress mb-3" aria-label="Onboarding progress">
                            <div class="onboarding-progress-meta"><span>Learning interests</span><strong>1 / 5</strong></div>
                            <div class="onboarding-progress-track"><div class="onboarding-progress-bar" style="width: 20%;"></div></div>
                            <div class="onboarding-progress-steps" aria-hidden="true">
                                <span class="active"></span><span></span><span></span><span></span><span></span>
                            </div>
                        </div>
                        <h3 class="fw-800">What do you want to learn?</h3>
                        <p class="text-muted small">Select all that interest you.</p>
                    </div>

                    @php
                        $learningInterestOptions = [
                            'AI Strategy',
                            'Productivity',
                            'Content Creation',
                            'Development',
                            'Data & Analytics',
                            'Automation',
                            'Design',
                            'Business & Marketing',
                        ];
                    @endphp

                    <div class="row g-3 item-selection overflow-y-auto" data-type="multi" style="max-height: 300px;">
                        @foreach($learningInterestOptions as $topic)
                            <div class="col-6 col-md-4">
                                <div class="selection-item" data-value="{{ $topic }}">
                                    <span class="item-label">{{ $topic }}</span>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-12">
                            <label class="other-entry">
                                <span class="fw-700 small">Other</span>
                                <input type="text" id="otherInterestInput" class="form-control" placeholder="Write another learning interest">
                            </label>
                        </div>
                    </div>

                    <div class="mt-5 d-flex gap-3">
                        <button class="btn btn-light rounded-4 fw-800 py-3 px-4 step-prev"><i class="bi bi-arrow-left"></i></button>
                        <button class="btn btn-primary flex-grow-1 btn-lg rounded-4 fw-800 py-3 step-next disabled" id="next-topics">Continue</button>
                    </div>
                </div>
            </div>

            <!-- Step 2: Goal -->
            <div class="onboarding-step" data-step="2">
                <div class="onboarding-content">
                    <div class="mb-4">
                        <div class="onboarding-progress mb-3" aria-label="Onboarding progress">
                            <div class="onboarding-progress-meta"><span>Main goal</span><strong>2 / 5</strong></div>
                            <div class="onboarding-progress-track"><div class="onboarding-progress-bar" style="width: 40%;"></div></div>
                            <div class="onboarding-progress-steps" aria-hidden="true">
                                <span class="complete"></span><span class="active"></span><span></span><span></span><span></span>
                            </div>
                        </div>
                        <h3 class="fw-800">What is your main goal?</h3>
                        <p class="text-muted small">Choose the one that best describes your objective.</p>
                    </div>

                    <div class="list-selection overflow-y-auto" data-type="single" style="max-height: 300px;">
                        @foreach([
                            'Get a job or improve my career',
                            'Build a project or startup idea',
                            'Improve productivity at work',
                            'Learn AI tools for daily tasks',
                            'Learn to code',
                            'Train my team or company',
                        ] as $goal)
                            <div class="list-selection-item" data-value="{{ $goal }}">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="radio-circle"></div>
                                    <span class="fw-700">{{ $goal }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 d-flex gap-3">
                        <button class="btn btn-light rounded-4 fw-800 py-3 px-4 step-prev"><i class="bi bi-arrow-left"></i></button>
                        <button class="btn btn-primary flex-grow-1 btn-lg rounded-4 fw-800 py-3 step-next disabled">Continue</button>
                    </div>
                </div>
            </div>

            <!-- Step 3: Level -->
            <div class="onboarding-step" data-step="3">
                <div class="onboarding-content">
                    <div class="mb-4">
                        <div class="onboarding-progress mb-3" aria-label="Onboarding progress">
                            <div class="onboarding-progress-meta"><span>Skill level</span><strong>3 / 5</strong></div>
                            <div class="onboarding-progress-track"><div class="onboarding-progress-bar" style="width: 60%;"></div></div>
                            <div class="onboarding-progress-steps" aria-hidden="true">
                                <span class="complete"></span><span class="complete"></span><span class="active"></span><span></span><span></span>
                            </div>
                        </div>
                        <h3 class="fw-800">What is your level?</h3>
                        <p class="text-muted small">This helps us recommend the right difficulty.</p>
                    </div>

                    <div class="level-selection" data-type="single">
                        @foreach([
                            'Beginner' => "I'm new to AI tools and need guided basics.",
                            'Intermediate' => 'I use some tools and want practical workflows.',
                            'Advanced' => 'I want deeper use cases, automation, and advanced techniques.',
                        ] as $level => $description)
                            <div class="level-card" data-value="{{ $level }}">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h5 class="fw-800 mb-0">{{ $level }}</h5>
                                    <div class="check-circle"><i class="bi bi-check-lg"></i></div>
                                </div>
                                <p class="text-muted small mb-0">{{ $description }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 d-flex gap-3">
                        <button class="btn btn-light rounded-4 fw-800 py-3 px-4 step-prev"><i class="bi bi-arrow-left"></i></button>
                        <button class="btn btn-primary flex-grow-1 btn-lg rounded-4 fw-800 py-3 step-next disabled">Continue</button>
                    </div>
                </div>
            </div>

            <!-- Step 4: Apps -->
            <div class="onboarding-step" data-step="4">
                <div class="onboarding-content">
                    <div class="mb-3">
                        <div class="onboarding-progress mb-3" aria-label="Onboarding progress">
                            <div class="onboarding-progress-meta"><span>Most used apps</span><strong>4 / 5</strong></div>
                            <div class="onboarding-progress-track"><div class="onboarding-progress-bar" style="width: 80%;"></div></div>
                            <div class="onboarding-progress-steps" aria-hidden="true">
                                <span class="complete"></span><span class="complete"></span><span class="complete"></span><span class="active"></span><span></span>
                            </div>
                        </div>
                        <h3 class="fw-800">Most used apps</h3>
                        <p class="text-muted small mb-0">Select the tools you use every day.</p>
                    </div>

                    <div class="mb-3">
                        <div class="input-group bg-white rounded-pill p-1 border shadow-sm onboarding-tool-search">
                            <span class="input-group-text border-0 bg-transparent ps-3 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="toolSearchInput" class="form-control border-0 bg-transparent" placeholder="Search tools from database..." style="box-shadow: none !important; font-size: 0.9rem;">
                        </div>
                    </div>

                    <div id="toolSelectedCount" class="text-primary fw-700 small mb-2" style="display: none;">
                        <i class="bi bi-check-circle-fill me-1"></i><span id="toolCountText">0</span> selected
                    </div>

                    <div id="toolsGrid" class="onboarding-tools-grid" data-type="multi">
                        @forelse($tools as $index => $tool)
                            <div class="tool-chip onboarding-tool-item {{ $index < 10 ? '' : 'd-none' }}"
                                 data-value="{{ $tool->name }}"
                                 data-search="{{ strtolower($tool->name . ' ' . ($tool->description ?? '')) }}"
                                 data-initial-visible="{{ $index < 10 ? '1' : '0' }}">
                                <div class="onboarding-tool-row">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="onboarding-tool-logo">
                                            @if($tool->logo)
                                                <img src="{{ asset($tool->logo) }}" alt="">
                                            @else
                                                <i class="bi bi-tools text-muted"></i>
                                            @endif
                                        </div>
                                        <div class="text-start">
                                            <h6>{{ $tool->name }}</h6>
                                            <span>{{ $tool->description ?: 'Include in your Daleel workflow' }}</span>
                                        </div>
                                    </div>
                                    <div class="tool-select-indicator"><i class="bi bi-check-lg"></i></div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-tools text-muted" style="font-size: 1.5rem;"></i>
                                <p class="text-muted small mt-2 mb-0">No active tools found in the database.</p>
                            </div>
                        @endforelse
                    </div>

                    <div id="noToolsMessage" class="text-center py-4" style="display: none;">
                        <i class="bi bi-search text-muted" style="font-size: 1.5rem;"></i>
                        <p class="text-muted small mt-2 mb-0">No tools found matching "<span id="noToolsQuery"></span>"</p>
                    </div>

                    <div class="mt-4 d-flex gap-3">
                        <button class="btn btn-light rounded-4 fw-800 py-3 px-4 step-prev"><i class="bi bi-arrow-left"></i></button>
                        <button class="btn btn-primary flex-grow-1 btn-lg rounded-4 fw-800 py-3 step-next">Continue</button>
                    </div>
                </div>
            </div>

            <!-- Step 5: Extension -->
            <div class="onboarding-step" data-step="5">
                <div class="onboarding-content text-center">
                    <div class="onboarding-progress mb-3" aria-label="Onboarding progress">
                        <div class="onboarding-progress-meta"><span>Connect workflow</span><strong>5 / 5</strong></div>
                        <div class="onboarding-progress-track"><div class="onboarding-progress-bar" style="width: 100%;"></div></div>
                        <div class="onboarding-progress-steps" aria-hidden="true">
                            <span class="complete"></span><span class="complete"></span><span class="complete"></span><span class="complete"></span><span class="active"></span>
                        </div>
                    </div>
                    <h3 class="fw-800 mb-2">Connect Daleel to Your Workflow</h3>
                    <p class="text-muted mb-4">Use Daleel across your browser, desktop, and soon mobile to get smarter recommendations and real-time guidance.</p>

                    <div class="workflow-connect-grid mb-4">
                        <article class="workflow-connect-card">
                            <div class="workflow-connect-card-header">
                                <i class="bi bi-browser-chrome"></i>
                                <h5>Chrome Extension</h5>
                            </div>
                            <p class="mb-3">Get AI learning recommendations synced directly into your browser tab.</p>
                            <a href="https://chromewebstore.google.com/detail/daleel-mentor/bpkbkfdbanbdlfmkmgcdkhlobfdifhpi" target="_blank" class="btn btn-primary rounded-4">
                                <i class="bi bi-plus-lg"></i> Add to Chrome
                            </a>
                        </article>
                        <article class="workflow-connect-card">
                            <div class="workflow-connect-card-header">
                                <i class="bi bi-laptop"></i>
                                <h5>Desktop App</h5>
                            </div>
                            <p class="mb-3">Get real-time guidance directly across your desktop software.</p>
                            <button type="button" class="btn btn-outline-primary rounded-4">
                                <i class="bi bi-download"></i> Download App
                            </button>
                        </article>
                        <article class="workflow-connect-card">
                            <span class="coming-soon-badge">Coming Soon</span>
                            <div class="workflow-connect-card-header">
                                <i class="bi bi-phone"></i>
                                <h5>Mobile App</h5>
                            </div>
                            <p class="mb-3">Track your focus score and access quick learning paths on the go.</p>
                            <button type="button" class="btn btn-light rounded-4" disabled>
                                <i class="bi bi-bell"></i> Notify Me
                            </button>
                        </article>
                    </div>

                    <div class="mt-5 d-flex gap-3">
                        <button class="btn btn-light rounded-4 fw-800 py-3 px-4 step-prev"><i class="bi bi-arrow-left"></i></button>
                        <button class="btn btn-primary flex-grow-1 btn-lg rounded-4 fw-800 py-3" id="finishOnboarding">Skip & Get Started</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .onboarding-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.86), rgba(30, 41, 59, 0.78));
        backdrop-filter: blur(14px);
        z-index: 10000;
        display: grid;
        place-items: center;
        padding: 24px;
    }

    .onboarding-card {
        background: white;
        width: 100%;
        max-width: 860px;
        max-height: calc(100vh - 48px);
        border: 1px solid rgba(226, 232, 240, 0.95);
        border-radius: 28px;
        box-shadow: 0 32px 90px rgba(2, 6, 23, 0.38);
        overflow: hidden;
        position: relative;
    }

    .onboarding-step {
        display: none;
        max-height: calc(100vh - 48px);
        padding: 36px 40px;
        overflow-y: auto;
        animation: slideIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .onboarding-step.active {
        display: block;
    }

    .onboarding-content {
        min-height: min(640px, calc(100vh - 120px));
        display: flex;
        flex-direction: column;
    }

    .onboarding-content > .d-flex:last-child,
    .onboarding-content > .d-grid:last-child {
        position: sticky;
        bottom: -36px;
        z-index: 2;
        margin-left: -40px;
        margin-right: -40px;
        margin-bottom: -36px;
        padding: 18px 40px 28px;
        background: linear-gradient(180deg, rgba(255,255,255,0.78), #fff 32%);
        border-top: 1px solid #eef2f7;
    }

    .onboarding-progress {
        width: 100%;
        max-width: none;
        padding: 14px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        background:
            radial-gradient(circle at top left, rgba(99, 102, 241, 0.12), transparent 34%),
            linear-gradient(180deg, #ffffff, #f8fafc);
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
    }

    .onboarding-progress-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
        color: #64748b;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .onboarding-progress-meta strong {
        padding: 4px 9px;
        border: 1px solid rgba(99, 102, 241, 0.16);
        border-radius: 999px;
        background: #eef2ff;
        color: var(--primary);
        font-size: 0.68rem;
        letter-spacing: 0.04em;
    }

    .onboarding-progress-track {
        position: relative;
        height: 10px;
        overflow: hidden;
        border-radius: 999px;
        background: #e2e8f0;
        box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.08);
    }

    .onboarding-progress-bar {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #22c55e, #14b8a6, #6366f1);
        box-shadow: 0 0 18px rgba(99, 102, 241, 0.35);
        transition: width 0.25s ease;
    }

    .onboarding-progress-steps {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
        margin-top: 10px;
    }

    .onboarding-progress-steps span {
        height: 4px;
        border-radius: 999px;
        background: #e2e8f0;
    }

    .onboarding-progress-steps span.complete {
        background: #14b8a6;
    }

    .onboarding-progress-steps span.active {
        background: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .welcome-icon {
        width: 80px;
        height: 80px;
        background: var(--primary);
        color: white;
        border-radius: 24px;
        display: grid;
        place-items: center;
        margin: 0 auto;
        font-size: 2.5rem;
        box-shadow: 0 20px 40px rgba(79, 70, 229, 0.3);
    }

    .aspect-ratio-16-9 {
        aspect-ratio: 16/9;
    }

    .overlay-play {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.3);
        display: grid;
        place-items: center;
        transition: 0.3s;
    }

    .play-btn {
        width: 60px;
        height: 60px;
        background: white;
        border-radius: 50%;
        border: none;
        color: var(--primary);
        font-size: 1.8rem;
        display: grid;
        place-items: center;
        transition: 0.3s;
        padding-left: 5px;
    }

    .play-btn:hover {
        transform: scale(1.15);
        background: var(--primary);
        color: white;
    }

    .selection-item {
        border: 2px solid #f1f5f9;
        border-radius: 16px;
        padding: 16px 12px;
        text-align: center;
        cursor: pointer;
        transition: 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .selection-item .item-icon {
        font-size: 1.8rem;
        margin-bottom: 12px;
        color: var(--text-muted);
        transition: 0.2s;
    }

    .selection-item .item-label {
        font-weight: 700;
        font-size: 0.85rem;
        color: var(--text-main);
    }

    .selection-item:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
    }

    .selection-item.selected {
        border-color: var(--primary);
        background: rgba(79, 70, 229, 0.05);
    }

    .selection-item.selected .item-icon {
        color: var(--primary);
        transform: scale(1.1);
    }

    .list-selection-item {
        border: 2px solid #f1f5f9;
        border-radius: 14px;
        padding: 15px 18px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: 0.2s;
    }

    .list-selection-item:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
    }

    .list-selection-item.selected {
        border-color: var(--primary);
        background: rgba(79, 70, 229, 0.05);
    }

    .radio-circle {
        width: 20px;
        height: 20px;
        border: 2px solid #cbd5e1;
        border-radius: 50%;
        transition: 0.2s;
        position: relative;
    }

    .list-selection-item.selected .radio-circle {
        border-color: var(--primary);
    }

    .list-selection-item.selected .radio-circle::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 10px;
        height: 10px;
        background: var(--primary);
        border-radius: 50%;
    }

    .level-card {
        border: 2px solid #f1f5f9;
        border-radius: 16px;
        padding: 18px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: 0.2s;
    }

    .level-card:hover {
        background: #f8fafc;
        border-color: #e2e8f0;
    }

    .level-card.selected {
        border-color: var(--primary);
        background: rgba(79, 70, 229, 0.05);
    }

    .check-circle {
        width: 24px;
        height: 24px;
        border: 2px solid #cbd5e1;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 0.8rem;
        color: white;
        transition: 0.2s;
    }

    .level-card.selected .check-circle {
        background: var(--primary);
        border-color: var(--primary);
    }

    .other-entry {
        display: grid;
        gap: 8px;
        padding: 14px;
        border: 2px dashed #dbe3ef;
        border-radius: 18px;
        background: #f8fafc;
    }

    .other-entry input {
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 600;
        padding: 11px 14px;
    }

    .other-entry input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }

    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }

    .btn-lg {
        padding: 14px 24px;
    }

    .item-selection.overflow-y-auto,
    .list-selection.overflow-y-auto,
    .tool-category-list.overflow-y-auto {
        max-height: none !important;
        overflow-y: visible !important;
    }

    /* Custom Scrollbar for the lists */
    .overflow-y-auto::-webkit-scrollbar {
        width: 5px;
    }
    .overflow-y-auto::-webkit-scrollbar-track {
        background: transparent;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .onboarding-tool-search {
        border: 2px solid #e2e8f0 !important;
    }

    /* Roadmap-style tool row */
    .onboarding-tools-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .onboarding-tools-other {
        grid-column: 1 / -1;
    }

    .tool-chip {
        cursor: pointer;
        user-select: none;
        min-width: 0;
    }

    .onboarding-tool-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        width: 100%;
        min-height: 76px;
        padding: 12px 14px;
        border: 2px solid #e2e8f0;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        transition: all 0.2s ease;
    }

    .tool-chip:hover .onboarding-tool-row {
        border-color: #cbd5e1;
        background: #f8fafc;
        transform: translateY(-1px);
    }

    .tool-chip.selected .onboarding-tool-row {
        border-color: var(--primary);
        background: rgba(79, 70, 229, 0.06);
        box-shadow: 0 10px 24px rgba(79, 70, 229, 0.12);
    }

    .onboarding-tool-logo {
        flex: 0 0 auto;
        width: 40px;
        height: 40px;
        display: grid;
        place-items: center;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #f8fafc;
    }

    .onboarding-tool-logo img {
        width: 24px;
        height: 24px;
        object-fit: contain;
    }

    .onboarding-tool-row h6 {
        margin: 0 0 2px;
        color: #0f172a;
        font-size: 0.9rem;
        font-weight: 800;
    }

    .onboarding-tool-row span {
        display: -webkit-box;
        max-width: 230px;
        overflow: hidden;
        color: #64748b;
        font-size: 0.76rem;
        font-weight: 600;
        line-height: 1.35;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }

    .tool-select-indicator {
        flex: 0 0 auto;
        width: 24px;
        height: 24px;
        display: grid;
        place-items: center;
        border: 2px solid #cbd5e1;
        border-radius: 50%;
        color: transparent;
        font-size: 0.78rem;
        transition: all 0.2s ease;
    }

    .tool-chip.selected .tool-select-indicator {
        border-color: var(--primary);
        background: var(--primary);
        color: #fff;
    }

    .tool-chip.hidden {
        display: none;
    }

    .workflow-connect-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        text-align: left;
    }

    .workflow-connect-card {
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 10px;
        min-height: 210px;
        padding: 18px;
        border: 2px solid #eef2f7;
        border-radius: 18px;
        background: #fff;
    }

    .workflow-connect-card > i {
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border-radius: 14px;
        background: #eef2ff;
        color: var(--primary);
        font-size: 1.35rem;
    }

    .workflow-connect-card h5 {
        margin: 0;
        font-weight: 800;
    }

    .workflow-connect-card p {
        flex: 1;
        margin: 0;
        color: #64748b;
        font-size: 0.82rem;
        line-height: 1.45;
    }

    .workflow-connect-card .btn {
        white-space: normal;
        text-align: center;
    }

    .coming-soon-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        padding: 4px 8px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #64748b;
        font-size: 0.64rem;
        font-weight: 800;
        text-transform: uppercase;
    }

    @media (max-width: 767px) {
        .onboarding-overlay {
            padding: 12px;
        }

        .onboarding-card {
            max-height: calc(100vh - 24px);
            border-radius: 22px;
        }

        .onboarding-step {
            max-height: calc(100vh - 24px);
            padding: 26px 22px;
        }

        .onboarding-content {
            min-height: calc(100vh - 76px);
        }

        .onboarding-content > .d-flex:last-child,
        .onboarding-content > .d-grid:last-child {
            bottom: -26px;
            margin-left: -22px;
            margin-right: -22px;
            margin-bottom: -26px;
            padding: 14px 22px 22px;
        }

        .onboarding-tools-grid {
            grid-template-columns: 1fr;
        }

        .onboarding-tool-row span {
            max-width: 100%;
        }

        .workflow-connect-grid {
            grid-template-columns: 1fr;
        }

        .workflow-connect-card {
            min-height: auto;
        }
    }

    #toolSearchInput:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12) !important;
        background: #fff !important;
    }

    /* Video Modal Styles */
    .video-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        z-index: 11000;
        display: grid;
        place-items: center;
        padding: 40px;
    }

    .video-modal-content {
        width: 100%;
        max-width: 900px;
        position: relative;
    }

    .video-wrapper {
        aspect-ratio: 16/9;
        background: black;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }

    .close-video {
        position: absolute;
        top: -50px;
        right: 0;
        background: transparent;
        border: none;
        color: white;
        font-size: 2.5rem;
        cursor: pointer;
        transition: 0.2s;
    }

    .close-video:hover {
        transform: scale(1.1);
        color: #6366f1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('onboardingModal');
        const steps = document.querySelectorAll('.onboarding-step');
        const nextBtns = document.querySelectorAll('.step-next');
        const prevBtns = document.querySelectorAll('.step-prev');
        const finishBtn = document.getElementById('finishOnboarding');

        let currentStep = 0;
        let userData = {
            interests: [],
            goal: '',
            level: '',
            connections: []
        };

        // Tool Chip Selection Logic
        const toolCountEl = document.getElementById('toolCountText');
        const toolCountWrapper = document.getElementById('toolSelectedCount');
        const noToolsMsg = document.getElementById('noToolsMessage');
        const noToolsQuery = document.getElementById('noToolsQuery');
        const toolSearchInput = document.getElementById('toolSearchInput');
        const otherInterestInput = document.getElementById('otherInterestInput');
        const otherToolInput = document.getElementById('otherToolInput');

        function getTrimmedValue(input) {
            return input && input.value ? input.value.trim() : '';
        }

        function syncStepNextState(stepContainer) {
            const nextBtn = stepContainer.querySelector('.step-next');
            if (!nextBtn) return;

            const hasSelection = stepContainer.querySelectorAll('.selected').length > 0;
            const hasOtherValue = Array.from(stepContainer.querySelectorAll('.other-entry input'))
                .some(input => getTrimmedValue(input) !== '');

            nextBtn.classList.toggle('disabled', !hasSelection && !hasOtherValue);
        }

        // Logic to show modal (Mandatory)
        modal.style.display = 'grid';

        // Selection Logic
        document.querySelectorAll('.selection-item, .list-selection-item, .level-card').forEach(item => {
            item.addEventListener('click', function() {
                const parent = this.closest('[data-type]');
                const type = parent.getAttribute('data-type');
                const value = this.getAttribute('data-value');

                if (type === 'single') {
                    parent.querySelectorAll('.selected').forEach(s => s.classList.remove('selected'));
                    this.classList.add('selected');
                } else {
                    this.classList.toggle('selected');
                }

                syncStepNextState(this.closest('.onboarding-step'));
            });
        });

        [otherInterestInput, otherToolInput].forEach(input => {
            if (!input) return;
            input.addEventListener('input', function() {
                syncStepNextState(this.closest('.onboarding-step'));
                if (this === otherToolInput) updateToolCount();
            });
        });

        function updateToolCount() {
            const count = document.querySelectorAll('#toolsGrid .tool-chip.selected').length + (getTrimmedValue(otherToolInput) ? 1 : 0);
            if (count > 0) {
                toolCountWrapper.style.display = 'block';
                toolCountEl.textContent = count;
            } else {
                toolCountWrapper.style.display = 'none';
            }
        }

        document.querySelectorAll('#toolsGrid .tool-chip').forEach(chip => {
            chip.addEventListener('click', function() {
                this.classList.toggle('selected');
                updateToolCount();
                syncStepNextState(this.closest('.onboarding-step'));
            });
        });

        // Tool Search Logic
        if (toolSearchInput) {
            toolSearchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const tools = document.querySelectorAll('#toolsGrid .tool-chip');
                let visibleCount = 0;

                tools.forEach((chip) => {
                    const searchAttr = chip.getAttribute('data-search') || '';
                    const isInitial = chip.getAttribute('data-initial-visible') === '1';
                    const isSelected = chip.classList.contains('selected');
                    const shouldShow = query === '' ? (isInitial || isSelected) : searchAttr.includes(query);

                    chip.classList.toggle('d-none', !shouldShow);
                    chip.classList.toggle('hidden', !shouldShow);
                    if (shouldShow) visibleCount++;
                });

                if (visibleCount === 0 && query !== '') {
                    noToolsMsg.style.display = 'block';
                    noToolsQuery.textContent = query;
                } else {
                    noToolsMsg.style.display = 'none';
                }
            });
        }

        // Navigation Logic
        nextBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.classList.contains('disabled')) return;

                // Collect data based on current step
                if (currentStep === 1) {
                    userData.interests = Array.from(steps[1].querySelectorAll('.selected')).map(s => s.getAttribute('data-value'));
                    const otherInterest = getTrimmedValue(otherInterestInput);
                    if (otherInterest) userData.interests.push(otherInterest);
                } else if (currentStep === 2) {
                    userData.goal = steps[2].querySelector('.selected').getAttribute('data-value');
                } else if (currentStep === 3) {
                    userData.level = steps[3].querySelector('.selected').getAttribute('data-value');
                } else if (currentStep === 4) {
                    userData.connections = Array.from(steps[4].querySelectorAll('.tool-chip.selected')).map(s => s.getAttribute('data-value'));
                    const otherTool = getTrimmedValue(otherToolInput);
                    if (otherTool) userData.connections.push(otherTool);
                }

                goToStep(currentStep + 1);
            });
        });

        prevBtns.forEach(btn => {
            btn.addEventListener('click', () => goToStep(currentStep - 1));
        });

        function goToStep(stepIndex) {
            steps[currentStep].classList.remove('active');
            currentStep = stepIndex;
            steps[currentStep].classList.add('active');
        }

        // Final Submit
        finishBtn.addEventListener('click', async () => {
             // Data is already collected in nextBtn clicks, but for Step 4 -> 5 it happens on 'Continue'
             // Final check for step 5 connection data if any

            finishBtn.disabled = true;
            finishBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            try {
                const response = await fetch('{{ route("onboarding.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(userData)
                });

                if (response.ok) {
                    window.location.href = '{{ route("extension.install") }}';
                } else {
                    alert('Something went wrong. Please try again.');
                    finishBtn.disabled = false;
                    finishBtn.innerText = 'Complete Setup';
                }
            } catch (error) {
                console.error('Error:', error);
                finishBtn.disabled = false;
                finishBtn.innerText = 'Complete Setup';
            }
        });
    });

    function openOnboardingVideo() {
        const videoModal = document.getElementById('videoModal');
        const iframe = document.getElementById('onboardingIframe');
        iframe.src = 'https://www.youtube.com/embed/KcbXKUR7-a0?autoplay=1&start=106';
        videoModal.style.display = 'grid';
    }

    function closeOnboardingVideo() {
        const videoModal = document.getElementById('videoModal');
        const iframe = document.getElementById('onboardingIframe');
        iframe.src = '';
        videoModal.style.display = 'none';
    }
</script>
