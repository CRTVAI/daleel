@extends('layouts.public')

@section('title', 'Daleel AI | Project Tracking Dashboard')

@section('meta_description', 'Track project activity, tool usage, learning progress, and AI adoption from one clear Daleel AI dashboard.')

@section('styles')
<style>
  :root {
    --navy: #060d1f;
    --navy-2: #0f172a;
    --slate: #475569;
    --slate-2: #64748b;
    --line: #e2e8f0;
    --soft: #f8fafc;
    --cyan: #06b6d4;
    --cyan-2: #22d3ee;
    --amber: #f59e0b;
    --emerald: #10b981;
    --rose: #fb7185;
    --white: #ffffff;
  }

  body { background-image: none; }

  .landing-page {
    font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    color: var(--navy-2);
    background: var(--white);
    overflow: hidden;
  }

  .landing-page p,
  .landing-page h1,
  .landing-page h2,
  .landing-page h3 { margin: 0; }

  .primary-btn,
  .secondary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 48px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 800;
    text-decoration: none;
    transition: all .3s cubic-bezier(.23, 1, .32, 1);
  }

  .primary-btn {
    padding: 0 26px;
    color: var(--white);
    background: linear-gradient(135deg, var(--cyan), #2563eb);
    box-shadow: 0 16px 30px rgba(6,182,212,.22);
  }

  .primary-btn:hover {
    color: var(--white);
    background: #0891b2;
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 20px 36px rgba(6,182,212,.35);
  }

  .secondary-btn {
    padding: 0 22px;
    color: #e2e8f0;
    border: 1px solid rgba(255,255,255,.18);
    background: rgba(255,255,255,.04);
  }

  .secondary-btn:hover {
    color: var(--white);
    border-color: rgba(255,255,255,.32);
    transform: translateY(-3px);
  }

  /* Evalia-style hero */
  .hero {
    padding: 148px 0 92px;
    color: var(--white);
    background:
      linear-gradient(90deg, rgba(2,6,23,.94) 0%, rgba(2,6,23,.82) 44%, rgba(2,6,23,.46) 100%),
      linear-gradient(180deg, rgba(2,6,23,.08), rgba(2,6,23,.34)),
      url("https://learn.g2.com/hubfs/Imported%20sitepage%20images/1ZB5giUShe0gw9a6L69qAgsd7wKTQ60ZRoJC5Xq3BIXS517sL6i6mnkAN9khqnaIGzE6FASAusRr7w=w1439-h786.png") center / cover no-repeat;
    overflow: hidden;
    position: relative;
  }

  .hero-noise {
    position: absolute;
    inset: 0;
    opacity: .018;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
    background-size: 180px;
    pointer-events: none;
    z-index: 1;
  }

  .hero-grid-bg {
    position: absolute;
    inset: 0;
    background-image:
      linear-gradient(rgba(255,255,255,.035) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255,255,255,.035) 1px, transparent 1px);
    background-size: 72px 72px;
    mask-image: linear-gradient(180deg, black 0%, transparent 78%);
    -webkit-mask-image: linear-gradient(180deg, black 0%, transparent 78%);
    pointer-events: none;
    z-index: 1;
  }

  .hero-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(90px);
    pointer-events: none;
  }

  .hero-orb--1 {
    width: 520px;
    height: 520px;
    top: -22%;
    right: -12%;
    background: radial-gradient(circle, rgba(6,182,212,.12), transparent 68%);
  }

  .hero-orb--2 {
    width: 420px;
    height: 420px;
    bottom: -28%;
    left: -12%;
    background: radial-gradient(circle, rgba(245,158,11,.08), transparent 70%);
  }

  .hero-grid {
    display: grid;
    grid-template-columns: minmax(0,.9fr) minmax(420px,1.1fr);
    align-items: center;
    gap: 64px;
    position: relative;
    z-index: 2;
  }

  .hero-content { position: relative; z-index: 2; }

  .eyebrow {
    margin-bottom: 20px;
    color: #67e8f9;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .14em;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    border: 1px solid rgba(103,232,249,.24);
    border-radius: 999px;
    padding: 8px 12px;
    background: rgba(255,255,255,.08);
    box-shadow: 0 10px 30px rgba(2,6,23,.18);
  }

  .eyebrow::before {
    content: "";
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--cyan);
  }

  .hero h1 {
    max-width: 680px;
    font-size: clamp(38px,5vw,62px);
    line-height: 1.06;
    letter-spacing: 0;
    font-weight: 900;
    color: var(--white);
    opacity: 0;
    animation: heroRevealUp .7s ease .2s forwards;
  }

  .hero h1 .highlight {
    background: linear-gradient(135deg, #0891b2, #2563eb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .hero-copy {
    max-width: 590px;
    margin-top: 22px;
    color: #cbd5e1;
    font-size: 18px;
    line-height: 1.7;
    opacity: 0;
    animation: heroRevealUp .7s ease .34s forwards;
  }

  .hero-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    margin-top: 32px;
    opacity: 0;
    animation: heroRevealUp .7s ease .46s forwards;
  }

  @keyframes heroRevealUp {
    0% { opacity: 0; transform: translateY(18px); }
    100% { opacity: 1; transform: translateY(0); }
  }

  /* Evalia-style dashboard mock */
  .dashboard-wrapper {
    position: relative;
    opacity: 0;
    animation: heroRevealUp .8s ease .32s forwards;
  }

  .dashboard-wrapper::before {
    content: "";
    position: absolute;
    inset: 22px -18px -18px 26px;
    border-radius: 28px;
    background: rgba(6,182,212,.08);
    z-index: -1;
    border: 1px solid rgba(6,182,212,.12);
  }

  .dashboard-wrapper::after {
    content: "";
    position: absolute;
    inset: auto 8% -34px 8%;
    height: 42px;
    border-radius: 50%;
    background: rgba(15,23,42,.16);
    filter: blur(26px);
    z-index: -2;
  }

  .dashboard-shell {
    border-radius: 24px;
    border: 1px solid rgba(15,23,42,.1);
    background: rgba(255,255,255,.78);
    padding: 12px;
    box-shadow:
      0 32px 70px rgba(15,23,42,.15),
      inset 0 1px 0 rgba(255,255,255,.7);
  }

  .dashboard {
    border-radius: 18px;
    border: 1px solid rgba(15,23,42,.08);
    background: #fff;
    padding: 22px;
    color: var(--navy-2);
    position: relative;
    overflow: hidden;
  }

  .dashboard-head {
    display: flex;
    justify-content: space-between;
    gap: 18px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--line);
  }

  .dashboard-head h2 { font-size: 16px; font-weight: 900; }
  .dashboard-head p { margin-top: 5px; color: var(--slate-2); font-size: 12px; }

  .status {
    height: 27px;
    white-space: nowrap;
    border-radius: 999px;
    padding: 6px 12px;
    color: #0e7490;
    background: #ecfeff;
    font-size: 12px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .status::before {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--cyan);
    animation: statusPulse 1.2s ease-in-out infinite;
  }

  @keyframes statusPulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: .2; transform: scale(.6); }
  }

  .metric-grid {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 12px;
    margin-top: 18px;
  }

  .metric {
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 16px;
    background: #f8fafc;
    transition: all .3s;
  }

  .metric:hover {
    background: #fff;
    transform: translateY(-4px);
    box-shadow: 0 12px 26px rgba(15,23,42,.08);
  }

  .metric span { color: var(--slate-2); font-size: 12px; font-weight: 700; }
  .metric strong { display: block; margin-top: 9px; font-size: 30px; line-height: 1; font-weight: 900; }
  .metric:hover strong { color: var(--cyan-2); }

  .report-grid {
    display: grid;
    grid-template-columns: 1.1fr .9fr;
    gap: 16px;
    margin-top: 16px;
  }

  .transcript,
  .risk-card,
  .coach-card {
    border-radius: 12px;
    padding: 16px;
    background: #f8fafc;
    transition: all .3s;
  }

  .transcript:hover,
  .risk-card:hover,
  .coach-card:hover {
    background: #fff;
    transform: translateY(-3px);
  }

  .transcript-title {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 14px;
    color: var(--slate-2);
    font-size: 11px;
    font-weight: 900;
    letter-spacing: .1em;
    text-transform: uppercase;
  }

  .line {
    margin-top: 10px;
    border: 1px solid var(--line);
    border-radius: 10px;
    padding: 12px;
    background: #fff;
    transition: all .3s;
  }

  .line:hover {
    border-color: rgba(6,182,212,.3);
    background: #ecfeff;
  }

  .line.warning {
    border-color: rgba(245,158,11,.35);
    background: rgba(245,158,11,.11);
    animation: warningGlow 2s ease-in-out infinite;
  }

  @keyframes warningGlow {
    0%, 100% { border-color: rgba(245,158,11,.35); }
    50% { border-color: rgba(245,158,11,.7); }
  }

  .speaker { display: block; margin-bottom: 4px; color: var(--slate-2); font-size: 11px; font-weight: 800; }
  .line p { color: #334155; font-size: 13px; line-height: 1.65; }
  .side-stack { display: grid; gap: 16px; }
  .risk-card { border: 1px solid rgba(251,113,133,.25); background: #fff1f2; }
  .coach-card { border: 1px solid rgba(16,185,129,.25); background: #ecfdf5; }
  .risk-card h3,
  .coach-card h3 { margin-bottom: 8px; font-size: 14px; }
  .risk-card h3 { color: #be123c; }
  .coach-card h3 { color: #047857; }
  .risk-card p,
  .coach-card p { color: #334155; font-size: 13px; line-height: 1.65; }

  .floating-badge {
    position: absolute;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 700;
    color: var(--white);
    background: rgba(15,23,42,.8);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,.1);
    box-shadow: 0 12px 32px rgba(0,0,0,.3);
    z-index: 5;
    white-space: nowrap;
    animation: badgeFloat 3.5s ease-in-out infinite;
  }

  .floating-badge--score { top: 8%; right: -12px; }
  .floating-badge--risk { bottom: 22%; left: -18px; animation-delay: .5s; }
  .floating-badge--coach { bottom: 5%; right: 5%; animation-delay: 1s; }
  @keyframes badgeFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
  .badge-dot { width: 8px; height: 8px; border-radius: 50%; }
  .badge-dot--green { background: var(--emerald); box-shadow: 0 0 10px rgba(16,185,129,.5); }
  .badge-dot--rose { background: var(--rose); box-shadow: 0 0 10px rgba(251,113,133,.5); }
  .badge-dot--cyan { background: var(--cyan); box-shadow: 0 0 10px rgba(6,182,212,.5); }

  .section { padding: 88px 0; position: relative; }
  .section.soft { border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); background: var(--soft); }
  .section.dark { color: var(--white); background: #020617; }
  .section-heading { max-width: 700px; margin-bottom: 48px; }
  .section-heading.center { margin-left: auto; margin-right: auto; text-align: center; }
  .section-kicker {
    margin: 0 0 18px;
    color: var(--cyan);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .22em;
    text-transform: uppercase;
  }
  .section-heading h2,
  .solo-heading,
  .cta-gateway h2 {
    margin: 0;
    font-size: clamp(28px,4vw,46px);
    line-height: 1.1;
    letter-spacing: -.035em;
    font-weight: 800;
  }
  .section-heading p,
  .cta-gateway p {
    margin: 16px 0 0;
    color: var(--slate);
    font-size: 16px;
    line-height: 1.7;
  }
  .section.dark .section-heading p { color: #94a3b8; }

  .how-split {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: center;
    margin-top: 52px;
  }
  .how-visual-inner {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    background: linear-gradient(145deg, #f0fdfa, #ecfeff);
    border: 1px solid var(--line);
    aspect-ratio: 4/3;
  }
  .how-hero-img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .how-float-card {
    position: absolute;
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--white);
    border: 1px solid var(--line);
    border-radius: 10px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 600;
    color: var(--navy-2);
    box-shadow: 0 4px 16px rgba(0,0,0,.06);
    animation: floatBounce 3s ease-in-out infinite;
  }
  .how-float-card--top { top: 20px; right: 20px; }
  .how-float-card--bottom { bottom: 20px; left: 20px; animation-delay: 1s; }
  @keyframes floatBounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-4px); } }
  .how-float-dot { width: 8px; height: 8px; border-radius: 50%; background: #ef4444; }
  .how-steps { display: flex; flex-direction: column; gap: 0; }
  .how-step { display: flex; gap: 20px; padding: 20px 0; border-bottom: 1px solid var(--line); }
  .how-step:last-child { border-bottom: none; }
  .how-step-circle {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
  }
  .how-step-left { position: relative; flex-shrink: 0; }
  .how-step-num {
    position: absolute;
    top: -6px;
    right: -6px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: var(--white);
    border: 2px solid var(--line);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 800;
  }
  .how-step-text h3 { font-size: 17px; font-weight: 700; letter-spacing: -.01em; }
  .how-step-text p { margin-top: 4px; color: var(--slate); font-size: 14px; line-height: 1.65; }

  #report { background: linear-gradient(180deg, #fff 0%, #f8fafc 100%); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); }
  #report .section-heading { margin-bottom: 34px; }
  .inside-grid {
    display: grid;
    grid-template-columns: minmax(0,1.25fr) repeat(2,minmax(0,.8fr));
    gap: 16px;
    position: relative;
    padding: 18px;
    border: 1px solid rgba(15,23,42,.08);
    border-radius: 20px;
    background: rgba(255,255,255,.72);
    box-shadow: 0 24px 70px rgba(15,23,42,.08);
  }
  .summary-card,
  .stat-card {
    position: relative;
    z-index: 1;
    border: 1px solid var(--line);
    border-radius: 12px;
    padding: 26px;
    background: var(--white);
    transition: all .3s ease;
  }
  .summary-card:hover,
  .stat-card:hover { transform: translateY(-3px); box-shadow: 0 16px 34px rgba(15,23,42,.08); }
  .summary-card {
    grid-row: span 2;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 330px;
    color: var(--white);
    border-color: rgba(15,23,42,.12);
    background: linear-gradient(135deg, rgba(8,145,178,.92), rgba(15,23,42,.96)), #0f172a;
    overflow: hidden;
  }
  .label { color: var(--slate-2); font-size: 11px; font-weight: 800; letter-spacing: .11em; text-transform: uppercase; }
  .summary-card .label { color: #a5f3fc; }
  .summary-card h3 { margin-top: 18px; max-width: 430px; font-size: 28px; line-height: 1.16; font-weight: 800; }
  .summary-card p:not(.label) { margin-top: 10px; color: #cbd5e1; font-size: 14px; line-height: 1.7; }
  .recommendation { margin-top: 22px; border: 1px solid rgba(255,255,255,.12); border-left: 3px solid var(--cyan-2); border-radius: 10px; padding: 16px 18px; background: rgba(255,255,255,.08); }
  .recommendation b { display: block; color: #67e8f9; font-size: 12px; margin-bottom: 7px; }
  .recommendation span { color: #fff; font-size: 13px; font-weight: 700; line-height: 1.6; }
  .stat-card strong { display: block; margin-top: 18px; color: var(--navy-2); font-size: 34px; line-height: 1; font-weight: 800; }
  .stat-card p:not(.label) { margin-top: 10px; color: var(--slate); font-size: 13px; line-height: 1.7; }

  .dark-heading { text-align: center; margin-bottom: 52px; }
  .dark-heading h2 { font-size: clamp(26px,3.5vw,40px); color: #fff; }
  .output-flow { display: grid; grid-template-columns: 1fr auto 1fr auto 1fr; gap: 0; align-items: stretch; }
  .output-card {
    border: 1px solid rgba(255,255,255,.06);
    border-radius: 16px;
    padding: 32px 28px 28px;
    background: rgba(255,255,255,.02);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    transition: all .35s cubic-bezier(.23,1,.32,1);
  }
  .output-card:hover { background: rgba(255,255,255,.04); border-color: rgba(6,182,212,.15); transform: translateY(-4px); }
  .output-step-label { color: var(--slate-2); font-size: 11px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
  .output-step-num { display: inline-grid; place-items: center; width: 28px; height: 28px; margin-right: 8px; border-radius: 8px; color: #67e8f9; background: rgba(6,182,212,.12); font-size: 12px; font-weight: 800; }
  .output-card h3 { margin-top: 20px; color: #fff; font-size: 19px; line-height: 1.3; font-weight: 800; }
  .output-card p { margin-top: 10px; color: #94a3b8; font-size: 13.5px; line-height: 1.75; }
  .flow-arrow { display: flex; align-items: center; justify-content: center; padding: 0 16px; color: rgba(255,255,255,.3); font-size: 28px; }

  .teams-section {
    position: relative;
    padding: 120px 0 140px;
    background: #f1f5f9;
    overflow: hidden;
  }
  .teams-section::before {
    content: "";
    position: absolute;
    inset: 0;
    background:
      radial-gradient(ellipse 50% 40% at 16% 50%, rgba(6,182,212,.08), transparent),
      radial-gradient(ellipse 50% 40% at 50% 50%, rgba(139,92,246,.06), transparent),
      radial-gradient(ellipse 50% 40% at 84% 50%, rgba(251,191,36,.06), transparent);
    pointer-events: none;
  }
  .teams-section .container { position: relative; z-index: 2; }
  .teams-section .section-heading { text-align: center; margin-bottom: 80px; }
  .portal-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 24px; }
  .portal {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    transition: transform .5s cubic-bezier(.23,1,.32,1), box-shadow .5s ease;
    background: #fff;
    border: 1.5px solid #cbd5e1;
  }
  .portal:hover { transform: translateY(-8px); box-shadow: 0 24px 64px -12px rgba(0,0,0,.15); }
  .portal-bg { position: absolute; inset: 0; z-index: 0; }
  .portal-content { position: relative; z-index: 3; padding: 36px 32px 32px; display: flex; flex-direction: column; min-height: 440px; }
  .portal-icon-wrap {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    display: grid;
    place-items: center;
    margin-bottom: 20px;
    font-size: 18px;
    font-weight: 900;
  }
  .portal-tag { display: inline-block; margin-bottom: 14px; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
  .portal h3 { font-size: 22px; font-weight: 700; line-height: 1.2; margin-bottom: 10px; color: #0f172a; }
  .portal p { font-size: 14px; line-height: 1.7; max-width: 320px; color: var(--slate); }
  .portal-features { list-style: none; margin: 20px 0 0; padding: 0; display: flex; flex-direction: column; gap: 12px; }
  .portal-features li { display: flex; align-items: flex-start; gap: 10px; font-size: 13px; line-height: 1.5; color: #334155; }
  .portal-features li::before { content: ""; width: 16px; height: 16px; flex: 0 0 16px; margin-top: 2px; border-radius: 50%; background: currentColor; mask: radial-gradient(circle at 50% 50%, #000 0 45%, transparent 46%); -webkit-mask: radial-gradient(circle at 50% 50%, #000 0 45%, transparent 46%); }
  .portal-stats-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: auto; padding-top: 20px; border-top: 1px solid var(--line); }
  .portal-mini-stat strong { display: block; font-size: 22px; font-weight: 700; letter-spacing: -.02em; line-height: 1; }
  .portal-mini-stat small { display: block; margin-top: 4px; font-size: 11px; font-weight: 600; color: var(--slate-2); text-transform: uppercase; letter-spacing: .04em; }
  .portal--vault { background: linear-gradient(180deg, #e0f7fa 0%, #fff 100%); border-color: #99f6e4; }
  .portal--vault .portal-bg { background: radial-gradient(circle at 70% 20%, rgba(6,182,212,.12), transparent 50%), radial-gradient(circle at 30% 80%, rgba(6,182,212,.06), transparent 40%); }
  .portal--vault .portal-icon-wrap { background: #cffafe; border: 1px solid #67e8f9; color: #0891b2; }
  .portal--vault .portal-tag { color: #0891b2; background: #cffafe; border: 1px solid #67e8f9; }
  .portal--vault .portal-mini-stat strong,
  .portal--vault .portal-features li { color: #0891b2; }
  .portal--grove { background: linear-gradient(180deg, #f0e6ff 0%, #fff 100%); border-color: #d8b4fe; }
  .portal--grove .portal-bg { background: radial-gradient(circle at 40% 30%, rgba(139,92,246,.1), transparent 50%), radial-gradient(circle at 70% 70%, rgba(168,85,247,.06), transparent 40%); }
  .portal--grove .portal-icon-wrap { background: #ede9fe; border: 1px solid #c4b5fd; color: #7c3aed; }
  .portal--grove .portal-tag { color: #7c3aed; background: #ede9fe; border: 1px solid #c4b5fd; }
  .portal--grove .portal-mini-stat strong,
  .portal--grove .portal-features li { color: #7c3aed; }
  .portal--tide { background: linear-gradient(180deg, #fef3c7 0%, #fff 100%); border-color: #fcd34d; }
  .portal--tide .portal-bg { background: radial-gradient(circle at 60% 40%, rgba(251,191,36,.1), transparent 50%), radial-gradient(circle at 20% 80%, rgba(245,158,11,.06), transparent 40%); }
  .portal--tide .portal-icon-wrap { background: #fef9c3; border: 1px solid #fde047; color: #d97706; }
  .portal--tide .portal-tag { color: #d97706; background: #fef9c3; border: 1px solid #fde047; }
  .portal--tide .portal-mini-stat strong,
  .portal--tide .portal-features li { color: #d97706; }
  .portal-features li span { color: #334155; }

  .cta-gateway {
    position: relative;
    padding: 86px 0 96px;
    text-align: center;
    background:
      linear-gradient(135deg, rgba(2,6,23,.97), rgba(15,23,42,.94)),
      url("{{ asset('hero-dashboard.png') }}") center / cover no-repeat;
    overflow: hidden;
  }
  .cta-orb { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: min(760px, 90vw); height: 260px; border-radius: 999px; background: radial-gradient(ellipse, rgba(6,182,212,.13) 0%, transparent 68%); filter: blur(46px); pointer-events: none; }
  .cta-gateway .container { position: relative; z-index: 2; }
  .cta-gateway h2 { color: #fff; max-width: 680px; margin: 0 auto; }
  .cta-gateway h2 .cta-gradient { background: linear-gradient(135deg, #67e8f9, #38bdf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
  .cta-gateway p { max-width: 520px; margin: 18px auto 0; color: #cbd5e1; }
  .cta-gateway-actions { display: flex; align-items: center; justify-content: center; gap: 14px; margin-top: 30px; }
  .cta-trust { display: flex; align-items: center; justify-content: center; gap: 22px; margin-top: 34px; color: #94a3b8; font-size: 12px; font-weight: 600; }
  .cta-trust-item { display: flex; align-items: center; gap: 8px; }
  .cta-trust-dot { width: 6px; height: 6px; border-radius: 50%; background: #22c55e; box-shadow: 0 0 8px rgba(34,197,94,.5); }

  [data-reveal] { opacity: 0; transform: translateY(28px); transition: opacity .6s ease, transform .6s ease; }
  [data-reveal].revealed { opacity: 1; transform: translateY(0); }
  [data-reveal="left"] { transform: translateX(-28px); }
  [data-reveal="left"].revealed { transform: translateX(0); }
  [data-reveal="scale"] { transform: scale(.96); }
  [data-reveal="scale"].revealed { transform: scale(1); }

  @media (max-width: 980px) {
    .hero-grid,
    .inside-grid,
    .how-split { grid-template-columns: 1fr; }
    .dashboard-shell { max-width: 720px; }
    .floating-badge { display: none; }
    .portal-grid { grid-template-columns: repeat(2,1fr); gap: 16px; }
    .output-flow { grid-template-columns: 1fr; gap: 16px; }
    .flow-arrow { display: none; }
  }

  @media (max-width: 680px) {
    .container { width: min(100% - 28px, 1180px); }
    .hero { padding: 108px 0 58px; }
    .hero-orb { display: none; }
    .hero h1 { font-size: 38px; }
    .hero-copy,
    .section-heading p,
    .cta-gateway p { font-size: 15px; }
    .hero-actions,
    .cta-gateway-actions,
    .cta-trust { flex-direction: column; align-items: stretch; }
    .primary-btn,
    .secondary-btn { width: 100%; }
    .dashboard-shell { padding: 10px; border-radius: 20px; }
    .dashboard { padding: 16px; border-radius: 16px; }
    .dashboard-head { flex-direction: column; align-items: flex-start; }
    .metric-grid,
    .report-grid,
    .portal-grid { grid-template-columns: 1fr; gap: 16px; }
    .section { padding: 66px 0; }
    .teams-section { padding: 72px 0; }
    .inside-grid { padding: 0; border: 0; background: transparent; box-shadow: none; }
    .summary-card { grid-row: auto; min-height: auto; }
  }

  @media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
      animation-duration: .01ms !important;
      animation-iteration-count: 1 !important;
      transition-duration: .01ms !important;
    }
    [data-reveal] { opacity: 1; transform: none; }
  }
</style>
@endsection

@section('content')
<div class="landing-page">
  <section class="hero">
    <div class="hero-noise"></div>
    <div class="hero-grid-bg"></div>
    <div class="hero-orb hero-orb--1"></div>
    <div class="hero-orb hero-orb--2"></div>

    <div class="container hero-grid">
      <div class="hero-content">
        <p class="eyebrow">Project tracking dashboard</p>
        <h1>Track projects, tools, and <span class="highlight">team growth.</span></h1>
        <p class="hero-copy">Daleel AI connects daily work activity with learning progress, so managers can spot blockers, measure adoption, and recommend the right AI workflows.</p>
        <div class="hero-actions">
          @auth
            <a class="primary-btn" href="{{ route('dashboard') }}">Open Dashboard</a>
          @else
            <a class="primary-btn" href="{{ route('register') }}">Get Started</a>
            <a class="secondary-btn" href="{{ route('login') }}">Login</a>
          @endauth
        </div>
      </div>

      <div class="dashboard-wrapper">
        <div class="dashboard-shell" aria-label="Project tracking dashboard preview">
          <div class="dashboard">
            <div class="dashboard-head">
              <div>
                <h2>Project Intelligence Report</h2>
                <p>Operations team - AI adoption - This week</p>
              </div>
              <span class="status">Insights ready</span>
            </div>

            <div class="metric-grid">
              <div class="metric"><span>Tasks mapped</span><strong>128</strong></div>
              <div class="metric"><span>Time saved</span><strong>34h</strong></div>
              <div class="metric"><span>Adoption</span><strong>82%</strong></div>
            </div>

            <div class="report-grid">
              <div class="transcript">
                <div class="transcript-title"><span>Tracked workflow</span><span>Status</span></div>
                <div class="line"><span class="speaker">Workflow</span><p>Weekly reporting workflow uses Sheets, Slack, and ChatGPT.</p></div>
                <div class="line warning"><span class="speaker">Signal</span><p>Manual handoff detected across repeated updates.</p></div>
              </div>
              <div class="side-stack">
                <div class="risk-card"><h3>Focus signal</h3><p>Context switching is slowing project reporting.</p></div>
                <div class="coach-card"><h3>Recommended lesson</h3><p>Automate status summaries with a prompt kit.</p></div>
              </div>
            </div>
          </div>
        </div>

        <div class="floating-badge floating-badge--score"><span class="badge-dot badge-dot--green"></span>82% adoption</div>
        <div class="floating-badge floating-badge--risk"><span class="badge-dot badge-dot--rose"></span>Blocker found</div>
        <div class="floating-badge floating-badge--coach"><span class="badge-dot badge-dot--cyan"></span>34h saved</div>
      </div>
    </div>
  </section>

  <section class="section soft" id="how">
    <div class="container">
      <div class="section-heading center" data-reveal>
        <p class="section-kicker">How it works</p>
        <h2>See work progress clearly.</h2>
      </div>

      <div class="how-split" data-reveal>
        <div class="how-visual">
          <div class="how-visual-inner">
            <img src="{{ asset('images/dashboard/hero.png') }}" alt="Daleel dashboard" class="how-hero-img" loading="lazy">
            <div class="how-float-card how-float-card--top"><span class="how-float-dot"></span>Activity tracking</div>
            <div class="how-float-card how-float-card--bottom"><i class="bi bi-check-circle-fill text-success"></i> Learning signal: 76%</div>
          </div>
        </div>

        <div class="how-steps">
          <article class="how-step">
            <div class="how-step-left"><div class="how-step-circle" style="background: linear-gradient(135deg, #06b6d4, #22d3ee);"><i class="bi bi-browser-chrome text-white"></i></div><span class="how-step-num">1</span></div>
            <div class="how-step-text"><h3>Track</h3><p>Capture work activity from the tools your team already uses.</p></div>
          </article>
          <article class="how-step">
            <div class="how-step-left"><div class="how-step-circle" style="background: linear-gradient(135deg, #7c3aed, #a78bfa);"><i class="bi bi-diagram-3 text-white"></i></div><span class="how-step-num">2</span></div>
            <div class="how-step-text"><h3>Understand</h3><p>Map repeated tasks, project flow, and workflow friction.</p></div>
          </article>
          <article class="how-step">
            <div class="how-step-left"><div class="how-step-circle" style="background: linear-gradient(135deg, #059669, #34d399);"><i class="bi bi-lightning-charge text-white"></i></div><span class="how-step-num">3</span></div>
            <div class="how-step-text"><h3>Recommend</h3><p>Suggest lessons, prompt kits, and practical AI workflows.</p></div>
          </article>
          <article class="how-step">
            <div class="how-step-left"><div class="how-step-circle" style="background: linear-gradient(135deg, #d97706, #fbbf24);"><i class="bi bi-graph-up-arrow text-white"></i></div><span class="how-step-num">4</span></div>
            <div class="how-step-text"><h3>Improve</h3><p>Measure adoption, time saved, learning progress, and team impact.</p></div>
          </article>
        </div>
      </div>
    </div>
  </section>

  <section class="section" id="report">
    <div class="container">
      <div class="section-heading" data-reveal="left">
        <p class="section-kicker">Inside the dashboard</p>
        <h2>The manager view for work patterns and learning progress.</h2>
        <p>Instead of asking for more manual reports, managers can see where work is happening, which tools are used, and which AI skills can improve the next sprint.</p>
      </div>

      <div class="inside-grid" data-reveal>
        <article class="summary-card">
          <p class="label">AI Summary</p>
          <h3>Project updates are taking longer because status work is repeated across multiple tools.</h3>
          <p>Daleel highlights the pattern, identifies the affected workflow, and recommends a short lesson to automate the recurring update process.</p>
          <div class="recommendation"><b>Recommended coaching</b><span>Automate status summaries with a prompt kit.</span></div>
        </article>
        <article class="stat-card"><p class="label">Active users</p><strong>42</strong><p>Team members with recent tracked work activity.</p></article>
        <article class="stat-card"><p class="label">Tool coverage</p><strong>8 apps</strong><p>Connected workplace tools and browser surfaces.</p></article>
        <article class="stat-card"><p class="label">Learning progress</p><strong>76%</strong><p>Average completion across assigned AI lessons.</p></article>
        <article class="stat-card"><p class="label">Opportunity</p><strong>12h</strong><p>Estimated weekly time that can be recovered.</p></article>
      </div>
    </div>
  </section>

  <section class="section dark">
    <div class="container">
      <div class="dark-heading" data-reveal>
        <p class="section-kicker">How Daleel works</p>
        <h2>From daily activity to measurable improvement.</h2>
      </div>
      <div class="output-flow" data-reveal="scale">
        <article class="output-card"><span class="output-step-num">01</span><span class="output-step-label">Input</span><h3>Daily activity</h3><p>Browser usage, repeated tasks, connected tools, help requests, and learning behavior.</p></article>
        <div class="flow-arrow"><i class="bi bi-arrow-right"></i></div>
        <article class="output-card"><span class="output-step-num">02</span><span class="output-step-label">AI</span><h3>Workflow intelligence</h3><p>Daleel detects friction, automation opportunities, and role-specific skill gaps.</p></article>
        <div class="flow-arrow"><i class="bi bi-arrow-right"></i></div>
        <article class="output-card"><span class="output-step-num">03</span><span class="output-step-label">Output</span><h3>Dashboard actions</h3><p>Managers get visibility, recommendations, and measurable improvement paths.</p></article>
      </div>
    </div>
  </section>

  <section class="teams-section" id="teams">
    <div class="container">
      <div class="section-heading center" data-reveal>
        <p class="section-kicker">Who it's for</p>
        <h2>Built for teams that need visibility without extra reporting work.</h2>
        <p>Use Daleel to connect project progress, learning, and tool adoption without adding another manual status process.</p>
      </div>

      <div class="portal-grid" data-reveal>
        <div class="portal portal--vault">
          <div class="portal-bg"></div>
          <div class="portal-content">
            <div class="portal-icon-wrap"><i class="bi bi-people"></i></div>
            <span class="portal-tag">Team visibility</span>
            <h3>Team leads</h3>
            <p>See blockers, adoption trends, and project activity before the weekly status meeting.</p>
            <ul class="portal-features"><li><span>Project activity signals</span></li><li><span>Blocker detection</span></li><li><span>Team adoption reporting</span></li></ul>
            <div class="portal-stats-row"><div class="portal-mini-stat"><strong>42</strong><small>Active users</small></div><div class="portal-mini-stat"><strong>12h</strong><small>Opportunity</small></div></div>
          </div>
        </div>
        <div class="portal portal--grove">
          <div class="portal-bg"></div>
          <div class="portal-content">
            <div class="portal-icon-wrap"><i class="bi bi-mortarboard"></i></div>
            <span class="portal-tag">Learning teams</span>
            <h3>Learning teams</h3>
            <p>Assign lessons based on the work employees actually perform every day.</p>
            <ul class="portal-features"><li><span>Role-based recommendations</span></li><li><span>Progress visibility</span></li><li><span>AI skill gap mapping</span></li></ul>
            <div class="portal-stats-row"><div class="portal-mini-stat"><strong>76%</strong><small>Progress</small></div><div class="portal-mini-stat"><strong>82%</strong><small>Adoption</small></div></div>
          </div>
        </div>
        <div class="portal portal--tide">
          <div class="portal-bg"></div>
          <div class="portal-content">
            <div class="portal-icon-wrap"><i class="bi bi-building"></i></div>
            <span class="portal-tag">Operations</span>
            <h3>Operations</h3>
            <p>Connect tool usage, productivity signals, and AI enablement in one place.</p>
            <ul class="portal-features"><li><span>Tool coverage reporting</span></li><li><span>Productivity pattern analysis</span></li><li><span>Workflow improvement tracking</span></li></ul>
            <div class="portal-stats-row"><div class="portal-mini-stat"><strong>8</strong><small>Apps</small></div><div class="portal-mini-stat"><strong>34h</strong><small>Saved</small></div></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="cta-gateway">
    <div class="cta-orb"></div>
    <div class="container">
      <h2>Start tracking project work with <span class="cta-gradient">Daleel AI.</span></h2>
      <p>Open your dashboard or create an account to connect learning, tool usage, and project progress.</p>
      <div class="cta-gateway-actions">
        @auth
          <a class="primary-btn" href="{{ route('dashboard') }}">Open Dashboard</a>
        @else
          <a class="primary-btn" href="{{ route('register') }}">Get Started</a>
          <a class="secondary-btn" href="{{ route('login') }}">Login</a>
        @endauth
      </div>
      <div class="cta-trust">
        <span class="cta-trust-item"><span class="cta-trust-dot"></span>AI-powered recommendations</span>
        <span class="cta-trust-item"><span class="cta-trust-dot"></span>No extra reporting work</span>
        <span class="cta-trust-item"><span class="cta-trust-dot"></span>Works with daily tools</span>
      </div>
    </div>
  </section>
</div>
@endsection

@section('scripts')
<script>
(function () {
  var revealItems = document.querySelectorAll('[data-reveal]');

  if ('IntersectionObserver' in window) {
    var revealObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.18 });

    revealItems.forEach(function (item) { revealObserver.observe(item); });
  } else {
    revealItems.forEach(function (item) { item.classList.add('revealed'); });
  }
})();
</script>
@endsection
