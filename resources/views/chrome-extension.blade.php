@extends('layouts.public')

@section('title', 'Daleel AI | Chrome Extension')

@section('content')
<section class="page-hero">
  <div class="container hero-grid">
    <div>
      <p class="eyebrow">Optional workflow signals</p>
      <h1>Chrome extension for smarter AI recommendations</h1>
      <p class="lead">The extension helps identify work patterns, active tools, repeated tasks, and time spent across platforms so recommendations become more useful.</p>
      <div class="hero-actions">
        <a class="btn primary" href="{{ route('login') }}">Install Extension</a>
        <a class="btn secondary" href="{{ url('privacy') }}">Read Privacy Policy</a>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container grid two">
    <article class="card card-body"><span class="mini-label">Feature</span><h3>Behavior signals, not private content</h3><p class="muted">The extension focuses on productivity patterns, tool usage, repeated actions, and timing signals.</p></article>
    <article class="card card-body"><span class="mini-label">Feature</span><h3>AI opportunity detection</h3><p class="muted">Repeated manual work can trigger recommended lessons, templates, or automation workflows.</p></article>
    <article class="card card-body"><span class="mini-label">Feature</span><h3>User and company controls</h3><p class="muted">Individuals or admins can enable, pause, or configure the extension based on company policy.</p></article>
    <article class="card card-body"><span class="mini-label">Feature</span><h3>Useful productivity insights</h3><p class="muted">Teams can see where training may save time without needing private work content.</p></article>
  </div>
</section>

<section class="section alt">
  <div class="container grid two">
    <div>
      <p class="eyebrow">Privacy checklist</p>
      <h2>Clear controls for rollout conversations</h2>
      <div class="accordion">
        <div class="accordion-item">
          <button class="accordion-button" type="button">What the extension helps detect<span>+</span></button>
          <div class="accordion-panel">Repeated tools, work sessions, task categories, and opportunities for AI assistance.</div>
        </div>
        <div class="accordion-item">
          <button class="accordion-button" type="button">What the extension should not collect<span>+</span></button>
          <div class="accordion-panel">Private document content, passwords, personal messages, or unrelated browsing content.</div>
        </div>
        <div class="accordion-item">
          <button class="accordion-button" type="button">How teams can roll it out<span>+</span></button>
          <div class="accordion-panel">Start with a pilot group, explain the signals collected, then expand by department once value is clear.</div>
        </div>
      </div>
    </div>
    <div class="mock-dashboard">
      <div class="mock-top">Extension insight <span class="status">Detected</span></div>
      <div class="card-body" style="padding: 24px; background: white; border-radius: 0 0 12px 12px;">
        <h3>Weekly spreadsheet cleanup appears 4 times this month</h3>
        <p class="muted">Suggested lesson: Automate repetitive spreadsheet preparation with AI prompts and no-code tools.</p>
        <div class="badge-row" style="display: flex; gap: 8px; margin-top: 16px;">
          <span class="badge primary" style="background: var(--primary); color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem;">Pattern</span>
          <span class="badge" style="background: var(--surface); padding: 4px 8px; border-radius: 4px; font-size: 0.75rem;">Sheets</span>
          <span class="badge green" style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem;">Save 2.5h</span>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
document.querySelectorAll(".accordion-button").forEach(button => {
  button.addEventListener("click", () => {
    const item = button.closest(".accordion-item");
    item.classList.toggle("open");
  });
});
</script>
@endsection
