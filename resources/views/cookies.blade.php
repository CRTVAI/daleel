@extends('layouts.public')

@section('title', 'Daleel AI | Cookie Policy')

@section('content')
<section class="page-hero">
  <div class="container hero-grid">
    <div>
      <p class="eyebrow">Legal</p>
      <h1>Cookie Policy</h1>
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
      <h3>1. Scope</h3><p class="muted">This sample page describes how Daleel AI may use cookies or similar technologies for login, preferences, analytics, and product improvement.</p>
      <h3>2. Responsibilities</h3><p class="muted">Essential cookies support account access, security, and basic site function.</p>
      <h3>3. Data and usage</h3><p class="muted">Analytics cookies should help understand public website usage and improve content performance.</p>
      <h3>4. Updates</h3><p class="muted">Users should be able to manage cookie preferences once production tracking tools are selected.</p>
    </div>
  </div>
</section>
@endsection
