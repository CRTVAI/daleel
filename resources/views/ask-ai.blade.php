@extends('layouts.user')

@section('content')
<div class="ask-ai-wrapper-full">
    <div class="row g-4">
        <!-- Main Chat Area -->
        <div class="col-lg-9">
            <div class="chat-header mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="ai-logo-square d-flex align-items-center justify-content-center">
                            <i class="bi bi-stars"></i>
                        </div>
                        <div>
                            <h1 class="fw-bold h3 mb-0">AI Learning Mentor</h1>
                            <p class="small mb-0 text-secondary">Your intelligent companion for mastering AI concepts and tools</p>
                        </div>
                    </div>
                </div>
                <button class="btn-new-chat d-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg"></i> New Conversation
                </button>
            </div>

            <!-- Filter Pills -->
            <div class="filter-pills-container mb-4">
                <button class="filter-pill-pro active">All Topics</button>
                <button class="filter-pill-pro">Machine Learning</button>
                <button class="filter-pill-pro">Automation</button>
                <button class="filter-pill-pro">Tools & APIs</button>
                <button class="filter-pill-pro">Career Growth</button>
                <button class="filter-pill-pro">Personalized</button>
            </div>

            <!-- Chat Messages Area -->
            <div class="chat-messages-scroll mb-4">
                <!-- User Message -->
                <div class="message-row-user mb-4">
                    <div class="d-flex justify-content-end">
                        <div class="message-content-user">
                            <p class="mb-0">How can I automate client onboarding using AI tools?</p>
                            <span class="message-time">Just now</span>
                        </div>
                        <div class="user-avatar ms-3">AH</div>
                    </div>
                </div>

                <!-- AI Response with Video Suggestions -->
                <div class="message-row-ai mb-4">
                    <div class="d-flex">
                        <div class="ai-avatar-pro me-3">
                            <i class="bi bi-stars"></i>
                        </div>
                        <div class="message-content-ai">
                            <p class="ai-intro-text">Based on your learning path and goals, I've curated these masterclasses from our library that directly address automation workflows and client onboarding.</p>
                            
                            <!-- Professional Video Slider with Arrow Buttons -->
                            <div class="video-slider-professional mt-4">
                                <div class="slider-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="slider-icon">
                                            <i class="bi bi-collection-play-fill"></i>
                                        </div>
                                        <h6 class="fw-bold mb-0">Recommended Masterclasses</h6>
                                    </div>
                                    <div class="slider-counter">
                                        <span id="currentSlide">1</span>/<span id="totalSlides">6</span>
                                    </div>
                                </div>
                                
                                <div class="slider-main-container">
                                    <!-- Left Arrow -->
                                    <button class="slider-arrow slider-arrow-left" onclick="slideVideos('prev')" aria-label="Previous">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    
                                    <!-- Video Cards Track -->
                                    <div class="video-cards-viewport" id="videoCardsViewport">
                                        <div class="video-cards-track" id="videoCardsTrack">
                                            @foreach($suggestedVideos as $index => $video)
                                            <div class="video-card-professional">
                                                <div class="card-thumbnail">
                                                    <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}">
                                                    <div class="thumbnail-overlay">
                                                        <div class="play-button">
                                                            <i class="bi bi-play-fill"></i>
                                                        </div>
                                                    </div>
                                                    <div class="duration-badge">{{ $video->duration_label }}</div>
                                                    @if($index === 0)
                                                    <div class="progress-indicator" style="width: 45%;"></div>
                                                    @endif
                                                </div>
                                                <div class="card-content">
                                                    <div class="card-badge">{{ $video->category ?? 'Masterclass' }}</div>
                                                    <h6 class="card-title">{{ $video->title }}</h6>
                                                    <div class="card-meta">
                                                        <span><i class="bi bi-bar-chart-fill"></i> {{ $video->skill_level ?? 'All Levels' }}</span>
                                                        <span><i class="bi bi-person-circle"></i> Expert</span>
                                                    </div>
                                                    <a href="{{ route('learn.watch', $video) }}" class="card-action-btn">
                                                        <span>Explore Masterclass</span>
                                                        <i class="bi bi-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- Right Arrow -->
                                    <button class="slider-arrow slider-arrow-right" onclick="slideVideos('next')" aria-label="Next">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="ai-followup mt-4">
                                <p class="mb-3 recommendation-text">
                                    <i class="bi bi-lightbulb-fill text-warning me-2"></i>
                                    I recommend starting with <strong>ChatGPT & Zapier integration</strong> — it's the most practical for your use case and includes ready-to-use templates.
                                </p>
                                
                                <div class="action-buttons-row">
                                    <button class="action-btn-icon" title="Copy response">
                                        <i class="bi bi-copy"></i>
                                    </button>
                                    <button class="action-btn-icon" title="Thumbs up">
                                        <i class="bi bi-hand-thumbs-up"></i>
                                    </button>
                                    <button class="action-btn-icon" title="Thumbs down">
                                        <i class="bi bi-hand-thumbs-down"></i>
                                    </button>
                                    <button class="action-btn-icon" title="Save for later">
                                        <i class="bi bi-bookmark"></i>
                                    </button>
                                    <div class="action-divider"></div>
                                    <button class="quick-reply-btn">
                                        <i class="bi bi-lightbulb me-1"></i> Show templates
                                    </button>
                                    <button class="quick-reply-btn">
                                        <i class="bi bi-gear me-1"></i> Tool recommendations
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Message 2 -->
                <div class="message-row-user mb-4">
                    <div class="d-flex justify-content-end">
                        <div class="message-content-user">
                            <p class="mb-0">Do you have any quick tips for Gmail automation?</p>
                            <span class="message-time">2 min ago</span>
                        </div>
                        <div class="user-avatar ms-3">AH</div>
                    </div>
                </div>

                <!-- AI Response 2 with Short Video -->
                <div class="message-row-ai mb-4">
                    <div class="d-flex">
                        <div class="ai-avatar-pro me-3">
                            <i class="bi bi-stars"></i>
                        </div>
                        <div class="message-content-ai">
                            <p class="mb-3 fw-semibold" style="color: #1a1a2e; font-size: 1.05rem;">Here's a quick 60-second hack for Gmail automation!</p>
                            
                            <div class="short-preview-card mb-3">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="short-thumb-wrapper">
                                        <img src="https://images.unsplash.com/photo-1557200134-90327ee9fafa?auto=format&fit=crop&q=80&w=200" alt="Gmail Short" class="short-thumb-img">
                                        <div class="short-play-btn">
                                            <i class="bi bi-play-fill"></i>
                                        </div>
                                        <span class="short-duration">0:58</span>
                                    </div>
                                    <div>
                                        <div class="short-label">QUICK TIP</div>
                                        <h6 class="fw-bold mb-1" style="color: #1a1a2e;">Gmail AI Filter Trick</h6>
                                        <p class="mb-2" style="color: #6b7280; font-size: 0.8rem;">Auto-categorize emails with AI labels</p>
                                        <a href="#" class="short-watch-link">
                                            Watch Now <i class="bi bi-play-circle ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <p style="color: #374151; line-height: 1.6;">
                                This technique uses Gmail's filter rules combined with AI labeling to automatically sort high-priority client emails.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="chat-input-container-pro">
                <div class="chat-input-card">
                    <textarea class="chat-textarea" rows="2" placeholder="Describe your learning goal or ask about specific AI tools..."></textarea>
                    <div class="chat-input-actions">
                        <div class="input-tools-group">
                            <button class="input-tool-btn" title="Attach files">
                                <i class="bi bi-paperclip"></i>
                            </button>
                            <button class="input-tool-btn" title="Reference a video">
                                <i class="bi bi-camera-video"></i>
                            </button>
                            <button class="input-tool-btn" title="My learning content">
                                <i class="bi bi-journal-text"></i>
                            </button>
                        </div>
                        <div class="input-send-group">
                            <span class="input-hint">⌘ + Enter</span>
                            <button class="btn-send-message">
                                <i class="bi bi-send-fill"></i>
                                <span>Send</span>
                            </button>
                        </div>
                    </div>
                </div>
                <p class="ai-disclaimer">AI responses are based on our masterclass library.</p>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="sidebar-card mb-3">
                <div class="sidebar-card-header">
                    <h6 class="fw-bold mb-0"><i class="bi bi-bullseye me-2 text-primary"></i>Your Context</h6>
                    <span class="badge-ai">AI Personalized</span>
                </div>
                <div class="sidebar-card-body">
                    <div class="context-item-pro">
                        <div class="context-dot bg-primary"></div>
                        <div>
                            <div class="context-label">Current Role</div>
                            <div class="context-value">Founder & Solutions Architect</div>
                        </div>
                    </div>
                    <div class="context-item-pro">
                        <div class="context-dot bg-success"></div>
                        <div>
                            <div class="context-label">Learning Goal</div>
                            <div class="context-value">Master AI Automation</div>
                        </div>
                    </div>
                    <div class="context-item-pro">
                        <div class="context-dot bg-warning"></div>
                        <div>
                            <div class="context-label">Skill Level</div>
                            <div class="context-value">Intermediate</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar-card mb-3">
                <div class="sidebar-card-header">
                    <h6 class="fw-bold mb-0"><i class="bi bi-lightning-charge me-2 text-warning"></i>Quick Prompts</h6>
                </div>
                <div class="sidebar-card-body">
                    <button class="prompt-chip"><i class="bi bi-robot me-2"></i>Explain transformer architecture</button>
                    <button class="prompt-chip"><i class="bi bi-tools me-2"></i>Best AI tools for startups</button>
                    <button class="prompt-chip"><i class="bi bi-graph-up me-2"></i>Career path in AI/ML</button>
                </div>
            </div>

            <div class="sidebar-card highlight-card">
                <div class="highlight-content">
                    <div class="highlight-icon"><i class="bi bi-rocket-takeoff"></i></div>
                    <h6 class="fw-bold mb-2">New Masterclasses</h6>
                    <p style="font-size: 0.8rem; line-height: 1.5;">Explore our latest additions on AI agents and automation frameworks.</p>
                    <a href="{{ route('learn.explore') }}" class="btn-explore-library">
                        Browse Library <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    :root {
        --surface-primary: #ffffff;
        --surface-secondary: #f8fafc;
        --surface-tertiary: #f1f5f9;
        --text-primary: #0f172a;
        --text-secondary: #334155;
        --text-tertiary: #64748b;
        --border-light: #e2e8f0;
        --border-medium: #cbd5e1;
        --accent-indigo: #6366f1;
        --accent-indigo-light: #eef2ff;
        --accent-indigo-dark: #4f46e5;
        --shadow-xs: 0 1px 2px rgba(0,0,0,0.05);
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.07);
        --shadow-lg: 0 10px 25px rgba(0,0,0,0.08);
        --shadow-xl: 0 20px 50px rgba(0,0,0,0.12);
        --radius-sm: 8px;
        --radius-md: 14px;
        --radius-lg: 20px;
        --radius-xl: 28px;
    }

    * {
        box-sizing: border-box;
    }

    .ask-ai-wrapper-full {
        padding: 24px 36px 40px;
        max-width: 1440px;
        margin: 0 auto;
    }

    /* Header */
    .ai-logo-square {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 14px;
        color: white;
        font-size: 22px;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-new-chat {
        padding: 10px 22px;
        background: var(--surface-primary);
        border: 1.5px solid var(--border-light);
        border-radius: 12px;
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-new-chat:hover {
        background: var(--surface-tertiary);
        border-color: var(--accent-indigo);
        color: var(--accent-indigo);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    /* Filter Pills */
    .filter-pills-container {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 4px;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .filter-pills-container::-webkit-scrollbar {
        display: none;
    }

    .filter-pill-pro {
        padding: 9px 20px;
        border-radius: 12px;
        border: 1.5px solid var(--border-light);
        background: var(--surface-primary);
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-tertiary);
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-pill-pro:hover {
        border-color: var(--accent-indigo);
        color: var(--accent-indigo);
        background: var(--accent-indigo-light);
    }

    .filter-pill-pro.active {
        background: var(--accent-indigo);
        color: white;
        border-color: var(--accent-indigo);
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    /* Chat Messages */
    .chat-messages-scroll {
        min-height: 500px;
        max-height: calc(100vh - 380px);
        overflow-y: auto;
        padding-right: 6px;
    }

    .chat-messages-scroll::-webkit-scrollbar {
        width: 5px;
    }

    .chat-messages-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .chat-messages-scroll::-webkit-scrollbar-thumb {
        background: var(--border-light);
        border-radius: 10px;
    }

    .chat-messages-scroll::-webkit-scrollbar-thumb:hover {
        background: var(--border-medium);
    }

    /* User Message */
    .message-content-user {
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        border: 1.5px solid #c7d2fe;
        border-radius: 20px 20px 4px 20px;
        padding: 16px 22px;
        max-width: 600px;
        color: #1e3a8a;
        font-weight: 500;
        font-size: 0.9375rem;
        line-height: 1.6;
    }

    .message-time {
        font-size: 0.6875rem;
        color: var(--text-tertiary);
        margin-top: 6px;
        display: block;
        font-weight: 500;
    }

    .user-avatar {
        width: 38px;
        height: 38px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 800;
        font-size: 0.8125rem;
        flex-shrink: 0;
        box-shadow: var(--shadow-sm);
    }

    /* AI Message */
    .ai-avatar-pro {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, #1e293b, #334155);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.125rem;
        flex-shrink: 0;
        box-shadow: var(--shadow-md);
    }

    .message-content-ai {
        background: var(--surface-primary);
        border: 1.5px solid var(--border-light);
        border-radius: 24px;
        padding: 28px 32px;
        max-width: 780px;
        box-shadow: var(--shadow-xs);
    }

    .ai-intro-text {
        color: var(--text-secondary);
        font-weight: 500;
        line-height: 1.7;
        font-size: 0.9375rem;
    }

    /* Professional Video Slider */
    .video-slider-professional {
        background: var(--surface-secondary);
        border: 1.5px solid var(--border-light);
        border-radius: var(--radius-xl);
        padding: 24px;
        position: relative;
    }

    .slider-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .slider-icon {
        width: 36px;
        height: 36px;
        background: var(--accent-indigo);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }

    .slider-counter {
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--text-tertiary);
        background: var(--surface-primary);
        padding: 6px 14px;
        border-radius: 10px;
        border: 1px solid var(--border-light);
    }

    #currentSlide {
        color: var(--accent-indigo);
        font-weight: 800;
    }

    .slider-main-container {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0;
    }

    /* Arrow Buttons */
    .slider-arrow {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 2px solid var(--border-light);
        background: var(--surface-primary);
        color: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 1.125rem;
        flex-shrink: 0;
        z-index: 10;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
    }

    .slider-arrow-left {
        left: -22px;
    }

    .slider-arrow-right {
        right: -22px;
    }

    .slider-arrow:hover {
        background: var(--accent-indigo);
        color: white;
        border-color: var(--accent-indigo);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.35);
        transform: translateY(-50%) scale(1.1);
    }

    .slider-arrow:active {
        transform: translateY(-50%) scale(1.05);
    }

    .slider-arrow:disabled {
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* Video Cards Viewport & Track */
    .video-cards-viewport {
        overflow-x: hidden;
        width: 100%;
        padding: 10px 8px;
    }

    .video-cards-track {
        display: flex;
        gap: 18px;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Individual Video Card */
    .video-card-professional {
        min-width: 270px;
        max-width: 270px;
        background: var(--surface-primary);
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1.5px solid var(--border-light);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
        cursor: pointer;
    }

    .video-card-professional:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        border-color: var(--accent-indigo);
    }

    .card-thumbnail {
        height: 165px;
        position: relative;
        overflow: hidden;
        background: #1a1a2e;
    }

    .card-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0.9;
    }

    .video-card-professional:hover .card-thumbnail img {
        transform: scale(1.1);
        opacity: 1;
    }

    .thumbnail-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.35s ease;
    }

    .video-card-professional:hover .thumbnail-overlay {
        opacity: 1;
    }

    .play-button {
        width: 52px;
        height: 52px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-indigo);
        font-size: 1.4rem;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s ease;
    }

    .play-button:hover {
        transform: scale(1.1);
    }

    .duration-badge {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(12px);
        color: white;
        font-size: 0.6875rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        letter-spacing: 0.02em;
    }

    .progress-indicator {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: var(--accent-indigo);
        border-radius: 0 3px 0 0;
        transition: width 0.3s ease;
    }

    .card-content {
        padding: 18px;
    }

    .card-badge {
        display: inline-block;
        font-size: 0.65rem;
        font-weight: 800;
        color: var(--accent-indigo);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 8px;
        background: var(--accent-indigo-light);
        padding: 3px 10px;
        border-radius: 6px;
    }

    .card-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.45;
        margin-bottom: 10px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-meta {
        display: flex;
        gap: 14px;
        margin-bottom: 14px;
        font-size: 0.7rem;
        color: var(--text-tertiary);
        font-weight: 600;
    }

    .card-meta span {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .card-action-btn {
        width: 100%;
        padding: 10px 16px;
        background: var(--accent-indigo-light);
        color: var(--accent-indigo);
        border: 1.5px solid transparent;
        border-radius: 10px;
        font-size: 0.8125rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .card-action-btn:hover {
        background: var(--accent-indigo);
        color: white;
        border-color: var(--accent-indigo);
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .card-action-btn i {
        transition: transform 0.3s ease;
    }

    .card-action-btn:hover i {
        transform: translateX(4px);
    }

    /* AI Follow-up */
    .ai-followup {
        border-top: 1.5px solid var(--border-light);
        padding-top: 20px;
        margin-top: 20px;
    }

    .recommendation-text {
        color: var(--text-secondary);
        line-height: 1.7;
        font-size: 0.9375rem;
    }

    .action-buttons-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }

    .action-btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1.5px solid var(--border-light);
        background: var(--surface-primary);
        color: var(--text-tertiary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.875rem;
    }

    .action-btn-icon:hover {
        background: var(--surface-tertiary);
        color: var(--text-primary);
        border-color: var(--border-medium);
    }

    .action-divider {
        width: 1px;
        height: 24px;
        background: var(--border-light);
        margin: 0 6px;
    }

    .quick-reply-btn {
        padding: 8px 16px;
        border-radius: 10px;
        border: 1.5px solid var(--border-light);
        background: var(--surface-primary);
        color: var(--text-secondary);
        font-size: 0.8125rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        white-space: nowrap;
    }

    .quick-reply-btn:hover {
        background: var(--accent-indigo-light);
        border-color: var(--accent-indigo);
        color: var(--accent-indigo);
    }

    /* Short Preview Card */
    .short-preview-card {
        background: var(--surface-secondary);
        border: 1.5px solid var(--border-light);
        border-radius: var(--radius-lg);
        padding: 18px;
        transition: all 0.3s ease;
        max-width: 400px;
    }

    .short-preview-card:hover {
        border-color: var(--accent-indigo);
        box-shadow: var(--shadow-md);
    }

    .short-thumb-wrapper {
        width: 80px;
        height: 105px;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        flex-shrink: 0;
    }

    .short-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .short-play-btn {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        background: rgba(0, 0, 0, 0.4);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .short-preview-card:hover .short-play-btn {
        opacity: 1;
    }

    .short-duration {
        position: absolute;
        bottom: 6px;
        right: 6px;
        background: rgba(0, 0, 0, 0.85);
        color: white;
        font-size: 0.625rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 4px;
    }

    .short-label {
        font-size: 0.625rem;
        font-weight: 800;
        color: var(--accent-indigo);
        letter-spacing: 0.1em;
        margin-bottom: 2px;
    }

    .short-watch-link {
        font-size: 0.8125rem;
        font-weight: 700;
        color: var(--accent-indigo);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
    }

    .short-watch-link:hover {
        gap: 8px;
    }

    /* Chat Input */
    .chat-input-container-pro {
        position: sticky;
        bottom: 0;
        background: linear-gradient(to top, var(--surface-primary) 80%, transparent);
        padding-top: 20px;
    }

    .chat-input-card {
        background: var(--surface-primary);
        border: 2px solid var(--border-light);
        border-radius: var(--radius-xl);
        padding: 18px 22px;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-lg);
    }

    .chat-input-card:focus-within {
        border-color: var(--accent-indigo);
        box-shadow: var(--shadow-xl), 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .chat-textarea {
        width: 100%;
        border: none;
        outline: none;
        resize: none;
        font-size: 0.9375rem;
        font-weight: 500;
        color: var(--text-primary);
        background: transparent;
        line-height: 1.6;
        font-family: inherit;
    }

    .chat-textarea::placeholder {
        color: var(--text-tertiary);
        opacity: 0.7;
    }

    .chat-input-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1.5px solid var(--border-light);
    }

    .input-tools-group {
        display: flex;
        gap: 8px;
    }

    .input-tool-btn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: 1.5px solid var(--border-light);
        background: var(--surface-primary);
        color: var(--text-tertiary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 0.9375rem;
    }

    .input-tool-btn:hover {
        background: var(--accent-indigo-light);
        color: var(--accent-indigo);
        border-color: var(--accent-indigo);
    }

    .input-send-group {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .input-hint {
        font-size: 0.6875rem;
        color: var(--text-tertiary);
        font-weight: 500;
    }

    .btn-send-message {
        padding: 11px 24px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35);
    }

    .btn-send-message:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.45);
    }

    .btn-send-message:active {
        transform: translateY(0);
    }

    .ai-disclaimer {
        text-align: center;
        font-size: 0.6875rem;
        color: var(--text-tertiary);
        margin-top: 10px;
        font-weight: 500;
    }

    /* Sidebar */
    .sidebar-card {
        background: var(--surface-primary);
        border: 1.5px solid var(--border-light);
        border-radius: var(--radius-xl);
        overflow: hidden;
    }

    .sidebar-card-header {
        padding: 20px 22px;
        border-bottom: 1.5px solid var(--border-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .badge-ai {
        background: var(--accent-indigo-light);
        color: var(--accent-indigo);
        font-size: 0.625rem;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 8px;
        letter-spacing: 0.06em;
    }

    .sidebar-card-body {
        padding: 16px 18px;
    }

    .context-item-pro {
        display: flex;
        gap: 14px;
        padding: 14px 16px;
        border-radius: 12px;
        transition: all 0.2s ease;
        margin-bottom: 6px;
    }

    .context-item-pro:hover {
        background: var(--surface-secondary);
    }

    .context-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-top: 5px;
        flex-shrink: 0;
    }

    .context-label {
        font-size: 0.6875rem;
        font-weight: 700;
        color: var(--text-tertiary);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 3px;
    }

    .context-value {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .prompt-chip {
        width: 100%;
        padding: 13px 16px;
        text-align: left;
        background: var(--surface-secondary);
        border: 1.5px solid var(--border-light);
        border-radius: 12px;
        color: var(--text-secondary);
        font-size: 0.8125rem;
        font-weight: 600;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        font-family: inherit;
    }

    .prompt-chip:hover {
        background: var(--accent-indigo-light);
        border-color: var(--accent-indigo);
        color: var(--accent-indigo);
        transform: translateX(4px);
    }

    .highlight-card {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border: none;
        color: white;
    }

    .highlight-content {
        padding: 26px;
    }

    .highlight-icon {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 18px;
        backdrop-filter: blur(12px);
    }

    .highlight-content h6 {
        color: white !important;
    }

    .highlight-content p {
        color: rgba(255, 255, 255, 0.65) !important;
    }

    .btn-explore-library {
        width: 100%;
        padding: 12px;
        background: rgba(255, 255, 255, 0.12);
        color: white;
        border: 1.5px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        font-size: 0.8125rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(12px);
    }

    .btn-explore-library:hover {
        background: rgba(255, 255, 255, 0.22);
        border-color: rgba(255, 255, 255, 0.4);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .ask-ai-wrapper-full {
            padding: 20px 24px 32px;
        }
        
        .message-content-ai {
            max-width: 600px;
            padding: 24px;
        }
    }

    @media (max-width: 768px) {
        .ask-ai-wrapper-full {
            padding: 16px;
        }
        
        .video-card-professional {
            min-width: 240px;
            max-width: 240px;
        }
        
        .slider-arrow {
            width: 38px;
            height: 38px;
        }
        
        .slider-arrow-left {
            left: -19px;
        }
        
        .slider-arrow-right {
            right: -19px;
        }
        
        .message-content-ai {
            padding: 20px;
        }
        
        .card-thumbnail {
            height: 140px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    let currentPosition = 0;
    let cardWidth = 270 + 18; // card width + gap
    
    function updateCardWidth() {
        const card = document.querySelector('.video-card-professional');
        if (card) {
            cardWidth = card.offsetWidth + 18;
        }
    }
    
    function slideVideos(direction) {
        const track = document.getElementById('videoCardsTrack');
        const viewport = document.getElementById('videoCardsViewport');
        
        updateCardWidth();
        
        const maxScroll = track.scrollWidth - viewport.offsetWidth;
        const maxPosition = Math.ceil(maxScroll / cardWidth);
        
        if (direction === 'next') {
            currentPosition = Math.min(currentPosition + 1, maxPosition);
        } else {
            currentPosition = Math.max(currentPosition - 1, 0);
        }
        
        const translateX = currentPosition * cardWidth;
        track.style.transform = `translateX(-${translateX}px)`;
        
        updateSliderCounter();
        updateArrowStates(maxPosition);
    }
    
    function updateSliderCounter() {
        const currentSlideEl = document.getElementById('currentSlide');
        if (currentSlideEl) {
            currentSlideEl.textContent = currentPosition + 1;
        }
    }
    
    function updateArrowStates(maxPosition) {
        const leftArrow = document.querySelector('.slider-arrow-left');
        const rightArrow = document.querySelector('.slider-arrow-right');
        
        if (leftArrow) {
            leftArrow.disabled = currentPosition === 0;
        }
        if (rightArrow) {
            rightArrow.disabled = currentPosition >= maxPosition;
        }
    }
    
    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        updateCardWidth();
        
        const totalCards = document.querySelectorAll('.video-card-professional').length;
        const totalSlides = document.getElementById('totalSlides');
        if (totalSlides && totalCards > 0) {
            totalSlides.textContent = totalCards;
        }
        
        const viewport = document.getElementById('videoCardsViewport');
        const track = document.getElementById('videoCardsTrack');
        if (viewport && track) {
            const maxScroll = track.scrollWidth - viewport.offsetWidth;
            const maxPosition = Math.ceil(maxScroll / cardWidth);
            updateArrowStates(maxPosition);
        }
        
        updateSliderCounter();
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const slider = document.querySelector('.video-slider-professional');
            if (slider && isElementInViewport(slider)) {
                if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    slideVideos('next');
                } else if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    slideVideos('prev');
                }
            }
        });
        
        // Touch swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        
        const sliderContainer = document.querySelector('.video-slider-professional');
        if (sliderContainer) {
            sliderContainer.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            });
            
            sliderContainer.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
        }
        
        function handleSwipe() {
            const swipeThreshold = 50;
            if (touchEndX < touchStartX - swipeThreshold) {
                slideVideos('next');
            } else if (touchEndX > touchStartX + swipeThreshold) {
                slideVideos('prev');
            }
        }
        
        function isElementInViewport(el) {
            const rect = el.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
        
        // Filter pills interaction
        document.querySelectorAll('.filter-pill-pro').forEach(pill => {
            pill.addEventListener('click', function() {
                document.querySelectorAll('.filter-pill-pro').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Auto-resize textarea
        const textarea = document.querySelector('.chat-textarea');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 150) + 'px';
            });
        }
        
        // Handle window resize
        window.addEventListener('resize', function() {
            updateCardWidth();
            currentPosition = 0;
            const track = document.getElementById('videoCardsTrack');
            if (track) {
                track.style.transform = 'translateX(0)';
            }
            const viewport = document.getElementById('videoCardsViewport');
            if (viewport && track) {
                const maxScroll = track.scrollWidth - viewport.offsetWidth;
                const maxPosition = Math.ceil(maxScroll / cardWidth);
                updateArrowStates(maxPosition);
            }
            updateSliderCounter();
        });
    });
</script>
@endsection
