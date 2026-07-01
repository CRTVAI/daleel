const navItems = [
  ["how-it-works", "How It Works", "how-it-works"],
  ["videos", "Lessons", "videos"],
  ["enterprise", "Enterprise", "enterprise"],
  ["success-stories", "Success Stories", "success-stories"],
  ["pricing", "Pricing", "pricing"]
];

const videoLessons = [
  { title: "How to Use ChatGPT for Daily Work", category: "Productivity", role: "All Roles", level: "Beginner", duration: "14 min", views: "18.4k", thumb: "https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&q=80&w=600" },
  { title: "Automate Repetitive Tasks with AI", category: "Automation", role: "Operations", level: "Intermediate", duration: "21 min", views: "14.2k", thumb: "/images/dashboard/interview.png" },
  { title: "AI for Customer Support Teams", category: "Support", role: "Support", level: "Beginner", duration: "18 min", views: "12.8k", thumb: "https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&q=80&w=600" },
  { title: "Build Better Prompts for Marketing", category: "Marketing", role: "Marketing", level: "Beginner", duration: "16 min", views: "11.7k", thumb: "https://images.unsplash.com/photo-1557838923-2985c318be48?auto=format&fit=crop&q=80&w=600" },
  { title: "Use AI to Summarize Meetings", category: "Operations", role: "Operations", level: "Beginner", duration: "11 min", views: "10.3k", thumb: "https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&q=80&w=600" },
  { title: "AI Tools for Founders", category: "Leadership", role: "Founder", level: "Intermediate", duration: "24 min", views: "9.6k", thumb: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=600" },
  { title: "Clean CRM Notes with AI", category: "Sales", role: "Sales", level: "Beginner", duration: "15 min", views: "7.1k", thumb: "https://images.unsplash.com/photo-1551288049-bbbda5366392?auto=format&fit=crop&q=80&w=600" },
  { title: "Create HR Onboarding Content", category: "HR", role: "HR", level: "Beginner", duration: "17 min", views: "5.4k", thumb: "/images/dashboard/interview.png" },
  { title: "Use AI for Code Review Prep", category: "Development", role: "Developer", level: "Advanced", duration: "22 min", views: "4.9k", thumb: "https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&q=80&w=600" }
];

const paths = [
  { title: "AI for Customer Support", role: "Support", lessons: 18, time: "4 weeks", level: "Beginner", focus: "Ticket replies, escalation summaries, quality checks" },
  { title: "AI for Marketing", role: "Marketing", lessons: 22, time: "5 weeks", level: "Intermediate", focus: "Campaign briefs, content production, research workflows" },
  { title: "AI for Sales", role: "Sales", lessons: 16, time: "3 weeks", level: "Beginner", focus: "Account research, follow-ups, CRM cleanup" },
  { title: "AI for Developers", role: "Developer", lessons: 24, time: "6 weeks", level: "Advanced", focus: "Code review, debugging, test planning, documentation" },
  { title: "AI for HR", role: "HR", lessons: 15, time: "3 weeks", level: "Beginner", focus: "Job descriptions, screening workflows, onboarding" },
  { title: "AI for Founders", role: "Founder", lessons: 20, time: "5 weeks", level: "Intermediate", focus: "Investor updates, GTM planning, founder operations" }
];

const stories = [
  { team: "Customer Support Team", industry: "Support", result: "Saved 200+ hours monthly", metric: "200h", challenge: "Repeated ticket responses slowed service quality.", body: "The team built reply templates, escalation summaries, and quality review workflows." },
  { team: "Marketing Department", industry: "Marketing", result: "Increased content speed by 60%", metric: "60%", challenge: "Campaign briefs and content variations took too long.", body: "Marketing adopted prompt libraries, creative QA checklists, and content repurposing flows." },
  { team: "Operations Team", industry: "Operations", result: "Reduced manual work by 40%", metric: "40%", challenge: "Weekly reports required manual copy and summary work.", body: "Operations used AI summaries, spreadsheet cleanup prompts, and reporting templates." },
  { team: "Sales Enablement", industry: "Sales", result: "Cut account prep time by 35%", metric: "35%", challenge: "Reps prepared for calls inconsistently.", body: "The sales team created role-based account research and follow-up workflows." },
  { team: "People Operations", industry: "HR", result: "Onboarding content created 2x faster", metric: "2x", challenge: "HR needed consistent onboarding materials across departments.", body: "HR used AI to draft role guides, policy explainers, and manager checklists." },
  { team: "Product Engineering", industry: "Development", result: "Review prep time down 28%", metric: "28%", challenge: "Developers spent too much time preparing release notes and review context.", body: "Engineers learned code explanation, test planning, and changelog workflows." }
];

const resources = [
  { title: "AI Adoption Playbook for Teams", type: "Guide", audience: "Managers", minutes: 18 },
  { title: "Best Prompt Patterns for Daily Work", type: "Article", audience: "Learners", minutes: 9 },
  { title: "How to Measure Time Saved With AI", type: "Guide", audience: "Enterprise", minutes: 14 },
  { title: "Chrome Extension Privacy Overview", type: "Article", audience: "IT", minutes: 7 },
  { title: "AI Training Launch Checklist", type: "Template", audience: "Managers", minutes: 11 },
  { title: "Building a Department Prompt Library", type: "Article", audience: "Teams", minutes: 12 }
];

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

function shellNav(activePage) {
  const links = navItems.map(([key, label, href]) => {
    const active = key === activePage ? " active" : "";
    return `<a class="${active}" href="${href}">${label}</a>`;
  }).join("");

  return `
    <header class="site-nav">
      <div class="container nav-inner">
        <a class="brand" href="/" aria-label="Daleel AI home">
          <span class="brand-mark">DA</span>
          <span class="brand-copy"><strong>Daleel AI</strong><small>by Creative AI</small></span>
        </a>
        <nav class="nav-links" id="siteNavLinks" aria-label="Public pages">${links}</nav>
        <div class="nav-actions">
          <a class="btn secondary" href="login">Login</a>
          <a class="btn primary" href="register">Start Free</a>
        </div>
        <button class="menu-toggle" id="siteMenuToggle" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="siteNavLinks">
          <span></span><span></span><span></span>
        </button>
      </div>
    </header>
  `;
}

function shellFooter() {
  return `
    <footer class="site-footer">
      <div class="container">
        <div class="footer-grid">
          <div class="footer-brand">
            <a class="brand" href="/"><span class="brand-mark">DA</span><span class="brand-copy"><strong>Daleel AI</strong><small>by Creative AI</small></span></a>
            <p>AI lessons matched to daily tasks, repeated work, and real workplace goals.</p>
            <form class="newsletter" data-demo-form>
              <input aria-label="Email for AI tips" placeholder="Get AI workflow tips">
              <button class="btn primary" type="submit">Join</button>
              <div class="form-message">Thanks. Newsletter signup is ready for backend integration.</div>
            </form>
          </div>
          <div>
            <h3>Product</h3>
            <a href="how-it-works">How It Works</a>
            <a href="videos">Video Lessons</a>
            <a href="learning-paths">Learning Paths</a>
            <a href="chrome-extension">Chrome Extension</a>
          </div>
          <div>
            <h3>Enterprise</h3>
            <a href="enterprise">Team Training</a>
            <a href="enterprise#analytics">Analytics</a>
            <a href="success-stories">Success Stories</a>
            <a href="contact">Book Demo</a>
          </div>
          <div>
            <h3>Resources</h3>
            <a href="blog">Blog</a>
            <a href="tools-directory">AI Tools Directory</a>
            <a href="help-center">Help Center</a>
            <a href="ai-mentor">AI Mentor</a>
          </div>
          <div>
            <h3>Company</h3>
            <a href="about">About</a>
            <a href="pricing">Pricing</a>
            <a href="contact">hello@Daleel.ai</a>
            <a href="terms">Terms</a>
            <a href="privacy">Privacy Policy</a>
            <a href="cookies">Cookie Policy</a>
          </div>
        </div>
        <div class="footer-bottom">
          <span>&copy; 2026 Daleel AI by Creative AI. All rights reserved.</span>
          <div class="footer-socials">
            <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
            <a href="#" aria-label="X"><i class="bi bi-twitter-x"></i></a>
            <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
          </div>
        </div>
      </div>
    </footer>
  `;
}

function hero({ eyebrow, title, text, primary = "Start Free", primaryHref = "register", secondary = "Book Demo", secondaryHref = "contact", mock = "" }) {
  return `
    <section class="page-hero">
      <div class="container hero-grid">
        <div>
          <p class="eyebrow">${eyebrow}</p>
          <h1>${title}</h1>
          <p class="lead">${text}</p>
          <div class="hero-actions">
            <a class="btn primary" href="${primaryHref}">${primary}</a>
            <a class="btn secondary" href="${secondaryHref}">${secondary}</a>
          </div>
        </div>
        ${mock || dashboardMock()}
      </div>
    </section>
  `;
}

function dashboardMock() {
  return `
    <aside class="product-preview" aria-label="Daleel AI product preview">
      <div class="mock-top">Daleel AI workspace <span class="status">Behavior insight on</span></div>
      <div class="preview-content" style="padding: 24px;">
        <div class="profile-row" style="display: flex; gap: 12px; margin-bottom: 20px; align-items: center;">
          <span class="avatar" style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800;">MH</span>
          <div><strong>Maya, Operations Manager</strong><br><small class="muted" style="font-size: 0.75rem;">Uses Sheets, Meet, Gmail, ChatGPT</small></div>
        </div>
        <div class="preview-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
          <div class="preview-tile large" style="grid-column: 1 / -1; background: var(--surface); padding: 16px; border-radius: 12px;">
            <p class="mini-label" style="font-size: 0.65rem;">Recommended lesson</p>
            <strong style="display: block; margin-bottom: 8px;">Reduce weekly reporting with AI summaries</strong>
            <div class="progress" style="height: 6px; background: #e2e8f0; border-radius: 3px; overflow: hidden;"><span style="display: block; width: 68%; height: 100%; background: var(--primary);"></span></div>
            <small class="muted" style="font-size: 0.7rem; margin-top: 4px; display: block;">68% path progress</small>
          </div>
          <div class="preview-tile" style="background: var(--surface); padding: 12px; border-radius: 12px;">
            <p class="mini-label" style="font-size: 0.65rem;">Saved</p>
            <strong style="font-size: 1.25rem;">7.5h</strong>
            <small class="muted" style="font-size: 0.7rem; display: block;">This week</small>
          </div>
          <div class="preview-tile" style="background: var(--surface); padding: 12px; border-radius: 12px;">
            <p class="mini-label" style="font-size: 0.65rem;">Detected</p>
            <strong style="font-size: 1.25rem;">14</strong>
            <small class="muted" style="font-size: 0.7rem; display: block;">Opportunities</small>
          </div>
        </div>
        <div class="activity-insight" style="border-top: 1px solid var(--line); padding-top: 16px;">
          <strong style="color: var(--primary); font-size: 0.85rem;">Chrome insight</strong>
          <p class="muted" style="font-size: 0.85rem; margin-top: 4px;">Repeated spreadsheet cleanup detected 4 times this month.</p>
        </div>
      </div>
    </aside>
  `;
}

function homeProductMock() {
  return dashboardMock();
}

function lessonPreviewMock() {
  return `
    <aside class="product-preview lesson-preview" aria-label="Lesson preview">
      <div class="mock-top">Featured lesson <span class="status">Most viewed</span></div>
      <div class="lesson-thumb"><span>AI workflow</span></div>
      <div class="card-body">
        <h3>How to Use ChatGPT for Daily Work</h3>
        <p class="muted">Learn repeatable prompts for emails, summaries, reports, and meeting follow-ups.</p>
        <div class="chip-row"><span class="chip">14 min</span><span class="chip">18.4k views</span><span class="chip">Beginner</span></div>
      </div>
    </aside>
  `;
}

function enterprisePreviewMock() {
  return `
    <aside class="product-preview" aria-label="Enterprise manager dashboard">
      <div class="mock-top">Manager dashboard <span class="status">Q2</span></div>
      <div class="preview-grid">
        <div class="preview-tile"><span class="tile-label">Employees trained</span><strong>128</strong><small>Across 6 teams</small></div>
        <div class="preview-tile"><span class="tile-label">Hours saved</span><strong>420</strong><small>This month</small></div>
        <div class="preview-tile"><span class="tile-label">Completion</span><strong>76%</strong><small>Learning paths</small></div>
        <div class="preview-tile"><span class="tile-label">Adoption score</span><strong>82</strong><small>Out of 100</small></div>
      </div>
      <div class="department-lines">
        <span>Support <b style="width: 86%;"></b></span>
        <span>Marketing <b style="width: 74%;"></b></span>
        <span>Operations <b style="width: 68%;"></b></span>
      </div>
    </aside>
  `;
}

function pricingPreviewMock() {
  return `
    <aside class="product-preview" aria-label="Plan recommendation">
      <div class="mock-top">Plan recommendation <span class="status">Based on need</span></div>
      <div class="card-body">
        <p class="mini-label">Best next step</p>
        <h3>Start free. Upgrade when team analytics matter.</h3>
        <p class="muted">Individuals can explore lessons first. Teams unlock dashboards, readiness scoring, and custom content.</p>
        <div class="recommendation-list">
          <span>Free for first lessons</span>
          <span>Pro for individual growth</span>
          <span>Team for manager visibility</span>
        </div>
      </div>
    </aside>
  `;
}

function workflowPreviewMock() {
  return `
    <aside class="product-preview" aria-label="Recommendation workflow preview">
      <div class="mock-top">Recommendation engine <span class="status">Step 3 of 6</span></div>
      <div class="flow-preview">
        <span>Role profile</span>
        <span>Tool behavior</span>
        <span>Repeated tasks</span>
        <strong>Lesson match: 94%</strong>
      </div>
    </aside>
  `;
}

const templates = {
  home: () => `
    ${hero({
      eyebrow: "AI learning for real work",
      title: "Learn AI through the work you already do",
      text: "Daleel AI recommends practical lessons from your role, tools, behavior signals, and repeated tasks so learning turns into daily AI workflows.",
      primary: "Start Free",
      primaryHref: "register",
      secondary: "Book Demo",
      secondaryHref: "contact",
      mock: homeProductMock()
    })}
    <section class="section metrics-section">
      <div class="container metrics-grid" style="grid-template-columns: repeat(6, 1fr);">
        <div><strong>1,500+</strong><span>active learners</span></div>
        <div><strong>10k+</strong><span>lessons watched</span></div>
        <div><strong>5,000+</strong><span>hours saved</span></div>
        <div><strong>320+</strong><span>AI workflows recommended</span></div>
        <div><strong>120+</strong><span>teams trained</span></div>
        <div><strong>31%</strong><span>avg. productivity lift</span></div>
      </div>
    </section>
    <section class="section">
      <div class="container">
        <div class="section-header center">
          <p class="eyebrow">Partners & Ecosystem</p>
          <h2>Built for practical AI adoption</h2>
          <p class="lead">Daleel AI integrates with the world's leading AI technologies to provide practical training in the tools your team uses daily.</p>
        </div>
        <div class="grid six ecosystem-grid">
          <div class="eco-card">
            <i class="bi bi-cpu"></i>
            <small>Client</small>
            <strong>Creative AI</strong>
          </div>
          <div class="eco-card">
            <i class="bi bi-building"></i>
            <small>Client</small>
            <strong>CsMena</strong>
          </div>
          <div class="eco-card">
            <i class="bi bi-chat-left-dots"></i>
            <small>AI Tool</small>
            <strong>OpenAI</strong>
          </div>
          <div class="eco-card">
            <i class="bi bi-cloud"></i>
            <small>Technology</small>
            <strong>Google Cloud</strong>
          </div>
          <div class="eco-card">
            <i class="bi bi-windows"></i>
            <small>Technology</small>
            <strong>Microsoft</strong>
          </div>
          <div class="eco-card">
            <i class="bi bi-mic"></i>
            <small>AI Tool</small>
            <strong>ElevenLabs</strong>
          </div>
        </div>
      </div>
    </section>
    <section class="section alt">
      <div class="container">
        <div class="section-header center">
          <p class="eyebrow">Most viewed lessons</p>
          <h2>Practical lessons people use at work</h2>
          <p class="lead">Short videos focused on repeated tasks, tools, and role-specific workflows.</p>
        </div>
        <div class="grid three">
          ${videoLessons.slice(0, 3).map((lesson, index) => lessonCard(lesson, index)).join("")}
        </div>
        <div class="inline-actions center-actions"><a class="btn secondary" href="videos">Browse Lessons</a></div>
      </div>
    </section>
    <section class="section">
      <div class="container">
        <div class="section-header center">
          <p class="eyebrow">How Daleel AI works</p>
          <h2>Not random courses. Recommendations from actual work behavior.</h2>
        </div>
        <div class="grid four">
          ${feature("Profile the learner", "Understand role, goals, tools, and current AI level.")}
          ${feature("Detect repeated work", "Use optional workflow signals to spot tasks AI can improve.")}
          ${feature("Recommend lessons", "Match practical videos and templates to real daily needs.")}
          ${feature("Measure progress", "Track completed lessons, saved time, and adoption growth.")}
        </div>
      </div>
    </section>
    <section class="section alt">
      <div class="container">
        <div class="section-header center">
          <p class="eyebrow">Choose your path</p>
          <h2>Practical AI for every level</h2>
        </div>
        <div class="grid two path-selection">
          <div class="card path-card individuals">
            <div class="path-icon"><i class="bi bi-person-badge"></i></div>
            <p class="eyebrow">For individuals</p>
            <h3>Learn the next AI workflow your job actually needs</h3>
            <p class="lead">Get a personalized feed, save useful lessons, ask the AI mentor questions, and turn repeated tasks into AI-powered workflows.</p>
            <div class="path-footer">
              <a class="btn primary" href="register">Start learning free</a>
              <a class="btn text" href="learning-paths">View paths <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
          <div class="card path-card companies dark">
            <div class="path-icon"><i class="bi bi-building"></i></div>
            <p class="eyebrow">For companies</p>
            <h3>Train employees and measure AI adoption</h3>
            <p class="lead">Give teams practical AI training while managers see progress, readiness, saved time, and department-level adoption.</p>
            <ul class="feature-list white">
              <li>Team dashboards & reporting</li>
              <li>Custom department learning paths</li>
              <li>Enterprise AI readiness scoring</li>
            </ul>
            <div class="path-footer">
              <a class="btn primary" href="contact">Book demo</a>
              <a class="btn secondary" href="enterprise">Explore enterprise</a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="section chrome-showcase">
      <div class="container grid two">
        <div class="reveal-content">
          <p class="eyebrow">Chrome Extension Insights</p>
          <h2>Turn repeated work into proactive learning</h2>
          <p class="lead">Our optional extension anonymously detects repeated tasks across your browser and suggests the exact AI tool or lesson to automate them.</p>
          <div class="opportunity-list">
            <div class="opp-item"><i class="bi bi-magic"></i> <span>Detected repetitive email drafting</span></div>
            <div class="opp-item"><i class="bi bi-clock"></i> <span>Spent 4h on manual data entry</span></div>
            <div class="opp-item"><i class="bi bi-lightning-charge"></i> <span>New AI workflow available</span></div>
          </div>
          <div class="inline-actions"><a class="btn primary" href="login">Install Extension</a><a class="btn text" href="chrome-extension">View privacy policy <i class="bi bi-arrow-right"></i></a></div>
        </div>
        <div class="insight-feed-visual">
          <div class="feed-header">
            <strong>Daleel Proactive Insights</strong>
            <span class="pulse-dot"></span>
          </div>
          <div class="feed-items">
            <div class="feed-card">
              <div class="feed-icon"><i class="bi bi-table"></i></div>
              <div class="feed-body">
                <strong>Spreadsheet Cleanup</strong>
                <p>Repeated pattern detected in Google Sheets. You can save ~1.5h weekly.</p>
                <span class="feed-badge">Recommended: Zapier AI Path</span>
              </div>
            </div>
            <div class="feed-card active">
              <div class="feed-icon"><i class="bi bi-envelope-heart"></i></div>
              <div class="feed-body">
                <strong>Client Communication</strong>
                <p>Detected 12 similar follow-up emails drafted today.</p>
                <span class="feed-badge">Recommended: ChatGPT Prompt Template</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="section alt">
      <div class="container">
        <div class="section-header center">
          <p class="eyebrow">Success stories</p>
          <h2>Teams use Daleel AI to reduce repeated work</h2>
        </div>
        <div class="grid three">
          ${stories.slice(0, 3).map(storyCard).join("")}
        </div>
        <div class="inline-actions center-actions"><a class="btn secondary" href="success-stories">View Success Stories</a></div>
      </div>
    </section>
    <section class="section">
      <div class="container final-band">
        <div>
          <p class="eyebrow">Get started</p>
          <h2>Start learning AI that improves daily work</h2>
          <p class="lead">Individuals start free. Companies can book a guided demo.</p>
        </div>
        <div class="inline-actions"><a class="btn primary" href="register">Start Free</a><a class="btn secondary" href="contact">Book Demo</a></div>
      </div>
    </section>
  `,

  pricing: () => `
    <header class="page-hero bg-pricing py-5">
      <div class="container text-center pt-5">
        <div class="eyebrow fade-in">Pricing</div>
        <h1 class="display-3 fw-bold mb-3 fade-up">Start free. <span class="text-gradient">Upgrade</span> when AI training needs proof.</h1>
      </div>
    </header>

    <section class="section pt-0">
      <div class="container">
        <div class="grid four mt-5">
          ${priceCard("Free", "For exploring practical AI lessons.", 0, 0, ["Starter learning path", "5 AI mentor questions", "Save up to 10 lessons", "Community resources"], "Start Free")}
          ${priceCard("Pro Learner", "For individuals applying AI every week.", 19, 15, ["Full video library", "Unlimited saved lessons", "AI mentor questions", "Certificates"], "Start Pro")}
          ${priceCard("Team", "For departments training employees.", 49, 39, ["Team dashboard", "Progress tracking", "Department paths", "Readiness scoring"], "Start Team", true)}
          ${priceCard("Enterprise", "Best for company-wide AI adoption.", "Custom", "Custom", ["Custom content", "ROI reporting", "SSO and admin controls", "Dedicated success manager"], "Book Demo")}
        </div>
      </div>
    </section>

    <section class="section border-top">
      <div class="container">
        <div class="section-header center mb-5">
          <h2>Compare public plans</h2>
          <p class="lead text-muted">A deep dive into the capabilities unlocked with each tier.</p>
        </div>
        
        <div class="comparison-modern shadow-sm rounded-4 bg-white overflow-hidden border">
          <div class="comp-row head d-none d-lg-flex">
            <div class="comp-feature">Feature Comparison</div>
            <div class="comp-val">Free</div>
            <div class="comp-val">Pro</div>
            <div class="comp-val">Team</div>
            <div class="comp-val">Enterprise</div>
          </div>
          
          <div class="comp-row">
            <div class="comp-feature">Video Lesson Library</div>
            <div class="comp-val gray">Starter</div>
            <div class="comp-val weight-800">Full</div>
            <div class="comp-val weight-800">Full</div>
            <div class="comp-val weight-800 text-primary">Custom+</div>
          </div>
          <div class="comp-row">
            <div class="comp-feature">AI Mentor Access</div>
            <div class="comp-val gray">5 / mo</div>
            <div class="comp-val">Unlimited</div>
            <div class="comp-val">Unlimited</div>
            <div class="comp-val">Managed</div>
          </div>
          
          <div class="comp-row">
            <div class="comp-feature">Admin Dashboard</div>
            <div class="comp-val gray"><i class="bi bi-dash"></i></div>
            <div class="comp-val gray"><i class="bi bi-dash"></i></div>
            <div class="comp-val"><i class="bi bi-check-circle-fill text-success"></i></div>
            <div class="comp-val">Advanced</div>
          </div>
          <div class="comp-row">
            <div class="comp-feature">Custom Training Paths</div>
            <div class="comp-val gray"><i class="bi bi-dash"></i></div>
            <div class="comp-val gray"><i class="bi bi-dash"></i></div>
            <div class="comp-val gray">Add-on</div>
            <div class="comp-val"><i class="bi bi-check-circle-fill text-success"></i></div>
          </div>
          
          <div class="comp-row">
            <div class="comp-feature">SSO Integration</div>
            <div class="comp-val gray"><i class="bi bi-dash"></i></div>
            <div class="comp-val gray"><i class="bi bi-dash"></i></div>
            <div class="comp-val gray"><i class="bi bi-dash"></i></div>
            <div class="comp-val"><i class="bi bi-check-circle-fill text-success"></i></div>
          </div>
          <div class="comp-row">
            <div class="comp-feature">Dedicated Success Manager</div>
            <div class="comp-val gray"><i class="bi bi-dash"></i></div>
            <div class="comp-val gray"><i class="bi bi-dash"></i></div>
            <div class="comp-val gray"><i class="bi bi-dash"></i></div>
            <div class="comp-val"><i class="bi bi-check-circle-fill text-success"></i></div>
          </div>
        </div>
      </div>
    </section>

    ${faqSection([
      ["How do plan upgrades work?", "You can upgrade from individual to team or enterprise plans at any time. Your billing will be adjusted immediately, and you will only be charged the prorated difference for the remainder of your cycle."],
      ["Can companies request custom AI lessons?", "Yes. Enterprise customers can request company-specific training for internal workflows, tools, and policies."],
      ["Can I change plans later?", "Yes. You can upgrade from individual learning into team training as your needs grow."],
      ["Is the Chrome extension required?", "No. It is optional and used only when smarter workflow recommendations are useful."]
    ])}
  `,

  "how-it-works": () => `
    ${hero({
      eyebrow: "Workflow-aware learning",
      title: "How Daleel AI recommends the right lesson at the right time",
      text: "Daleel AI connects learner profiles, tool behavior, repeated tasks, and progress to recommend practical lessons that can be applied immediately.",
      mock: workflowPreviewMock()
    })}
    <section class="section">
      <div class="container">
        <div class="section-header center"><h2>The learning workflow</h2><p class="lead">From onboarding to measurable AI adoption.</p></div>
        <div class="workflow-timeline">
          ${step("01", "Profile the learner", "Capture role, goals, department, skill level, and tools used at work.")}
          ${step("02", "Detect work patterns", "Identify recurring tasks, common blockers, and workflows with automation potential.")}
          ${step("03", "Recommend practical lessons", "Show lessons, paths, and mentor prompts matched to real work.")}
          ${step("04", "Apply inside daily tools", "Learners use templates, prompts, and workflows immediately.")}
          ${step("05", "Track progress", "Individuals and teams see completed lessons, adoption, and time saved.")}
          ${step("06", "Improve recommendations", "The feed adapts as learners watch, ask, save, and complete paths.")}
        </div>
      </div>
    </section>
    <section class="section alt">
      <div class="container grid two">
        <div>
          <p class="eyebrow">Interactive sample</p>
          <h2>Pick a role to preview recommendations</h2>
          <p class="lead">This mock selector shows how the site can recommend paths from public profile data.</p>
          <div class="segmented" data-role-picker>
            <button type="button" class="active" data-role="Support">Support</button>
            <button type="button" data-role="Marketing">Marketing</button>
            <button type="button" data-role="Sales">Sales</button>
            <button type="button" data-role="Founder">Founder</button>
          </div>
        </div>
        <div class="card card-body" id="roleRecommendation"></div>
      </div>
    </section>
  `,

  videos: () => `
    <header class="page-hero search-hero">
      <div class="container text-center">
        <p class="eyebrow">Daleel AI Playground</p>
        <h1>Immediate AI workflows for your role</h1>
        <div class="search-bar-luxury">
          <i class="bi bi-search"></i>
          <input id="videoSearch" type="search" placeholder="Search prompts, tools, or roles...">
          <div class="search-filters">
            <select id="videoRole"><option value="All">Roles</option></select>
            <select id="videoLevel"><option value="All">Level</option></select>
          </div>
        </div>
      </div>
    </header>

    <section class="section pt-0 bg-playground">
      <div class="container">
        <div class="playground-layout">
          <div class="main-player">
            <div class="player-wrapper shadow-xl">
              <div class="aspect-video movie-backdrop">
                <img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&q=80" alt="Main Video">
                <button class="play-huge"><i class="bi bi-play-fill"></i></button>
              </div>
            </div>
            <div class="player-info mt-4">
              <span class="badge-premium">Live Lesson</span>
              <h2>How to Use ChatGPT for Daily Work</h2>
              <p class="lead">The foundational course for any professional. Learn to draft emails, summarize reports, and plan your week with precision.</p>
              <div class="player-meta">
                <span><i class="bi bi-clock"></i> 14 min</span>
                <span><i class="bi bi-eye"></i> 18k active</span>
              </div>
            </div>
          </div>
          
          <aside class="up-next-sidebar">
            <div class="sidebar-header">
              <strong>Up Next</strong>
              <span class="status">Auto-play</span>
            </div>
            <div class="related-list">
              <div class="related-item active">
                <div class="related-thumb"><img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&q=80&w=200"></div>
                <div class="related-body">
                  <strong>ChatGPT Mastery</strong>
                  <small>14:00 • Beginner</small>
                </div>
              </div>
              <div class="related-item">
                <div class="related-thumb"><img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&q=80&w=200"></div>
                <div class="related-body">
                  <strong>AI Support Teams</strong>
                  <small>18:00 • Intermediate</small>
                </div>
              </div>
              <div class="related-item">
                <div class="related-thumb"><img src="https://images.unsplash.com/photo-1557838923-2985c318be48?auto=format&fit=crop&q=80&w=200"></div>
                <div class="related-body">
                  <strong>Marketing Prompts</strong>
                  <small>16:00 • Advanced</small>
                </div>
              </div>
              <div class="related-item">
                <div class="related-thumb"><img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&q=80&w=200"></div>
                <div class="related-body">
                  <strong>Meeting Summaries</strong>
                  <small>11:00 • Beginner</small>
                </div>
              </div>
            </div>
            <a href="register" class="btn primary full mt-4">Unlock Full Library</a>
          </aside>
        </div>
      </div>
    </section>

    <section class="section bg-slate">
      <div class="container">
        <div class="section-header">
          <h2>All Workflows</h2>
        </div>
        <div class="grid three mt-4" id="videoResults"></div>
      </div>
    </section>
  `,

  lesson: () => `
    <section class="section lesson-page">
      <div class="container lesson-layout">
        <div>
          <div class="video-player">
            <span>Video lesson preview</span>
          </div>
          <div class="lesson-detail">
            <p class="eyebrow">Productivity lesson</p>
            <h1>How to Use ChatGPT for Daily Work</h1>
            <p class="lead">Learn a repeatable workflow for emails, meeting notes, reports, task planning, and faster knowledge work.</p>
            <div class="chip-row"><span class="chip">14 min</span><span class="chip">18.4k views</span><span class="chip">Beginner</span><span class="chip">Certificate path</span></div>
            <div class="inline-actions"><button class="btn primary" type="button">Mark as Completed</button><button class="btn secondary" type="button">Save Lesson</button></div>
          </div>
          <section class="section compact-section">
            <h2>Related lessons</h2>
            <div class="grid three">${videoLessons.slice(1, 4).map((lesson, index) => lessonCard(lesson, index)).join("")}</div>
          </section>
        </div>
        <aside class="chat-shell sticky-panel">
          <div class="chat-head">AI Mentor Q&A <span class="status">Lesson aware</span></div>
          <div class="chat-lines">
            <div class="chat-bubble">How do I use this for weekly reports?</div>
            <div class="chat-bubble ai">Start with a report template, paste your raw notes, ask for a summary, then ask for risks and next steps.</div>
            <div class="chat-bubble">Suggested next lesson?</div>
            <div class="chat-bubble ai">Use AI to Summarize Meetings is the best next lesson.</div>
          </div>
        </aside>
      </div>
    </section>
  `,

  "learning-paths": () => `
    ${hero({
      eyebrow: "Structured paths",
      title: "Role-based AI learning paths with certificates",
      text: "Each path gives learners a clear sequence of lessons, practice workflows, and completion outcomes.",
      secondary: "Explore Videos",
      secondaryHref: "videos"
    })}
    <section class="section">
      <div class="container">
        <div class="section-header center"><h2>Available learning paths</h2><p class="lead">Built for the departments companies train first.</p></div>
        <div class="grid three">
          ${paths.map(pathCard).join("")}
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
            <div class="field"><label for="pathRole">Role</label><select id="pathRole">${unique(paths.map(p => p.role)).map(v => `<option>${v}</option>`).join("")}</select></div>
            <div class="field"><label for="pathLevel">Level</label><select id="pathLevel"><option>Beginner</option><option>Intermediate</option><option>Advanced</option></select></div>
          </div>
        </div>
        <div class="card card-body" id="pathBuilderResult"></div>
      </div>
    </section>
  `,

  enterprise: () => `
    ${hero({
      eyebrow: "Enterprise AI training",
      title: "Train employees on practical AI workflows",
      text: "Give every department personalized AI training while managers track readiness, completion, time saved, and repeated task reduction.",
      primary: "Book Enterprise Demo",
      primaryHref: "contact",
      mock: enterprisePreviewMock()
    })}
    <section class="section" id="analytics">
      <div class="container grid two">
        <div>
          <p class="eyebrow">Team capabilities</p>
          <h2>Everything managers need to drive adoption</h2>
          <ul class="feature-list">
            <li>Team dashboards and department progress</li>
            <li>Employee learning paths and certificates</li>
            <li>AI readiness and workflow opportunity scoring</li>
            <li>Custom training for internal tools and policies</li>
            <li>ROI and time-saving reports</li>
            <li>Optional Chrome extension rollout controls</li>
          </ul>
        </div>
        ${enterprisePreviewMock()}
      </div>
    </section>
    <section class="section alt">
      <div class="container">
        <div class="section-header center"><p class="eyebrow">Training request flow</p><h2>From pilot team to company-wide adoption</h2></div>
        <div class="grid four">
          ${feature("Pick departments", "Start with support, marketing, sales, operations, or leadership teams.")}
          ${feature("Map workflows", "Identify repeated tasks and AI opportunities by department.")}
          ${feature("Launch paths", "Assign role-based lessons and custom company content.")}
          ${feature("Measure impact", "Track employees trained, hours saved, completion rate, and adoption score.")}
        </div>
      </div>
    </section>
    <section class="section">
      <div class="container grid two">
        <div class="card calculator">
          <p class="eyebrow">ROI calculator</p>
          <h2>Estimate monthly time saved</h2>
          <div class="form-grid">
            <div class="field"><label for="employees">Employees</label><input id="employees" type="number" value="80" min="1"></div>
            <div class="field"><label for="hours">Hours saved per employee weekly</label><input id="hours" type="number" value="1.5" min="0" step="0.5"></div>
            <div class="field full"><label for="rate">Average hourly cost</label><input id="rate" type="number" value="35" min="1"></div>
          </div>
        </div>
        <div class="calc-result" id="roiResult"></div>
      </div>
    </section>
  `,

  "chrome-extension": () => `
    ${hero({
      eyebrow: "Optional workflow signals",
      title: "Chrome extension for smarter AI recommendations",
      text: "The extension helps identify work patterns, active tools, repeated tasks, and time spent across platforms so recommendations become more useful.",
      secondary: "Read Privacy Policy",
      secondaryHref: "privacy"
    })}
    <section class="section">
      <div class="container grid two">
        ${feature("Behavior signals, not private content", "The extension focuses on productivity patterns, tool usage, repeated actions, and timing signals.")}
        ${feature("AI opportunity detection", "Repeated manual work can trigger recommended lessons, templates, or automation workflows.")}
        ${feature("User and company controls", "Individuals or admins can enable, pause, or configure the extension based on company policy.")}
        ${feature("Useful productivity insights", "Teams can see where training may save time without needing private work content.")}
      </div>
    </section>
    <section class="section alt">
      <div class="container grid two">
        <div>
          <p class="eyebrow">Privacy checklist</p>
          <h2>Clear controls for rollout conversations</h2>
          <div class="accordion">
            ${accordionItem("What the extension helps detect", "Repeated tools, work sessions, task categories, and opportunities for AI assistance.")}
            ${accordionItem("What the extension should not collect", "Private document content, passwords, personal messages, or unrelated browsing content.")}
            ${accordionItem("How teams can roll it out", "Start with a pilot group, explain the signals collected, then expand by department once value is clear.")}
          </div>
        </div>
        <div class="mock-dashboard">
          <div class="mock-top">Extension insight <span class="status">Detected</span></div>
          <div class="card-body">
            <h3>Weekly spreadsheet cleanup appears 4 times this month</h3>
            <p class="muted">Suggested lesson: Automate repetitive spreadsheet preparation with AI prompts and no-code tools.</p>
            <div class="badge-row"><span class="badge primary">Pattern</span><span class="badge">Sheets</span><span class="badge green">Save 2.5h</span></div>
          </div>
        </div>
      </div>
    </section>
  `,

  "ai-mentor": () => `
    ${hero({
      eyebrow: "Ask while you learn",
      title: "An AI mentor that connects lessons to your work",
      text: "Learners can ask about a video, request workflow suggestions, compare tools, or turn a problem into a custom lesson plan."
    })}
    <section class="section">
      <div class="container grid two">
        <div class="chat-shell">
          <div class="chat-head">AI Mentor <span class="status">Online</span></div>
          <div class="chat-lines" id="mentorChat">
            <div class="chat-bubble">How can I use AI to reduce manual reporting?</div>
            <div class="chat-bubble ai">Start by identifying repeated reporting steps, then use templates, automation tools, and AI summaries.</div>
          </div>
        </div>
        <div>
          <p class="eyebrow">Try example prompts</p>
          <h2>Preview mentor behavior</h2>
          <div class="grid two" data-mentor-prompts>
            <button class="btn secondary" type="button" data-prompt="Summarize this lesson for my support team.">Ask about a video</button>
            <button class="btn secondary" type="button" data-prompt="What AI workflow should I use for weekly reports?">Workflow suggestion</button>
            <button class="btn secondary" type="button" data-prompt="Which AI tool should marketing use for campaign briefs?">Tool recommendation</button>
            <button class="btn secondary" type="button" data-prompt="Create a custom lesson outline for our sales team.">Custom lesson</button>
          </div>
        </div>
      </div>
    </section>
  `,

  "success-stories": () => `
    ${hero({
      eyebrow: "Proof of impact",
      title: "Real results from teams applying AI at work",
      text: "Explore public case-study previews by department and outcome."
    })}
    <section class="section">
      <div class="container">
        <div class="section-header center mb-5">
          <p class="eyebrow">Impact Reports</p>
          <h2 class="display-6 fw-bold">Success Stories from Every Industry</h2>
        </div>
        <div class="grid three" id="storyResults"></div>
      </div>
    </section>
  `,

  about: () => `
    ${hero({
      eyebrow: "Company profile",
      title: "Daleel AI helps people learn AI through the work they already do",
      text: "We are building a practical AI learning layer for professionals, teams, and enterprises that need measurable adoption, not random content."
    })}
    <section class="section">
      <div class="container grid two">
        <div>
          <p class="eyebrow">Company mission</p>
          <h2>Make AI useful at work for every role</h2>
          <p class="lead">Daleel AI exists because most AI training is disconnected from the user's actual job. Our platform connects learning to role, tools, workflows, goals, and behavior so users can apply AI immediately.</p>
          <div class="inline-actions"><a class="btn primary" href="contact">Partner With Us</a><a class="btn secondary" href="enterprise">Enterprise Training</a></div>
        </div>
        <div class="profile-card">
          <div class="mock-top">Company snapshot <span class="status">Public profile</span></div>
          <div class="stat-grid">
            <div class="stat-tile"><strong>2026</strong><span>public site profile</span></div>
            <div class="stat-tile"><strong>20+</strong><span>countries reached</span></div>
            <div class="stat-tile"><strong>120+</strong><span>teams trained</span></div>
            <div class="stat-tile"><strong>10k+</strong><span>lessons watched</span></div>
          </div>
        </div>
      </div>
    </section>
    <section class="section alt">
      <div class="container">
        <div class="section-header center"><h2>What the company provides</h2><p class="lead">A complete public profile of the product, audience, and services.</p></div>
        <div class="grid three">
          ${feature("Individual AI learning", "Personalized videos, saved lessons, mentor questions, and certificates for professionals.")}
          ${feature("Team training", "Department paths, team dashboards, readiness scoring, and adoption reporting for managers.")}
          ${feature("Custom enterprise content", "Company-specific lessons and workflows built around internal processes, policies, and tools.")}
          ${feature("Workflow intelligence", "Optional Chrome extension signals that identify repeated work and AI opportunities.")}
          ${feature("Partner ecosystem", "Support for learning around tools such as OpenAI, Google Cloud, Microsoft, and ElevenLabs.")}
          ${feature("Measurable adoption", "Progress, time saved, completed learning paths, and practical AI implementation outcomes.")}
        </div>
      </div>
    </section>
    <section class="section">
      <div class="container">
        <div class="section-header"><p class="eyebrow">Company timeline</p><h2>How the platform grows</h2></div>
        <div class="timeline">
          ${timeline("Phase 1", "Launch public learning library and role-based paths.")}
          ${timeline("Phase 2", "Add AI mentor, saved lessons, and workflow recommendations.")}
          ${timeline("Phase 3", "Roll out team analytics, enterprise reporting, and custom training.")}
          ${timeline("Phase 4", "Expand Chrome extension insights and partner ecosystem integrations.")}
        </div>
      </div>
    </section>
  `,

  contact: () => `
    ${hero({
      eyebrow: "Contact",
      title: "Book a demo or talk to the Daleel AI team",
      text: "Tell us about your company, training goals, and AI adoption needs. This static sample shows the intended lead-capture flow.",
      primary: "Start Free",
      primaryHref: "register",
      secondary: "View Pricing",
      secondaryHref: "pricing"
    })}
    <section class="section">
      <div class="container grid two">
        <form class="form-panel card-body" data-demo-form>
          <p class="eyebrow">Demo request</p>
          <h2>Request enterprise contact</h2>
          <div class="form-grid">
            <div class="field"><label for="name">Name</label><input id="name" required placeholder="Maya Hassan"></div>
            <div class="field"><label for="email">Work email</label><input id="email" type="email" required placeholder="maya@company.com"></div>
            <div class="field"><label for="company">Company</label><input id="company" required placeholder="Acme Services"></div>
            <div class="field"><label for="size">Company size</label><select id="size"><option>1-50</option><option>51-250</option><option>251-1000</option><option>1000+</option></select></div>
            <div class="field full"><label for="goal">Training goal</label><textarea id="goal" placeholder="We want to train customer support and operations teams on AI workflows."></textarea></div>
          </div>
          <button class="btn primary full" type="submit">Send Request</button>
          <div class="form-message">Thanks. This demo form is ready for backend integration.</div>
        </form>
        <div>
          <p class="eyebrow">Company details</p>
          <h2>Public profile contact points</h2>
          <div class="grid">
            ${feature("Sales", "For team training, enterprise pilots, and custom AI learning paths.")}
            ${feature("Partnerships", "For AI ecosystem partners, agencies, universities, and innovation programs.")}
            ${feature("Support", "For account questions, content requests, and learner help.")}
          </div>
        </div>
      </div>
    </section>
  `,

  login: () => authPage("Login to Daleel AI", "Welcome back. Continue learning AI based on your work.", "Login", "No account yet?", "register", "Create one"),
  signup: () => authPage("Create your free account", "Set up your profile in under 2 minutes and get your first recommendations.", "Create Account", "Already have an account?", "login", "Login"),

  blog: () => `
    ${hero({
      eyebrow: "Resources",
      title: "Guides, templates, and AI adoption resources",
      text: "Public resources for learners, managers, IT teams, and companies planning AI training.",
      primary: "Explore Resources",
      primaryHref: "#resources",
      secondary: "Help Center",
      secondaryHref: "help-center"
    })}
    <section class="section" id="resources">
      <div class="container">
        <div class="filter-toolbar">
          <div><p class="eyebrow">Resource library</p><h2>Browse public resources</h2></div>
          <div class="search-bar">
            <input id="resourceSearch" type="search" placeholder="Search resources">
            <select id="resourceType"><option value="All">All types</option></select>
            <select id="resourceAudience"><option value="All">All audiences</option></select>
          </div>
        </div>
        <div class="grid three" id="resourceResults"></div>
      </div>
    </section>
  `,

  "tools-directory": () => `
    ${hero({
      eyebrow: "AI tools directory",
      title: "Connect tools to practical lessons and workflows",
      text: "A public directory showing how learners can discover AI tools by category and learn the best workflow for each one.",
      secondary: "View Lessons",
      secondaryHref: "videos"
    })}
    <section class="section">
      <div class="container">
        <div class="filter-toolbar">
          <div><p class="eyebrow">Directory</p><h2>Find tools by category</h2></div>
          <div class="search-bar">
            <input id="toolSearch" type="search" placeholder="Search tools">
            <select id="toolCategory"><option value="All">All categories</option></select>
            <select id="toolSort"><option value="Name">Sort by name</option><option value="Category">Sort by category</option></select>
          </div>
        </div>
        <div class="grid four" id="toolResults"></div>
      </div>
    </section>
  `,

  "help-center": () => `
    ${hero({
      eyebrow: "Help center",
      title: "Answers for learners, teams, and admins",
      text: "Search common questions about learning paths, pricing, extension privacy, enterprise analytics, and account setup.",
      primary: "Contact Support",
      primaryHref: "contact",
      secondary: "Read Privacy",
      secondaryHref: "privacy"
    })}
    <section class="section">
      <div class="container">
        <div class="filter-toolbar">
          <div><p class="eyebrow">Support</p><h2>Search help topics</h2></div>
          <div class="search-bar"><input id="helpSearch" type="search" placeholder="Search help center"><select id="helpTopic"><option value="All">All topics</option></select><span></span></div>
        </div>
        <div class="accordion" id="helpResults"></div>
      </div>
    </section>
  `,

  terms: () => legalPage("Terms", "Terms of Service", [
    "This sample page outlines expected public website terms for Daleel AI. Replace this copy with reviewed legal language before launch.",
    "Users are responsible for using lessons, templates, and AI recommendations in accordance with their company policies and applicable laws.",
    "The platform may provide educational guidance, workflow suggestions, analytics, and AI mentor responses, but users remain responsible for final work decisions.",
    "Enterprise features, custom content, and support obligations should be governed by a signed agreement or order form."
  ]),
  privacy: () => legalPage("Privacy", "Privacy Policy", [
    "Daleel AI should collect only the information needed to personalize learning, operate accounts, support teams, and improve recommendations.",
    "Optional workflow analysis should focus on behavior signals such as tools used, repeated tasks, and time allocation, not private content.",
    "Companies should configure employee analytics transparently and explain how progress, adoption, and productivity insights are used.",
    "Users should be able to request access, correction, deletion, or export of personal data according to the final production policy."
  ]),
  cookies: () => legalPage("Cookies", "Cookie Policy", [
    "This sample page describes how Daleel AI may use cookies or similar technologies for login, preferences, analytics, and product improvement.",
    "Essential cookies support account access, security, and basic site function.",
    "Analytics cookies should help understand public website usage and improve content performance.",
    "Users should be able to manage cookie preferences once production tracking tools are selected."
  ])
};

function priceCard(name, description, monthly, yearly, features, cta, featured = false) {
  const price = typeof monthly === "number" ? `$${monthly}` : monthly;
  const yearlyPrice = typeof yearly === "number" ? `$${yearly}` : yearly;
  const enterpriseClass = name === "Enterprise" ? " enterprise-plan" : "";
  return `
    <article class="card price-card ${featured ? "featured" : ""}${enterpriseClass}" data-monthly="${price}" data-yearly="${yearlyPrice}">
      <div class="card-body">
        <h3>${name}</h3>
        <p class="muted">${description}</p>
        <div class="price"><strong class="price-amount">${price}</strong><span>${name === "Enterprise" ? "" : "/ user"}</span></div>
        <ul class="feature-list">${features.map(item => `<li>${item}</li>`).join("")}</ul>
        <div class="inline-actions"><a class="btn ${featured ? "primary" : "secondary"} full" href="${name === "Enterprise" ? "contact" : "register"}">${cta}</a></div>
      </div>
    </article>
  `;
}

function step(num, title, text) {
  return `<article class="card step-card"><span class="step-number">${num}</span><div><h3>${title}</h3><p class="muted">${text}</p></div></article>`;
}

function feature(title, text) {
  return `<article class="card card-body"><span class="mini-label">Feature</span><h3>${title}</h3><p class="muted">${text}</p></article>`;
}

function pathCard(path) {
  return `
    <article class="card card-body">
      <span class="mini-label">${path.role}</span>
      <h3>${path.title}</h3>
      <p class="muted">${path.focus}</p>
      <div class="chip-row">
        <span class="chip">${path.lessons} lessons</span>
        <span class="chip">${path.time}</span>
        <span class="chip">${path.level}</span>
        <span class="chip">Certificate</span>
      </div>
    </article>
  `;
}

function lessonCard(lesson, index = 0) {
  const hasYoutube = !!lesson.youtubeId;
  const href       = lesson.watchUrl || '#';
  const desc       = lesson.description
    ? `<p class="muted" style="font-size:0.85rem;margin:6px 0 10px;line-height:1.5;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">${lesson.description}</p>`
    : '';

  // If we have a YouTube ID, use the modal; otherwise link to the watch page
  const clickAttr = hasYoutube
    ? `onclick="openVideoModal('${lesson.youtubeId}', ${JSON.stringify(lesson.title)});return false;" href="#"`
    : `href="${href}"`;

  return `
    <article class="card media-card lesson-card">
      <a ${clickAttr} style="display:block;text-decoration:none;color:inherit;cursor:pointer;">
        <div class="thumb" style="background-image: url('${lesson.thumb}'); position:relative;">
          <span class="play-pill" style="position:absolute;inset:0;margin:auto;width:52px;height:52px;border-radius:50%;background:rgba(255,255,255,0.95);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px rgba(0,0,0,0.3);font-size:1.3rem;color:#6366f1;">&#9654;</span>
        </div>
        <div class="card-body">
          <p class="mini-label">${lesson.category || ''}</p>
          <h3>${lesson.title}</h3>
          ${desc}
          <div class="chip-row">
            <span class="chip">${lesson.duration || ''}</span>
            <span class="chip">${lesson.level || ''}</span>
          </div>
          <div class="lesson-actions">
            <a class="btn secondary" ${clickAttr}>
              <i class="bi bi-play-circle-fill" style="margin-right:6px;"></i>Watch Lesson
            </a>
          </div>
        </div>
      </a>
    </article>
  `;
}

function lessonBenefit(lesson) {
  const benefits = {
    Productivity: "Save time on daily writing, summaries, and follow-ups.",
    Automation: "Turn repeated manual steps into AI-supported workflows.",
    Support: "Improve reply quality and reduce repetitive ticket work.",
    Marketing: "Create better briefs, content variations, and campaign ideas.",
    Operations: "Summarize meetings, reports, and recurring updates faster.",
    Leadership: "Use AI for planning, research, investor updates, and decision prep.",
    Sales: "Research accounts, clean CRM notes, and write better follow-ups.",
    HR: "Draft onboarding, role guides, and policy explainers faster.",
    Development: "Prepare code reviews, tests, release notes, and documentation."
  };
  return benefits[lesson.category] || "Learn a practical AI workflow for daily work.";
}

function storyCard(story) {
  return `
    <article class="card card-body success-card">
      <div class="story-header">
        <span class="story-metric">${story.metric}</span>
        <p class="mini-label">${story.industry}</p>
      </div>
      <h3>${story.team}</h3>
      <div class="story-details">
        <p class="muted"><strong>Challenge:</strong> ${story.challenge}</p>
        <p class="muted"><strong>Impact:</strong> ${story.result}</p>
      </div>
    </article>
  `;
}

function timeline(label, text) {
  return `<article class="card timeline-item"><p class="mini-label">${label}</p><h3>${text}</h3></article>`;
}

function accordionItem(question, answer) {
  return `<div class="accordion-item"><button class="accordion-button" type="button">${question}<span>+</span></button><div class="accordion-panel">${answer}</div></div>`;
}

function faqSection(items) {
  return `<section class="section"><div class="container"><div class="section-header center"><h2>Frequently asked questions</h2></div><div class="accordion">${items.map(([q, a]) => accordionItem(q, a)).join("")}</div></div></section>`;
}

function authPage(title, text, button, linkText, linkHref, linkLabel) {
  return `
    <section class="page-hero">
      <div class="container grid two">
        <div>
          <p class="eyebrow">Account access</p>
          <h1>${title}</h1>
          <p class="lead">${text}</p>
        </div>
        <form class="form-panel card-body" data-demo-form>
          <div class="form-grid">
            <div class="field full"><label for="authEmail">Email</label><input id="authEmail" type="email" required placeholder="you@company.com"></div>
            <div class="field full"><label for="authPassword">Password</label><input id="authPassword" type="password" required placeholder="Enter password"></div>
            ${button === "Create Account" ? `<div class="field full"><label for="authRole">Role</label><select id="authRole"><option>Marketing</option><option>Sales</option><option>Support</option><option>Operations</option><option>Founder</option></select></div>` : ""}
          </div>
          <button class="btn primary full" type="submit">${button}</button>
          <p class="muted" style="margin: 14px 0 0;">${linkText} <a class="btn text" href="${linkHref}">${linkLabel}</a></p>
          <div class="form-message">This sample form is ready for authentication integration.</div>
        </form>
      </div>
    </section>
  `;
}

function legalPage(eyebrow, title, paragraphs) {
  return `
    ${hero({
      eyebrow,
      title,
      text: "Sample legal content for the static public website. Replace with reviewed production policy text before launch.",
      primary: "Contact",
      primaryHref: "contact",
      secondary: "Back Home",
      secondaryHref: "/"
    })}
    <section class="section">
      <div class="container">
        <div class="card card-body">
          ${paragraphs.map((p, i) => `<h3>${i + 1}. ${["Scope", "Responsibilities", "Data and usage", "Updates"][i] || "Policy"}</h3><p class="muted">${p}</p>`).join("")}
        </div>
      </div>
    </section>
  `;
}

function unique(values) {
  return [...new Set(values)];
}

function initShell() {
  const activePage = document.body.dataset.page || "pricing";
  const content = templates[activePage] ? templates[activePage]() : templates.pricing();
  document.getElementById("site-root").innerHTML = `
    <div class="site-shell">
      ${shellNav(activePage)}
      <main>${content}</main>
      ${shellFooter()}
    </div>
  `;
  document.title = `Daleel AI | ${pageTitle(activePage)}`;
}

function pageTitle(page) {
  return {
    home: "AI Learning for Real Work",
    pricing: "Pricing",
    "how-it-works": "How It Works",
    videos: "AI Video Lessons",
    lesson: "How to Use ChatGPT for Daily Work",
    "learning-paths": "Learning Paths",
    enterprise: "Enterprise",
    "chrome-extension": "Chrome Extension",
    "ai-mentor": "AI Mentor",
    "success-stories": "Success Stories",
    about: "About",
    contact: "Contact",
    login: "Login",
    signup: "Sign Up",
    blog: "Resources",
    "tools-directory": "AI Tools Directory",
    "help-center": "Help Center",
    terms: "Terms",
    privacy: "Privacy Policy",
    cookies: "Cookie Policy"
  }[page] || "Public Website";
}

function initNav() {
  const toggle = document.getElementById("siteMenuToggle");
  if (!toggle) return;
  toggle.addEventListener("click", () => {
    const isOpen = document.body.classList.toggle("nav-open");
    toggle.setAttribute("aria-expanded", String(isOpen));
    toggle.setAttribute("aria-label", isOpen ? "Close menu" : "Open menu");
  });
  document.querySelectorAll(".nav-links a, .nav-actions a").forEach(link => {
    link.addEventListener("click", () => {
      document.body.classList.remove("nav-open");
      toggle.setAttribute("aria-expanded", "false");
      toggle.setAttribute("aria-label", "Open menu");
    });
  });
}

function initAccordions() {
  document.querySelectorAll(".accordion-button").forEach(button => {
    button.addEventListener("click", () => {
      const item = button.closest(".accordion-item");
      item.classList.toggle("open");
    });
  });
}

function initPricing() {
  const billing = document.querySelector("[data-billing]");
  if (!billing) return;
  billing.addEventListener("click", event => {
    const button = event.target.closest("button");
    if (!button) return;
    
    const cycle = button.dataset.cycle;
    billing.setAttribute("data-cycle", cycle);
    
    billing.querySelectorAll("button").forEach(btn => btn.classList.remove("active"));
    button.classList.add("active");
    
    document.querySelectorAll(".price-card").forEach(card => {
      const amount = card.querySelector(".price-amount");
      if (amount) {
        amount.textContent = card.dataset[cycle];
      }
    });
  });
}

function initRoleRecommendation() {
  const picker = document.querySelector("[data-role-picker]");
  const target = document.getElementById("roleRecommendation");
  if (!picker || !target) return;
  const render = role => {
    const lesson = videoLessons.find(item => item.role === role) || videoLessons[0];
    target.innerHTML = `<p class="mini-label">${role}</p><h3>${lesson.title}</h3><p class="muted">Recommended first lesson: ${lesson.category}, ${lesson.duration}, ${lesson.level} level.</p><div class="chip-row"><span class="chip">${lesson.views} views</span><span class="chip">${lesson.category}</span><span class="chip">Practical workflow</span></div>`;
  };
  picker.addEventListener("click", event => {
    const button = event.target.closest("button");
    if (!button) return;
    picker.querySelectorAll("button").forEach(btn => btn.classList.remove("active"));
    button.classList.add("active");
    render(button.dataset.role);
  });
  render("Support");
}

function initVideos() {
  const results = document.getElementById("videoResults");
  if (!results) return;

  const role    = document.getElementById("videoRole");
  const level   = document.getElementById("videoLevel");
  const search  = document.getElementById("videoSearch");

  // Show loading state
  results.innerHTML = `<div class="card card-body" style="grid-column:1/-1;text-align:center;padding:3rem;"><p class="muted">Loading lessons…</p></div>`;

  fetch("/api/public/videos")
    .then(r => r.json())
    .then(videos => {
      // Populate filter dropdowns from real data
      fillSelect(level,  unique(videos.map(v => v.level).filter(Boolean)));
      fillSelect(role,   unique(videos.map(v => v.category).filter(Boolean)));

      const render = () => {
        const query = search.value.toLowerCase();
        const list = videos.filter(video => {
          const matchesQuery = `${video.title} ${video.category} ${video.description || ""} ${video.tags || ""}`.toLowerCase().includes(query);
          const matchesRole  = role.value  === "All" || video.category === role.value;
          const matchesLevel = level.value === "All" || video.level    === level.value;
          return matchesQuery && matchesRole && matchesLevel;
        });
        results.innerHTML = list.length
          ? list.map((video, index) => lessonCard(video, index)).join("")
          : `<div class="card card-body"><h3>No lessons found</h3><p class="muted">Try a different category, level, or search term.</p></div>`;
      };

      [role, level, search].forEach(control => control.addEventListener("input", render));
      render();
    })
    .catch(() => {
      // Fallback to static demo data if API fails
      fillSelect(level, unique(videoLessons.map(v => v.level)));
      fillSelect(role,  unique(videoLessons.map(v => v.role)));
      const render = () => {
        const query = search.value.toLowerCase();
        const list  = videoLessons.filter(v =>
          `${v.title} ${v.category}`.toLowerCase().includes(query) &&
          (role.value === "All"  || v.role  === role.value) &&
          (level.value === "All" || v.level === level.value)
        );
        results.innerHTML = list.map((v, i) => lessonCard(v, i)).join("") ||
          `<div class="card card-body"><h3>No lessons found</h3></div>`;
      };
      [role, level, search].forEach(ctrl => ctrl.addEventListener("input", render));
      render();
    });
}

function fillSelect(select, values) {
  values.forEach(value => {
    const option = document.createElement("option");
    option.value = value;
    option.textContent = value;
    select.appendChild(option);
  });
}

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
  render();
}

function initRoiCalculator() {
  const employees = document.getElementById("employees");
  const hours = document.getElementById("hours");
  const rate = document.getElementById("rate");
  const result = document.getElementById("roiResult");
  if (!employees || !hours || !rate || !result) return;
  const render = () => {
    const monthlyHours = Number(employees.value) * Number(hours.value) * 4;
    const value = monthlyHours * Number(rate.value);
    result.innerHTML = `<p class="eyebrow" style="color:#9cc2ff;">Estimated monthly impact</p><strong>${Math.round(monthlyHours).toLocaleString()} hours</strong><p>Estimated time saved each month.</p><strong>$${Math.round(value).toLocaleString()}</strong><p>Estimated productivity value based on your inputs.</p>`;
  };
  [employees, hours, rate].forEach(input => input.addEventListener("input", render));
  render();
}

function initMentor() {
  const promptWrap = document.querySelector("[data-mentor-prompts]");
  const chat = document.getElementById("mentorChat");
  if (!promptWrap || !chat) return;
  promptWrap.addEventListener("click", event => {
    const button = event.target.closest("button");
    if (!button) return;
    const user = document.createElement("div");
    user.className = "chat-bubble";
    user.textContent = button.dataset.prompt;
    const answer = document.createElement("div");
    answer.className = "chat-bubble ai";
    answer.textContent = "I would turn that into a short workflow, recommend the best lesson, and give you a reusable prompt template.";
    chat.append(user, answer);
    chat.scrollIntoView({ behavior: "smooth", block: "nearest" });
  });
}

function initStories() {
  const results = document.getElementById("storyResults");
  const filter = document.querySelector("[data-story-filter]");
  if (!results) return;
  const render = value => {
    const list = stories.filter(story => value === "All" || story.industry === value);
    results.innerHTML = list.map(storyCard).join("");
  };
  if (filter) {
    filter.addEventListener("click", event => {
      const button = event.target.closest("button");
      if (!button) return;
      filter.querySelectorAll("button").forEach(btn => btn.classList.remove("active"));
      button.classList.add("active");
      render(button.dataset.filter);
    });
  }
  render("All");
}

function initResources() {
  const results = document.getElementById("resourceResults");
  if (!results) return;
  const search = document.getElementById("resourceSearch");
  const type = document.getElementById("resourceType");
  const audience = document.getElementById("resourceAudience");
  fillSelect(type, unique(resources.map(item => item.type)));
  fillSelect(audience, unique(resources.map(item => item.audience)));
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
        <a class="btn text" href="contact">Request Resource</a>
      </article>
    `).join("") || `<div class="card card-body"><h3>No resources found</h3></div>`;
  };
  [search, type, audience].forEach(control => control.addEventListener("input", render));
  render();
}

function initTools() {
  const results = document.getElementById("toolResults");
  if (!results) return;
  const search = document.getElementById("toolSearch");
  const category = document.getElementById("toolCategory");
  const sort = document.getElementById("toolSort");
  fillSelect(category, unique(tools.map(tool => tool.category)));
  const render = () => {
    const query = search.value.toLowerCase();
    const list = tools.filter(tool => {
      return `${tool.name} ${tool.category} ${tool.use}`.toLowerCase().includes(query)
        && (category.value === "All" || tool.category === category.value);
    }).sort((a, b) => a[sort.value.toLowerCase()].localeCompare(b[sort.value.toLowerCase()]));
    results.innerHTML = list.map(tool => `
      <article class="card card-body">
        <span class="icon-box purple">${tool.name.slice(0, 2).toUpperCase()}</span>
        <p class="mini-label">${tool.category}</p>
        <h3>${tool.name}</h3>
        <p class="muted">${tool.use}</p>
        <span class="badge green">Lesson: ${tool.lesson}</span>
      </article>
    `).join("") || `<div class="card card-body"><h3>No tools found</h3></div>`;
  };
  [search, category, sort].forEach(control => control.addEventListener("input", render));
  render();
}

function initHelp() {
  const results = document.getElementById("helpResults");
  if (!results) return;
  const search = document.getElementById("helpSearch");
  const topic = document.getElementById("helpTopic");
  fillSelect(topic, unique(helpTopics.map(item => item[0])));
  const render = () => {
    const query = search.value.toLowerCase();
    const list = helpTopics.filter(([group, question, answer]) => {
      return `${group} ${question} ${answer}`.toLowerCase().includes(query)
        && (topic.value === "All" || group === topic.value);
    });
    results.innerHTML = list.map(([group, question, answer]) => accordionItem(`${group}: ${question}`, answer)).join("") || `<div class="card card-body"><h3>No topics found</h3></div>`;
    initAccordions();
  };
  [search, topic].forEach(control => control.addEventListener("input", render));
  render();
}

function initForms() {
  document.querySelectorAll("[data-demo-form]").forEach(form => {
    form.addEventListener("submit", event => {
      event.preventDefault();
      const message = form.querySelector(".form-message");
      if (message) message.classList.add("show");
      form.reset();
    });
  });
}

function init() {
  initShell();
  initNav();
  initAccordions();
  initPricing();
  initRoleRecommendation();
  initVideos();
  initPathBuilder();
  initRoiCalculator();
  initMentor();
  initStories();
  initResources();
  initTools();
  initHelp();
  initForms();
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", init);
} else {
  init();
}
