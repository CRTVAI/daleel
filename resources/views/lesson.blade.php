@extends('layouts.public')

@section('title', 'Dallel AI | Lesson Preview')

@section('content')
<section class="section lesson-page">
  <div class="container lesson-layout" style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
    <div>
      <div class="video-player" style="aspect-ratio: 16/9; background: #000; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
        <span>Video lesson preview</span>
      </div>
      <div class="lesson-detail" style="margin-top: 24px;">
        <p class="eyebrow">Productivity lesson</p>
        <h1>How to Use ChatGPT for Daily Work</h1>
        <p class="lead">Learn a repeatable workflow for emails, meeting notes, reports, task planning, and faster knowledge work.</p>
        <div class="chip-row" style="display: flex; gap: 12px; margin-bottom: 24px;">
          <span class="chip">14 min</span>
          <span class="chip">18.4k views</span>
          <span class="chip">Beginner</span>
          <span class="chip">Certificate path</span>
        </div>
        <div class="inline-actions" style="display: flex; gap: 12px;">
          <a class="btn primary" href="{{ route('register') }}">Mark as Completed</a>
          <a class="btn secondary" href="{{ route('register') }}">Save Lesson</a>
        </div>
      </div>
      <section class="section compact-section" style="margin-top: 48px;">
        <h2>Related lessons</h2>
        <div class="grid three">
          <article class="card media-card lesson-card">
            <div class="thumb" style="background: #eee; aspect-ratio: 16/9; border-radius: 8px;"></div>
            <div class="card-body">
              <p class="mini-label">Automation</p>
              <h3>Automate Repetitive Tasks with AI</h3>
            </div>
          </article>
          <article class="card media-card lesson-card">
            <div class="thumb" style="background: #eee; aspect-ratio: 16/9; border-radius: 8px;"></div>
            <div class="card-body">
              <p class="mini-label">Support</p>
              <h3>AI for Customer Support Teams</h3>
            </div>
          </article>
          <article class="card media-card lesson-card">
            <div class="thumb" style="background: #eee; aspect-ratio: 16/9; border-radius: 8px;"></div>
            <div class="card-body">
              <p class="mini-label">Marketing</p>
              <h3>Build Better Prompts for Marketing</h3>
            </div>
          </article>
        </div>
      </section>
    </div>
    <aside class="chat-shell sticky-panel" style="background: #fff; border: 1px solid var(--line); border-radius: 12px; overflow: hidden; height: fit-content;">
      <div class="chat-head" style="padding: 16px; background: #f8fafc; border-bottom: 1px solid var(--line); font-weight: 800;">AI Mentor Q&A <span class="status" style="float: right; font-size: 0.7rem; color: #10b981;">Lesson aware</span></div>
      <div class="chat-lines" style="padding: 16px; display: flex; flex-direction: column; gap: 12px;">
        <div class="chat-bubble" style="background: #f1f5f9; padding: 10px 14px; border-radius: 12px 12px 12px 0; font-size: 0.85rem; width: fit-content;">How do I use this for weekly reports?</div>
        <div class="chat-bubble ai" style="background: #6366f1; color: #fff; padding: 10px 14px; border-radius: 12px 12px 0 12px; font-size: 0.85rem; width: fit-content; align-self: flex-end;">Start with a report template, paste your raw notes, ask for a summary, then ask for risks and next steps.</div>
        <div class="chat-bubble" style="background: #f1f5f9; padding: 10px 14px; border-radius: 12px 12px 12px 0; font-size: 0.85rem; width: fit-content;">Suggested next lesson?</div>
        <div class="chat-bubble ai" style="background: #6366f1; color: #fff; padding: 10px 14px; border-radius: 12px 12px 0 12px; font-size: 0.85rem; width: fit-content; align-self: flex-end;">Use AI to Summarize Meetings is the best next lesson.</div>
      </div>
    </aside>
  </div>
</section>
@endsection
