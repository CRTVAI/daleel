<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Dallel AI')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('site.css') }}">
  @yield('styles')
</head>
<body>
  <header class="site-nav">
    <div class="container nav-inner">
      <a class="brand" href="/" aria-label="Dallel AI home">
        <span class="brand-mark">DA</span>
        <span class="brand-copy"><strong>Dallel AI</strong><small>by Creative AI</small></span>
      </a>
      <nav class="nav-links" id="siteNavLinks" aria-label="Public pages">
        <a href="{{ url('how-it-works') }}">How It Works</a>
        <a href="{{ url('videos') }}">Lessons</a>
        <a href="{{ url('enterprise') }}">Enterprise</a>
        <a href="{{ url('success-stories') }}">Success Stories</a>
        <a href="{{ url('pricing') }}">Pricing</a>
      </nav>
      <div class="nav-actions">
        <a class="btn secondary" href="{{ route('login') }}">Login</a>
        <a class="btn primary" href="{{ route('register') }}">Start Free</a>
      </div>
      <button class="menu-toggle" id="siteMenuToggle" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="siteNavLinks">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>

  <main style="min-height: calc(100vh - 400px);">
    @yield('content')
  </main>

  <footer class="site-footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <a class="brand" href="/"><span class="brand-mark">DA</span><span class="brand-copy"><strong>Dallel AI</strong><small>by Creative AI</small></span></a>
          <p>AI lessons matched to daily tasks, repeated work, and real workplace goals.</p>
          <form class="newsletter">
            <input aria-label="Email for AI tips" placeholder="Get AI workflow tips">
            <button class="btn primary" type="submit">Join</button>
          </form>
        </div>
        <div>
          <h3>Product</h3>
          <a href="{{ url('how-it-works') }}">How It Works</a>
          <a href="{{ url('videos') }}">Video Lessons</a>
          <a href="{{ url('learning-paths') }}">Learning Paths</a>
          <a href="{{ url('chrome-extension') }}">Chrome Extension</a>
        </div>
        <div>
          <h3>Enterprise</h3>
          <a href="{{ url('enterprise') }}">Team Training</a>
          <a href="{{ url('enterprise#analytics') }}">Analytics</a>
          <a href="{{ url('success-stories') }}">Success Stories</a>
          <a href="{{ url('contact') }}">Book Demo</a>
        </div>
        <div>
          <h3>Resources</h3>
          <a href="{{ url('blog') }}">Blog</a>
          <a href="{{ url('tools-directory') }}">AI Tools Directory</a>
          <a href="{{ url('help-center') }}">Help Center</a>
          <a href="{{ url('ai-mentor') }}">AI Mentor</a>
        </div>
        <div>
          <h3>Company</h3>
          <a href="{{ url('about') }}">About</a>
          <a href="{{ url('pricing') }}">Pricing</a>
          <a href="{{ url('contact') }}">hello@dallel.ai</a>
          <a href="{{ url('terms') }}">Terms</a>
          <a href="{{ url('privacy') }}">Privacy Policy</a>
          <a href="{{ url('cookies') }}">Cookie Policy</a>
        </div>
      </div>
      <div class="footer-bottom">
        <span>Copyright 2026 Dallel AI by Creative AI. All rights reserved.</span>
        <span>LinkedIn | X | YouTube</span>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('site.js') }}"></script>
  <script>
    // Initialize standard nav logic since we are not using SPA for this page
    const toggle = document.getElementById("siteMenuToggle");
    if (toggle) {
        toggle.addEventListener("click", () => {
          document.body.classList.toggle("nav-open");
        });
    }
  </script>
  @yield('scripts')
</body>
</html>
