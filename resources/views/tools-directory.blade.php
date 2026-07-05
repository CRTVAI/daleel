@extends('layouts.public')

@section('title', 'Daleel AI | AI Tools Directory')

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
  { name: "ChatGPT", category: "AI Tools", use: "Draft, summarize, ideate, and analyze work content.", lesson: "Daily Work Starter" },
  { name: "Claude", category: "AI Tools", use: "Long-form document review and structured reasoning.", lesson: "Policy and SOP Review" },
  { name: "Google Gemini", category: "AI Tools", use: "Research, writing, and workspace assistance.", lesson: "Workspace Research Flow" },
  { name: "Gmail", category: "Google Workspace", use: "Write, summarize, and prioritize email workflows.", lesson: "Inbox Productivity Flow" },
  { name: "Google Sheets", category: "Google Workspace", use: "Clean, analyze, and explain spreadsheet data.", lesson: "Spreadsheet Analysis Basics" },
  { name: "Microsoft Copilot", category: "Microsoft Office", use: "Use AI across documents, spreadsheets, email, and meetings.", lesson: "Office Workflow Basics" },
  { name: "Excel", category: "Microsoft Office", use: "Analyze data, create formulas, and summarize reports.", lesson: "Excel AI Assistant" },
  { name: "Notion", category: "Productivity", use: "Summarize notes, organize docs, and build team wikis.", lesson: "Knowledge Base Cleanup" },
  { name: "Asana", category: "Productivity", use: "Plan projects and turn work updates into clear task lists.", lesson: "Project Planning with AI" },
  { name: "Slack", category: "Communication", use: "Summarize channels, draft updates, and capture decisions.", lesson: "Team Communication Flow" },
  { name: "Zoom", category: "Communication", use: "Turn meeting transcripts into notes, tasks, and follow-ups.", lesson: "Meeting Summary Workflow" },
  { name: "Figma", category: "Design", use: "Generate design briefs, critique layouts, and organize feedback.", lesson: "Design Review Assistant" },
  { name: "Canva", category: "Design", use: "Create visual content for training, social, and internal updates.", lesson: "Fast Visual Content" },
  { name: "Zapier AI", category: "Automation", use: "Connect tools and automate repeated work steps.", lesson: "No-Code AI Automation" },
  { name: "Make", category: "Automation", use: "Build multi-step automations across teams and apps.", lesson: "Workflow Automation Builder" },
  { name: "GitHub", category: "Development", use: "Review code, summarize issues, and prepare releases.", lesson: "Developer AI Workflow" },
  { name: "VS Code", category: "Development", use: "Use AI assistance while coding, debugging, and documenting.", lesson: "Coding Assistant Basics" }
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
