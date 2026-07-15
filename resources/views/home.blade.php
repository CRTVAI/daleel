@extends('layouts.public')

@section('title', 'Daleel AI | AI Learning Matched to Your Daily Work')

@section('content')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<!-- ==================== GO TO TOP BUTTON ==================== -->
<button id="goToTopBtn" class="go-to-top-btn" aria-label="Go to top">
  <i class="bi bi-chevron-up"></i>
</button>

<!-- ==================== HERO SECTION ==================== -->
<section class="hero-section" id="hero">
  <div class="hero-bg-pattern"></div>
  
  <div class="container">
    <div class="hero-grid">
      <!-- Left Content -->
      <div class="hero-content">
        <div class="hero-badge animate-fade-in">
          <span class="pulse-dot"></span>
          The Future of Workforce Training
        </div>
        
        <h1 class="hero-title animate-slide-up">
          Master AI through the<br>
          <span class="gradient-text">work you already do</span>
        </h1>
        
        <p class="hero-description animate-slide-up">
          Daleel AI doesn't just give you courses. We analyze your role, tools, and repeated tasks to recommend the exact AI workflows that save you hours every week.
        </p>
        
        <div class="hero-buttons animate-slide-up">
          <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
            <span>Start Learning Free</span>
            <i class="bi bi-arrow-right"></i>
          </a>
        </div>
        
        <div class="hero-stats animate-fade-in">
          <div class="stat-card">
            <div class="stat-icon-wrapper">
              <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-info">
              <span class="stat-number">15K+</span>
              <span class="stat-text">Active Learners</span>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon-wrapper">
              <i class="bi bi-lightning-charge-fill"></i>
            </div>
            <div class="stat-info">
              <span class="stat-number">320+</span>
              <span class="stat-text">AI Workflows</span>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon-wrapper">
              <i class="bi bi-star-fill"></i>
            </div>
            <div class="stat-info">
              <div class="rating-row">
                <span class="stat-number">4.9</span>
                <span class="stars">★★★★★</span>
              </div>
              <span class="stat-text">User Rating</span>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Right Visual -->
      <div class="hero-visual animate-fade-in">
        <div class="dashboard-mockup">
          <!-- Mockup Header -->
          <div class="mockup-header">
            <div class="window-controls">
              <span class="control close"></span>
              <span class="control minimize"></span>
              <span class="control maximize"></span>
            </div>
            <span class="mockup-title">Personal Intelligence Hub</span>
            <div class="live-badge">
              <span class="live-dot"></span> Live
            </div>
          </div>
          
          <!-- Mockup Body -->
          <div class="mockup-body">
            <!-- User Card -->
            <div class="user-card">
              <div class="avatar-wrapper">
                <img src="https://i.pravatar.cc/100?img=47" alt="Maya Hassan" class="user-image">
                <div class="online-indicator"></div>
              </div>
              <div class="user-info">
                <h4>Maya Hassan</h4>
                <p>Operations Manager · 8 Active Tools</p>
              </div>
            </div>
            
            <!-- AI Recommendation Card -->
            <div class="ai-card">
              <div class="ai-card-accent"></div>
              <div class="ai-badge">
                <i class="bi bi-magic"></i> AI Recommendation
              </div>
              <h3>Automate learning path create</h3>
              <p>We detected you manually your computer and browser. And create a roadmap for you.</p>
              
              <div class="ai-card-footer">
                <div class="time-badge">
                  <i class="bi bi-clock"></i> 12 min lesson
                </div>
                <a href="#" class="apply-btn">
                  Apply Now <i class="bi bi-arrow-right-short"></i>
                </a>
              </div>
            </div>
            
            <!-- Connected Tools -->
            <div class="tools-row">
              <span class="tools-label">Connected Tools</span>
              <div class="tool-icons">
                <div class="tool-badge" data-tool="ChatGPT">
                  <img src="https://img.icons8.com/fluency/48/chatgpt.png" alt="ChatGPT">
                </div>
                <div class="tool-badge" data-tool="Google Sheets">
                  <img src="https://img.icons8.com/color/48/google-sheets.png" alt="Sheets">
                </div>
                <div class="tool-badge" data-tool="Notion">
                  <img src="https://img.icons8.com/color/48/notion.png" alt="Notion">
                </div>
                <div class="tool-badge" data-tool="Slack">
                  <img src="https://img.icons8.com/color/48/slack-new.png" alt="Slack">
                </div>
                <div class="add-tool-btn">
                  <i class="bi bi-plus-lg"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Floating Elements -->
        <div class="floating-card card-1">
          <i class="bi bi-graph-up-arrow"></i>
          <span>Productivity +40%</span>
        </div>
        <div class="floating-card card-2">
          <i class="bi bi-check-circle-fill"></i>
          <span>Task Automated</span>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Wave Divider -->
  <div class="wave-divider">
    <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M0 60L60 55C120 50 240 40 360 45C480 50 600 70 720 75C840 80 960 70 1080 60C1200 50 1320 40 1380 35L1440 30V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V60Z" fill="white"/>
    </svg>
  </div>
</section>

<!-- ==================== HOW IT WORKS SECTION ==================== -->
<section class="how-section" id="how-it-works">
  <div class="section-divider">
    <div class="divider-line"></div>
    <div class="divider-icon">
      <i class="bi bi-chevron-compact-down"></i>
    </div>
  </div>
  
  <div class="container">
    <div class="section-header text-center">
      <span class="section-badge">⚡ THE Daleel METHOD</span>
      <h2 class="section-title">
        Learning that adapts to your <span class="gradient-text">actual workday</span>
      </h2>
      <p class="section-subtitle">
        Generic courses waste time. Daleel AI identifies the surgical AI workflows you need based on the tools you use and the tasks you perform in real-time.
      </p>
    </div>
    
    <div class="steps-grid">
      <!-- Step 1 -->
      <div class="step-card" data-aos="fade-up">
        <div class="step-illustration">
          <img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&w=400&q=80" alt="Intelligent Mapping" class="step-image">
          <div class="step-number-badge">01</div>
        </div>
        <div class="step-content">
          <div class="step-icon-circle">
            <i class="bi bi-browser-chrome"></i>
          </div>
          <h3>Intelligent Mapping</h3>
          <p>Our browser extension maps your tool usage and identifies repetitive manual workflows in Slack, Gmail, and Notion.</p>
          <ul class="step-features">
            <li><i class="bi bi-check2"></i> Passive tool mapping</li>
            <li><i class="bi bi-check2"></i> Workflow fragmentation analysis</li>
            <li><i class="bi bi-check2"></i> 100% Privacy-first tracking</li>
          </ul>
        </div>
      </div>
      
      <!-- Step 2 -->
      <div class="step-card" data-aos="fade-up" data-aos-delay="100">
        <div class="step-illustration">
          <img src="https://images.unsplash.com/photo-1655720828018-edd2daec9349?auto=format&fit=crop&w=400&q=80" alt="Surgical Lessons" class="step-image">
          <div class="step-number-badge">02</div>
        </div>
        <div class="step-content">
          <div class="step-icon-circle">
            <i class="bi bi-lightning-charge"></i>
          </div>
          <h3>Surgical Lessons</h3>
          <p>Receive 10-minute "Action Lessons" and optimized prompt templates matched exactly to your current browser tab.</p>
          <ul class="step-features">
            <li><i class="bi bi-check2"></i> Just-in-time delivery</li>
            <li><i class="bi bi-check2"></i> Role-specific prompt kits</li>
            <li><i class="bi bi-check2"></i> Immediate application</li>
          </ul>
        </div>
      </div>
      
      <!-- Step 3 -->
      <div class="step-card" data-aos="fade-up" data-aos-delay="200">
        <div class="step-illustration">
          <img src="https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&w=400&q=80" alt="Impact Measurement" class="step-image">
          <div class="step-number-badge">03</div>
        </div>
        <div class="step-content">
          <div class="step-icon-circle">
            <i class="bi bi-graph-up-arrow"></i>
          </div>
          <h3>Verified ROI</h3>
          <p>Track your productivity gains and time saved through a personal dashboard that proves your AI maturity growth.</p>
          <ul class="step-features">
            <li><i class="bi bi-check2"></i> Time-saved counter</li>
            <li><i class="bi bi-check2"></i> Productivity focus scores</li>
            <li><i class="bi bi-check2"></i> AI Adoption reporting</li>
          </ul>
        </div>
      </div>
    </div>
    
    <div class="text-center mt-5">
      <a href="{{ url('how-it-works') }}" class="btn btn-outline btn-lg">
        Learn More About Our Methodology <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- ==================== BUILT FOR TEAMS SECTION ==================== -->
<section class="teams-showcase" style="padding: 100px 0; background: var(--bg-tertiary);">
  <div class="container">
    <div class="showcase-grid">
      <div class="showcase-content">
        <span class="section-badge">👥 FOR ENTERPRISE</span>
        <h2 class="section-title">
          Build a high-performance <span class="gradient-text">AI Culture</span>
        </h2>
        <p class="section-subtitle" style="margin-left: 0; text-align: left;">
          Daleel for Teams isn't just about individual growth. It's about collective intelligence. Share prompts, track department-wide ROI, and outpace your competitors.
        </p>
        
        <div class="feature-cards">
          <div class="feature-card-mini">
            <div class="feature-icon-mini">
              <i class="bi bi-share"></i>
            </div>
            <div>
              <h4>Collective Prompt Library</h4>
              <p>Top-performing prompts built by your best team members are instantly shared across the department.</p>
            </div>
          </div>
          
          <div class="feature-card-mini">
            <div class="feature-icon-mini">
              <i class="bi bi-bar-chart-line"></i>
            </div>
            <div>
              <h4>Manager Insights Dashboard</h4>
              <p>Visualize aggregate time saved and identify skill gaps across Marketing, Sales, and Ops teams.</p>
            </div>
          </div>
          
          <div class="feature-card-mini">
            <div class="feature-icon-mini">
              <i class="bi bi-shield-lock"></i>
            </div>
            <div>
              <h4>Enterprise Security</h4>
              <p>SSO integration, role-based access, and local data processing to keep your corporate data secure.</p>
            </div>
          </div>
        </div>
      </div>
      
      <div class="showcase-visual">
        <div class="team-stats-mockup" style="background: white; padding: 40px; border-radius: 32px; box-shadow: var(--shadow-2xl); border: 1px solid var(--border-light);">
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
            <h4 style="font-weight: 800; margin: 0;">Team Productivity Impact</h4>
            <span style="font-size: 12px; color: var(--success); font-weight: 700; background: #ECFDF5; padding: 4px 12px; border-radius: 100px;">+24% vs Last Month</span>
          </div>
          
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 32px;">
            <div style="background: var(--bg-secondary); padding: 20px; border-radius: 16px; border: 1px solid var(--border-light);">
              <span style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 8px;">TOTAL HOURS SAVED</span>
              <span style="font-size: 28px; font-weight: 900; color: var(--primary);">482h</span>
            </div>
            <div style="background: var(--bg-secondary); padding: 20px; border-radius: 16px; border: 1px solid var(--border-light);">
              <span style="font-size: 12px; color: var(--text-muted); display: block; margin-bottom: 8px;">AI ADOPTION RATE</span>
              <span style="font-size: 28px; font-weight: 900; color: var(--accent);">88%</span>
            </div>
          </div>
          
          <div style="margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; font-weight: 700;">
              <span>Marketing Team</span>
              <span>124h saved</span>
            </div>
            <div style="height: 8px; background: var(--bg-secondary); border-radius: 4px; overflow: hidden;">
              <div style="height: 100%; width: 75%; background: var(--primary);"></div>
            </div>
          </div>
          
          <div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; font-weight: 700;">
              <span>Customer Support</span>
              <span>210h saved</span>
            </div>
            <div style="height: 8px; background: var(--bg-secondary); border-radius: 4px; overflow: hidden;">
              <div style="height: 100%; width: 92%; background: var(--accent);"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==================== FEATURES SHOWCASE SECTION ==================== -->
<section class="features-showcase" id="features">
  <div class="container">
    <div class="showcase-grid">
      <!-- Left: Image Gallery -->
      <div class="showcase-visual">
        <div class="image-stack">
          <div class="stack-image main-image">
            <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=600&q=80" alt="AI Learning Dashboard">
            <div class="image-overlay">
              <i class="bi bi-play-circle-fill"></i>
              <span>Watch Demo</span>
            </div>
          </div>
          <div class="stack-image floating-image-1">
            <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=300&q=80" alt="Team Collaboration">
          </div>
          <div class="stack-image floating-image-2">
            <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=300&q=80" alt="Analytics Dashboard">
          </div>
        </div>
      </div>
      
      <!-- Right: Content -->
      <div class="showcase-content">
        <span class="section-badge">📚 LEARNING LIBRARY</span>
        <h2 class="section-title">
          Surgical lessons for <span class="gradient-text">immediate impact</span>
        </h2>
        <p class="section-subtitle">
          No fluff. No theory. Just practical workflows you can apply the moment you finish watching.
        </p>
        
        <div class="feature-cards">
          <div class="feature-card-mini">
            <div class="feature-icon-mini">
              <i class="bi bi-diagram-3"></i>
            </div>
            <div>
              <h4>Role-Based Paths</h4>
              <p>Specialized training for Support, Marketing, Sales, and Ops teams.</p>
            </div>
          </div>
          
          <div class="feature-card-mini">
            <div class="feature-icon-mini">
              <i class="bi bi-tools"></i>
            </div>
            <div>
              <h4>Tool Mastery</h4>
              <p>Expert workflows for ChatGPT, Claude, Copilot, and Gemini.</p>
            </div>
          </div>
          
          <div class="feature-card-mini">
            <div class="feature-icon-mini">
              <i class="bi bi-headset"></i>
            </div>
            <div>
              <h4>AI Mentor 24/7</h4>
              <p>An AI guide to help troubleshoot your specific prompts anytime.</p>
            </div>
          </div>
        </div>
        
        <a href="{{ url('videos') }}" class="btn btn-primary btn-lg mt-4">
          <span>Browse 200+ Lessons</span>
          <i class="bi bi-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ==================== VIDEO PREVIEW SECTION ==================== -->
<section class="video-section">
  <div class="container">
    <div class="section-header text-center">
      <span class="section-badge">🎥 FEATURED LESSONS</span>
      <h2 class="section-title">
        Popular <span class="gradient-text">AI workflows</span>
      </h2>
      <p class="section-subtitle">
        Start with these trending lessons loved by our community
      </p>
    </div>
    
    <div class="video-grid">
      <a href="{{ url('learn/152') }}" class="video-card-link">
        <div class="video-card-large">
          <div class="video-thumbnail">
            <img src="http://daleelmentor.com/storage/thumbnails/using-google-gemini-ai-effectively-1783078504.jpeg" alt="Using Google Gemini AI Effectively">
            <div class="play-button">
              <i class="bi bi-play-fill"></i>
            </div>
            <div class="duration-badge">1:30</div>
          </div>
          <div class="video-details">
            <div class="video-category">Productivity Software</div>
            <h4>Using Google Gemini AI Effectively</h4>
            <p>Master Google Gemini for writing, analyzing, and organizing tasks in Gmail, Docs, and Sheets.</p>
            <div class="video-meta">
              <span><i class="bi bi-eye"></i> 1.2K views</span>
              <span><i class="bi bi-clock"></i> 1 min</span>
              <span class="level beginner">Beginner</span>
            </div>
          </div>
        </div>
      </a>
      
      <a href="{{ url('learn/140') }}" class="video-card-link">
        <div class="video-card-large">
          <div class="video-thumbnail">
            <img src="http://daleelmentor.com/storage/thumbnails/gmail-ai-features-with-gemini-1783077804.jpeg" alt="Gmail AI Features with Gemini">
            <div class="play-button">
              <i class="bi bi-play-fill"></i>
            </div>
            <div class="duration-badge">0:33</div>
          </div>
          <div class="video-details">
            <div class="video-category">Email Communication</div>
            <h4>Gmail AI Features with Gemini</h4>
            <p>Discover how Gemini enhances Gmail with AI summaries, tone-matching replies, and smart inbox tools.</p>
            <div class="video-meta">
              <span><i class="bi bi-eye"></i> 980 views</span>
              <span><i class="bi bi-clock"></i> 33 sec</span>
              <span class="level beginner">Beginner</span>
            </div>
          </div>
        </div>
      </a>
      
      <a href="{{ url('learn/156') }}" class="video-card-link">
        <div class="video-card-large">
          <div class="video-thumbnail">
            <img src="http://daleelmentor.com/storage/thumbnails/use-google-gemini-ai-for-creativity-1783078505.jpeg" alt="Use Google Gemini AI for Creativity">
            <div class="play-button">
              <i class="bi bi-play-fill"></i>
            </div>
            <div class="duration-badge">0:52</div>
          </div>
          <div class="video-details">
            <div class="video-category">Productivity Tools</div>
            <h4>Use Google Gemini AI for Creativity</h4>
            <p>Unlock Gemini's creative power — generate AI images, video, and music directly from your browser.</p>
            <div class="video-meta">
              <span><i class="bi bi-eye"></i> 1.5K views</span>
              <span><i class="bi bi-clock"></i> 52 sec</span>
              <span class="level beginner">Beginner</span>
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- ==================== TESTIMONIALS SECTION ==================== -->
<section class="testimonials-section">
  <div class="container">
    <div class="section-header text-center">
      <span class="section-badge">💬 SUCCESS STORIES</span>
      <h2 class="section-title">
        Loved by <span class="gradient-text">professionals</span>
      </h2>
    </div>
    
    <div class="testimonials-grid">
      <div class="testimonial-card">
        <div class="testimonial-rating">★★★★★</div>
        <p class="testimonial-text">"Daleel AI transformed how our team learns. Instead of generic courses, we get exactly what we need for our daily tools. Saved us 10+ hours per week."</p>
        <div class="testimonial-author">
          <img src="https://i.pravatar.cc/80?img=11" alt="Sarah Chen">
          <div>
            <strong>Sarah Chen</strong>
            <span>Marketing Director, TechGlobal</span>
          </div>
        </div>
      </div>
      
      <div class="testimonial-card">
        <div class="testimonial-rating">★★★★★</div>
        <p class="testimonial-text">"The pattern detection feature is genius. It found tasks I didn't even realize I was doing repetitively. Now they're all automated."</p>
        <div class="testimonial-author">
          <img src="https://i.pravatar.cc/80?img=12" alt="Marcus Johnson">
          <div>
            <strong>Marcus Johnson</strong>
            <span>Operations Lead, NexusOps</span>
          </div>
        </div>
      </div>
      
      <div class="testimonial-card">
        <div class="testimonial-rating">★★★★★</div>
        <p class="testimonial-text">"Finally, AI training that actually applies to my job. The role-based paths make learning relevant and immediately useful."</p>
        <div class="testimonial-author">
          <img src="https://i.pravatar.cc/80?img=5" alt="Emily Rodriguez">
          <div>
            <strong>Emily Rodriguez</strong>
            <span>Sales Manager, CSMENA</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==================== CTA SECTION ==================== -->
<section class="cta-section" id="cta">
  <div class="container">
    <div class="cta-card">
      <div class="cta-pattern"></div>
      <div class="cta-content">
        <div class="cta-left">
          <h2>Ready to save your first 5 hours?</h2>
          <p>Join 15,000+ professionals turning repetitive work into AI-powered growth. Start free today.</p>
          <div class="cta-avatars">
            <div class="avatar-stack">
              <img src="https://i.pravatar.cc/80?img=1" alt="">
              <img src="https://i.pravatar.cc/80?img=2" alt="">
              <img src="https://i.pravatar.cc/80?img=3" alt="">
              <img src="https://i.pravatar.cc/80?img=4" alt="">
              <div class="avatar-count">+15K</div>
            </div>
            <div class="rating-badge">
              <i class="bi bi-star-fill"></i> 4.9/5 from 2,000+ reviews
            </div>
          </div>
        </div>
        <div class="cta-right">
          <a href="{{ route('register') }}" class="btn btn-primary btn-xl">
            <span>Get Started Free</span>
            <i class="bi bi-arrow-right"></i>
          </a>
          <p class="cta-note">No credit card required</p>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
/* ==================== CSS VARIABLES ==================== */
:root {
  --primary: #6366F1;
  --primary-light: #EEF2FF;
  --primary-dark: #4F46E5;
  --accent: #06B6D4;
  --text: #0F172A;
  --text-secondary: #475569;
  --text-muted: #94A3B8;
  --bg: #FFFFFF;
  --bg-secondary: #F8FAFC;
  --bg-tertiary: #F1F5F9;
  --border: #E2E8F0;
  --border-light: #F1F5F9;
  --success: #10B981;
  --warning: #F59E0B;
  --radius-sm: 8px;
  --radius-md: 12px;
  --radius-lg: 16px;
  --radius-xl: 24px;
  --radius-2xl: 32px;
  --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
  --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
  --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
  --shadow-xl: 0 20px 40px -5px rgba(0, 0, 0, 0.1);
  --shadow-2xl: 0 30px 60px -10px rgba(0, 0, 0, 0.15);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html {
  scroll-behavior: smooth;
  overflow-y: scroll !important;
}

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  color: var(--text);
  background: var(--bg);
  -webkit-font-smoothing: antialiased;
  line-height: 1.6;
  overflow-x: hidden;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 32px;
}

.gradient-text {
  background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 50%, #A855F7 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* ==================== GO TO TOP BUTTON ==================== */
.go-to-top-btn {
  position: fixed;
  bottom: 32px;
  right: 32px;
  width: 48px;
  height: 48px;
  background: var(--primary);
  color: white;
  border: none;
  border-radius: 50%;
  font-size: 24px;
  cursor: pointer;
  box-shadow: var(--shadow-lg);
  transition: all 0.3s ease;
  opacity: 0;
  visibility: hidden;
  transform: translateY(20px);
  z-index: 999;
  display: flex;
  align-items: center;
  justify-content: center;
}

.go-to-top-btn.visible {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.go-to-top-btn:hover {
  background: var(--primary-dark);
  transform: translateY(-4px) scale(1.05);
  box-shadow: 0 8px 32px rgba(99, 102, 241, 0.4);
}

.go-to-top-btn:active {
  transform: scale(0.95);
}

.go-to-top-btn i {
  line-height: 1;
}

/* ==================== HERO SECTION ==================== */
.hero-section {
  position: relative;
  padding: 80px 0 0;
  background: linear-gradient(180deg, #FAFBFC 0%, #FFFFFF 30%);
  overflow: hidden;
}

.hero-bg-pattern {
  position: absolute;
  top: 0;
  right: 0;
  width: 60%;
  height: 100%;
  background: radial-gradient(circle at 70% 30%, rgba(99, 102, 241, 0.03) 0%, transparent 70%);
  pointer-events: none;
}

.hero-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
  position: relative;
  z-index: 1;
  padding: 20px 0 60px;
}

/* Hero Content */
.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 8px 20px;
  background: white;
  border: 1px solid var(--border);
  border-radius: 100px;
  font-size: 14px;
  font-weight: 600;
  color: var(--text-secondary);
  margin-bottom: 28px;
  box-shadow: var(--shadow-sm);
}

.pulse-dot {
  width: 8px;
  height: 8px;
  background: var(--success);
  border-radius: 50%;
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: 0.5; transform: scale(1.5); }
}

.hero-title {
  font-size: 52px;
  font-weight: 900;
  line-height: 1.1;
  letter-spacing: -0.02em;
  color: var(--text);
  margin-bottom: 20px;
}

.hero-description {
  font-size: 18px;
  line-height: 1.7;
  color: var(--text-secondary);
  margin-bottom: 36px;
  max-width: 520px;
}

.hero-buttons {
  display: flex;
  gap: 16px;
  margin-bottom: 48px;
  flex-wrap: wrap;
}

/* Buttons */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  font-weight: 600;
  font-size: 15px;
  text-decoration: none;
  transition: all 0.2s ease;
  cursor: pointer;
  border-radius: var(--radius-md);
  font-family: 'Inter', sans-serif;
}

.btn-primary {
  background: var(--primary);
  color: white;
  border: none;
  padding: 14px 28px;
}

.btn-primary:hover {
  background: var(--primary-dark);
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);
  color: white;
}

.btn-outline {
  background: white;
  color: var(--text);
  border: 1.5px solid var(--border);
  padding: 14px 28px;
}

.btn-outline:hover {
  border-color: var(--primary);
  color: var(--primary);
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}

.btn-lg {
  padding: 14px 28px;
  font-size: 16px;
}

.btn-xl {
  padding: 18px 36px;
  font-size: 18px;
  border-radius: var(--radius-lg);
}

/* Hero Stats */
.hero-stats {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

.stat-card {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 14px 18px;
  background: white;
  border: 1px solid var(--border-light);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-sm);
}

.stat-icon-wrapper {
  width: 40px;
  height: 40px;
  background: var(--primary-light);
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary);
  font-size: 18px;
  flex-shrink: 0;
}

.stat-number {
  font-size: 22px;
  font-weight: 800;
  color: var(--text);
  line-height: 1;
}

.stat-text {
  font-size: 12px;
  color: var(--text-muted);
  font-weight: 500;
  margin-top: 2px;
}

.rating-row {
  display: flex;
  align-items: center;
  gap: 8px;
}

.stars {
  color: #F59E0B;
  font-size: 12px;
  letter-spacing: 2px;
}

/* Hero Visual - Dashboard Mockup */
.hero-visual {
  position: relative;
}

.dashboard-mockup {
  background: white;
  border-radius: var(--radius-xl);
  box-shadow: var(--shadow-2xl);
  overflow: hidden;
  border: 1px solid var(--border-light);
}

.mockup-header {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 14px 18px;
  background: #F8FAFC;
  border-bottom: 1px solid var(--border-light);
}

.window-controls {
  display: flex;
  gap: 8px;
}

.control {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  flex-shrink: 0;
}

.control.close { background: #EF4444; }
.control.minimize { background: #F59E0B; }
.control.maximize { background: #10B981; }

.mockup-title {
  font-size: 13px;
  font-weight: 600;
  color: var(--text);
  flex: 1;
}

.live-badge {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  font-weight: 600;
  color: var(--success);
  background: #ECFDF5;
  padding: 4px 12px;
  border-radius: 100px;
  flex-shrink: 0;
}

.live-dot {
  width: 6px;
  height: 6px;
  background: var(--success);
  border-radius: 50%;
  animation: pulse 2s ease-in-out infinite;
}

.mockup-body {
  padding: 20px;
}

.user-card {
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 20px;
  padding-bottom: 20px;
  border-bottom: 1px solid var(--border-light);
}

.avatar-wrapper {
  position: relative;
  flex-shrink: 0;
}

.user-image {
  width: 44px;
  height: 44px;
  border-radius: var(--radius-md);
  object-fit: cover;
}

.online-indicator {
  position: absolute;
  bottom: -2px;
  right: -2px;
  width: 14px;
  height: 14px;
  background: var(--success);
  border: 3px solid white;
  border-radius: 50%;
}

.user-info h4 {
  font-size: 15px;
  font-weight: 700;
  color: var(--text);
}

.user-info p {
  font-size: 13px;
  color: var(--text-muted);
  margin-top: 2px;
}

.ai-card {
  background: #FAFBFC;
  border-radius: var(--radius-lg);
  padding: 18px;
  margin-bottom: 20px;
  position: relative;
  border: 1px solid var(--border-light);
}

.ai-card-accent {
  position: absolute;
  left: 0;
  top: 0;
  width: 4px;
  height: 100%;
  background: linear-gradient(180deg, #6366F1, #8B5CF6);
  border-radius: 4px 0 0 4px;
}

.ai-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 11px;
  font-weight: 700;
  color: var(--primary);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 10px;
}

.ai-card h3 {
  font-size: 16px;
  font-weight: 700;
  color: var(--text);
  margin-bottom: 6px;
}

.ai-card p {
  font-size: 13px;
  color: var(--text-secondary);
  line-height: 1.6;
  margin-bottom: 14px;
}

.ai-card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 8px;
}

.time-badge {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 13px;
  font-weight: 600;
  color: var(--text-muted);
  background: white;
  padding: 6px 12px;
  border-radius: 8px;
  border: 1px solid var(--border-light);
}

.apply-btn {
  font-size: 13px;
  font-weight: 700;
  color: var(--primary);
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 4px;
}

.apply-btn:hover {
  color: var(--primary-dark);
}

.tools-row {
  margin-top: 4px;
}

.tools-label {
  font-size: 12px;
  font-weight: 600;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  display: block;
  margin-bottom: 10px;
}

.tool-icons {
  display: flex;
  gap: 8px;
  align-items: center;
  flex-wrap: wrap;
}

.tool-badge {
  width: 38px;
  height: 38px;
  background: white;
  border: 1px solid var(--border);
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  padding: 6px;
}

.tool-badge:hover {
  border-color: var(--primary);
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}

.tool-badge img {
  width: 22px;
  height: 22px;
  object-fit: contain;
}

.add-tool-btn {
  width: 38px;
  height: 38px;
  border: 2px dashed #CBD5E1;
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-muted);
  font-size: 16px;
  cursor: pointer;
  transition: all 0.2s;
}

.add-tool-btn:hover {
  border-color: var(--primary);
  color: var(--primary);
  background: var(--primary-light);
}

/* Floating Cards */
.floating-card {
  position: absolute;
  background: white;
  padding: 10px 16px;
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-lg);
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  font-weight: 600;
  color: var(--text);
  border: 1px solid var(--border-light);
}

.card-1 {
  top: 10%;
  left: -8%;
  animation: float 4s ease-in-out infinite;
}

.card-1 i {
  color: var(--success);
  font-size: 16px;
}

.card-2 {
  bottom: 20%;
  right: -6%;
  animation: float 4s ease-in-out infinite 1s;
}

.card-2 i {
  color: var(--primary);
  font-size: 16px;
}

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

/* Wave Divider */
.wave-divider {
  position: absolute;
  bottom: -1px;
  left: 0;
  width: 100%;
}

.wave-divider svg {
  width: 100%;
  height: auto;
  display: block;
}

/* ==================== HOW IT WORKS SECTION ==================== */
.how-section {
  padding: 60px 0 80px;
  background: white;
}

.section-badge {
  display: inline-flex;
  align-items: center;
  padding: 6px 16px;
  background: var(--primary-light);
  color: var(--primary-dark);
  border-radius: 100px;
  font-size: 13px;
  font-weight: 700;
  margin-bottom: 16px;
  letter-spacing: 0.5px;
}

.section-header {
  margin-bottom: 48px;
}

.section-header.text-center {
  text-align: center;
}

.section-title {
  font-size: 38px;
  font-weight: 800;
  line-height: 1.2;
  letter-spacing: -0.02em;
  color: var(--text);
  margin-bottom: 16px;
}

.section-subtitle {
  font-size: 17px;
  line-height: 1.7;
  color: var(--text-secondary);
  max-width: 650px;
  margin: 0 auto;
}

.steps-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 28px;
  position: relative;
}

.step-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: var(--radius-xl);
  overflow: hidden;
  transition: all 0.4s;
  height: 100%;
}

.step-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-xl);
  border-color: var(--primary);
}

.step-illustration {
  position: relative;
  height: 160px;
  overflow: hidden;
}

.step-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.step-number-badge {
  position: absolute;
  top: 12px;
  right: 12px;
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  color: white;
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  font-size: 15px;
  box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
  transition: transform 0.3s, box-shadow 0.3s;
}

.step-card:hover .step-number-badge {
  transform: scale(1.1);
  box-shadow: 0 6px 20px rgba(99, 102, 241, 0.5);
}

.step-content {
  padding: 20px;
}

.step-icon-circle {
  width: 48px;
  height: 48px;
  background: var(--primary-light);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary);
  font-size: 20px;
  margin-bottom: 16px;
}

.step-content h3 {
  font-size: 18px;
  font-weight: 700;
  color: var(--text);
  margin-bottom: 10px;
}

.step-content p {
  font-size: 14px;
  color: var(--text-secondary);
  line-height: 1.6;
  margin-bottom: 16px;
}

.step-features {
  list-style: none;
  padding: 0;
}

.step-features li {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  color: var(--text-secondary);
  margin-bottom: 6px;
}

.step-features li i {
  color: var(--success);
  font-weight: 700;
}

/* Step connector line */
.steps-grid {
  position: relative;
}

.steps-grid::before {
  content: '';
  position: absolute;
  top: 80px;
  left: 20%;
  right: 20%;
  height: 2px;
  background: linear-gradient(90deg, var(--primary), var(--primary-light), var(--primary));
  opacity: 0.25;
  z-index: 0;
  pointer-events: none;
}

@media (max-width: 768px) {
  .steps-grid::before {
    display: none;
  }
}

.step-card {
  position: relative;
  z-index: 1;
}

.mt-5 {
  margin-top: 40px;
}

.text-center {
  text-align: center;
}

/* ==================== FEATURES SHOWCASE ==================== */
.features-showcase {
  padding: 80px 0;
  background: var(--bg-secondary);
}

.showcase-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
}

.showcase-visual {
  position: relative;
}

.image-stack {
  position: relative;
}

.main-image {
  border-radius: var(--radius-xl);
  overflow: hidden;
  box-shadow: var(--shadow-xl);
  position: relative;
}

.main-image img {
  width: 100%;
  height: 380px;
  object-fit: cover;
  display: block;
}

.image-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(15, 23, 42, 0.4);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 12px;
  color: white;
  font-size: 18px;
  font-weight: 600;
  cursor: pointer;
  opacity: 0;
  transition: opacity 0.3s;
}

.main-image:hover .image-overlay {
  opacity: 1;
}

.image-overlay i {
  font-size: 44px;
}

.floating-image-1,
.floating-image-2 {
  position: absolute;
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-lg);
  border: 4px solid white;
}

.floating-image-1 {
  top: -24px;
  right: -24px;
  width: 140px;
  height: 100px;
  animation: float 5s ease-in-out infinite;
}

.floating-image-2 {
  bottom: -24px;
  left: -24px;
  width: 140px;
  height: 100px;
  animation: float 5s ease-in-out infinite 1.5s;
}

.floating-image-1 img,
.floating-image-2 img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.feature-cards {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin: 28px 0;
}

.feature-card-mini {
  display: flex;
  gap: 14px;
  align-items: flex-start;
  padding: 16px 18px;
  background: white;
  border-radius: var(--radius-lg);
  border: 1px solid var(--border-light);
  transition: all 0.3s;
}

.feature-card-mini:hover {
  border-color: var(--primary);
  box-shadow: var(--shadow-md);
  transform: translateX(8px);
}

.feature-icon-mini {
  width: 44px;
  height: 44px;
  background: var(--primary-light);
  border-radius: var(--radius-md);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--primary);
  font-size: 20px;
  flex-shrink: 0;
}

.feature-card-mini h4 {
  font-size: 15px;
  font-weight: 700;
  color: var(--text);
  margin-bottom: 4px;
}

.feature-card-mini p {
  font-size: 13px;
  color: var(--text-secondary);
  line-height: 1.5;
}

.mt-4 {
  margin-top: 16px;
}

/* ==================== VIDEO SECTION ==================== */
.video-section {
  padding: 80px 0;
  background: white;
}

.video-card-link {
  text-decoration: none;
  color: inherit;
  display: block;
}

.video-card-link:hover {
  color: inherit;
}

.video-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}

.video-card-large {
  background: white;
  border: 1px solid var(--border-light);
  border-radius: var(--radius-xl);
  overflow: hidden;
  transition: all 0.3s;
  cursor: pointer;
  height: 100%;
}

.video-card-large:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-xl);
  border-color: var(--primary);
}

.video-thumbnail {
  position: relative;
  height: 180px;
  overflow: hidden;
}

.video-thumbnail img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.play-button {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 50px;
  height: 50px;
  background: rgba(99, 102, 241, 0.95);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 22px;
  opacity: 0;
  transition: all 0.3s;
}

.video-card-large:hover .play-button {
  opacity: 1;
  transform: translate(-50%, -50%) scale(1.1);
}

.duration-badge {
  position: absolute;
  bottom: 10px;
  right: 10px;
  background: rgba(15, 23, 42, 0.8);
  color: white;
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 12px;
  font-weight: 600;
}

.video-details {
  padding: 18px;
}

.video-category {
  font-size: 11px;
  font-weight: 700;
  color: var(--primary);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 6px;
}

.video-details h4 {
  font-size: 16px;
  font-weight: 700;
  color: var(--text);
  margin-bottom: 6px;
}

.video-details p {
  font-size: 13px;
  color: var(--text-secondary);
  line-height: 1.5;
  margin-bottom: 14px;
}

.video-meta {
  display: flex;
  gap: 14px;
  align-items: center;
  font-size: 12px;
  color: var(--text-muted);
  flex-wrap: wrap;
}

.video-meta span {
  display: flex;
  align-items: center;
  gap: 4px;
}

.level {
  padding: 2px 10px;
  border-radius: 100px;
  font-size: 11px;
  font-weight: 700;
}

.level.beginner { background: #ECFDF5; color: #059669; }
.level.intermediate { background: #FEF3C7; color: #D97706; }
.level.advanced { background: #FEE2E2; color: #DC2626; }

/* ==================== TESTIMONIALS ==================== */
.testimonials-section {
  padding: 80px 0;
  background: var(--bg-secondary);
}

.testimonials-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  margin-top: 40px;
}

.testimonial-card {
  background: white;
  border: 1px solid var(--border-light);
  border-radius: var(--radius-xl);
  padding: 28px;
  transition: all 0.3s;
  height: 100%;
}

.testimonial-card:hover {
  box-shadow: var(--shadow-lg);
  border-color: var(--primary);
}

.testimonial-rating {
  color: #F59E0B;
  font-size: 16px;
  letter-spacing: 4px;
  margin-bottom: 14px;
}

.testimonial-text {
  font-size: 15px;
  line-height: 1.7;
  color: var(--text-secondary);
  margin-bottom: 20px;
  font-style: italic;
}

.testimonial-author {
  display: flex;
  align-items: center;
  gap: 12px;
}

.testimonial-author img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.testimonial-author strong {
  display: block;
  font-size: 14px;
  color: var(--text);
}

.testimonial-author span {
  font-size: 12px;
  color: var(--text-muted);
}

/* ==================== TEAMS SHOWCASE ==================== */
.teams-showcase {
  padding: 80px 0 !important;
  background: var(--bg-tertiary);
}

.team-stats-mockup {
  background: white;
  padding: 32px !important;
  border-radius: 32px;
  box-shadow: var(--shadow-2xl);
  border: 1px solid var(--border-light);
}

/* ==================== CTA SECTION ==================== */
.cta-section {
  padding: 60px 0 80px;
  background: white;
}

.cta-card {
  background: #0F172A;
  border-radius: var(--radius-2xl);
  padding: 60px 48px;
  position: relative;
  overflow: hidden;
}

.cta-pattern {
  position: absolute;
  top: 0;
  right: 0;
  width: 50%;
  height: 100%;
  background: radial-gradient(circle at center, rgba(99, 102, 241, 0.1) 0%, transparent 70%);
  pointer-events: none;
}

.cta-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 48px;
  position: relative;
  z-index: 1;
}

.cta-left h2 {
  font-size: 36px;
  font-weight: 800;
  color: white;
  margin-bottom: 14px;
}

.cta-left p {
  font-size: 16px;
  color: #94A3B8;
  line-height: 1.6;
  margin-bottom: 28px;
}

.cta-avatars {
  display: flex;
  align-items: center;
  gap: 16px;
  flex-wrap: wrap;
}

.avatar-stack {
  display: flex;
  align-items: center;
}

.avatar-stack img {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: 3px solid #0F172A;
  margin-right: -10px;
  object-fit: cover;
}

.avatar-count {
  width: 36px;
  height: 36px;
  background: var(--primary);
  border-radius: 50%;
  border: 3px solid #0F172A;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 10px;
  font-weight: 700;
  margin-left: 4px;
}

.rating-badge {
  display: flex;
  align-items: center;
  gap: 6px;
  color: #FBBF24;
  font-size: 13px;
  font-weight: 600;
}

.cta-right {
  text-align: center;
  flex-shrink: 0;
}

.cta-note {
  font-size: 13px;
  color: #64748B;
  margin-top: 12px;
}

/* ==================== ANIMATIONS ==================== */
.animate-fade-in {
  animation: fadeIn 0.8s ease-out forwards;
  opacity: 0;
}

.animate-slide-up {
  animation: slideUp 0.8s ease-out forwards;
  opacity: 0;
}

.animate-slide-up:nth-child(2) { animation-delay: 0.1s; }
.animate-slide-up:nth-child(3) { animation-delay: 0.2s; }
.animate-slide-up:nth-child(4) { animation-delay: 0.3s; }

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ==================== RESPONSIVE ==================== */
@media (max-width: 1024px) {
  .container {
    padding: 0 24px;
  }

  .hero-grid,
  .showcase-grid {
    grid-template-columns: 1fr;
    gap: 48px;
  }

  .hero-title {
    font-size: 40px;
  }

  .section-title {
    font-size: 32px;
  }

  .steps-grid,
  .video-grid,
  .testimonials-grid {
    grid-template-columns: 1fr 1fr;
  }

  .cta-content {
    flex-direction: column;
    text-align: center;
  }

  .cta-avatars {
    justify-content: center;
  }

  .hero-stats {
    flex-wrap: wrap;
  }

  .floating-card {
    display: none;
  }

  .showcase-content .btn-lg {
    display: flex;
    justify-content: center;
    width: 100%;
  }

  .team-stats-mockup {
    padding: 24px !important;
  }

  .floating-image-1,
  .floating-image-2 {
    display: none;
  }
}

@media (max-width: 768px) {
  .container {
    padding: 0 16px;
  }

  .hero-section {
    padding: 40px 0 0;
  }

  .hero-grid {
    gap: 32px;
    padding: 0 0 40px;
  }

  .hero-title {
    font-size: 32px;
  }

  .hero-description {
    font-size: 16px;
    margin-bottom: 28px;
  }

  .hero-buttons {
    flex-direction: column;
    margin-bottom: 32px;
  }

  .btn {
    width: 100%;
    justify-content: center;
  }

  .btn-lg,
  .btn-xl {
    padding: 12px 24px;
    font-size: 15px;
  }

  .hero-stats {
    flex-direction: column;
    gap: 12px;
  }

  .stat-card {
    padding: 12px 16px;
  }

  .stat-number {
    font-size: 20px;
  }

  .steps-grid,
  .video-grid,
  .testimonials-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .section-title {
    font-size: 28px;
  }

  .section-subtitle {
    font-size: 15px;
  }

  .section-header {
    margin-bottom: 32px;
  }

  .step-illustration {
    height: 140px;
  }

  .step-content {
    padding: 16px;
  }

  .feature-cards {
    margin: 20px 0;
  }

  .feature-card-mini {
    padding: 14px 16px;
  }

  .features-showcase {
    padding: 48px 0;
  }

  .video-section {
    padding: 48px 0;
  }

  .testimonials-section {
    padding: 48px 0;
  }

  .teams-showcase {
    padding: 48px 0 !important;
  }

  .team-stats-mockup {
    padding: 16px !important;
  }

  .team-stats-mockup h4 {
    font-size: 16px;
  }

  .cta-section {
    padding: 40px 0 60px;
  }

  .cta-card {
    padding: 32px 20px;
  }

  .cta-left h2 {
    font-size: 28px;
  }

  .cta-left p {
    font-size: 15px;
  }

  .cta-content {
    gap: 32px;
  }

  .mockup-body {
    padding: 16px;
  }

  .ai-card {
    padding: 16px;
  }

  .ai-card-footer {
    flex-direction: column;
    gap: 10px;
    align-items: flex-start;
  }

  .tools-row {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    padding-bottom: 4px;
  }

  .tool-icons {
    flex-wrap: nowrap;
  }

  .section-divider {
    padding: 16px 0;
  }

  .divider-icon {
    width: 32px;
    height: 32px;
    font-size: 16px;
  }

  .how-section {
    padding: 40px 0 60px;
  }

  .image-stack .main-image img {
    height: 240px;
  }

  .testimonial-card {
    padding: 20px;
  }

  .testimonial-text {
    font-size: 14px;
  }

  .video-card-large .video-thumbnail {
    height: 160px;
  }

  .video-details {
    padding: 14px;
  }

  .video-details h4 {
    font-size: 15px;
  }

  .go-to-top-btn {
    bottom: 20px;
    right: 20px;
    width: 44px;
    height: 44px;
    font-size: 20px;
  }

  .mt-5 {
    margin-top: 32px;
  }

  .mt-4 {
    margin-top: 12px;
  }

  .showcase-grid {
    gap: 32px;
  }

  .floating-image-1,
  .floating-image-2 {
    display: none;
  }

  .team-stats-mockup div[style*="grid-template-columns"] {
    grid-template-columns: 1fr !important;
  }
}

@media (max-width: 480px) {
  .container {
    padding: 0 12px;
  }

  .hero-title {
    font-size: 28px;
  }

  .hero-badge {
    font-size: 12px;
    padding: 6px 14px;
  }

  .section-title {
    font-size: 24px;
  }

  .section-badge {
    font-size: 11px;
    padding: 4px 12px;
  }

  .stat-card {
    padding: 10px 14px;
  }

  .stat-number {
    font-size: 18px;
  }

  .stat-icon-wrapper {
    width: 36px;
    height: 36px;
    font-size: 16px;
  }

  .cta-card {
    padding: 24px 16px;
  }

  .cta-left h2 {
    font-size: 24px;
  }

  .cta-avatars {
    gap: 12px;
  }

  .avatar-stack img {
    width: 32px;
    height: 32px;
  }

  .avatar-count {
    width: 32px;
    height: 32px;
    font-size: 9px;
  }

  .go-to-top-btn {
    bottom: 16px;
    right: 16px;
    width: 40px;
    height: 40px;
    font-size: 18px;
  }
}
</style>

<script>
// ==================== GO TO TOP BUTTON ====================
const goToTopBtn = document.getElementById('goToTopBtn');

window.addEventListener('scroll', function() {
  if (window.pageYOffset > 300) {
    goToTopBtn.classList.add('visible');
  } else {
    goToTopBtn.classList.remove('visible');
  }
});

goToTopBtn.addEventListener('click', function() {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});

// ==================== SMOOTH SCROLL FOR ANCHOR LINKS ====================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});

// ==================== INTERSECTION OBSERVER FOR SCROLL ANIMATIONS ====================
const observerOptions = {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = '1';
      entry.target.style.transform = 'translateY(0)';
    }
  });
}, observerOptions);

// Observe cards for animation
document.querySelectorAll('.step-card, .video-card-large, .testimonial-card, .trusted-logo-card, .feature-card-mini').forEach(el => {
  el.style.opacity = '0';
  el.style.transform = 'translateY(20px)';
  el.style.transition = 'all 0.6s ease-out';
  observer.observe(el);
});
</script>
@endsection