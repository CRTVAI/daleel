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
                    <h2 class="fw-800 mb-3">Welcome to Dallel AI</h2>
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
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-bold mb-2">Step 1 of 4</span>
                        <h3 class="fw-800">What do you want to learn?</h3>
                        <p class="text-muted small">Select all that interest you.</p>
                    </div>

                    <div class="row g-3 item-selection overflow-y-auto" data-type="multi" style="max-height: 300px;">
                        @foreach($interestsList as $topic)
                            <div class="col-6 col-md-4">
                                <div class="selection-item" data-value="{{ $topic }}">
                                    <span class="item-label">{{ $topic }}</span>
                                </div>
                            </div>
                        @endforeach
                        @if(empty($interestsList))
                            <div class="col-12 text-center py-4">
                                <p class="text-muted">No categories found. Adding defaults...</p>
                                @foreach(['AI Strategy', 'Productivity', 'Content Creation', 'Development'] as $topic)
                                    <div class="selection-item mb-2" data-value="{{ $topic }}">
                                        <span class="item-label">{{ $topic }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
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
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-bold mb-2">Step 2 of 4</span>
                        <h3 class="fw-800">What is your main goal?</h3>
                        <p class="text-muted small">Choose the one that best describes your objective.</p>
                    </div>

                    <div class="list-selection overflow-y-auto" data-type="single" style="max-height: 300px;">
                        @foreach($learningGoals as $goal)
                            <div class="list-selection-item" data-value="{{ $goal->title }}">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="radio-circle"></div>
                                    <span class="fw-700">{{ $goal->title }}</span>
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
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-bold mb-2">Step 3 of 4</span>
                        <h3 class="fw-800">What is your level?</h3>
                        <p class="text-muted small">This helps us recommend the right difficulty.</p>
                    </div>

                    <div class="level-selection" data-type="single">
                        @foreach($experienceLevels as $level)
                            <div class="level-card" data-value="{{ $level->title }}">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h5 class="fw-800 mb-0">{{ $level->title }}</h5>
                                    <div class="check-circle"><i class="bi bi-check-lg"></i></div>
                                </div>
                                <p class="text-muted small mb-0">Experience with AI and productivity tools.</p>
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
                    <div class="mb-4">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fw-bold mb-2">Final Step</span>
                        <h3 class="fw-800">Most used apps</h3>
                        <p class="text-muted small">Select the tools you use every day.</p>
                    </div>

                    <div class="row g-3 item-selection overflow-y-auto" data-type="multi" style="max-height: 250px;">
                        @foreach($tools as $tool)
                            <div class="col-6 col-md-4">
                                <div class="selection-item app-item d-flex flex-column align-items-center gap-2" data-value="{{ $tool->name }}">
                                    @if($tool->logo)
                                        <img src="{{ $tool->logo }}" style="width: 32px; height: 32px; object-fit: contain;">
                                    @else
                                        <div class="item-icon"><i class="bi bi-app"></i></div>
                                    @endif
                                    <span class="item-label">{{ $tool->name }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 d-flex gap-3">
                        <button class="btn btn-light rounded-4 fw-800 py-3 px-4 step-prev"><i class="bi bi-arrow-left"></i></button>
                        <button class="btn btn-primary flex-grow-1 btn-lg rounded-4 fw-800 py-3 step-next">Continue</button>
                    </div>
                </div>
            </div>

            <!-- Step 5: Extension -->
            <div class="onboarding-step" data-step="5">
                <div class="onboarding-content text-center">
                    <div class="welcome-icon mb-4" style="background: #ff4757;">
                        <i class="bi bi-cpu"></i>
                    </div>
                    <h3 class="fw-800 mb-3">Power Up with AI Mentor</h3>
                    <p class="text-muted mb-4">Install our extension to get real-time tracking and AI mentorship on any website you visit.</p>
                    
                    <div class="d-grid gap-3 mb-4">
                        <a href="https://chrome.google.com/webstore" target="_blank" class="btn btn-dark btn-lg rounded-4 fw-800 py-3">
                            <i class="bi bi-google me-2"></i> Add to Chrome
                        </a>
                    </div>

                    <div class="mt-5 d-flex gap-3">
                        <button class="btn btn-light rounded-4 fw-800 py-3 px-4 step-prev"><i class="bi bi-arrow-left"></i></button>
                        <button class="btn btn-primary flex-grow-1 btn-lg rounded-4 fw-800 py-3" id="finishOnboarding">Get Started</button>
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
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(12px);
        z-index: 10000;
        display: grid;
        place-items: center;
        padding: 20px;
    }

    .onboarding-card {
        background: white;
        width: 100%;
        max-width: 600px;
        border-radius: 32px;
        box-shadow: 0 40px 100px rgba(0,0,0,0.5);
        overflow: hidden;
        position: relative;
    }

    .onboarding-step {
        display: none;
        padding: 48px;
        animation: slideIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .onboarding-step.active {
        display: block;
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
        border-radius: 20px;
        padding: 15px 10px;
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
        border-radius: 16px;
        padding: 18px 24px;
        margin-bottom: 12px;
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
        border-radius: 20px;
        padding: 20px;
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
    
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }

    .btn-lg {
        padding: 16px 28px;
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

                // Enable/Disable Next Button based on selection
                const stepContainer = this.closest('.onboarding-step');
                const nextBtn = stepContainer.querySelector('.step-next');
                if (nextBtn) {
                    const selections = stepContainer.querySelectorAll('.selected');
                    if (selections.length > 0) {
                        nextBtn.classList.remove('disabled');
                    } else {
                        nextBtn.classList.add('disabled');
                    }
                }
            });
        });

        // Navigation Logic
        nextBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.classList.contains('disabled')) return;
                
                // Collect data based on current step
                if (currentStep === 1) {
                    userData.interests = Array.from(steps[1].querySelectorAll('.selected')).map(s => s.getAttribute('data-value'));
                } else if (currentStep === 2) {
                    userData.goal = steps[2].querySelector('.selected').getAttribute('data-value');
                } else if (currentStep === 3) {
                    userData.level = steps[3].querySelector('.selected').getAttribute('data-value');
                } else if (currentStep === 4) {
                    userData.connections = Array.from(steps[4].querySelectorAll('.selected')).map(s => s.getAttribute('data-value'));
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
