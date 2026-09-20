<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dunco School Management System')</title>
    <!-- Modern Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icon-512x512.png') }}">
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
        }
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 0.5em;
        }
        h1 { font-size: 2.2rem; }
        h2 { font-size: 1.6rem; }
        h3 { font-size: 1.3rem; }
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 224px;
            background: #0f172a;
            color: #e2e8f0;
            z-index: 1030;
            transition: width 0.3s, left 0.3s;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            justify-content: flex-start;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        .sidebar.collapsed { width: 68px; }
        .sidebar.collapsed .sidebar-header { justify-content: center; padding: 0; }
        .sidebar.collapsed .brand-text { display: none; }
        .sidebar.collapsed .sidebar-collapse-toggle { display: none !important; }
        .sidebar.collapsed #sidebarExpandBtn { display: flex !important; }
        .sidebar.collapsed .sidebar-section { display: none; }
        .sidebar.collapsed .nav-link,
        .sidebar.collapsed .accordion-button { padding: 0; justify-content: center; border-radius: 8px; margin: 2px 6px; height: 40px; }
        .sidebar.collapsed .nav-link .nav-text,
        .sidebar.collapsed .accordion-button .nav-text,
        .sidebar.collapsed .accordion-button .custom-chevron,
        .sidebar.collapsed .nav-link .badge { display: none !important; }
        .sidebar.collapsed .nav-link .nav-icon,
        .sidebar.collapsed .accordion-button .nav-icon { width: auto; min-width: unset; font-size: 20px; }
        .sidebar.collapsed .accordion-body,
        .sidebar.collapsed .accordion-collapse { display: none !important; }
        #sidebarExpandBtn { display: none; }

        /* Sidebar header */
        .sidebar .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            padding: 0 16px;
            border-bottom: 1px solid #334155;
            flex-shrink: 0;
        }
        .sidebar .brand-text {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 700;
            color: #f1f5f9;
            white-space: nowrap;
            overflow: hidden;
        }
        .sidebar .brand-logo {
            width: 32px;
            height: 32px;
            background: rgba(6,182,212,0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: #06b6d4;
            flex-shrink: 0;
            line-height: 1;
        }
        .sidebar .collapse-btn {
            background: none;
            border: none;
            color: #64748b;
            font-size: 0.85rem;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            transition: color 0.15s, background 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .sidebar .collapse-btn:hover { color: #e2e8f0; background: rgba(255,255,255,0.05); }

        /* Section titles */
        .sidebar-section {
            padding: 20px 16px 8px;
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 600;
            width: 100%;
        }

        /* Nav link (top-level) */
        .sidebar .nav-link {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 12px;
            height: 40px;
            padding: 0 12px;
            margin: 1px 8px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: #cbd5e1;
            text-decoration: none;
            white-space: nowrap;
            transition: background 0.15s, color 0.15s;
            position: relative;
            outline: none;
            width: calc(100% - 16px);
        }
        .sidebar .nav-link:hover { background: rgba(255,255,255,0.05); color: #f1f5f9; }
        .sidebar .nav-link:focus-visible { outline: 2px solid rgba(6,182,212,0.5); outline-offset: -2px; }
        .sidebar .nav-link.active { background: rgba(6,182,212,0.12); color: #f1f5f9; font-weight: 600; }

        /* Nav icon */
        .sidebar .nav-link .nav-icon,
        .sidebar .accordion-button .nav-icon {
            width: 20px;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
            transition: color 0.15s;
        }
        .sidebar .nav-link:hover .nav-icon,
        .sidebar .nav-link.active .nav-icon,
        .sidebar .accordion-button:hover .nav-icon { color: #22d3ee; }

        /* Nav text */
        .sidebar .nav-link .nav-text,
        .sidebar .accordion-button .nav-text {
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 13px;
            font-weight: 500;
            text-align: left;
            line-height: 1;
        }
        .sidebar .nav-link.active .nav-text { font-weight: 600; }

        /* Badge */
        .sidebar .nav-link .badge,
        .sidebar .accordion-button .badge {
            background: #06b6d4;
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            border-radius: 10px;
            padding: 1px 6px;
            margin-left: auto;
            flex-shrink: 0;
        }

        /* Accordion button (parent menu items) */
        .sidebar .accordion-button {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 12px;
            height: 40px;
            padding: 0 12px;
            margin: 1px 8px;
            border-radius: 8px;
            border: none;
            background: transparent;
            font-size: 13px;
            font-weight: 500;
            color: #cbd5e1;
            text-align: left;
            white-space: nowrap;
            transition: background 0.15s, color 0.15s;
            outline: none;
            width: calc(100% - 16px);
            cursor: pointer;
        }
        .sidebar .accordion-button:hover { background: rgba(255,255,255,0.05); color: #f1f5f9; }
        .sidebar .accordion-button::after { display: none !important; }
        .sidebar .accordion-button .custom-chevron {
            color: #475569;
            font-size: 12px;
            margin-left: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.2s;
        }
        .sidebar .accordion-button:not(.collapsed) .custom-chevron { transform: rotate(180deg); }

        /* Accordion body / submenu */
        .sidebar .accordion-body,
        .sidebar .nav.flex-column { width: 100%; display: flex; flex-direction: column; padding: 0; }
        .sidebar .accordion-body .nav-link,
        .sidebar .accordion-body .nav .nav-link {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 10px;
            height: 36px;
            padding: 0 12px 0 44px;
            margin: 1px 8px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 400;
            color: #94a3b8;
            text-align: left;
            width: calc(100% - 16px);
            transition: background 0.15s, color 0.15s;
        }
        .sidebar .accordion-body .nav-link:hover,
        .sidebar .accordion-body .nav .nav-link:hover { background: rgba(255,255,255,0.04); color: #e2e8f0; }
        .sidebar .accordion-body .nav-link.active,
        .sidebar .accordion-body .nav .nav-link.active { background: rgba(6,182,212,0.12); color: #f1f5f9; font-weight: 500; }
        .sidebar .accordion-body .nav-link.d-flex,
        .sidebar .accordion-body .nav .nav-link.d-flex {
            display: flex !important; flex-direction: row !important; align-items: center !important;
            gap: 10px; margin-bottom: 0 !important; color: #94a3b8 !important;
        }
        .sidebar .accordion-body .nav-link.d-flex:hover,
        .sidebar .accordion-body .nav .nav-link.d-flex:hover { color: #e2e8f0 !important; }
        .sidebar .accordion-body .nav-link.d-flex.active,
        .sidebar .accordion-body .nav .nav-link.d-flex.active { color: #f1f5f9 !important; }
        .sidebar .accordion-body .nav-link .me-2,
        .sidebar .accordion-body .nav .nav-link .me-2 { margin-right: 0 !important; }

        /* Toggle buttons */
        .sidebar-collapse-toggle, .expand-btn {
            background: rgba(255,255,255,0.05);
            color: #64748b;
            border: none;
            border-radius: 6px;
            width: 32px;
            height: 32px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s, color 0.15s;
            cursor: pointer;
            margin: 0;
        }
        .sidebar-collapse-toggle:hover, .expand-btn:hover { background: rgba(255,255,255,0.08); color: #e2e8f0; }

        /* Mobile overlay */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1020; }
        .sidebar-overlay.show { display: block; }

        /* Main content */
        .main-content {
            margin-left: 224px;
            padding: 1.5rem;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            width: calc(100vw - 224px);
            overflow-x: auto;
        }
        .sidebar.collapsed ~ .main-content {
            margin-left: 68px;
            width: calc(100vw - 68px);
        }
        @media (max-width: 991.98px) {
            .sidebar { left: -260px; width: 260px; }
            .sidebar.show { left: 0; }
            .main-content { margin-left: 0; width: 100vw; padding: 1rem 0.75rem; }
            .sidebar.collapsed ~ .main-content { margin-left: 0; width: 100vw; }
        }

        .status-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 6px; vertical-align: middle; }
        .status-dot.online { background: #22c55e; }
        .status-dot.offline { background: #64748b; }

    </style>
</head>
<body>
    <button class="sidebar-toggle d-lg-none" id="sidebarToggle" aria-label="Open sidebar">
        <i class="fas fa-bars"></i>
    </button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <nav class="sidebar d-flex flex-column shadow-lg" id="sidebarNav" aria-label="Main navigation">
        <div class="sidebar-header d-flex align-items-center justify-content-between position-relative" style="width: 100%;">
            <span class="brand-text d-flex align-items-center gap-2">
                <span class="brand-logo"><i class="fas fa-graduation-cap"></i></span>
                <span class="fw-bold">Dunco</span>
            </span>
            <button class="collapse-btn sidebar-collapse-toggle" id="sidebarCollapseBtn" aria-label="Collapse sidebar">
                <i class="fas fa-angle-double-left"></i>
            </button>
        </div>
        <button class="expand-btn sidebar-collapse-toggle d-none" id="sidebarExpandBtn" aria-label="Expand sidebar">
            <i class="fas fa-angle-double-right"></i>
        </button>
        <div class="sidebar-section mt-2">Main</div>
        <div class="sidebar-section-divider"></div>
        <a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif" title="Dashboard">
            <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
            <span class="nav-text">Dashboard</span>
        </a>
        @if(auth()->check() && (\App\Helpers\NavigationHelper::canAccessModule('core') || auth()->user()->hasRole('admin')))
        <div class="sidebar-section" data-module="core">Core Modules</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="sidebarMainAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="coreHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#coreCollapse" aria-expanded="false" aria-controls="coreCollapse">
                        <span class="nav-icon"><i class="fas fa-cubes"></i></span>
                        <span class="nav-text">Core Modules</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="coreCollapse" class="accordion-collapse collapse" aria-labelledby="coreHeading" data-bs-parent="#sidebarMainAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->check() && auth()->user()->hasPermission('schools.view'))
                            <a href="{{ route('core.schools.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('core.schools.*')) active @endif" data-permission="schools.view">
                                <i class="fas fa-school me-2"></i> Schools
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('users.view'))
                            <a href="{{ route('core.users.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('core.users.*')) active @endif" data-permission="users.view">
                                <i class="fas fa-users me-2"></i> Users
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('roles.view'))
                            <a href="{{ route('core.roles.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('core.roles.*')) active @endif" data-permission="roles.view">
                                <i class="fas fa-user-shield me-2"></i> Roles
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('permissions.view'))
                            <a href="{{ route('core.permissions.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('core.permissions.*')) active @endif" data-permission="permissions.view">
                                <i class="fas fa-key me-2"></i> Permissions
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('audit.view'))
                            <a href="{{ route('core.audit_logs.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('core.audit_logs.*')) active @endif" data-permission="audit.view">
                                <i class="fas fa-clipboard-list me-2"></i> Audit Logs
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->check() && auth()->user()->hasPermission('settings.view'))
        <div class="sidebar-section" data-module="settings">Settings</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion-item border-0 bg-transparent">
            <h2 class="accordion-header" id="settingsHeading">
                <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#settingsCollapse" aria-expanded="false" aria-controls="settingsCollapse">
                    <span class="nav-icon"><i class="fas fa-cog"></i></span>
                    <span class="nav-text">Settings</span>
                    <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                </button>
            </h2>
            <div id="settingsCollapse" class="accordion-collapse collapse" aria-labelledby="settingsHeading" data-bs-parent="#sidebarMainAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->check() && auth()->user()->hasPermission('settings.view'))
                            <a href="{{ route('settings.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('settings.*')) active @endif" data-permission="settings.view">
                                <i class="fas fa-cog me-2"></i> Settings
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('settings.view'))
                            <a href="{{ route('settings.global') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('settings.global')) active @endif">
                                <i class="fas fa-sliders-h me-2"></i> Global Settings
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('settings.view'))
                            <a href="{{ route('settings.per_school') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('settings.per_school')) active @endif">
                                <i class="fas fa-school me-2"></i> Per-School Settings
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->check() && (\App\Helpers\NavigationHelper::canAccessModule('hr') || auth()->user()->hasRole('admin')))
        <div class="sidebar-section" data-module="hr">Management</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion-item border-0 bg-transparent">
            <h2 class="accordion-header" id="hrHeading">
                <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#hrCollapse" aria-expanded="false" aria-controls="hrCollapse">
                    <span class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                    <span class="nav-text">HR</span>
                    <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                </button>
            </h2>
            <div id="hrCollapse" class="accordion-collapse collapse" aria-labelledby="hrHeading" data-bs-parent="#sidebarMainAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->check() && auth()->user()->hasPermission('hr.view'))
                            <a href="{{ route('hr.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link" data-permission="hr.view">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('hr.staff.view'))
                            <a href="{{ route('hr.staff.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link" data-permission="hr.staff.view">
                                <i class="fas fa-user-tie me-2"></i> Staff
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('hr.leave.view'))
                            <a href="{{ route('hr.leave.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link">
                                <i class="fas fa-plane-departure me-2"></i> Leave
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('hr.payroll.view'))
                            <a href="{{ route('hr.payroll.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link">
                                <i class="fas fa-money-bill-wave me-2"></i> Payroll
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('hr.contract.view'))
                            <a href="{{ route('hr.contracts.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link">
                                <i class="fas fa-file-contract me-2"></i> Contracts
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('hr.departments.view'))
                            <a href="{{ route('hr.departments.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link">
                                <i class="fas fa-building me-2"></i> Departments
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->check() && (\App\Helpers\NavigationHelper::canAccessModule('academic') || auth()->user()->hasRole('admin')))
        <div class="sidebar-section" data-module="academic">Academic</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="academicSidebarAccordion" data-module="academic">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="academicHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#academicCollapse" aria-expanded="false" aria-controls="academicCollapse">
                        <span class="nav-icon"><i class="fas fa-graduation-cap"></i></span>
                        <span class="nav-text">Academic</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="academicCollapse" class="accordion-collapse collapse" aria-labelledby="academicHeading" data-bs-parent="#academicSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('academic.view'))
                            <a href="/academic" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('academic')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('academic.courses.view'))
                            <a href="/academic/courses" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('academic/courses*')) active @endif">
                                <i class="fas fa-book me-2"></i> Courses
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('academic.subjects.view'))
                            <a href="/academic/subjects" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('academic/subjects*')) active @endif">
                                <i class="fas fa-book-open me-2"></i> Subjects
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('academic.classes.view'))
                            <a href="/academic/classes" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('academic/classes*')) active @endif">
                                <i class="fas fa-users me-2"></i> Classes
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->check() && \App\Helpers\NavigationHelper::canAccessModule('examination') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section" data-module="examination">Examination</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="examinationSidebarAccordion" data-module="examination">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="examinationHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#examinationCollapse" aria-expanded="false" aria-controls="examinationCollapse">
                        <span class="nav-icon"><i class="fas fa-file-alt"></i></span>
                        <span class="nav-text">Examination</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="examinationCollapse" class="accordion-collapse collapse" aria-labelledby="examinationHeading" data-bs-parent="#examinationSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('examination.view'))
                            <a href="{{ route('examination.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.dashboard')) active @endif">
                                <i class="fas fa-chart-line me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('examination.exams.view'))
                            <a href="{{ route('examination.exams.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.exams.*')) active @endif">
                                <i class="fas fa-file-alt me-2"></i> Exams
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('examination.questions.view'))
                            <a href="{{ route('examination.questions.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.questions.*')) active @endif">
                                <i class="fas fa-question-circle me-2"></i> Question Bank
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('examination.categories.view'))
                            <a href="{{ route('examination.categories.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.categories.*')) active @endif">
                                <i class="fas fa-folder-open me-2"></i> Question Categories
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('examination.schedules.view'))
                            <a href="{{ route('examination.schedules.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.schedules.*')) active @endif">
                                <i class="fas fa-calendar-alt me-2"></i> Schedules
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('examination.timetable.view'))
                            <a href="{{ route('examination.schedules.timetable') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.schedules.timetable')) active @endif">
                                <i class="fas fa-clock me-2"></i> Timetable
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('examination.results.view'))
                            <a href="{{ route('examination.results.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.results.*')) active @endif">
                                <i class="fas fa-chart-bar me-2"></i> Results
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('examination.proctoring.view'))
                            <a href="{{ route('examination.proctoring.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.proctoring.*')) active @endif">
                                <i class="fas fa-eye me-2"></i> Proctoring
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('examination.online.view'))
                            <a href="{{ route('examination.online.start', 1) }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.online.*')) active @endif">
                                <i class="fas fa-laptop me-2"></i> Online Exams
                            </a>
                            @endif
                            @role('student')
                            <div class="sidebar-section mt-2">My Exams</div>
                            <a href="{{ route('examination.student.exams') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.student.exams')) active @endif">
                                <i class="fas fa-file-alt me-2"></i> My Exams
                            </a>
                            <a href="{{ route('examination.student.results') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.student.results')) active @endif">
                                <i class="fas fa-chart-bar me-2"></i> My Results
                            </a>
                            <a href="{{ route('examination.student.history') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.student.history')) active @endif">
                                <i class="fas fa-history me-2"></i> Exam History
                            </a>
                            @endrole
                            @role('teacher')
                            <div class="sidebar-section mt-2">Teaching Tools</div>
                            @if(auth()->user()->hasPermission('examination.teacher.exams'))
                            <a href="{{ route('examination.teacher.exams') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.teacher.exams')) active @endif">
                                <i class="fas fa-file-alt me-2"></i> My Exams
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('examination.teacher.grade'))
                            <a href="{{ route('examination.teacher.grade') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.teacher.grade')) active @endif">
                                <i class="fas fa-pen me-2"></i> Grade Exams
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('examination.teacher.analytics'))
                            <a href="{{ route('examination.teacher.analytics') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.teacher.analytics')) active @endif">
                                <i class="fas fa-chart-line me-2"></i> Exam Analytics
                            </a>
                            @endif
                            @endrole
                            @role('admin')
                            <div class="sidebar-section mt-2">Admin Tools</div>
                            <a href="{{ route('examination.admin.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.admin.dashboard')) active @endif">
                                <i class="fas fa-tachometer-alt me-2"></i> Admin Dashboard
                            </a>
                            <a href="{{ route('examination.admin.settings') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.admin.settings')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Settings
                            </a>
                            <a href="{{ route('examination.admin.reports') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.admin.reports')) active @endif">
                                <i class="fas fa-file-alt me-2"></i> Reports
                            </a>
                            <a href="{{ route('examination.admin.backup') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.admin.backup')) active @endif">
                                <i class="fas fa-database me-2"></i> Backup
                            </a>
                            @endrole
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->check() && \App\Helpers\NavigationHelper::canAccessModule('library') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section" data-module="library">Library</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="librarySidebarAccordion" data-module="library">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="libraryHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#libraryCollapse" aria-expanded="false" aria-controls="libraryCollapse">
                        <span class="nav-icon"><i class="fas fa-book"></i></span>
                        <span class="nav-text">Library</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="libraryCollapse" class="accordion-collapse collapse" aria-labelledby="libraryHeading" data-bs-parent="#librarySidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('library.view'))
                            <a href="{{ route('library.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('library.dashboard')) active @endif" data-permission="library.view">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('library.books.view'))
                            <a href="{{ route('library.books.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('library.books.*')) active @endif" data-permission="library.books.view">
                                <i class="fas fa-book me-2"></i> Books
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('library.authors.view'))
                            <a href="{{ route('library.authors.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('library.authors.*')) active @endif" data-permission="library.authors.view">
                                <i class="fas fa-user-edit me-2"></i> Authors
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('library.categories.view'))
                            <a href="{{ route('library.categories.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('library.categories.*')) active @endif" data-permission="library.categories.view">
                                <i class="fas fa-tags me-2"></i> Categories
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('library.publishers.view'))
                            <a href="{{ route('library.publishers.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('library.publishers.*')) active @endif" data-permission="library.publishers.view">
                                <i class="fas fa-building me-2"></i> Publishers
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('library.members.view'))
                            <a href="{{ route('library.members.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('library.members.*')) active @endif" data-permission="library.members.view">
                                <i class="fas fa-users me-2"></i> Members
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('library.borrows.view'))
                            <a href="{{ route('library.borrows.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('library.borrows.*')) active @endif" data-permission="library.borrows.view">
                                <i class="fas fa-exchange-alt me-2"></i> Borrows
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('library.reports.view'))
                            <a href="{{ route('library.reports.borrowed') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('library.reports.*')) active @endif" data-permission="library.reports.view">
                                <i class="fas fa-chart-bar me-2"></i> Reports
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->check() && (\App\Helpers\NavigationHelper::canAccessModule('chatbot') || auth()->user()->hasRole('admin')))
        <div class="sidebar-section" data-module="chatbot">AI Assistant</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="chatbotSidebarAccordion" data-module="chatbot">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="chatbotHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#chatbotCollapse" aria-expanded="false" aria-controls="chatbotCollapse">
                        <span class="nav-icon"><i class="fas fa-robot"></i></span>
                        <span class="nav-text">ChatBot</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="chatbotCollapse" class="accordion-collapse collapse" aria-labelledby="chatbotHeading" data-bs-parent="#chatbotSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->check() && auth()->user()->hasPermission('chatbot.view'))
                            <a href="{{ route('chatbot.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('chatbot.index')) active @endif" data-permission="chatbot.view">
                                <i class="fas fa-comments me-2"></i> Chat Interface
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('chatbot.admin'))
                            <a href="{{ route('chatbot.admin.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('chatbot.admin.dashboard')) active @endif" data-permission="chatbot.admin">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('chatbot.admin'))
                            <a href="{{ route('chatbot.admin.settings') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('chatbot.admin.settings')) active @endif" data-permission="chatbot.admin">
                                <i class="fas fa-cog me-2"></i> Settings
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('chatbot.admin'))
                            <a href="{{ route('chatbot.admin.usage') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('chatbot.admin.usage')) active @endif" data-permission="chatbot.admin">
                                <i class="fas fa-chart-bar me-2"></i> Usage Statistics
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('chatbot.admin'))
                            <a href="{{ route('chatbot.admin.models') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('chatbot.admin.models')) active @endif" data-permission="chatbot.admin">
                                <i class="fas fa-brain me-2"></i> AI Models
                            </a>
                            @endif
                            @if(auth()->check() && auth()->user()->hasPermission('chatbot.admin'))
                            <a href="{{ route('chatbot.admin.health') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('chatbot.admin.health')) active @endif" data-permission="chatbot.admin">
                                <i class="fas fa-heartbeat me-2"></i> System Health
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->check() && (\App\Helpers\NavigationHelper::canAccessModule('finance') || auth()->user()->hasRole('admin')))
        <div class="sidebar-section" data-module="finance">Finance</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="financeSidebarAccordion" data-module="finance">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="financeHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#financeCollapse" aria-expanded="false" aria-controls="financeCollapse">
                        <span class="nav-icon"><i class="fas fa-money-bill-wave"></i></span>
                        <span class="nav-text">Finance</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="financeCollapse" class="accordion-collapse collapse" aria-labelledby="financeHeading" data-bs-parent="#financeSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('finance.view'))
                            <a href="{{ route('finance.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.index')) active @endif" data-permission="finance.view">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('finance.fees.view'))
                            <a href="{{ route('finance.fees.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.fees.*')) active @endif" data-permission="finance.fees.view">
                                <i class="fas fa-coins me-2"></i> Fee Structures
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('finance.billing.view'))
                            <a href="{{ route('finance.billing.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.billing.*')) active @endif" data-permission="finance.billing.view">
                                <i class="fas fa-file-invoice-dollar me-2"></i> Billing & Invoices
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('finance.payments.view'))
                            <a href="{{ route('finance.payments.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.payments.*')) active @endif" data-permission="finance.payments.view">
                                <i class="fas fa-credit-card me-2"></i> Payments
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('finance.receipts.view'))
                            <a href="{{ route('finance.receipts.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.receipts.*')) active @endif" data-permission="finance.receipts.view">
                                <i class="fas fa-receipt me-2"></i> Receipts
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('finance.reports.view'))
                            <a href="{{ route('finance.reports.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.reports.*')) active @endif" data-permission="finance.reports.view">
                                <i class="fas fa-chart-pie me-2"></i> Reports
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('finance.bank-reconciliation.view'))
                            <a href="{{ route('finance.bank-reconciliation.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.bank-reconciliation.*')) active @endif" data-permission="finance.bank-reconciliation.view">
                                <i class="fas fa-random me-2"></i> Bank Reconciliation
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('finance.banks.view'))
                            <a href="{{ route('finance.banks.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.banks.*')) active @endif" data-permission="finance.banks.view">
                                <i class="fas fa-university me-2"></i> Multi-bank
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('finance.ledger.view'))
                            <a href="{{ route('finance.ledger.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.ledger.*')) active @endif" data-permission="finance.ledger.view">
                                <i class="fas fa-book me-2"></i> General Ledger
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('finance.forecasting.view'))
                            <a href="{{ route('finance.forecasting.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.forecasting.*')) active @endif" data-permission="finance.forecasting.view">
                                <i class="fas fa-chart-line me-2"></i> Forecasting
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('finance.taxes.view'))
                            <a href="{{ route('finance.taxes.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.taxes.*')) active @endif" data-permission="finance.taxes.view">
                                <i class="fas fa-percentage me-2"></i> Tax Management
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('finance.settings.view') || auth()->user()->hasRole('admin'))
                            <a href="{{ route('finance.settings.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.settings.*')) active @endif" data-permission="finance.settings.view">
                                <i class="fas fa-cogs me-2"></i> Settings
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->check() && (\App\Helpers\NavigationHelper::canAccessModule('timetable') || auth()->user()->hasRole('admin')))
        <div class="sidebar-section" data-module="timetable">Timetable</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="timetableSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="timetableHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#timetableCollapse" aria-expanded="false" aria-controls="timetableCollapse">
                        <span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>
                        <span class="nav-text">Timetable</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="timetableCollapse" class="accordion-collapse collapse" aria-labelledby="timetableHeading" data-bs-parent="#timetableSidebarAccordion">
                    <div class="accordion-body p-0">
                    <nav class="nav flex-column ms-3">
                        @if(auth()->user()->hasPermission('timetable.view'))
                        <a href="{{ route('timetables.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('timetables/dashboard')) active @endif">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                        @endif
                        @if(auth()->user()->hasPermission('timetable.schedules.view'))
                        <a href="{{ route('class_schedules.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('class_schedules.*')) active @endif">
                                <i class="fas fa-calendar me-2"></i> Class Schedules
                        </a>
                        @endif
                        @if(auth()->user()->hasPermission('timetable.teacher_availabilities.view'))
                        <a href="{{ route('teacher_availabilities.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('teacher_availabilities.*')) active @endif">
                            <i class="fas fa-user-clock me-2"></i> Teacher Availabilities
                        </a>
                        @endif
                        @if(auth()->user()->hasPermission('timetable.rooms.view'))
                        <a href="{{ route('rooms.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('rooms.*')) active @endif">
                            <i class="fas fa-door-open me-2"></i> Rooms
                        </a>
                        @endif
                        @if(auth()->user()->hasPermission('timetable.room_allocations.view'))
                        <a href="{{ route('room_allocations.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('room_allocations.*')) active @endif">
                            <i class="fas fa-th-large me-2"></i> Room Allocations
                        </a>
                        @endif
                            @if(auth()->user()->hasPermission('timetable.settings.view'))
                            <a href="{{ route('timetable.settings') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('timetable.settings')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Settings
                            </a>
                            @endif
                    </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->check() && (\App\Helpers\NavigationHelper::canAccessModule('portal') || auth()->user()->hasRole('admin')))
        <div class="sidebar-section" data-module="portal">Portal</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="portalSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="portalHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#portalCollapse" aria-expanded="false" aria-controls="portalCollapse">
            <span class="nav-icon"><i class="fas fa-user-friends"></i></span>
            <span class="nav-text">Student/Parent Portal</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="portalCollapse" class="accordion-collapse collapse" aria-labelledby="portalHeading" data-bs-parent="#portalSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('portal.view'))
                            <a href="{{ route('portal.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('portal.dashboard')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
        </a>
        @endif
                            @if(auth()->user()->hasPermission('portal.academics.view'))
                            <a href="{{ route('portal.academics') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('portal.academics')) active @endif">
                                <i class="fas fa-graduation-cap me-2"></i> Academics
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('portal.schedule.view'))
                            <a href="{{ route('portal.schedule') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('portal.schedule')) active @endif">
                                <i class="fas fa-calendar-alt me-2"></i> Schedule
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('portal.materials.view'))
                            <a href="{{ route('portal.materials') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('portal.materials')) active @endif">
                                <i class="fas fa-book me-2"></i> Materials
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('portal.assignments.view'))
                            <a href="{{ route('portal.assignments') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('portal.assignments')) active @endif">
                                <i class="fas fa-book-open me-2"></i> Assignments
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('portal.finance.view'))
                            <a href="{{ route('portal.finance') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('portal.finance')) active @endif">
                                <i class="fas fa-money-bill-wave me-2"></i> Finance
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('portal.communication.view'))
                            <a href="{{ route('portal.communication') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('portal.communication')) active @endif">
                                <i class="fas fa-comments me-2"></i> Communication
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->check() && (\App\Helpers\NavigationHelper::canAccessModule('attendance') || auth()->user()->hasRole('admin')))
        <div class="sidebar-section" data-module="attendance">Attendance</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="attendanceSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="attendanceHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#attendanceCollapse" aria-expanded="false" aria-controls="attendanceCollapse">
                        <span class="nav-icon"><i class="fas fa-user-check"></i></span>
                        <span class="nav-text">Attendance</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="attendanceCollapse" class="accordion-collapse collapse" aria-labelledby="attendanceHeading" data-bs-parent="#attendanceSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('attendance.view'))
                            <a href="{{ route('attendance.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('attendance.dashboard')) active @endif">
                                <i class="fas fa-chart-bar me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('attendance.mark.view'))
                            <a href="{{ route('attendance.mark') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('attendance.mark')) active @endif">
                                <i class="fas fa-clipboard-check me-2"></i> Mark Attendance
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('attendance.reports.view'))
                            <a href="{{ route('attendance.reports') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('attendance.reports')) active @endif">
                                <i class="fas fa-file-alt me-2"></i> Reports
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('attendance.settings.view'))
                            <a href="{{ route('attendance.settings') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('attendance.settings')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Settings
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('attendance.biometric_logs.view'))
                            <a href="{{ url('/attendance/biometric-logs') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('attendance/biometric-logs*')) active @endif">
                                <i class="fas fa-fingerprint me-2"></i> Biometric Logs
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('attendance.qr_logs.view'))
                            <a href="{{ url('/attendance/qr-logs') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('attendance/qr-logs*')) active @endif">
                                <i class="fas fa-qrcode me-2"></i> QR Logs
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('attendance.face_logs.view'))
                            <a href="{{ url('/attendance/face-logs') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('attendance/face-logs*')) active @endif">
                                <i class="fas fa-camera me-2"></i> Face Logs
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('attendance.acknowledgment_logs.view'))
                            <a href="{{ url('/attendance/acknowledgment-logs') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('attendance/acknowledgment-logs*')) active @endif">
                                <i class="fas fa-check-double me-2"></i> Acknowledgment Logs
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->check() && (\App\Helpers\NavigationHelper::canAccessModule('communication') || auth()->user()->hasRole('admin')))
        <div class="sidebar-section" data-module="communication">Communication</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="communicationSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="communicationHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#communicationCollapse" aria-expanded="false" aria-controls="communicationCollapse">
                        <span class="nav-icon"><i class="fas fa-comments"></i></span>
                        <span class="nav-text">Communication</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="communicationCollapse" class="accordion-collapse collapse" aria-labelledby="communicationHeading" data-bs-parent="#communicationSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('communication.view'))
                            <a href="{{ route('communication.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('communication.dashboard')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('communication.inbox.view'))
                            <a href="{{ route('communication.inbox') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('communication.inbox.*')) active @endif">
                                <i class="fas fa-inbox me-2"></i> Inbox
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('communication.compose.view'))
                            <a href="{{ route('communication.compose') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('communication.compose.*')) active @endif">
                                <i class="fas fa-edit me-2"></i> Compose
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('communication.announcements.view'))
                            <a href="{{ route('communication.announcements') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('communication.announcements.*')) active @endif">
                                <i class="fas fa-bullhorn me-2"></i> Announcements
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('communication.templates.view'))
                            <a href="{{ route('communication.templates') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('communication.templates.*')) active @endif">
                                <i class="fas fa-file-alt me-2"></i> Templates
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('communication.settings.view'))
                            <a href="{{ route('communication.settings') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('communication.settings.*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Settings
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->check() && (auth()->user()->hasRole('admin') || \App\Helpers\NavigationHelper::canAccessModule('hostel')))
        <div class="sidebar-section" data-module="hostel">Hostel</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="hostelSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="hostelHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#hostelCollapse" aria-expanded="false" aria-controls="hostelCollapse">
                        <span class="nav-icon"><i class="fas fa-hotel"></i></span>
                        <span class="nav-text">Hostel</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="hostelCollapse" class="accordion-collapse collapse" aria-labelledby="hostelHeading" data-bs-parent="#hostelSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('hostel.view'))
                            <a href="{{ route('hostel.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.dashboard')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('hostel.hostels.view'))
                            <a href="{{ route('hostel.hostels.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.hostels.*')) active @endif">
                                <i class="fas fa-building me-2"></i> Hostels
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('hostel.allocations.view'))
                            <a href="{{ route('hostel.room_allocations.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.room_allocations.*')) active @endif">
                                <i class="fas fa-bed me-2"></i> Room Allocations
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('hostel.rooms.view'))
                            <a href="{{ route('hostel.rooms.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.rooms.*')) active @endif">
                                <i class="fas fa-door-open me-2"></i> Rooms
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('hostel.floors.view'))
                            <a href="{{ route('hostel.floors.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.floors.*')) active @endif">
                                <i class="fas fa-building me-2"></i> Floors
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('hostel.beds.view'))
                            <a href="{{ route('hostel.beds.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.beds.*')) active @endif">
                                <i class="fas fa-bed me-2"></i> Beds
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('hostel.fees.view'))
                            <a href="{{ route('hostel.fees.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.fees.*')) active @endif">
                                <i class="fas fa-coins me-2"></i> Hostel Fees
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('hostel.issues.view'))
                            <a href="{{ route('hostel.issues.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.issues.*')) active @endif">
                                <i class="fas fa-exclamation-triangle me-2"></i> Issues
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('hostel.leave.view'))
                            <a href="{{ route('hostel.leave_requests.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.leave_requests.*')) active @endif">
                                <i class="fas fa-plane-departure me-2"></i> Leave Management
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('hostel.visitors.view'))
                            <a href="{{ route('hostel.visitors.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.visitors.*')) active @endif">
                                <i class="fas fa-user-friends me-2"></i> Visitors
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('hostel.announcements.view'))
                            <a href="{{ route('hostel.announcements.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.announcements.*')) active @endif">
                                <i class="fas fa-bullhorn me-2"></i> Announcements
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('hostel.wardens.view'))
                            <a href="{{ route('hostel.wardens.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.wardens.*')) active @endif">
                                <i class="fas fa-user-shield me-2"></i> Wardens
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('hostel.reports.view'))
                            <a href="{{ route('hostel.reports.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.reports.*')) active @endif">
                                <i class="fas fa-chart-bar me-2"></i> Reports
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->check() && (auth()->user()->hasRole('admin') || \App\Helpers\NavigationHelper::canAccessModule('transport')))
        <div class="sidebar-section" data-module="transport">Transport</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="transportSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="transportHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#transportCollapse" aria-expanded="false" aria-controls="transportCollapse">
                        <span class="nav-icon"><i class="fas fa-bus"></i></span>
                        <span class="nav-text">Transport</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="transportCollapse" class="accordion-collapse collapse" aria-labelledby="transportHeading" data-bs-parent="#transportSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('transport.view'))
                            <a href="{{ route('transport.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('transport.index')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('transport.vehicles.view'))
                            <a href="{{ route('transport.vehicles.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('transport.vehicles.*')) active @endif">
                                <i class="fas fa-car me-2"></i> Vehicles
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('transport.routes.view'))
                            <a href="{{ route('transport.routes.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('transport.routes.*')) active @endif">
                                <i class="fas fa-route me-2"></i> Routes
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('transport.drivers.view'))
                            <a href="{{ route('transport.drivers.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('transport.drivers.*')) active @endif">
                                <i class="fas fa-user-tie me-2"></i> Drivers
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('transport.trips.view'))
                            <a href="{{ route('transport.trips.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('transport.trips.*')) active @endif">
                                <i class="fas fa-route me-2"></i> Trips
                            </a>
                            @endif
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('transport.reports.view'))
                            <a href="{{ route('transport.reports') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('transport.reports')) active @endif">
                                <i class="fas fa-chart-bar me-2"></i> Reports
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->check() && (\App\Helpers\NavigationHelper::canAccessModule('document') || auth()->user()->hasRole('admin')))
        <div class="sidebar-section" data-module="document">Document</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="documentSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="documentHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#documentCollapse" aria-expanded="false" aria-controls="documentCollapse">
                        <span class="nav-icon"><i class="fas fa-file-alt"></i></span>
                        <span class="nav-text">Document</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="documentCollapse" class="accordion-collapse collapse" aria-labelledby="documentHeading" data-bs-parent="#documentSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('document.view'))
                            <a href="{{ route('document.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('document.index')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('document.upload.view'))
                            <a href="{{ route('document.upload') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('document.upload.*')) active @endif">
                                <i class="fas fa-upload me-2"></i> Upload
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('document.manage.view'))
                            <a href="{{ route('document.manage') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('document.manage.*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->check() && (\App\Helpers\NavigationHelper::canAccessModule('notification') || auth()->user()->hasRole('admin')))
        <div class="sidebar-section" data-module="notification">Notification</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="notificationSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="notificationHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#notificationCollapse" aria-expanded="false" aria-controls="notificationCollapse">
                        <span class="nav-icon"><i class="fas fa-bell"></i></span>
                        <span class="nav-text">Notification</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="notificationCollapse" class="accordion-collapse collapse" aria-labelledby="notificationHeading" data-bs-parent="#notificationSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('notification.view'))
                            <a href="{{ route('notification.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('notification.index')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('notification.manage.view'))
                            <a href="{{ route('notification.manage') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('notification.manage.*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->check() && (\App\Helpers\NavigationHelper::canAccessModule('api') || auth()->user()->hasRole('admin')))
        <div class="sidebar-section" data-module="api">API</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="apiSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="apiHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#apiCollapse" aria-expanded="false" aria-controls="apiCollapse">
                        <span class="nav-icon"><i class="fas fa-code"></i></span>
                        <span class="nav-text">API</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="apiCollapse" class="accordion-collapse collapse" aria-labelledby="apiHeading" data-bs-parent="#apiSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('api.view'))
                            <a href="{{ route('api.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('api.index')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('api.manage.view'))
                            <a href="{{ route('api.manage') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('api.manage.*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('api.users.view'))
                            <a href="{{ route('api.users') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('api.users')) active @endif">
                                <i class="fas fa-users me-2"></i> Users API
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('api.students.view'))
                            <a href="{{ route('api.students') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('api.students')) active @endif">
                                <i class="fas fa-user-graduate me-2"></i> Students API
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('api.staff.view'))
                            <a href="{{ route('api.staff') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('api.staff')) active @endif">
                                <i class="fas fa-user-tie me-2"></i> Staff API
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('api.classes.view'))
                            <a href="{{ route('api.classes') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('api.classes')) active @endif">
                                <i class="fas fa-chalkboard me-2"></i> Classes API
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('api.stats.view'))
                            <a href="{{ route('api.stats') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('api.stats')) active @endif">
                                <i class="fas fa-chart-bar me-2"></i> Statistics API
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </nav>
    
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <span class="navbar-brand fw-bold d-flex align-items-center gap-2" style="font-size:1.25rem;">
                    <i class="fas fa-graduation-cap me-2"></i> Dunco SMS
                </span>
            </div>
            <form class="d-flex mx-auto" style="max-width: 340px;">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input class="form-control border-start-0" type="search" placeholder="Search..." aria-label="Search">
                </div>
            </form>
            @if(auth()->check() && (\App\Helpers\NavigationHelper::canAccessModule('chatbot') || auth()->user()->hasRole('admin')))
            <div class="d-flex align-items-center me-3">
                <a href="{{ route('chatbot.index') }}" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2" title="AI School Assistant">
                    <i class="fas fa-robot"></i>
                    <span class="d-none d-md-inline">AI Assistant</span>
                </a>
            </div>
            @endif
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                        @if($unread > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unread }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="min-width: 350px; max-width: 400px;">
                        <li class="dropdown-header">Notifications</li>
                        @forelse(auth()->user()->unreadNotifications->take(5) as $note)
                            <li><a class="dropdown-item small" href="#">{{ $note->data['action'] ?? 'Update' }}: {{ $note->data['class'] ?? '' }} ({{ $note->data['day'] ?? '' }} {{ $note->data['start_time'] ?? '' }})</a></li>
                        @empty
                            <li><span class="dropdown-item small text-muted">No new notifications</span></li>
                        @endforelse
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="{{ route('notification.index') }}">View all notifications</a></li>
                    </ul>
                </li>
                @endauth
            </ul>
            <div class="d-flex align-items-center gap-2 ms-auto">
                <button class="btn btn-outline-dark me-2" id="toggleDarkMode" title="Toggle dark mode" style="display:none;">
                    <i class="fas fa-moon"></i>
                </button>
                <div class="dropdown">
                    <a class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=1ea7ff&color=fff&rounded=true&size=32" alt="Avatar" class="rounded-circle" width="32" height="32">
                        <span class="fw-semibold">{{ Auth::user()->name ?? 'Guest' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user-edit me-2"></i>Profile
                        </a></li>
                        <li><button class="dropdown-item" id="themeToggleDropdown" type="button">
                            <i class="fas fa-moon me-2" id="themeIconDropdown"></i> Night Mode
                        </button></li>
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
        </div>
    </nav>
    
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
    
    {{-- Main Content Wrapper --}}
    <div class="main-content">
        @yield('content')
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Manager -->
    <script src="{{ asset('js/sidebar-manager.js') }}"></script>
    
    <script>
        // Force refresh sidebar to clear any caching issues
        function refreshSidebar() {
            // Clear any cached sidebar data
            if (typeof sessionStorage !== 'undefined') {
                sessionStorage.removeItem('sidebar_cache');
            }
            
            // Force reload the page to ensure fresh sidebar
            window.location.reload(true);
        }
        
        // Auto-refresh sidebar every 5 minutes to ensure permissions are up to date
        setInterval(function() {
            // Only refresh if user is logged in
            if (document.querySelector('.sidebar')) {
                refreshSidebar();
            }
        }, 300000); // 5 minutes
        
        // Accordion behavior: Ensure only one accordion is open at a time
        document.addEventListener('DOMContentLoaded', function() {
            const accordionButtons = document.querySelectorAll('.sidebar .accordion-button');
            
            accordionButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('data-bs-target');
                    const targetCollapse = document.querySelector(targetId);
                    
                    // Close all other accordions
                    const allCollapses = document.querySelectorAll('.sidebar .accordion-collapse');
                    allCollapses.forEach(collapse => {
                        if (collapse !== targetCollapse && collapse.classList.contains('show')) {
                            const bsCollapse = new bootstrap.Collapse(collapse, {
                                toggle: false
                            });
                            bsCollapse.hide();
                        }
                    });
                });
            });
        });
        
        // Ensure proper sidebar spacing and prevent content overlap
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const collapseBtn = document.getElementById('sidebarCollapseBtn');
            const expandBtn = document.getElementById('sidebarExpandBtn');
            const sidebarToggle = document.getElementById('sidebarToggle');

            function updateContentSpacing() {
                if (!sidebar || !mainContent) return;
                if (sidebar.classList.contains('collapsed')) {
                    mainContent.style.marginLeft = '68px';
                    mainContent.style.width = 'calc(100vw - 68px)';
                } else {
                    mainContent.style.marginLeft = '200px';
                    mainContent.style.width = 'calc(100vw - 200px)';
                }
            }

            // Sidebar collapse/expand toggle
            if (collapseBtn) {
                collapseBtn.addEventListener('click', function() {
                    sidebar.classList.add('collapsed');
                    collapseBtn.classList.add('d-none');
                    expandBtn.classList.remove('d-none');
                    updateContentSpacing();
                });
            }
            if (expandBtn) {
                expandBtn.addEventListener('click', function() {
                    sidebar.classList.remove('collapsed');
                    expandBtn.classList.add('d-none');
                    collapseBtn.classList.remove('d-none');
                    updateContentSpacing();
                });
            }

            // Mobile sidebar toggle
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    var overlay = document.getElementById('sidebarOverlay');
                    if (overlay) overlay.classList.toggle('show');
                });
            }
            var overlay = document.getElementById('sidebarOverlay');
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            }

            // Initial setup
            updateContentSpacing();

            // Watch for sidebar state changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        updateContentSpacing();
                    }
                });
            });

            if (sidebar) {
                observer.observe(sidebar, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
