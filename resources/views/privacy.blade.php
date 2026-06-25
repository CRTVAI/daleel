@extends('layouts.public')

@section('title', 'Daleel AI | Privacy Policy')

@section('content')
<section class="page-hero">
  <div class="container hero-grid">
    <div>
      <p class="eyebrow">Legal</p>
      <h1>Privacy Policy</h1>
      <p class="lead">Sample legal content for the static public website. Replace with reviewed production policy text before launch.</p>
      <div class="hero-actions">
        <a class="btn primary" href="{{ url('contact') }}">Contact</a>
        <a class="btn secondary" href="{{ url('/') }}">Back Home</a>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="card card-body">
      <h3>1. Scope</h3><p class="muted">Daleel AI should collect only the information needed to personalize learning, operate accounts, support teams, and improve recommendations.</p>
      <h3>2. Responsibilities</h3><p class="muted">Optional workflow analysis should focus on behavior signals such as tools used, repeated tasks, and time allocation, not private content.</p>
      <h3>3. Data and usage</h3><p class="muted">Companies should configure employee analytics transparently and explain how progress, adoption, and productivity insights are used.</p>
      <h3>4. Updates</h3><p class="muted">Users should be able to request access, correction, deletion, or export of personal data according to the final production policy.</p>
    </div>
  </div>
</section>
@endsection
