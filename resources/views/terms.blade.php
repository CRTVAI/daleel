@extends('layouts.public')

@section('title', 'Daleel AI | Terms of Service')

@section('content')
<section class="page-hero">
  <div class="container hero-grid">
    <div>
      <p class="eyebrow">Legal</p>
      <h1>Terms of Service</h1>
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
      <h3>1. Scope</h3><p class="muted">This sample page outlines expected public website terms for Daleel AI. Replace this copy with reviewed legal language before launch.</p>
      <h3>2. Responsibilities</h3><p class="muted">Users are responsible for using lessons, templates, and AI recommendations in accordance with their company policies and applicable laws.</p>
      <h3>3. Data and usage</h3><p class="muted">The platform may provide educational guidance, workflow suggestions, analytics, and AI mentor responses, but users remain responsible for final work decisions.</p>
      <h3>4. Updates</h3><p class="muted">Enterprise features, custom content, and support obligations should be governed by a signed agreement or order form.</p>
    </div>
  </div>
</section>
@endsection
