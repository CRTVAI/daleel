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
      <div class="form-message" id="formMsg">Thanks. This demo form is ready for backend integration.</div>
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
<script>
document.querySelector("[data-demo-form]").addEventListener("submit", event => {
  event.preventDefault();
  document.getElementById("formMsg").classList.add("show");
  event.target.reset();
});
</script>
<style>
.form-message { display: none; margin-top: 16px; padding: 12px; background: #dcfce7; color: #166534; border-radius: 8px; }
.form-message.show { display: block; }
</style>
@endsection
