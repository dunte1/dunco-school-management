<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dunco School Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 220px;
            background: #1a237e;
            color: #fff;
            z-index: 1030;
            transition: width 0.3s ease, left 0.3s ease;
            overflow-x: hidden;
        }
        .sidebar.collapsed {
            width: 60px;
        }
        .sidebar .sidebar-header {
            padding: 1.5rem 1rem 1rem 1rem;
            font-size: 1.3rem;
            font-weight: bold;
            background: #0d133d;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 70px;
        }
        .sidebar .collapse-btn {
            background: none;
            border: none;
            color: #b0bec5;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: color 0.2s, background 0.2s;
        }
        .sidebar .collapse-btn:hover {
            color: #fff;
            background: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link {
            color: #fff;
            font-size: 1.05rem;
            padding: 0.75rem 1.5rem;
            border-radius: 0 2rem 2rem 0;
            margin-bottom: 0.2rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            white-space: nowrap;
            text-decoration: none;
        }
        .sidebar.collapsed .nav-link {
            padding: 0.75rem 0.7rem;
            font-size: 1.2rem;
            justify-content: center;
            border-radius: 0.5rem;
            margin: 0 0.5rem 0.2rem 0.5rem;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #3949ab;
            color: #fff;
            transform: translateX(2px);
        }
        .sidebar.collapsed .nav-link.active, .sidebar.collapsed .nav-link:hover {
            transform: none;
        }
        .sidebar .nav-icon {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.7rem;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .sidebar.collapsed .nav-icon {
            margin-right: 0;
        }
        .sidebar .nav-text {
            transition: opacity 0.2s ease, width 0.2s ease;
            overflow: hidden;
        }
        .sidebar.collapsed .nav-text {
            opacity: 0;
            width: 0;
        }
        .sidebar-section {
            padding: 0.5rem 1.5rem 0.2rem 1.5rem;
            font-size: 0.85rem;
            color: #b0bec5;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: opacity 0.2s ease;
            white-space: nowrap;
        }
        .sidebar.collapsed .sidebar-section {
            opacity: 0;
            height: 0;
            padding: 0;
            margin: 0;
        }
        .sidebar.collapsed .sidebar-header .brand-text {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }
        .main-content {
            margin-left: 220px;
            padding: 2rem 1rem 1rem 1rem;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }
        .sidebar.collapsed ~ .main-content {
            margin-left: 60px;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                left: -220px;
                width: 220px;
            }
            .sidebar.show {
                left: 0;
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar.collapsed ~ .main-content {
                margin-left: 0;
            }
        }
        .sidebar-toggle {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1040;
            background: #fff;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .sidebar-toggle:hover {
            transform: scale(1.05);
        }
        .sidebar-toggle:focus {
            outline: none;
        }
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.3);
            z-index: 1029;
            backdrop-filter: blur(2px);
        }
        .sidebar.show ~ .sidebar-backdrop {
            display: block;
        }
        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95) !important;
        }
        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
    </style>
    @stack('styles')
</head>
<body>
    <x-sidebar />
    <div class="main-content">
        <x-navbar />
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar functionality
        const sidebar = document.getElementById('sidebarNav');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');
        const collapseBtn = document.getElementById('sidebarCollapseBtn');
        
        // Mobile sidebar toggle
        function toggleMobileSidebar() {
            sidebar.classList.toggle('show');
            sidebarBackdrop.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
        }
        
        document.getElementById('sidebarToggle').onclick = toggleMobileSidebar;
        document.getElementById('sidebarToggle2').onclick = toggleMobileSidebar;
        
        // Close sidebar on backdrop click
        sidebarBackdrop.onclick = function() {
            sidebar.classList.remove('show');
            sidebarBackdrop.style.display = 'none';
        };
        
        // Desktop sidebar collapse/expand
        function setSidebarCollapsed(collapsed) {
            if (collapsed) {
                sidebar.classList.add('collapsed');
                collapseBtn.innerHTML = '<i class="fas fa-angle-double-right"></i>';
                collapseBtn.setAttribute('aria-label', 'Expand sidebar');
            } else {
                sidebar.classList.remove('collapsed');
                collapseBtn.innerHTML = '<i class="fas fa-angle-double-left"></i>';
                collapseBtn.setAttribute('aria-label', 'Collapse sidebar');
            }
            localStorage.setItem('sidebarCollapsed', collapsed ? '1' : '0');
        }
        
        if (collapseBtn) {
            collapseBtn.onclick = function() {
                setSidebarCollapsed(!sidebar.classList.contains('collapsed'));
            };
            
            // Restore sidebar state on page load
            if (window.innerWidth > 991) {
                const wasCollapsed = localStorage.getItem('sidebarCollapsed') === '1';
                setSidebarCollapsed(wasCollapsed);
            }
        }
        
        // Handle responsive behavior
        function handleResize() {
            if (window.innerWidth <= 991) {
                // Mobile: remove collapsed state
                sidebar.classList.remove('collapsed');
                sidebar.classList.remove('show');
                sidebarBackdrop.style.display = 'none';
            } else {
                // Desktop: restore collapsed state if it was saved
                const wasCollapsed = localStorage.getItem('sidebarCollapsed') === '1';
                setSidebarCollapsed(wasCollapsed);
            }
        }
        
        window.addEventListener('resize', handleResize);
        
        // Dark mode toggle
        document.getElementById('toggleDarkMode').onclick = function() {
            const isDark = document.body.classList.toggle('bg-dark');
            document.body.classList.toggle('text-white', isDark);
            document.querySelectorAll('.card').forEach(card => {
                card.classList.toggle('bg-dark', isDark);
                card.classList.toggle('text-white', isDark);
            });
            document.querySelectorAll('.navbar').forEach(navbar => {
                navbar.classList.toggle('bg-dark', isDark);
                navbar.classList.toggle('navbar-dark', isDark);
            });
            localStorage.setItem('darkMode', isDark ? '1' : '0');
        };
        
        // Restore dark mode state
        if (localStorage.getItem('darkMode') === '1') {
            document.getElementById('toggleDarkMode').click();
        }
        
        // Add tooltips for collapsed sidebar
        if (window.innerWidth > 991) {
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                if (sidebar.classList.contains('collapsed')) {
                    link.setAttribute('data-bs-toggle', 'tooltip');
                    link.setAttribute('data-bs-placement', 'right');
                }
            });
        }
        
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
    @stack('scripts')
</body>
</html> 