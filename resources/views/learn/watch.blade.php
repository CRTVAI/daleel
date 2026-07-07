@extends(auth()->check() ? 'layouts.user' : 'layouts.public')

@section('title', $content->title . ' — Daleel AI')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap');
    /* ==================== SHARED ROADMAP TIMELINE STYLES ==================== */
    :root {
        --primary: #4f46e5;
        --primary-light: #818cf8;
        --primary-dark: #3730a3;
        --primary-bg: #eef2ff;
        --success: #10b981;
        --warning: #f59e0b;
        --orange: #f97316;
        --pink: #ec4899;
        --purple: #8b5cf6;
        --teal: #14b8a6;
        --indigo: #6366f1;
        --gradient-1: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        --gradient-2: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
        --gradient-3: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
        --gradient-4: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
    }

    .timeline-wrapper { position: relative; padding-left: 36px; }
    .timeline-line {
        position: absolute;
        left: 12px;
        top: 12px;
        bottom: 12px;
        width: 2px;
        background: linear-gradient(180deg, #4f46e5, #7c3aed, #8b5cf6, #a78bfa);
        border-radius: 4px;
    }

    .step-item {
        position: relative;
        margin-bottom: 16px;
        padding-left: 0;
    }

    .step-dot {
        position: absolute;
        left: -36px;
        top: 4px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 900;
        color: #94a3b8;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 2;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .step-item.completed .step-dot {
        background: var(--gradient-1);
        border-color: #4f46e5;
        color: #fff;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    .step-item.active .step-dot {
        border-color: #4f46e5;
        color: #4f46e5;
        width: 30px;
        height: 30px;
        left: -38px;
        top: 2px;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
    }

    .step-card {
        background: #fff;
        border-radius: 16px;
        padding: 16px 20px;
        border: 1px solid rgba(79, 70, 229, 0.06);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        position: relative;
        overflow: hidden;
    }
    .step-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-1);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .step-item:nth-child(1) .step-card::after { background: var(--gradient-1); }
    .step-item:nth-child(2) .step-card::after { background: var(--gradient-2); }
    .step-item:nth-child(3) .step-card::after { background: var(--gradient-3); }
    .step-item:nth-child(4) .step-card::after { background: var(--gradient-4); }
    .step-item:nth-child(5) .step-card::after { background: var(--gradient-2); }

    .step-card:hover {
        border-color: rgba(79, 70, 229, 0.15);
        box-shadow: 0 4px 16px rgba(79, 70, 229, 0.06);
    }
    .step-card:hover::after { opacity: 1; }

    .step-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }
    .step-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f8faff, #f0f4ff);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 700;
        color: #475569;
        flex-shrink: 0;
        border: 1px solid rgba(79, 70, 229, 0.08);
        transition: 0.3s;
    }
    .step-item:nth-child(1) .step-icon { background: linear-gradient(135deg, #eef2ff, #dbeafe); color: #4f46e5; }
    .step-item:nth-child(2) .step-icon { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #f59e0b; }
    .step-item:nth-child(3) .step-icon { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #10b981; }
    .step-item:nth-child(4) .step-icon { background: linear-gradient(135deg, #fce7f3, #fbcfe8); color: #ec4899; }
    .step-item:nth-child(5) .step-icon { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed; }
    .step-icon img { width: 20px; height: 20px; object-fit: contain; }

    .step-title-group {
        flex: 1;
        min-width: 0;
    }
    .step-title {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
    }
    .step-subtitle {
        font-size: 11px;
        color: #94a3b8;
        font-weight: 600;
        margin: 0;
    }

    .step-progress {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 2px;
    }
    .step-progress-bar {
        flex: 1;
        height: 5px;
        background: #f1f5f9;
        border-radius: 6px;
        overflow: hidden;
        min-width: 30px;
    }
    .step-progress-bar .fill {
        height: 100%;
        border-radius: 6px;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .step-item:nth-child(1) .fill { background: var(--gradient-1); }
    .step-item:nth-child(2) .fill { background: var(--gradient-2); }
    .step-item:nth-child(3) .fill { background: var(--gradient-3); }
    .step-item:nth-child(4) .fill { background: var(--gradient-4); }
    .step-item:nth-child(5) .fill { background: var(--gradient-2); }

    .step-progress-text {
        font-size: 12px;
        font-weight: 800;
        color: #0f172a;
        white-space: nowrap;
        min-width: 36px;
        text-align: right;
    }

    .step-lessons {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1.5px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .lesson-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 10px;
        border-radius: 8px;
        text-decoration: none !important;
        color: #334155;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.25s;
        background: transparent;
        border: none;
        width: 100%;
        text-align: left;
        position: relative;
    }
    .lesson-link:hover {
        background: #f8fafc;
        color: #0f172a;
        transform: translateX(4px);
    }
    .lesson-link.active {
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        color: #4f46e5;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.06);
    }
    .lesson-link .check {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        flex-shrink: 0;
        color: #fff;
        transition: 0.2s;
    }
    .lesson-link.done .check {
        background: linear-gradient(135deg, #10b981, #34d399);
        border-color: #10b981;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.2);
    }
    .lesson-link.active .check {
        border-color: #4f46e5;
        border-width: 2px;
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.15);
    }
    .lesson-link.active .check::after {
        content: '';
        width: 6px;
        height: 6px;
        background: #4f46e5;
        border-radius: 50%;
    }
    .lesson-link .play-icon {
        margin-left: auto;
        color: #94a3b8;
        font-size: 14px;
        transition: 0.2s;
    }
    .lesson-link.active .play-icon {
        color: #4f46e5;
    }
    /* ==================== CUSTOM SUBTITLE SYSTEM ==================== */
    .player-shadow, .player-wrapper {
        position: relative !important;
    }
    
    .custom-subtitles-overlay {
        position: absolute;
        bottom: 70px;
        left: 5%;
        right: 5%;
        z-index: 99;
        pointer-events: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        transition: bottom 0.3s ease;
    }
    
    .plyr--hide-controls ~ .custom-subtitles-overlay,
    .plyr--hide-controls + .custom-subtitles-overlay {
        bottom: 25px !important;
    }
    
    .subtitle-text {
        color: #ffffff;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        text-align: center;
        max-width: 90%;
        line-height: 1.4;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
        text-shadow: 0 2px 4px rgba(0,0,0,0.8);
        transition: opacity 0.15s ease;
        word-wrap: break-word;
    }
    
    .subtitle-ar {
        font-family: 'Cairo', sans-serif;
        direction: rtl;
        font-size: 1.25rem;
        border-color: rgba(99, 102, 241, 0.2);
    }
    
    /* Subtitle control card styles */
    .translation-controls-card {
        border: 1px solid rgba(99, 102, 241, 0.08) !important;
        transition: all 0.3s ease;
    }
    .translation-controls-card:hover {
        border-color: rgba(99, 102, 241, 0.15) !important;
        box-shadow: 0 6px 18px rgba(99, 102, 241, 0.04) !important;
    }
    
    /* ==================== INTERACTIVE TRANSCRIPT ==================== */
    .transcript-tab-container {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(99, 102, 241, 0.08);
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        background: #fff;
    }
    
    .transcript-tabs {
        display: flex;
        border-bottom: 1px solid #f1f5f9;
        background: #fafbfe;
        padding: 0 16px;
    }
    
    .transcript-tab-btn {
        padding: 14px 20px;
        background: none;
        border: none;
        font-weight: 700;
        font-size: 0.9rem;
        color: #64748b;
        cursor: pointer;
        position: relative;
        transition: all 0.2s;
    }
    
    .transcript-tab-btn:hover {
        color: #4f46e5;
    }
    
    .transcript-tab-btn.active {
        color: #4f46e5;
    }
    
    .transcript-tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-1);
        border-radius: 3px 3px 0 0;
    }
    
    .transcript-panel {
        display: none;
        padding: 24px;
    }
    
    .transcript-panel.active {
        display: block;
    }
    
    .transcript-scroll-area {
        max-height: 350px;
        overflow-y: auto;
        padding-right: 8px;
    }
    
    .transcript-scroll-area::-webkit-scrollbar {
        width: 6px;
    }
    .transcript-scroll-area::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }
    .transcript-scroll-area::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
    }
    .transcript-scroll-area::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .transcript-line {
        padding: 10px 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 6px;
        border: 1px solid transparent;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .transcript-line:hover {
        background: #f8faff;
        border-color: rgba(99, 102, 241, 0.15);
        transform: translateX(4px);
    }
    
    .transcript-line.active {
        background: #eef2ff;
        border-color: rgba(99, 102, 241, 0.25);
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.06);
    }
    
    .transcript-timestamp {
        font-family: monospace;
        font-size: 0.8rem;
        color: #818cf8;
        font-weight: 600;
        background: #f1f5f9;
        padding: 2px 6px;
        border-radius: 4px;
    }
    
    .transcript-line.active .transcript-timestamp {
        background: #4f46e5;
        color: #fff;
    }
    
    .transcript-text-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .transcript-text-en {
        font-size: 0.95rem;
        font-weight: 500;
        color: #334155;
    }
    
    .transcript-text-ar {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        font-family: 'Cairo', sans-serif;
        text-align: right;
        direction: rtl;
    }
    
    .transcript-line.active .transcript-text-en {
        color: #1e1b4b;
        font-weight: 600;
    }
    
    .transcript-line.active .transcript-text-ar {
        color: #1e1b4b;
        font-weight: 750;
    }
</style>

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
                    <div class="player-shadow position-relative">
                        @if($content->video_url && (str_contains($content->video_url, 'amazonaws.com') || str_ends_with($content->video_url, '.mp4')))
                            <video id="player" playsinline controls data-poster="{{ $content->thumbnail_url }}">
                                <source src="{{ $content->video_url }}" type="video/mp4" />
                            </video>
                        @else
                            <div id="player" data-plyr-provider="youtube" data-plyr-embed-id="{{ $content->youtube_id }}"></div>
                        @endif
                        
                        <!-- Custom Subtitles overlay -->
                        <div id="custom-subtitles" class="custom-subtitles-overlay d-none">
                            <div id="sub-en" class="subtitle-text subtitle-en d-none"></div>
                            <div id="sub-ar" class="subtitle-text subtitle-ar d-none" dir="rtl"></div>
                        </div>
                    </div>
                </div>

                <!-- Subtitle Translation Preferences -->
                @if($content->srt_file_en || $content->srt_file_ar)
                <div class="translation-controls-card p-3 mb-4 rounded-4 border bg-white shadow-sm">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-translate text-primary fs-5"></i>
                            <div>
                                <div class="fw-bold small">Video Subtitles & Translation</div>
                                <div class="text-muted small" style="font-size: 11px;">Select subtitle preference for learning</div>
                            </div>
                        </div>
                        <div class="btn-group btn-group-sm rounded-3 overflow-hidden" role="group" aria-label="Subtitle Language">
                            <button type="button" class="btn btn-dark active px-3" data-lang="off" onclick="setSubtitleLang('off')">Off</button>
                            @if($content->srt_file_en)
                                <button type="button" class="btn btn-outline-dark px-3" data-lang="en" onclick="setSubtitleLang('en')">English</button>
                            @endif
                            @if($content->srt_file_ar)
                                <button type="button" class="btn btn-outline-dark px-3" data-lang="ar" onclick="setSubtitleLang('ar')">Arabic (العربية)</button>
                            @endif
                            @if($content->srt_file_en && $content->srt_file_ar)
                                <button type="button" class="btn btn-outline-dark px-3" data-lang="both" onclick="setSubtitleLang('both')">Dual (En + Ar)</button>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

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
                    <div class="mt-4 d-flex flex-wrap gap-2 mb-4">
                        @foreach(array_map('trim', explode(',', $content->tags)) as $tag)
                            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill small">{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif
                    
                    <!-- Tabbed container for Info vs Transcripts -->
                    <div class="transcript-tab-container mt-4 mb-2">
                        <div class="transcript-tabs">
                            <button type="button" class="transcript-tab-btn active" onclick="switchWatchTab('overview')">
                                <i class="bi bi-info-circle me-1"></i> Lesson Info
                            </button>
                            @if($content->srt_file_en || $content->srt_file_ar)
                            <button type="button" class="transcript-tab-btn" id="btn-transcript-tab" onclick="switchWatchTab('transcript')">
                                <i class="bi bi-file-text me-1"></i> Live Translation & Transcript
                            </button>
                            @endif
                        </div>
                        
                        <!-- Overview Panel -->
                        <div class="transcript-panel active" id="panel-overview">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="lesson-meta-item">
                                        <span class="meta-label"><i class="bi bi-briefcase"></i> Use Case</span>
                                        <span class="meta-value text-muted" style="font-size: 13px;">Automating recurring professional workflows using specialized AI interactions and real-time behavioral mapping.</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="lesson-meta-item">
                                        <span class="meta-label"><i class="bi bi-person-badge"></i> Role Relevance</span>
                                        <span class="meta-value text-muted" style="font-size: 13px;">Essential for professionals looking to minimize cognitive load during tool-switching and repetitive digital tasks.</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="lesson-meta-item">
                                        <span class="meta-label"><i class="bi bi-journal-check"></i> Lesson Outcome</span>
                                        <span class="meta-value text-muted" style="font-size: 13px;">Competency in deploying modern AI strategies to save at least 15-20% of daily digital operation time.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Transcript Panel -->
                        @if($content->srt_file_en || $content->srt_file_ar)
                        <div class="transcript-panel" id="panel-transcript">
                            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                <div class="text-muted small">
                                    <i class="bi bi-info-circle me-1"></i> Click on any line below to jump directly to that part of the video!
                                </div>
                                <div class="position-relative">
                                    <input type="text" id="transcript-search" class="form-control form-control-sm pe-4 rounded-pill" placeholder="Search transcript..." onkeyup="filterTranscript()">
                                    <i class="bi bi-search position-absolute text-muted" style="right:12px;top:50%;transform:translateY(-50%);font-size:11px;"></i>
                                </div>
                            </div>
                            <div class="transcript-scroll-area" id="transcript-lines-list">
                                <div class="text-center py-4 text-muted">
                                    <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                                    <div>Loading synced translation...</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                @if($roadmapContext)
                    <div class="sidebar-label">Roadmap Playlist</div>
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header text-white py-3 px-3" style="background: linear-gradient(135deg,#4f46e5,#7c3aed);">
                            <div class="small opacity-75 fw-bold text-uppercase" style="font-size: 10px; letter-spacing: 0.05em;">Playing from Roadmap</div>
                            <h6 class="fw-bold mb-0 text-truncate" title="{{ $roadmapContext->title }}">{{ $roadmapContext->title }}</h6>
                        </div>
                        <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                            @php $lessonNumber = 1; @endphp
                            @foreach($roadmapData as $phaseIndex => $phase)
                                <div class="px-3 py-2 border-bottom" style="background:#f8fafc;">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($phase['tool'] && $phase['tool']->logo)
                                            <img src="{{ asset($phase['tool']->logo) }}" alt="{{ $phase['tool']->name }}" style="width:22px;height:22px;object-fit:contain;">
                                        @else
                                            <i class="bi bi-gear-fill text-primary"></i>
                                        @endif
                                        <div style="min-width:0;flex:1;">
                                            <div class="small fw-bold text-dark text-truncate">{{ $phase['tool']->name ?? ($phase['name'] ?? 'Phase '.($phaseIndex + 1)) }}</div>
                                            <div class="text-muted" style="font-size:10px;">{{ $phase['completed'] }} of {{ $phase['total'] }} lessons complete</div>
                                        </div>
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill" style="font-size:10px;">{{ $phase['percent'] }}%</span>
                                    </div>
                                </div>
                                @foreach($phase['contents'] as $lesson)
                                    <a href="{{ route('learn.watch', [$lesson, 'roadmap_id' => $roadmapContext->id]) }}"
                                       class="rec-card-mini border-0 rounded-0 m-0 border-bottom text-decoration-none {{ $lesson->id == $content->id ? 'bg-light' : '' }}"
                                       style="padding: 14px 15px;">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="lesson-num-circle flex-shrink-0 {{ $lesson->id == $content->id ? 'text-white' : '' }}"
                                                 style="{{ $lesson->id == $content->id ? 'background:linear-gradient(135deg,#4f46e5,#818cf8);' : '' }}">
                                                {{ $lessonNumber++ }}
                                            </div>
                                            <div style="flex:1;min-width:0;">
                                                <h4 class="small fw-bold mb-0 text-truncate {{ $lesson->id == $content->id ? 'text-primary' : 'text-dark' }}">
                                                    {{ $lesson->title }}
                                                </h4>
                                                <div class="d-flex align-items-center gap-2 mt-1">
                                                    @if(($lesson->completion_pct ?? 0) > 0)
                                                        <span class="text-primary fw-bold" style="font-size: 10px;">{{ $lesson->completion_pct }}%</span>
                                                    @endif
                                                    @if($lesson->duration_label)
                                                        <span class="text-muted" style="font-size: 10px;">{{ $lesson->duration_label }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($lesson->id == $content->id)
                                                <i class="bi bi-play-circle-fill text-primary"></i>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            @endforeach
                        </div>
                        <div class="card-footer bg-white border-top text-center py-2">
                            <a href="{{ route('roadmap.show', $roadmapContext) }}" class="small fw-bold text-primary text-decoration-none">
                                <i class="bi bi-map me-1"></i>Back to Roadmap
                            </a>
                        </div>
                    </div>
                @elseif($course)
                    <div class="sidebar-label">Course Curriculum</div>
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-dark text-white py-3 px-3">
                            <div class="small opacity-75 fw-bold text-uppercase" style="font-size: 10px; letter-spacing: 0.05em;">Playing from Course</div>
                            <h6 class="fw-bold mb-0 text-truncate" title="{{ $course->title }}">{{ $course->title }}</h6>
                        </div>
                        <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                            @foreach($course->contents as $lesson)
                                <a href="{{ route('learn.watch', [$lesson, 'course_id' => $course->id]) }}" class="rec-card-mini border-0 rounded-0 m-0 border-bottom {{ $lesson->id == $content->id ? 'bg-light' : '' }}" style="padding: 15px;">
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
                <div id="player-wrap" class="position-relative w-100 h-100">
                    @if($content->video_url && (str_contains($content->video_url, 'amazonaws.com') || str_ends_with($content->video_url, '.mp4')))
                        <video id="player" playsinline controls data-poster="{{ $content->thumbnail_url }}">
                            <source src="{{ $content->video_url }}" type="video/mp4" />
                        </video>
                    @else
                        <div id="player" data-plyr-provider="youtube" data-plyr-embed-id="{{ $content->youtube_id }}"></div>
                    @endif

                    <!-- Custom Subtitles overlay -->
                    <div id="custom-subtitles" class="custom-subtitles-overlay d-none">
                        <div id="sub-en" class="subtitle-text subtitle-en d-none"></div>
                        <div id="sub-ar" class="subtitle-text subtitle-ar d-none" dir="rtl"></div>
                    </div>

                    @php
                        $nextLessonOverlay = null;
                        $nextLessonOverlayUrl = null;
                        if ($roadmapContext && $roadmapContents->count() > 0) {
                            $currentIdx = $roadmapContents->search(fn($c) => $c->id == $content->id);
                            if ($currentIdx !== false && $currentIdx + 1 < $roadmapContents->count()) {
                                $nextLessonOverlay = $roadmapContents[$currentIdx + 1];
                                $nextLessonOverlayUrl = route('learn.watch', [$nextLessonOverlay, 'roadmap_id' => $roadmapContext->id]);
                            }
                        }
                    @endphp

                    @if($nextLessonOverlay)
                    <!-- YouTube-style Next Video Overlay Button -->
                    <div id="next-video-overlay" style="
                        position: absolute;
                        top: 16px;
                        right: 16px;
                        z-index: 20;
                        opacity: 0;
                        transition: opacity 0.25s ease;
                        pointer-events: none;
                    ">
                        <a href="{{ $nextLessonOverlayUrl }}"
                           style="
                            display: flex;
                            align-items: center;
                            gap: 10px;
                            background: rgba(15,23,42,0.85);
                            backdrop-filter: blur(8px);
                            -webkit-backdrop-filter: blur(8px);
                            border: 1px solid rgba(255,255,255,0.12);
                            border-radius: 12px;
                            padding: 10px 16px;
                            color: #fff;
                            text-decoration: none;
                            font-size: 13px;
                            font-weight: 700;
                            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
                            white-space: nowrap;
                            max-width: 260px;
                           "
                           title="Next: {{ $nextLessonOverlay->title }}"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16" style="flex-shrink:0;">
                                <path d="M12.5 4a.5.5 0 0 0-1 0v3.248L5.233 3.612C4.693 3.264 4 3.652 4 4.308v7.384c0 .656.693 1.044 1.233.696L11.5 8.752V12a.5.5 0 0 0 1 0V4z"/>
                            </svg>
                            <div style="min-width:0;">
                                <div style="font-size:10px;opacity:.65;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Up Next</div>
                                <div style="font-size:12px;font-weight:700;overflow:hidden;text-overflow:ellipsis;max-width:180px;">{{ Str::limit($nextLessonOverlay->title, 40) }}</div>
                            </div>
                        </a>
                    </div>
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

            @php
                // Compute next lesson for the "Up Next" card below progress bar
                $nextLesson = null;
                $nextLessonUrl = null;
                if ($roadmapContext && $roadmapContents->count() > 0) {
                    $currentIndex = $roadmapContents->search(fn($c) => $c->id == $content->id);
                    if ($currentIndex !== false && $currentIndex + 1 < $roadmapContents->count()) {
                        $nextLesson = $roadmapContents[$currentIndex + 1];
                        $nextLessonUrl = route('learn.watch', [$nextLesson, 'roadmap_id' => $roadmapContext->id]);
                    }
                }
            @endphp

            @if($nextLesson)
            <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-4 border bg-white" style="border-color: rgba(79,70,229,0.15) !important; box-shadow: 0 2px 10px rgba(79,70,229,0.06);">
                <div style="flex:1;min-width:0;">
                    <div class="text-muted" style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.04em;">Up Next</div>
                    <div class="fw-bold small text-truncate mt-1">{{ $nextLesson->title }}</div>
                    @if($nextLesson->duration_label)
                        <div class="text-muted" style="font-size:11px;">{{ $nextLesson->duration_label }}</div>
                    @endif
                </div>
                <a href="{{ $nextLessonUrl }}" class="btn btn-sm btn-primary rounded-pill px-3 flex-shrink-0" style="background:linear-gradient(135deg,#4f46e5,#7c3aed);border:none;" id="btn-next-video">
                    <i class="bi bi-skip-end-fill me-1"></i> Next
                </a>
            </div>
            @endif

            <!-- Subtitle Translation Preferences -->
            @if($content->srt_file_en || $content->srt_file_ar)
            <div class="translation-controls-card p-3 mb-4 rounded-4 border bg-white shadow-sm">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-translate text-primary fs-5"></i>
                        <div>
                            <div class="fw-bold small">Video Subtitles & Translation</div>
                            <div class="text-muted" style="font-size: 11px;">Select subtitle preference for learning</div>
                        </div>
                    </div>
                    <div class="btn-group btn-group-sm rounded-3 overflow-hidden" role="group" aria-label="Subtitle Language">
                        <button type="button" class="btn btn-dark active px-3" data-lang="off" onclick="setSubtitleLang('off')">Off</button>
                        @if($content->srt_file_en)
                            <button type="button" class="btn btn-outline-dark px-3" data-lang="en" onclick="setSubtitleLang('en')">English</button>
                        @endif
                        @if($content->srt_file_ar)
                            <button type="button" class="btn btn-outline-dark px-3" data-lang="ar" onclick="setSubtitleLang('ar')">Arabic (العربية)</button>
                        @endif
                        @if($content->srt_file_en && $content->srt_file_ar)
                            <button type="button" class="btn btn-outline-dark px-3" data-lang="both" onclick="setSubtitleLang('both')">Dual (En + Ar)</button>
                        @endif
                    </div>
                </div>
            </div>
            @endif

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

                <!-- Tabbed container for Info vs Transcripts -->
                <div class="transcript-tab-container mt-4 mb-2">
                    <div class="transcript-tabs">
                        <button type="button" class="transcript-tab-btn active" onclick="switchWatchTab('overview')">
                            <i class="bi bi-info-circle me-1"></i> Lesson Info
                        </button>
                        @if($content->srt_file_en || $content->srt_file_ar)
                        <button type="button" class="transcript-tab-btn" id="btn-transcript-tab" onclick="switchWatchTab('transcript')">
                            <i class="bi bi-file-text me-1"></i> Live Translation & Transcript
                        </button>
                        @endif
                    </div>
                    
                    <!-- Overview Panel -->
                    <div class="transcript-panel active" id="panel-overview">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="lesson-meta-item">
                                    <span class="meta-label"><i class="bi bi-briefcase"></i> Use Case</span>
                                    <span class="meta-value">Automating recurring professional workflows using specialized AI interactions and real-time behavioral mapping.</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="lesson-meta-item">
                                    <span class="meta-label"><i class="bi bi-person-badge"></i> Role Relevance</span>
                                    <span class="meta-value">Essential for professionals looking to minimize cognitive load during tool-switching and repetitive digital tasks.</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="lesson-meta-item">
                                    <span class="meta-label"><i class="bi bi-journal-check"></i> Lesson Outcome</span>
                                    <span class="meta-value">Competency in deploying modern AI strategies to save at least 15-20% of daily digital operation time.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Transcript Panel -->
                    @if($content->srt_file_en || $content->srt_file_ar)
                    <div class="transcript-panel" id="panel-transcript">
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                            <div class="text-muted small">
                                <i class="bi bi-info-circle me-1"></i> Click on any line below to jump directly to that part of the video!
                            </div>
                            <div class="position-relative">
                                <input type="text" id="transcript-search" class="form-control form-control-sm pe-4 rounded-pill" placeholder="Search transcript..." onkeyup="filterTranscript()">
                                <i class="bi bi-search position-absolute text-muted" style="right:12px;top:50%;transform:translateY(-50%);font-size:11px;"></i>
                            </div>
                        </div>
                        <div class="transcript-scroll-area" id="transcript-lines-list">
                            <div class="text-center py-4 text-muted">
                                <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                                <div>Loading synced translation...</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <a href="#" class="btn-report-outdated" onclick="event.preventDefault(); reportOutdated({{ $content->id }})">
                    <i class="bi bi-flag"></i> Report this content as outdated
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sidebar-section">
                @if($roadmapContext)
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header text-white py-3 px-4" style="background: linear-gradient(135deg,#4f46e5,#7c3aed);">
                            <div class="small opacity-75 fw-bold text-uppercase mb-1" style="font-size: 11px;">Playing from Roadmap</div>
                            <h6 class="fw-bold mb-0 text-truncate" title="{{ $roadmapContext->title }}">{{ $roadmapContext->title }}</h6>
                        </div>
                        <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                            @php $lessonNumber = 1; @endphp
                            @foreach($roadmapData as $phaseIndex => $phase)
                                <div class="px-4 py-3 border-bottom" style="background:#f8fafc;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        @if($phase['tool'] && $phase['tool']->logo)
                                            <img src="{{ asset($phase['tool']->logo) }}" alt="{{ $phase['tool']->name }}" style="width:24px;height:24px;object-fit:contain;">
                                        @else
                                            <i class="bi bi-gear-fill text-primary"></i>
                                        @endif
                                        <div class="fw-800 small text-dark text-truncate" style="min-width:0;flex:1;">
                                            {{ $phase['tool']->name ?? ($phase['name'] ?? 'Phase '.($phaseIndex + 1)) }}
                                        </div>
                                        <span class="fw-bold text-primary" style="font-size:11px;">{{ $phase['percent'] }}%</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height:4px;background:#e2e8f0;">
                                        <div class="progress-bar rounded-pill" style="width:{{ $phase['percent'] }}%;background:linear-gradient(90deg,#4f46e5,#818cf8);"></div>
                                    </div>
                                    <div class="text-muted mt-1" style="font-size:10px;">{{ $phase['completed'] }} of {{ $phase['total'] }} lessons complete</div>
                                </div>
                                @foreach($phase['contents'] as $lesson)
                                    @php
                                        $lessonPct = $lesson->completion_pct ?? 0;
                                        $lessonDone = $lesson->is_completed ?? false;
                                        $currentNum = $lessonNumber++;
                                    @endphp
                                    <a href="{{ route('learn.watch', [$lesson, 'roadmap_id' => $roadmapContext->id]) }}"
                                       class="curriculum-item d-flex align-items-center gap-3 py-3 px-4 text-decoration-none {{ $lesson->id == $content->id ? 'active' : '' }}">
                                        <div class="lesson-num-circle flex-shrink-0 {{ $lesson->id == $content->id ? 'bg-primary text-white' : ($lessonDone ? 'bg-success text-white' : '') }}">
                                            @if($lessonDone && $lesson->id != $content->id)
                                                <i class="bi bi-check2" style="font-size:12px;"></i>
                                            @else
                                                {{ $currentNum }}
                                            @endif
                                        </div>
                                        <div style="min-width:0;flex:1;">
                                            <div class="fw-bold small text-truncate">{{ $lesson->title }}</div>
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                @if($lessonPct > 0 && !$lessonDone)
                                                    <div style="flex:1;height:3px;background:#e2e8f0;border-radius:4px;overflow:hidden;">
                                                        <div style="width:{{ $lessonPct }}%;height:100%;background:linear-gradient(90deg,#4f46e5,#818cf8);border-radius:4px;"></div>
                                                    </div>
                                                    <span class="text-primary fw-bold" style="font-size:10px;white-space:nowrap;">{{ $lessonPct }}%</span>
                                                @elseif($lessonDone)
                                                    <span class="text-success fw-bold" style="font-size:10px;">Completed</span>
                                                @elseif($lesson->duration_label)
                                                    <span class="text-muted" style="font-size:10px;">{{ $lesson->duration_label }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($lesson->id == $content->id)
                                            <i class="bi bi-play-circle-fill text-primary ms-auto flex-shrink-0"></i>
                                        @elseif($lessonDone)
                                            <i class="bi bi-check-circle-fill text-success ms-auto flex-shrink-0" style="font-size:14px;"></i>
                                        @endif
                                    </a>
                                @endforeach
                            @endforeach
                        </div>
                        <div class="card-footer bg-white border-top text-center py-2">
                            <a href="{{ route('roadmap.show', $roadmapContext) }}" class="small fw-bold text-primary text-decoration-none">
                                <i class="bi bi-map me-1"></i>Back to Roadmap
                            </a>
                        </div>
                    </div>
                @elseif($course)
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-dark text-white py-3 px-4">
                            <div class="small opacity-75 fw-bold text-uppercase mb-1" style="font-size: 11px;">Current Course</div>
                            <h6 class="fw-bold mb-0 text-truncate" title="{{ $course->title }}">{{ $course->title }}</h6>
                        </div>
                        <div class="card-body p-0" style="max-height: 500px; overflow-y: auto;">
                            @foreach($course->contents as $lesson)
                                <a href="{{ route('learn.watch', [$lesson, 'course_id' => $course->id]) }}" class="curriculum-item d-flex align-items-center gap-3 py-3 px-4 text-decoration-none {{ $lesson->id == $content->id ? 'active' : '' }}">
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

    // Resume playback from saved progress
    const savedWatchedSeconds = {{ $progress ? (int) $progress->watched_seconds : 0 }};
    const savedCompletionPercent = {{ $progress ? (float) $progress->completion_percent : 0 }};
    const shouldResume = isAuthenticated && savedWatchedSeconds > 5 && savedCompletionPercent < 95;

    const player = new Plyr('#player', {
        youtube: { noCookie: true, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1 }
    });

    // ─── RESUME FROM LAST POSITION ─────────────────────────────────────────────
    // For YouTube + Plyr: player.duration is 0 until the user clicks play.
    // The ONLY reliable hook is the first 'playing' event.
    // We hook it, immediately seek to saved time, which causes a tiny initial
    // flash at 0:00 but is the same behaviour as YouTube's own resume.
    if (shouldResume) {
        let seekApplied = false;
        const seekTime = savedWatchedSeconds;

        if (!isYoutube) {
            // For native HTML5 / MP4 videos: use loadedmetadata which fires before play
            player.on('loadedmetadata', () => {
                if (!seekApplied) {
                    try { player.currentTime = seekTime; seekApplied = true; } catch(e) {}
                }
            });
            player.on('canplay', () => {
                if (!seekApplied) {
                    try { player.currentTime = seekTime; seekApplied = true; } catch(e) {}
                }
            });
        }

        // For BOTH YouTube and HTML5: hook first 'playing' as the guaranteed fallback.
        // On first play, seek immediately — player.currentTime is settable at this moment.
        const onFirstPlay = () => {
            if (!seekApplied) {
                try {
                    player.currentTime = seekTime;
                    seekApplied = true;
                } catch(e) {}
            }
            // Remove listener so it only runs once
            player.off('playing', onFirstPlay);
        };
        player.on('playing', onFirstPlay);
    }

    // ─── NEXT VIDEO OVERLAY SHOW/HIDE ──────────────────────────────────────────
    const nextOverlay = document.getElementById('next-video-overlay');
    if (nextOverlay) {
        const playerWrap = document.getElementById('player-wrap');
        playerWrap.addEventListener('mouseenter', () => {
            nextOverlay.style.opacity = '1';
            nextOverlay.style.pointerEvents = 'auto';
        });
        playerWrap.addEventListener('mouseleave', () => {
            nextOverlay.style.opacity = '0';
            nextOverlay.style.pointerEvents = 'none';
        });
        // Also show briefly when near the end of video
        player.on('timeupdate', () => {
            if (player.duration > 0 && (player.duration - player.currentTime) < 20) {
                nextOverlay.style.opacity = '1';
                nextOverlay.style.pointerEvents = 'auto';
            }
        });
    }

    // Subtitle & Translation timing configuration
    @if($content->srt_file_en)
        const srtUrlEn = "{{ route('subtitles.convert', ['url' => $content->srt_file_en]) }}";
    @else
        const srtUrlEn = null;
    @endif

    @if($content->srt_file_ar)
        const srtUrlAr = "{{ route('subtitles.convert', ['url' => $content->srt_file_ar]) }}";
    @else
        const srtUrlAr = null;
    @endif

    let englishCues = [];
    let arabicCues = [];
    let mergedCuesList = [];
    let activeSubtitleLang = localStorage.getItem('activeSubtitleLang') || 'off'; // Persist choice!
    let lastHighlightedIndex = -1;

    // Parse VTT format file
    function parseVTT(text) {
        const cues = [];
        const lines = text.split(/\r?\n/);
        let currentCue = null;
        const timeRegex = /(\d{2}):(\d{2}):(\d{2})[.,](\d{3})\s*-->\s*(\d{2}):(\d{2}):(\d{2})[.,](\d{3})/;

        for (let i = 0; i < lines.length; i++) {
            const line = lines[i].trim();
            if (!line) continue;

            const match = timeRegex.exec(line);
            if (match) {
                const start = parseFloat(match[1]) * 3600 + parseFloat(match[2]) * 60 + parseFloat(match[3]) + parseFloat(match[4]) / 1000;
                const end = parseFloat(match[5]) * 3600 + parseFloat(match[6]) * 60 + parseFloat(match[7]) + parseFloat(match[8]) / 1000;
                currentCue = { start, end, text: '' };
                cues.push(currentCue);
            } else if (currentCue) {
                if (line !== 'WEBVTT' && !/^\d+$/.test(line)) {
                    if (currentCue.text) {
                        currentCue.text += ' ' + line;
                    } else {
                        currentCue.text = line;
                    }
                }
            }
        }
        return cues;
    }

    // Merge English and Arabic subtitles into a unified structure
    function mergeCues(enCues, arCues) {
        const merged = [];
        
        if (enCues.length > 0 && arCues.length === 0) {
            return enCues.map(c => ({ start: c.start, end: c.end, en: c.text, ar: '' }));
        }
        
        if (arCues.length > 0 && enCues.length === 0) {
            return arCues.map(c => ({ start: c.start, end: c.end, en: '', ar: c.text }));
        }
        
        enCues.forEach(enCue => {
            const mid = (enCue.start + enCue.end) / 2;
            const arCue = arCues.find(ar => mid >= ar.start && mid <= ar.end) || 
                          arCues.find(ar => Math.abs(ar.start - enCue.start) < 1.0);
                          
            merged.push({
                start: enCue.start,
                end: enCue.end,
                en: enCue.text,
                ar: arCue ? arCue.text : ''
            });
        });
        
        arCues.forEach(arCue => {
            const isMerged = merged.some(m => {
                const mid = (m.start + m.end) / 2;
                return mid >= arCue.start && mid <= arCue.end;
            });
            
            if (!isMerged) {
                const index = merged.findIndex(m => m.start > arCue.start);
                const newline = { start: arCue.start, end: arCue.end, en: '', ar: arCue.text };
                if (index === -1) {
                    merged.push(newline);
                } else {
                    merged.splice(index, 0, newline);
                }
            }
        });
        
        return merged;
    }

    // Helper format seconds -> MM:SS
    function formatTime(seconds) {
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = Math.floor(seconds % 60);
        
        const pad = (num) => String(num).padStart(2, '0');
        
        if (h > 0) {
            return `${h}:${pad(m)}:${pad(s)}`;
        }
        return `${pad(m)}:${pad(s)}`;
    }

    // Update active subtitles on screen
    function updateSubtitles() {
        if (!player || activeSubtitleLang === 'off') {
            const container = document.getElementById('custom-subtitles');
            if (container) container.classList.add('d-none');
            return;
        }

        const currentTime = player.currentTime;
        let showEn = false;
        let showAr = false;
        let enText = '';
        let arText = '';

        if (activeSubtitleLang === 'en' || activeSubtitleLang === 'both') {
            const cue = englishCues.find(c => currentTime >= c.start && currentTime <= c.end);
            if (cue) {
                enText = cue.text;
                showEn = true;
            }
        }

        if (activeSubtitleLang === 'ar' || activeSubtitleLang === 'both') {
            const cue = arabicCues.find(c => currentTime >= c.start && currentTime <= c.end);
            if (cue) {
                arText = cue.text;
                showAr = true;
            }
        }

        const container = document.getElementById('custom-subtitles');
        const enDiv = document.getElementById('sub-en');
        const arDiv = document.getElementById('sub-ar');

        if (container) {
            if (showEn || showAr) {
                container.classList.remove('d-none');
                
                if (showEn && enDiv) {
                    enDiv.classList.remove('d-none');
                    enDiv.innerHTML = enText;
                } else if (enDiv) {
                    enDiv.classList.add('d-none');
                }

                if (showAr && arDiv) {
                    arDiv.classList.remove('d-none');
                    arDiv.innerHTML = arText;
                } else if (arDiv) {
                    arDiv.classList.add('d-none');
                }
            } else {
                container.classList.add('d-none');
            }
        }
    }

    // Highlight current transcript line and scroll
    function highlightActiveTranscriptLine() {
        if (!player || mergedCuesList.length === 0) return;
        
        const currentTime = player.currentTime;
        const activeIndex = mergedCuesList.findIndex(c => currentTime >= c.start && currentTime <= c.end);
        
        if (activeIndex !== lastHighlightedIndex) {
            if (lastHighlightedIndex !== -1) {
                const prev = document.getElementById(`t-line-${lastHighlightedIndex}`);
                if (prev) prev.classList.remove('active');
            }
            
            if (activeIndex !== -1) {
                const current = document.getElementById(`t-line-${activeIndex}`);
                if (current) {
                    current.classList.add('active');
                    
                    const scrollArea = document.querySelector('.transcript-scroll-area');
                    if (scrollArea) {
                        const lineRect = current.offsetTop - scrollArea.offsetTop;
                        scrollArea.scrollTo({
                            top: lineRect - (scrollArea.clientHeight / 2) + (current.clientHeight / 2),
                            behavior: 'smooth'
                        });
                    }
                }
            }
            lastHighlightedIndex = activeIndex;
        }
    }

    // Subtitle lang setter
    window.setSubtitleLang = function(lang) {
        activeSubtitleLang = lang;
        localStorage.setItem('activeSubtitleLang', lang);
        
        updateSubtitleControlsUI();
        updateSubtitles();
    };

    function updateSubtitleControlsUI() {
        document.querySelectorAll('.translation-controls-card button').forEach(btn => {
            if (btn.dataset.lang === activeSubtitleLang) {
                btn.classList.remove('btn-outline-dark');
                btn.classList.add('btn-dark');
                btn.classList.add('active');
            } else {
                btn.classList.remove('btn-dark');
                btn.classList.remove('active');
                btn.classList.add('btn-outline-dark');
            }
        });
    }

    // Switch between info and transcript tabs
    window.switchWatchTab = function(tabName) {
        document.querySelectorAll('.transcript-tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.transcript-panel').forEach(p => p.classList.remove('active'));
        
        if (tabName === 'overview') {
            const btn = document.querySelector('.transcript-tab-btn[onclick*="overview"]');
            if (btn) btn.classList.add('active');
            const panel = document.getElementById('panel-overview');
            if (panel) panel.classList.add('active');
        } else if (tabName === 'transcript') {
            const btn = document.getElementById('btn-transcript-tab');
            if (btn) btn.classList.add('active');
            const panel = document.getElementById('panel-transcript');
            if (panel) panel.classList.add('active');
        }
    };

    // Render loaded transcript lines
    function renderTranscript() {
        const listEl = document.getElementById('transcript-lines-list');
        if (!listEl) return;
        
        mergedCuesList = mergeCues(englishCues, arabicCues);
        
        if (mergedCuesList.length === 0) {
            listEl.innerHTML = '<div class="text-center py-4 text-muted">No transcription lines available.</div>';
            return;
        }
        
        let html = '';
        mergedCuesList.forEach((cue, index) => {
            html += `
                <div class="transcript-line" id="t-line-${index}" onclick="jumpToTime(${cue.start})">
                    <span class="transcript-timestamp">${formatTime(cue.start)}</span>
                    <div class="transcript-text-wrapper">
                        ${cue.en ? `<span class="transcript-text-en">${cue.en}</span>` : ''}
                        ${cue.ar ? `<span class="transcript-text-ar">${cue.ar}</span>` : ''}
                    </div>
                </div>
            `;
        });
        listEl.innerHTML = html;
    }

    // Jump video to specified time
    window.jumpToTime = function(seconds) {
        if (player) {
            player.currentTime = seconds;
            player.play();
        }
    };

    // Filter transcript via search
    window.filterTranscript = function() {
        const query = document.getElementById('transcript-search').value.toLowerCase();
        const lines = document.querySelectorAll('.transcript-line');
        
        lines.forEach((line, index) => {
            const cue = mergedCuesList[index];
            if (!cue) return;
            
            const matchEn = cue.en && cue.en.toLowerCase().includes(query);
            const matchAr = cue.ar && cue.ar.toLowerCase().includes(query);
            
            if (matchEn || matchAr) {
                line.style.display = 'flex';
            } else {
                line.style.display = 'none';
            }
        });
    };

    // Load Subtitles from conversion API
    async function loadSubtitles() {
        const loaders = [];
        
        if (srtUrlEn) {
            loaders.push(
                fetch(srtUrlEn)
                    .then(res => res.ok ? res.text() : Promise.reject())
                    .then(text => { englishCues = parseVTT(text); })
                    .catch(() => console.warn("Failed to load English subtitles"))
            );
        }
        
        if (srtUrlAr) {
            loaders.push(
                fetch(srtUrlAr)
                    .then(res => res.ok ? res.text() : Promise.reject())
                    .then(text => { arabicCues = parseVTT(text); })
                    .catch(() => console.warn("Failed to load Arabic subtitles"))
            );
        }
        
        if (loaders.length > 0) {
            await Promise.all(loaders);
            renderTranscript();
        }
        
        updateSubtitleControlsUI();
    }

    // Event hooks on player time update
    player.on('timeupdate', () => {
        updateSubtitles();
        highlightActiveTranscriptLine();
    });

    // Run load
    document.addEventListener('DOMContentLoaded', () => {
        loadSubtitles();
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
        const saveProgress = (force = false) => {
            if (!player) return;
            if (!force && !player.playing) return;

            const current = Number(player.currentTime || 0);
            const duration = Number(player.duration || 0);
            const safeDuration = Math.max(0, duration);
            const safeWatched = Math.max(0, current);

            fetch("{{ route('learn.progress.save') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    content_id: {{ $content->id }},
                    // Avoid truncation issues at the end; backend will clamp to [0..100]
                    watched_seconds: Math.min(Math.floor(safeWatched), Math.floor(safeDuration)),
                    duration_seconds: Math.floor(safeDuration)
                })
            })
            .then(r => r.json())
            .then(data => {
                const bar = document.getElementById('progress-bar');
                const label = document.getElementById('progress-label');
                if (bar) bar.style.width = data.completion_percent + '%';
                if (label) label.textContent = Math.round(data.completion_percent) + '%';
            });
        };

        // Save immediately when playback starts (helps accuracy)
        player.on('playing', () => {
            saveProgress(true);
        });

        // Save at the very end so 100% is reflected
        player.on('ended', () => {
            saveProgress(true);
        });

        // Periodic fallback
        setInterval(() => {
            saveProgress(false);
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
