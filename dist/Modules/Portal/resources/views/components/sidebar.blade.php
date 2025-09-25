<nav class="portal-sidebar d-flex flex-column p-3 bg-white shadow-sm" style="min-height:100vh;width:220px;position:fixed;top:0;left:0;z-index:1040;">
    <a href="/portal" class="d-flex align-items-center mb-4 text-decoration-none">
        <span class="fs-4 fw-bold text-primary"><i class="fas fa-user-friends me-2"></i>Portal</span>
    </a>
    <ul class="nav nav-pills flex-column mb-auto gap-3">
        <li class="nav-item"><a href="{{ route('portal.dashboard') }}" class="nav-link @if(request()->routeIs('portal.dashboard')) active @endif"><i class="fas fa-home me-2"></i>Dashboard</a></li>
        <li class="nav-item"><a href="{{ route('portal.academics') }}" class="nav-link @if(request()->routeIs('portal.academics')) active @endif"><i class="fas fa-graduation-cap me-2"></i>Academics</a></li>
        <li class="nav-item"><a href="{{ route('portal.schedule') }}" class="nav-link @if(request()->routeIs('portal.schedule')) active @endif"><i class="fas fa-calendar-alt me-2"></i>Schedule</a></li>
        <li class="nav-item"><a href="{{ route('portal.materials') }}" class="nav-link @if(request()->routeIs('portal.materials')) active @endif"><i class="fas fa-book me-2"></i>Materials</a></li>
        <li class="nav-item"><a href="{{ route('portal.assignments') }}" class="nav-link @if(request()->routeIs('portal.assignments')) active @endif"><i class="fas fa-book-open me-2"></i>Assignments</a></li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.finance') ? 'active' : '' }}" href="{{ route('portal.finance') }}">
                <i class="fas fa-money-bill-wave me-2"></i>Finance
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.communication') ? 'active' : '' }}" href="{{ route('portal.communication') }}">
                <i class="fas fa-comments me-2"></i>Communication
            </a>
        </li>
         <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.lms') ? 'active' : '' }}" href="{{ route('portal.lms') }}">
                <i class="fas fa-graduation-cap me-2"></i>LMS
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.hostel') ? 'active' : '' }}" href="{{ route('portal.hostel') }}">
                <i class="fas fa-hotel me-2"></i>Hostel
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.transport') ? 'active' : '' }}" href="{{ route('portal.transport') }}">
                <i class="fas fa-bus me-2"></i>Transport
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.welfare') ? 'active' : '' }}" href="{{ route('portal.welfare') }}">
                <i class="fas fa-heartbeat me-2"></i>Welfare
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.profile') ? 'active' : '' }}" href="{{ route('portal.profile') }}">
                <i class="fas fa-user-circle me-2"></i>Profile
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('portal.library.search') ? 'active' : '' }}" href="{{ route('portal.library.search') }}">
                <i class="fas fa-book me-2"></i>Library
            </a>
        </li>
        {{-- Other links as needed --}}
    </ul>
    <style>
        .portal-sidebar {
            border-top-right-radius: 18px;
            border-bottom-right-radius: 18px;
            background: #fff !important;
            min-height: 100vh;
            width: 220px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            overflow-y: auto;
            max-height: 100vh;
        }
        .portal-sidebar .nav-link {
            color: #222b45;
            font-weight: 500;
            border-radius: 8px;
            font-size: 1.08rem;
            padding: 0.7rem 1.1rem;
            margin-bottom: 0.2rem;
            letter-spacing: 0.2px;
            transition: background 0.18s, color 0.18s;
        }
        .portal-sidebar .nav-link.active, .portal-sidebar .nav-link:hover {
            background: #e3f2fd;
            color: #1565c0;
            font-weight: 700;
        }
        .portal-sidebar .nav-link i {
            font-size: 1.15rem;
            color: #90caf9;
            margin-right: 0.7rem;
        }
        .portal-sidebar .nav-link.active i, .portal-sidebar .nav-link:hover i {
            color: #1565c0;
        }
        .portal-sidebar .nav-item {
            margin-bottom: 0.2rem;
        }
        @media (max-width: 991.98px) {
            .portal-sidebar {
                position: static;
                width: 100%;
                min-height: auto;
                flex-direction: row;
                overflow-x: auto;
                border-radius: 0;
            }
            .portal-sidebar ul {
                flex-direction: row !important;
                gap: 0.5rem;
            }
            .portal-sidebar .nav-link {
                padding: 0.5rem 0.8rem;
                font-size: 1rem;
            }
        }
    </style>
</nav> 