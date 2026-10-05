<header class="sticky top-0 z-30 backdrop-blur-md bg-white/60 supports-[backdrop-filter]:bg-white/50 border-b border-white/40 shadow-[0_10px_30px_rgba(2,8,23,0.06)] transition-all duration-300" id="main-header">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 lg:h-24 flex items-center justify-between">
    
    <!-- 1️⃣ Branding with Logo -->
    <div class="flex items-center gap-3">
      <a href="{{ url('/') }}" class="flex items-center gap-3" aria-label="Go to homepage">
        <!-- Logo Container with proper spacing (no hover effects) -->
        <div class="size-12 lg:size-14 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600 grid place-content-center overflow-hidden ring-1 ring-blue-200">
          @if(isset($branding) && !empty($branding['logo_url']))
            <img src="{{ $branding['logo_url'] }}" alt="{{ $branding['name'] ?? 'Logo' }}" class="h-10 w-10 lg:h-12 lg:w-12 object-contain">
          @else
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 lg:size-7">
              <path d="M11.7 2.1a1 1 0 0 1 .6 0l8 2.7a1 1 0 0 1 .7 1v7.9a5 5 0 0 1-3 4.6l-6 2.7a1 1 0 0 1-.8 0l-6-2.7a5 5 0 0 1-3-4.6V5.8a1 1 0 0 1 .7-1l8-2.7Z"/>
            </svg>
          @endif
        </div>
        <!-- Brand Name -->
        <div class="hidden sm:block">
          <div class="text-lg lg:text-xl font-extrabold tracking-tight" style="color: {{ isset($branding) ? ($branding['text_color'] ?? '#111827') : '#111827' }}">
            {{ isset($branding) ? ($branding['name'] ?? config('app.name')) : config('app.name') }}
          </div>
          @if(isset($branding) && !empty($branding['tagline']))
            <div class="text-xs text-slate-500 font-medium">{{ $branding['tagline'] }}</div>
          @endif
        </div>
      </a>
    </div>
    
    <!-- 2️⃣ Desktop Navigation -->
    <nav class="hidden lg:flex items-center gap-2" role="navigation" aria-label="Main navigation">
      <a href="{{ url('/') }}" class="text-sm font-medium text-slate-700 hover:text-slate-900 transition-all duration-200 relative group px-3 py-2 rounded-md hover:bg-slate-50">
        Home
        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-200"></span>
      </a>
      <a href="{{ route('features') }}" class="text-sm font-medium text-slate-700 hover:text-slate-900 transition-all duration-200 relative group px-3 py-2 rounded-md hover:bg-slate-50">
        Features
        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-200"></span>
      </a>
      <a href="{{ route('about') }}" class="text-sm font-medium text-slate-700 hover:text-slate-900 transition-all duration-200 relative group px-3 py-2 rounded-md hover:bg-slate-50">
        About
        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-200"></span>
      </a>
      <a href="{{ route('contact') }}" class="text-sm font-medium text-slate-700 hover:text-slate-900 transition-all duration-200 relative group px-3 py-2 rounded-md hover:bg-slate-50">
        Contact
        <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-blue-600 group-hover:w-full transition-all duration-200"></span>
      </a>
    </nav>

    <!-- 3️⃣ Call-to-Action (CTA) -->
    <div class="flex items-center gap-3">
      @auth
        <a href="{{ \App\Helpers\NavigationHelper::hasRole('super_admin') ? url('/super-admin') : route('dashboard') }}" class="hidden sm:inline-flex items-center rounded-xl bg-gradient-to-tr from-indigo-600 via-blue-600 to-cyan-500 text-white px-4 py-2.5 text-sm font-semibold shadow-[0_6px_20px_rgba(37,99,235,0.35)] transition-transform duration-200 ease-out hover:-translate-y-0.5 hover:scale-[1.02] hover:shadow-[0_10px_25px_rgba(37,99,235,0.45)] hover:from-indigo-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/70 focus:ring-offset-2">
          Dashboard
        </a>
        <button type="button" onclick="handleLogout()" class="hidden sm:inline-flex items-center rounded-xl px-3 py-2.5 text-sm font-semibold text-slate-700 bg-white/40 border border-white/60 shadow-[inset_0_1px_0_rgba(255,255,255,0.6)] transition-transform duration-200 ease-out hover:-translate-y-0.5 hover:scale-[1.02] hover:bg-white/60 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500/50 focus:ring-offset-2">
          Logout
        </button>
      @else
        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center rounded-full px-4 py-2.5 text-sm font-semibold text-white bg-slate-900 shadow-sm transition-transform duration-200 ease-out hover:-translate-y-0.5 hover:scale-[1.02] hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
          Login
        </a>
        <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center gap-2 rounded-full bg-white text-slate-900 px-4 py-2.5 text-sm font-semibold shadow-sm transition-transform duration-200 ease-out hover:-translate-y-0.5 hover:scale-[1.02] hover:shadow-md hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
          <span>Get Started</span>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
            <path d="M13.5 4.5a.75.75 0 000 1.5h4.19L6.22 17.47a.75.75 0 101.06 1.06L18.75 7.06v4.19a.75.75 0 001.5 0v-6a.75.75 0 00-.75-.75h-6z" />
          </svg>
        </a>
      @endauth
    </div>

    <!-- 4️⃣ Mobile Menu Button -->
    <button id="mobile-menu-button" class="lg:hidden inline-flex items-center justify-center p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-all duration-200" aria-expanded="false" aria-controls="mobile-menu" aria-label="Toggle main menu">
      <!-- Hamburger Icon -->
      <svg id="hamburger-icon" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
      <!-- Close Icon -->
      <svg id="close-icon" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  <!-- 4️⃣ Mobile Menu -->
  <div id="mobile-menu" class="lg:hidden hidden bg-white border-t border-slate-200 shadow-lg" role="navigation" aria-label="Mobile navigation">
    <div class="px-4 py-3 space-y-1">
      <a href="{{ url('/') }}" class="block px-3 py-3 rounded-lg text-base font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset">
        Home
      </a>
      <a href="{{ route('features') }}" class="block px-3 py-3 rounded-lg text-base font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset">
        Features
      </a>
      <a href="{{ route('about') }}" class="block px-3 py-3 rounded-lg text-base font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset">
        About
      </a>
      <a href="{{ route('contact') }}" class="block px-3 py-3 rounded-lg text-base font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset">
        Contact
      </a>
      
      <div class="pt-4 pb-3 border-t border-slate-200 space-y-2">
        @auth
          <a href="{{ \App\Helpers\NavigationHelper::hasRole('super_admin') ? url('/super-admin') : route('dashboard') }}" class="block px-3 py-3 rounded-lg text-base font-medium bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-inset">
            Dashboard
          </a>
          <button type="button" onclick="handleLogout()" class="w-full text-left px-3 py-3 rounded-lg text-base font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-inset">
            Logout
          </button>
        @else
          <a href="{{ route('login') }}" class="block px-3 py-3 rounded-lg text-base font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-inset">
            Login
          </a>
          <a href="{{ route('register') }}" class="block px-3 py-3 rounded-lg text-base font-medium bg-gradient-to-r from-slate-900 to-slate-800 text-white hover:from-slate-800 hover:to-slate-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-inset">
            Get Started
          </a>
        @endauth
      </div>
    </div>
  </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const mobileMenuButton = document.getElementById('mobile-menu-button');
  const mobileMenu = document.getElementById('mobile-menu');
  const hamburgerIcon = document.getElementById('hamburger-icon');
  const closeIcon = document.getElementById('close-icon');
  const header = document.getElementById('main-header');

  // Mobile menu toggle
  mobileMenuButton.addEventListener('click', function() {
    const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
    
    if (isExpanded) {
      // Close menu
      mobileMenu.classList.add('hidden');
      mobileMenuButton.setAttribute('aria-expanded', 'false');
      hamburgerIcon.classList.remove('hidden');
      closeIcon.classList.add('hidden');
    } else {
      // Open menu
      mobileMenu.classList.remove('hidden');
      mobileMenuButton.setAttribute('aria-expanded', 'true');
      hamburgerIcon.classList.add('hidden');
      closeIcon.classList.remove('hidden');
    }
  });

  // Close menu when clicking outside
  document.addEventListener('click', function(event) {
    if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
      mobileMenu.classList.add('hidden');
      mobileMenuButton.setAttribute('aria-expanded', 'false');
      hamburgerIcon.classList.remove('hidden');
      closeIcon.classList.add('hidden');
    }
  });

  // Close menu on escape key
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      mobileMenu.classList.add('hidden');
      mobileMenuButton.setAttribute('aria-expanded', 'false');
      hamburgerIcon.classList.remove('hidden');
      closeIcon.classList.add('hidden');
    }
  });

  // Header scroll effect
  let lastScrollTop = 0;
  window.addEventListener('scroll', function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (scrollTop > lastScrollTop && scrollTop > 100) {
      // Scrolling down
      header.style.transform = 'translateY(-100%)';
    } else {
      // Scrolling up
      header.style.transform = 'translateY(0)';
    }
    
    lastScrollTop = scrollTop;
  });
  
  // Handle logout with immediate UI update
  window.handleLogout = function() {
    // Show loading state
    const logoutBtn = event.target;
    const originalText = logoutBtn.textContent;
    logoutBtn.textContent = 'Logging out...';
    logoutBtn.disabled = true;
    
    // Submit logout form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("logout") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    form.appendChild(csrfToken);
    document.body.appendChild(form);
    form.submit();
  };
});
</script>