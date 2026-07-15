@extends('layouts.public')

@section('title', 'Help Center - Daleel AI')

@section('content')
<style>
  :root {
    --hc-bg: #f8fafb;
    --hc-surface: #ffffff;
    --hc-border: #e8ecf1;
    --hc-text: #0f172a;
    --hc-text-secondary: #475569;
    --hc-accent: #2563eb;
    --hc-accent-light: #eff6ff;
    --hc-radius: 14px;
  }

  .help-center {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    color: var(--hc-text);
    background: var(--hc-bg);
    min-height: 100vh;
  }

  .hc-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 28px;
  }

  /* Hero */
  .hc-hero {
    padding: 56px 0 52px;
    text-align: center;
    background: var(--hc-surface);
    border-bottom: 1px solid var(--hc-border);
  }

  .hc-badge {
    display: inline-block;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--hc-accent);
    background: var(--hc-accent-light);
    padding: 6px 16px;
    border-radius: 100px;
    margin-bottom: 20px;
    letter-spacing: 0.03em;
  }

  .hc-hero h1 {
    font-size: 2.5rem;
    font-weight: 700;
    letter-spacing: -0.03em;
    margin: 0 0 12px;
    line-height: 1.2;
  }

  .hc-hero p {
    font-size: 1.05rem;
    color: var(--hc-text-secondary);
    margin: 0 auto 28px;
    max-width: 500px;
    line-height: 1.6;
  }

  .hc-hero-btns {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
  }

  .hc-btn {
    display: inline-flex;
    align-items: center;
    padding: 11px 24px;
    border-radius: 100px;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.2s;
    border: 1px solid transparent;
  }

  .hc-btn-primary {
    background: var(--hc-accent);
    color: #fff;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
  }

  .hc-btn-primary:hover {
    background: #1d4ed8;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    transform: translateY(-1px);
  }

  .hc-btn-ghost {
    background: var(--hc-surface);
    color: var(--hc-text);
    border-color: var(--hc-border);
  }

  .hc-btn-ghost:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
  }

  /* FAQ Section */
  .hc-faq {
    padding: 48px 0 80px;
  }

  .hc-faq-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }

  .hc-faq-card {
    background: var(--hc-surface);
    border: 1px solid var(--hc-border);
    border-radius: var(--hc-radius);
    padding: 24px;
    transition: box-shadow 0.2s;
  }

  .hc-faq-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
  }

  .hc-faq-card h3 {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--hc-accent);
    margin: 0 0 16px;
    letter-spacing: -0.01em;
  }

  .hc-faq-items {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .hc-faq-item {
    border-bottom: 1px solid #f1f5f9;
  }

  .hc-faq-item:last-child {
    border-bottom: none;
  }

  .hc-faq-item summary {
    padding: 14px 0;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    list-style: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--hc-text);
    transition: color 0.15s;
  }

  .hc-faq-item summary:hover {
    color: var(--hc-accent);
  }

  .hc-faq-item summary::-webkit-details-marker {
    display: none;
  }

  .hc-faq-item summary::after {
    content: '+';
    font-size: 1.1rem;
    color: #94a3b8;
    transition: transform 0.25s;
    font-weight: 400;
  }

  .hc-faq-item[open] summary::after {
    transform: rotate(45deg);
    color: var(--hc-accent);
  }

  .hc-faq-item[open] summary {
    color: var(--hc-accent);
    padding-bottom: 8px;
  }

  .hc-faq-item p {
    margin: 0;
    padding: 0 0 14px;
    font-size: 0.88rem;
    color: var(--hc-text-secondary);
    line-height: 1.6;
  }

  @media (max-width: 640px) {
    .hc-hero h1 {
      font-size: 1.9rem;
    }
    .hc-hero p {
      font-size: 0.95rem;
    }
    .hc-faq-grid {
      grid-template-columns: 1fr;
    }
    .hc-container {
      padding: 0 20px;
    }
  }
</style>

<div class="help-center">
  {{-- Hero Section --}}
  <section class="hc-hero">
    <div class="hc-container">
      <span class="hc-badge">Help Center</span>
      <h1>How can we help you?</h1>
      <p>Find answers about learning paths, pricing, extension privacy, and account management.</p>
      <div class="hc-hero-btns">
        <a href="{{ url('contact') }}" class="hc-btn hc-btn-primary">Contact Support</a>
        <a href="{{ url('privacy') }}" class="hc-btn hc-btn-ghost">Privacy Policy →</a>
      </div>
    </div>
  </section>

  {{-- FAQ Section --}}
  <section class="hc-faq">
    <div class="hc-container">
      <div class="hc-faq-grid" id="faqGrid"></div>
    </div>
  </section>
</div>
@endsection

@section('scripts')
<script>
  const faqData = [
    {
      category: "Getting Started",
      items: [
        { q: "How do recommendations work?", a: "Daleel AI analyzes your role, goals, completed lessons, saved items, and tool usage patterns to suggest the most relevant learning paths for you." },
        { q: "Can individuals use the platform?", a: "Yes. Anyone can sign up and access role-based paths, video lessons, saved content, and the AI mentor without being part of a company workspace." }
      ]
    },
    {
      category: "Enterprise",
      items: [
        { q: "What can managers track?", a: "Managers can monitor team progress, completed paths, AI readiness scores, department adoption, and estimated productivity impact across the organization." },
        { q: "Can we request custom training?", a: "Absolutely. We work with companies to build custom training content for internal tools, policies, departments, and specialized workflows." }
      ]
    },
    {
      category: "Chrome Extension",
      items: [
        { q: "Does the extension read private content?", a: "No. The extension only tracks work behavior signals like tools used, repeated task patterns, and time allocation. It never accesses or reads private content." }
      ]
    },
    {
      category: "Billing",
      items: [
        { q: "Is there a free plan available?", a: "Yes. The free plan includes starter lessons, unlimited saved items, and a limited number of AI mentor questions each month." },
        { q: "Can teams switch plans later?", a: "Yes. Teams can upgrade or downgrade at any time. Enterprise customers can also contact sales for custom pricing and arrangements." }
      ]
    },
    {
      category: "Privacy",
      items: [
        { q: "Can users pause workflow analysis?", a: "Yes. Both individual users and company admins can control extension settings and pause or limit tracking based on their privacy preferences." }
      ]
    }
  ];

  document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('faqGrid');
    
    grid.innerHTML = faqData.map(function(cat) {
      return `
        <div class="hc-faq-card">
          <h3>${cat.category}</h3>
          <div class="hc-faq-items">
            ${cat.items.map(function(item) {
              return `
                <details class="hc-faq-item">
                  <summary>${item.q}</summary>
                  <p>${item.a}</p>
                </details>
              `;
            }).join('')}
          </div>
        </div>
      `;
    }).join('');
  });
</script>
@endsection