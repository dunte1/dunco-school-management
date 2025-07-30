<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication Module</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; }
        .comm-sidebar { min-height: 100vh; width: 220px; position: fixed; top: 0; left: 0; z-index: 1040; background: #fff; box-shadow: 2px 0 8px rgba(0,0,0,0.04); }
        .comm-sidebar .nav-link { color: #333; font-weight: 500; }
        .comm-sidebar .nav-link.active, .comm-sidebar .nav-link:hover { background: #e3e9f7; color: #1a237e; }
        .comm-sidebar .sidebar-header { font-size: 1.5rem; font-weight: bold; color: #1a237e; padding: 1.5rem 1rem 1rem 1rem; }
        .comm-content { margin-left: 220px; padding: 2rem 2rem 2rem 2rem; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="comm-sidebar d-flex flex-column p-3">
        <div class="sidebar-header mb-4">
            <i class="fas fa-comments me-2"></i> Communication
        </div>
        <ul class="nav nav-pills flex-column mb-auto gap-2">
            <li class="nav-item"><a href="{{ route('communication.dashboard') }}" class="nav-link @if(request()->routeIs('communication.dashboard')) active @endif"><i class="fas fa-home me-2"></i>Dashboard</a></li>
            <li class="nav-item"><a href="{{ route('communication.inbox') }}" class="nav-link @if(request()->routeIs('communication.inbox')) active @endif"><i class="fas fa-inbox me-2"></i>Inbox</a></li>
            <li class="nav-item"><a href="{{ route('communication.outbox') }}" class="nav-link @if(request()->routeIs('communication.outbox')) active @endif"><i class="fas fa-paper-plane me-2"></i>Outbox</a></li>
            <li class="nav-item"><a href="{{ route('communication.compose') }}" class="nav-link @if(request()->routeIs('communication.compose')) active @endif"><i class="fas fa-edit me-2"></i>Compose</a></li>
            <li class="nav-item"><a href="{{ route('communication.groups') }}" class="nav-link @if(request()->routeIs('communication.groups*')) active @endif"><i class="fas fa-users me-2"></i>Groups</a></li>
            @if(auth()->user()->hasRole(['admin', 'super_admin']))
                <li class="nav-item"><a href="{{ route('communication.broadcasts') }}" class="nav-link @if(request()->routeIs('communication.broadcasts*')) active @endif"><i class="fas fa-bullhorn me-2"></i>Broadcasts</a></li>
            @endif
            <li class="nav-item"><a href="{{ route('communication.announcements') }}" class="nav-link @if(request()->routeIs('communication.announcements*')) active @endif"><i class="fas fa-bell me-2"></i>Announcements</a></li>
            <li class="nav-item">
                <a href="{{ route('communication.notifications') }}" class="nav-link @if(request()->routeIs('communication.notifications*')) active @endif">
                    <i class="fas fa-bell me-2"></i>Notifications
                    <span id="notification-count" class="badge bg-danger ms-auto" style="display: none;">0</span>
                </a>
            </li>
            <li class="nav-item"><a href="{{ route('communication.notifications.settings') }}" class="nav-link @if(request()->routeIs('communication.notifications.settings*')) active @endif"><i class="fas fa-cog me-2"></i>Notification Settings</a></li>
        </ul>
    </nav>
    <main class="comm-content">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html> 