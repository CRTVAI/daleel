@extends('layouts.user')

@section('title', 'Team Telemetry - Daleel AI')

@section('styles')
<style>
    /* ── RESET & BASE ── */
    * { box-sizing: border-box; }
    body { background: #f8f9fb; overflow-x: hidden; }

    /* ── Page top row ── */
    .page-top {
        margin-bottom: 1.75rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }
    .page-title {
        font-size: clamp(1.3rem, 3vw, 1.55rem);
        font-weight: 800;
        color: #111827;
        letter-spacing: -0.03em;
        margin: 0 0 0.2rem;
        word-break: break-word;
    }
    .page-sub {
        font-size: clamp(0.75rem, 1.2vw, 0.85rem);
        color: #6b7280;
        margin: 0;
        word-break: break-word;
    }

    /* ── KPI Strip ── */
    .kpi-strip {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        width: 100%;
        max-width: 100%;
    }
    .kpi-item {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 14px;
        padding: clamp(0.75rem, 1.5vw, 1rem) clamp(1rem, 2vw, 1.4rem);
        display: flex;
        align-items: center;
        gap: 0.9rem;
        flex: 1 1 180px;
        min-width: 140px;
        max-width: 100%;
    }
    .kpi-icon {
        width: clamp(34px, 4vw, 40px);
        height: clamp(34px, 4vw, 40px);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: clamp(0.9rem, 1.5vw, 1.1rem);
        flex-shrink: 0;
    }
    .kpi-value {
        font-size: clamp(1.1rem, 2.5vw, 1.4rem);
        font-weight: 800;
        color: #111827;
        line-height: 1.1;
        letter-spacing: -0.02em;
    }
    .kpi-label {
        font-size: clamp(0.6rem, 0.9vw, 0.72rem);
        font-weight: 600;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    /* ── Telemetry Banner ── */
    .telemetry-banner {
        background: #111827;
        border-radius: 16px;
        padding: clamp(1rem, 2vw, 1.5rem) clamp(1rem, 2vw, 1.75rem);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        width: 100%;
        max-width: 100%;
    }
    .telemetry-banner h5 {
        color: #fff;
        font-weight: 700;
        font-size: clamp(0.85rem, 1.3vw, 1rem);
        margin: 0 0 0.2rem;
        word-break: break-word;
    }
    .telemetry-banner p {
        color: #9ca3af;
        font-size: clamp(0.7rem, 1vw, 0.82rem);
        margin: 0;
        word-break: break-word;
    }
    .btn-telemetry {
        background: #fff;
        color: #111827;
        font-weight: 700;
        font-size: clamp(0.7rem, 1vw, 0.85rem);
        border: none;
        border-radius: 10px;
        padding: 0.6rem 1.2rem;
        white-space: nowrap;
        transition: background 0.15s;
        flex-shrink: 0;
    }
    .btn-telemetry:hover { background: #f3f4f6; color: #111827; }
    .btn-telemetry i { color: #4f46e5; }

    /* ── Section Cards ── */
    .section-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 16px;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
    }
    .section-head {
        padding: clamp(0.8rem, 1.5vw, 1.1rem) clamp(1rem, 2vw, 1.4rem);
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .section-head h5 {
        font-size: clamp(0.85rem, 1.2vw, 0.95rem);
        font-weight: 700;
        color: #111827;
        margin: 0;
        word-break: break-word;
    }
    .count-badge {
        background: #f3f4f6;
        color: #6b7280;
        border-radius: 20px;
        padding: 0.2rem 0.65rem;
        font-size: 0.75rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    /* ── Departments List ── */
    .dept-row {
        padding: clamp(0.75rem, 1.2vw, 1rem) clamp(1rem, 2vw, 1.4rem);
        border-bottom: 1px solid #f9fafb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        transition: background 0.12s;
        flex-wrap: wrap;
    }
    .dept-row:last-child { border-bottom: none; }
    .dept-row:hover { background: #fafafa; }
    .dept-name {
        font-weight: 700;
        font-size: clamp(0.8rem, 1.1vw, 0.9rem);
        color: #111827;
        word-break: break-word;
    }
    .dept-meta {
        font-size: clamp(0.65rem, 0.9vw, 0.78rem);
        color: #9ca3af;
        margin-top: 0.1rem;
    }
    .dept-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-shrink: 0;
        flex-wrap: wrap;
    }
    .btn-analyze {
        background: #f3f4f6;
        color: #374151;
        font-size: clamp(0.65rem, 0.9vw, 0.78rem);
        font-weight: 700;
        border: none;
        border-radius: 8px;
        padding: 0.35rem 0.75rem;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .btn-analyze:hover { background: #4f46e5; color: #fff; }
    .btn-analyze i { font-size: 0.7rem; }
    .btn-del {
        background: transparent;
        border: none;
        color: #d1d5db;
        padding: 0.3rem;
        border-radius: 6px;
        transition: all 0.15s;
        font-size: 0.8rem;
        line-height: 1;
    }
    .btn-del:hover { color: #ef4444; background: #fef2f2; }

    /* ── Employee Table ── */
    .emp-search-wrap {
        padding: clamp(0.6rem, 1.2vw, 0.85rem) clamp(1rem, 2vw, 1.4rem);
        border-bottom: 1px solid #f3f4f6;
        position: relative;
        width: 100%;
        max-width: 100%;
    }
    .emp-search-wrap i {
        position: absolute;
        top: 50%;
        left: clamp(1.8rem, 3vw, 2.15rem);
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 0.85rem;
    }
    .emp-search-input {
        width: 100%;
        max-width: 100%;
        background: #f9fafb;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 0.5rem 0.9rem 0.5rem clamp(1.8rem, 3vw, 2.2rem);
        font-size: clamp(0.75rem, 1vw, 0.85rem);
        font-weight: 500;
        color: #111827;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .emp-search-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.08);
        background: #fff;
    }

    .emp-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }
    .emp-table th {
        background: #f9fafb;
        color: #6b7280;
        font-size: clamp(0.6rem, 0.8vw, 0.7rem);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: clamp(0.5rem, 1vw, 0.75rem) clamp(0.5rem, 1vw, 1rem);
        border-bottom: 1px solid #f3f4f6;
        white-space: nowrap;
        text-align: left;
    }
    .emp-table td {
        padding: clamp(0.5rem, 1vw, 0.85rem) clamp(0.5rem, 1vw, 1rem);
        border-bottom: 1px solid #f9fafb;
        vertical-align: middle;
        font-size: clamp(0.75rem, 1vw, 0.875rem);
        color: #374151;
    }
    .emp-table tbody tr:last-child td { border-bottom: none; }
    .emp-table tbody tr:hover td { background: #fafafa; }

    /* Avatar initials circle */
    .emp-avatar {
        width: clamp(28px, 3.5vw, 34px);
        height: clamp(28px, 3.5vw, 34px);
        border-radius: 50%;
        font-weight: 700;
        font-size: clamp(0.6rem, 0.9vw, 0.75rem);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .emp-name {
        font-weight: 700;
        color: #111827;
        font-size: clamp(0.75rem, 1vw, 0.875rem);
        word-break: break-word;
    }
    .emp-dept {
        font-size: clamp(0.6rem, 0.8vw, 0.72rem);
        color: #9ca3af;
        margin-top: 1px;
        word-break: break-word;
    }

    .code-chip {
        background: #f3f4f6;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 0.25rem 0.55rem;
        font-family: monospace;
        font-size: clamp(0.65rem, 0.9vw, 0.78rem);
        font-weight: 600;
        color: #374151;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: all 0.15s;
        word-break: break-all;
        max-width: 100%;
    }
    .code-chip:hover { background: #e9ecef; color: #111827; }
    .code-chip i { font-size: 0.7rem; color: #9ca3af; flex-shrink: 0; }

    .time-chip {
        background: #f3f4f6;
        border-radius: 6px;
        padding: 0.25rem 0.6rem;
        font-size: clamp(0.65rem, 0.9vw, 0.78rem);
        font-weight: 700;
        color: #374151;
        display: inline-block;
    }

    .btn-view-tools {
        background: #4f46e5;
        color: #fff;
        font-size: clamp(0.65rem, 0.8vw, 0.75rem);
        font-weight: 700;
        border: none;
        border-radius: 7px;
        padding: 0.35rem 0.75rem;
        transition: all 0.15s;
        white-space: nowrap;
    }
    .btn-view-tools:hover { background: #4338ca; color: #fff; }
    .btn-view-tools i { font-size: 0.7rem; }

    /* ── Sidebar (Offcanvas) ── */
    #telemetrySidebar {
        width: min(480px, 95vw);
        max-width: 95vw;
        border-left: 1px solid #e9ecef !important;
        box-shadow: -8px 0 40px rgba(0,0,0,0.06);
    }
    .sidebar-header {
        padding: clamp(1rem, 1.5vw, 1.25rem) clamp(1rem, 2vw, 1.5rem);
        background: #fff;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .sidebar-title {
        font-weight: 800;
        font-size: clamp(0.9rem, 1.3vw, 1rem);
        color: #111827;
        margin: 0 0 0.15rem;
        word-break: break-word;
    }
    .sidebar-sub {
        font-size: clamp(0.7rem, 0.9vw, 0.78rem);
        color: #9ca3af;
        margin: 0;
        word-break: break-word;
    }

    /* ── Empty States ── */
    .empty-state {
        text-align: center;
        padding: clamp(1.5rem, 4vw, 3rem) clamp(1rem, 2vw, 1.5rem);
        color: #9ca3af;
    }
    .empty-state i { font-size: 2rem; display: block; margin-bottom: 0.75rem; opacity: 0.35; }
    .empty-state h6 { font-weight: 700; color: #374151; margin-bottom: 0.25rem; font-size: clamp(0.9rem, 1.2vw, 1rem); }
    .empty-state p  { font-size: clamp(0.7rem, 1vw, 0.82rem); margin: 0; }

    /* ── Action buttons in header ── */
    .page-top .d-flex.gap-2 {
        flex-wrap: wrap;
        gap: 0.5rem !important;
    }
    .btn-head-outline {
        background: #fff;
        border: 1px solid #d1d5db;
        color: #374151;
        font-weight: 700;
        font-size: clamp(0.7rem, 1vw, 0.82rem);
        border-radius: 9px;
        padding: clamp(0.4rem, 0.8vw, 0.5rem) clamp(0.75rem, 1.2vw, 1rem);
        transition: all 0.15s;
        white-space: nowrap;
    }
    .btn-head-outline:hover { border-color: #9ca3af; background: #f9fafb; color: #111827; }
    .btn-head-primary {
        background: #4f46e5;
        border: none;
        color: #fff;
        font-weight: 700;
        font-size: clamp(0.7rem, 1vw, 0.82rem);
        border-radius: 9px;
        padding: clamp(0.4rem, 0.8vw, 0.5rem) clamp(0.75rem, 1.2vw, 1rem);
        transition: all 0.15s;
        white-space: nowrap;
    }
    .btn-head-primary:hover { background: #4338ca; color: #fff; }

    .btn-sync-extension {
        background: #111827;
        border: none;
        color: #fff;
        font-weight: 700;
        font-size: clamp(0.7rem, 1vw, 0.82rem);
        border-radius: 9px;
        padding: clamp(0.4rem, 0.8vw, 0.5rem) clamp(0.75rem, 1.2vw, 1rem);
        transition: all 0.15s;
        white-space: nowrap;
    }
    .btn-sync-extension:hover { background: #1f2937; color: #fff; }
    .btn-sync-extension:disabled {
        background: #6b7280;
        cursor: wait;
        opacity: 0.85;
    }
    .page-top .form-select {
        font-size: clamp(0.7rem, 1vw, 0.875rem);
        min-width: 100px;
        max-width: 100%;
    }

    /* ── Colors for dept/employee avatar dots ── */
    .c1 { background: #4f46e5; }
    .c2 { background: #0ea5e9; }
    .c3 { background: #10b981; }
    .c4 { background: #f59e0b; }
    .c5 { background: #ec4899; }
    .c6 { background: #8b5cf6; }
    .c7 { background: #ef4444; }
    .c8 { background: #14b8a6; }

    /* dept dot */
    .dept-dot {
        width: clamp(30px, 3.5vw, 36px);
        height: clamp(30px, 3.5vw, 36px);
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: clamp(0.65rem, 0.9vw, 0.8rem);
        color: #fff;
        flex-shrink: 0;
    }

    /* ── Team Restricted Overlay & Blur ── */
    .team-restricted-wrapper {
        position: relative;
        min-height: 85vh;
        border-radius: 16px;
        background: #f8f9fb;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    .team-blur-container {
        filter: blur(8px) grayscale(30%);
        pointer-events: none;
        user-select: none;
        opacity: 0.5;
        transition: filter 0.3s ease;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    .team-gate-overlay {
        position: absolute;
        inset: 0;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(248, 249, 251, 0.4);
        padding: 2rem 1.5rem;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    .team-gate-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 24px;
        box-shadow: 0 30px 70px rgba(0,0,0,0.12);
        padding: clamp(1.5rem, 4vw, 3rem) clamp(1rem, 3vw, 2.5rem);
        max-width: 580px;
        width: 100%;
        text-align: center;
    }
    .gate-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #eef2ff;
        color: #4f46e5;
        font-size: clamp(0.6rem, 0.9vw, 0.75rem);
        font-weight: 800;
        padding: 0.4rem 0.95rem;
        border-radius: 20px;
        margin-bottom: 1.25rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .team-gate-card h2 {
        font-size: clamp(1.3rem, 3.5vw, 1.85rem);
        font-weight: 900;
        color: #111827;
        letter-spacing: -0.03em;
        margin-bottom: 0.85rem;
        line-height: 1.2;
        word-break: break-word;
    }
    .team-gate-card p {
        color: #4b5563;
        font-size: clamp(0.8rem, 1.2vw, 0.95rem);
        line-height: 1.6;
        margin-bottom: 2rem;
        word-break: break-word;
    }
    .gate-features-mini {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        margin-bottom: 2.25rem;
        text-align: left;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: clamp(0.75rem, 2vw, 1.25rem);
        width: 100%;
        max-width: 100%;
    }
    .gate-feature-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }
    .gate-feature-item i {
        color: #4f46e5;
        font-size: clamp(0.85rem, 1.2vw, 1rem);
        margin-top: 0.15rem;
        flex-shrink: 0;
    }
    .gate-feature-item span {
        font-size: clamp(0.75rem, 1vw, 0.85rem);
        color: #374151;
        line-height: 1.4;
        word-break: break-word;
    }
    .btn-request-access {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #fff;
        border: none;
        font-weight: 800;
        font-size: clamp(0.8rem, 1.1vw, 0.95rem);
        border-radius: 12px;
        padding: 0.85rem 2.2rem;
        width: 100%;
        transition: all 0.2s;
        box-shadow: 0 4px 14px rgba(79,70,229,0.3);
    }
    .btn-request-access:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(79,70,229,0.45);
        color: #fff;
    }

    /* Modal form config */
    .contact-modal-icon {
        width: 56px; height: 56px;
        border-radius: 14px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 1.4rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    /* ── RESPONSIVE MEDIA QUERIES ── */
    @media (max-width: 1200px) {
        .col-lg-4, .col-lg-8 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .row.g-4 > * {
            padding-left: calc(1.5rem * 0.5);
            padding-right: calc(1.5rem * 0.5);
        }
    }

    @media (max-width: 992px) {
        .emp-table {
            min-width: 500px;
        }
        .emp-table th,
        .emp-table td {
            padding: 0.6rem 0.5rem;
        }
        .page-top .d-flex.gap-2 {
            width: 100%;
        }
        .page-top .form-select {
            min-width: 80px;
        }
        .dept-row {
            flex-wrap: wrap;
        }
        .dept-actions {
            margin-left: auto;
        }
        .telemetry-banner .btn-telemetry {
            white-space: normal;
            word-break: break-word;
        }
    }

    @media (max-width: 768px) {
        .kpi-item {
            flex: 1 1 100%;
            min-width: 100%;
        }
        .kpi-strip {
            gap: 0.75rem;
        }
        .telemetry-banner {
            flex-direction: column;
            align-items: flex-start;
        }
        .telemetry-banner .btn-telemetry {
            width: 100%;
            justify-content: center;
        }
        .page-top {
            flex-direction: column;
            align-items: stretch !important;
        }
        .page-top .d-flex.gap-2 {
            flex-wrap: wrap;
            width: 100%;
        }
        .page-top .d-flex.gap-2 > * {
            flex: 1 1 auto;
            min-width: 0;
        }
        .page-top .form-select {
            min-width: 60px;
            flex: 0 1 auto;
        }
        .btn-head-outline,
        .btn-head-primary,
        .btn-sync-extension {
            font-size: 0.7rem;
            padding: 0.4rem 0.6rem;
            white-space: normal;
            word-break: break-word;
        }
        .section-head {
            flex-direction: column;
            align-items: flex-start;
        }
        .emp-table {
            min-width: 100%;
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .emp-table thead,
        .emp-table tbody,
        .emp-table tr {
            display: block;
        }
        .emp-table thead {
            display: none;
        }
        .emp-table tr {
            margin-bottom: 0.75rem;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem;
            background: #fff;
        }
        .emp-table td {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.4rem 0;
            border: none;
            border-bottom: 1px solid #f3f4f6;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .emp-table td:last-child {
            border-bottom: none;
        }
        .emp-table td::before {
            content: attr(data-label);
            font-weight: 700;
            font-size: 0.6rem;
            text-transform: uppercase;
            color: #9ca3af;
            letter-spacing: 0.06em;
            flex-shrink: 0;
        }
        .emp-table td .d-flex.align-items-center.gap-2 {
            flex-wrap: wrap;
        }
        .emp-table td .text-end,
        .emp-table td .d-flex.justify-content-end {
            justify-content: flex-start !important;
            text-align: left !important;
        }
        .emp-table td .progress {
            width: 40px !important;
        }
        #telemetrySidebar {
            width: 100%;
            max-width: 100%;
        }
        .btn-view-tools,
        .btn-analyze {
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
        }
        .team-gate-card {
            padding: 1.5rem 1rem;
        }
        .team-gate-card h2 {
            font-size: 1.3rem;
        }
        .code-chip {
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
        }
        .time-chip {
            font-size: 0.6rem;
            padding: 0.2rem 0.4rem;
        }
    }

    @media (max-width: 576px) {
        .page-title {
            font-size: 1.1rem;
        }
        .page-sub {
            font-size: 0.7rem;
        }
        .kpi-item {
            padding: 0.6rem 0.8rem;
            gap: 0.6rem;
        }
        .kpi-icon {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }
        .kpi-value {
            font-size: 0.95rem;
        }
        .kpi-label {
            font-size: 0.55rem;
        }
        .telemetry-banner {
            padding: 0.75rem 1rem;
        }
        .telemetry-banner h5 {
            font-size: 0.8rem;
        }
        .telemetry-banner p {
            font-size: 0.65rem;
        }
        .btn-telemetry {
            font-size: 0.65rem;
            padding: 0.4rem 0.8rem;
        }
        .dept-row {
            padding: 0.6rem 0.8rem;
        }
        .dept-dot {
            width: 26px;
            height: 26px;
            font-size: 0.55rem;
            border-radius: 7px;
        }
        .dept-name {
            font-size: 0.75rem;
        }
        .dept-meta {
            font-size: 0.6rem;
        }
        .dept-actions .btn-analyze {
            font-size: 0.6rem;
            padding: 0.25rem 0.5rem;
        }
        .section-head {
            padding: 0.6rem 0.8rem;
        }
        .section-head h5 {
            font-size: 0.8rem;
        }
        .count-badge {
            font-size: 0.65rem;
            padding: 0.15rem 0.5rem;
        }
        .emp-search-wrap {
            padding: 0.5rem 0.8rem;
        }
        .emp-search-input {
            font-size: 0.7rem;
            padding: 0.4rem 0.6rem 0.4rem 1.8rem;
        }
        .emp-search-wrap i {
            left: 1.4rem;
            font-size: 0.7rem;
        }
        .emp-avatar {
            width: 24px;
            height: 24px;
            font-size: 0.5rem;
        }
        .emp-name {
            font-size: 0.7rem;
        }
        .emp-dept {
            font-size: 0.55rem;
        }
        .emp-table td {
            font-size: 0.7rem;
            padding: 0.3rem 0;
        }
        .emp-table td::before {
            font-size: 0.5rem;
        }
        .btn-head-outline,
        .btn-head-primary,
        .btn-sync-extension {
            font-size: 0.6rem;
            padding: 0.3rem 0.5rem;
            border-radius: 6px;
        }
        .page-top .form-select {
            font-size: 0.6rem;
            min-width: 50px;
            padding: 0.25rem 0.5rem;
        }
        .btn-view-tools {
            font-size: 0.55rem;
            padding: 0.2rem 0.4rem;
            border-radius: 5px;
        }
        .btn-analyze {
            font-size: 0.55rem;
            padding: 0.2rem 0.4rem;
            border-radius: 5px;
        }
        .code-chip {
            font-size: 0.55rem;
            padding: 0.15rem 0.3rem;
            gap: 0.15rem;
        }
        .code-chip i {
            font-size: 0.55rem;
        }
        .time-chip {
            font-size: 0.55rem;
            padding: 0.15rem 0.3rem;
        }
        .gate-features-mini {
            padding: 0.75rem;
        }
        .gate-feature-item span {
            font-size: 0.7rem;
        }
        .btn-request-access {
            font-size: 0.75rem;
            padding: 0.6rem 1.5rem;
        }
        .team-gate-card h2 {
            font-size: 1.1rem;
        }
        .team-gate-card p {
            font-size: 0.75rem;
        }
        .gate-badge {
            font-size: 0.55rem;
            padding: 0.3rem 0.7rem;
        }
        .modal-dialog {
            margin: 0.5rem;
        }
        .modal-content {
            border-radius: 16px !important;
        }
        .modal-body {
            padding: 1rem !important;
        }
        .sidebar-header {
            padding: 0.75rem 1rem;
        }
        .sidebar-title {
            font-size: 0.85rem;
        }
        .sidebar-sub {
            font-size: 0.65rem;
        }
        #telemetrySidebar {
            width: 100%;
            max-width: 100%;
        }
    }

    @media (max-width: 400px) {
        .team-gate-card {
            padding: 1rem 0.75rem;
        }
        .gate-features-mini {
            padding: 0.5rem;
            gap: 0.5rem;
        }
        .gate-feature-item {
            gap: 0.5rem;
        }
        .gate-feature-item i {
            font-size: 0.75rem;
        }
        .gate-feature-item span {
            font-size: 0.65rem;
        }
        .btn-request-access {
            font-size: 0.7rem;
            padding: 0.5rem 1rem;
        }
        .emp-table td {
            flex-direction: column;
            align-items: flex-start;
        }
        .emp-table td::before {
            width: 100%;
        }
        .emp-table td .d-flex.align-items-center.gap-2 {
            width: 100%;
        }
        .dept-actions {
            width: 100%;
            justify-content: flex-start;
        }
        .page-top .d-flex.gap-2 {
            flex-direction: column;
            align-items: stretch;
        }
        .page-top .form-select {
            width: 100%;
        }
        .btn-head-outline,
        .btn-head-primary,
        .btn-sync-extension {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
@php
    $ms = function($v) {
        $v = max(0, intval($v));
        if ($v < 60000) return round($v/1000).'s';
        $m = floor($v/60000);
        if ($m < 60) return $m.'m';
        return floor($m/60).'h '.($m%60).'m';
    };
    $colors = ['c1','c2','c3','c4','c5','c6','c7','c8'];
@endphp

<div class="team-restricted-wrapper">
    @if(!auth()->user()->can_access_team)
    <div class="team-gate-overlay">
        <div class="team-gate-card">
            <div class="gate-badge"><i class="bi bi-shield-lock-fill"></i> Premium Feature</div>
            <h2>Team Telemetry & Analytics</h2>
            <p>Monitor how your entire team uses AI tools in real time. Get department-level insights, track productivity, identify top tools, and make smarter decisions with live telemetry data from every employee's browser.</p>

            <div class="gate-features-mini">
                <div class="gate-feature-item">
                    <i class="bi bi-check-circle-fill"></i>
                    <span><strong>Employee Tracking:</strong> Track active time, tool usage, and productivity metrics for every employee.</span>
                </div>
                <div class="gate-feature-item">
                    <i class="bi bi-check-circle-fill"></i>
                    <span><strong>Department Analytics:</strong> Group employees by department and see aggregated tool usage side by side.</span>
                </div>
                <div class="gate-feature-item">
                    <i class="bi bi-check-circle-fill"></i>
                    <span><strong>Live Telemetry:</strong> See who is online right now, which platforms they are using, and audit requests.</span>
                </div>
            </div>

            <button class="btn btn-request-access" data-bs-toggle="modal" data-bs-target="#teamAccessModal">
                <i class="bi bi-send-fill me-2"></i>Request Team Access
            </button>

            @if(session('enterprise_contact_success'))
            <div class="alert d-flex align-items-center gap-2 rounded-3 mt-3 text-start" style="background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;font-size:0.85rem;">
                <i class="bi bi-check-circle-fill fs-6"></i>
                <div><strong>Request sent!</strong> Admin will review and grant access shortly.</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="{{ !auth()->user()->can_access_team ? 'team-blur-container' : '' }}" style="width:100%;max-width:100%;overflow:hidden;">

{{-- Page Top Row --}}
<div class="page-top d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">Team Telemetry</h1>
        <p class="page-sub">Track which sites and tools your departments and employees use most.</p>
    </div>
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <select class="form-select" onchange="window.location.href='?range='+this.value" style="width: auto; min-width: 100px; border-color: #e5e7eb; border-radius: 8px; font-weight: 500; font-size: clamp(0.7rem, 1vw, 0.875rem);">
            <option value="today" {{ (isset($range) && $range == 'today') ? 'selected' : '' }}>Today</option>
            <option value="7days" {{ (isset($range) && $range == '7days') ? 'selected' : '' }}>Last 7 Days</option>
            <option value="30days" {{ (isset($range) && $range == '30days') ? 'selected' : '' }}>Last 30 Days</option>
            <option value="all" {{ (!isset($range) || $range == 'all') ? 'selected' : '' }}>All Time</option>
        </select>
        <button type="button" class="btn btn-sync-extension" id="extensionSyncNowBtn" title="Requests sync from the extension installed in this browser. Employee browsers must upload their own latest data.">
            <i class="bi bi-arrow-repeat me-1"></i> Sync
        </button>
        <button class="btn-head-outline btn" data-bs-toggle="modal" data-bs-target="#addDeptModal">
            <i class="bi bi-diagram-3 me-1"></i> Add Dept
        </button>
        <button class="btn-head-primary btn" data-bs-toggle="modal" data-bs-target="#addEmpModal">
            <i class="bi bi-person-plus me-1"></i> Add Emp
        </button>
    </div>
</div>

<div style="width:100%;max-width:100%;overflow:hidden;">

    @if(session('success'))
        <div class="d-flex align-items-center gap-2 mb-4 px-3 py-2 rounded-3"
             style="background:#ecfdf5; color:#065f46; font-weight:600; font-size:clamp(0.7rem, 1vw, 0.875rem);border:1px solid #a7f3d0;flex-wrap:wrap;">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    {{-- KPI Strip --}}
    <div class="kpi-strip">

        <div class="kpi-item">
            <div class="kpi-icon" style="background:#dcfce7; color:#16a34a;"><i class="bi bi-diagram-3-fill"></i></div>
            <div>
                <div class="kpi-value">{{ $departments->count() }}</div>
                <div class="kpi-label">Departments</div>
            </div>
        </div>
        <div class="kpi-item">
            <div class="kpi-icon" style="background:#e0f2fe; color:#0284c7;"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="kpi-value">{{ $employees->total() }}</div>
                <div class="kpi-label">Employees</div>
            </div>
        </div>
    </div>

    {{-- Company Telemetry Banner --}}
    <div class="telemetry-banner">
        <div>
            <h5><i class="bi bi-bar-chart-fill me-2" style="color:#818cf8;"></i>Overall Company Telemetry</h5>
            <p>See which platforms, tools and AI assistants are most used across your entire company.</p>
        </div>
        <button class="btn btn-telemetry btn-view-overall-sites"
                data-name="Overall Company Telemetry"
                data-url="{{ route('team.overall-top-sites', ['range' => $range ?? 'today']) }}">
            <i class="bi bi-bar-chart-line-fill me-1"></i> View Top Visited Tools
        </button>
    </div>

    <div class="row g-4">

        {{-- ── DEPARTMENTS ── --}}
        <div class="col-lg-4">
            <div class="section-card">
                <div class="section-head">
                    <h5><i class="bi bi-diagram-3 me-2 text-success"></i>Departments</h5>
                    <span class="count-badge">{{ $departments->count() }}</span>
                </div>

                @forelse($departments as $dept)
                    @php $dc = $colors[$dept->id % count($colors)]; @endphp
                    <div class="dept-row">
                        <div class="d-flex align-items-center gap-3" style="flex:1;min-width:0;">
                            <div class="dept-dot {{ $dc }}">{{ mb_substr($dept->name, 0, 1) }}</div>
                            <div style="min-width:0;">
                                <div class="dept-name">{{ $dept->name }}</div>
                                <div class="dept-meta">{{ $dept->employees_count }} employees</div>
                            </div>
                        </div>
                        <div class="dept-actions">
                            <button class="btn btn-analyze btn-view-dept-sites"
                                    data-id="{{ $dept->id }}"
                                    data-name="{{ $dept->name }}"
                                    data-url="{{ route('team.departments.top-sites', ['department' => $dept, 'range' => $range ?? 'today']) }}">
                                <i class="bi bi-bar-chart-line-fill"></i> Analyze
                            </button>
                            <form action="{{ route('team.departments.destroy', $dept) }}" method="POST"
                                  onsubmit="return confirm('Delete this department?');" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-del"><i class="bi bi-trash3"></i></button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-diagram-3"></i>
                        <h6>No Departments Yet</h6>
                        <p>Add departments to group your employees.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ── EMPLOYEES ── --}}
        <div class="col-lg-8">
            <div class="section-card">
                <div class="section-head">
                    <h5><i class="bi bi-people me-2 text-primary"></i>Employees</h5>
                    <span class="count-badge">{{ $employees->total() }}</span>
                </div>

                <div class="emp-search-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" id="empSearchInput" class="emp-search-input" placeholder="Search by name…">
                </div>

                <div style="overflow-x:auto;width:100%;max-width:100%;">
                    <table class="emp-table">
                        <thead>
                            <tr>
                                <th>Employee / Dept</th>
                                <th>Status</th>
                                <th>Connection Key</th>
                                <th>Active Time</th>
                                <th class="text-center">Activity</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="empTableBody">
                        @forelse($employees as $emp)
                            @php
                                $ec = $colors[$emp->id % count($colors)];
                                $initials = collect(explode(' ', $emp->name))
                                    ->map(fn($n) => mb_substr($n,0,1))->take(2)->join('');
                                $activeRatio = $empActiveRatios[$emp->id] ?? 0;
                                $lastSynced = $empLastSyncedAt[$emp->id];
                                $isOnline = $lastSynced && $lastSynced->diffInMinutes(now()) < 5;
                            @endphp
                            <tr class="emp-row" data-name="{{ strtolower($emp->name) }}">
                                <td data-label="Employee">
                                    <div class="d-flex align-items-center gap-2" style="flex-wrap:wrap;">
                                        <div class="emp-avatar {{ $ec }}">{{ strtoupper($initials) }}</div>
                                        <div>
                                            <div class="emp-name">{{ $emp->name }}</div>
                                            <div class="emp-dept">
                                                {{ $emp->department?->name ?? 'No Department' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Status">
                                    @if($isOnline)
                                        <span class="badge rounded-pill bg-success p-2 d-inline-flex align-items-center gap-1" style="font-size: clamp(8px, 1vw, 10px); font-weight: 800;">
                                            <span style="width: 6px; height: 6px; background: #fff; border-radius: 50%; display: block; animation: pulse 1.5s infinite;"></span> ONLINE
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-light text-muted border p-2" style="font-size: clamp(8px, 1vw, 10px); font-weight: 800;">
                                            OFFLINE
                                        </span>
                                    @endif
                                </td>
                                <td data-label="Connection Key">
                                    <div class="d-flex align-items-center gap-2" style="flex-wrap:wrap;">
                                        <span class="code-chip"
                                              onclick="navigator.clipboard.writeText('{{ $emp->connection_code }}'); showToast('Code copied!');"
                                              title="Click to copy">
                                            {{ $emp->connection_code }}
                                            <i class="bi bi-clipboard"></i>
                                        </span>
                                        <form action="{{ route('team.employees.regenerate-code', $emp) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-light p-1" title="Regenerate Key" onclick="return confirm('Regenerate key? The current extension will need to be re-linked.');">
                                                <i class="bi bi-arrow-clockwise text-muted" style="font-size: 0.75rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @if($emp->connection_code_issued_at)
                                        <div style="font-size: clamp(8px, 0.8vw, 10px); margin-top: 3px;">
                                            <span class="{{ $emp->connection_code_issued_at->diffInDays(now()) > 30 ? 'text-warning fw-bold' : 'text-muted' }}">
                                                Issued {{ $emp->connection_code_issued_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td data-label="Active Time">
                                    <span class="time-chip">{{ $ms($empActiveMs[$emp->id] ?? 0) }}</span>
                                    <div style="font-size: clamp(8px, 0.8vw, 10px); color: #9ca3af; margin-top: 2px;">{{ $lastSynced ? 'Last sync: '.$lastSynced->diffForHumans() : 'Never synced' }}</div>
                                </td>
                                <td data-label="Activity" class="text-center">
                                    <div class="progress" style="height: 6px; width: clamp(40px, 6vw, 60px); margin: 0 auto 4px; background: #f1f5f9;">
                                      <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $activeRatio }}%"></div>
                                    </div>
                                    <span style="font-size: clamp(8px, 0.8vw, 10px); font-weight: 800; color: #4b5563;">{{ $activeRatio }}%</span>
                                </td>
                                <td data-label="Actions" class="text-end">
                                    <div class="d-flex justify-content-end gap-2" style="flex-wrap:wrap;">
                                        <button class="btn btn-view-tools btn-view-emp-sites px-2 py-1"
                                                data-id="{{ $emp->id }}"
                                                data-name="{{ $emp->name }}"
                                                data-url="{{ route('team.employees.top-sites', ['employee' => $emp, 'range' => $range ?? 'today']) }}"
                                                title="View Tools">
                                            <i class="bi bi-bar-chart-line-fill"></i> Sites
                                        </button>
                                        <button class="btn btn-analyze btn-view-help px-2 py-1"
                                                style="background: #f5f3ff; color: #7c3aed; border: 1px solid #ddd6fe;"
                                                data-id="{{ $emp->id }}"
                                                data-name="{{ $emp->name }}"
                                                data-url="{{ route('team.employees.help-requests', $emp) }}"
                                                title="View Help">
                                            <i class="bi bi-chat-dots-fill"></i> Help
                                        </button>
                                        <form action="{{ route('team.employees.destroy', $emp) }}" method="POST"
                                              onsubmit="return confirm('Remove this employee?');" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-del"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="bi bi-people"></i>
                                        <h6>No Employees Yet</h6>
                                        <p>Add employees to start tracking their tool usage.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($employees->hasPages())
                    <div class="p-3 border-top d-flex justify-content-center" style="flex-wrap:wrap;">
                        {{ $employees->appends(request()->except('employees_page'))->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── MODALS ── --}}
<div class="modal fade" id="addDeptModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('team.departments.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="fw-800 text-dark mb-0">New Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <label class="form-label fw-600 text-muted small mb-1">Department Name</label>
                    <input type="text" name="name" class="form-control rounded-3"
                           placeholder="e.g. Engineering, Marketing" required>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-light fw-600 rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-700 rounded-3 px-4">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addEmpModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('team.employees.store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <h5 class="fw-800 text-dark mb-0">Add Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-3">
                    <div class="mb-3">
                        <label class="form-label fw-600 text-muted small mb-1">Full Name</label>
                        <input type="text" name="name" class="form-control rounded-3"
                               placeholder="e.g. John Doe" required>
                    </div>
                    <div>
                        <label class="form-label fw-600 text-muted small mb-1">Department (Optional)</label>
                        <select name="department_id" class="form-select rounded-3">
                            <option value="">— None —</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0 gap-2">
                    <button type="button" class="btn btn-light fw-600 rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-700 rounded-3 px-4">Add & Generate Key</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── OFFCANVAS SIDEBAR ── --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="telemetrySidebar"
     aria-labelledby="telemetrySidebarLabel" style="border:none;">
    <div class="sidebar-header">
        <div>
            <p class="sidebar-title" id="telemetrySidebarLabel">Top Visited Tools</p>
            <p class="sidebar-sub" id="telemetrySidebarSublabel"></p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0" id="telemetrySidebarBody" style="padding-top:0 !important;">
        <div class="text-center py-5" id="telemetrySidebarSpinner">
            <div class="spinner-border text-primary" role="status" style="width:1.6rem;height:1.6rem;"></div>
            <p class="text-muted small mt-2 mb-0">Loading…</p>
        </div>
    </div>
    </div> {{-- End of team-blur-container --}}
</div> {{-- End of team-restricted-wrapper --}}

{{-- Contact Modal --}}
<div class="modal fade" id="teamAccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <div class="contact-modal-icon"><i class="bi bi-people-fill"></i></div>
                    <h5 class="fw-800 text-dark mb-1">Request Team Access</h5>
                    <p class="text-muted small mb-0">Your request will be sent to the admin for review.</p>
                </div>
                <form action="{{ route('enterprise.contact.send') }}" method="POST">
                    @csrf
                    <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                    <div class="mb-3">
                        <label class="form-label fw-600 small text-muted mb-1">Your Email</label>
                        <input type="email" name="email" class="form-control rounded-3" value="{{ auth()->user()->email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 small text-muted mb-1">Subject</label>
                        <input type="text" name="subject" class="form-control rounded-3" value="Team Access Request - {{ auth()->user()->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600 small text-muted mb-1">Message <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control rounded-3" rows="3" placeholder="Hi, I'd like to request access to Team Telemetry for my organization..." required></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light fw-600 rounded-3 flex-grow-1" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fw-700 rounded-3 flex-grow-1">
                            <i class="bi bi-envelope-fill me-1"></i> Send Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const syncBtn = document.getElementById('extensionSyncNowBtn');

    if (syncBtn) {
        let syncFinished = false;
        const originalSyncHtml = syncBtn.innerHTML;

        window.addEventListener('message', function (event) {
            if (event.source !== window || !event.data || event.data.type !== 'Daleel AI_EXTENSION_SYNC_RESULT') {
                return;
            }

            syncFinished = true;
            const ok = event.data.ok !== false;
            showToast(ok ? 'Extension data synced. Refreshing team data...' : 'Extension sync failed. Please open the extension and retry.', ok ? 'success' : 'info');

            if (ok) {
                setTimeout(() => window.location.reload(), 900);
            } else {
                syncBtn.disabled = false;
                syncBtn.innerHTML = originalSyncHtml;
            }
        });

        syncBtn.addEventListener('click', function () {
            syncFinished = false;
            syncBtn.disabled = true;
            syncBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Syncing...';

            window.postMessage({
                type: 'Daleel AI_EXTENSION_SYNC_NOW',
                source: 'team-page',
                requestedAt: new Date().toISOString(),
            }, window.location.origin);

            setTimeout(() => {
                if (syncFinished) {
                    return;
                }

                showToast('No direct extension response. Refreshing latest saved data...', 'info');
                window.location.reload();
            }, 4500);
        });
    }

    // Search
    const search = document.getElementById('empSearchInput');
    if (search) {
        search.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('.emp-row').forEach(r => {
                r.style.display = r.dataset.name.includes(q) ? '' : 'none';
            });
        });
    }

    // Sidebar
    const sidebarEl  = document.getElementById('telemetrySidebar');
    const sidebar    = new bootstrap.Offcanvas(sidebarEl);
    const sTitle     = document.getElementById('telemetrySidebarLabel');
    const sSub       = document.getElementById('telemetrySidebarSublabel');
    const sBody      = document.getElementById('telemetrySidebarBody');
    const spinner    = document.getElementById('telemetrySidebarSpinner').outerHTML;

    function loadSites(url, name, sub) {
        sTitle.textContent = name;
        sSub.textContent   = sub;
        sBody.innerHTML    = spinner;
        sidebar.show();

        fetch(url)
            .then(r => { if (!r.ok) throw new Error(); return r.text(); })
            .then(html => { sBody.innerHTML = html; })
            .catch(() => {
                sBody.innerHTML = '<div class="text-center py-5 text-danger"><i class="bi bi-exclamation-triangle fs-3 d-block mb-2"></i><p class="fw-600 mb-0">Failed to load. Please retry.</p></div>';
            });
    }

    document.querySelectorAll('.btn-view-emp-sites').forEach(b =>
        b.addEventListener('click', function () {
            loadSites(this.dataset.url, this.dataset.name, 'Employee Telemetry');
        })
    );
    document.querySelectorAll('.btn-view-dept-sites').forEach(b =>
        b.addEventListener('click', function () {
            loadSites(this.dataset.url, this.dataset.name + ' Dept.', 'Department Telemetry');
        })
    );
    document.querySelectorAll('.btn-view-overall-sites').forEach(b =>
        b.addEventListener('click', function () {
            loadSites(this.dataset.url, this.dataset.name, 'Company-wide Telemetry');
        })
    );
    document.querySelectorAll('.btn-view-help').forEach(b =>
        b.addEventListener('click', function () {
            loadSites(this.dataset.url, this.dataset.name, 'Employee Help Requests');
        })
    );
});
</script>
@endsection