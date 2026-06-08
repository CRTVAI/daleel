<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dallel AI | AI Learning for Real Work</title>
  <link rel="stylesheet" href="{{ asset('site.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css">
  <style>
    /* === Video Modal Overlay === */
    #video-modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 9999;
      background: rgba(0, 0, 0, 0.92);
      backdrop-filter: blur(8px);
      align-items: center;
      justify-content: center;
      padding: 24px;
    }
    #video-modal-overlay.open {
      display: flex;
    }
    #video-modal-box {
      width: 100%;
      max-width: 900px;
      background: #000;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 40px 80px rgba(0,0,0,0.6);
      position: relative;
    }
    #video-modal-box .plyr,
    #video-modal-box iframe {
      width: 100% !important;
      border-radius: 0;
    }
    #video-modal-title {
      color: #fff;
      font-size: 1rem;
      font-weight: 700;
      padding: 16px 20px 0;
      font-family: 'Inter', sans-serif;
      letter-spacing: -0.02em;
    }
    #video-modal-close {
      position: absolute;
      top: -48px;
      right: 0;
      background: rgba(255,255,255,0.15);
      border: none;
      color: #fff;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      font-size: 1.25rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background 0.2s;
    }
    #video-modal-close:hover { background: rgba(255,255,255,0.3); }
    /* Plyr theme */
    #video-modal-box {
      --plyr-color-main: #6366f1;
    }
  </style>
</head>
<body data-page="{{ $page ?? 'home' }}">
  <div id="site-root"></div>

  <!-- === Plyr Video Modal === -->
  <div id="video-modal-overlay" role="dialog" aria-modal="true" aria-label="Video player">
    <div id="video-modal-box">
      <button id="video-modal-close" aria-label="Close video">&times;</button>
      <div id="video-modal-title"></div>
      <div id="video-modal-player"></div>
    </div>
  </div>

  <script src="{{ asset('site.js') }}"></script>
  <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
  <script>
    // === Global Plyr Modal Player ===
    let modalPlayer = null;

    function openVideoModal(youtubeId, title) {
      if (!youtubeId) return;

      const overlay   = document.getElementById('video-modal-overlay');
      const container = document.getElementById('video-modal-player');
      const titleEl   = document.getElementById('video-modal-title');

      // Destroy previous player instance if any
      if (modalPlayer) {
        modalPlayer.destroy();
        modalPlayer = null;
      }

      // Set title
      titleEl.textContent = title || '';

      // Create fresh player div
      container.innerHTML = '<div id="plyr-embed" data-plyr-provider="youtube" data-plyr-embed-id="' + youtubeId + '"></div>';

      // Init Plyr
      modalPlayer = new Plyr('#plyr-embed', {
        youtube: { noCookie: true, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1 },
        controls: ['play','progress','current-time','duration','mute','volume','settings','fullscreen'],
        settings: ['speed'],
        autoplay: true,
      });

      overlay.classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closeVideoModal() {
      const overlay = document.getElementById('video-modal-overlay');
      overlay.classList.remove('open');
      document.body.style.overflow = '';
      if (modalPlayer) {
        modalPlayer.pause();
        modalPlayer.destroy();
        modalPlayer = null;
      }
      document.getElementById('video-modal-player').innerHTML = '';
    }

    // Close button
    document.getElementById('video-modal-close').addEventListener('click', closeVideoModal);

    // Click outside the box to close
    document.getElementById('video-modal-overlay').addEventListener('click', function(e) {
      if (e.target === this) closeVideoModal();
    });

    // Escape key to close
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') closeVideoModal();
    });
  </script>
</body>
</html>
