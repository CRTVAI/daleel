@extends('layouts.public')

@section('title', 'Daleel AI | Help Center')

@section('content')
<section class="page-hero">
  <div class="container hero-grid">
    <div>
      <p class="eyebrow">Help center</p>
      <h1>Answers for learners, teams, and admins</h1>
      <p class="lead">Search common questions about learning paths, pricing, extension privacy, enterprise analytics, and account setup.</p>
      <div class="hero-actions">
        <a class="btn primary" href="{{ url('contact') }}">Contact Support</a>
        <a class="btn secondary" href="{{ url('privacy') }}">Read Privacy Policy</a>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="filter-toolbar" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; flex-wrap: wrap; gap: 20px;">
      <div><p class="eyebrow">Support</p><h2>Search help topics</h2></div>
      <div class="search-bar" style="display: flex; gap: 12px; flex-wrap: wrap;">
        <input id="helpSearch" type="search" placeholder="Search help center" style="padding: 10px 16px; border: 1px solid var(--line); border-radius: 8px;">
        <select id="helpTopic" style="padding: 10px 16px; border: 1px solid var(--line); border-radius: 8px;"><option value="All">All topics</option></select>
      </div>
    </div>
    <div class="accordion" id="helpResults">
      <!-- To be filled by JS -->
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
const helpTopics = [
  ["Getting Started", "How do recommendations work?", "Daleel AI uses your role, goals, selected tools, completed lessons, saved items, and optional workflow signals to recommend practical lessons."],
  ["Getting Started", "Can individuals use the platform?", "Yes. Individual learners can use role-based paths, video lessons, saved lessons, and the AI mentor without joining a company workspace."],
  ["Enterprise", "What can managers track?", "Managers can track progress, completed paths, AI readiness, adoption by department, and estimated productivity impact."],
  ["Enterprise", "Can we request custom training?", "Yes. Companies can request training content for internal tools, policies, departments, or workflows."],
  ["Chrome Extension", "Does the extension read private content?", "The extension is designed to track work behavior signals such as tools used, repeated task patterns, and time allocation, not private content."],
  ["Billing", "Is there a free plan?", "Yes. The free plan includes starter lessons, saved lessons, and limited AI mentor questions."],
  ["Billing", "Can teams switch plans later?", "Yes. Teams can move between plans or talk to sales for enterprise needs."],
  ["Privacy", "Can users pause workflow analysis?", "Yes. Users and companies can control extension usage and pause or limit tracking based on policy."]
];

function initHelp() {
  const results = document.getElementById("helpResults");
  if (!results) return;
  const search = document.getElementById("helpSearch");
  const topic = document.getElementById("helpTopic");
  
  const groups = [...new Set(helpTopics.map(item => item[0]))];
  groups.forEach(group => {
    const opt = document.createElement("option");
    opt.value = group;
    opt.textContent = group;
    topic.appendChild(opt);
  });

  const render = () => {
    const query = search.value.toLowerCase();
    const list = helpTopics.filter(([group, question, answer]) => {
      return `${group} ${question} ${answer}`.toLowerCase().includes(query)
        && (topic.value === "All" || group === topic.value);
    });
    results.innerHTML = list.map(([group, question, answer]) => `
      <div class="accordion-item">
        <button class="accordion-button" type="button">${group}: ${question}<span>+</span></button>
        <div class="accordion-panel">${answer}</div>
      </div>
    `).join("") || `<div class="card card-body"><h3>No topics found</h3></div>`;
    
    document.querySelectorAll(".accordion-button").forEach(button => {
      button.onclick = () => {
        button.closest(".accordion-item").classList.toggle("open");
      };
    });
  };
  [search, topic].forEach(control => control.addEventListener("input", render));
  render();
}
initHelp();
</script>
@endsection
