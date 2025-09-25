<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AI ChatBot Assistant')</title>
    <!-- Modern Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="manifest" href="/manifest.json">
    <style>
        :root {
            /* Modern color palette */
            --color-primary: #1ea7ff;
            --color-accent: #1565c0;
            --color-success: #22c55e;
            --color-warning: #ffb300;
            --color-error: #e53935;
            --color-info: #6ec1e4;
            --color-bg: #f8f9fa;
            --color-surface: #fff;
            --color-sidebar: #1a237e;
            --color-sidebar-accent: #3949ab;
            --color-sidebar-header: #0d133d;
            --color-text: #222b45;
            --color-text-muted: #b0bec5;
            --color-shadow: 0 8px 32px 0 rgba(30,167,255,0.10), 0 1.5px 6px 0 rgba(21,101,192,0.10);
            /* Dark mode ready */
            --color-bg-dark: #0a1931;
            --color-surface-dark: #11224d;
            --color-text-dark: #eaf6fb;
        }
        html, body {
            font-family: 'Inter', 'Poppins', system-ui, Arial, sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            font-size: 16px;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
        }
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 0.5em;
        }
        h1 { font-size: 2.2rem; }
        h2 { font-size: 1.6rem; }
        h3 { font-size: 1.3rem; }
        
        /* Simple header for chatbot */
        .chatbot-header {
            background: linear-gradient(135deg, #162447 0%, #1a237e 60%, #3949ab 100%);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 4px 16px rgba(30,167,255,0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .chatbot-header .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .chatbot-header .brand-logo {
            background: rgba(30,167,255,0.2);
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 1.1rem;
        }
        
        .chatbot-header .back-link {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        
        .chatbot-header .back-link:hover {
            color: white;
        }
        
        /* Main content area */
        .chatbot-main {
            height: calc(100vh - 80px);
            overflow: hidden;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .chatbot-header {
                padding: 1rem;
            }
            
            .chatbot-header .brand {
                font-size: 1rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="chatbot-header">
        <div class="brand">
            <div class="brand-logo">
                <i class="fas fa-robot"></i>
            </div>
            <span>Dunco School AI Assistant</span>
        </div>
        <a href="{{ route('dashboard') }}" class="back-link">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
    
    <div class="chatbot-main">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    @yield('scripts')
</body>
</html> 