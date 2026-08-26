@extends('layouts.public')

@section('title', 'Daleel AI | Contact')

@section('content')
<section class="page-hero">
  <div class="container hero-grid">
    <div>
      <p class="eyebrow">Contact</p>
      <h1>Book a demo or talk to the Daleel AI team</h1>
      <p class="lead">Tell us about your company, training goals, and AI adoption needs.</p>
      <div class="hero-actions">
        <a class="btn primary" href="{{ route('register') }}">Start Free</a>
        <a class="btn secondary" href="{{ url('pricing') }}">View Pricing</a>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container grid two">
    <form class="form-panel card-body" method="POST" action="{{ route('contact.send') }}">
      @csrf
      <p class="eyebrow">Demo request</p>
      <h2>Request enterprise contact</h2>
      @if (session('enterprise_contact_error'))
        <div class="form-message show error">{{ session('enterprise_contact_error') }}</div>
      @endif
      @if (session('enterprise_contact_success'))
        <div class="form-message show">Thanks. Your request has been sent.</div>
      @endif
      @if ($errors->any())
        <div class="form-message show error">{{ $errors->first() }}</div>
      @endif
      <div class="form-grid">
        <div class="field"><label for="name">Name</label><input id="name" name="name" value="{{ old('name') }}" required placeholder="Maya Hassan"></div>
        <div class="field"><label for="email">Work email</label><input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="maya@company.com"></div>
        <div class="field"><label for="company">Company</label><input id="company" name="company" value="{{ old('company') }}" required placeholder="Acme Services"></div>
        <div class="field"><label for="size">Company size</label><select id="size" name="size"><option>1-50</option><option>51-250</option><option>251-1000</option><option>1000+</option></select></div>
        <div class="field full"><label for="goal">Training goal</label><textarea id="goal" name="goal" required placeholder="We want to train customer support and operations teams on AI workflows.">{{ old('goal') }}</textarea></div>
      </div>
      <button class="btn primary full" type="submit">Send Request</button>
    </form>
    <div>
      <p class="eyebrow">Company details</p>
      <h2>Public profile contact points</h2>
      <div class="grid">
        <article class="card card-body"><span class="mini-label">Feature</span><h3>Sales</h3><p class="muted">For team training, enterprise pilots, and custom AI learning paths.</p></article>
        <article class="card card-body"><span class="mini-label">Feature</span><h3>Partnerships</h3><p class="muted">For AI ecosystem partners, agencies, universities, and innovation programs.</p></article>
        <article class="card card-body"><span class="mini-label">Feature</span><h3>Support</h3><p class="muted">For account questions, content requests, and learner help.</p></article>
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
<style>
.form-message { display: none; margin-top: 16px; padding: 12px; background: #dcfce7; color: #166534; border-radius: 8px; }
.form-message.show { display: block; }
.form-message.error { background: #fee2e2; color: #991b1b; }
</style>
@endsection
