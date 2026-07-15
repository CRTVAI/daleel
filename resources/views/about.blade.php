@extends('layouts.public')

@section('title', 'Daleel AI | About')

@section('content')
<style>
  :root {
    --about-bg: #ffffff;
    --about-surface: #f8fafc;
    --about-border: #e8ecf1;
    --about-text: #0f172a;
    --about-text-secondary: #475569;
    --about-accent: #2563eb;
    --about-accent-light: #eff6ff;
    --about-radius: 12px;
    --about-shadow: 0 1px 2px rgba(0,0,0,0.03);
    --about-shadow-lg: 0 4px 12px rgba(0,0,0,0.04);
  }

  .about-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--about-text);
    background: var(--about-bg);
  }

  .about-container {
    max-width: 1080px;
    margin: 0 auto;
    padding: 0 28px;
  }

  /* Hero */
  .about-hero {
    padding: 40px 0 36px;
    background: linear-gradient(180deg, #fafbfc 0%, #ffffff 100%);
    border-bottom: 1px solid var(--about-border);
  }

  .about-hero-content {
    max-width: 680px;
  }

  .about-badge {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--about-accent);
    background: var(--about-accent-light);
    padding: 4px 12px;
    border-radius: 100px;
    margin-bottom: 16px;
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }

  .about-hero h1 {
    font-size: 2.5rem;
    font-weight: 700;
    letter-spacing: -0.03em;
    line-height: 1.2;
    margin: 0 0 12px;
  }

  .about-hero .lead {
    font-size: 1.05rem;
    color: var(--about-text-secondary);
    line-height: 1.6;
    margin: 0;
    max-width: 560px;
  }

  /* Mission Section */
  .about-mission {
    padding: 40px 0;
  }

  .about-mission-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: start;
  }

  .about-mission-content .about-badge {
    margin-bottom: 12px;
  }

  .about-mission-content h2 {
    font-size: 1.75rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    margin: 0 0 10px;
    line-height: 1.2;
  }

  .about-mission-content .lead {
    font-size: 0.95rem;
    color: var(--about-text-secondary);
    line-height: 1.6;
    margin: 0 0 20px;
  }

  .about-mission-btns {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }

  .about-btn {
    display: inline-flex;
    align-items: center;
    padding: 10px 22px;
    border-radius: 100px;
    font-weight: 600;
    font-size: 0.85rem;
    text-decoration: none;
    transition: all 0.2s;
    border: 1px solid transparent;
  }

  .about-btn-primary {
    background: var(--about-accent);
    color: #fff;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.15);
  }

  .about-btn-primary:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
  }

  .about-btn-ghost {
    background: #fff;
    color: var(--about-text);
    border-color: var(--about-border);
  }

  .about-btn-ghost:hover {
    background: var(--about-surface);
  }

  .about-stats-card {
    background: #fff;
    border: 1px solid var(--about-border);
    border-radius: var(--about-radius);
    overflow: hidden;
    box-shadow: var(--about-shadow-lg);
  }

  .about-stats-header {
    padding: 12px 20px;
    border-bottom: 1px solid var(--about-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.8rem;
    font-weight: 600;
  }

  .about-status {
    font-size: 0.7rem;
    color: #16a34a;
    background: #f0fdf4;
    padding: 3px 10px;
    border-radius: 100px;
    font-weight: 500;
  }

  .about-stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    padding: 16px;
  }

  .about-stat {
    background: var(--about-surface);
    padding: 16px 12px;
    border-radius: 10px;
    text-align: center;
  }

  .about-stat-number {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--about-text);
    letter-spacing: -0.02em;
  }

  .about-stat-label {
    font-size: 0.7rem;
    color: var(--about-text-secondary);
    margin-top: 2px;
  }

  /* Features Section */
  .about-features {
    padding: 40px 0;
    background: var(--about-surface);
    border-top: 1px solid var(--about-border);
    border-bottom: 1px solid var(--about-border);
  }

  .about-section-header {
    text-align: center;
    margin-bottom: 32px;
  }

  .about-section-header h2 {
    font-size: 1.75rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    margin: 0 0 8px;
  }

  .about-section-header .lead {
    font-size: 0.95rem;
    color: var(--about-text-secondary);
    max-width: 480px;
    margin: 0 auto;
  }

  .about-features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
  }

  .about-feature-card {
    background: #fff;
    border: 1px solid var(--about-border);
    border-radius: var(--about-radius);
    padding: 20px;
    transition: all 0.2s;
  }

  .about-feature-card:hover {
    box-shadow: var(--about-shadow-lg);
    border-color: #cbd5e1;
  }

  .about-feature-icon {
    width: 36px;
    height: 36px;
    background: var(--about-accent-light);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
    font-size: 1rem;
  }

  .about-feature-label {
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--about-accent);
    margin-bottom: 6px;
  }

  .about-feature-card h3 {
    font-size: 0.95rem;
    font-weight: 600;
    margin: 0 0 6px;
    letter-spacing: -0.01em;
  }

  .about-feature-card p {
    font-size: 0.85rem;
    color: var(--about-text-secondary);
    line-height: 1.5;
    margin: 0;
  }

  /* Timeline Section */
  .about-timeline {
    padding: 40px 0;
  }

  .about-timeline-grid {
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-width: 680px;
    margin: 0 auto;
  }

  .about-timeline-item {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    padding: 16px 20px;
    background: #fff;
    border: 1px solid var(--about-border);
    border-radius: var(--about-radius);
    transition: all 0.2s;
  }

  .about-timeline-item:hover {
    box-shadow: var(--about-shadow);
  }

  .about-timeline-phase {
    flex-shrink: 0;
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--about-accent);
    background: var(--about-accent-light);
    padding: 4px 12px;
    border-radius: 100px;
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }

  .about-timeline-item h3 {
    font-size: 0.9rem;
    font-weight: 600;
    margin: 0;
    line-height: 1.5;
    letter-spacing: -0.01em;
  }

  @media (max-width: 768px) {
    .about-hero h1 {
      font-size: 2rem;
    }
    .about-mission-grid {
      grid-template-columns: 1fr;
      gap: 28px;
    }
    .about-features-grid {
      grid-template-columns: 1fr 1fr;
    }
    .about-container {
      padding: 0 20px;
    }
  }

  @media (max-width: 480px) {
    .about-features-grid {
      grid-template-columns: 1fr;
    }
    .about-timeline-item {
      flex-direction: column;
      gap: 8px;
    }
  }
</style>

<div class="about-page">
  {{-- Hero --}}
  <section class="about-hero">
    <div class="about-container">
      <div class="about-hero-content">
        <span class="about-badge">Company Profile</span>
        <h1>Daleel AI helps people learn AI through the work they already do</h1>
        <p class="lead">We are building a practical AI learning layer for professionals, teams, and enterprises that need measurable adoption, not random content.</p>
      </div>
    </div>
  </section>

  {{-- Mission + Stats --}}
  <section class="about-mission">
    <div class="about-container">
      <div class="about-mission-grid">
        <div class="about-mission-content">
          <span class="about-badge">Company Mission</span>
          <h2>Make AI useful at work for every role</h2>
          <p class="lead">Daleel AI exists because most AI training is disconnected from the user's actual job. Our platform connects learning to role, tools, workflows, goals, and behavior so users can apply AI immediately.</p>
          <div class="about-mission-btns">
            <a href="{{ url('contact') }}" class="about-btn about-btn-primary">Partner With Us</a>
            <a href="{{ url('enterprise') }}" class="about-btn about-btn-ghost">Enterprise Training</a>
          </div>
        </div>
        <div class="about-stats-card">
          <div class="about-stats-header">
            Company Snapshot
            <span class="about-status">● Public Profile</span>
          </div>
          <div class="about-stats-grid">
            <div class="about-stat">
              <div class="about-stat-number">2026</div>
              <div class="about-stat-label">Public Site Profile</div>
            </div>
            <div class="about-stat">
              <div class="about-stat-number">20+</div>
              <div class="about-stat-label">Countries Reached</div>
            </div>
            <div class="about-stat">
              <div class="about-stat-number">120+</div>
              <div class="about-stat-label">Teams Trained</div>
            </div>
            <div class="about-stat">
              <div class="about-stat-number">10k+</div>
              <div class="about-stat-label">Lessons Watched</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Features --}}
  <section class="about-features">
    <div class="about-container">
      <div class="about-section-header">
        <h2>What the company provides</h2>
        <p class="lead">A complete public profile of the product, audience, and services.</p>
      </div>
      <div class="about-features-grid">
        <div class="about-feature-card">
          <div class="about-feature-icon">🎯</div>
          <div class="about-feature-label">Feature</div>
          <h3>Individual AI Learning</h3>
          <p>Personalized videos, saved lessons, mentor questions, and certificates for professionals.</p>
        </div>
        <div class="about-feature-card">
          <div class="about-feature-icon">👥</div>
          <div class="about-feature-label">Feature</div>
          <h3>Team Training</h3>
          <p>Department paths, team dashboards, readiness scoring, and adoption reporting for managers.</p>
        </div>
        <div class="about-feature-card">
          <div class="about-feature-icon">🏗️</div>
          <div class="about-feature-label">Feature</div>
          <h3>Custom Enterprise Content</h3>
          <p>Company-specific lessons and workflows built around internal processes, policies, and tools.</p>
        </div>
        <div class="about-feature-card">
          <div class="about-feature-icon">⚡</div>
          <div class="about-feature-label">Feature</div>
          <h3>Workflow Intelligence</h3>
          <p>Optional Chrome extension signals that identify repeated work and AI opportunities.</p>
        </div>
        <div class="about-feature-card">
          <div class="about-feature-icon">🤝</div>
          <div class="about-feature-label">Feature</div>
          <h3>Partner Ecosystem</h3>
          <p>Support for learning around tools such as OpenAI, Google Cloud, Microsoft, and ElevenLabs.</p>
        </div>
        <div class="about-feature-card">
          <div class="about-feature-icon">📊</div>
          <div class="about-feature-label">Feature</div>
          <h3>Measurable Adoption</h3>
          <p>Progress, time saved, completed learning paths, and practical AI implementation outcomes.</p>
        </div>
      </div>
    </div>
  </section>

</div>
@endsection