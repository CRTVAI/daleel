@extends('layouts.public')

@section('title', 'Daleel AI | Terms of Service')

@section('content')
<style>
  :root {
    --tos-bg: #ffffff;
    --tos-surface: #f8fafc;
    --tos-border: #e8ecf1;
    --tos-text: #0f172a;
    --tos-text-secondary: #475569;
    --tos-accent: #2563eb;
    --tos-accent-light: #eff6ff;
    --tos-radius: 12px;
    --tos-shadow: 0 1px 2px rgba(0,0,0,0.03);
  }

  .tos-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--tos-text);
    background: var(--tos-bg);
  }

  .tos-container {
    max-width: 1080px;
    margin: 0 auto;
    padding: 0 28px;
  }

  /* Hero */
  .tos-hero {
    padding: 40px 0 36px;
    background: linear-gradient(180deg, #fafbfc 0%, #ffffff 100%);
    border-bottom: 1px solid var(--tos-border);
  }

  .tos-hero-content {
    max-width: 680px;
  }

  .tos-badge {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--tos-accent);
    background: var(--tos-accent-light);
    padding: 4px 12px;
    border-radius: 100px;
    margin-bottom: 16px;
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }

  .tos-hero h1 {
    font-size: 2.5rem;
    font-weight: 700;
    letter-spacing: -0.03em;
    line-height: 1.2;
    margin: 0 0 12px;
  }

  .tos-hero .lead {
    font-size: 1.05rem;
    color: var(--tos-text-secondary);
    line-height: 1.6;
    margin: 0 0 20px;
    max-width: 560px;
  }

  .tos-hero-btns {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
  }

  .tos-btn {
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

  .tos-btn-primary {
    background: var(--tos-accent);
    color: #fff;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.15);
  }

  .tos-btn-primary:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
  }

  .tos-btn-ghost {
    background: #fff;
    color: var(--tos-text);
    border-color: var(--tos-border);
  }

  .tos-btn-ghost:hover {
    background: var(--tos-surface);
  }

  /* Content Section */
  .tos-content {
    padding: 40px 0;
  }

  .tos-card {
    background: #fff;
    border: 1px solid var(--tos-border);
    border-radius: var(--tos-radius);
    padding: 32px;
    box-shadow: var(--tos-shadow);
  }

  .tos-card h3 {
    font-size: 1.05rem;
    font-weight: 600;
    color: var(--tos-text);
    margin: 0 0 8px;
    letter-spacing: -0.01em;
  }

  .tos-card h3:not(:first-child) {
    margin-top: 24px;
  }

  .tos-card p {
    font-size: 0.9rem;
    color: var(--tos-text-secondary);
    line-height: 1.6;
    margin: 0;
  }

  .tos-notice {
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 10px;
    padding: 14px 18px;
    margin-bottom: 24px;
    display: flex;
    gap: 10px;
    align-items: flex-start;
  }

  .tos-notice-icon {
    font-size: 1rem;
    flex-shrink: 0;
    margin-top: 1px;
  }

  .tos-notice p {
    font-size: 0.85rem;
    color: #92400e;
    margin: 0;
    line-height: 1.5;
  }

  @media (max-width: 768px) {
    .tos-hero h1 {
      font-size: 2rem;
    }
    .tos-card {
      padding: 24px;
    }
    .tos-container {
      padding: 0 20px;
    }
  }
</style>

<div class="tos-page">
  {{-- Hero --}}
  <section class="tos-hero">
    <div class="tos-container">
      <div class="tos-hero-content">
        <span class="tos-badge">Legal</span>
        <h1>Terms of Service</h1>
        <p class="lead">Sample legal content for the static public website. Replace with reviewed production policy text before launch.</p>
        <div class="tos-hero-btns">
          <a href="{{ url('contact') }}" class="tos-btn tos-btn-primary">Contact</a>
          <a href="{{ url('/') }}" class="tos-btn tos-btn-ghost">Back Home</a>
        </div>
      </div>
    </div>
  </section>

  {{-- Content --}}
  <section class="tos-content">
    <div class="tos-container">
      <div class="tos-notice">
        <span class="tos-notice-icon">⚠️</span>
        <p>This is sample placeholder text. Replace with reviewed legal language before production launch.</p>
      </div>
      
      <div class="tos-card">
        <h3>1. Scope</h3>
        <p>This sample page outlines expected public website terms for Daleel AI. Replace this copy with reviewed legal language before launch.</p>

        <h3>2. Responsibilities</h3>
        <p>Users are responsible for using lessons, templates, and AI recommendations in accordance with their company policies and applicable laws.</p>

        <h3>3. Data and Usage</h3>
        <p>The platform may provide educational guidance, workflow suggestions, analytics, and AI mentor responses, but users remain responsible for final work decisions.</p>

        <h3>4. Updates</h3>
        <p>Enterprise features, custom content, and support obligations should be governed by a signed agreement or order form.</p>
      </div>
    </div>
  </section>
</div>
@endsection