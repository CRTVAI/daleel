@extends('layouts.public')

@section('title', 'Daleel AI | Privacy Policy')

@section('content')
<style>
  :root {
    --privacy-bg: #ffffff;
    --privacy-surface: #f8fafc;
    --privacy-border: #e8ecf1;
    --privacy-text: #0f172a;
    --privacy-text-secondary: #475569;
    --privacy-accent: #2563eb;
    --privacy-accent-light: #eff6ff;
    --privacy-radius: 12px;
    --privacy-shadow: 0 1px 2px rgba(0,0,0,0.03);
  }

  .privacy-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--privacy-text);
    background: var(--privacy-bg);
  }

  .privacy-container {
    max-width: 1080px;
    margin: 0 auto;
    padding: 0 28px;
  }

  /* Hero */
  .privacy-hero {
    padding: 40px 0 36px;
    background: linear-gradient(180deg, #fafbfc 0%, #ffffff 100%);
    border-bottom: 1px solid var(--privacy-border);
  }

  .privacy-hero-content {
    max-width: 680px;
  }

  .privacy-badge {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--privacy-accent);
    background: var(--privacy-accent-light);
    padding: 4px 12px;
    border-radius: 100px;
    margin-bottom: 16px;
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }

  .privacy-hero h1 {
    font-size: 2.5rem;
    font-weight: 700;
    letter-spacing: -0.03em;
    line-height: 1.2;
    margin: 0 0 12px;
  }

  .privacy-hero .lead {
    font-size: 1.05rem;
    color: var(--privacy-text-secondary);
    line-height: 1.6;
    margin: 0 0 20px;
    max-width: 560px;
  }

  .privacy-hero-btns {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }

  .privacy-btn {
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

  .privacy-btn-primary {
    background: var(--privacy-accent);
    color: #fff;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.15);
  }

  .privacy-btn-primary:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
  }

  .privacy-btn-ghost {
    background: #fff;
    color: var(--privacy-text);
    border-color: var(--privacy-border);
  }

  .privacy-btn-ghost:hover {
    background: var(--privacy-surface);
  }

  /* Content Section */
  .privacy-content {
    padding: 40px 0;
  }

  .privacy-card {
    background: #fff;
    border: 1px solid var(--privacy-border);
    border-radius: var(--privacy-radius);
    padding: 32px;
    box-shadow: var(--privacy-shadow);
  }

  .privacy-card h3 {
    font-size: 1.05rem;
    font-weight: 600;
    color: var(--privacy-text);
    margin: 0 0 8px;
    letter-spacing: -0.01em;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .privacy-card h3:not(:first-child) {
    margin-top: 24px;
  }

  .privacy-card h3 .icon {
    width: 28px;
    height: 28px;
    background: var(--privacy-accent-light);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    flex-shrink: 0;
  }

  .privacy-card p {
    font-size: 0.9rem;
    color: var(--privacy-text-secondary);
    line-height: 1.6;
    margin: 0 0 0 38px;
  }

  .privacy-notice {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 10px;
    padding: 14px 18px;
    margin-bottom: 24px;
    display: flex;
    gap: 10px;
    align-items: flex-start;
  }

  .privacy-notice-icon {
    font-size: 1rem;
    flex-shrink: 0;
    margin-top: 1px;
  }

  .privacy-notice p {
    font-size: 0.85rem;
    color: #92400e;
    margin: 0;
    line-height: 1.5;
  }

  .privacy-rights-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-top: 24px;
  }

  .privacy-right-item {
    background: var(--privacy-surface);
    border-radius: 10px;
    padding: 16px;
    font-size: 0.85rem;
    color: var(--privacy-text-secondary);
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .privacy-right-icon {
    font-size: 1rem;
    flex-shrink: 0;
  }

  @media (max-width: 768px) {
    .privacy-hero h1 {
      font-size: 2rem;
    }
    .privacy-card {
      padding: 24px;
    }
    .privacy-card p {
      margin-left: 0;
    }
    .privacy-container {
      padding: 0 20px;
    }
    .privacy-rights-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="privacy-page">
  {{-- Hero --}}
  <section class="privacy-hero">
    <div class="privacy-container">
      <div class="privacy-hero-content">
        <span class="privacy-badge">Legal</span>
        <h1>Privacy Policy</h1>
        <p class="lead">How Daleel AI collects, uses, protects, and manages information across our learning platform and browser-extension features.</p>
        <div class="privacy-hero-btns">
          <a href="{{ url('contact') }}" class="privacy-btn privacy-btn-primary">Contact</a>
          <a href="{{ url('/') }}" class="privacy-btn privacy-btn-ghost">Back Home</a>
        </div>
      </div>
    </div>
  </section>

  {{-- Content --}}
  <section class="privacy-content">
    <div class="privacy-container">
      <div class="privacy-notice">
        <span class="privacy-notice-icon">⚠️</span>
        <p>This policy explains the main categories of information Daleel AI handles and the controls available to learners, instructors, and team administrators.</p>
      </div>
      
      <div class="privacy-card">
        <h3>
          <span class="icon">📋</span>
          1. Data Collection
        </h3>
        <p>Daleel AI should collect only the information needed to personalize learning, operate accounts, support teams, and improve recommendations.</p>

        <h3>
          <span class="icon">🔒</span>
          2. Workflow Privacy
        </h3>
        <p>Optional workflow analysis should focus on behavior signals such as tools used, repeated tasks, and time allocation, not private content.</p>

        <h3>
          <span class="icon">📊</span>
          3. Enterprise Analytics
        </h3>
        <p>Companies should configure employee analytics transparently and explain how progress, adoption, and productivity insights are used.</p>

        <h3>
          <span class="icon">⚙️</span>
          4. User Rights
        </h3>
        <p>Users should be able to request access, correction, deletion, or export of personal data according to the final production policy.</p>

        <div class="privacy-rights-grid">
          <div class="privacy-right-item">
            <span class="privacy-right-icon">✅</span>
            Access your data
          </div>
          <div class="privacy-right-item">
            <span class="privacy-right-icon">✏️</span>
            Correct information
          </div>
          <div class="privacy-right-item">
            <span class="privacy-right-icon">🗑️</span>
            Delete account
          </div>
          <div class="privacy-right-item">
            <span class="privacy-right-icon">📤</span>
            Export data
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
