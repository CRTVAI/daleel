@extends('layouts.public')

@section('title', 'Contact Us | Daleel AI')

@section('content')
<style>
  :root {
    --contact-bg: #ffffff;
    --contact-surface: #f8fafc;
    --contact-border: #e8ecf1;
    --contact-text: #0f172a;
    --contact-text-secondary: #64748b;
    --contact-accent: #6366f1;
    --contact-accent-light: #eef2ff;
    --contact-radius: 16px;
  }

  .contact-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--contact-text);
    background: var(--contact-bg);
  }

  .contact-container {
    max-width: 880px;
    margin: 0 auto;
    padding: 0 24px;
    width: 100%;
  }

  /* Hero */
  .contact-hero {
    padding: 36px 0 32px;
    text-align: center;
  }

  .contact-badge {
    display: inline-block;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--contact-accent);
    background: var(--contact-accent-light);
    padding: 4px 12px;
    border-radius: 100px;
    margin-bottom: 14px;
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }

  .contact-hero h1 {
    font-size: 2.25rem;
    font-weight: 700;
    letter-spacing: -0.03em;
    margin: 0 0 8px;
    line-height: 1.2;
  }

  .contact-hero .lead {
    font-size: 0.95rem;
    color: var(--contact-text-secondary);
    margin: 0 auto;
    max-width: 440px;
    line-height: 1.5;
  }

  /* Main Card */
  .contact-wrapper {
    background: #fff;
    border-radius: 24px;
    box-shadow: 0 0 0 1px rgba(0,0,0,0.04), 0 20px 60px -20px rgba(0,0,0,0.1);
    overflow: hidden;
    display: grid;
    grid-template-columns: 280px 1fr;
    margin-bottom: 48px;
  }

  /* Left Panel */
  .contact-info-panel {
    background: linear-gradient(160deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%);
    padding: 40px 32px;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
  }

  .contact-info-panel::before {
    content: '';
    position: absolute;
    top: -60px;
    right: -60px;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
  }

  .contact-info-panel::after {
    content: '';
    position: absolute;
    bottom: -40px;
    left: -40px;
    width: 160px;
    height: 160px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
  }

  .contact-info-content {
    position: relative;
    z-index: 1;
  }

  .contact-info-panel .badge {
    display: inline-block;
    font-size: 0.7rem;
    font-weight: 700;
    background: rgba(255,255,255,0.15);
    padding: 5px 14px;
    border-radius: 100px;
    margin-bottom: 20px;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    backdrop-filter: blur(4px);
  }

  .contact-info-panel h2 {
    font-size: 1.75rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    margin: 0 0 10px;
    line-height: 1.25;
  }

  .contact-info-panel .info-subtitle {
    font-size: 0.88rem;
    opacity: 0.75;
    line-height: 1.6;
    margin: 0 0 32px;
  }

  .contact-info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
    font-size: 0.88rem;
  }

  .contact-info-item .icon-circle {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(255,255,255,0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .contact-info-item span {
    opacity: 0.9;
  }

  /* Right Panel - Form */
  .contact-form-panel {
    padding: 40px 36px;
  }

  .contact-form-panel h3 {
    font-size: 1.4rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    margin: 0 0 4px;
  }

  .contact-form-panel .form-subtitle {
    font-size: 0.85rem;
    color: var(--contact-text-secondary);
    margin-bottom: 24px;
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 12px;
  }

  .form-group {
    margin-bottom: 12px;
  }

  .form-group label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--contact-text);
    margin-bottom: 5px;
    letter-spacing: -0.01em;
  }

  .form-group input,
  .form-group select,
  .form-group textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid #e8ecf1;
    border-radius: 10px;
    font-size: 0.9rem;
    font-family: inherit;
    color: var(--contact-text);
    background: #fafbfc;
    transition: all 0.2s;
    outline: none;
  }

  .form-group input:focus,
  .form-group select:focus,
  .form-group textarea:focus {
    border-color: var(--contact-accent);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.06);
  }

  .form-group input::placeholder,
  .form-group textarea::placeholder {
    color: #b0b8c1;
  }

  .form-group select {
    appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="%2394a3b8" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>');
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 36px;
    cursor: pointer;
  }

  .form-group textarea {
    resize: vertical;
    min-height: 100px;
  }

  .form-group .error-text {
    font-size: 0.75rem;
    color: #ef4444;
    margin-top: 4px;
  }

  .form-group input.error,
  .form-group select.error,
  .form-group textarea.error {
    border-color: #ef4444;
  }

  .submit-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-top: 4px;
  }

  .submit-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 28px;
    background: var(--contact-accent);
    color: #fff;
    border: none;
    border-radius: 100px;
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s;
    font-family: inherit;
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.3);
    white-space: nowrap;
  }

  .submit-btn:hover {
    background: #4f46e5;
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
    transform: translateY(-1px);
  }

  .submit-btn:disabled {
    opacity: 0.7;
    transform: none;
    cursor: not-allowed;
  }

  .response-time {
    font-size: 0.8rem;
    color: var(--contact-text-secondary);
  }

  /* Alerts */
  .alert {
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 0.82rem;
    margin-bottom: 16px;
    font-weight: 500;
  }

  .alert-error {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
  }

  .alert-success {
    background: #f0fdf4;
    color: #166534;
    border: 1px solid #bbf7d0;
  }

  /* Success Popup */
  .popup-backdrop {
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(15, 23, 42, 0.5);
    backdrop-filter: blur(6px);
    z-index: 1080;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s;
  }

  .popup-backdrop.show {
    opacity: 1;
    visibility: visible;
  }

  .popup {
    width: min(400px, 90%);
    background: #fff;
    border-radius: 20px;
    padding: 32px 28px;
    text-align: center;
    box-shadow: 0 30px 60px rgba(0,0,0,0.2);
    transform: translateY(10px);
    transition: transform 0.2s;
  }

  .popup-backdrop.show .popup {
    transform: translateY(0);
  }

  .popup-icon {
    width: 60px;
    height: 60px;
    background: #f0fdf4;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #16a34a;
    margin-bottom: 16px;
  }

  .popup h3 {
    font-size: 1.2rem;
    font-weight: 700;
    margin: 0 0 6px;
  }

  .popup p {
    font-size: 0.88rem;
    color: var(--contact-text-secondary);
    margin: 0 0 20px;
    line-height: 1.5;
  }

  .popup-close {
    padding: 10px 26px;
    border: none;
    border-radius: 100px;
    background: var(--contact-accent);
    color: #fff;
    font-weight: 600;
    font-size: 0.88rem;
    cursor: pointer;
    font-family: inherit;
  }

  @media (max-width: 768px) {
    .contact-hero h1 {
      font-size: 1.8rem;
    }
    .contact-wrapper {
      grid-template-columns: 1fr;
    }
    .contact-info-panel {
      padding: 28px 24px;
    }
    .contact-form-panel {
      padding: 28px 24px;
    }
    .form-row {
      grid-template-columns: 1fr;
    }
    .submit-row {
      flex-direction: column;
      align-items: stretch;
      text-align: center;
    }
  }
</style>

<div class="contact-page">
  {{-- Hero --}}
  <section class="contact-hero">
    <div class="contact-container">
      <span class="contact-badge">Get in Touch</span>
      <h1>Contact Our Team</h1>
      <p class="lead">We're here to help with any questions about Daleel AI.</p>
    </div>
  </section>

  {{-- Form Card --}}
  <div class="contact-container">
    <div class="contact-wrapper">
      
      {{-- Left Info Panel --}}
      <div class="contact-info-panel">
        <div class="contact-info-content">
          <span class="badge">Contact</span>
          <h2>Let's talk</h2>
          <p class="info-subtitle">Fill in the form and we'll get back to you within 24 hours.</p>
          
          <div class="contact-info-item">
            <div class="icon-circle">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </div>
            <span>noreply@daleelmentor.com</span>
          </div>
          
          <div class="contact-info-item">
            <div class="icon-circle">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <span>Response within 24h</span>
          </div>
        </div>
      </div>

      {{-- Right Form Panel --}}
      <div class="contact-form-panel">
        <h3>Send a message</h3>
        <p class="form-subtitle">We'll reply as quickly as possible.</p>

        @if ($errors->any())
          <div class="alert alert-error">Please check the form and try again.</div>
        @endif
        @if (session('enterprise_contact_error'))
          <div class="alert alert-error">{{ session('enterprise_contact_error') }}</div>
        @endif
        @if (session('enterprise_contact_success'))
          <div class="alert alert-success">Message sent! We'll be in touch shortly.</div>
        @endif

        <form id="enterpriseContactForm" method="POST" action="{{ route('enterprise.contact.send') }}">
          @csrf
          
          <div class="form-row">
            <div class="form-group">
              <label for="name">Full Name</label>
              <input type="text" id="name" name="name" class="@error('name') error @enderror" value="{{ old('name') }}" placeholder="John Doe" required>
              @error('name')<div class="error-text">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label for="email">Work Email</label>
              <input type="email" id="email" name="email" class="@error('email') error @enderror" value="{{ old('email') }}" placeholder="john@company.com" required>
              @error('email')<div class="error-text">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="form-group">
            <label for="subject">Subject</label>
            <select id="subject" name="subject" class="@error('subject') error @enderror" required>
              <option value="enterprise" @selected(old('subject') === 'enterprise')>Enterprise Solutions</option>
              <option value="support" @selected(old('subject') === 'support')>Technical Support</option>
              <option value="partnership" @selected(old('subject') === 'partnership')>Partnership Inquiry</option>
              <option value="other" @selected(old('subject') === 'other')>Other</option>
            </select>
            @error('subject')<div class="error-text">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" class="@error('message') error @enderror" rows="3" placeholder="Tell us how we can help..." required>{{ old('message') }}</textarea>
            @error('message')<div class="error-text">{{ $message }}</div>@enderror
          </div>

          <div class="submit-row">
            <span class="response-time">We respond within 24 hours</span>
            <button type="submit" class="submit-btn">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
              Send Message
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Success Popup --}}
  <div id="successPopup" class="popup-backdrop {{ session('enterprise_contact_success') ? 'show' : '' }}">
    <div class="popup">
      <div class="popup-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
      </div>
      <h3>Message Sent</h3>
      <p>Thanks for reaching out. We'll get back to you shortly.</p>
      <button class="popup-close" id="successPopupClose">Got it</button>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('enterpriseContactForm');
    const popup = document.getElementById('successPopup');
    const closeBtn = document.getElementById('successPopupClose');

    function closePopup() { popup?.classList.remove('show'); }

    form?.addEventListener('submit', function() {
      const btn = form.querySelector('.submit-btn');
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = 'Sending...';
      }
    });

    closeBtn?.addEventListener('click', closePopup);
    popup?.addEventListener('click', function(e) { if (e.target === popup) closePopup(); });
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closePopup(); });
  });
</script>
@endsection