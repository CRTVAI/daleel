@extends('layouts.public')

@section('title', 'Daleel AI | Resources')

@section('content')
<section class="page-hero">
  <div class="container hero-grid">
    <div>
      <p class="eyebrow">Resources</p>
      <h1>Guides, templates, and AI adoption resources</h1>
      <p class="lead">Public resources for learners, managers, IT teams, and companies planning AI training.</p>
      <div class="hero-actions">
        <a class="btn primary" href="#resources">Explore Resources</a>
        <a class="btn secondary" href="{{ url('help-center') }}">Help Center</a>
      </div>
    </div>
  </div>
</section>

<section class="section" id="resources">
  <div class="container">
    <div class="filter-toolbar" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; flex-wrap: wrap; gap: 20px;">
      <div><p class="eyebrow">Resource library</p><h2>Browse public resources</h2></div>
      <div class="search-bar" style="display: flex; gap: 12px; flex-wrap: wrap;">
        <input id="resourceSearch" type="search" placeholder="Search resources" style="padding: 10px 16px; border: 1px solid var(--line); border-radius: 8px;">
        <select id="resourceType" style="padding: 10px 16px; border: 1px solid var(--line); border-radius: 8px;"><option value="All">All types</option></select>
        <select id="resourceAudience" style="padding: 10px 16px; border: 1px solid var(--line); border-radius: 8px;"><option value="All">All audiences</option></select>
      </div>
    </div>
    <div class="grid three" id="resourceResults">
      <!-- To be filled by JS -->
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
const resources = [
  { title: "AI Adoption Playbook for Teams", type: "Guide", audience: "Managers", minutes: 18 },
  { title: "Best Prompt Patterns for Daily Work", type: "Article", audience: "Learners", minutes: 9 },
  { title: "How to Measure Time Saved With AI", type: "Guide", audience: "Enterprise", minutes: 14 },
  { title: "Chrome Extension Privacy Overview", type: "Article", audience: "IT", minutes: 7 },
  { title: "AI Training Launch Checklist", type: "Template", audience: "Managers", minutes: 11 },
  { title: "Building a Department Prompt Library", type: "Article", audience: "Teams", minutes: 12 }
];

function initResources() {
  const results = document.getElementById("resourceResults");
  if (!results) return;
  const search = document.getElementById("resourceSearch");
  const type = document.getElementById("resourceType");
  const audience = document.getElementById("resourceAudience");
  
  const types = [...new Set(resources.map(item => item.type))];
  const audiences = [...new Set(resources.map(item => item.audience))];
  
  types.forEach(t => {
    const opt = document.createElement("option");
    opt.value = t; opt.textContent = t; type.appendChild(opt);
  });
  audiences.forEach(a => {
    const opt = document.createElement("option");
    opt.value = a; opt.textContent = a; audience.appendChild(opt);
  });

  const render = () => {
    const query = search.value.toLowerCase();
    const list = resources.filter(item => {
      return `${item.title} ${item.type} ${item.audience}`.toLowerCase().includes(query)
        && (type.value === "All" || item.type === type.value)
        && (audience.value === "All" || item.audience === audience.value);
    });
    results.innerHTML = list.map(item => `
      <article class="card card-body">
        <p class="mini-label">${item.type}</p>
        <h3>${item.title}</h3>
        <p class="muted">For ${item.audience}. Estimated read time: ${item.minutes} minutes.</p>
        <a class="btn text" href="{{ url('contact') }}">Request Resource</a>
      </article>
    `).join("") || `<div class="card card-body"><h3>No resources found</h3></div>`;
  };
  [search, type, audience].forEach(control => control.addEventListener("input", render));
  render();
}
initResources();
</script>
@endsection
