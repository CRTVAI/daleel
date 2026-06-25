@extends('layouts.public')

@section('title', 'Daleel AI | Success Stories')

@section('content')
<section class="page-hero">
  <div class="container hero-grid">
    <div>
      <p class="eyebrow">Proof of impact</p>
      <h1>Real results from teams applying AI at work</h1>
      <p class="lead">Explore public case-study previews by department and outcome.</p>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-header center mb-5">
      <p class="eyebrow">Impact Reports</p>
      <h2 class="display-6 fw-bold">Success Stories from Every Industry</h2>
    </div>
    <div class="grid three" id="storyResults">
      <article class="card card-body success-card">
        <div class="story-header">
          <span class="story-metric">200h</span>
          <p class="mini-label">Support</p>
        </div>
        <h3>Customer Support Team</h3>
        <div class="story-details">
          <p class="muted"><strong>Challenge:</strong> Repeated ticket responses slowed service quality.</p>
          <p class="muted"><strong>Impact:</strong> Saved 200+ hours monthly</p>
        </div>
      </article>
      <article class="card card-body success-card">
        <div class="story-header">
          <span class="story-metric">60%</span>
          <p class="mini-label">Marketing</p>
        </div>
        <h3>Marketing Department</h3>
        <div class="story-details">
          <p class="muted"><strong>Challenge:</strong> Campaign briefs and content variations took too long.</p>
          <p class="muted"><strong>Impact:</strong> Increased content speed by 60%</p>
        </div>
      </article>
      <article class="card card-body success-card">
        <div class="story-header">
          <span class="story-metric">40%</span>
          <p class="mini-label">Operations</p>
        </div>
        <h3>Operations Team</h3>
        <div class="story-details">
          <p class="muted"><strong>Challenge:</strong> Weekly reports required manual copy and summary work.</p>
          <p class="muted"><strong>Impact:</strong> Reduced manual work by 40%</p>
        </div>
      </article>
      <article class="card card-body success-card">
        <div class="story-header">
          <span class="story-metric">35%</span>
          <p class="mini-label">Sales</p>
        </div>
        <h3>Sales Enablement</h3>
        <div class="story-details">
          <p class="muted"><strong>Challenge:</strong> Reps prepared for calls inconsistently.</p>
          <p class="muted"><strong>Impact:</strong> Cut account prep time by 35%</p>
        </div>
      </article>
      <article class="card card-body success-card">
        <div class="story-header">
          <span class="story-metric">2x</span>
          <p class="mini-label">HR</p>
        </div>
        <h3>People Operations</h3>
        <div class="story-details">
          <p class="muted"><strong>Challenge:</strong> HR needed consistent onboarding materials across departments.</p>
          <p class="muted"><strong>Impact:</strong> Onboarding content created 2x faster</p>
        </div>
      </article>
      <article class="card card-body success-card">
        <div class="story-header">
          <span class="story-metric">28%</span>
          <p class="mini-label">Development</p>
        </div>
        <h3>Product Engineering</h3>
        <div class="story-details">
          <p class="muted"><strong>Challenge:</strong> Developers spent too much time preparing release notes and review context.</p>
          <p class="muted"><strong>Impact:</strong> Review prep time down 28%</p>
        </div>
      </article>
    </div>
  </div>
</section>
@endsection
