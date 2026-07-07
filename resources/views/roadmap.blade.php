@extends('layouts.user')

@section('title', 'Learning Roadmap — Daleel AI')

@section('content')
<style>
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
        --shadow-glow: 0 8px 32px rgba(79, 70, 229, 0.15);
    }
    
    /* RESET & BASE - Prevent overflow */
    * {
        box-sizing: border-box;
    }
    
    .roadmap-page { 
        background: linear-gradient(180deg, #f0f4ff 0%, #faf5ff 100%);
        min-height: 100vh; 
        padding: 16px 0;
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
    }
    
    .container {
        width: 100%;
        max-width: 1320px;
        margin: 0 auto;
        padding: 0 16px;
        overflow-x: hidden;
    }
    
    /* Stats Cards - Vibrant */
    .stat-card {
        background: #fff;
        border-radius: 20px;
        padding: 24px 28px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid rgba(79, 70, 229, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-1);
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(79, 70, 229, 0.12);
    }
    .stat-card:nth-child(2)::before { background: var(--gradient-2); }
    .stat-card:nth-child(3)::before { background: var(--gradient-3); }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .stat-icon.blue { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; }
    .stat-icon.orange { background: linear-gradient(135deg, #fed7aa, #fdba74); color: #f97316; }
    .stat-icon.purple { background: linear-gradient(135deg, #e9d5ff, #d8b4fe); color: #7c3aed; }
    
    .stat-label {
        font-size: 12px;
        font-weight: 700;
        color: #94a3b8;
        margin-bottom: 2px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }
    .stat-sub {
        font-size: 13px;
        color: #94a3b8;
        font-weight: 600;
    }
    .stat-sub i { margin-right: 4px; color: var(--primary-light); }
    
    /* Progress Ring - Animated */
    .progress-ring {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        flex-shrink: 0;
        background: conic-gradient(#4f46e5 {{ $overallProgress * 3.6 }}deg, #eef2ff 0deg);
        animation: ringPulse 2s ease-in-out infinite;
    }
    @keyframes ringPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.2); }
        50% { box-shadow: 0 0 0 8px rgba(79, 70, 229, 0.05); }
    }
    .progress-ring::after {
        content: '';
        position: absolute;
        inset: 5px;
        background: #fff;
        border-radius: 50%;
    }
    .progress-ring span {
        position: relative;
        z-index: 2;
        font-size: 16px;
        font-weight: 900;
        color: #1e293b;
    }
    
    /* Main Layout - FIXED: No overflow */
    .roadmap-main { 
        display: flex; 
        gap: 20px; 
        align-items: flex-start;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    .roadmap-timeline { 
        flex: 1; 
        min-width: 0;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
    }
    .roadmap-sidebar { 
        width: 340px; 
        flex-shrink: 0; 
        max-width: 100%;
    }
    
    /* Timeline - FIXED: No overflow */
    .timeline-wrapper { 
        position: relative; 
        padding-left: 48px;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    .timeline-line {
        position: absolute;
        left: 16px;
        top: 12px;
        bottom: 12px;
        width: 3px;
        background: linear-gradient(180deg, #4f46e5, #7c3aed, #8b5cf6, #a78bfa);
        border-radius: 4px;
    }
    
    .step-item {
        position: relative;
        margin-bottom: 12px;
        padding-left: 0;
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    .step-item:nth-child(1) { animation-delay: 0.1s; }
    .step-item:nth-child(2) { animation-delay: 0.2s; }
    .step-item:nth-child(3) { animation-delay: 0.3s; }
    .step-item:nth-child(4) { animation-delay: 0.4s; }
    .step-item:nth-child(5) { animation-delay: 0.5s; }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .step-item:last-child { margin-bottom: 0; }
    
    .step-dot {
        position: absolute;
        left: -48px;
        top: 6px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 900;
        color: #94a3b8;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 2;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        flex-shrink: 0;
    }
    
    .btn-roadmap {
        height: 56px; 
        border-radius: 16px; 
        font-weight: 800; 
        font-size: 15px;
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 12px; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        border: none;
        width: 100%;
        max-width: 100%;
    }
    
    .btn-roadmap-primary { 
        background: #4338ca; 
        color: #fff; 
        box-shadow: 0 4px 6px -1px rgba(67, 56, 202, 0.1), 0 2px 4px -1px rgba(67, 56, 202, 0.06); 
    }
    
    .btn-roadmap-primary:hover { 
        background: #3730a3; 
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(67, 56, 202, 0.2); 
        color: #fff; 
    }
    
    .btn-roadmap-outline { 
        background: #fff; 
        border: 1.5px solid #e2e8f0; 
        color: #475569; 
    }
    
    .btn-roadmap-outline:hover { 
        background: #f8fafc; 
        border-color: #cbd5e1; 
        color: #1e293b; 
        transform: translateY(-1px);
    }
    
    .step-item.completed .step-dot {
        background: var(--gradient-1);
        border-color: #4f46e5;
        color: #fff;
        box-shadow: 0 4px 16px rgba(79, 70, 229, 0.3);
    }
    .step-item.active .step-dot {
        border-color: #4f46e5;
        color: #4f46e5;
        width: 42px;
        height: 42px;
        left: -54px;
        top: 3px;
        box-shadow: 0 4px 20px rgba(79, 70, 229, 0.2);
        animation: dotPulse 2s ease-in-out infinite;
    }
    @keyframes dotPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.3); }
        50% { box-shadow: 0 0 0 8px rgba(79, 70, 229, 0.08); }
    }
    
    .step-card {
        background: #fff;
        border-radius: 20px;
        padding: 24px 28px;
        border: 1px solid rgba(79, 70, 229, 0.06);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
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
        box-shadow: 0 8px 32px rgba(79, 70, 229, 0.08);
        transform: translateY(-2px);
    }
    .step-card:hover::after { opacity: 1; }
    
    .step-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 14px;
        width: 100%;
        max-width: 100%;
        flex-wrap: wrap;
    }
    .step-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #f8faff, #f0f4ff);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
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
    
    .step-icon img { width: 30px; height: 30px; object-fit: contain; }
    
    .step-title-group {
        flex: 1;
        min-width: 0;
        max-width: 100%;
    }
    .step-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        word-break: break-word;
    }
    .step-subtitle {
        font-size: 13px;
        color: #94a3b8;
        font-weight: 600;
        margin: 0;
    }
    
    .step-progress {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 2px;
        flex-wrap: wrap;
    }
    .step-progress-bar {
        flex: 1;
        height: 6px;
        background: #f1f5f9;
        border-radius: 6px;
        overflow: hidden;
        min-width: 40px;
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
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        white-space: nowrap;
        min-width: 48px;
        text-align: right;
    }
    .step-progress-count {
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        white-space: nowrap;
    }
    
    /* Lessons inside step */
    .step-lessons {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1.5px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        gap: 2px;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    .lesson-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border-radius: 10px;
        text-decoration: none;
        color: #334155;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.25s;
        background: transparent;
        border: none;
        width: 100%;
        max-width: 100%;
        text-align: left;
        position: relative;
        overflow: hidden;
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
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
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
        width: 8px;
        height: 8px;
        background: #4f46e5;
        border-radius: 50%;
    }
    .lesson-link .play-icon {
        margin-left: auto;
        color: #94a3b8;
        font-size: 16px;
        transition: 0.2s;
        flex-shrink: 0;
    }
    .lesson-link.active .play-icon {
        color: #4f46e5;
        animation: playBounce 2s ease-in-out infinite;
    }
    @keyframes playBounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    /* Sidebar - Vibrant */
    .sidebar-card {
        background: #fff;
        border-radius: 20px;
        padding: 28px;
        border: 1px solid rgba(79, 70, 229, 0.08);
        box-shadow: 0 4px 24px rgba(0,0,0,0.04);
        position: sticky;
        top: 100px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
    }
    .sidebar-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-1);
    }
    .sidebar-card:hover {
        box-shadow: 0 8px 40px rgba(79, 70, 229, 0.08);
    }
    
    .sidebar-badge {
        font-size: 11px;
        font-weight: 800;
        color: #4f46e5;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 18px;
        display: block;
        background: #eef2ff;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
    }
    .sidebar-lesson {
        display: flex;
        gap: 16px;
        margin-bottom: 18px;
        align-items: center;
    }
    .sidebar-lesson-thumb {
        width: 80px;
        height: 54px;
        border-radius: 12px;
        background: linear-gradient(135deg, #eef2ff, #dbeafe);
        flex-shrink: 0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #4f46e5;
    }
    .sidebar-lesson-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .sidebar-lesson-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
    }
    .sidebar-lesson-meta {
        font-size: 13px;
        color: #94a3b8;
        font-weight: 600;
        margin: 0;
    }
    .sidebar-lesson-meta i { margin-right: 4px; color: #4f46e5; }
    
    .sidebar-desc {
        font-size: 14px;
        color: #64748b;
        line-height: 1.7;
        margin-bottom: 22px;
        padding: 16px 0;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .btn-primary-roadmap {
        background: var(--gradient-1);
        color: #fff;
        border: none;
        border-radius: 14px;
        padding: 16px;
        font-weight: 700;
        font-size: 15px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s;
        text-decoration: none;
        box-shadow: 0 4px 16px rgba(79, 70, 229, 0.25);
        position: relative;
        overflow: hidden;
    }
    .btn-primary-roadmap::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
        transform: rotate(45deg) translateX(-100%);
        transition: 0.6s;
    }
    .btn-primary-roadmap:hover::after { transform: rotate(45deg) translateX(100%); }
    .btn-primary-roadmap:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(79, 70, 229, 0.35);
        color: #fff;
    }
    
    .btn-outline-roadmap {
        background: #fff;
        color: #4f46e5;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        font-weight: 700;
        font-size: 15px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s;
        text-decoration: none;
        margin-top: 12px;
    }
    .btn-outline-roadmap:hover {
        border-color: #4f46e5;
        color: #4f46e5;
        background: #f8faff;
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(79, 70, 229, 0.08);
    }
    .btn-outline-roadmap i { color: #8b5cf6; }
    
    .empty-state {
        text-align: center;
        padding: 30px 0;
    }
    .empty-state i {
        font-size: 48px;
        background: linear-gradient(135deg, #4f46e5, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 16px;
        display: block;
    }
    .empty-state p {
        color: #94a3b8;
        font-weight: 600;
        margin: 0;
    }
    
    /* Decorative elements */
    .bg-decoration {
        position: fixed;
        top: -200px;
        right: -200px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(79,70,229,0.03) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .bg-decoration-2 {
        position: fixed;
        bottom: -100px;
        left: -100px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(139,92,246,0.03) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .container { position: relative; z-index: 1; }
    
    /* ============================================================ */
    /* MEDIA QUERIES - FIXED RESPONSIVENESS */
    /* ============================================================ */
    
    @media (max-width: 1200px) {
        .roadmap-main { gap: 16px; }
        .roadmap-sidebar { width: 300px; }
    }
    
    @media (max-width: 992px) {
        .roadmap-main { 
            flex-direction: column; 
            gap: 24px;
            overflow: visible;
        }
        .roadmap-sidebar { 
            width: 100%; 
            max-width: 100%;
        }
        .sidebar-card { 
            position: static !important; 
        }
        .roadmap-hero-cta .btn-lg { font-size: 14px; padding: 10px 20px; }
        .step-icon { width: 44px; height: 44px; font-size: 17px; }
        .step-icon img { width: 26px; height: 26px; }
        .step-card { padding: 20px 24px; }
        .timeline-wrapper { padding-left: 44px; }
        .step-dot { left: -44px; width: 32px; height: 32px; font-size: 13px; }
        .step-item.active .step-dot { width: 38px; height: 38px; left: -50px; }
    }
    
    @media (max-width: 768px) {
        .roadmap-page { padding: 12px 0; }
        .container { padding: 0 12px; }
        
        .roadmap-hero-cta { justify-content: center; flex-wrap: wrap; }
        .roadmap-hero-cta .btn-lg { font-size: 13px; padding: 9px 18px; }
        .step-title { font-size: 15px; }
        .step-card { padding: 16px 18px; border-radius: 16px; }
        .step-card:hover { transform: none; }
        .step-header { gap: 12px; }
        .step-icon { width: 40px; height: 40px; font-size: 15px; border-radius: 10px; }
        .step-icon img { width: 22px; height: 22px; }
        .progress-ring { width: 56px; height: 56px; }
        .progress-ring::after { inset: 4px; }
        .progress-ring span { font-size: 13px; }
        .stat-card { padding: 18px 22px; }
        .sidebar-card { padding: 22px; }
        .sidebar-card h4 { font-size: 17px !important; }
        .btn-roadmap { height: 48px; font-size: 14px; }
        .lesson-link { padding: 8px 12px; }
        .timeline-wrapper { padding-left: 40px; }
        .step-dot { left: -40px; width: 28px; height: 28px; font-size: 12px; top: 5px; border-width: 2.5px; }
        .step-item.active .step-dot { width: 34px; height: 34px; left: -46px; top: 2px; }
        .step-title-group { min-width: 0; }
        .step-title { font-size: 14px; }
        .step-subtitle { font-size: 11px; }
        .step-progress-text { font-size: 12px; min-width: 36px; }
        .sidebar-lesson-thumb { width: 64px; height: 44px; }
        .sidebar-card { padding: 18px; border-radius: 16px; }
        .sidebar-card h4 { font-size: 16px !important; }
        .btn-roadmap { height: 44px; font-size: 13px; border-radius: 12px; }
        .timeline-line { left: 12px; width: 2px; }
        .lesson-link { gap: 10px; padding: 7px 10px; }
        .lesson-link .check { width: 18px; height: 18px; font-size: 10px; }
        .lesson-link .play-icon { font-size: 14px; }
        .roadmap-hero-cta .btn-lg { font-size: 12px; padding: 8px 14px; }
        .step-lessons { margin-top: 12px; padding-top: 12px; }
        .step-card::after { height: 2px; }
        
        /* Fix hero card on mobile */
        .card.overflow-hidden .row {
            width: 100%;
            max-width: 100%;
            overflow: hidden;
        }
        .card.overflow-hidden .p-3, 
        .card.overflow-hidden .p-md-4 {
            padding: 16px !important;
        }
        .card.overflow-hidden h1 {
            font-size: 22px !important;
            word-break: break-word;
        }
    }
    
    @media (max-width: 576px) {
        .container { padding: 0 10px; }
        .roadmap-page { padding: 8px 0; }
        
        .stat-card { 
            padding: 14px 16px; 
            flex-wrap: wrap; 
            gap: 12px;
            border-radius: 16px;
        }
        .stat-value { font-size: 20px; }
        .step-card { 
            padding: 12px 14px; 
            border-radius: 14px;
        }
        .step-header { 
            flex-wrap: wrap; 
            gap: 8px; 
        }
        .step-progress { 
            flex-wrap: wrap; 
        }
        .timeline-wrapper { 
            padding-left: 32px; 
        }
        .step-dot { 
            left: -32px; 
            width: 24px; 
            height: 24px; 
            font-size: 10px; 
            top: 6px; 
            border-width: 2px; 
        }
        .step-item.active .step-dot { 
            width: 30px; 
            height: 30px; 
            left: -38px; 
            top: 3px; 
        }
        .step-icon { 
            width: 34px; 
            height: 34px; 
            font-size: 12px; 
            border-radius: 8px; 
        }
        .step-icon img { 
            width: 18px; 
            height: 18px; 
        }
        .step-title-group { 
            min-width: 0; 
            flex: 1;
        }
        .step-title { 
            font-size: 13px; 
            gap: 4px;
        }
        .step-title .badge { font-size: 8px !important; padding: 1px 6px !important; }
        .step-subtitle { font-size: 10px; }
        .step-progress-text { 
            font-size: 11px; 
            min-width: 30px; 
        }
        .sidebar-card { 
            padding: 14px; 
            border-radius: 14px;
        }
        .sidebar-card h4 { 
            font-size: 15px !important; 
        }
        .sidebar-card .badge { 
            font-size: 8px; 
            padding: 1px 8px; 
        }
        .btn-roadmap { 
            height: 40px; 
            font-size: 12px; 
            border-radius: 10px; 
            gap: 6px; 
        }
        .lesson-link { 
            gap: 8px; 
            padding: 6px 8px; 
            border-radius: 8px; 
            font-size: 12px;
        }
        .lesson-link .check { 
            width: 16px; 
            height: 16px; 
            font-size: 9px; 
        }
        .lesson-link .badge { font-size: 9px !important; }
        .timeline-line { left: 10px; }
        .progress-ring { width: 40px; height: 40px; }
        .progress-ring::after { inset: 3px; }
        .progress-ring span { font-size: 10px; }
        .stat-icon { 
            width: 40px; 
            height: 40px; 
            font-size: 16px; 
        }
        .stat-label { font-size: 10px; }
        .stat-value { font-size: 17px; }
        
        .sidebar-card div[style*="width:52px"] { 
            width: 36px !important; 
            height: 36px !important; 
        }
        .sidebar-card div[style*="width:52px"] i { 
            font-size: 15px !important; 
        }
        .sidebar-card div[style*="width:36px"] { 
            width: 26px !important; 
            height: 26px !important; 
        }
        .sidebar-card div[style*="width:36px"] img { 
            width: 18px !important; 
            height: 18px !important; 
        }
        .sidebar-card div[style*="height:6px"] { 
            height: 4px !important; 
        }
        
        .hero-badge-text { 
            font-size: 9px !important; 
            padding: 2px 10px !important; 
        }
        .roadmap-hero-cta { 
            gap: 6px !important; 
            flex-wrap: wrap;
        }
        .roadmap-hero-cta .btn { 
            padding: 6px 12px !important; 
            font-size: 11px !important; 
            border-radius: 20px !important;
        }
        
        /* Hide desktop-only elements on mobile */
        .d-none.d-lg-flex {
            display: none !important;
        }
        
        /* Fix for step card content overflow */
        .step-card .d-flex.align-items-center.gap-2 {
            flex-wrap: wrap;
        }
        .step-card .ms-auto {
            margin-left: 0 !important;
        }
        
        /* Lesson link improvements for mobile */
        .lesson-link > div[style*="flex:1"] {
            min-width: 0;
            flex: 1;
        }
        .lesson-link > div[style*="flex:1"] > div {
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: clip !important;
            font-size: 12px !important;
        }
        .lesson-link .d-flex.align-items-center.gap-2.ms-2 {
            margin-left: auto !important;
            flex-shrink: 0;
        }
    }
    
    @media (max-width: 400px) {
        .container { padding: 0 8px; }
        .step-card { padding: 10px 10px; border-radius: 12px; }
        .timeline-wrapper { padding-left: 28px; }
        .step-dot { 
            left: -28px; 
            width: 20px; 
            height: 20px; 
            font-size: 8px; 
            top: 7px; 
            border-width: 1.5px; 
        }
        .step-item.active .step-dot { 
            width: 26px; 
            height: 26px; 
            left: -34px; 
            top: 4px; 
        }
        .step-icon { 
            width: 28px; 
            height: 28px; 
            font-size: 10px; 
            border-radius: 6px; 
        }
        .step-icon img { 
            width: 16px; 
            height: 16px; 
        }
        .step-title { 
            font-size: 12px; 
        }
        .step-subtitle { font-size: 9px; }
        .step-progress-text { 
            font-size: 10px; 
            min-width: 26px; 
        }
        .sidebar-card { padding: 12px; }
        .btn-roadmap { 
            height: 36px; 
            font-size: 11px; 
            border-radius: 8px; 
            gap: 4px; 
        }
        .lesson-link { 
            padding: 5px 6px; 
            gap: 6px;
            font-size: 11px;
        }
        .lesson-link .check { 
            width: 14px; 
            height: 14px; 
            font-size: 8px; 
        }
        .timeline-line { left: 8px; }
        .stat-card { 
            padding: 10px 12px; 
            gap: 10px;
            border-radius: 14px;
        }
        .stat-icon { 
            width: 34px; 
            height: 34px; 
            font-size: 14px; 
            border-radius: 12px;
        }
        .stat-value { font-size: 15px; }
        .stat-label { font-size: 9px; }
    }
</style>

<div class="roadmap-page">
    <!-- Decorative Background -->
    <div class="bg-decoration"></div>
    <div class="bg-decoration-2"></div>
    
    <div class="container">

        {{-- ====== JOURNEY HERO ====== --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border-0 overflow-hidden shadow rounded-4" style="background: linear-gradient(135deg,#0f172a 0%,#1e293b 100%);">
                    <div class="row g-0 align-items-center">
                        <div class="col-12 col-lg-7 p-3 p-md-4">
                            <span class="badge mb-3 px-3 py-2 rounded-pill fw-bold text-uppercase" style="font-size:11px;letter-spacing:1px;background:rgba(99,102,241,.2);color:#a5b4fc;">
                                <i class="bi bi-compass-fill me-1"></i> Active Journey
                            </span>
                            <h1 class="fw-900 text-white mb-1" style="font-size:clamp(20px,4vw,34px);word-break:break-word;">
                                {{ $overallProgress == 100 ? '🎉 Mission Accomplished!' : 'Resume Your Journey' }}
                            </h1>
                            <p class="text-white mb-3" style="opacity:.65;word-break:break-word;">{{ $roadmap->title }}</p>
                            @if(!$hasKnownDurations && $totalLessons > 0)
                                <div class="mb-3 px-3 py-2 rounded-3 d-flex align-items-center gap-2" style="background:rgba(245,158,11,.14);color:#fde68a;font-size:12px;font-weight:700;flex-wrap:wrap;">
                                    <i class="bi bi-info-circle"></i>
                                    Video durations are not synced yet. Open a lesson to start tracking accurate watch time.
                                </div>
                            @endif
                            <div class="roadmap-hero-cta d-flex flex-wrap gap-3 mt-4">
                                @if($nextIncompleteLesson ?? $currentLesson)
                                <a href="{{ route('learn.watch', [$nextIncompleteLesson ?? $currentLesson, 'roadmap_id' => $roadmap->id]) }}" class="btn btn-primary btn-lg rounded-pill fw-bold px-4 d-flex align-items-center gap-2 shadow-sm" style="font-size:clamp(12px,1.2vw,16px);">
                                    <i class="bi bi-play-fill"></i> Continue Watching
                                </a>
                                @endif
                                <a href="{{ route('ai.mentor') }}" class="btn btn-outline-light btn-lg rounded-pill px-4" style="opacity:.85;font-size:clamp(12px,1.2vw,16px);">
                                    <i class="bi bi-stars me-1"></i> Ask AI Mentor
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-5 d-none d-lg-flex align-items-center justify-content-center p-4 gap-4">
                            {{-- Overall Ring --}}
                            <div class="text-center">
                                <div class="progress-ring mx-auto mb-2" style="width:90px;height:90px;background:conic-gradient(#4f46e5 {{ $overallProgress * 3.6 }}deg, rgba(255,255,255,.1) 0deg);">
                                    <span class="text-black fw-900" style="font-size:18px;">{{ $overallProgress }}%</span>
                                </div>
                                <span class="text-white opacity-50" style="font-size:11px;font-weight:700;">Overall</span>
                            </div>
                            {{-- Time pill stats --}}
                            <div class="d-flex flex-column gap-2">
                                <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2" style="background:rgba(255,255,255,.07);">
                                    <i class="bi bi-collection-play text-info"></i>
                                    <div>
                                        <div class="text-white fw-700" style="font-size:13px;">{{ $formattedTotalDuration }} total</div>
                                        <div class="opacity-50 text-white" style="font-size:11px;">All roadmap videos</div>
                                    </div>
                                </div>
                                <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2" style="background:rgba(255,255,255,.07);">
                                    <i class="bi bi-clock-history text-primary"></i>
                                    <div>
                                        <div class="text-white fw-700" style="font-size:13px;">{{ $formattedWatchedTime }} watched</div>
                                        <div class="opacity-50 text-white" style="font-size:11px;">Time invested</div>
                                    </div>
                                </div>
                                <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2" style="background:rgba(255,255,255,.07);">
                                    <i class="bi bi-hourglass-split text-warning"></i>
                                    <div>
                                        <div class="text-white fw-700" style="font-size:13px;">{{ $formattedRemainingTime }}{{ $hasKnownDurations ? ' left' : '' }}</div>
                                        <div class="opacity-50 text-white" style="font-size:11px;">To complete roadmap</div>
                                    </div>
                                </div>
                                <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2" style="background:rgba(255,255,255,.07);">
                                    <i class="bi bi-mortarboard text-success"></i>
                                    <div>
                                        <div class="text-white fw-700" style="font-size:13px;">{{ $lessonsCompleted }} / {{ $totalLessons }} lessons</div>
                                        <div class="opacity-50 text-white" style="font-size:11px;">Completed</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====== MAIN LAYOUT ====== --}}
        <div class="roadmap-main">

            {{-- ====== TIMELINE ====== --}}
            <div class="roadmap-timeline">
                <div class="timeline-wrapper">
                    <div class="timeline-line"></div>

                    @foreach($roadmapData as $index => $data)
                        @php
                            $isCompleted = $data['percent'] == 100;
                            $isActive    = $data['percent'] > 0 && !$isCompleted;
                            $stepNum     = $index + 1;
                            $iconColors  = ['#4f46e5','#f59e0b','#10b981','#ec4899','#7c3aed'];
                            $iconBg      = ['#eef2ff','#fef3c7','#d1fae5','#fce7f3','#ede9fe'];
                        @endphp
                        <div class="step-item {{ $isCompleted ? 'completed' : '' }}">
                            <div class="step-dot">
                                @if($isCompleted) <i class="bi bi-check-lg" style="font-size:14px;"></i>
                                @else {{ $stepNum }} @endif
                            </div>

                            <div class="step-card">
                                {{-- Phase Header --}}
                                <div class="step-header">
                                    <div class="step-icon" style="background:{{ $iconBg[$index%5] }};color:{{ $iconColors[$index%5] }};flex-shrink:0;">
                                        @if($data['tool'] && $data['tool']->logo)
                                            <img src="{{ asset($data['tool']->logo) }}" alt="{{ $data['tool']->name }}">
                                        @else
                                            <i class="bi bi-gear-fill" style="font-size:18px;"></i>
                                        @endif
                                    </div>
                                    <div class="step-title-group">
                                        <div class="step-title d-flex align-items-center gap-2 flex-wrap">
                                            {{ $data['tool']->name ?? 'Phase '.$stepNum }}
                                            @if($isCompleted)
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size:10px;"><i class="bi bi-check-circle-fill"></i> Done</span>
                                            @elseif($isActive)
                                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1" style="font-size:10px;"><i class="bi bi-play-fill"></i> In Progress</span>
                                            @endif
                                        </div>
                                        <div class="step-subtitle">{{ $data['completed'] }} of {{ $data['total'] }} lessons complete</div>
                                    </div>
                                    <div class="step-progress-text ms-auto">{{ $data['percent'] }}%</div>
                                </div>

                                {{-- Lesson List --}}
                                <div class="step-lessons" id="lessons-{{ $data['tool']->id ?? $index }}">
                                    @foreach($data['contents'] as $loopIndex => $content)
                                        @php
                                            $isActiveLesson = $currentLesson && $currentLesson->id == $content->id;
                                            $isDone         = $content->is_completed ?? false;
                                            $pct            = $content->completion_pct ?? 0;
                                            $watchedS       = $content->watched_seconds ?? 0;
                                            $watchedMin     = floor($watchedS / 60);
                                            $durMin         = $content->duration_seconds > 0 ? max(1, ceil($content->duration_seconds / 60)) : 0;
                                            $hidden         = $loopIndex >= 5;
                                        @endphp

                                        <a href="{{ route('learn.watch', [$content, 'roadmap_id' => $roadmap->id]) }}"
                                           class="lesson-link {{ $isActiveLesson ? 'active' : '' }} {{ $isDone ? 'done' : '' }} {{ $hidden ? 'd-none extra-lesson' : '' }}"
                                           data-lesson-id="{{ $content->id }}">

                                            {{-- Status icon --}}
                                            <span class="check">
                                                @if($isDone) <i class="bi bi-check"></i> @endif
                                            </span>

                                            {{-- Title + meta --}}
                                            <div style="flex:1;min-width:0;">
                                                <div style="font-size:14px;font-weight:{{ $isActiveLesson ? '700' : '500' }};color:{{ $isActiveLesson ? '#4f46e5' : '#334155' }};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%;">
                                                    {{ $content->title }}
                                                </div>
                                                {{-- Mini progress bar (only if started but not done) --}}
                                                @if($pct > 0 && !$isDone)
                                                    <div style="height:3px;background:#f1f5f9;border-radius:3px;margin-top:5px;overflow:hidden;max-width:100%;">
                                                        <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,#4f46e5,#818cf8);border-radius:3px;transition:.5s;"></div>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Right badges --}}
                                            <div class="d-flex align-items-center gap-2 ms-2 flex-shrink-0">
                                                @if($isDone)
                                                    <span class="badge rounded-pill" style="font-size:10px;background:#d1fae5;color:#065f46;font-weight:700;"><i class="bi bi-check-circle-fill me-1"></i>Watched</span>
                                                @elseif($pct > 0)
                                                    <span class="badge rounded-pill" style="font-size:10px;background:#ede9fe;color:#4f46e5;font-weight:700;">{{ $pct }}%</span>
                                                @endif
                                                @if($durMin > 0)
                                                    <span style="font-size:11px;color:#94a3b8;font-weight:600;white-space:nowrap;">{{ $durMin }}m</span>
                                                @endif
                                                @if($isActiveLesson)
                                                    <span class="play-icon"><i class="bi bi-play-circle-fill"></i></span>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach

                                    @if($data['contents']->count() > 5)
                                        <button class="btn btn-sm w-100 mt-2 rounded-3 border-0 fw-700 text-primary"
                                                style="background:#f8faff;font-size:12px;max-width:100%;"
                                                onclick="toggleExtra(this,'lessons-{{ $data['tool']->id ?? $index }}')">
                                            <span><i class="bi bi-chevron-down me-1"></i>Show {{ $data['contents']->count() - 5 }} more lessons</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ====== SIDEBAR ====== --}}
            <div class="roadmap-sidebar">
                <div class="sidebar-card border-0 shadow-sm" style="position:sticky;top:90px;width:100%;max-width:100%;">
                    @if($currentLesson)
                        @php $cp = $currentProgressRecord; @endphp

                        {{-- Tool badge --}}
                        @if($currentTool)
                        <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                            @if($currentTool->logo)
                            <div style="width:36px;height:36px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <img src="{{ asset($currentTool->logo) }}" style="width:24px;height:24px;object-fit:contain;">
                            </div>
                            @endif
                            <span style="font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.05em;word-break:break-word;">
                                {{ $currentTool->name }}
                                @if($currentProgressRecord && $currentProgressRecord->last_watched_at)
                                    · Last Watched {{ $currentProgressRecord->last_watched_at->diffForHumans() }}
                                @else
                                    · Last Watched
                                @endif
                            </span>
                        </div>
                        @endif

                        {{-- Lesson title --}}
                        <h4 class="fw-900 text-dark mb-3" style="font-size:clamp(16px,1.5vw,18px);line-height:1.35;word-break:break-word;">{{ $currentLesson->title }}</h4>

                        {{-- Thumbnail with progress overlay --}}
                        <div class="position-relative rounded-4 overflow-hidden mb-3 shadow-sm" style="aspect-ratio:16/9;background:#e2e8f0;width:100%;max-width:100%;">
                            @if($currentLesson->thumbnail_url)
                                <img src="{{ $currentLesson->thumbnail_url }}" class="w-100 h-100 object-fit-cover" style="width:100%;height:100%;object-fit:cover;">
                            @endif
                            {{-- Play icon overlay --}}
                            <div class="position-absolute inset-0 d-flex align-items-center justify-content-center">
                                <div style="width:52px;height:52px;background:rgba(0,0,0,.55);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-play-fill text-white" style="font-size:22px;margin-left:3px;"></i>
                                </div>
                            </div>
                            {{-- Duration badge --}}
                            <div class="position-absolute bottom-0 end-0 m-2 px-2 py-1 rounded-2 text-white fw-bold" style="background:rgba(0,0,0,.7);font-size:11px;">
                                {{ $currentLesson->duration_label ?: '—' }}
                            </div>
                        </div>

                        {{-- Progress bar for this lesson --}}
                        @php $lessonPct = $cp ? round($cp->completion_percent) : 0; @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1 flex-wrap">
                                <span style="font-size:12px;font-weight:700;color:#64748b;">Your progress</span>
                                <span style="font-size:12px;font-weight:800;color:#4f46e5;">{{ $lessonPct }}%</span>
                            </div>
                            <div style="height:6px;background:#f1f5f9;border-radius:6px;overflow:hidden;width:100%;">
                                <div style="height:100%;width:{{ $lessonPct }}%;background:linear-gradient(90deg,#4f46e5,#818cf8);border-radius:6px;transition:.6s;"></div>
                            </div>
                        </div>

                        {{-- Roadmap totals --}}
                        <div class="p-3 rounded-3 mb-4 d-flex flex-column gap-2" style="background:#f8fafc;border:1px solid #f1f5f9;width:100%;max-width:100%;">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                <span style="font-size:12px;color:#64748b;font-weight:700;"><i class="bi bi-clock-history me-1 text-primary"></i>Total Watched</span>
                                <span style="font-size:13px;font-weight:900;color:#0f172a;">
                                    {{ $formattedWatchedTime }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                <span style="font-size:12px;color:#64748b;font-weight:700;"><i class="bi bi-hourglass-split me-1 text-warning"></i>{{ $hasKnownDurations ? 'Time to Finish' : 'Time to Finish' }}</span>
                                <span style="font-size:13px;font-weight:900;color:#0f172a;">
                                    {{ $formattedRemainingTime }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                <span style="font-size:12px;color:#64748b;font-weight:700;"><i class="bi bi-mortarboard me-1 text-success"></i>Completed</span>
                                <span style="font-size:13px;font-weight:900;color:#0f172a;">{{ $lessonsCompleted }} / {{ $totalLessons }}</span>
                            </div>
                        </div>

                        {{-- CTA — always points to next incomplete lesson --}}
                        @php $ctaLesson = $nextIncompleteLesson ?? $currentLesson; @endphp
                        @if($ctaLesson)
                        <a href="{{ route('learn.watch', [$ctaLesson, 'roadmap_id' => $roadmap->id]) }}" class="btn-roadmap btn-roadmap-primary" style="width:100%;max-width:100%;">
                            <i class="bi bi-play-fill"></i>
                            @if($ctaLesson->id === ($currentLesson->id ?? null) && $lessonPct > 0)
                                Continue Lesson
                            @elseif($ctaLesson->id !== ($currentLesson->id ?? null))
                                Next Lesson
                            @else
                                Start Lesson
                            @endif
                        </a>
                        @endif

                        {{-- Last watched timestamp --}}
                        @if($currentProgressRecord && $currentProgressRecord->last_watched_at)
                        <div class="text-center mt-3" style="font-size:11px;color:#94a3b8;word-break:break-word;">
                            Last watched {{ $currentProgressRecord->last_watched_at->diffForHumans() }}
                        </div>
                        @endif

                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-journal-richtext fs-1 text-primary opacity-50 mb-3 d-block"></i>
                            <p class="text-muted fw-bold mb-1">No lessons started yet</p>
                            <p class="text-muted small">Click any lesson in the roadmap to begin your journey.</p>
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
        : `<i class="bi bi-chevron-down me-1"></i>Show ${extras.length} more lessons`;
}
</script>
@endsection@extends('layouts.user')

@section('title', 'Learning Roadmap — Daleel AI')

@section('content')
<style>
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
        --shadow-glow: 0 8px 32px rgba(79, 70, 229, 0.15);
    }
    
    /* RESET & BASE - Prevent overflow */
    * {
        box-sizing: border-box;
    }
    
    .roadmap-page { 
        background: linear-gradient(180deg, #f0f4ff 0%, #faf5ff 100%);
        min-height: 100vh; 
        padding: 16px 0;
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
    }
    
    .container {
        width: 100%;
        max-width: 1320px;
        margin: 0 auto;
        padding: 0 16px;
        overflow-x: hidden;
    }
    
    /* Stats Cards - Vibrant */
    .stat-card {
        background: #fff;
        border-radius: 20px;
        padding: 24px 28px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid rgba(79, 70, 229, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--gradient-1);
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(79, 70, 229, 0.12);
    }
    .stat-card:nth-child(2)::before { background: var(--gradient-2); }
    .stat-card:nth-child(3)::before { background: var(--gradient-3); }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .stat-icon.blue { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; }
    .stat-icon.orange { background: linear-gradient(135deg, #fed7aa, #fdba74); color: #f97316; }
    .stat-icon.purple { background: linear-gradient(135deg, #e9d5ff, #d8b4fe); color: #7c3aed; }
    
    .stat-label {
        font-size: 12px;
        font-weight: 700;
        color: #94a3b8;
        margin-bottom: 2px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    .stat-value {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }
    .stat-sub {
        font-size: 13px;
        color: #94a3b8;
        font-weight: 600;
    }
    .stat-sub i { margin-right: 4px; color: var(--primary-light); }
    
    /* Progress Ring - Animated */
    .progress-ring {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        flex-shrink: 0;
        background: conic-gradient(#4f46e5 {{ $overallProgress * 3.6 }}deg, #eef2ff 0deg);
        animation: ringPulse 2s ease-in-out infinite;
    }
    @keyframes ringPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.2); }
        50% { box-shadow: 0 0 0 8px rgba(79, 70, 229, 0.05); }
    }
    .progress-ring::after {
        content: '';
        position: absolute;
        inset: 5px;
        background: #fff;
        border-radius: 50%;
    }
    .progress-ring span {
        position: relative;
        z-index: 2;
        font-size: 16px;
        font-weight: 900;
        color: #1e293b;
    }
    
    /* Main Layout - FIXED: No overflow */
    .roadmap-main { 
        display: flex; 
        gap: 20px; 
        align-items: flex-start;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    .roadmap-timeline { 
        flex: 1; 
        min-width: 0;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
    }
    .roadmap-sidebar { 
        width: 340px; 
        flex-shrink: 0; 
        max-width: 100%;
    }
    
    /* Timeline - FIXED: No overflow */
    .timeline-wrapper { 
        position: relative; 
        padding-left: 48px;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    .timeline-line {
        position: absolute;
        left: 16px;
        top: 12px;
        bottom: 12px;
        width: 3px;
        background: linear-gradient(180deg, #4f46e5, #7c3aed, #8b5cf6, #a78bfa);
        border-radius: 4px;
    }
    
    .step-item {
        position: relative;
        margin-bottom: 12px;
        padding-left: 0;
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    .step-item:nth-child(1) { animation-delay: 0.1s; }
    .step-item:nth-child(2) { animation-delay: 0.2s; }
    .step-item:nth-child(3) { animation-delay: 0.3s; }
    .step-item:nth-child(4) { animation-delay: 0.4s; }
    .step-item:nth-child(5) { animation-delay: 0.5s; }
    
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .step-item:last-child { margin-bottom: 0; }
    
    .step-dot {
        position: absolute;
        left: -48px;
        top: 6px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 900;
        color: #94a3b8;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 2;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        flex-shrink: 0;
    }
    
    .btn-roadmap {
        height: 56px; 
        border-radius: 16px; 
        font-weight: 800; 
        font-size: 15px;
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 12px; 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        border: none;
        width: 100%;
        max-width: 100%;
    }
    
    .btn-roadmap-primary { 
        background: #4338ca; 
        color: #fff; 
        box-shadow: 0 4px 6px -1px rgba(67, 56, 202, 0.1), 0 2px 4px -1px rgba(67, 56, 202, 0.06); 
    }
    
    .btn-roadmap-primary:hover { 
        background: #3730a3; 
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(67, 56, 202, 0.2); 
        color: #fff; 
    }
    
    .btn-roadmap-outline { 
        background: #fff; 
        border: 1.5px solid #e2e8f0; 
        color: #475569; 
    }
    
    .btn-roadmap-outline:hover { 
        background: #f8fafc; 
        border-color: #cbd5e1; 
        color: #1e293b; 
        transform: translateY(-1px);
    }
    
    .step-item.completed .step-dot {
        background: var(--gradient-1);
        border-color: #4f46e5;
        color: #fff;
        box-shadow: 0 4px 16px rgba(79, 70, 229, 0.3);
    }
    .step-item.active .step-dot {
        border-color: #4f46e5;
        color: #4f46e5;
        width: 42px;
        height: 42px;
        left: -54px;
        top: 3px;
        box-shadow: 0 4px 20px rgba(79, 70, 229, 0.2);
        animation: dotPulse 2s ease-in-out infinite;
    }
    @keyframes dotPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.3); }
        50% { box-shadow: 0 0 0 8px rgba(79, 70, 229, 0.08); }
    }
    
    .step-card {
        background: #fff;
        border-radius: 20px;
        padding: 24px 28px;
        border: 1px solid rgba(79, 70, 229, 0.06);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 12px rgba(0,0,0,0.03);
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
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
        box-shadow: 0 8px 32px rgba(79, 70, 229, 0.08);
        transform: translateY(-2px);
    }
    .step-card:hover::after { opacity: 1; }
    
    .step-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 14px;
        width: 100%;
        max-width: 100%;
        flex-wrap: wrap;
    }
    .step-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, #f8faff, #f0f4ff);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
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
    
    .step-icon img { width: 30px; height: 30px; object-fit: contain; }
    
    .step-title-group {
        flex: 1;
        min-width: 0;
        max-width: 100%;
    }
    .step-title {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        word-break: break-word;
    }
    .step-subtitle {
        font-size: 13px;
        color: #94a3b8;
        font-weight: 600;
        margin: 0;
    }
    
    .step-progress {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 2px;
        flex-wrap: wrap;
    }
    .step-progress-bar {
        flex: 1;
        height: 6px;
        background: #f1f5f9;
        border-radius: 6px;
        overflow: hidden;
        min-width: 40px;
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
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        white-space: nowrap;
        min-width: 48px;
        text-align: right;
    }
    .step-progress-count {
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        white-space: nowrap;
    }
    
    /* Lessons inside step */
    .step-lessons {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1.5px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        gap: 2px;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    .lesson-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        border-radius: 10px;
        text-decoration: none;
        color: #334155;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.25s;
        background: transparent;
        border: none;
        width: 100%;
        max-width: 100%;
        text-align: left;
        position: relative;
        overflow: hidden;
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
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
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
        width: 8px;
        height: 8px;
        background: #4f46e5;
        border-radius: 50%;
    }
    .lesson-link .play-icon {
        margin-left: auto;
        color: #94a3b8;
        font-size: 16px;
        transition: 0.2s;
        flex-shrink: 0;
    }
    .lesson-link.active .play-icon {
        color: #4f46e5;
        animation: playBounce 2s ease-in-out infinite;
    }
    @keyframes playBounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    /* Sidebar - Vibrant */
    .sidebar-card {
        background: #fff;
        border-radius: 20px;
        padding: 28px;
        border: 1px solid rgba(79, 70, 229, 0.08);
        box-shadow: 0 4px 24px rgba(0,0,0,0.04);
        position: sticky;
        top: 100px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
    }
    .sidebar-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-1);
    }
    .sidebar-card:hover {
        box-shadow: 0 8px 40px rgba(79, 70, 229, 0.08);
    }
    
    .sidebar-badge {
        font-size: 11px;
        font-weight: 800;
        color: #4f46e5;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 18px;
        display: block;
        background: #eef2ff;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
    }
    .sidebar-lesson {
        display: flex;
        gap: 16px;
        margin-bottom: 18px;
        align-items: center;
    }
    .sidebar-lesson-thumb {
        width: 80px;
        height: 54px;
        border-radius: 12px;
        background: linear-gradient(135deg, #eef2ff, #dbeafe);
        flex-shrink: 0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #4f46e5;
    }
    .sidebar-lesson-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .sidebar-lesson-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
    }
    .sidebar-lesson-meta {
        font-size: 13px;
        color: #94a3b8;
        font-weight: 600;
        margin: 0;
    }
    .sidebar-lesson-meta i { margin-right: 4px; color: #4f46e5; }
    
    .sidebar-desc {
        font-size: 14px;
        color: #64748b;
        line-height: 1.7;
        margin-bottom: 22px;
        padding: 16px 0;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .btn-primary-roadmap {
        background: var(--gradient-1);
        color: #fff;
        border: none;
        border-radius: 14px;
        padding: 16px;
        font-weight: 700;
        font-size: 15px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s;
        text-decoration: none;
        box-shadow: 0 4px 16px rgba(79, 70, 229, 0.25);
        position: relative;
        overflow: hidden;
    }
    .btn-primary-roadmap::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
        transform: rotate(45deg) translateX(-100%);
        transition: 0.6s;
    }
    .btn-primary-roadmap:hover::after { transform: rotate(45deg) translateX(100%); }
    .btn-primary-roadmap:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(79, 70, 229, 0.35);
        color: #fff;
    }
    
    .btn-outline-roadmap {
        background: #fff;
        color: #4f46e5;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        font-weight: 700;
        font-size: 15px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s;
        text-decoration: none;
        margin-top: 12px;
    }
    .btn-outline-roadmap:hover {
        border-color: #4f46e5;
        color: #4f46e5;
        background: #f8faff;
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(79, 70, 229, 0.08);
    }
    .btn-outline-roadmap i { color: #8b5cf6; }
    
    .empty-state {
        text-align: center;
        padding: 30px 0;
    }
    .empty-state i {
        font-size: 48px;
        background: linear-gradient(135deg, #4f46e5, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 16px;
        display: block;
    }
    .empty-state p {
        color: #94a3b8;
        font-weight: 600;
        margin: 0;
    }
    
    /* Decorative elements */
    .bg-decoration {
        position: fixed;
        top: -200px;
        right: -200px;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(79,70,229,0.03) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .bg-decoration-2 {
        position: fixed;
        bottom: -100px;
        left: -100px;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(139,92,246,0.03) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .container { position: relative; z-index: 1; }
    
    /* ============================================================ */
    /* MEDIA QUERIES - FIXED RESPONSIVENESS */
    /* ============================================================ */
    
    @media (max-width: 1200px) {
        .roadmap-main { gap: 16px; }
        .roadmap-sidebar { width: 300px; }
    }
    
    @media (max-width: 992px) {
        .roadmap-main { 
            flex-direction: column; 
            gap: 24px;
            overflow: visible;
        }
        .roadmap-sidebar { 
            width: 100%; 
            max-width: 100%;
        }
        .sidebar-card { 
            position: static !important; 
        }
        .roadmap-hero-cta .btn-lg { font-size: 14px; padding: 10px 20px; }
        .step-icon { width: 44px; height: 44px; font-size: 17px; }
        .step-icon img { width: 26px; height: 26px; }
        .step-card { padding: 20px 24px; }
        .timeline-wrapper { padding-left: 44px; }
        .step-dot { left: -44px; width: 32px; height: 32px; font-size: 13px; }
        .step-item.active .step-dot { width: 38px; height: 38px; left: -50px; }
    }
    
    @media (max-width: 768px) {
        .roadmap-page { padding: 12px 0; }
        .container { padding: 0 12px; }
        
        .roadmap-hero-cta { justify-content: center; flex-wrap: wrap; }
        .roadmap-hero-cta .btn-lg { font-size: 13px; padding: 9px 18px; }
        .step-title { font-size: 15px; }
        .step-card { padding: 16px 18px; border-radius: 16px; }
        .step-card:hover { transform: none; }
        .step-header { gap: 12px; }
        .step-icon { width: 40px; height: 40px; font-size: 15px; border-radius: 10px; }
        .step-icon img { width: 22px; height: 22px; }
        .progress-ring { width: 56px; height: 56px; }
        .progress-ring::after { inset: 4px; }
        .progress-ring span { font-size: 13px; }
        .stat-card { padding: 18px 22px; }
        .sidebar-card { padding: 22px; }
        .sidebar-card h4 { font-size: 17px !important; }
        .btn-roadmap { height: 48px; font-size: 14px; }
        .lesson-link { padding: 8px 12px; }
        .timeline-wrapper { padding-left: 40px; }
        .step-dot { left: -40px; width: 28px; height: 28px; font-size: 12px; top: 5px; border-width: 2.5px; }
        .step-item.active .step-dot { width: 34px; height: 34px; left: -46px; top: 2px; }
        .step-title-group { min-width: 0; }
        .step-title { font-size: 14px; }
        .step-subtitle { font-size: 11px; }
        .step-progress-text { font-size: 12px; min-width: 36px; }
        .sidebar-lesson-thumb { width: 64px; height: 44px; }
        .sidebar-card { padding: 18px; border-radius: 16px; }
        .sidebar-card h4 { font-size: 16px !important; }
        .btn-roadmap { height: 44px; font-size: 13px; border-radius: 12px; }
        .timeline-line { left: 12px; width: 2px; }
        .lesson-link { gap: 10px; padding: 7px 10px; }
        .lesson-link .check { width: 18px; height: 18px; font-size: 10px; }
        .lesson-link .play-icon { font-size: 14px; }
        .roadmap-hero-cta .btn-lg { font-size: 12px; padding: 8px 14px; }
        .step-lessons { margin-top: 12px; padding-top: 12px; }
        .step-card::after { height: 2px; }
        
        /* Fix hero card on mobile */
        .card.overflow-hidden .row {
            width: 100%;
            max-width: 100%;
            overflow: hidden;
        }
        .card.overflow-hidden .p-3, 
        .card.overflow-hidden .p-md-4 {
            padding: 16px !important;
        }
        .card.overflow-hidden h1 {
            font-size: 22px !important;
            word-break: break-word;
        }
    }
    
    @media (max-width: 576px) {
        .container { padding: 0 10px; }
        .roadmap-page { padding: 8px 0; }
        
        .stat-card { 
            padding: 14px 16px; 
            flex-wrap: wrap; 
            gap: 12px;
            border-radius: 16px;
        }
        .stat-value { font-size: 20px; }
        .step-card { 
            padding: 12px 14px; 
            border-radius: 14px;
        }
        .step-header { 
            flex-wrap: wrap; 
            gap: 8px; 
        }
        .step-progress { 
            flex-wrap: wrap; 
        }
        .timeline-wrapper { 
            padding-left: 32px; 
        }
        .step-dot { 
            left: -32px; 
            width: 24px; 
            height: 24px; 
            font-size: 10px; 
            top: 6px; 
            border-width: 2px; 
        }
        .step-item.active .step-dot { 
            width: 30px; 
            height: 30px; 
            left: -38px; 
            top: 3px; 
        }
        .step-icon { 
            width: 34px; 
            height: 34px; 
            font-size: 12px; 
            border-radius: 8px; 
        }
        .step-icon img { 
            width: 18px; 
            height: 18px; 
        }
        .step-title-group { 
            min-width: 0; 
            flex: 1;
        }
        .step-title { 
            font-size: 13px; 
            gap: 4px;
        }
        .step-title .badge { font-size: 8px !important; padding: 1px 6px !important; }
        .step-subtitle { font-size: 10px; }
        .step-progress-text { 
            font-size: 11px; 
            min-width: 30px; 
        }
        .sidebar-card { 
            padding: 14px; 
            border-radius: 14px;
        }
        .sidebar-card h4 { 
            font-size: 15px !important; 
        }
        .sidebar-card .badge { 
            font-size: 8px; 
            padding: 1px 8px; 
        }
        .btn-roadmap { 
            height: 40px; 
            font-size: 12px; 
            border-radius: 10px; 
            gap: 6px; 
        }
        .lesson-link { 
            gap: 8px; 
            padding: 6px 8px; 
            border-radius: 8px; 
            font-size: 12px;
        }
        .lesson-link .check { 
            width: 16px; 
            height: 16px; 
            font-size: 9px; 
        }
        .lesson-link .badge { font-size: 9px !important; }
        .timeline-line { left: 10px; }
        .progress-ring { width: 40px; height: 40px; }
        .progress-ring::after { inset: 3px; }
        .progress-ring span { font-size: 10px; }
        .stat-icon { 
            width: 40px; 
            height: 40px; 
            font-size: 16px; 
        }
        .stat-label { font-size: 10px; }
        .stat-value { font-size: 17px; }
        
        .sidebar-card div[style*="width:52px"] { 
            width: 36px !important; 
            height: 36px !important; 
        }
        .sidebar-card div[style*="width:52px"] i { 
            font-size: 15px !important; 
        }
        .sidebar-card div[style*="width:36px"] { 
            width: 26px !important; 
            height: 26px !important; 
        }
        .sidebar-card div[style*="width:36px"] img { 
            width: 18px !important; 
            height: 18px !important; 
        }
        .sidebar-card div[style*="height:6px"] { 
            height: 4px !important; 
        }
        
        .hero-badge-text { 
            font-size: 9px !important; 
            padding: 2px 10px !important; 
        }
        .roadmap-hero-cta { 
            gap: 6px !important; 
            flex-wrap: wrap;
        }
        .roadmap-hero-cta .btn { 
            padding: 6px 12px !important; 
            font-size: 11px !important; 
            border-radius: 20px !important;
        }
        
        /* Hide desktop-only elements on mobile */
        .d-none.d-lg-flex {
            display: none !important;
        }
        
        /* Fix for step card content overflow */
        .step-card .d-flex.align-items-center.gap-2 {
            flex-wrap: wrap;
        }
        .step-card .ms-auto {
            margin-left: 0 !important;
        }
        
        /* Lesson link improvements for mobile */
        .lesson-link > div[style*="flex:1"] {
            min-width: 0;
            flex: 1;
        }
        .lesson-link > div[style*="flex:1"] > div {
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: clip !important;
            font-size: 12px !important;
        }
        .lesson-link .d-flex.align-items-center.gap-2.ms-2 {
            margin-left: auto !important;
            flex-shrink: 0;
        }
    }
    
    @media (max-width: 400px) {
        .container { padding: 0 8px; }
        .step-card { padding: 10px 10px; border-radius: 12px; }
        .timeline-wrapper { padding-left: 28px; }
        .step-dot { 
            left: -28px; 
            width: 20px; 
            height: 20px; 
            font-size: 8px; 
            top: 7px; 
            border-width: 1.5px; 
        }
        .step-item.active .step-dot { 
            width: 26px; 
            height: 26px; 
            left: -34px; 
            top: 4px; 
        }
        .step-icon { 
            width: 28px; 
            height: 28px; 
            font-size: 10px; 
            border-radius: 6px; 
        }
        .step-icon img { 
            width: 16px; 
            height: 16px; 
        }
        .step-title { 
            font-size: 12px; 
        }
        .step-subtitle { font-size: 9px; }
        .step-progress-text { 
            font-size: 10px; 
            min-width: 26px; 
        }
        .sidebar-card { padding: 12px; }
        .btn-roadmap { 
            height: 36px; 
            font-size: 11px; 
            border-radius: 8px; 
            gap: 4px; 
        }
        .lesson-link { 
            padding: 5px 6px; 
            gap: 6px;
            font-size: 11px;
        }
        .lesson-link .check { 
            width: 14px; 
            height: 14px; 
            font-size: 8px; 
        }
        .timeline-line { left: 8px; }
        .stat-card { 
            padding: 10px 12px; 
            gap: 10px;
            border-radius: 14px;
        }
        .stat-icon { 
            width: 34px; 
            height: 34px; 
            font-size: 14px; 
            border-radius: 12px;
        }
        .stat-value { font-size: 15px; }
        .stat-label { font-size: 9px; }
    }
</style>

<div class="roadmap-page">
    <!-- Decorative Background -->
    <div class="bg-decoration"></div>
    <div class="bg-decoration-2"></div>
    
    <div class="container">

        {{-- ====== JOURNEY HERO ====== --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border-0 overflow-hidden shadow rounded-4" style="background: linear-gradient(135deg,#0f172a 0%,#1e293b 100%);">
                    <div class="row g-0 align-items-center">
                        <div class="col-12 col-lg-7 p-3 p-md-4">
                            <span class="badge mb-3 px-3 py-2 rounded-pill fw-bold text-uppercase" style="font-size:11px;letter-spacing:1px;background:rgba(99,102,241,.2);color:#a5b4fc;">
                                <i class="bi bi-compass-fill me-1"></i> Active Journey
                            </span>
                            <h1 class="fw-900 text-white mb-1" style="font-size:clamp(20px,4vw,34px);word-break:break-word;">
                                {{ $overallProgress == 100 ? '🎉 Mission Accomplished!' : 'Resume Your Journey' }}
                            </h1>
                            <p class="text-white mb-3" style="opacity:.65;word-break:break-word;">{{ $roadmap->title }}</p>
                            @if(!$hasKnownDurations && $totalLessons > 0)
                                <div class="mb-3 px-3 py-2 rounded-3 d-flex align-items-center gap-2" style="background:rgba(245,158,11,.14);color:#fde68a;font-size:12px;font-weight:700;flex-wrap:wrap;">
                                    <i class="bi bi-info-circle"></i>
                                    Video durations are not synced yet. Open a lesson to start tracking accurate watch time.
                                </div>
                            @endif
                            <div class="roadmap-hero-cta d-flex flex-wrap gap-3 mt-4">
                                @if($nextIncompleteLesson ?? $currentLesson)
                                <a href="{{ route('learn.watch', [$nextIncompleteLesson ?? $currentLesson, 'roadmap_id' => $roadmap->id]) }}" class="btn btn-primary btn-lg rounded-pill fw-bold px-4 d-flex align-items-center gap-2 shadow-sm" style="font-size:clamp(12px,1.2vw,16px);">
                                    <i class="bi bi-play-fill"></i> Continue Watching
                                </a>
                                @endif
                                <a href="{{ route('ai.mentor') }}" class="btn btn-outline-light btn-lg rounded-pill px-4" style="opacity:.85;font-size:clamp(12px,1.2vw,16px);">
                                    <i class="bi bi-stars me-1"></i> Ask AI Mentor
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-5 d-none d-lg-flex align-items-center justify-content-center p-4 gap-4">
                            {{-- Overall Ring --}}
                            <div class="text-center">
                                <div class="progress-ring mx-auto mb-2" style="width:90px;height:90px;background:conic-gradient(#4f46e5 {{ $overallProgress * 3.6 }}deg, rgba(255,255,255,.1) 0deg);">
                                    <span class="text-black fw-900" style="font-size:18px;">{{ $overallProgress }}%</span>
                                </div>
                                <span class="text-white opacity-50" style="font-size:11px;font-weight:700;">Overall</span>
                            </div>
                            {{-- Time pill stats --}}
                            <div class="d-flex flex-column gap-2">
                                <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2" style="background:rgba(255,255,255,.07);">
                                    <i class="bi bi-collection-play text-info"></i>
                                    <div>
                                        <div class="text-white fw-700" style="font-size:13px;">{{ $formattedTotalDuration }} total</div>
                                        <div class="opacity-50 text-white" style="font-size:11px;">All roadmap videos</div>
                                    </div>
                                </div>
                                <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2" style="background:rgba(255,255,255,.07);">
                                    <i class="bi bi-clock-history text-primary"></i>
                                    <div>
                                        <div class="text-white fw-700" style="font-size:13px;">{{ $formattedWatchedTime }} watched</div>
                                        <div class="opacity-50 text-white" style="font-size:11px;">Time invested</div>
                                    </div>
                                </div>
                                <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2" style="background:rgba(255,255,255,.07);">
                                    <i class="bi bi-hourglass-split text-warning"></i>
                                    <div>
                                        <div class="text-white fw-700" style="font-size:13px;">{{ $formattedRemainingTime }}{{ $hasKnownDurations ? ' left' : '' }}</div>
                                        <div class="opacity-50 text-white" style="font-size:11px;">To complete roadmap</div>
                                    </div>
                                </div>
                                <div class="px-3 py-2 rounded-3 d-flex align-items-center gap-2" style="background:rgba(255,255,255,.07);">
                                    <i class="bi bi-mortarboard text-success"></i>
                                    <div>
                                        <div class="text-white fw-700" style="font-size:13px;">{{ $lessonsCompleted }} / {{ $totalLessons }} lessons</div>
                                        <div class="opacity-50 text-white" style="font-size:11px;">Completed</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====== MAIN LAYOUT ====== --}}
        <div class="roadmap-main">

            {{-- ====== TIMELINE ====== --}}
            <div class="roadmap-timeline">
                <div class="timeline-wrapper">
                    <div class="timeline-line"></div>

                    @foreach($roadmapData as $index => $data)
                        @php
                            $isCompleted = $data['percent'] == 100;
                            $isActive    = $data['percent'] > 0 && !$isCompleted;
                            $stepNum     = $index + 1;
                            $iconColors  = ['#4f46e5','#f59e0b','#10b981','#ec4899','#7c3aed'];
                            $iconBg      = ['#eef2ff','#fef3c7','#d1fae5','#fce7f3','#ede9fe'];
                        @endphp
                        <div class="step-item {{ $isCompleted ? 'completed' : '' }}">
                            <div class="step-dot">
                                @if($isCompleted) <i class="bi bi-check-lg" style="font-size:14px;"></i>
                                @else {{ $stepNum }} @endif
                            </div>

                            <div class="step-card">
                                {{-- Phase Header --}}
                                <div class="step-header">
                                    <div class="step-icon" style="background:{{ $iconBg[$index%5] }};color:{{ $iconColors[$index%5] }};flex-shrink:0;">
                                        @if($data['tool'] && $data['tool']->logo)
                                            <img src="{{ asset($data['tool']->logo) }}" alt="{{ $data['tool']->name }}">
                                        @else
                                            <i class="bi bi-gear-fill" style="font-size:18px;"></i>
                                        @endif
                                    </div>
                                    <div class="step-title-group">
                                        <div class="step-title d-flex align-items-center gap-2 flex-wrap">
                                            {{ $data['tool']->name ?? 'Phase '.$stepNum }}
                                            @if($isCompleted)
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size:10px;"><i class="bi bi-check-circle-fill"></i> Done</span>
                                            @elseif($isActive)
                                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1" style="font-size:10px;"><i class="bi bi-play-fill"></i> In Progress</span>
                                            @endif
                                        </div>
                                        <div class="step-subtitle">{{ $data['completed'] }} of {{ $data['total'] }} lessons complete</div>
                                    </div>
                                    <div class="step-progress-text ms-auto">{{ $data['percent'] }}%</div>
                                </div>

                                {{-- Lesson List --}}
                                <div class="step-lessons" id="lessons-{{ $data['tool']->id ?? $index }}">
                                    @foreach($data['contents'] as $loopIndex => $content)
                                        @php
                                            $isActiveLesson = $currentLesson && $currentLesson->id == $content->id;
                                            $isDone         = $content->is_completed ?? false;
                                            $pct            = $content->completion_pct ?? 0;
                                            $watchedS       = $content->watched_seconds ?? 0;
                                            $watchedMin     = floor($watchedS / 60);
                                            $durMin         = $content->duration_seconds > 0 ? max(1, ceil($content->duration_seconds / 60)) : 0;
                                            $hidden         = $loopIndex >= 5;
                                        @endphp

                                        <a href="{{ route('learn.watch', [$content, 'roadmap_id' => $roadmap->id]) }}"
                                           class="lesson-link {{ $isActiveLesson ? 'active' : '' }} {{ $isDone ? 'done' : '' }} {{ $hidden ? 'd-none extra-lesson' : '' }}"
                                           data-lesson-id="{{ $content->id }}">

                                            {{-- Status icon --}}
                                            <span class="check">
                                                @if($isDone) <i class="bi bi-check"></i> @endif
                                            </span>

                                            {{-- Title + meta --}}
                                            <div style="flex:1;min-width:0;">
                                                <div style="font-size:14px;font-weight:{{ $isActiveLesson ? '700' : '500' }};color:{{ $isActiveLesson ? '#4f46e5' : '#334155' }};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%;">
                                                    {{ $content->title }}
                                                </div>
                                                {{-- Mini progress bar (only if started but not done) --}}
                                                @if($pct > 0 && !$isDone)
                                                    <div style="height:3px;background:#f1f5f9;border-radius:3px;margin-top:5px;overflow:hidden;max-width:100%;">
                                                        <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,#4f46e5,#818cf8);border-radius:3px;transition:.5s;"></div>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Right badges --}}
                                            <div class="d-flex align-items-center gap-2 ms-2 flex-shrink-0">
                                                @if($isDone)
                                                    <span class="badge rounded-pill" style="font-size:10px;background:#d1fae5;color:#065f46;font-weight:700;"><i class="bi bi-check-circle-fill me-1"></i>Watched</span>
                                                @elseif($pct > 0)
                                                    <span class="badge rounded-pill" style="font-size:10px;background:#ede9fe;color:#4f46e5;font-weight:700;">{{ $pct }}%</span>
                                                @endif
                                                @if($durMin > 0)
                                                    <span style="font-size:11px;color:#94a3b8;font-weight:600;white-space:nowrap;">{{ $durMin }}m</span>
                                                @endif
                                                @if($isActiveLesson)
                                                    <span class="play-icon"><i class="bi bi-play-circle-fill"></i></span>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach

                                    @if($data['contents']->count() > 5)
                                        <button class="btn btn-sm w-100 mt-2 rounded-3 border-0 fw-700 text-primary"
                                                style="background:#f8faff;font-size:12px;max-width:100%;"
                                                onclick="toggleExtra(this,'lessons-{{ $data['tool']->id ?? $index }}')">
                                            <span><i class="bi bi-chevron-down me-1"></i>Show {{ $data['contents']->count() - 5 }} more lessons</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ====== SIDEBAR ====== --}}
            <div class="roadmap-sidebar">
                <div class="sidebar-card border-0 shadow-sm" style="position:sticky;top:90px;width:100%;max-width:100%;">
                    @if($currentLesson)
                        @php $cp = $currentProgressRecord; @endphp

                        {{-- Tool badge --}}
                        @if($currentTool)
                        <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                            @if($currentTool->logo)
                            <div style="width:36px;height:36px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <img src="{{ asset($currentTool->logo) }}" style="width:24px;height:24px;object-fit:contain;">
                            </div>
                            @endif
                            <span style="font-size:12px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.05em;word-break:break-word;">
                                {{ $currentTool->name }}
                                @if($currentProgressRecord && $currentProgressRecord->last_watched_at)
                                    · Last Watched {{ $currentProgressRecord->last_watched_at->diffForHumans() }}
                                @else
                                    · Last Watched
                                @endif
                            </span>
                        </div>
                        @endif

                        {{-- Lesson title --}}
                        <h4 class="fw-900 text-dark mb-3" style="font-size:clamp(16px,1.5vw,18px);line-height:1.35;word-break:break-word;">{{ $currentLesson->title }}</h4>

                        {{-- Thumbnail with progress overlay --}}
                        <div class="position-relative rounded-4 overflow-hidden mb-3 shadow-sm" style="aspect-ratio:16/9;background:#e2e8f0;width:100%;max-width:100%;">
                            @if($currentLesson->thumbnail_url)
                                <img src="{{ $currentLesson->thumbnail_url }}" class="w-100 h-100 object-fit-cover" style="width:100%;height:100%;object-fit:cover;">
                            @endif
                            {{-- Play icon overlay --}}
                            <div class="position-absolute inset-0 d-flex align-items-center justify-content-center">
                                <div style="width:52px;height:52px;background:rgba(0,0,0,.55);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-play-fill text-white" style="font-size:22px;margin-left:3px;"></i>
                                </div>
                            </div>
                            {{-- Duration badge --}}
                            <div class="position-absolute bottom-0 end-0 m-2 px-2 py-1 rounded-2 text-white fw-bold" style="background:rgba(0,0,0,.7);font-size:11px;">
                                {{ $currentLesson->duration_label ?: '—' }}
                            </div>
                        </div>

                        {{-- Progress bar for this lesson --}}
                        @php $lessonPct = $cp ? round($cp->completion_percent) : 0; @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1 flex-wrap">
                                <span style="font-size:12px;font-weight:700;color:#64748b;">Your progress</span>
                                <span style="font-size:12px;font-weight:800;color:#4f46e5;">{{ $lessonPct }}%</span>
                            </div>
                            <div style="height:6px;background:#f1f5f9;border-radius:6px;overflow:hidden;width:100%;">
                                <div style="height:100%;width:{{ $lessonPct }}%;background:linear-gradient(90deg,#4f46e5,#818cf8);border-radius:6px;transition:.6s;"></div>
                            </div>
                        </div>

                        {{-- Roadmap totals --}}
                        <div class="p-3 rounded-3 mb-4 d-flex flex-column gap-2" style="background:#f8fafc;border:1px solid #f1f5f9;width:100%;max-width:100%;">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                <span style="font-size:12px;color:#64748b;font-weight:700;"><i class="bi bi-clock-history me-1 text-primary"></i>Total Watched</span>
                                <span style="font-size:13px;font-weight:900;color:#0f172a;">
                                    {{ $formattedWatchedTime }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                <span style="font-size:12px;color:#64748b;font-weight:700;"><i class="bi bi-hourglass-split me-1 text-warning"></i>{{ $hasKnownDurations ? 'Time to Finish' : 'Time to Finish' }}</span>
                                <span style="font-size:13px;font-weight:900;color:#0f172a;">
                                    {{ $formattedRemainingTime }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-1">
                                <span style="font-size:12px;color:#64748b;font-weight:700;"><i class="bi bi-mortarboard me-1 text-success"></i>Completed</span>
                                <span style="font-size:13px;font-weight:900;color:#0f172a;">{{ $lessonsCompleted }} / {{ $totalLessons }}</span>
                            </div>
                        </div>

                        {{-- CTA — always points to next incomplete lesson --}}
                        @php $ctaLesson = $nextIncompleteLesson ?? $currentLesson; @endphp
                        @if($ctaLesson)
                        <a href="{{ route('learn.watch', [$ctaLesson, 'roadmap_id' => $roadmap->id]) }}" class="btn-roadmap btn-roadmap-primary" style="width:100%;max-width:100%;">
                            <i class="bi bi-play-fill"></i>
                            @if($ctaLesson->id === ($currentLesson->id ?? null) && $lessonPct > 0)
                                Continue Lesson
                            @elseif($ctaLesson->id !== ($currentLesson->id ?? null))
                                Next Lesson
                            @else
                                Start Lesson
                            @endif
                        </a>
                        @endif

                        {{-- Last watched timestamp --}}
                        @if($currentProgressRecord && $currentProgressRecord->last_watched_at)
                        <div class="text-center mt-3" style="font-size:11px;color:#94a3b8;word-break:break-word;">
                            Last watched {{ $currentProgressRecord->last_watched_at->diffForHumans() }}
                        </div>
                        @endif

                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-journal-richtext fs-1 text-primary opacity-50 mb-3 d-block"></i>
                            <p class="text-muted fw-bold mb-1">No lessons started yet</p>
                            <p class="text-muted small">Click any lesson in the roadmap to begin your journey.</p>
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
        : `<i class="bi bi-chevron-down me-1"></i>Show ${extras.length} more lessons`;
}
</script>
@endsection