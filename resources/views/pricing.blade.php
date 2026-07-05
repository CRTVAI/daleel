@extends('layouts.public')

@section('title', 'Pricing | Daleel AI')

@section('styles')
<style>
/* ==================== PRICING CUSTOM STYLES ==================== */
:root {
    --pricing-primary: #6366F1;
    --pricing-primary-dark: #4F46E5;
    --pricing-bg-secondary: #F8FAFC;
}

.pricing-page {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    color: #0F172A;
}

/* --- Hero Section --- */
.pricing-hero {
    padding: 80px 0 60px;
    background: radial-gradient(circle at 10% 10%, rgba(99, 102, 241, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 90%, rgba(168, 85, 247, 0.05) 0%, transparent 40%);
}

.pricing-hero h1 {
    font-size: 48px;
    font-weight: 850;
    letter-spacing: -0.02em;
    margin-bottom: 20px;
}

.pricing-hero .lead {
    font-size: 18px;
    color: #475569;
    max-width: 700px;
    margin: 0 auto 40px;
}

/* --- Toggle Switch --- */
.billing-toggle-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    margin-bottom: 50px;
}

.toggle-label {
    font-size: 15px;
    font-weight: 600;
    color: #64748B;
}

.toggle-label.active {
    color: #0F172A;
}

.switch {
    position: relative;
    display: inline-block;
    width: 56px;
    height: 30px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #E2E8F0;
    transition: .4s;
    border-radius: 34px;
}

.slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

input:checked + .slider {
    background-color: var(--pricing-primary);
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.save-badge {
    background: #F0FDFA;
    color: #0D9488;
    font-size: 12px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 100px;
    border: 1px solid #CCFBF1;
}

/* --- Pricing Cards --- */
.pricing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 80px;
}

.pricing-card {
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 24px;
    padding: 40px;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
    position: relative;
}

.pricing-card:hover {
    border-color: #CBD5E1;
    box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.05);
    transform: translateY(-4px);
}

.pricing-card.featured {
    border: 2px solid var(--pricing-primary);
    box-shadow: 0 20px 50px -12px rgba(99, 102, 241, 0.15);
}

.popular-tag {
    position: absolute;
    top: -14px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--pricing-primary);
    color: white;
    font-size: 12px;
    font-weight: 800;
    padding: 6px 16px;
    border-radius: 100px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-header h3 {
    font-size: 20px;
    font-weight: 800;
    margin-bottom: 8px;
}

.card-header .description {
    font-size: 15px;
    color: #64748B;
    line-height: 1.5;
    margin-bottom: 24px;
}

.price-box {
    margin-bottom: 30px;
    display: flex;
    align-items: baseline;
    gap: 4px;
}

.currency {
    font-size: 24px;
    font-weight: 700;
    color: #0F172A;
}

.amount {
    font-size: 48px;
    font-weight: 850;
    color: #0F172A;
    letter-spacing: -0.02em;
}

.period {
    font-size: 15px;
    color: #64748B;
    font-weight: 500;
}

.pricing-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 24px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
    width: 100%;
    margin-bottom: 30px;
}

.btn-outline-pricing {
    border: 1.5px solid #E2E8F0;
    color: #0F172A;
}

.btn-outline-pricing:hover {
    background: #F8FAFC;
    border-color: #CBD5E1;
}

.btn-primary-pricing {
    background: var(--pricing-primary);
    color: white;
}

.btn-primary-pricing:hover {
    background: var(--pricing-primary-dark);
    box-shadow: 0 8px 20px -6px rgba(99, 102, 241, 0.4);
}

.features-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.features-list li {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    color: #475569;
    margin-bottom: 12px;
}

.features-list li svg {
    flex-shrink: 0;
    color: #10B981;
}

/* --- Comparison Table --- */
.comparison-section {
    padding: 80px 0;
    background: #F8FAFC;
}

.comparison-card {
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 10px 30px -10px rgba(0,0,0,0.03);
}

.comparison-table {
    width: 100%;
    border-collapse: collapse;
}

.comparison-table th, .comparison-table td {
    padding: 24px;
    text-align: left;
    border-bottom: 1px solid #F1F5F9;
}

.comparison-table th {
    background: #F8FAFC;
    font-size: 13px;
    font-weight: 700;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.feature-name {
    font-weight: 600;
    color: #0F172A;
    width: 35%;
}

.plan-val {
    font-size: 14px;
    color: #475569;
    text-align: center;
}

/* --- FAQ Section --- */
.faq-section {
    padding: 100px 0;
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 40px;
    max-width: 1000px;
    margin: 0 auto;
}

.faq-item h4 {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 12px;
}

.faq-item p {
    font-size: 15px;
    color: #64748B;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .pricing-hero h1 { font-size: 32px; }
    .faq-grid { grid-template-columns: 1fr; }
    .comparison-table { display: block; overflow-x: auto; }
}
</style>
@endsection

@section('content')
<div class="pricing-page">
    <!-- Hero -->
    <section class="pricing-hero">
        <div class="container text-center">
            <span class="eyebrow fade-in">Simple Pricing</span>
            <h1 class="fade-up">Invest in your growth, not your tools.</h1>
            <p class="lead fade-up" style="animation-delay: 0.1s;">
                Three plans built around real learning journeys. Start free, scale when you're ready — no hidden fees, no lock-ins.
            </p>
        </div>
    </section>

    <!-- Pricing Grid -->
    <section class="container">
        <div class="pricing-grid">
            <!-- Free Plan -->
            <div class="pricing-card">
                <div class="card-header">
                    <h3>Navigator <span style="font-size:13px;font-weight:600;color:#94a3b8;">Free</span></h3>
                    <p class="description">For individuals exploring AI-powered learning for the first time.</p>
                </div>
                <div class="price-box">
                    <span class="currency">$</span>
                    <span class="amount">0</span>
                    <span class="period">/month</span>
                </div>
                @auth
                    <a href="{{ route('dashboard') }}" class="pricing-btn btn-outline-pricing">Go to Dashboard</a>
                @else
                    <a href="{{ route('register') }}" class="pricing-btn btn-outline-pricing">Get Started Free</a>
                @endauth
                <ul class="features-list">
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <strong>2</strong> AI-generated Roadmaps</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <strong>10</strong> AI Mentor messages / day</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <strong>7-day</strong> activity history</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <strong>1</strong> browser connection via Extension</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Join teams (view-only)</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Dashboard activity tracking only</li>
                </ul>
            </div>

            <!-- Pro Plan -->
            <div class="pricing-card featured">
                <div class="popular-tag">Most Popular</div>
                <div class="card-header">
                    <h3>Career Accelerator <span style="font-size:13px;font-weight:600;color:#6366f1;">Pro</span></h3>
                    <p class="description">For serious learners who want unlimited AI guidance and deep analytics.</p>
                </div>
                <div class="price-box">
                    <span class="currency">$</span>
                    <span class="amount" data-monthly="19" data-yearly="15">19</span>
                    <span class="period">/month</span>
                </div>
                @auth
                    <a href="{{ route('upgrade') }}" class="pricing-btn btn-primary-pricing">Start Accelerating</a>
                @else
                    <a href="{{ route('register') }}" class="pricing-btn btn-primary-pricing">Start Accelerating</a>
                @endauth
                <ul class="features-list">
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <strong>Unlimited</strong> AI Roadmaps</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <strong>Unlimited</strong> AI Mentor (fair use)</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <strong>Full lifetime</strong> activity history</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <strong>Multi-device</strong> browser sync (Extension)</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Full video library access</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Create & manage <strong>Teams</strong></li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> View team members' tool activity</li>
                </ul>
            </div>

            <!-- Institutional Plan -->
            <div class="pricing-card">
                <div class="card-header">
                    <h3>Institutional <span style="font-size:13px;font-weight:600;color:#94a3b8;">Enterprise</span></h3>
                    <p class="description">For universities, companies & organizations managing teams at scale.</p>
                </div>
                <div class="price-box">
                    <span class="amount">Custom</span>
                </div>
                <a href="{{ url('contact') }}" class="pricing-btn btn-outline-pricing">Contact Sales</a>
                <ul class="features-list">
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Everything in Pro, for all seats</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Centralized <strong>Admin Dashboard</strong></li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <strong>Assign roadmaps</strong> to team members</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Org-wide <strong>Skill Map & analytics</strong></li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Private internal tool library</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Priority AI & dedicated support</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> SSO & enterprise-grade security</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Team Feature Spotlight -->
    <section style="padding: 80px 0; background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge rounded-pill mb-3 px-3 py-2 fw-700" style="background: rgba(99,102,241,0.2); color: #a5b4fc; font-size: 11px; letter-spacing: 1px;">TEAM MANAGEMENT</span>
                    <h2 class="fw-900 text-white mb-4" style="font-size: 36px; letter-spacing: -0.02em;">See exactly how your team learns — tool by tool, person by person.</h2>
                    <p class="mb-4" style="color:#94a3b8; font-size:16px; line-height:1.7;">The Daleel Teams dashboard gives managers and institutions a real-time view of every employee's or student's learning activity — which AI tools they're using, how long they spend, and where the skill gaps are. All powered by the Chrome Extension.</p>
                    <ul style="list-style:none; padding:0; color:#cbd5e1;">
                        <li class="mb-3"><span class="me-2" style="color:#6366f1;">✦</span> Create departments and assign members</li>
                        <li class="mb-3"><span class="me-2" style="color:#6366f1;">✦</span> View browsing tool activity per employee</li>
                        <li class="mb-3"><span class="me-2" style="color:#6366f1;">✦</span> Department-wide skill & tool usage reports</li>
                        <li class="mb-3"><span class="me-2" style="color:#6366f1;">✦</span> Push AI roadmaps to specific team members</li>
                        <li class="mb-3"><span class="me-2" style="color:#6366f1;">✦</span> Multi-browser connection tracking per user</li>
                    </ul>
                    <div class="mt-4 d-flex gap-3">
                        <span class="badge rounded-pill px-3 py-2 fw-600" style="background:rgba(99,102,241,0.15); color:#a5b4fc; border:1px solid rgba(99,102,241,0.3); font-size:12px;">Pro: Teams</span>
                        <span class="badge rounded-pill px-3 py-2 fw-600" style="background:rgba(16,185,129,0.15); color:#6ee7b7; border:1px solid rgba(16,185,129,0.3); font-size:12px;">Institutional: Unlimited Teams + Full Analytics</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="rounded-4 p-4" style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);">
                        <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                            <div class="rounded-3 d-flex align-items-center justify-content-center" style="width:40px;height:40px;background:linear-gradient(135deg,#4f46e5,#7c3aed);"><i class="bi bi-people-fill text-white"></i></div>
                            <div><div class="text-white fw-700">Engineering Department</div><div style="color:#64748b;font-size:12px;">14 members · 3 active now</div></div>
                            <span class="ms-auto badge rounded-pill px-3 py-1 fw-700" style="background:rgba(16,185,129,0.2);color:#6ee7b7;font-size:11px;">+12% this week</span>
                        </div>
                        @foreach([['Ahmed K.','ChatGPT, GitHub Copilot','2h 40m','#4f46e5'],['Sara M.','Midjourney, Figma AI','1h 15m','#7c3aed'],['Rami J.','Cursor AI, Claude','3h 05m','#06b6d4']] as $row)
                        <div class="d-flex align-items-center gap-3 mb-3 py-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-800 text-white" style="width:36px;height:36px;background:{{ $row[3] }};font-size:13px;">{{ substr($row[0],0,1) }}</div>
                            <div>
                                <div class="text-white fw-600" style="font-size:14px;">{{ $row[0] }}</div>
                                <div style="color:#64748b;font-size:11px;">{{ $row[1] }}</div>
                            </div>
                            <div class="ms-auto text-end">
                                <div style="color:#a5b4fc;font-size:13px;font-weight:700;">{{ $row[2] }}</div>
                                <div style="color:#475569;font-size:10px;">today</div>
                            </div>
                        </div>
                        @endforeach
                        <div class="mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,0.06);">
                            <div style="color:#475569;font-size:12px;" class="mb-2">Top tools this week</div>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['ChatGPT','GitHub Copilot','Claude','Midjourney','Cursor AI'] as $tool)
                                <span class="badge rounded-pill px-3 py-1" style="background:rgba(99,102,241,0.1);color:#818cf8;font-size:11px;">{{ $tool }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comparison Table -->
    <!-- <section class="comparison-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-800">Compare all features</h2>
                <p class="text-muted">A full breakdown of every capability across each plan.</p>
            </div>
            <div class="comparison-card">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th class="feature-name">Feature</th>
                            <th class="plan-val">Navigator (Free)</th>
                            <th class="plan-val">Career Accelerator (Pro)</th>
                            <th class="plan-val">Institutional</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td class="feature-name" colspan="4" style="background:#f8fafc;font-size:11px;font-weight:800;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;">AI & Learning</td></tr>
                        <tr>
                            <td class="feature-name">AI Roadmap Generation</td>
                            <td class="plan-val">2 total</td>
                            <td class="plan-val">Unlimited</td>
                            <td class="plan-val">Unlimited + Assignable</td>
                        </tr>
                        <tr>
                            <td class="feature-name">AI Mentor Messages</td>
                            <td class="plan-val">10 / day</td>
                            <td class="plan-val">Unlimited</td>
                            <td class="plan-val">Priority Unlimited</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Full Video Library</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">✓</td>
                            <td class="plan-val">✓</td>
                        </tr>

                        <tr><td class="feature-name" colspan="4" style="background:#f8fafc;font-size:11px;font-weight:800;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;">Chrome Extension & Tracking</td></tr>
                        <tr>
                            <td class="feature-name">Activity History</td>
                            <td class="plan-val">7 days</td>
                            <td class="plan-val">Full Lifetime</td>
                            <td class="plan-val">Full Lifetime</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Browser Connections</td>
                            <td class="plan-val">1 device</td>
                            <td class="plan-val">Multi-device</td>
                            <td class="plan-val">Multi-device (all seats)</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Passive Web Tracking</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">✓</td>
                            <td class="plan-val">✓</td>
                        </tr>

                        <tr><td class="feature-name" colspan="4" style="background:#f8fafc;font-size:11px;font-weight:800;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;">Team Management</td></tr>
                        <tr>
                            <td class="feature-name">Join a Team</td>
                            <td class="plan-val">View only</td>
                            <td class="plan-val">✓</td>
                            <td class="plan-val">✓</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Create & Manage Teams</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">Teams</td>
                            <td class="plan-val">Unlimited teams</td>
                        </tr>
                        <tr>
                            <td class="feature-name">View Member Tool Activity</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">✓</td>
                            <td class="plan-val">✓</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Department Segmentation</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">✓</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Assign Roadmaps to Members</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">✓</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Org-wide Skill Map</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">✓</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Department Analytics Dashboard</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">✓</td>
                        </tr>

                        <tr><td class="feature-name" colspan="4" style="background:#f8fafc;font-size:11px;font-weight:800;color:#94a3b8;letter-spacing:1px;text-transform:uppercase;">Enterprise</td></tr>
                        <tr>
                            <td class="feature-name">Private Internal Tool Library</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">✓</td>
                        </tr>
                        <tr>
                            <td class="feature-name">SSO & Enterprise Security</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">✓</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Dedicated Support Manager</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">✓</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section> -->

    <!-- FAQ -->
    <section class="faq-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-800">Frequently Asked Questions</h2>
            </div>
            <div class="faq-grid">
                <div class="faq-item">
                    <h4>What happens when I hit the Free limits?</h4>
                    <p>When you use your 2 roadmaps or reach 10 AI Mentor messages in a day, you'll be prompted to upgrade. Your existing data and progress are never lost.</p>
                </div>
                <div class="faq-item">
                    <h4>What does the Chrome Extension track?</h4>
                    <p>The extension tracks your learning activity across your browser — time on lessons, tools explored, and YouTube study sessions. Pro users also get passive web tracking on external documentation and learning sites.</p>
                </div>
                <div class="faq-item">
                    <h4>How does Team Management work?</h4>
                    <p>Pro users can create Teams and invite members. You'll see each member's tool activity and browsing patterns. Institutional plans add departments, role-segmented analytics, and the ability to push specific roadmaps to individual employees.</p>
                </div>
                <div class="faq-item">
                    <h4>Is the Institutional plan suitable for universities?</h4>
                    <p>Absolutely. You can bulk-onboard students, organize them into departments, assign curated learning paths, track completion, and view which AI tools each student is engaging with most.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('billingToggle');
    const amounts = document.querySelectorAll('.amount[data-monthly]');
    const monthlyLabel = document.getElementById('monthlyLabel');
    const yearlyLabel = document.getElementById('yearlyLabel');

    function updatePricing() {
        const isYearly = toggle.checked;
        
        amounts.forEach(amount => {
            amount.textContent = isYearly ? amount.getAttribute('data-yearly') : amount.getAttribute('data-monthly');
        });

        if (isYearly) {
            yearlyLabel.classList.add('active');
            monthlyLabel.classList.remove('active');
        } else {
            monthlyLabel.classList.add('active');
            yearlyLabel.classList.remove('active');
        }
    }

    toggle.addEventListener('change', updatePricing);
    updatePricing(); // Initial state
});
</script>
@endsection
