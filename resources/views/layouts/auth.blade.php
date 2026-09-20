<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dunco School Management System')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icon-512x512.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet"/>
    <style>
        :root {
            --brand-50: #ecfeff; --brand-100: #cffafe; --brand-200: #a5f3fc;
            --brand-500: #06b6d4; --brand-600: #0891b2; --brand-700: #0e7490;
            --slate-50: #f8fafc; --slate-100: #f1f5f9; --slate-200: #e2e8f0;
            --slate-300: #cbd5e1; --slate-400: #94a3b8; --slate-500: #64748b;
            --slate-600: #475569; --slate-700: #334155; --slate-800: #1e293b;
            --slate-900: #0f172a;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background: var(--slate-50); -webkit-font-smoothing: antialiased; }
        a { text-decoration: none; color: inherit; }

        .auth-layout { display: flex; min-height: 100vh; }

        .auth-brand-panel {
            display: none; width: 50%; align-items: center; justify-content: center;
            background: linear-gradient(135deg, var(--brand-600) 0%, var(--brand-700) 40%, #4338ca 100%);
            position: relative; overflow: hidden; padding: 3rem;
        }
        @media (min-width: 1024px) { .auth-brand-panel { display: flex; } }
        .auth-brand-panel::before {
            content: ''; position: absolute; top: -30%; right: -20%;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%); border-radius: 50%;
        }
        .auth-brand-panel::after {
            content: ''; position: absolute; bottom: -20%; left: -10%;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%); border-radius: 50%;
        }
        .auth-brand-content { position: relative; z-index: 1; text-align: center; max-width: 400px; }
        .auth-brand-logo {
            width: 64px; height: 64px; background: rgba(255,255,255,0.15); border-radius: 16px;
            display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;
            font-size: 1.75rem; color: white; backdrop-filter: blur(8px);
        }
        .auth-brand-content h2 { font-size: 1.75rem; font-weight: 800; color: white; margin-bottom: 0.75rem; line-height: 1.2; }
        .auth-brand-content p { font-size: 1rem; color: rgba(255,255,255,0.7); line-height: 1.6; }
        .auth-brand-features { margin-top: 2.5rem; text-align: left; }
        .auth-brand-feature {
            display: flex; align-items: center; gap: 12px; padding: 10px 0;
            color: rgba(255,255,255,0.85); font-size: 0.9rem;
        }
        .auth-brand-feature-icon {
            width: 32px; height: 32px; background: rgba(255,255,255,0.1); border-radius: 8px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.8rem;
        }

        .auth-form-panel {
            flex: 1; display: flex; align-items: center; justify-content: center;
            padding: 2rem; background: white;
        }
        @media (min-width: 1024px) { .auth-form-panel { width: 50%; } }

        .auth-form-container { width: 100%; max-width: 400px; }
        .auth-form-header { margin-bottom: 2rem; }
        .auth-form-header .logo-mobile {
            display: flex; align-items: center; gap: 10px; margin-bottom: 1.5rem;
        }
        @media (min-width: 1024px) { .auth-form-header .logo-mobile { display: none; } }
        .logo-mobile-icon {
            width: 40px; height: 40px; background: var(--brand-600); border-radius: 10px;
            display: flex; align-items: center; justify-content: center; color: white; font-size: 1.1rem;
        }
        .logo-mobile-text { font-size: 1.15rem; font-weight: 700; color: var(--slate-900); }
        .auth-form-header h1 { font-size: 1.5rem; font-weight: 700; color: var(--slate-900); margin-bottom: 0.5rem; }
        .auth-form-header p { font-size: 0.9rem; color: var(--slate-500); }

        .auth-card {
            background: white; border: 1px solid var(--slate-200); border-radius: 16px;
            padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            animation: authFadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes authFadeIn { from { opacity: 0; transform: scale(0.97); } to { opacity: 1; transform: scale(1); } }

        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--slate-700); margin-bottom: 6px; }
        .form-input {
            width: 100%; height: 40px; padding: 0 12px; border: 1px solid var(--slate-300); border-radius: 8px;
            font-size: 0.875rem; color: var(--slate-900); background: white; outline: none;
            transition: border-color 0.15s, box-shadow 0.15s; font-family: inherit;
        }
        .form-input::placeholder { color: var(--slate-400); }
        .form-input:focus { border-color: var(--brand-500); box-shadow: 0 0 0 3px rgba(6,182,212,0.15); }
        .form-input.error { border-color: #ef4444; }
        .form-input.error:focus { box-shadow: 0 0 0 3px rgba(239,68,68,0.15); }
        .form-error { font-size: 0.8rem; color: #ef4444; margin-top: 4px; }

        .form-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
        .form-check { display: flex; align-items: center; gap: 8px; }
        .form-check input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--brand-600); cursor: pointer; }
        .form-check label { font-size: 0.85rem; color: var(--slate-600); cursor: pointer; }
        .form-forgot { font-size: 0.85rem; color: var(--brand-600); font-weight: 500; }
        .form-forgot:hover { color: var(--brand-700); }

        .btn-primary {
            width: 100%; height: 44px; background: var(--brand-600); color: white; border: none;
            border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer;
            transition: background 0.15s, box-shadow 0.15s; font-family: inherit;
            box-shadow: 0 1px 2px rgba(8,145,178,0.2);
        }
        .btn-primary:hover { background: var(--brand-700); box-shadow: 0 2px 8px rgba(8,145,178,0.3); }
        .btn-primary:active { transform: scale(0.98); }

        .auth-divider { display: flex; align-items: center; gap: 12px; margin: 1.5rem 0; }
        .auth-divider::before, .auth-divider::after { content: ''; flex: 1; height: 1px; background: var(--slate-200); }
        .auth-divider span { font-size: 0.8rem; color: var(--slate-400); white-space: nowrap; }

        .auth-footer { text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: var(--slate-500); }
        .auth-footer a { color: var(--brand-600); font-weight: 500; }
        .auth-footer a:hover { color: var(--brand-700); }

        .alert { padding: 12px 16px; border-radius: 8px; font-size: 0.875rem; margin-bottom: 1rem; }
        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-danger ul { margin: 0; padding-left: 1.2rem; }

        .input-group { position: relative; }
        .input-group .form-input { padding-right: 40px; }
        .input-toggle {
            position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: var(--slate-400); cursor: pointer;
            padding: 4px; display: flex; align-items: center; justify-content: center;
        }
        .input-toggle:hover { color: var(--slate-600); }

        @media (max-width: 640px) {
            .auth-form-panel { padding: 1.5rem; }
            .auth-card { padding: 1.5rem; }
        }
    </style>
    @stack('head')
</head>
<body>
    <div class="auth-layout">
        <div class="auth-brand-panel">
            <div class="auth-brand-content">
                <div class="auth-brand-logo"><i class="fas fa-graduation-cap"></i></div>
                <h2>Dunco School Management System</h2>
                <p>A comprehensive platform to manage students, staff, academics, finances, and everything in between.</p>
                <div class="auth-brand-features">
                    <div class="auth-brand-feature">
                        <div class="auth-brand-feature-icon"><i class="fas fa-chart-line"></i></div>
                        <span>Real-time analytics and reporting</span>
                    </div>
                    <div class="auth-brand-feature">
                        <div class="auth-brand-feature-icon"><i class="fas fa-shield-halved"></i></div>
                        <span>Enterprise-grade security</span>
                    </div>
                    <div class="auth-brand-feature">
                        <div class="auth-brand-feature-icon"><i class="fas fa-mobile-screen-button"></i></div>
                        <span>Mobile-friendly for everyone</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="auth-form-panel">
            <div class="auth-form-container">
                @yield('content')
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
