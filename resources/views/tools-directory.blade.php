@extends('layouts.public')

@section('title', 'Dallel AI | AI Tools Directory')

@section('content')
<section class="page-hero">
  <div class="container hero-grid">
    <div>
      <p class="eyebrow">AI tools directory</p>
      <h1>Connect tools to practical lessons and workflows</h1>
      <p class="lead">A public directory showing how learners can discover AI tools by category and learn the best workflow for each one.</p>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="filter-toolbar" style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; flex-wrap: wrap; gap: 20px;">
      <div><p class="eyebrow">Directory</p><h2>Find tools by category</h2></div>
      <div class="search-bar" style="display: flex; gap: 12px; flex-wrap: wrap;">
        <input id="toolSearch" type="search" placeholder="Search tools" style="padding: 10px 16px; border: 1px solid var(--line); border-radius: 8px;">
        <select id="toolCategory" style="padding: 10px 16px; border: 1px solid var(--line); border-radius: 8px;"><option value="All">All categories</option></select>
      </div>
    </div>
    <div class="grid four" id="toolResults">
      <!-- To be filled by JS -->
    </div>
  </div>
</section>
@endsection

@section('scripts')
<script>
const tools = [
  { name: "ChatGPT", category: "Writing", use: "Draft, summarize, ideate, and analyze work content.", lesson: "Daily Work Starter" },
  { name: "Claude", category: "Writing", use: "Long-form document review and structured reasoning.", lesson: "Policy and SOP Review" },
  { name: "Microsoft Copilot", category: "Productivity", use: "Use AI across documents, spreadsheets, email, and meetings.", lesson: "Office Workflow Basics" },
  { name: "Google Gemini", category: "Productivity", use: "Research, writing, and workspace assistance.", lesson: "Workspace Research Flow" },
  { name: "ElevenLabs", category: "Media", use: "Voice generation for training, explainers, and localization.", lesson: "Audio Lessons at Scale" },
  { name: "Zapier AI", category: "Automation", use: "Connect tools and automate repeated work steps.", lesson: "No-Code AI Automation" },
  { name: "Notion AI", category: "Knowledge", use: "Summarize notes, organize docs, and build team wikis.", lesson: "Knowledge Base Cleanup" },
  { name: "Perplexity", category: "Research", use: "Research topics with cited source trails.", lesson: "Fast Market Research" }
];

function initTools() {
  const results = document.getElementById("toolResults");
  if (!results) return;
  const search = document.getElementById("toolSearch");
  const category = document.getElementById("toolCategory");
  
  const categories = [...new Set(tools.map(tool => tool.category))];
  categories.forEach(cat => {
    const opt = document.createElement("option");
    opt.value = cat;
    opt.textContent = cat;
    category.appendChild(opt);
  });

  const render = () => {
    const query = search.value.toLowerCase();
    const list = tools.filter(tool => {
      return `${tool.name} ${tool.category} ${tool.use}`.toLowerCase().includes(query)
        && (category.value === "All" || tool.category === category.value);
    });
    results.innerHTML = list.map(tool => `
      <article class="card card-body">
        <span class="icon-box purple" style="width: 40px; height: 40px; background: #f5f3ff; color: #7c3aed; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-weight: 800; margin-bottom: 12px;">${tool.name.slice(0, 2).toUpperCase()}</span>
        <p class="mini-label">${tool.category}</p>
        <h3>${tool.name}</h3>
        <p class="muted">${tool.use}</p>
        <span class="badge green" style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; margin-top: 12px; display: inline-block;">Lesson: ${tool.lesson}</span>
      </article>
    `).join("") || `<div class="card card-body"><h3>No tools found</h3></div>`;
  };
  [search, category].forEach(control => control.addEventListener("input", render));
  render();
}
initTools();
</script>
@endsection
