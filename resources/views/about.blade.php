 @extends('layouts.public')

@section('title', 'Daleel AI | About')

@section('content')
<section class="page-hero">
  <div class="container hero-grid">
    <div>
      <p class="eyebrow">Company profile</p>
      <h1>Daleel AI helps people learn AI through the work they already do</h1>
      <p class="lead">We are building a practical AI learning layer for professionals, teams, and enterprises that need measurable adoption, not random content.</p>
    </div>
  </div>
</section>

<section class="section">
  <div class="container grid two">
    <div>
      <p class="eyebrow">Company mission</p>
      <h2>Make AI useful at work for every role</h2>
      <p class="lead">Daleel AI exists because most AI training is disconnected from the user's actual job. Our platform connects learning to role, tools, workflows, goals, and behavior so users can apply AI immediately.</p>
      <div class="inline-actions"><a class="btn primary" href="{{ url('contact') }}">Partner With Us</a><a class="btn secondary" href="{{ url('enterprise') }}">Enterprise Training</a></div>
    </div>
    <div class="profile-card">
      <div class="mock-top">Company snapshot <span class="status">Public profile</span></div>
      <div class="stat-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 24px;">
        <div class="stat-tile" style="background: var(--surface); padding: 16px; border-radius: 12px; text-align: center;"><strong>2026</strong><br><span style="font-size: 0.75rem; color: var(--muted);">public site profile</span></div>
        <div class="stat-tile" style="background: var(--surface); padding: 16px; border-radius: 12px; text-align: center;"><strong>20+</strong><br><span style="font-size: 0.75rem; color: var(--muted);">countries reached</span></div>
        <div class="stat-tile" style="background: var(--surface); padding: 16px; border-radius: 12px; text-align: center;"><strong>120+</strong><br><span style="font-size: 0.75rem; color: var(--muted);">teams trained</span></div>
        <div class="stat-tile" style="background: var(--surface); padding: 16px; border-radius: 12px; text-align: center;"><strong>10k+</strong><br><span style="font-size: 0.75rem; color: var(--muted);">lessons watched</span></div>
      </div>
    </div>
  </div>
</section>

<section class="section alt">
  <div class="container">
    <div class="section-header center"><h2>What the company provides</h2><p class="lead">A complete public profile of the product, audience, and services.</p></div>
    <div class="grid three">
      <article class="card card-body"><span class="mini-label">Feature</span><h3>Individual AI learning</h3><p class="muted">Personalized videos, saved lessons, mentor questions, and certificates for professionals.</p></article>
      <article class="card card-body"><span class="mini-label">Feature</span><h3>Team training</h3><p class="muted">Department paths, team dashboards, readiness scoring, and adoption reporting for managers.</p></article>
      <article class="card card-body"><span class="mini-label">Feature</span><h3>Custom enterprise content</h3><p class="muted">Company-specific lessons and workflows built around internal processes, policies, and tools.</p></article>
      <article class="card card-body"><span class="mini-label">Feature</span><h3>Workflow intelligence</h3><p class="muted">Optional Chrome extension signals that identify repeated work and AI opportunities.</p></article>
      <article class="card card-body"><span class="mini-label">Feature</span><h3>Partner ecosystem</h3><p class="muted">Support for learning around tools such as OpenAI, Google Cloud, Microsoft, and ElevenLabs.</p></article>
      <article class="card card-body"><span class="mini-label">Feature</span><h3>Measurable adoption</h3><p class="muted">Progress, time saved, completed learning paths, and practical AI implementation outcomes.</p></article>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-header"><p class="eyebrow">Company timeline</p><h2>How the platform grows</h2></div>
    <div class="timeline" style="display: flex; flex-direction: column; gap: 24px;">
      <article class="card timeline-item"><p class="mini-label">Phase 1</p><h3>Launch public learning library and role-based paths.</h3></article>
      <article class="card timeline-item"><p class="mini-label">Phase 2</p><h3>Add AI mentor, saved lessons, and workflow recommendations.</h3></article>
      <article class="card timeline-item"><p class="mini-label">Phase 3</p><h3>Roll out team analytics, enterprise reporting, and custom training.</h3></article>
      <article class="card timeline-item"><p class="mini-label">Phase 4</p><h3>Expand Chrome extension insights and partner ecosystem integrations.</h3></article>
    </div>
  </div>
</section>
@endsection
