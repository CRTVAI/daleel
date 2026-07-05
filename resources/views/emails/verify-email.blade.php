<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: #f4f7fa; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f7fa; padding: 40px 0; }
        .main { background-color: #ffffff; margin: 0 auto; max-width: 560px; border-radius: 24px; overflow: hidden; box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.05); }
        .header { padding: 48px 40px 32px; text-align: center; }
        .logo-img { max-width: 180px; height: auto; display: inline-block; }
        .content { padding: 0 48px 48px; text-align: left; color: #475569; line-height: 1.7; }
        .content h1 { color: #0f172a; font-size: 28px; font-weight: 800; margin-bottom: 24px; letter-spacing: -0.02em; text-align: center; }
        .content p { font-size: 16px; margin-bottom: 24px; }
        .greeting { font-weight: 700; color: #1e1b4b; font-size: 18px; margin-bottom: 12px !important; }
        .btn-wrapper { text-align: center; margin: 40px 0; }
        .btn { background: #1e1b4b; color: #ffffff !important; padding: 18px 40px; border-radius: 14px; text-decoration: none; font-weight: 700; font-size: 16px; display: inline-block; box-shadow: 0 10px 25px -5px rgba(30, 27, 75, 0.3); }
        .safety { background: #f8fafc; padding: 32px 48px; color: #64748b; font-size: 14px; text-align: center; border-top: 1px solid #f1f5f9; }
        .footer { padding: 40px 0; text-align: center; color: #94a3b8; font-size: 12px; font-weight: 500; }
        .footer a { color: #6366f1; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            <div class="header">
                <img src="{{ url(asset('images/dashboard/logo.png')) }}" class="logo-img" alt="Daleel AI">
            </div>
            <div class="content">
                <h1>Email Verification</h1>
                <p class="greeting">Hello {{ $name }},</p>
                <p>Welcome to <strong>Daleel AI</strong>. We're excited to have you on board! Please verify your email address to activate your account and start your personalized learning journey.</p>
                
                <div class="btn-wrapper">
                    <a href="{{ $url }}" class="btn">Verify my email</a>
                </div>

                <p style="font-size: 13px; color: #94a3b8; text-align: center; margin-top: 32px;">This link will expire in 60 minutes.</p>
            </div>
            <div class="safety">
                If you did not create a Daleel AI account, you can safely ignore this email.
            </div>
        </div>
        <div class="footer">
            &copy; 2026 Daleel AI by <a href="#">Creative AI</a>. All rights reserved.
        </div>
    </div>
</body>
</html>
