<nav class="public-nav" id="mainNav" aria-label="Main navigation">
    <div class="nav-container">
        <a href="{{ route('welcome') }}" class="nav-brand" aria-label="Dunco SMS Home">
            <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <rect width="36" height="36" rx="8" fill="#2563eb"/>
                <path d="M18 10L8 15L18 20L28 15L18 10Z" fill="white"/>
                <path d="M8 15V22L18 27V22L8 15Z" fill="rgba(255,255,255,0.8)"/>
                <path d="M28 15V22L18 27V22L28 15Z" fill="rgba(255,255,255,0.6)"/>
            </svg>
            <span class="nav-brand-text">Dunco<span class="text-blue-600">SMS</span></span>
        </a>

        <div class="nav-links-desktop">
            <a href="{{ route('welcome') }}#features" class="nav-link">Features</a>
            <a href="{{ route('welcome') }}#modules" class="nav-link">Modules</a>
            <a href="{{ route('welcome') }}#how-it-works" class="nav-link">How It Works</a>
            <a href="{{ route('welcome') }}#testimonials" class="nav-link">Testimonials</a>
            <a href="{{ route('welcome') }}#faq" class="nav-link">FAQ</a>
            <a href="{{ route('public.contact') }}" class="nav-link">Contact</a>
        </div>

        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-nav-outline">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-nav-outline">Log In</a>
                <a href="{{ route('public.demo') }}" class="btn-nav-primary">Request a Demo</a>
            @endauth
        </div>

        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="mobileMenu">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
    </div>

    <div class="mobile-menu" id="mobileMenu" aria-hidden="true">
        <div class="mobile-menu-content">
            <a href="{{ route('welcome') }}#features" class="mobile-nav-link">Features</a>
            <a href="{{ route('welcome') }}#modules" class="mobile-nav-link">Modules</a>
            <a href="{{ route('welcome') }}#how-it-works" class="mobile-nav-link">How It Works</a>
            <a href="{{ route('welcome') }}#testimonials" class="mobile-nav-link">Testimonials</a>
            <a href="{{ route('welcome') }}#faq" class="mobile-nav-link">FAQ</a>
            <a href="{{ route('public.contact') }}" class="mobile-nav-link">Contact</a>
            <div class="mobile-menu-divider"></div>
            @auth
                <a href="{{ route('dashboard') }}" class="mobile-nav-link font-semibold">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="mobile-nav-link">Log In</a>
                <a href="{{ route('public.demo') }}" class="mobile-nav-link font-semibold text-blue-600">Request a Demo</a>
            @endauth
        </div>
    </div>
</nav>
