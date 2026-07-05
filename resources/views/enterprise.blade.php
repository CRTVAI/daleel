@extends('layouts.public')

@section('title', 'Contact Us | Daleel AI')

@section('styles')
<style>
/* ==================== CSS VARIABLES ==================== */
:root {
    --primary: #6366F1;
    --primary-light: #EEF2FF;
    --primary-dark: #4F46E5;
    --text: #0F172A;
    --text-secondary: #475569;
    --text-muted: #94A3B8;
    --bg: #FFFFFF;
    --bg-secondary: #F8FAFC;
    --border: #E2E8F0;
    --border-light: #F1F5F9;
    --radius-sm: 8px;
    --radius-md: 12px;
    --radius-lg: 20px;
    --radius-xl: 28px;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.04);
    --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.04), 0 2px 6px rgba(0, 0, 0, 0.02);
    --shadow-lg: 0 20px 40px -10px rgba(0, 0, 0, 0.06);
    --shadow-xl: 0 30px 50px -15px rgba(0, 0, 0, 0.08);
}

/* ==================== GLOBAL STYLES ==================== */
.contact-page {
    background: white;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.eyebrow {
    display: inline-flex;
    padding: 8px 18px;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(168, 85, 247, 0.08));
    color: var(--primary-dark);
    border-radius: 100px;
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 24px;
    border: 1px solid rgba(99, 102, 241, 0.15);
}

/* ==================== HERO SECTION ==================== */
.page-hero {
    padding: 50px 0 40px;
    position: relative;
    overflow: hidden;
    background: radial-gradient(circle at 80% 20%, rgba(238, 242, 255, 0.8) 0%, rgba(255, 255, 255, 1) 70%);
}

.page-hero::before {
    content: '';
    position: absolute;
    top: -150px;
    right: -150px;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.04) 0%, transparent 70%);
    border-radius: 50%;
    z-index: 0;
}

.page-hero .container {
    position: relative;
    z-index: 1;
}

.page-hero h1 {
    font-size: 48px;
    font-weight: 850;
    letter-spacing: -0.02em;
    background: linear-gradient(135deg, #0F172A 0%, #4338CA 80%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 20px;
}

.page-hero .lead {
    font-size: 18px;
    color: var(--text-secondary);
    line-height: 1.7;
    font-weight: 450;
}

/* ==================== CONTACT FORM CARD ==================== */
.contact-form-card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-xl);
    border: 1px solid rgba(255, 255, 255, 0.6);
    padding: 48px;
    transition: transform 0.3s ease;
}

.contact-form-card:hover {
    transform: translateY(-4px);
}

.contact-form-card h2 {
    font-size: 28px;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 8px;
    letter-spacing: -0.01em;
}

.form-label {
    font-weight: 700;
    color: var(--text);
    margin-bottom: 8px;
    font-size: 14px;
    letter-spacing: 0.2px;
}

.form-control, .form-select {
    border: 1.5px solid var(--border);
    border-radius: var(--radius-md);
    padding: 14px 18px;
    font-size: 15px;
    color: var(--text);
    transition: all 0.2s ease;
    background: white;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    outline: none;
}

.form-control::placeholder {
    color: var(--text-muted);
}

/* ==================== BUTTONS ==================== */
.btn-primary-contact {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 16px 32px;
    background: linear-gradient(135deg, var(--primary), #8B5CF6);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    font-size: 16px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 8px 20px -8px rgba(99, 102, 241, 0.4);
    position: relative;
    overflow: hidden;
    z-index: 1;
    width: 100%;
    letter-spacing: 0.2px;
}

.btn-primary-contact::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #8B5CF6, var(--primary));
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
}

.btn-primary-contact:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 28px -6px rgba(99, 102, 241, 0.5);
    color: white;
}

.btn-primary-contact:hover::before {
    opacity: 1;
}

.btn-primary-contact:disabled {
    opacity: 0.8;
    transform: none;
    box-shadow: 0 4px 10px -4px rgba(99, 102, 241, 0.3);
}

/* ==================== INFO SIDEBAR ==================== */
.info-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(12px);
    border-radius: var(--radius-lg);
    padding: 28px;
    border: 1px solid var(--border-light);
    transition: all 0.3s ease;
}

.info-card:hover {
    box-shadow: var(--shadow-lg);
    border-color: rgba(99, 102, 241, 0.2);
}

.icon-box {
    width: 52px;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--radius-sm);
    background: linear-gradient(135deg, var(--primary-light), white);
    color: var(--primary);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
    flex-shrink: 0;
}

.icon-box-accent {
    background: linear-gradient(135deg, #F3E8FF, white);
    color: #7C3AED;
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.15);
}

.enterprise-demo-card {
    background: linear-gradient(135deg, #1E1B4B, #312E81, #4338CA);
    border-radius: var(--radius-xl);
    padding: 32px;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.4);
}

.enterprise-demo-card::before {
    content: '';
    position: absolute;
    top: -40px;
    right: -40px;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
    border-radius: 50%;
}

.enterprise-demo-card h4 {
    font-weight: 800;
    position: relative;
    z-index: 1;
}

.enterprise-demo-card p {
    opacity: 0.8;
    position: relative;
    z-index: 1;
}

.btn-outline-white-contact {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 28px;
    background: transparent;
    color: white;
    border: 1.5px solid rgba(255, 255, 255, 0.5);
    border-radius: var(--radius-md);
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(4px);
    position: relative;
    z-index: 1;
    width: 100%;
    letter-spacing: 0.2px;
}

.btn-outline-white-contact:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.8);
    color: white;
    transform: translateY(-2px);
}

.social-links a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    background: var(--bg-secondary);
    border-radius: var(--radius-sm);
    color: var(--text-secondary);
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid var(--border-light);
}

.social-links a:hover {
    background: var(--primary-light);
    color: var(--primary);
    border-color: var(--primary);
    transform: translateY(-2px);
}

/* ==================== ALERT ==================== */
.alert-success-custom {
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    color: #065F46;
    border-radius: var(--radius-md);
    padding: 16px 20px;
    font-weight: 500;
}

.alert-error-custom {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    color: #991B1B;
    border-radius: var(--radius-md);
    padding: 16px 20px;
    font-weight: 500;
}

.success-popup-backdrop {
    position: fixed;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(15, 23, 42, 0.42);
    backdrop-filter: blur(6px);
    z-index: 1080;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.24s ease, visibility 0.24s ease;
}

.success-popup-backdrop.show {
    opacity: 1;
    visibility: visible;
}

.success-popup {
    width: min(440px, 100%);
    background: #FFFFFF;
    border-radius: 24px;
    padding: 34px 30px 30px;
    text-align: center;
    box-shadow: 0 30px 80px rgba(15, 23, 42, 0.24);
    border: 1px solid rgba(255, 255, 255, 0.7);
    transform: translateY(16px) scale(0.96);
    transition: transform 0.24s ease;
}

.success-popup-backdrop.show .success-popup {
    transform: translateY(0) scale(1);
}

.success-popup-icon {
    width: 70px;
    height: 70px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #047857;
    background: linear-gradient(135deg, #D1FAE5, #ECFDF5);
    box-shadow: 0 16px 32px rgba(16, 185, 129, 0.18);
    margin-bottom: 20px;
}

.success-popup h3 {
    color: var(--text);
    font-size: 24px;
    font-weight: 850;
    margin-bottom: 10px;
}

.success-popup p {
    color: var(--text-secondary);
    font-size: 15px;
    line-height: 1.65;
    margin-bottom: 24px;
}

.success-popup-close {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 46px;
    padding: 12px 24px;
    border: 0;
    border-radius: var(--radius-md);
    background: linear-gradient(135deg, var(--primary), #8B5CF6);
    color: #FFFFFF;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 12px 24px rgba(99, 102, 241, 0.24);
}

.success-popup-close:hover {
    background: linear-gradient(135deg, #4F46E5, #7C3AED);
}

/* ==================== ANIMATIONS ==================== */
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

.fade-in { animation: fadeIn 0.8s ease forwards; }
.fade-up { opacity: 0; animation: fadeUp 0.8s ease forwards; }

/* ==================== RESPONSIVE ==================== */
@media (max-width: 992px) {
    .contact-form-card {
        padding: 32px 24px;
    }
    
    .page-hero h1 {
        font-size: 36px;
    }
}

@media (max-width: 576px) {
    .page-hero {
        padding: 60px 0 40px;
    }
    
    .page-hero h1 {
        font-size: 30px;
    }
    
    .contact-form-card {
        padding: 24px 20px;
    }

    .success-popup {
        padding: 30px 22px 24px;
        border-radius: 20px;
    }
}
</style>
@endsection

@section('content')
<div class="contact-page">
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container text-center py-3">
            <span class="eyebrow fade-in">Get in Touch</span>
            <h1 class="fade-up">Contact Our Team</h1>
        </div>
    </section>

    <section class="section" style="padding: 60px 0 100px;">
        <div class="container">
            <div class="row g-5">
                <!-- Contact Form -->
                <div class="col-lg-7">
                    <div class="contact-form-card">
                        <form id="enterpriseContactForm" class="contact-form" method="POST" action="{{ route('enterprise.contact.send') }}">
                            @csrf
                            <h2 class="mb-2">Send us a message</h2>
                            <p class="text-muted mb-4" style="font-size: 15px;">Fill out the form below and we'll get back to you within 24 hours.</p>

                            @if ($errors->any())
                                <div class="alert-error-custom mb-4">
                                    Please check the form and try again.
                                </div>
                            @endif

                            @if (session('enterprise_contact_error'))
                                <div class="alert-error-custom mb-4">
                                    {{ session('enterprise_contact_error') }}
                                </div>
                            @endif
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="field mb-3">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="John Doe" required>
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field mb-3">
                                        <label for="email" class="form-label">Work Email</label>
                                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="john@company.com" required>
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="field mb-3">
                                        <label for="subject" class="form-label">Subject</label>
                                        <select id="subject" name="subject" class="form-select @error('subject') is-invalid @enderror" required>
                                            <option value="enterprise" @selected(old('subject') === 'enterprise')>Enterprise Solutions</option>
                                            <option value="support" @selected(old('subject') === 'support')>Technical Support</option>
                                            <option value="partnership" @selected(old('subject') === 'partnership')>Partnership Inquiry</option>
                                            <option value="other" @selected(old('subject') === 'other')>Other</option>
                                        </select>
                                        @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="field mb-4">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" rows="5" placeholder="Tell us how we can help..." required>{{ old('message') }}</textarea>
                                        @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn-primary-contact">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                </svg>
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Info & Sidebar -->
                <div class="col-lg-5">
                    <div class="ps-lg-3">
                        <div class="mb-5">
                            <h3 class="fw-800 mb-4" style="font-size: 22px;">Direct Contact</h3>
                            
                            <div class="info-card mb-4 d-flex align-items-start gap-3">
                                <div class="icon-box">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="fw-700 mb-1" style="font-size: 16px;">Email Us</h5>
                                    <p class="text-muted mb-0" style="font-size: 15px;">hello@Daleel.ai</p>
                                    <p class="text-muted" style="font-size: 13px;">Response time: < 24 hours</p>
                                </div>
                            </div>
                            
                            <div class="info-card d-flex align-items-start gap-3">
                                <div class="icon-box icon-box-accent">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="fw-700 mb-1" style="font-size: 16px;">Live Support</h5>
                                    <p class="text-muted mb-0" style="font-size: 15px;">Available for Enterprise customers</p>
                                    <p class="text-muted" style="font-size: 13px;">Mon-Fri, 9am - 6pm EST</p>
                                </div>
                            </div>
                        </div>

                        <div class="enterprise-demo-card mb-5">
                            <h4 class="mb-3">Enterprise Demo</h4>
                            <p class="small mb-4" style="font-size: 14px; line-height: 1.6;">Looking for a customized walk-through of our platform for your team? Schedule a dedicated demo session with our specialists.</p>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-xl">
                                <span>Get Started Free</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>                        </div>

                        <div>
                            <h4 class="fw-800 mb-3" style="font-size: 18px;">Follow Us</h4>
                            <div class="social-links d-flex gap-3">
                                <a href="#" title="LinkedIn">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                                        <rect x="2" y="9" width="4" height="12"></rect>
                                        <circle cx="4" cy="4" r="2"></circle>
                                    </svg>
                                </a>
                                <a href="#" title="Twitter / X">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path>
                                    </svg>
                                </a>
                                <a href="#" title="YouTube">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="successPopup" class="success-popup-backdrop {{ session('enterprise_contact_success') ? 'show' : '' }}" role="dialog" aria-modal="true" aria-labelledby="successPopupTitle">
        <div class="success-popup">
            <div class="success-popup-icon" aria-hidden="true">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <h3 id="successPopupTitle">Message Sent Successfully</h3>
            <p>Thank you for reaching out. Your email has been sent to our team, and we will get back to you shortly.</p>
            <button type="button" class="success-popup-close" id="successPopupClose">Great, thanks</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('enterpriseContactForm');
    const popup = document.getElementById('successPopup');
    const popupClose = document.getElementById('successPopupClose');

    function closeSuccessPopup() {
        if (popup) {
            popup.classList.remove('show');
        }
    }
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const btn = form.querySelector('button[type="submit"]');
            
            btn.disabled = true;
            btn.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="width: 18px; height: 18px;"></span>
                <span class="ms-2">Sending...</span>
            `;
        });
    }

    if (popupClose) {
        popupClose.addEventListener('click', closeSuccessPopup);
    }

    if (popup) {
        popup.addEventListener('click', function(e) {
            if (e.target === popup) {
                closeSuccessPopup();
            }
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSuccessPopup();
        }
    });
});
</script>
@endsection
