<nav class="navbar navbar-expand-lg navbar-light shadow-sm mb-4 rounded">
    <div class="container-fluid">
        <button class="sidebar-toggle d-lg-none me-2" id="sidebarToggle2" aria-label="Open sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <span class="navbar-brand fw-bold">
            <i class="fas fa-graduation-cap me-2"></i> Dunco SMS
        </span>
        <form class="d-flex ms-auto me-3">
            <div class="input-group">
                <span class="input-group-text bg-transparent border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input class="form-control border-start-0" type="search" placeholder="Search..." aria-label="Search">
            </div>
        </form>
        <button class="btn btn-outline-dark me-2" id="toggleDarkMode" title="Toggle dark mode">
            <i class="fas fa-moon"></i>
        </button>
        <div class="dropdown">
            <a class="btn btn-outline-secondary dropdown-toggle" href="#" role="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user me-1"></i> {{ Auth::user()?->name ?? 'Guest' }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-edit me-2"></i>Profile
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav> 