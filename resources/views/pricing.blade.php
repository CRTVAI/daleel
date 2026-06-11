@extends('layouts.public')

@section('title', 'Pricing | Dallel AI')

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
            <h1 class="fade-up">Training that pays for itself.</h1>
            <p class="lead fade-up" style="animation-delay: 0.1s;">
                Choose the plan that fits your learning style. Upgrade or downgrade at any time as your team grows and AI needs evolve.
            </p>

            <!-- <div class="billing-toggle-container fade-up" style="animation-delay: 0.2s;">
                <span class="toggle-label" id="monthlyLabel">Monthly</span>
                <label class="switch">
                    <input type="checkbox" id="billingToggle">
                    <span class="slider"></span>
                </label>
                <span class="toggle-label" id="yearlyLabel">Yearly</span>
                <span class="save-badge">Save up to 20%</span>
            </div> -->
        </div>
    </section>

    <!-- Pricing Grid -->
    <section class="container">
        <div class="pricing-grid">
            <!-- Free Plan -->
            <div class="pricing-card">
                <div class="card-header">
                    <h3>Free</h3>
                    <p class="description">For individuals starting their AI journey.</p>
                </div>
                <div class="price-box">
                    <span class="currency">$</span>
                    <span class="amount">0</span>
                    <span class="period">/month</span>
                </div>
                <a href="{{ route('register') }}" class="pricing-btn btn-outline-pricing">Get Started</a>
                <ul class="features-list">
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Starter learning path</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> 5 AI mentor questions</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Save up to 10 lessons</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Community resources</li>
                </ul>
            </div>

            <!-- Pro Plan -->
            <div class="pricing-card">
                <div class="card-header">
                    <h3>Pro Learner</h3>
                    <p class="description">For power users applying AI daily.</p>
                </div>
                <div class="price-box">
                    <span class="currency">$</span>
                    <span class="amount" data-monthly="19" data-yearly="15">19</span>
                    <span class="period">/month</span>
                </div>
                <a href="{{ route('register') }}" class="pricing-btn btn-outline-pricing">Start Free Trial</a>
                <ul class="features-list">
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <strong>Full</strong> video library</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Unlimited saved lessons</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Priority AI mentor access</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Completion certificates</li>
                </ul>
            </div>

            <!-- Team Plan -->
            <div class="pricing-card featured">
                <div class="popular-tag">Most Popular</div>
                <div class="card-header">
                    <h3>Team</h3>
                    <p class="description">For departments and growing teams.</p>
                </div>
                <div class="price-box">
                    <span class="currency">$</span>
                    <span class="amount" data-monthly="49" data-yearly="39">49</span>
                    <span class="period">/user/mo</span>
                </div>
                <a href="{{ route('register') }}" class="pricing-btn btn-primary-pricing">Start Team Trial</a>
                <ul class="features-list">
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <strong>Admin Dashboard</strong></li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Progress tracking</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Department-wide paths</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> AI Readiness scoring</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Advanced team analytics</li>
                </ul>
            </div>

            <!-- Enterprise Plan -->
            <div class="pricing-card">
                <div class="card-header">
                    <h3>Enterprise</h3>
                    <p class="description">For organization-wide AI scaling.</p>
                </div>
                <div class="price-box">
                    <span class="amount">Custom</span>
                </div>
                <a href="{{ url('contact') }}" class="pricing-btn btn-outline-pricing">Contact Sales</a>
                <ul class="features-list">
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> <strong>Custom</strong> course content</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> ROI & impact reporting</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> SSO & Enterprise security</li>
                    <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Dedicated Success Manager</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Comparison Table -->
    <section class="comparison-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-800">Compare features</h2>
                <p class="text-muted">A deep dive into everything you get with Dallel AI.</p>
            </div>
            
            <div class="comparison-card">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th class="feature-name">Features</th>
                            <th class="plan-val">Free</th>
                            <th class="plan-val">Pro</th>
                            <th class="plan-val">Team</th>
                            <th class="plan-val">Enterprise</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="feature-name">Video Lesson Library</td>
                            <td class="plan-val">Starter</td>
                            <td class="plan-val">Full</td>
                            <td class="plan-val">Full</td>
                            <td class="plan-val">Custom+</td>
                        </tr>
                        <tr>
                            <td class="feature-name">AI Mentor Access</td>
                            <td class="plan-val">5 / month</td>
                            <td class="plan-val">Unlimited</td>
                            <td class="plan-val">Unlimited</td>
                            <td class="plan-val">Priority</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Progress Tracking</td>
                            <td class="plan-val">Basic</td>
                            <td class="plan-val">Detailed</td>
                            <td class="plan-val">Team-wide</td>
                            <td class="plan-val">Custom Reports</td>
                        </tr>
                        <tr>
                            <td class="feature-name">Admin Controls</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">Standard</td>
                            <td class="plan-val">Advanced</td>
                        </tr>
                        <tr>
                            <td class="feature-name">API Access</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">—</td>
                            <td class="plan-val">Available</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="faq-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-800">Frequently Asked Questions</h2>
            </div>
            <div class="faq-grid">
                <div class="faq-item">
                    <h4>How do plan upgrades work?</h4>
                    <p>You can upgrade your plan at any time. When you upgrade, the new features are unlocked immediately, and your billing will be adjusted on a prorated basis for the remainder of your cycle.</p>
                </div>
                <div class="faq-item">
                    <h4>Can I cancel my subscription?</h4>
                    <p>Yes, you can cancel your subscription at any time through your account settings. You'll continue to have access to your plan features until the end of your current billing period.</p>
                </div>
                <div class="faq-item">
                    <h4>Do you offer discounts for non-profits?</h4>
                    <p>Absolutely! We offer special pricing for educational institutions and non-profit organizations. Please contact our sales team to learn more.</p>
                </div>
                <div class="faq-item">
                    <h4>Is training customized for our company?</h4>
                    <p>Our Enterprise plan includes the ability to request custom content tailored specifically to your company's unique workflows, tools, and internal AI policies.</p>
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
