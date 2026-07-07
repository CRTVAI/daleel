<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Daleel AI')</title>
  <meta name="description" content="@yield('meta_description', $seo['meta_description'] ?? 'Browse our dynamic learning paths, video lessons, corporate training portals, and guides.')">
  <meta name="keywords" content="@yield('meta_keywords', $seo['meta_keywords'] ?? 'AI, learning roadmaps, tutorials, courses')">
  <link rel="canonical" href="@yield('canonical', $seo['canonical'] ?? request()->url())">
  
  <meta property="og:title" content="@yield('title', 'Daleel AI')">
  <meta property="og:description" content="@yield('meta_description', $seo['meta_description'] ?? 'Browse our dynamic learning paths, video lessons, corporate training portals, and guides.')">
  <meta property="og:image" content="@yield('og_image', $seo['og_image'] ?? asset('images/default-blog.jpg'))">
  <meta name="twitter:card" content="summary_large_image">

  <link rel="icon" type="image/png" href="{{ asset('images/dashboard/fav.png') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('site.css') }}">
  @yield('styles')
</head>
<body>
  <header class="site-nav glass">
    <div class="container nav-inner">
      <a class="brand" href="/" aria-label="Daleel AI home">
        <x-application-logo style="height: 36px; width: auto;" />
      </a>
      <nav class="nav-links" id="siteNavLinks" aria-label="Public pages">
        <a href="{{ url('how-it-works') }}" class="fw-700">How It Works</a>
        <a href="{{ url('videos') }}" class="fw-700">Lessons</a>
        <a href="{{ route('public.blog') }}" class="fw-700">Blog</a>
        <a href="{{ url('enterprise') }}" class="fw-700">Contact Us</a>
        <a href="{{ url('pricing') }}" class="fw-700">Pricing</a>
        <div class="nav-actions">
          <a class="btn secondary px-4" href="{{ route('login') }}" style="height: 42px; font-size: 0.85rem;">Login</a>
          <a class="btn primary px-4" href="{{ route('register') }}" style="height: 42px; font-size: 0.85rem;">Get Started</a>
        </div>
      </nav>
      <div class="nav-overlay" id="siteNavOverlay"></div>
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
          <a class="brand" href="/"><img src="{{ asset('images/dashboard/logo-white.png') }}"></a>
          <p>AI lessons matched to daily tasks, repeated work, and real workplace goals.</p>
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
          <a href="{{ url('contact') }}">hello@Daleel.ai</a>
          <a href="{{ url('terms') }}">Terms</a>
          <a href="{{ url('privacy') }}">Privacy Policy</a>
          <a href="{{ url('cookies') }}">Cookie Policy</a>
        </div>
      </div>
      <div class="footer-bottom">
        <span>Copyright 2026 Daleel AI by Creative AI. All rights reserved.</span>
        <span>LinkedIn | X | YouTube</span>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const toggle = document.getElementById("siteMenuToggle");
    const overlay = document.getElementById("siteNavOverlay");
    function toggleNav() {
      const isOpen = document.body.classList.toggle("nav-open");
      toggle.setAttribute("aria-expanded", isOpen);
    }
    if (toggle) {
      toggle.addEventListener("click", toggleNav);
    }
    if (overlay) {
      overlay.addEventListener("click", toggleNav);
    }
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && document.body.classList.contains("nav-open")) {
        toggleNav();
      }
    });
  </script>
  @yield('scripts')
</body>
</html>
