@extends('layouts.public')

@section('title', 'Enterprise AI Training | Dallel AI')

@section('content')
<div class="enterprise-page">
    <!-- Hero Section -->
    <section class="ent-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="eyebrow fade-in">Enterprise Solution</div>
                    <h1 class="display-3 fw-900 mb-4 fade-up">Scale AI Excellence Across Your <span class="text-gradient">Entire Workforce</span></h1>
                    <p class="lead mb-5 fade-up" style="animation-delay: 0.1s;">Elevate team productivity with specialized AI training matched to your company's unique workflows. Secure, scalable, and built for modern enterprises.</p>
                    <div class="hero-actions d-flex gap-3 fade-up" style="animation-delay: 0.2s;">
                        <a href="{{ url('contact') }}" class="btn primary btn-lg px-4 py-3 h-auto">Book Executive Demo</a>
                        <a href="#features" class="btn secondary btn-lg px-4 py-3 h-auto">Explore Features</a>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="ent-hero-visual fade-in">
                        <div class="visual-card main">
                            <div class="card-head mb-2">
                                <div class="pulse-dot"></div>
                                <span>Live Team Intelligence</span>
                            </div>
                            <p class="small text-muted mb-4" style="font-size: 0.75rem;">Monitoring real-time AI tool adoption and workflow optimization across all active departments.</p>
                            <div class="card-chart">
                                <div class="bar-row">
                                    <div class="bar" style="height: 60%;"></div>
                                    <div class="bar" style="height: 85%;"></div>
                                    <div class="bar" style="height: 45%;"></div>
                                    <div class="bar" style="height: 95%;"></div>
                                    <div class="bar" style="height: 70%;"></div>
                                </div>
                            </div>
                            <div class="card-stats">
                                <div><strong>1.2k</strong><span>Active Users</span></div>
                                <div><strong>+24%</strong><span>Efficiency</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Logo Strip -->
    <section class="py-5 bg-white border-bottom">
        <div class="container">
            <p class="text-center small fw-800 text-muted text-uppercase letter-spacing-1 mb-4">Trusted by innovative leadership teams</p>
            <div class="logo-strip justify-content-center">
                <span class="fs-4 fw-bold opacity-50 mx-4">TECHCORP</span>
                <span class="fs-4 fw-bold opacity-50 mx-4">FINTECH.OS</span>
                <span class="fs-4 fw-bold opacity-50 mx-4">GLOBAL_LOGISTICS</span>
                <span class="fs-4 fw-bold opacity-50 mx-4">NEXUS.AI</span>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section">
        <div class="container">
            <div class="section-header center mb-5">
                <h2 class="fw-900">Built for the Modern Organization</h2>
                <p class="text-muted fs-5">Move past generic training. Dallel Enterprise adapts to your company's processes, data, and culture.</p>
            </div>
            
            <div class="grid three">
                <div class="card p-4">
                    <div class="icon-box text-primary"><i class="bi bi-bar-chart-steps"></i></div>
                    <h4 class="fw-800">Skill Gap Analytics</h4>
                    <p class="text-muted small">Identify exactly where your team is struggling and deploy targeted AI lessons to bridge the gap in real-time.</p>
                </div>
                <div class="card p-4">
                    <div class="icon-box text-accent"><i class="bi bi-lock"></i></div>
                    <h4 class="fw-800">Workspace Security</h4>
                    <p class="text-muted small">Enterprise-grade SSO (Okta, Azure), SOC2 compliance, and data residency controls to keep your knowledge safe.</p>
                </div>
                <div class="card p-4">
                    <div class="icon-box text-success"><i class="bi bi-people"></i></div>
                    <h4 class="fw-800">Dedicated Success</h4>
                    <p class="text-muted small">A dedicated AI consultant to help map your workflows to our training library and ensure measurable ROI.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section alt py-5">
        <div class="container">
            <div class="cta-box rounded-5 p-5 text-center text-white" style="background: linear-gradient(135deg, #0f172a, #1e293b); border: 1px solid rgba(255,255,255,0.1);">
                <div class="eyebrow bg-primary text-white mb-4">Ready to Transform?</div>
                <h2 class="display-5 fw-900 mb-3 text-white">Drive 10x Team Performance Today</h2>
                <p class="opacity-75 mb-5 mx-auto" style="max-width: 600px;">Join the organizations using Dallel AI to turn every employee into a high-performance AI power user.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ url('contact') }}" class="btn primary btn-lg px-5 border-0">Contact Sales</a>
                    <a href="{{ url('pricing') }}" class="btn btn-outline-light btn-lg px-5">View Pricing</a>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.enterprise-page {
    overflow-x: hidden;
}

.ent-hero {
    padding: 100px 0;
    background: radial-gradient(circle at top right, rgba(37, 99, 235, 0.1), transparent 600px),
                radial-gradient(circle at bottom left, rgba(124, 58, 237, 0.08), transparent 600px);
}

.text-gradient {
    background: linear-gradient(135deg, var(--primary), var(--accent));
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.fw-900 { font-weight: 900; }
.fw-800 { font-weight: 800; }

.ent-hero-visual {
    position: relative;
    padding: 20px;
}

.visual-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 30px 60px -12px rgba(0,0,0,0.15);
    padding: 24px;
    border: 1px solid var(--line);
}

.visual-card.main {
    width: 80%;
    margin-left: 10%;
    z-index: 2;
    position: relative;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

.card-head {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 800;
    font-size: 0.85rem;
    margin-bottom: 20px;
    color: var(--muted);
}

.card-chart {
    height: 120px;
    display: flex;
    align-items: flex-end;
    margin-bottom: 20px;
}

.bar-row {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 12px;
}

.bar {
    flex: 1;
    background: linear-gradient(to top, var(--primary), var(--accent));
    border-radius: 6px;
    transition: height 1s ease;
}

.card-stats {
    display: flex;
    justify-content: space-between;
    border-top: 1px solid var(--line);
    padding-top: 15px;
}

.card-stats div { display: flex; flex-direction: column; }
.card-stats strong { font-size: 1.1rem; font-weight: 800; }
.card-stats span { font-size: 0.7rem; color: var(--muted); text-transform: uppercase; font-weight: 700; }

.fade-in { animation: fadeIn 0.8s ease forwards; }
.fade-up { opacity: 0; animation: fadeUp 0.8s ease forwards; }

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

/* Section Refinements */
.section { padding: 100px 0; }
.section-header center { max-width: 600px; margin-left: auto; margin-right: auto; }

.card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.card:hover {
    box-shadow: 0 20px 40px rgba(37, 99, 235, 0.1);
}
</style>
@endsection
