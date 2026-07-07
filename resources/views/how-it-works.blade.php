@extends('layouts.public')

@section('title', 'How It Works | Daleel AI')

@section('content')
<!-- Re-linking fonts for consistency with home page -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
/* ==================== HIGH-FIDELITY DESIGN SYSTEM ==================== */
:root {
  --primary: #6366F1;
  --primary-light: #EEF2FF;
  --primary-dark: #4F46E5;
  --accent: #06B6D4;
  --text: #0F172A;
  --text-secondary: #475569;
  --text-muted: #94A3B8;
  --bg: #FFFFFF;
  --bg-secondary: #F8FAFC;
  --border: #E2E8F0;
  --success: #10B981;
  --radius-lg: 16px;
  --radius-xl: 24px;
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
  --shadow-xl: 0 20px 40px -5px rgba(0, 0, 0, 0.1);
}

.how-works-page {
  font-family: 'Inter', sans-serif;
  color: var(--text);
  line-height: 1.6;
}

.gradient-text {
  background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 50%, #A855F7 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* ==================== HERO SECTION ==================== */
.hiw-hero {
  padding: 100px 0 80px;
  background: radial-gradient(circle at 70% 30%, rgba(99, 102, 241, 0.05) 0%, transparent 70%);
  position: relative;
  overflow: hidden;
}

.hiw-hero::before {
  content: "";
  position: absolute;
  top: -100px;
  right: -100px;
  width: 400px;
  height: 400px;
  background: var(--primary-light);
  filter: blur(100px);
  opacity: 0.3;
  border-radius: 50%;
  z-index: -1;
}

.hero-tag {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 6px 16px;
  background: white;
  border: 1px solid var(--border);
  border-radius: 100px;
  font-size: 13px;
  font-weight: 700;
  color: var(--primary);
  margin-bottom: 24px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

.hiw-hero h1 {
  font-size: 56px;
  font-weight: 900;
  line-height: 1.1;
  letter-spacing: -0.02em;
  margin-bottom: 24px;
}

.hiw-hero p.lead {
  font-size: 19px;
  color: var(--text-secondary);
  max-width: 600px;
  margin-bottom: 40px;
}

/* ==================== STEPS SECTION ==================== */
.steps-container {
  padding: 80px 0;
  background: white;
}

.section-label {
  display: block;
  text-align: center;
  font-size: 13px;
  font-weight: 800;
  color: var(--primary);
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 12px;
}

.section-title {
  text-align: center;
  font-size: 36px;
  font-weight: 800;
  margin-bottom: 60px;
}

.hiw-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
}

.hiw-step-card {
  padding: 32px;
  background: var(--bg-secondary);
  border: 1px solid var(--border);
  border-radius: var(--radius-xl);
  transition: all 0.3s ease;
  height: 100%;
  position: relative;
}

.hiw-step-card:hover {
  background: white;
  border-color: var(--primary);
  transform: translateY(-8px);
  box-shadow: var(--shadow-xl);
}

.step-num {
  width: 44px;
  height: 44px;
  background: var(--primary);
  color: white;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: 18px;
  margin-bottom: 24px;
}

.hiw-step-card h3 {
  font-size: 20px;
  font-weight: 800;
  margin-bottom: 12px;
}

.hiw-step-card p {
  font-size: 15px;
  color: var(--text-secondary);
  line-height: 1.6;
}

/* ==================== FEATURE HIGHLIGHT ==================== */
.feature-highlight {
  padding: 100px 0;
  background: var(--bg-secondary);
}

.highlight-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 80px;
  align-items: center;
}

.visual-mockup {
  background: white;
  border-radius: var(--radius-xl);
  padding: 24px;
  box-shadow: var(--shadow-xl);
  border: 1px solid var(--border);
}

.mockup-item {
  display: flex;
  gap: 16px;
  padding: 16px;
  background: var(--bg-secondary);
  border-radius: 12px;
  margin-bottom: 12px;
  border: 1px solid transparent;
}

.mockup-item.active {
  background: white;
  border-color: var(--primary);
  box-shadow: var(--shadow-lg);
}

.mockup-icon {
  width: 48px;
  height: 48px;
  background: var(--primary-light);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary);
  font-size: 20px;
}

.mockup-content strong {
  display: block;
  font-size: 15px;
  margin-bottom: 4px;
}

.mockup-content p {
  font-size: 13px;
  color: var(--text-secondary);
  margin: 0;
}

/* ==================== FAQ SECTION ==================== */
.faq-section {
  padding: 100px 0;
}

.faq-list {
  max-width: 800px;
  margin: 0 auto;
}

.faq-item {
  margin-bottom: 16px;
  border: 1px solid var(--border);
  border-radius: 16px;
  overflow: hidden;
}

.faq-trigger {
  width: 100%;
  padding: 24px;
  background: white;
  border: none;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: 700;
  font-size: 16px;
  text-align: left;
  cursor: pointer;
  transition: background 0.2s;
}

.faq-trigger:hover {
  background: var(--bg-secondary);
}

.faq-content {
  padding: 0 24px 24px;
  color: var(--text-secondary);
  font-size: 15px;
  display: none;
}

.faq-item.active .faq-content {
  display: block;
}

.faq-item.active .faq-icon {
  transform: rotate(45deg);
}

.faq-icon {
  transition: transform 0.3s;
  color: var(--text-muted);
}

/* ==================== RESPONSIVE ==================== */
@media (max-width: 1024px) {
  .hiw-grid { grid-template-columns: repeat(2, 1fr); }
  .highlight-grid { grid-template-columns: 1fr; gap: 48px; }
  .hiw-hero h1 { font-size: 40px; }
  .section-title { font-size: 30px; margin-bottom: 40px; }
}

@media (max-width: 768px) {
  .hiw-hero { padding: 60px 0 50px; }
  .hiw-hero h1 { font-size: 34px; }
  .hiw-hero p.lead { font-size: 17px; }
  .hiw-hero .d-flex { flex-direction: column; align-items: flex-start; }
  .hiw-hero .d-flex .btn { width: 100%; }
  .steps-container { padding: 50px 0; }
  .hiw-step-card { padding: 24px; }
  .feature-highlight { padding: 60px 0; }
  .faq-section { padding: 60px 0; }
  .section-title { font-size: 26px; margin-bottom: 32px; }
  .highlight-grid { gap: 40px; }
  .visual-mockup { padding: 16px; }
}

@media (max-width: 640px) {
  .hiw-grid { grid-template-columns: 1fr; }
  .hiw-hero { padding: 40px 0; }
  .hiw-hero h1 { font-size: 28px; }
  .hiw-hero p.lead { font-size: 15px; margin-bottom: 28px; }
  .steps-container { padding: 40px 0; }
  .section-title { font-size: 24px; margin-bottom: 24px; }
  .hiw-step-card { padding: 20px; }
  .feature-highlight { padding: 40px 0; }
  .faq-section { padding: 40px 0; }
  .faq-trigger { padding: 16px; font-size: 14px; }
  .faq-content { padding: 0 16px 16px; font-size: 14px; }
  .highlight-grid { gap: 32px; }
  .visual-mockup { padding: 12px; }
  .mockup-item { padding: 12px; }
  .mockup-icon { width: 40px; height: 40px; font-size: 16px; }
  .hero-tag { font-size: 12px; padding: 5px 12px; }
  .hiw-hero .d-flex { gap: 12px !important; }
  .hiw-hero .d-flex .btn { font-size: 15px; padding: 10px 20px; }
}

@media (max-width: 400px) {
  .hiw-hero h1 { font-size: 24px; }
  .hiw-hero p.lead { font-size: 14px; }
  .step-num { width: 36px; height: 36px; font-size: 15px; margin-bottom: 16px; }
  .hiw-step-card h3 { font-size: 17px; }
  .hiw-step-card p { font-size: 13px; }
}

/* CTA section responsive override */
@media (max-width: 768px) {
  .how-works-page .rounded-5 { border-radius: 16px !important; }
  .how-works-page .display-5 { font-size: 1.75rem; }
  .how-works-page .p-5 { padding: 2rem !important; }
}

@media (max-width: 640px) {
  .how-works-page .display-5 { font-size: 1.5rem; }
  .how-works-page .p-5 { padding: 1.5rem !important; }
}
</style>

<div class="how-works-page">
  <!-- Hero Section -->
  <section class="hiw-hero">
    <div class="container">
      <div class="hero-tag">
        <i class="bi bi-lightning-charge-fill"></i>
        AI-Powered Transformation
      </div>
      <h1>How Daleel AI <span class="gradient-text">augments</span> your workday</h1>
      <p class="lead">Stop spending weeks on generic courses. Daleel AI maps your real-world behavior to the exact AI skills you need to save time today.</p>
      
      <div class="d-flex gap-3">
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5">Get Started Free</a>
        <a href="#process" class="btn btn-outline-secondary btn-lg px-5">See the Process</a>
      </div>
    </div>
  </section>

  <!-- Process Section -->
  <section class="steps-container" id="process">
    <div class="container">
      <span class="section-label">The Workflow</span>
      <h2 class="section-title">Four steps to AI mastery</h2>
      
      <div class="hiw-grid">
        <div class="hiw-step-card">
          <div class="step-num">01</div>
          <h3>Connect Tools</h3>
          <p>Securely link the software you use daily—like Slack, Notion, and Gmail—to identify your unique work patterns.</p>
        </div>
        <div class="hiw-step-card">
          <div class="step-num">02</div>
          <h3>Identify Gaps</h3>
          <p>Our engine detects repetitive manual tasks and identifies where AI could save you 30-60 minutes every day.</p>
        </div>
        <div class="hiw-step-card">
          <div class="step-num">03</div>
          <h3>Contextual Learning</h3>
          <p>Receive bite-sized, role-specific video lessons and prompt templates matched exactly to your active projects.</p>
        </div>
        <div class="hiw-step-card">
          <div class="step-num">04</div>
          <h3>Measure Impact</h3>
          <p>Track your productivity gains, time saved, and AI adoption through a beautiful personal analytics dashboard.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Highlight Section -->
  <section class="feature-highlight">
    <div class="container">
      <div class="highlight-grid">
        <div class="visual-mockup">
          <div class="mockup-item">
            <div class="mockup-icon"><i class="bi bi-slack"></i></div>
            <div class="mockup-content">
              <strong>Active Tool: Slack</strong>
              <p>Detected long thread summarization need.</p>
            </div>
          </div>
          <div class="mockup-item active">
            <div class="mockup-icon"><i class="bi bi-magic"></i></div>
            <div class="mockup-content">
              <strong>AI Recommendation</strong>
              <p>Apply "LLM Thread Summarizer" workflow.</p>
            </div>
          </div>
          <div class="mockup-item">
            <div class="mockup-icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="mockup-content">
              <strong>Time Saved: 12 Minutes</strong>
              <p>Workflow successfully applied.</p>
            </div>
          </div>
        </div>
        
        <div>
          <span class="section-label">Real-time Intelligence</span>
          <h2 class="mb-4">The "Learning Layer" that lives where you work</h2>
          <p class="text-secondary mb-4">Unlike traditional LMS platforms that require you to leave your workflow, Daleel AI integrates directly into your browser. It identifies automation opportunities in real-time as you switch between tabs.</p>
          
          <ul class="list-unstyled">
            <li class="mb-3 d-flex gap-3"><i class="bi bi-shield-check text-success"></i> <strong>Privacy-First:</strong> We track tool usage, not your private data.</li>
            <li class="mb-3 d-flex gap-3"><i class="bi bi-lightning text-primary"></i> <strong>Instant ROI:</strong> Apply lessons immediately to your current task.</li>
            <li class="mb-3 d-flex gap-3"><i class="bi bi-graph-up text-accent"></i> <strong>Visual Growth:</strong> See your AI maturity score improve daily.</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="faq-section">
    <div class="container">
      <h2 class="section-title">Common Questions</h2>
      
      <div class="faq-list">
        <div class="faq-item">
          <button class="faq-trigger">
            Is my data secure?
            <i class="bi bi-plus-lg faq-icon"></i>
          </button>
          <div class="faq-content">
            Yes. We follow enterprise-grade security protocols. Our browser extension only maps domains and application usage to provide recommendations; it never records your keystrokes, passwords, or private content.
          </div>
        </div>
        
        <div class="faq-item">
          <button class="faq-trigger">
            Do I need to be a prompt engineer?
            <i class="bi bi-plus-lg faq-icon"></i>
          </button>
          <div class="faq-content">
            Not at all. Daleel AI is designed for every professional. We provide the templates, the logic, and the video guides to help you go from beginner to AI-proficient in minutes.
          </div>
        </div>
        
        <div class="faq-item">
          <button class="faq-trigger">
            How does it save time?
            <i class="bi bi-plus-lg faq-icon"></i>
          </button>
          <div class="faq-content">
            By identifying repetitive tasks—like drafting weekly emails, summarizing research, or cleaning data—and showing you how to automate them with AI tools you already have.
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="container pb-5">
    <div class="p-5 text-center bg-dark text-white rounded-5 shadow-xl" style="background: linear-gradient(135deg, #0F172A, #1E293B);">
      <h2 class="display-5 fw-bold mb-3">Ready to transform your workflow?</h2>
      <div class="d-flex justify-content-center gap-3">
        <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 py-3">Join Now</a>
      </div>
    </div>
  </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const faqItems = document.querySelectorAll('.faq-item');
  
  faqItems.forEach(item => {
    const trigger = item.querySelector('.faq-trigger');
    trigger.addEventListener('click', () => {
      const isActive = item.classList.contains('active');
      
      // Close all others
      faqItems.forEach(i => i.classList.remove('active'));
      
      // Toggle current
      if (!isActive) {
        item.classList.add('active');
      }
    });
  });
});
</script>
@endsection
