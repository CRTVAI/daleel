@extends('layouts.public')

@section('title', 'How It Works | Daleel AI')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
:root {
  --primary: #6366F1;
  --primary-light: #EEF2FF;
  --primary-dark: #4F46E5;
  --text: #0F172A;
  --text-secondary: #475569;
  --text-muted: #94A3B8;
  --bg: #FFFFFF;
  --bg-secondary: #F8FAFC;
  --border: #E2E8F0;
  --success: #10B981;
  --radius: 12px;
}

.how-works-page {
  font-family: 'Inter', sans-serif;
  color: var(--text);
  line-height: 1.5;
}

.gradient-text {
  background: linear-gradient(135deg, #6366F1, #8B5CF6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Hero */
.hiw-hero {
  padding: 40px 0 32px;
  position: relative;
  overflow: hidden;
  background: radial-gradient(circle at 80% 20%, rgba(99,102,241,0.04) 0%, transparent 60%);
}

.hero-tag {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 5px 14px;
  background: var(--primary-light);
  border-radius: 100px;
  font-size: 11px;
  font-weight: 700;
  color: var(--primary-dark);
  margin-bottom: 16px;
}

.hiw-hero h1 {
  font-size: 2.5rem;
  font-weight: 900;
  line-height: 1.15;
  letter-spacing: -0.02em;
  margin-bottom: 10px;
}

.hiw-hero p.lead {
  font-size: 1rem;
  color: var(--text-secondary);
  max-width: 520px;
  margin-bottom: 20px;
}

.hiw-hero .btn-primary {
  padding: 10px 24px;
  font-size: 14px;
  font-weight: 700;
  border-radius: 8px;
  background: var(--primary);
  border: none;
}

.hiw-hero .btn-primary:hover { background: var(--primary-dark); }

.hiw-hero .btn-outline-secondary {
  padding: 10px 24px;
  font-size: 14px;
  font-weight: 700;
  border-radius: 8px;
  border: 2px solid var(--border);
  color: var(--text);
}

.hiw-hero .btn-outline-secondary:hover { border-color: var(--primary); color: var(--primary); }

.hero-stats-row {
  display: flex;
  gap: 32px;
  margin-top: 24px;
  padding-top: 20px;
  border-top: 1px solid var(--border);
}

.hero-stat h3 {
  font-size: 1.25rem;
  font-weight: 900;
  margin: 0;
}

.hero-stat p {
  font-size: 11px;
  color: var(--text-muted);
  margin: 2px 0 0;
}

/* Steps */
.steps-container {
  padding: 32px 0;
  background: var(--bg-secondary);
}

.section-label {
  display: block;
  text-align: center;
  font-size: 11px;
  font-weight: 800;
  color: var(--primary);
  text-transform: uppercase;
  letter-spacing: 1.5px;
  margin-bottom: 6px;
}

.section-title {
  text-align: center;
  font-size: 1.5rem;
  font-weight: 800;
  margin-bottom: 24px;
  letter-spacing: -0.02em;
}

.hiw-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
}

.hiw-step-card {
  padding: 20px;
  background: white;
  border: 1px solid var(--border);
  border-radius: var(--radius);
  transition: all 0.2s;
}

.hiw-step-card:hover {
  border-color: var(--primary);
  box-shadow: 0 4px 16px rgba(99,102,241,0.08);
}

.step-icon-wrap {
  width: 38px;
  height: 38px;
  background: var(--primary-light);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 12px;
}

.step-icon-wrap i { font-size: 17px; color: var(--primary); }

.step-num {
  font-size: 10px;
  font-weight: 700;
  color: var(--text-muted);
  letter-spacing: 0.5px;
  margin-bottom: 2px;
}

.hiw-step-card h3 {
  font-size: 15px;
  font-weight: 800;
  margin-bottom: 6px;
}

.hiw-step-card p {
  font-size: 12px;
  color: var(--text-secondary);
  line-height: 1.5;
  margin: 0;
}

/* Preview */
.preview-section {
  padding: 32px 0;
  background: white;
}

.preview-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 36px;
  align-items: center;
}

.preview-mockup {
  background: var(--bg-secondary);
  border-radius: var(--radius);
  padding: 16px;
  border: 1px solid var(--border);
}

.mockup-header {
  display: flex;
  align-items: center;
  gap: 5px;
  margin-bottom: 12px;
  padding-bottom: 10px;
  border-bottom: 1px solid var(--border);
}

.mockup-dot { width: 7px; height: 7px; border-radius: 50%; }
.mockup-dot.red { background: #EF4444; }
.mockup-dot.yellow { background: #F59E0B; }
.mockup-dot.green { background: #10B981; }

.mockup-header span {
  font-size: 10px;
  font-weight: 600;
  color: var(--text-muted);
  margin-left: 5px;
}

.mockup-row {
  display: flex;
  gap: 10px;
  padding: 10px;
  background: white;
  border-radius: 8px;
  margin-bottom: 8px;
  border: 1px solid var(--border);
}

.mockup-row.highlight {
  border-color: var(--primary);
  box-shadow: 0 2px 8px rgba(99,102,241,0.06);
}

.mockup-row-icon {
  width: 34px;
  height: 34px;
  background: var(--primary-light);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary);
  flex-shrink: 0;
}

.mockup-row-content strong {
  display: block;
  font-size: 12px;
  margin-bottom: 1px;
}

.mockup-row-content p {
  font-size: 11px;
  color: var(--text-secondary);
  margin: 0;
}

.preview-text h3 {
  font-size: 1.5rem;
  font-weight: 800;
  margin-bottom: 10px;
  letter-spacing: -0.02em;
}

.preview-text p {
  font-size: 14px;
  color: var(--text-secondary);
  line-height: 1.6;
  margin-bottom: 16px;
}

.preview-features {
  list-style: none;
  padding: 0;
}

.preview-features li {
  display: flex;
  gap: 8px;
  margin-bottom: 8px;
  font-size: 13px;
}

.preview-features li i { color: var(--success); margin-top: 2px; flex-shrink: 0; }

/* FAQ */
.faq-section {
  padding: 32px 0;
  background: var(--bg-secondary);
}

.faq-list {
  max-width: 640px;
  margin: 0 auto;
}

.faq-item {
  margin-bottom: 8px;
  border: 1px solid var(--border);
  border-radius: 10px;
  overflow: hidden;
  background: white;
}

.faq-item.active { border-color: var(--primary); }

.faq-trigger {
  width: 100%;
  padding: 14px 18px;
  background: white;
  border: none;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: 700;
  font-size: 14px;
  text-align: left;
  cursor: pointer;
  gap: 10px;
}

.faq-trigger:hover { background: var(--bg-secondary); }

.faq-content {
  padding: 0 18px 14px;
  color: var(--text-secondary);
  font-size: 13px;
  display: none;
  line-height: 1.6;
}

.faq-item.active .faq-content { display: block; }
.faq-item.active .faq-icon { transform: rotate(45deg); color: var(--primary); }

.faq-icon {
  transition: transform 0.2s, color 0.2s;
  color: var(--text-muted);
  font-size: 16px;
  flex-shrink: 0;
}

/* CTA */
.cta-section {
  padding: 32px 0;
  background: white;
}

.cta-card {
  padding: 36px 32px;
  border-radius: 16px;
  background: linear-gradient(135deg, #0F172A, #1E1B4B);
  text-align: center;
}

.cta-card h2 {
  font-size: 1.5rem;
  font-weight: 900;
  color: white;
  margin-bottom: 8px;
}

.cta-card p {
  font-size: 14px;
  color: #94A3B8;
  margin-bottom: 20px;
  max-width: 440px;
  margin-left: auto;
  margin-right: auto;
}

.cta-card .btn {
  padding: 12px 32px;
  font-size: 14px;
  font-weight: 700;
  border-radius: 10px;
  background: var(--primary);
  border: none;
}

.cta-card .btn:hover { background: var(--primary-dark); }

.cta-note {
  font-size: 11px;
  color: #64748B;
  margin-top: 10px;
}

/* Responsive */
@media (max-width: 1024px) {
  .hiw-hero h1 { font-size: 2rem; }
  .hiw-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
  .hiw-hero { padding: 32px 0 24px; }
  .hiw-hero h1 { font-size: 1.6rem; }
  .hiw-hero p.lead { font-size: 14px; }
  .hero-stats-row { gap: 20px; }
  .hero-stat h3 { font-size: 1.1rem; }
  .section-title { font-size: 1.3rem; margin-bottom: 20px; }
  .hiw-step-card { padding: 16px; }
  .preview-grid { grid-template-columns: 1fr; gap: 24px; }
  .preview-text h3 { font-size: 1.3rem; }
  .cta-card { padding: 28px 20px; }
  .cta-card h2 { font-size: 1.3rem; }
  .hiw-hero .d-flex { flex-direction: column; gap: 8px; }
  .hiw-hero .d-flex .btn { width: 100%; text-align: center; }
}

@media (max-width: 640px) {
  .hiw-hero h1 { font-size: 1.4rem; }
  .hiw-grid { grid-template-columns: 1fr; }
  .hero-stats-row { flex-direction: column; gap: 12px; }
}
</style>

<div class="how-works-page">

  <!-- Hero -->
  <section class="hiw-hero">
    <div class="container">
      <div class="hero-tag">
        <i class="bi bi-lightning-charge-fill"></i>
        AI-Powered Transformation
      </div>
      <h1>How Daleel AI <span class="gradient-text">augments</span> your workday</h1>
      <p class="lead">Stop spending weeks on generic courses. Daleel AI maps your real-world behavior to the exact AI skills you need to save time today.</p>

      <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('register') }}" class="btn btn-primary">Get Started Free</a>
        <a href="#process" class="btn  text-black">See the Process</a>
      </div>

      <div class="hero-stats-row">
        <div class="hero-stat"><h3>15K+</h3><p>Active Learners</p></div>
        <div class="hero-stat"><h3>320+</h3><p>AI Workflows</p></div>
        <div class="hero-stat"><h3>40%</h3><p>Avg. Productivity Gain</p></div>
      </div>
    </div>
  </section>

  <!-- 4 Steps -->
  <section class="steps-container" id="process">
    <div class="container">
      <span class="section-label">The Workflow</span>
      <h2 class="section-title">Four steps to AI mastery</h2>

      <div class="hiw-grid">
        <div class="hiw-step-card">
          <div class="step-icon-wrap"><i class="bi bi-puzzle-fill"></i></div>
          <div class="step-num">01</div>
          <h3>Connect Tools</h3>
          <p>Link your daily tools — Slack, Notion, Gmail — to identify your unique work patterns.</p>
        </div>
        <div class="hiw-step-card">
          <div class="step-icon-wrap"><i class="bi bi-search-heart"></i></div>
          <div class="step-num">02</div>
          <h3>Identify Gaps</h3>
          <p>Our engine finds repetitive manual tasks and shows where AI can save you time.</p>
        </div>
        <div class="hiw-step-card">
          <div class="step-icon-wrap"><i class="bi bi-mortarboard-fill"></i></div>
          <div class="step-num">03</div>
          <h3>Contextual Learning</h3>
          <p>Bite-sized lessons and prompt templates matched to your active browser tab.</p>
        </div>
        <div class="hiw-step-card">
          <div class="step-icon-wrap"><i class="bi bi-bar-chart-fill"></i></div>
          <div class="step-num">04</div>
          <h3>Measure Impact</h3>
          <p>Track time saved, skills mastered, and AI adoption on your dashboard.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Looks -->
  <section class="preview-section">
    <div class="container">
      <div class="preview-grid">
        <div class="preview-mockup">
          <div class="mockup-header">
            <span class="mockup-dot red"></span><span class="mockup-dot yellow"></span><span class="mockup-dot green"></span>
            <span>Live Activity Feed</span>
          </div>
          <div class="mockup-row">
            <div class="mockup-row-icon"><i class="bi bi-slack"></i></div>
            <div class="mockup-row-content"><strong>Active Tool: Slack</strong><p>Detected long thread summarization need.</p></div>
          </div>
          <div class="mockup-row highlight">
            <div class="mockup-row-icon"><i class="bi bi-magic"></i></div>
            <div class="mockup-row-content"><strong>AI Recommendation</strong><p>Apply "Thread Summarizer" workflow.</p></div>
          </div>
          <div class="mockup-row">
            <div class="mockup-row-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="mockup-row-content"><strong>Time Saved: 12 min</strong><p>Workflow applied successfully.</p></div>
          </div>
        </div>
        <div class="preview-text">
          <span class="section-label" style="text-align: left;">Real-time Intelligence</span>
          <h3>The learning layer that lives where you work</h3>
          <p>Daleel integrates directly into your browser. It identifies automation opportunities as you switch between tabs — no context switching required.</p>
          <ul class="preview-features">
            <li><i class="bi bi-check-circle-fill"></i> Privacy-first — we track tool usage, not content</li>
            <li><i class="bi bi-check-circle-fill"></i> Apply lessons instantly to your current task</li>
            <li><i class="bi bi-check-circle-fill"></i> Watch your AI maturity score improve daily</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section class="faq-section">
    <div class="container">
      <h2 class="section-title">Common Questions</h2>
      <div class="faq-list">
        <div class="faq-item">
          <button class="faq-trigger">Is my data secure?<i class="bi bi-plus-lg faq-icon"></i></button>
          <div class="faq-content">Yes. The extension only maps domains and app usage — never keystrokes, passwords, or private content.</div>
        </div>
        <div class="faq-item">
          <button class="faq-trigger">Do I need to be a prompt engineer?<i class="bi bi-plus-lg faq-icon"></i></button>
          <div class="faq-content">Not at all. We provide ready-to-use templates and video guides to go from beginner to AI-proficient quickly.</div>
        </div>
        <div class="faq-item">
          <button class="faq-trigger">How does it save time?<i class="bi bi-plus-lg faq-icon"></i></button>
          <div class="faq-content">By identifying repetitive tasks — drafting emails, summarizing research, cleaning data — and showing how to automate them.</div>
        </div>
        <div class="faq-item">
          <button class="faq-trigger">Can my team use it together?<i class="bi bi-plus-lg faq-icon"></i></button>
          <div class="faq-content">Yes. Managers get a shared dashboard to track team adoption, identify skill gaps, and measure productivity gains.</div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-card">
        <h2>Ready to transform your workflow?</h2>
        <p>Join 15,000+ professionals saving hours every week with Daleel AI.</p>
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started Free</a>
        <div class="cta-note">No credit card required. Cancel anytime.</div>
      </div>
    </div>
  </section>

</div>

<script>
document.querySelectorAll('.faq-item').forEach(item => {
  item.querySelector('.faq-trigger').addEventListener('click', () => {
    const active = item.classList.contains('active');
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));
    if (!active) item.classList.add('active');
  });
});
</script>
@endsection