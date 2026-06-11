@extends('layouts.public')

@section('title', 'Dallel AI | Learning Paths')

@section('content')
<section class="page-hero">
  <div class="container hero-grid">
    <div>
      <p class="eyebrow">Structured paths</p>
      <h1>Role-based AI learning paths with certificates</h1>
      <p class="lead">Each path gives learners a clear sequence of lessons, practice workflows, and completion outcomes.</p>
      <div class="hero-actions">
        <a class="btn primary" href="{{ route('register') }}">Start Free</a>
        <a class="btn secondary" href="{{ url('videos') }}">Explore Videos</a>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-header center"><h2>Available learning paths</h2><p class="lead">Built for the departments companies train first.</p></div>
    <div class="grid three">
      <article class="card card-body">
        <span class="mini-label">Support</span>
        <h3>AI for Customer Support</h3>
        <p class="muted">Ticket replies, escalation summaries, quality checks</p>
        <div class="chip-row">
          <span class="chip">18 lessons</span>
          <span class="chip">4 weeks</span>
          <span class="chip">Beginner</span>
          <span class="chip">Certificate</span>
        </div>
      </article>
      <article class="card card-body">
        <span class="mini-label">Marketing</span>
        <h3>AI for Marketing</h3>
        <p class="muted">Campaign briefs, content production, research workflows</p>
        <div class="chip-row">
          <span class="chip">22 lessons</span>
          <span class="chip">5 weeks</span>
          <span class="chip">Intermediate</span>
          <span class="chip">Certificate</span>
        </div>
      </article>
      <article class="card card-body">
        <span class="mini-label">Sales</span>
        <h3>AI for Sales</h3>
        <p class="muted">Account research, follow-ups, CRM cleanup</p>
        <div class="chip-row">
          <span class="chip">16 lessons</span>
          <span class="chip">3 weeks</span>
          <span class="chip">Beginner</span>
          <span class="chip">Certificate</span>
        </div>
      </article>
      <article class="card card-body">
        <span class="mini-label">Developer</span>
        <h3>AI for Developers</h3>
        <p class="muted">Code review, debugging, test planning, documentation</p>
        <div class="chip-row">
          <span class="chip">24 lessons</span>
          <span class="chip">6 weeks</span>
          <span class="chip">Advanced</span>
          <span class="chip">Certificate</span>
        </div>
      </article>
      <article class="card card-body">
        <span class="mini-label">HR</span>
        <h3>AI for HR</h3>
        <p class="muted">Job descriptions, screening workflows, onboarding</p>
        <div class="chip-row">
          <span class="chip">15 lessons</span>
          <span class="chip">3 weeks</span>
          <span class="chip">Beginner</span>
          <span class="chip">Certificate</span>
        </div>
      </article>
      <article class="card card-body">
        <span class="mini-label">Founder</span>
        <h3>AI for Founders</h3>
        <p class="muted">Investor updates, GTM planning, founder operations</p>
        <div class="chip-row">
          <span class="chip">20 lessons</span>
          <span class="chip">5 weeks</span>
          <span class="chip">Intermediate</span>
          <span class="chip">Certificate</span>
        </div>
      </article>
    </div>
  </div>
</section>

<section class="section alt">
  <div class="container grid two">
    <div>
      <p class="eyebrow">Path builder</p>
      <h2>Public onboarding preview</h2>
      <p class="lead">Select a role and experience level to see a sample recommended first path.</p>
      <div class="form-grid">
        <div class="field"><label for="pathRole">Role</label><select id="pathRole"><option>Support</option><option>Marketing</option><option>Sales</option><option>Developer</option><option>HR</option><option>Founder</option></select></div>
        <div class="field"><label for="pathLevel">Level</label><select id="pathLevel"><option>Beginner</option><option>Intermediate</option><option>Advanced</option></select></div>
      </div>
    </div>
    <div class="card card-body" id="pathBuilderResult">
        <p class="mini-label">Recommended path</p>
        <h3>AI for Customer Support</h3>
        <p class="muted">Ticket replies, escalation summaries, quality checks</p>
        <div class="chip-row"><span class="chip">18 lessons</span><span class="chip">4 weeks</span><span class="chip">Beginner</span><span class="chip">Certificate</span></div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
const paths = [
  { title: "AI for Customer Support", role: "Support", lessons: 18, time: "4 weeks", level: "Beginner", focus: "Ticket replies, escalation summaries, quality checks" },
  { title: "AI for Marketing", role: "Marketing", lessons: 22, time: "5 weeks", level: "Intermediate", focus: "Campaign briefs, content production, research workflows" },
  { title: "AI for Sales", role: "Sales", lessons: 16, time: "3 weeks", level: "Beginner", focus: "Account research, follow-ups, CRM cleanup" },
  { title: "AI for Developers", role: "Developer", lessons: 24, time: "6 weeks", level: "Advanced", focus: "Code review, debugging, test planning, documentation" },
  { title: "AI for HR", role: "HR", lessons: 15, time: "3 weeks", level: "Beginner", focus: "Job descriptions, screening workflows, onboarding" },
  { title: "AI for Founders", role: "Founder", lessons: 20, time: "5 weeks", level: "Intermediate", focus: "Investor updates, GTM planning, founder operations" }
];

function initPathBuilder() {
  const role = document.getElementById("pathRole");
  const level = document.getElementById("pathLevel");
  const result = document.getElementById("pathBuilderResult");
  if (!role || !level || !result) return;
  const render = () => {
    const selected = paths.find(path => path.role === role.value) || paths[0];
    result.innerHTML = `<p class="mini-label">Recommended path</p><h3>${selected.title}</h3><p class="muted">${selected.focus}</p><div class="chip-row"><span class="chip">${selected.lessons} lessons</span><span class="chip">${selected.time}</span><span class="chip">${level.value}</span><span class="chip">Certificate</span></div>`;
  };
  [role, level].forEach(control => control.addEventListener("change", render));
}
initPathBuilder();
</script>
@endsection
