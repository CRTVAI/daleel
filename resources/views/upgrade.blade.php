@extends('layouts.user')

@section('title', 'Upgrade to Pro — Daleel AI')

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --ink: #0b1220;
        --ink-soft: #55607a;
        --paper: #ffffff;
        --mist: #f5f7fb;
        --page-bg: #eef1f7;
        --line: #e3e7f0;
        --line-strong: #cbd3e3;
        --brand: #2a2f8f;
        --brand-2: #4147c9;
        --gold: #b8863b;
        --gold-soft: #f6ecd9;
        --success: #0e9f6e;
        --success-soft: #eafbf3;
        --danger: #dc2626;
        --shadow-card: 0 24px 60px -20px rgba(11, 18, 32, 0.16), 0 2px 8px rgba(11, 18, 32, 0.04);
        --shadow-soft: 0 10px 24px -12px rgba(11, 18, 32, 0.12);
        --font-display: 'Space Grotesk', 'Inter', sans-serif;
        --font-body: 'Inter', sans-serif;
        --font-mono: 'IBM Plex Mono', monospace;
    }

    body { background: var(--page-bg); }

    * { -webkit-font-smoothing: antialiased; }

    .checkout-shell {
        max-width: 1160px;
        margin: 2.5rem auto 4.5rem;
        padding: 0 1rem;
        font-family: var(--font-body);
        color: var(--ink);
    }

    .checkout-header {
        margin-bottom: 2.2rem;
        text-align: center;
    }

    .style-label {
        font-size: 0.72rem;
        font-family: var(--font-mono);
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--brand-2);
        background: rgba(65, 71, 201, 0.06);
        border: 1px solid rgba(65, 71, 201, 0.12);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        margin-bottom: 0.8rem;
    }

    .checkout-header h2 {
        font-family: var(--font-display);
        font-size: 2rem;
        font-weight: 700;
        color: var(--ink);
        letter-spacing: -0.02em;
        margin: 0 0 0.5rem;
    }

    .checkout-header p {
        color: var(--ink-soft);
        font-size: 0.92rem;
        max-width: 580px;
        margin: 0 auto;
        line-height: 1.65;
    }

    .checkout-top {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        color: var(--ink-soft);
        font-size: 0.82rem;
        font-weight: 600;
    }

    .checkout-top i { color: var(--success); }

    .checkout-card {
        display: grid;
        grid-template-columns: 1fr 1.35fr;
        gap: 1.5rem;
        align-items: start;
    }

    /* ---------- TICKET (order summary) ---------- */

    .ticket-wrap { position: sticky; top: 1.75rem; }

    .ticket {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 20px;
        box-shadow: var(--shadow-card);
        overflow: hidden;
    }

    .ticket-top {
        padding: 2.1rem 2.1rem 1.6rem;
    }

    .pro-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-family: var(--font-mono);
        font-size: 0.68rem;
        font-weight: 600;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        color: var(--gold);
        background: var(--gold-soft);
        border: 1px solid #ecdcbb;
        border-radius: 999px;
        padding: 0.32rem 0.7rem 0.32rem 0.55rem;
        margin-bottom: 1.1rem;
    }

    .pro-chip i { font-size: 0.75rem; }

    .ticket-title {
        font-family: var(--font-display);
        font-size: 1.62rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin: 0;
    }

    .ticket-subtitle {
        margin-top: 0.6rem;
        color: var(--ink-soft);
        font-size: 0.92rem;
        line-height: 1.6;
    }

    .ticket-subtitle strong { color: var(--ink); }

    .price-row {
        display: flex;
        align-items: baseline;
        gap: 0.55rem;
        margin-top: 1.5rem;
    }

    .price-main {
        font-family: var(--font-mono);
        font-size: 2.5rem;
        font-weight: 600;
        letter-spacing: -0.02em;
    }

    .price-note {
        color: var(--ink-soft);
        font-weight: 600;
        font-size: 0.86rem;
    }

    /* perforated seam */
    .ticket-seam {
        position: relative;
        height: 1px;
        margin: 0 0;
        background: repeating-linear-gradient(90deg, var(--line-strong) 0 8px, transparent 8px 16px);
    }

    .ticket-seam::before,
    .ticket-seam::after {
        content: "";
        position: absolute;
        top: 50%;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--page-bg);
        transform: translateY(-50%);
    }

    .ticket-seam::before { left: -12px; }
    .ticket-seam::after { right: -12px; }

    .ticket-body {
        padding: 1.5rem 2.1rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.6rem 0;
        font-size: 0.88rem;
        border-bottom: 1px dashed var(--line);
    }

    .summary-row:last-child { border-bottom: 0; padding-bottom: 0; }
    .summary-row:first-child { padding-top: 0; }

    .summary-key { color: var(--ink-soft); font-weight: 500; }

    .summary-val {
        font-weight: 700;
        text-align: right;
        font-family: var(--font-mono);
        font-size: 0.86rem;
    }

    .summary-val.discount { color: var(--success); }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-top: 0.9rem;
        padding-top: 0.9rem;
        border-top: 1px solid var(--line);
    }

    .summary-total .label { font-weight: 700; font-size: 0.9rem; }
    .summary-total .value {
        font-family: var(--font-mono);
        font-weight: 700;
        font-size: 1.15rem;
        color: var(--success);
    }

    .ticket-ref {
        padding: 0 2.1rem 1.9rem;
    }

    .barcode {
        height: 30px;
        border-radius: 3px;
        background: repeating-linear-gradient(
            90deg,
            var(--ink) 0px, var(--ink) 2px,
            transparent 2px, transparent 4px,
            var(--ink) 4px, var(--ink) 5px,
            transparent 5px, transparent 9px,
            var(--ink) 9px, var(--ink) 12px,
            transparent 12px, transparent 14px
        );
        opacity: 0.85;
    }

    .barcode-caption {
        margin-top: 0.5rem;
        font-family: var(--font-mono);
        font-size: 0.7rem;
        letter-spacing: 0.06em;
        color: var(--ink-soft);
        display: flex;
        justify-content: space-between;
    }

    /* benefits + trust live below the ticket, outside the card */
    .benefits-card {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 20px;
        box-shadow: var(--shadow-card);
        padding: 1.6rem;
        margin-top: 1.25rem;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    }

    .benefits-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 28px 64px -22px rgba(11, 18, 32, 0.18);
    }

    .benefits-header {
        font-family: var(--font-display);
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--ink-soft);
        margin-bottom: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .benefits-header i {
        color: var(--brand-2);
        font-size: 0.92rem;
    }

    .benefit-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .benefit-item {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        color: #334155;
        font-size: 0.88rem;
        line-height: 1.45;
    }

    .benefit-item strong {
        color: var(--ink);
        font-weight: 700;
    }

    .benefit-item .tick {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: var(--success-soft);
        color: var(--success);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 0.12rem;
        font-size: 0.68rem;
    }

    .benefits-divider {
        height: 1px;
        background: var(--line);
        margin: 1.25rem 0;
    }

    .trust-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
    }

    .trust-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: 0.35rem;
        padding: 0.6rem 0.25rem;
        border-radius: 12px;
        background: var(--mist);
        border: 1px solid transparent;
        transition: all 0.2s ease;
    }

    .trust-item:hover {
        background: var(--paper);
        border-color: var(--line-strong);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(11, 18, 32, 0.04);
    }

    .trust-item i {
        font-size: 1.15rem;
        color: var(--brand);
    }

    .trust-item span {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.1;
    }

    /* ---------- FORM PANEL ---------- */

    .form-panel {
        background: var(--paper);
        border: 1px solid var(--line);
        border-radius: 20px;
        box-shadow: var(--shadow-card);
        padding: 2.2rem 2.3rem 2.4rem;
    }

    /* stepper */
    .stepper { margin-bottom: 2.1rem; }

    .stepper-track {
        position: relative;
        height: 2px;
        background: var(--line);
        border-radius: 2px;
        margin: 17px 17px 0;
    }

    .stepper-progress {
        position: absolute;
        top: 0; left: 0;
        height: 2px;
        width: 0%;
        background: linear-gradient(90deg, var(--brand), var(--brand-2));
        border-radius: 2px;
        transition: width 0.35s ease;
    }

    .stepper-nodes {
        display: flex;
        justify-content: space-between;
        margin-top: -17px;
    }

    .stepper-node {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        width: 33.33%;
    }

    .node-dot {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--paper);
        border: 2px solid var(--line-strong);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: var(--font-mono);
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--ink-soft);
        transition: all 0.25s ease;
    }

    .node-check { display: none; font-size: 0.95rem; }

    .stepper-node.active .node-dot {
        border-color: var(--brand);
        color: var(--brand);
        box-shadow: 0 0 0 4px rgba(42, 47, 143, 0.1);
    }

    .stepper-node.completed .node-dot {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
    }

    .stepper-node.completed .node-num { display: none; }
    .stepper-node.completed .node-check { display: inline; }

    .node-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--ink-soft);
    }

    .stepper-node.active .node-label { color: var(--brand); }
    .stepper-node.completed .node-label { color: var(--ink); }

    .pane { display: none; }
    .pane.active { display: block; animation: paneIn 0.28s ease; }

    @keyframes paneIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .pane-title {
        font-family: var(--font-display);
        font-size: 1.3rem;
        font-weight: 700;
        letter-spacing: -0.01em;
        margin-bottom: 0.35rem;
    }

    .pane-subtitle {
        color: var(--ink-soft);
        font-size: 0.89rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .review-card {
        border: 1px solid var(--line);
        border-radius: 14px;
        background: var(--mist);
        padding: 1.15rem 1.3rem;
        margin-bottom: 1.4rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .review-card strong { display: block; font-size: 0.96rem; margin-bottom: 0.2rem; }
    .review-card span { color: var(--ink-soft); font-size: 0.84rem; }
    .review-card .review-badge {
        font-family: var(--font-mono);
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--success);
        background: var(--success-soft);
        padding: 0.3rem 0.6rem;
        border-radius: 8px;
        white-space: nowrap;
    }

    .field { margin-bottom: 1.1rem; }

    .field-label {
        display: block;
        margin-bottom: 0.42rem;
        font-size: 0.74rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
    }

    .field-label .req { color: var(--danger); }

    .field-input {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 1.5px solid var(--line-strong);
        border-radius: 12px;
        background: var(--paper);
        color: var(--ink);
        font-size: 0.94rem;
        font-weight: 600;
        font-family: var(--font-body);
        outline: none;
        transition: border-color 0.18s ease, box-shadow 0.18s ease;
    }

    .field-input:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3px rgba(42, 47, 143, 0.12);
    }

    .field-input[readonly] {
        background: var(--mist);
        color: var(--ink-soft);
        cursor: not-allowed;
    }

    .field-input.invalid { border-color: var(--danger); }
    .field-input.invalid:focus { box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12); }

    .phone-error {
        display: none;
        margin-top: 0.45rem;
        color: var(--danger);
        font-size: 0.78rem;
        font-weight: 700;
        align-items: center;
        gap: 0.35rem;
    }

    .phone-error.show { display: flex; }

    @keyframes shake {
        10%, 90% { transform: translateX(-1px); }
        20%, 80% { transform: translateX(2px); }
        30%, 50%, 70% { transform: translateX(-4px); }
        40%, 60% { transform: translateX(4px); }
    }

    .shake { animation: shake 0.5s; }

    .action-row {
        display: grid;
        grid-template-columns: 1fr 1.3fr;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn-checkout {
        width: 100%;
        border: 0;
        border-radius: 12px;
        padding: 0.92rem 1rem;
        font-size: 0.92rem;
        font-weight: 700;
        font-family: var(--font-body);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    }

    .btn-primary-checkout {
        background: linear-gradient(180deg, var(--brand-2), var(--brand));
        color: #fff;
        box-shadow: var(--shadow-soft);
    }

    .btn-primary-checkout:hover { transform: translateY(-1px); }
    .btn-primary-checkout:disabled { opacity: 0.75; cursor: default; transform: none; }

    .btn-secondary-checkout {
        background: var(--paper);
        color: #334155;
        border: 1.5px solid var(--line-strong);
    }

    .btn-secondary-checkout:hover { background: var(--mist); }

    /* confirm pane */
    .confirm-hero {
        border: 1px solid var(--line);
        border-radius: 14px;
        background: linear-gradient(135deg, #f8f9ff 0%, var(--mist) 100%);
        padding: 1.3rem 1.4rem;
        margin-bottom: 1.3rem;
    }

    .confirm-hero strong {
        display: block;
        font-family: var(--font-display);
        font-size: 1.05rem;
        margin-bottom: 0.3rem;
    }

    .confirm-hero span { color: var(--ink-soft); font-size: 0.86rem; }

    .confirm-list { border: 1px solid var(--line); border-radius: 14px; overflow: hidden; }

    .confirm-list .summary-row {
        padding: 0.85rem 1.2rem;
        border-bottom: 1px solid var(--line);
        font-size: 0.87rem;
    }

    .confirm-list .summary-row:last-child { border-bottom: 0; }

    .fine-print {
        margin-top: 1.2rem;
        font-size: 0.78rem;
        color: var(--ink-soft);
        line-height: 1.6;
        display: flex;
        gap: 0.5rem;
    }

    .fine-print i { flex-shrink: 0; margin-top: 0.15rem; }

    @media (max-width: 992px) {
        .checkout-card { grid-template-columns: 1fr; }
        .ticket-wrap { position: static; }
    }

    @media (max-width: 576px) {
        .checkout-shell { margin: 1.25rem auto 2.5rem; padding: 0 0.65rem; }
        .ticket-top, .ticket-body, .ticket-ref { padding-left: 1.4rem; padding-right: 1.4rem; }
        .form-panel { padding: 1.5rem 1.4rem 1.8rem; }
        .action-row { grid-template-columns: 1fr; }
        .node-label { font-size: 0.64rem; }
        .review-card { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
        .benefits-card { padding: 1.25rem; }
        .trust-grid { grid-template-columns: 1fr; gap: 0.4rem; }
        .trust-item { flex-direction: row; justify-content: flex-start; padding: 0.6rem 1rem; gap: 0.6rem; }
    }
</style>
@endsection

@section('content')
<div class="checkout-shell">

    <div class="checkout-header">
        <span class="style-label">
            <i class="bi bi-stars"></i> Exclusive Pro Offer
        </span>
        <h2>Claim Your 3-Month Pro Trial</h2>
        <p>Unlock absolute learning freedom. Generate unlimited AI roadmaps, access complete logs, and activate your personal student metrics tool dashboard instantly.</p>
    </div>

    <div class="checkout-top">
        <i class="bi bi-shield-lock-fill"></i> Encrypted, secure activation
    </div>

    <div class="checkout-card">

        <div class="ticket-wrap">
            <div class="ticket">
                <div class="ticket-top">
                    <span class="pro-chip"><i class="bi bi-stars"></i> Pro trial</span>

                    <h1 class="ticket-title">Complete your Pro upgrade</h1>
                    <p class="ticket-subtitle">
                        You're currently on the <strong>Free Plan</strong>. Activate 3 months of Pro — nothing due today.
                    </p>

                    <div class="price-row">
                        <div class="price-main">$0.00</div>
                        <div class="price-note">USD · first 3 months</div>
                    </div>
                </div>

                <div class="ticket-seam"></div>

                <div class="ticket-body">
                    <div class="summary-row">
                        <span class="summary-key">Plan</span>
                        <span class="summary-val">3-Month Pro Trial</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-key">Discount</span>
                        <span class="summary-val discount">−100%</span>
                    </div>
                    <div class="summary-total">
                        <span class="label">Total due today</span>
                        <span class="value">$0.00</span>
                    </div>
                </div>

                <div class="ticket-ref">
                    <div class="barcode"></div>
                    <div class="barcode-caption">
                        <span>TRIAL · VOUCHER</span>
                        <span>NO CARD ON FILE</span>
                    </div>
                </div>
            </div>

            <div class="benefits-card">
                <div class="benefits-header">
                    <i class="bi bi-patch-check-fill"></i>
                    <span>Included in Pro Trial</span>
                </div>

                <div class="benefit-list">
                    <div class="benefit-item">
                        <span class="tick"><i class="bi bi-check-lg"></i></span>
                        <span><strong>Unlimited</strong> AI roadmap generation</span>
                    </div>
                    <div class="benefit-item">
                        <span class="tick"><i class="bi bi-check-lg"></i></span>
                        <span><strong>Full</strong> browser history — no 7-day limit</span>
                    </div>
                    <div class="benefit-item">
                        <span class="tick"><i class="bi bi-check-lg"></i></span>
                        <span><strong>Connect</strong> unlimited extension devices</span>
                    </div>
                    <div class="benefit-item">
                        <span class="tick"><i class="bi bi-check-lg"></i></span>
                        <span><strong>Full</strong> "My Team" dashboard access</span>
                    </div>
                    <div class="benefit-item">
                        <span class="tick"><i class="bi bi-check-lg"></i></span>
                        <span><strong>Real-time</strong> AI video recommendations</span>
                    </div>
                </div>

                <div class="benefits-divider"></div>

                <div class="trust-grid">
                    <div class="trust-item">
                        <i class="bi bi-lock-fill"></i>
                        <span>No card required</span>
                    </div>
                    <div class="trust-item">
                        <i class="bi bi-shield-check"></i>
                        <span>Instant activation</span>
                    </div>
                    <div class="trust-item">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        <span>Cancel anytime</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-panel">

            <div class="stepper">
                <div class="stepper-track"><div class="stepper-progress" id="stepperProgress"></div></div>
                <div class="stepper-nodes">
                    <div class="stepper-node active" id="step-nav-1">
                        <span class="node-dot"><span class="node-num">1</span><i class="bi bi-check-lg node-check"></i></span>
                        <span class="node-label">Review</span>
                    </div>
                    <div class="stepper-node" id="step-nav-2">
                        <span class="node-dot"><span class="node-num">2</span><i class="bi bi-check-lg node-check"></i></span>
                        <span class="node-label">Verify</span>
                    </div>
                    <div class="stepper-node" id="step-nav-3">
                        <span class="node-dot"><span class="node-num">3</span><i class="bi bi-check-lg node-check"></i></span>
                        <span class="node-label">Confirm</span>
                    </div>
                </div>
            </div>

            <div class="pane active" id="step-pane-1">
                <div class="pane-title">Review your upgrade</div>
                <div class="pane-subtitle">Check the trial details below, then continue to verify your account.</div>

                <div class="review-card">
                    <div>
                        <strong>3-Month Pro Trial Voucher</strong>
                        <span>Free activation · nothing due today</span>
                    </div>
                    <span class="review-badge">$0.00</span>
                </div>

                <button type="button" class="btn-checkout btn-primary-checkout" onclick="changeStep(2)">
                    Continue <i class="bi bi-arrow-right"></i>
                </button>
            </div>

            <div class="pane" id="step-pane-2">
                <div class="pane-title">Verify your details</div>
                <div class="pane-subtitle">Confirm your account and add a phone number so we can reach you about your trial.</div>

                <div class="field">
                    <label class="field-label">Name</label>
                    <input type="text" class="field-input" value="{{ $user->name }}" readonly>
                </div>

                <div class="field">
                    <label class="field-label">Email</label>
                    <input type="email" class="field-input" value="{{ $user->email }}" readonly>
                </div>

                <div class="field">
                    <label class="field-label">Phone <span class="req">*</span></label>
                    <input type="tel" class="field-input" id="phone-input" placeholder="e.g. +880 1XXXXXXXXX" value="{{ $user->phone ?? '' }}" maxlength="20" required>
                    <div id="phone-error" class="phone-error"><i class="bi bi-exclamation-circle-fill"></i> Enter a valid phone number to continue.</div>
                </div>

                <div class="action-row">
                    <button type="button" class="btn-checkout btn-secondary-checkout" onclick="changeStep(1)">
                        <i class="bi bi-arrow-left"></i> Back
                    </button>
                    <button type="button" class="btn-checkout btn-primary-checkout" onclick="changeStep(3)">
                        Continue <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>

            <div class="pane" id="step-pane-3">
                <div class="pane-title">Confirm and activate</div>
                <div class="pane-subtitle">This is your final confirmation before the trial is activated.</div>

                <div class="confirm-hero">
                    <strong>3-Month Pro Trial — $0.00</strong>
                    <span>Runs through {{ now()->addMonths(3)->format('F j, Y') }}</span>
                </div>

                <div class="confirm-list">
                    <div class="summary-row">
                        <span class="summary-key">Member</span>
                        <span class="summary-val">{{ $user->name }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-key">Phone</span>
                        <span class="summary-val" id="summary-phone-text">—</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-key">Total due today</span>
                        <span class="summary-val discount">$0.00</span>
                    </div>
                </div>

                <div class="fine-print">
                    <i class="bi bi-info-circle"></i>
                    <span>No payment method is stored. You can cancel or downgrade from your account settings at any time before the trial ends.</span>
                </div>

                <form method="POST" action="{{ route('upgrade.activate') }}" id="upgradeSubmitForm">
                    @csrf
                    <input type="hidden" name="phone" id="submit-phone-hidden">

                    <div class="action-row">
                        <button type="button" class="btn-checkout btn-secondary-checkout" onclick="changeStep(2)">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>
                        <button type="submit" class="btn-checkout btn-primary-checkout" id="activateBtnSubmit">
                            <i class="bi bi-patch-check-fill"></i> Activate Pro trial
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
    function setStepper(step) {
        document.getElementById('stepperProgress').style.width = ((step - 1) / 2 * 100) + '%';

        for (let i = 1; i <= 3; i++) {
            const node = document.getElementById('step-nav-' + i);
            node.classList.remove('active', 'completed');
            if (i < step) node.classList.add('completed');
            if (i === step) node.classList.add('active');
        }
    }

    function changeStep(step) {
        if (step === 3) {
            const input = document.getElementById('phone-input');
            const phone = input.value.trim();
            const err = document.getElementById('phone-error');

            if (phone.length < 5) {
                err.classList.add('show');
                input.classList.add('invalid', 'shake');
                input.focus();
                setTimeout(() => input.classList.remove('shake'), 500);
                return;
            }

            err.classList.remove('show');
            input.classList.remove('invalid');
            document.getElementById('summary-phone-text').textContent = phone;
            document.getElementById('submit-phone-hidden').value = phone;
        }

        document.querySelectorAll('.pane').forEach(el => el.classList.remove('active'));
        document.getElementById('step-pane-' + step).classList.add('active');
        setStepper(step);
    }

    document.getElementById('phone-input').addEventListener('input', function () {
        this.classList.remove('invalid');
        document.getElementById('phone-error').classList.remove('show');
    });

    document.getElementById('upgradeSubmitForm').addEventListener('submit', function () {
        const btn = document.getElementById('activateBtnSubmit');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Activating...';
    });
</script>
@endsection