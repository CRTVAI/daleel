@extends('layouts.user')

@section('title', 'Learning Roadmap — Daleel AI')

@section('content')
<style>
    /* ============================================================
       PROFESSIONAL, LIGHT & CLEAN — MOBILE OPTIMIZED
       ============================================================ */
    :root {
        --primary: #1e3a5f;
        --primary-light: #eef3f9;
        --primary-soft: #3b6a9e;
        --accent: #4f7fb3;
        --success: #2b7a5e;
        --warning: #b16f2b;
        --gray-50: #f9fafc;
        --gray-100: #f1f4f8;
        --gray-200: #e3e8ef;
        --gray-300: #cdd5e0;
        --gray-500: #8b99ab;
        --gray-700: #3d4a5c;
        --gray-900: #1e293b;
        --shadow-sm: 0 2px 8px rgba(30, 58, 95, 0.05);
        --shadow-md: 0 6px 24px rgba(30, 58, 95, 0.07);
        --radius-lg: 20px;
        --radius-md: 14px;
        --radius-sm: 10px;
        --transition: 0.25s ease;
    }

    .roadmap-page {
        background: #f5f7fb;
        padding: 16px 0 40px;
        min-height: 100vh;
    }

    /* ===== STAT CARDS ===== */
    .stat-card {
        background: #fff;
        border-radius: var(--radius-md);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        transition: transform var(--transition), box-shadow var(--transition);
        height: 100%;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        background: var(--primary-light);
        color: var(--primary);
    }
    .stat-icon.green { background: #e3f0ec; color: var(--success); }
    .stat-icon.amber { background: #f6efe6; color: var(--warning); }

    .stat-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: var(--gray-500);
        margin-bottom: 1px;
    }
    .stat-value {
        font-size: 20px;
        font-weight: 800;
        color: var(--gray-900);
        line-height: 1.2;
    }
    .stat-sub {
        font-size: 12px;
        color: var(--gray-500);
        font-weight: 500;
    }

    /* ===== HERO CARD (matching index banner) ===== */
    .hero-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        border-radius: 24px;
        box-shadow: 0 10px 30px -10px rgba(30, 27, 75, 0.3);
        border: none;
        overflow: hidden;
        position: relative;
    }
    .hero-card::after {
        content: ''; position: absolute; top: -50%; right: -10%; width: 400px; height: 400px;
        background: rgba(99, 102, 241, 0.1); border-radius: 50%;
        pointer-events: none;
    }
    .hero-card .text-light {
        color: rgba(255, 255, 255, 0.85) !important;
    }
    .hero-card .badge-soft {
        background: rgba(255, 255, 255, 0.12);
        color: #b8ccf0;
        font-weight: 600;
        letter-spacing: 0.02em;
        font-size: 11px;
    }

    /* ===== PROGRESS RING ===== */
    .progress-ring {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        background: conic-gradient(#fff {{ $overallProgress * 3.6 }}deg, rgba(255,255,255,0.12) 0deg);
        transition: background 0.4s;
    }
    .progress-ring::after {
        content: '';
        position: absolute;
        inset: 5px;
        background: var(--primary);
        border-radius: 50%;
    }
    .progress-ring span {
        position: relative;
        z-index: 2;
        font-size: 18px;
        font-weight: 800;
        color: #fff;
        letter-spacing: -0.01em;
    }
    .stat-pill {
        background: rgba(255, 255, 255, 0.07);
        border-radius: var(--radius-sm);
        padding: 6px 14px;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid rgba(255,255,255,0.04);
    }
    .stat-pill i { font-size: 16px; color: rgba(255,255,255,0.6); }
    .stat-pill .num { font-weight: 700; color: #fff; font-size: 14px; }
    .stat-pill .lbl { font-size: 10px; color: rgba(255,255,255,0.5); font-weight: 500; }

    /* ===== TIMELINE — MOBILE OPTIMIZED ===== */
    .timeline-wrapper {
        position: relative;
        padding-left: 32px;
    }
    .timeline-line {
        position: absolute;
        left: 11px;
        top: 10px;
        bottom: 10px;
        width: 2px;
        background: var(--gray-300);
        border-radius: 4px;
    }

    .step-item {
        position: relative;
        margin-bottom: 10px;
        animation: fadeInUp 0.4s ease forwards;
        opacity: 0;
    }
    .step-item:nth-child(1) { animation-delay: 0.05s; }
    .step-item:nth-child(2) { animation-delay: 0.10s; }
    .step-item:nth-child(3) { animation-delay: 0.15s; }
    .step-item:nth-child(4) { animation-delay: 0.20s; }
    .step-item:nth-child(5) { animation-delay: 0.25s; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .step-dot {
        position: absolute;
        left: -32px;
        top: 4px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid var(--gray-300);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
        color: var(--gray-500);
        transition: all var(--transition);
        z-index: 2;
        box-shadow: var(--shadow-sm);
        flex-shrink: 0;
    }
    .step-item.completed .step-dot {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
        box-shadow: 0 3px 10px rgba(30, 58, 95, 0.2);
        font-size: 10px;
    }
    .step-item.active .step-dot {
        border-color: var(--primary);
        color: var(--primary);
        width: 30px;
        height: 30px;
        left: -36px;
        top: 2px;
        box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.08);
    }

    /* step card — cleaner spacing */
    .step-card {
        background: #fff;
        border-radius: var(--radius-md);
        padding: 16px 18px;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-sm);
        transition: box-shadow var(--transition), transform var(--transition);
    }
    .step-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-1px);
    }
    .step-card .step-header {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 8px;
    }
    .step-card .step-icon {
        width: 38px;
        height: 38px;
        border-radius: var(--radius-sm);
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        color: var(--primary);
        flex-shrink: 0;
        border: 1px solid var(--gray-200);
        margin-top: 2px;
    }
    .step-card .step-icon img {
        width: 22px;
        height: 22px;
        object-fit: contain;
    }
    .step-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0;
        line-height: 1.3;
    }
    .step-subtitle {
        font-size: 11px;
        color: var(--gray-500);
        font-weight: 500;
        margin: 0;
    }
    .step-progress-bar {
        height: 4px;
        background: var(--gray-200);
        border-radius: 4px;
        overflow: hidden;
        flex: 1;
        min-width: 30px;
    }
    .step-progress-bar .fill {
        height: 100%;
        border-radius: 4px;
        background: var(--primary);
        transition: width 0.6s ease;
    }
    .step-progress-text {
        font-size: 12px;
        font-weight: 700;
        color: var(--gray-700);
        white-space: nowrap;
        min-width: 38px;
        text-align: right;
    }

    /* ===== LESSONS — FULL ROW HIGHLIGHT ===== */
    .step-lessons {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid var(--gray-200);
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .lesson-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: var(--radius-sm);
        text-decoration: none;
        color: var(--gray-700);
        font-size: 13px;
        font-weight: 500;
        transition: all var(--transition);
        background: transparent;
        border: 1px solid transparent;
        width: 100%;
        text-align: left;
        position: relative;
    }
    .lesson-link:hover {
        background: var(--gray-100);
        color: var(--gray-900);
        border-color: var(--gray-200);
    }
    /* Full row highlight for active lesson (next to watch) */
    .lesson-link.active {
        background: var(--primary-light);
        color: var(--primary);
        font-weight: 600;
        border-color: var(--primary);
        box-shadow: 0 2px 8px rgba(30, 58, 95, 0.06);
    }
    /* Completed lessons - subtle styling */
    .lesson-link.done {
        opacity: 0.75;
    }
    .lesson-link.done:hover {
        opacity: 1;
    }
    .lesson-link .check {
        width: 18px;
        height: 18px;
        min-width: 18px;
        border-radius: 50%;
        border: 1.5px solid var(--gray-300);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        flex-shrink: 0;
        color: #fff;
        transition: 0.2s;
        background: #fff;
    }
    .lesson-link.done .check {
        background: var(--success);
        border-color: var(--success);
    }
    .lesson-link.active .check {
        border-color: var(--primary);
        border-width: 1.5px;
        background: #fff;
    }
    .lesson-link.active .check::after {
        content: '';
        width: 6px;
        height: 6px;
        background: var(--primary);
        border-radius: 50%;
    }
    .lesson-link .play-icon {
        margin-left: auto;
        color: var(--gray-500);
        font-size: 14px;
        flex-shrink: 0;
    }
    .lesson-link.active .play-icon {
        color: var(--primary);
        animation: pulse 2s ease-in-out infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.15); }
    }
    .lesson-link .badge-progress {
        font-size: 10px;
        font-weight: 700;
        background: var(--gray-100);
        color: var(--gray-700);
        padding: 1px 8px;
        border-radius: 20px;
        flex-shrink: 0;
    }
    .lesson-link .duration-badge {
        font-size: 10px;
        color: var(--gray-500);
        font-weight: 500;
        flex-shrink: 0;
    }
    .lesson-link .title-text {
        flex: 1;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    /* Watched badge - clean and professional */
    .lesson-link .watched-badge {
        font-size: 9px;
        font-weight: 700;
        color: var(--success);
        background: #e3f0ec;
        padding: 1px 10px;
        border-radius: 20px;
        flex-shrink: 0;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        border: 1px solid rgba(43, 122, 94, 0.15);
    }
    /* "Next up" badge for active lesson */
    .lesson-link .next-badge {
        font-size: 9px;
        font-weight: 700;
        color: var(--primary);
        background: var(--primary-light);
        padding: 1px 10px;
        border-radius: 20px;
        flex-shrink: 0;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        border: 1px solid rgba(30, 58, 95, 0.15);
    }

    /* ===== SIDEBAR ===== */
    .sidebar-card {
        background: #fff;
        border-radius: var(--radius-lg);
        padding: 20px 22px;
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-sm);
        position: sticky;
        top: 100px;
        transition: box-shadow var(--transition);
    }
    .sidebar-card:hover {
        box-shadow: var(--shadow-md);
    }
    .sidebar-card .thumb {
        border-radius: var(--radius-sm);
        background: var(--gray-100);
        overflow: hidden;
        aspect-ratio: 16/9;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--gray-200);
    }
    .sidebar-card .thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .btn-soft-primary {
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: var(--radius-sm);
        padding: 11px 16px;
        font-weight: 700;
        font-size: 14px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: background var(--transition), box-shadow var(--transition);
        box-shadow: var(--shadow-sm);
        text-decoration: none;
    }
    .btn-soft-primary:hover {
        background: var(--primary-soft);
        color: #fff;
        box-shadow: var(--shadow-md);
    }
    .btn-outline-soft {
        background: transparent;
        border: 1.5px solid var(--gray-200);
        color: var(--gray-700);
        border-radius: var(--radius-sm);
        padding: 11px 16px;
        font-weight: 600;
        font-size: 14px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all var(--transition);
        text-decoration: none;
        margin-top: 8px;
    }
    .btn-outline-soft:hover {
        border-color: var(--primary);
        color: var(--primary);
        background: var(--primary-light);
    }

    .badge-soft-success { background: #e3f0ec; color: var(--success); font-weight: 600; font-size: 10px; }
    .badge-soft-primary { background: var(--primary-light); color: var(--primary); font-weight: 600; font-size: 10px; }

    /* ===== MOBILE FIRST TWEAKS ===== */
    @media (max-width: 768px) {
        .roadmap-page { 
            padding: 8px 0 24px; 
        }
        
        /* Container padding */
        .container {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        /* Hero card - reduced padding */
        .hero-card { 
            padding: 16px 14px !important; 
            border-radius: 18px !important;
        }
        .hero-card h1 { 
            font-size: 20px !important; 
            margin-bottom: 4px !important;
        }
        .hero-card .text-light { 
            font-size: 12px !important; 
            margin-bottom: 8px !important;
        }
        .hero-card .badge-soft { 
            font-size: 9px !important; 
            padding: 4px 10px !important;
            margin-bottom: 6px !important;
        }
        .hero-card .btn { 
            font-size: 11px !important; 
            padding: 6px 12px !important;
        }
        
        /* Hero row spacing */
        .hero-card .row.g-3 {
            --bs-gutter-y: 0.5rem !important;
            --bs-gutter-x: 0.5rem !important;
        }

        /* Stats row - 3 columns in one line */
        .row.g-2.g-md-3.mb-4 {
            --bs-gutter-y: 6px !important;
            --bs-gutter-x: 6px !important;
            margin-bottom: 12px !important;
        }

        .stat-card { 
            padding: 10px 10px !important; 
            gap: 8px !important;
            border-radius: 12px !important;
        }
        .stat-value { 
            font-size: 15px !important; 
        }
        .stat-sub { 
            font-size: 10px !important; 
        }
        .stat-label { 
            font-size: 8px !important;
            margin-bottom: 0 !important;
        }
        .stat-icon { 
            width: 30px !important; 
            height: 30px !important; 
            font-size: 13px !important;
            border-radius: 8px !important;
        }

        /* Timeline */
        .timeline-wrapper { padding-left: 24px !important; }
        .step-dot { 
            left: -24px !important; 
            width: 20px !important; 
            height: 20px !important; 
            font-size: 9px !important; 
            top: 3px !important;
        }
        .step-item.active .step-dot {
            width: 24px !important;
            height: 24px !important;
            left: -28px !important;
            top: 1px !important;
        }
        .step-item.completed .step-dot { font-size: 8px !important; }
        
        /* Step cards */
        .step-card { 
            padding: 12px 12px !important; 
            border-radius: 12px !important;
        }
        .step-card .step-header { gap: 8px !important; }
        .step-card .step-icon { 
            width: 30px !important; 
            height: 30px !important; 
            font-size: 13px !important;
            border-radius: 8px !important;
        }
        .step-card .step-icon img { 
            width: 16px !important; 
            height: 16px !important; 
        }
        .step-title { 
            font-size: 13px !important; 
        }
        .step-subtitle { 
            font-size: 10px !important; 
        }
        .step-progress-text { 
            font-size: 11px !important; 
            min-width: 30px !important;
        }
        
        /* Lessons */
        .step-lessons { 
            gap: 1px !important; 
            margin-top: 8px !important;
            padding-top: 8px !important;
        }
        .lesson-link { 
            padding: 6px 8px !important; 
            font-size: 11px !important; 
            gap: 6px !important;
            border-radius: 8px !important;
        }
        .lesson-link .check { 
            width: 15px !important; 
            height: 15px !important; 
            min-width: 15px !important; 
            font-size: 7px !important; 
        }
        .lesson-link .watched-badge, 
        .lesson-link .next-badge { 
            font-size: 7px !important; 
            padding: 0 6px !important; 
        }
        .lesson-link .badge-progress { 
            font-size: 8px !important; 
            padding: 0 6px !important;
        }
        .lesson-link .duration-badge { 
            font-size: 8px !important; 
        }
        .lesson-link .play-icon { 
            font-size: 12px !important; 
        }

        /* Show more button */
        .step-lessons .btn {
            font-size: 10px !important;
            padding: 3px 0 !important;
            border-radius: 8px !important;
        }

        /* Main layout - stack on mobile */
        .roadmap-main { 
            flex-direction: column !important; 
            gap: 12px !important;
        }
        
        /* Sidebar - FULL WIDTH on mobile, keep sticky position as is */
        .roadmap-sidebar { 
            width: 100% !important; 
            flex-shrink: 0 !important;
        }
        .sidebar-card { 
            position: static !important;
            padding: 14px !important;
            border-radius: 16px !important;
            margin-bottom: 12px !important;
        }
        .sidebar-card h5 { 
            font-size: 14px !important; 
        }
        .sidebar-card .thumb { 
            border-radius: 10px !important;
            margin-bottom: 10px !important;
        }
        .sidebar-card .btn-soft-primary { 
            font-size: 13px !important; 
            padding: 10px 14px !important;
            border-radius: 10px !important;
        }
        .sidebar-card .p-2.rounded-3 { 
            padding: 6px !important;
            border-radius: 8px !important;
        }
        .sidebar-card .p-2.rounded-3 div:first-child {
            font-size: 14px !important;
        }
        .sidebar-card .p-2.rounded-3 div:last-child {
            font-size: 9px !important;
        }
        .sidebar-card .text-center.mt-2 {
            font-size: 9px !important;
            margin-top: 6px !important;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        
        .hero-card { 
            padding: 12px 10px !important; 
            border-radius: 14px !important;
        }
        .hero-card h1 { 
            font-size: 18px !important; 
        }
        .hero-card .btn { 
            font-size: 10px !important; 
            padding: 5px 10px !important;
        }
        
        .stat-card { 
            padding: 8px 8px !important; 
            gap: 6px !important;
        }
        .stat-value { 
            font-size: 13px !important; 
        }
        .stat-icon { 
            width: 26px !important; 
            height: 26px !important; 
            font-size: 11px !important;
        }
        
        .step-card { 
            padding: 10px 10px !important; 
        }
        .step-card .step-icon { 
            width: 26px !important; 
            height: 26px !important; 
            font-size: 11px !important;
        }
        .step-title { 
            font-size: 12px !important; 
        }
        .timeline-wrapper { 
            padding-left: 20px !important; 
        }
        .step-dot { 
            left: -20px !important; 
            width: 18px !important; 
            height: 18px !important; 
            font-size: 8px !important; 
            top: 2px !important;
        }
        .step-item.active .step-dot { 
            width: 22px !important; 
            height: 22px !important; 
            left: -24px !important; 
        }
        .lesson-link { 
            font-size: 10px !important; 
            padding: 5px 6px !important; 
        }
        .sidebar-card { 
            padding: 12px !important; 
        }
    }
</style>

<div class="roadmap-page">
    <div class="container">

        {{-- ===== HERO ===== --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="hero-card p-3 p-md-5">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-7">
                            <span class="badge badge-soft px-3 py-2 rounded-pill mb-2 d-inline-flex align-items-center gap-2">
                                <i class="bi bi-compass"></i> Active Journey
                            </span>
                            <h1 class="fw-800 text-white mb-0" style="font-size: clamp(20px, 3vw, 34px);">
                                {{ $overallProgress == 100 ? '🎯 Mission Complete' : 'Continue Your Path' }}
                            </h1>
                            <p class="text-light mb-3" style="opacity: 0.7; font-weight: 400; font-size: 14px;">{{ $roadmap->title }}</p>
                            <div class="d-flex flex-wrap gap-2">
                                @if($currentLesson)
                                <a href="{{ route('learn.watch', [$currentLesson, 'roadmap_id' => $roadmap->id]) }}" 
                                   class="btn btn-light btn-sm rounded-pill fw-700 px-3 d-flex align-items-center gap-2 shadow-sm" style="font-size: 13px;">
                                    <i class="bi bi-play-fill"></i> Continue
                                </a>
                                @endif
                                <a href="{{ route('ai.mentor') }}" class="btn btn-outline-light btn-sm rounded-pill px-3" style="opacity:0.8; font-size: 13px;">
                                    <i class="bi bi-stars me-1"></i> AI Mentor
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-5 d-none d-lg-flex align-items-center justify-content-end gap-4">
                            <div class="text-center">
                                <div class="progress-ring mx-auto mb-2">
                                    <span>{{ $overallProgress }}%</span>
                                </div>
                                <span class="text-light" style="font-size: 10px; font-weight: 600; opacity: 0.6;">Overall</span>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                @php
                                    $watchedH = floor($totalWatchedSeconds/3600);
                                    $watchedM = floor(($totalWatchedSeconds%3600)/60);
                                    $remH = floor($remainingSeconds/3600);
                                    $remM = floor(($remainingSeconds%3600)/60);
                                @endphp
                                <div class="stat-pill">
                                    <i class="bi bi-clock-history"></i>
                                    <span><span class="num">{{ $watchedH }}h {{ $watchedM }}m</span> <span class="lbl">watched</span></span>
                                </div>
                                <div class="stat-pill">
                                    <i class="bi bi-hourglass-split"></i>
                                    <span><span class="num">{{ $remH }}h {{ $remM }}m</span> <span class="lbl">remaining</span></span>
                                </div>
                                <div class="stat-pill">
                                    <i class="bi bi-mortarboard"></i>
                                    <span><span class="num">{{ $lessonsCompleted }}/{{ $totalLessons }}</span> <span class="lbl">lessons</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== STATS ROW ===== --}}
        <div class="row g-2 g-md-3 mb-4">
            <div class="col-4 col-md-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="bi bi-check2-circle"></i></div>
                    <div>
                        <div class="stat-label">Completed</div>
                        <div class="stat-value">{{ $lessonsCompleted }} <span class="stat-sub">/ {{ $totalLessons }}</span></div>
                    </div>
                </div>
            </div>
            <div class="col-4 col-md-4">
                <div class="stat-card">
                    <div class="stat-icon green"><i class="bi bi-clock"></i></div>
                    <div>
                        <div class="stat-label">Time invested</div>
                        <div class="stat-value">{{ $watchedH }}h {{ $watchedM }}m</div>
                    </div>
                </div>
            </div>
            <div class="col-4 col-md-4">
                <div class="stat-card">
                    <div class="stat-icon amber"><i class="bi bi-hourglass"></i></div>
                    <div>
                        <div class="stat-label">Time to finish</div>
                        <div class="stat-value">{{ $remH }}h {{ $remM }}m</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== MAIN LAYOUT ===== --}}
        <div class="roadmap-main d-lg-flex gap-4 align-items-start">

            {{-- ===== TIMELINE ===== --}}
            <div class="roadmap-timeline flex-grow-1">
                <div class="timeline-wrapper">

                    @foreach($roadmapData as $index => $data)
                        @php
                            $isCompleted = $data['percent'] == 100;
                            $isActive    = $data['percent'] > 0 && !$isCompleted;
                            $stepNum     = $index + 1;
                        @endphp
                        <div class="step-item {{ $isCompleted ? 'completed' : ($isActive ? 'active' : '') }}">
                            <div class="step-dot">
                                @if($isCompleted) <i class="bi bi-check-lg"></i> @else {{ $stepNum }} @endif
                            </div>

                            <div class="step-card">
                                <div class="step-header">
                                    <div class="step-icon">
                                        @if($data['tool'] && $data['tool']->logo)
                                            <img src="{{ asset($data['tool']->logo) }}" alt="{{ $data['tool']->name }}">
                                        @else
                                            <i class="bi bi-gear"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="step-title d-flex align-items-center gap-2 flex-wrap">
                                            <span class="text-truncate">{{ $data['tool']->name ?? 'Phase '.$stepNum }}</span>
                                            @if($isCompleted)
                                                <span class="badge-soft-success rounded-pill px-2 py-1" style="font-size:9px; white-space:nowrap;"><i class="bi bi-check-circle"></i> Done</span>
                                            @elseif($isActive)
                                                <span class="badge-soft-primary rounded-pill px-2 py-1" style="font-size:9px; white-space:nowrap;"><i class="bi bi-arrow-right"></i> In Progress</span>
                                            @endif
                                        </div>
                                        <div class="step-subtitle">{{ $data['completed'] }} of {{ $data['total'] }} lessons</div>
                                    </div>
                                    <div class="step-progress-text">{{ $data['percent'] }}%</div>
                                </div>

                                {{-- progress bar --}}
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <div class="step-progress-bar">
                                        <div class="fill" style="width:{{ $data['percent'] }}%;"></div>
                                    </div>
                                </div>

                                {{-- lessons --}}
                                <div class="step-lessons" id="lessons-{{ $data['tool']->id ?? $index }}">
                                    @foreach($data['contents'] as $loopIndex => $content)
                                        @php
                                            // Determine if this is the active lesson (next to watch)
                                            // Active = NOT completed AND (either it's the first incomplete lesson OR it's the current lesson with progress < 100%)
                                            $isDone = $content->is_completed ?? false;
                                            $pct = $content->completion_pct ?? 0;
                                            
                                            // A lesson is "active" if:
                                            // 1. It's not completed (is_done == false)
                                            // 2. It's either the current lesson OR it's the first incomplete lesson in the list
                                            $isActiveLesson = false;
                                            if (!$isDone) {
                                                // If it's the current lesson from controller, mark as active
                                                if ($currentLesson && $currentLesson->id == $content->id) {
                                                    $isActiveLesson = true;
                                                } else {
                                                    // Or if it's the first incomplete lesson we encounter
                                                    static $firstIncompleteFound = false;
                                                    if (!$firstIncompleteFound) {
                                                        $isActiveLesson = true;
                                                        $firstIncompleteFound = true;
                                                    }
                                                }
                                            }
                                            
                                            $durMin = $content->duration_seconds > 0 ? floor($content->duration_seconds / 60) : 0;
                                            $hidden = $loopIndex >= 5;
                                        @endphp
                                        <a href="{{ route('learn.watch', [$content, 'roadmap_id' => $roadmap->id]) }}"
                                           class="lesson-link {{ $isActiveLesson ? 'active' : '' }} {{ $isDone ? 'done' : '' }} {{ $hidden ? 'd-none extra-lesson' : '' }}">
                                            {{-- Check circle --}}
                                            <span class="check">
                                                @if($isDone) <i class="bi bi-check"></i> @endif
                                            </span>
                                            
                                            {{-- Lesson title --}}
                                            <span class="title-text">{{ $content->title }}</span>
                                            
                                            {{-- Status badges --}}
                                            @if($isDone)
                                                <span class="watched-badge"><i class="bi bi-check-circle me-1"></i>Watched</span>
                                            @elseif($isActiveLesson)
                                                <span class="next-badge"><i class="bi bi-arrow-right me-1"></i>Next</span>
                                            @elseif($pct > 0)
                                                <span class="badge-progress">{{ $pct }}%</span>
                                            @endif
                                            
                                            {{-- Duration --}}
                                            @if($durMin > 0)
                                                <span class="duration-badge">{{ $durMin }}m</span>
                                            @endif
                                            
                                            {{-- Play icon for active --}}
                                            @if($isActiveLesson)
                                                <span class="play-icon"><i class="bi bi-play-circle-fill"></i></span>
                                            @endif
                                        </a>
                                    @endforeach

                                    @if($data['contents']->count() > 5)
                                        <button class="btn btn-sm w-100 mt-2 rounded-3 border-0 fw-600 text-primary"
                                                style="background:var(--gray-100); font-size:11px; padding: 4px 0;"
                                                onclick="toggleExtra(this,'lessons-{{ $data['tool']->id ?? $index }}')">
                                            <span><i class="bi bi-chevron-down me-1"></i>Show {{ $data['contents']->count() - 5 }} more</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ===== SIDEBAR ===== --}}
            <div class="roadmap-sidebar" style="width: 330px; flex-shrink: 0;">
                <div class="sidebar-card">
                    @if($currentLesson)
                        @php $cp = $currentProgressRecord; @endphp

                        @if($currentTool)
                            <div class="d-flex align-items-center gap-2 mb-3">
                                @if($currentTool->logo)
                                    <div style="width:28px;height:28px;background:#fff;border-radius:6px;border:1px solid var(--gray-200);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <img src="{{ asset($currentTool->logo) }}" style="width:18px;height:18px;object-fit:contain;">
                                    </div>
                                @endif
                                <span style="font-size:10px; font-weight:700; color:var(--gray-500); text-transform:uppercase; letter-spacing:0.03em;">{{ $currentTool->name }}</span>
                            </div>
                        @endif

                        <h5 class="fw-700 text-dark mb-2" style="font-size:16px; line-height:1.3;">{{ $currentLesson->title }}</h5>

                        {{-- thumbnail --}}
                        <div class="thumb mb-3 position-relative">
                            @if($currentLesson->thumbnail_url)
                                <img src="{{ $currentLesson->thumbnail_url }}" alt="thumbnail">
                            @else
                                <i class="bi bi-play-circle" style="font-size:28px; color:var(--gray-300);"></i>
                            @endif
                        </div>

                        @php $lessonPct = $cp ? round($cp->completion_percent) : 0; @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span style="font-size:11px; font-weight:600; color:var(--gray-500);">Progress</span>
                                <span style="font-size:11px; font-weight:700; color:var(--primary);">{{ $lessonPct }}%</span>
                            </div>
                            <div style="height:4px; background:var(--gray-200); border-radius:4px; overflow:hidden;">
                                <div style="height:100%; width:{{ $lessonPct }}%; background:var(--primary); border-radius:4px; transition:0.6s;"></div>
                            </div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="p-2 rounded-3 text-center" style="background:var(--gray-50); border:1px solid var(--gray-200);">
                                    <div style="font-size:16px; font-weight:800; color:var(--primary);">{{ $cp ? floor($cp->watched_seconds/60) : 0 }}m</div>
                                    <div style="font-size:10px; color:var(--gray-500); font-weight:500;">Watched</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded-3 text-center" style="background:var(--gray-50); border:1px solid var(--gray-200);">
                                    @php
                                        $lessonRem = max(0, ($currentLesson->duration_seconds ?? 0) - ($cp?->watched_seconds ?? 0));
                                        $lessonRemMin = floor($lessonRem / 60);
                                    @endphp
                                    <div style="font-size:16px; font-weight:800; color:var(--warning);">{{ $lessonRemMin }}m</div>
                                    <div style="font-size:10px; color:var(--gray-500); font-weight:500;">Remaining</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-2 mb-2">
                            <a href="{{ route('learn.watch', [$currentLesson, 'roadmap_id' => $roadmap->id]) }}" class="btn-soft-primary text-center d-block text-decoration-none">
                                <i class="bi bi-play-fill"></i> {{ $lessonPct > 0 ? 'Continue' : 'Start' }}
                            </a>
                            @if($lessonPct > 0)
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-3 py-2 fw-bold" onclick="resetRoadmapLessonProgress({{ $currentLesson->id }})">
                                     Start Over
                                </button>
                            @endif
                        </div>

                        @if($cp && $cp->last_watched_at)
                            <div class="text-center mt-2" style="font-size:10px; color:var(--gray-500);">
                                Last watched {{ $cp->last_watched_at->diffForHumans() }}
                            </div>
                        @endif

                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-journal-text" style="font-size:32px; color:var(--gray-300); display:block; margin-bottom:10px;"></i>
                            <p style="font-weight:600; color:var(--gray-700); font-size:14px;">No lesson started</p>
                            <p style="font-size:12px; color:var(--gray-500);">Pick a lesson from the roadmap.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>{{-- /roadmap-main --}}

    </div>{{-- /container --}}
</div>{{-- /roadmap-page --}}

<script>
function toggleExtra(btn, blockId) {
    const block = document.getElementById(blockId);
    const extras = block.querySelectorAll('.extra-lesson');
    const span = btn.querySelector('span');
    const hidden = extras[0]?.classList.contains('d-none');
    extras.forEach(el => el.classList.toggle('d-none', !hidden));
    span.innerHTML = hidden
        ? '<i class="bi bi-chevron-up me-1"></i>Show less'
        : `<i class="bi bi-chevron-down me-1"></i>Show ${extras.length} more`;
}

function resetRoadmapLessonProgress(contentId) {
    if (!confirm('Are you sure you want to restart this lesson from the beginning?')) return;

    fetch("{{ route('learn.progress.reset') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json'
        },
        body: JSON.stringify({ content_id: contentId })
    })
    .then(r => r.json())
    .then(data => {
        window.location.reload();
    })
    .catch(e => {
        console.error(e);
        alert('Could not reset progress. Please try again.');
    });
}
</script>
@endsection