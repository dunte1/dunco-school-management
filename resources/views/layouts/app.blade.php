<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dunco School Management System')</title>
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
        }
        h1, h2, h3, h4, h5, h6 {
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 0.5em;
        }
        h1 { font-size: 2.2rem; }
        h2 { font-size: 1.6rem; }
        h3 { font-size: 1.3rem; }
        /* Sidebar Modernization */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 180px;
            background: linear-gradient(135deg, #162447 0%, #1a237e 60%, #3949ab 100%);
            color: #fff;
            z-index: 1030;
            transition: width 0.3s, left 0.3s, box-shadow 0.2s;
            overflow-x: hidden;
            box-shadow: 0 8px 32px 0 rgba(30,167,255,0.10), 0 1.5px 6px 0 rgba(21,101,192,0.10);
            padding: 20px 0 20px 0;
            display: flex;
            flex-direction: column;
            border-top-right-radius: 18px;
            border-bottom-right-radius: 18px;
            align-items: center;
            justify-content: flex-start;
        }
        .sidebar.collapsed { width: 64px; }
        .sidebar .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            min-height: 64px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            margin-bottom: 12px;
            background: transparent;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .sidebar .brand-text {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            color: #eaf6fb;
            text-shadow: 0 1px 4px rgba(30,167,255,0.08);
            text-transform: uppercase;
        }
        .sidebar .brand-logo {
            background: #1ea7ff22;
            border-radius: 8px;
            padding: 6px 8px;
            margin-right: 8px;
            font-size: 1.3rem;
            color: #1ea7ff;
            box-shadow: 0 2px 8px rgba(30,167,255,0.10);
        }
        .sidebar .collapse-btn {
            background: none;
            border: none;
            color: var(--color-text-muted);
            font-size: 1.3rem;
            cursor: pointer;
            padding: 0.35rem 0.5rem;
            border-radius: 6px;
            transition: color 0.2s, background 0.2s, box-shadow 0.2s;
            box-shadow: none;
            display: block !important;
        }
        .sidebar.collapsed .collapse-btn {
            display: block !important;
        }
        .sidebar-section {
            padding: 0 10px 0 10px;
            font-size: 12px;
            color: #1ea7ff;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            font-weight: 800;
            opacity: 1;
            margin-top: 32px;
            margin-bottom: 10px;
            width: 100%;
            text-align: center;
            position: relative;
            text-shadow: 0 1px 8px rgba(30,167,255,0.18);
        }
        .sidebar-section-divider {
            width: 80%;
            height: 2.5px;
            background: linear-gradient(90deg, #1ea7ff 0%, #ffb300 100%);
            border-radius: 2px;
            margin: 0.5rem auto 1rem auto;
            box-shadow: 0 2px 8px rgba(30,167,255,0.13);
        }
        .sidebar .nav-link.active, .sidebar .accordion-button.active {
            background: rgba(30,167,255,0.18);
            color: #fff;
            border-left: 4px solid #ffb300;
            box-shadow: 0 4px 16px rgba(30,167,255,0.18);
            font-weight: 700;
            transform: translateX(2px) scale(1.04);
        }
        .sidebar .nav-link .badge, .sidebar .accordion-button .badge {
            background: linear-gradient(90deg, #ffb300 0%, #1ea7ff 100%);
            color: #0d133d;
            font-size: 0.78rem;
            font-weight: 700;
            border-radius: 8px;
            padding: 2px 8px;
            margin-left: 8px;
            box-shadow: 0 2px 8px rgba(30,167,255,0.13);
        }
        .sidebar.collapsed .sidebar-section {
            opacity: 0;
            height: 0;
            padding: 0;
            margin: 0;
        }
        .sidebar .nav-link, .sidebar .accordion-button {
            color: #eaf6fb;
            font-size: 1.05rem;
            font-weight: 500;
            padding: 14px 0;
            border-radius: 6px;
            margin-bottom: 2px;
            transition: all 0.2s cubic-bezier(.4,0,.2,1);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            text-decoration: none;
            gap: 6px;
            line-height: 1.5;
            position: relative;
            outline: none;
            width: 100%;
        }
        .sidebar .nav-link:focus {
            box-shadow: 0 0 0 2px #1ea7ff55;
            background: rgba(30,167,255,0.10);
        }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.07);
            color: #fff;
            border-left: 3px solid #1ea7ff;
            box-shadow: 0 2px 8px rgba(30,167,255,0.10);
            transform: translateX(2px) scale(1.01);
        }
        .sidebar .nav-link.active {
            background: rgba(30,167,255,0.13);
            color: #fff;
            border-left: 3px solid #1ea7ff;
            box-shadow: 0 4px 16px rgba(30,167,255,0.13);
            font-weight: 600;
            transform: translateX(2px) scale(1.02);
        }
        .sidebar.collapsed .nav-link {
            padding: 12px 10px;
            font-size: 1.2rem;
            justify-content: center;
            border-radius: 8px;
            margin: 0 6px 2px 6px;
        }
        .sidebar.collapsed .nav-link .nav-text {
            opacity: 0;
            width: 0;
            display: none;
        }
        .sidebar .nav-link .nav-icon, .sidebar .accordion-button .nav-icon {
            width: 28px;
            min-width: 28px;
            text-align: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            transition: color 0.2s, transform 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        .sidebar .nav-link:hover .nav-icon, .sidebar .nav-link.active .nav-icon {
            color: #1ea7ff;
            transform: scale(1.13) rotate(-6deg);
        }
        .sidebar .nav-link .nav-text, .sidebar .accordion-button .nav-text, .sidebar .accordion-body .nav-link .nav-text {
            transition: opacity 0.2s, width 0.2s;
            overflow: hidden;
            font-size: 1.05rem;
            font-weight: 500;
            text-align: center;
            width: 100%;
        }
        .sidebar.collapsed .nav-link .nav-text,
        .sidebar.collapsed .accordion-button .nav-text,
        .sidebar.collapsed .accordion-body .nav-link .nav-text,
        .sidebar.collapsed .sidebar-section {
            opacity: 0;
            width: 0;
            display: none !important;
        }
        .sidebar .nav-link, .sidebar .accordion-button, .sidebar .accordion-body .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        .sidebar .accordion-body, .sidebar .nav.flex-column {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .sidebar .accordion-body .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }
        .sidebar-collapse-toggle, .expand-btn {
            background: #fff;
            color: #1ea7ff;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(30,167,255,0.13);
            transition: background 0.2s, color 0.2s, box-shadow 0.2s, left 0.3s, top 0.3s, transform 0.3s;
            cursor: pointer;
            margin: 0;
        }
        .sidebar-collapse-toggle:hover, .expand-btn:hover, .sidebar-collapse-toggle:focus, .expand-btn:focus {
            background: #1ea7ff;
            color: #fff;
            box-shadow: 0 4px 16px rgba(30,167,255,0.18);
            outline: none;
        }
        .sidebar.collapsed .sidebar-header .sidebar-collapse-toggle {
            display: none !important;
        }
        .sidebar.collapsed #sidebarExpandBtn {
            display: flex !important;
        }
        #sidebarExpandBtn {
            display: none;
        }
        .main-content {
            margin-left: 180px;
            padding: 2rem 1rem 1rem 1rem;
            transition: margin-left 0.3s;
            min-height: 100vh;
        }
        .sidebar.collapsed ~ .main-content {
            margin-left: 64px;
        }
        @media (max-width: 991.98px) {
            .sidebar { left: -248px; width: 248px; }
            .sidebar.show { left: 0; }
            .main-content { margin-left: 0; }
            .sidebar.collapsed ~ .main-content { margin-left: 0; }
            .sidebar-section-divider { width: 95%; }
        }
        /* Remove default Bootstrap accordion arrow */
        .sidebar .accordion-button::after {
            display: none !important;
        }
        /* Style custom chevron icon */
        .sidebar .accordion-button .custom-chevron {
            color: #1ea7ff;
            font-size: 1.3rem;
            margin-left: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s;
        }
        .sidebar .accordion-button:not(.collapsed) .custom-chevron {
            transform: rotate(180deg);
        }
        .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
            box-shadow: 0 0 6px rgba(30,167,255,0.18);
            vertical-align: middle;
        }
        .status-dot.online {
            background: #22c55e;
            box-shadow: 0 0 8px #22c55e88;
        }
        .status-dot.offline {
            background: #b0bec5;
            box-shadow: 0 0 6px #b0bec588;
        }
    </style>
</head>
<body>
    <button class="sidebar-toggle d-lg-none" id="sidebarToggle" aria-label="Open sidebar">
        <i class="fas fa-bars"></i>
    </button>
    
    <nav class="sidebar d-flex flex-column shadow-lg" id="sidebarNav" aria-label="Main navigation" style="background: linear-gradient(135deg, #1a237e 0%, #3949ab 100%);">
        <div class="sidebar-header d-flex align-items-center justify-content-between position-relative" style="width: 100%;">
            <span class="brand-text d-flex align-items-center gap-2">
                <span class="brand-logo"><i class="fas fa-graduation-cap"></i></span>
                <span class="fw-bold">Dunco SMS</span>
            </span>
            <button class="collapse-btn sidebar-collapse-toggle" id="sidebarCollapseBtn" aria-label="Collapse sidebar" style="margin-left: 8px;">
                <i class="fas fa-angle-double-left"></i>
            </button>
        </div>
        <button class="expand-btn sidebar-collapse-toggle d-none" id="sidebarExpandBtn" aria-label="Expand sidebar" style="position: absolute; left: 50%; top: 12px; transform: translateX(-50%); z-index: 1100;">
            <i class="fas fa-angle-double-right"></i>
        </button>
        <div class="sidebar-section mt-2">Main</div>
        <div class="sidebar-section-divider"></div>
        <a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif" title="Dashboard">
            <span class="nav-icon"><i class="fas fa-tachometer-alt"></i></span>
            <span class="nav-text">Dashboard</span>
        </a>
        @if(\App\Helpers\NavigationHelper::canAccessModule('core') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section" data-module="core">Core Modules</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="coreSidebarAccordion" data-module="core">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="coreHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#coreCollapse" aria-expanded="false" aria-controls="coreCollapse">
                        <span class="nav-icon"><i class="fas fa-cubes"></i></span>
                        <span class="nav-text">Core Modules</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="coreCollapse" class="accordion-collapse collapse" aria-labelledby="coreHeading" data-bs-parent="#coreSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('schools.view'))
                            <a href="{{ route('core.schools.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('core.schools.*')) active @endif" data-permission="schools.view">
                                <i class="fas fa-school me-2"></i> Schools
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('users.view'))
                            <a href="{{ route('core.users.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('core.users.*')) active @endif" data-permission="users.view">
                                <i class="fas fa-users me-2"></i> Users
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('roles.view'))
                            <a href="{{ route('core.roles.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('core.roles.*')) active @endif" data-permission="roles.view">
                                <i class="fas fa-user-shield me-2"></i> Roles
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('permissions.view'))
                            <a href="{{ route('core.permissions.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('core.permissions.*')) active @endif" data-permission="permissions.view">
                                <i class="fas fa-key me-2"></i> Permissions
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('audit.view'))
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
        @if(auth()->user()->hasPermission('settings.view'))
        <div class="sidebar-section" data-module="settings">Settings</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="settingsSidebarAccordion" data-module="settings">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="settingsHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#settingsCollapse" aria-expanded="false" aria-controls="settingsCollapse">
                        <span class="nav-icon"><i class="fas fa-cog"></i></span>
                        <span class="nav-text">Settings</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="settingsCollapse" class="accordion-collapse collapse" aria-labelledby="settingsHeading" data-bs-parent="#settingsSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('settings.view'))
                            <a href="{{ route('settings.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('settings.*')) active @endif" data-permission="settings.view">
                                <i class="fas fa-cog me-2"></i> Settings
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('settings.view'))
                            <a href="{{ route('settings.global') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('settings.global')) active @endif">
                                <i class="fas fa-sliders-h me-2"></i> Global Settings
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('settings.view'))
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
        @if(\App\Helpers\NavigationHelper::canAccessModule('hr') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section" data-module="hr">Management</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="hrSidebarAccordion" data-module="hr">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="hrHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#hrCollapse" aria-expanded="false" aria-controls="hrCollapse">
                        <span class="nav-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                        <span class="nav-text">HR</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="hrCollapse" class="accordion-collapse collapse" aria-labelledby="hrHeading" data-bs-parent="#hrSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('hr.view'))
                            <a href="{{ route('hr.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link" data-permission="hr.view">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hr.staff.view'))
                            <a href="{{ route('hr.staff.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link" data-permission="hr.staff.view">
                                <i class="fas fa-user-tie me-2"></i> Staff
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hr.leave.view'))
                            <a href="{{ route('hr.leave.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link">
                                <i class="fas fa-plane-departure me-2"></i> Leave
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hr.payroll.view'))
                            <a href="{{ route('hr.payroll.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link">
                                <i class="fas fa-money-bill-wave me-2"></i> Payroll
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hr.contract.view'))
                            <a href="{{ route('hr.contract.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link">
                                <i class="fas fa-file-contract me-2"></i> Contracts
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hr.departments.view'))
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
        @if(\App\Helpers\NavigationHelper::canAccessModule('academic') || auth()->user()->hasRole('admin'))
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
        @if(\App\Helpers\NavigationHelper::canAccessModule('examination') || auth()->user()->hasRole('admin'))
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

        @if(\App\Helpers\NavigationHelper::canAccessModule('library') || auth()->user()->hasRole('admin'))
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

        @if(\App\Helpers\NavigationHelper::canAccessModule('finance') || auth()->user()->hasRole('admin'))
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
                            <a href="{{ route('finance.fees.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.fees.*')) active @endif" data-permission="finance.view">
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

        @if(\App\Helpers\NavigationHelper::canAccessModule('timetable') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section">Timetable</div>
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
                    <nav class="nav flex-column ms-3">
                        @if(auth()->user()->hasPermission('timetable.view'))
                        <a href="{{ route('timetables.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('timetables/dashboard')) active @endif">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                        @endif
                        @if(auth()->user()->hasPermission('timetable.schedules.view'))
                        <a href="{{ route('class_schedules.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('class_schedules.*')) active @endif">
                            <i class="icon-calendar"></i> Class Schedules
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
                    </nav>
                </div>
            </div>
        </div>
        @endif
        @if(\App\Helpers\NavigationHelper::canAccessModule('portal') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section">Portal</div>
        <div class="sidebar-section-divider"></div>
        <a href="{{ route('portal.dashboard') }}" class="nav-link @if(request()->routeIs('portal.*')) active @endif" title="Student/Parent Portal">
            <span class="nav-icon"><i class="fas fa-user-friends"></i></span>
            <span class="nav-text">Student/Parent Portal</span>
        </a>
        @endif
        @if(\App\Helpers\NavigationHelper::canAccessModule('attendance') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section">Attendance</div>
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
        @if(\App\Helpers\NavigationHelper::canAccessModule('communication') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section">Communication</div>
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
                            <a href="{{ route('communication.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('communication.dashboard')) active @endif">
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
        @if(\App\Helpers\NavigationHelper::canAccessModule('hostel') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section">Hostel</div>
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
                            @if(auth()->user()->hasPermission('hostel.view'))
                            <a href="{{ route('hostel.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.dashboard')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hostel.allocations.view'))
                            <a href="{{ route('hostel.room_allocations.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.room_allocations.*')) active @endif">
                                <i class="fas fa-bed me-2"></i> Room Allocations
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hostel.rooms.view'))
                            <a href="{{ route('hostel.rooms.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.rooms.*')) active @endif">
                                <i class="fas fa-door-open me-2"></i> Rooms
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hostel.floors.view'))
                            <a href="{{ route('hostel.floors.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.floors.*')) active @endif">
                                <i class="fas fa-building me-2"></i> Floors
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hostel.beds.view'))
                            <a href="{{ route('hostel.beds.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.beds.*')) active @endif">
                                <i class="fas fa-bed me-2"></i> Beds
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hostel.fees.view'))
                            <a href="{{ route('hostel.fees.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.fees.*')) active @endif">
                                <i class="fas fa-coins me-2"></i> Hostel Fees
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hostel.issues.view'))
                            <a href="{{ route('hostel.issues.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.issues.*')) active @endif">
                                <i class="fas fa-exclamation-triangle me-2"></i> Issues
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hostel.leave.view'))
                            <a href="{{ route('hostel.leave_requests.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.leave_requests.*')) active @endif">
                                <i class="fas fa-plane-departure me-2"></i> Leave Management
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hostel.visitors.view'))
                            <a href="{{ route('hostel.visitors.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.visitors.*')) active @endif">
                                <i class="fas fa-user-friends me-2"></i> Visitors
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hostel.announcements.view'))
                            <a href="{{ route('hostel.announcements.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.announcements.*')) active @endif">
                                <i class="fas fa-bullhorn me-2"></i> Announcements
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hostel.wardens.view'))
                            <a href="{{ route('hostel.wardens.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('hostel.wardens.*')) active @endif">
                                <i class="fas fa-user-shield me-2"></i> Wardens
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('hostel.reports.view'))
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
        @if(\App\Helpers\NavigationHelper::canAccessModule('transport') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section">Transport</div>
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
                            @if(auth()->user()->hasPermission('transport.view'))
                            <a href="/transport" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('transport')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('transport.vehicles.view'))
                            <a href="/transport/vehicles" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('transport/vehicles*')) active @endif">
                                <i class="fas fa-car me-2"></i> Vehicles
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('transport.routes.view'))
                            <a href="/transport/routes" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('transport/routes*')) active @endif">
                                <i class="fas fa-route me-2"></i> Routes
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('transport.drivers.view'))
                            <a href="/transport/drivers" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('transport/drivers*')) active @endif">
                                <i class="fas fa-user-tie me-2"></i> Drivers
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('transport.trips.view'))
                            <a href="/transport/trips" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('transport/trips*')) active @endif">
                                <i class="fas fa-route me-2"></i> Trips
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('transport.reports.view'))
                            <a href="/transport/reports" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('transport/reports*')) active @endif">
                                <i class="fas fa-chart-bar me-2"></i> Reports
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        {{-- Governance & Compliance Section (Merged Audit + Compliance) --}}
        @if(\App\Helpers\NavigationHelper::canAccessModule('compliance') || \App\Helpers\NavigationHelper::canAccessModule('audit') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section">Governance & Compliance</div>
        <div class="sidebar-section-divider"></div>
        
        {{-- Compliance Module (Standards & Policies) --}}
        <div class="accordion mb-2" id="complianceSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="complianceHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#complianceCollapse" aria-expanded="false" aria-controls="complianceCollapse">
                        <span class="nav-icon"><i class="fas fa-shield-alt"></i></span>
                        <span class="nav-text">Compliance</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="complianceCollapse" class="accordion-collapse collapse" aria-labelledby="complianceHeading" data-bs-parent="#complianceSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('compliance.view'))
                            <a href="/compliance" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('compliance')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('compliance.manage.view'))
                            <a href="/compliance/manage" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('compliance/manage*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Audit Module (Monitoring & Logs) --}}
        <div class="accordion mb-2" id="auditSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="auditHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#auditCollapse" aria-expanded="false" aria-controls="auditCollapse">
                        <span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>
                        <span class="nav-text">Audit</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="auditCollapse" class="accordion-collapse collapse" aria-labelledby="auditHeading" data-bs-parent="#auditSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('audit.view'))
                            <a href="{{ route('audit.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('audit.index')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('audit.manage.view'))
                            <a href="{{ route('audit.manage.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('audit.manage.*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(\App\Helpers\NavigationHelper::canAccessModule('lms') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section">LMS</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="lmsSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="lmsHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#lmsCollapse" aria-expanded="false" aria-controls="lmsCollapse">
                        <span class="nav-icon"><i class="fas fa-graduation-cap"></i></span>
                        <span class="nav-text">LMS</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="lmsCollapse" class="accordion-collapse collapse" aria-labelledby="lmsHeading" data-bs-parent="#lmsSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('lms.view'))
                            <a href="/lms" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('lms')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('lms.courses.view'))
                            <a href="/lms/courses" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('lms/courses*')) active @endif">
                                <i class="fas fa-book me-2"></i> Courses
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('lms.assignments.view'))
                            <a href="/lms/assignments" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('lms/assignments*')) active @endif">
                                <i class="fas fa-tasks me-2"></i> Assignments
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('lms.grades.view'))
                            <a href="/lms/grades" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('lms/grades*')) active @endif">
                                <i class="fas fa-star me-2"></i> Grades
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(\App\Helpers\NavigationHelper::canAccessModule('document') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section">Document</div>
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
                            <a href="/document" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('document')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('document.upload.view'))
                            <a href="/document/upload" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('document/upload*')) active @endif">
                                <i class="fas fa-upload me-2"></i> Upload
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->user()->hasPermission('notification.view'))
        <div class="sidebar-section">Notification</div>
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
                            <a href="/notification" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('notification*')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('notification.manage.view'))
                            <a href="/notification/manage" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('notification/manage*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->user()->hasPermission('analytics.view'))
        <div class="sidebar-section">Analytics</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="analyticsSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="analyticsHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#analyticsCollapse" aria-expanded="false" aria-controls="analyticsCollapse">
                        <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
                        <span class="nav-text">Analytics</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="analyticsCollapse" class="accordion-collapse collapse" aria-labelledby="analyticsHeading" data-bs-parent="#analyticsSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('analytics.view'))
                            <a href="/analytics" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('analytics')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('analytics.reports.view'))
                            <a href="/analytics/reports" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('analytics/reports*')) active @endif">
                                <i class="fas fa-chart-bar me-2"></i> Reports
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->user()->hasPermission('api.view'))
        <div class="sidebar-section">API</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="apiSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="apiHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#apiCollapse" aria-expanded="false" aria-controls="apiCollapse">
                        <span class="nav-icon"><i class="fas fa-home"></i></span>
                        <span class="nav-text">API</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="apiCollapse" class="accordion-collapse collapse" aria-labelledby="apiHeading" data-bs-parent="#apiSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('api.view'))
                            <a href="/api" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('api')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('api.manage.view'))
                            <a href="/api/manage" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('api/manage*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->user()->hasPermission('chatbot.view'))
        <div class="sidebar-section">Chatbot</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="chatbotSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="chatbotHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#chatbotCollapse" aria-expanded="false" aria-controls="chatbotCollapse">
                        <span class="nav-icon"><i class="fas fa-home"></i></span>
                        <span class="nav-text">Chatbot</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="chatbotCollapse" class="accordion-collapse collapse" aria-labelledby="chatbotHeading" data-bs-parent="#chatbotSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('chatbot.view'))
                            <a href="/chatbot" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('chatbot')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('chatbot.manage.view'))
                            <a href="/chatbot/manage" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('chatbot/manage*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->user()->hasPermission('marketplace.view'))
        <div class="sidebar-section">Marketplace</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="marketplaceSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="marketplaceHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#marketplaceCollapse" aria-expanded="false" aria-controls="marketplaceCollapse">
                        <span class="nav-icon"><i class="fas fa-home"></i></span>
                        <span class="nav-text">Marketplace</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="marketplaceCollapse" class="accordion-collapse collapse" aria-labelledby="marketplaceHeading" data-bs-parent="#marketplaceSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('marketplace.view'))
                            <a href="/marketplace" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('marketplace')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('marketplace.manage.view'))
                            <a href="/marketplace/manage" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('marketplace/manage*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->user()->hasPermission('pwa.view'))
        <div class="sidebar-section">PWA</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="pwaSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="pwaHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#pwaCollapse" aria-expanded="false" aria-controls="pwaCollapse">
                        <span class="nav-icon"><i class="fas fa-home"></i></span>
                        <span class="nav-text">PWA</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="pwaCollapse" class="accordion-collapse collapse" aria-labelledby="pwaHeading" data-bs-parent="#pwaSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('pwa.view'))
                            <a href="/pwa" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('pwa*')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('pwa.manage.view'))
                            <a href="/pwa/manage" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('pwa/manage*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(auth()->user()->hasPermission('research.view'))
        <div class="sidebar-section">Research</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="researchSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="researchHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#researchCollapse" aria-expanded="false" aria-controls="researchCollapse">
                        <span class="nav-icon"><i class="fas fa-home"></i></span>
                        <span class="nav-text">Research</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="researchCollapse" class="accordion-collapse collapse" aria-labelledby="researchHeading" data-bs-parent="#researchSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('research.view'))
                            <a href="/research" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('research')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('research.manage.view'))
                            <a href="/research/manage" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('research/manage*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        {{-- Student Services Section (Merged Welfare + Cafeteria) --}}
        @if(\App\Helpers\NavigationHelper::canAccessModule('welfare') || \App\Helpers\NavigationHelper::canAccessModule('cafeteria') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section">Student Services</div>
        <div class="sidebar-section-divider"></div>
        
        {{-- Welfare Module (Student Support) --}}
        <div class="accordion mb-2" id="welfareSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="welfareHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#welfareCollapse" aria-expanded="false" aria-controls="welfareCollapse">
                        <span class="nav-icon"><i class="fas fa-heart"></i></span>
                        <span class="nav-text">Welfare</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="welfareCollapse" class="accordion-collapse collapse" aria-labelledby="welfareHeading" data-bs-parent="#welfareSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('welfare.view'))
                            <a href="/welfare" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('welfare')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('welfare.manage'))
                            <a href="/welfare/manage" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('welfare/manage*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Cafeteria Module (Food Services) --}}
        <div class="accordion mb-2" id="cafeteriaSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="cafeteriaHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#cafeteriaCollapse" aria-expanded="false" aria-controls="cafeteriaCollapse">
                        <span class="nav-icon"><i class="fas fa-utensils"></i></span>
                        <span class="nav-text">Cafeteria</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="cafeteriaCollapse" class="accordion-collapse collapse" aria-labelledby="cafeteriaHeading" data-bs-parent="#cafeteriaSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('cafeteria.view'))
                            <a href="/cafeteria" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('cafeteria')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('cafeteria.manage'))
                            <a href="/cafeteria/manage" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('cafeteria/manage*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        {{-- Resource Management Section (Merged Inventory + Assets) --}}
        @if(\App\Helpers\NavigationHelper::canAccessModule('inventory') || \App\Helpers\NavigationHelper::canAccessModule('assets') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section">Resource Management</div>
        <div class="sidebar-section-divider"></div>
        
        {{-- Inventory Module (Items & Supplies) --}}
        <div class="accordion mb-2" id="inventorySidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="inventoryHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#inventoryCollapse" aria-expanded="false" aria-controls="inventoryCollapse">
                        <span class="nav-icon"><i class="fas fa-boxes"></i></span>
                        <span class="nav-text">Inventory</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="inventoryCollapse" class="accordion-collapse collapse" aria-labelledby="inventoryHeading" data-bs-parent="#inventorySidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('inventory.view'))
                            <a href="/inventory" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('inventory')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('inventory.manage'))
                            <a href="/inventory/manage" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('inventory/manage*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Assets Module (Equipment & Facilities) --}}
        <div class="accordion mb-2" id="assetsSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="assetsHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#assetsCollapse" aria-expanded="false" aria-controls="assetsCollapse">
                        <span class="nav-icon"><i class="fas fa-building"></i></span>
                        <span class="nav-text">Assets</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="assetsCollapse" class="accordion-collapse collapse" aria-labelledby="assetsHeading" data-bs-parent="#assetsSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('assets.view'))
                            <a href="/assets" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('assets')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('assets.manage'))
                            <a href="/assets/manage" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('assets/manage*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        {{-- Alumni & External Relations Section --}}
        @if(\App\Helpers\NavigationHelper::canAccessModule('alumni') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section">External Relations</div>
        <div class="sidebar-section-divider"></div>
        
        {{-- Alumni Module --}}
        <div class="accordion mb-2" id="alumniSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="alumniHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#alumniCollapse" aria-expanded="false" aria-controls="alumniCollapse">
                        <span class="nav-icon"><i class="fas fa-graduation-cap"></i></span>
                        <span class="nav-text">Alumni</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="alumniCollapse" class="accordion-collapse collapse" aria-labelledby="alumniHeading" data-bs-parent="#alumniSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('alumni.view'))
                            <a href="/alumni" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('alumni')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('alumni.manage'))
                            <a href="/alumni/manage" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('alumni/manage*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
                            </a>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        {{-- System Configuration Section --}}
        @if(\App\Helpers\NavigationHelper::canAccessModule('localization') || auth()->user()->hasRole('admin'))
        <div class="sidebar-section">System Configuration</div>
        <div class="sidebar-section-divider"></div>
        
        {{-- Localization Module --}}
        <div class="accordion mb-2" id="localizationSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="localizationHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#localizationCollapse" aria-expanded="false" aria-controls="localizationCollapse">
                        <span class="nav-icon"><i class="fas fa-globe"></i></span>
                        <span class="nav-text">Localization</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="localizationCollapse" class="accordion-collapse collapse" aria-labelledby="localizationHeading" data-bs-parent="#localizationSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            @if(auth()->user()->hasPermission('localization.view'))
                            <a href="/localization" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('localization')) active @endif">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            @endif
                            @if(auth()->user()->hasPermission('localization.manage'))
                            <a href="/localization/manage" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('localization/manage*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Manage
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
    </script>
    
    @stack('scripts')
</body>
</html>
