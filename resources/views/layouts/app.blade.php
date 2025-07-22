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
        <div class="sidebar-section">Core Modules</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="coreSidebarAccordion">
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
                            <a href="{{ route('core.schools.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('core.schools.*')) active @endif">
                                <i class="fas fa-school me-2"></i> Schools
                            </a>
                            <a href="{{ route('core.users.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('core.users.*')) active @endif">
                                <i class="fas fa-users me-2"></i> Users
                            </a>
                            <a href="{{ route('core.roles.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('core.roles.*')) active @endif">
                                <i class="fas fa-user-shield me-2"></i> Roles
                            </a>
                            <a href="{{ route('core.permissions.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('core.permissions.*')) active @endif">
                                <i class="fas fa-key me-2"></i> Permissions
                            </a>
                            <a href="{{ route('core.audit_logs.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('core.audit_logs.*')) active @endif">
                                <i class="fas fa-clipboard-list me-2"></i> Audit Logs
                            </a>
                            <a href="{{ route('settings.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('settings.*')) active @endif">
                                <i class="fas fa-cog me-2"></i> Settings
                            </a>
                            <a href="{{ route('settings.global') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('settings.global')) active @endif">
                                <i class="fas fa-sliders-h me-2"></i> Global Settings
                            </a>
                            <a href="{{ route('settings.per_school') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('settings.per_school')) active @endif">
                                <i class="fas fa-school me-2"></i> Per-School Settings
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="sidebar-section">Management</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="hrSidebarAccordion">
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
                            <a href="{{ route('hr.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link">
                                <i class="fas fa-home me-2"></i> Dashboard
                            </a>
                            <a href="{{ route('hr.staff.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link">
                                <i class="fas fa-user-tie me-2"></i> Staff
                            </a>
                            <a href="{{ route('hr.leave.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link">
                                <i class="fas fa-plane-departure me-2"></i> Leave
                            </a>
                            <a href="{{ route('hr.payroll.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link">
                                <i class="fas fa-money-bill-wave me-2"></i> Payroll
                            </a>
                            <a href="{{ route('hr.contract.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link">
                                <i class="fas fa-file-contract me-2"></i> Contracts
                            </a>
                            <a href="{{ route('hr.departments.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link">
                                <i class="fas fa-building me-2"></i> Departments
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="sidebar-section">Academic</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="academicSidebarAccordion">
            <div class="accordion-item border-0 bg-transparent">
                <h2 class="accordion-header" id="academicHeading">
                    <button class="accordion-button collapsed bg-transparent px-2 py-1 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#academicCollapse" aria-expanded="false" aria-controls="academicCollapse">
                        <span class="nav-icon"><i class="fas fa-book"></i></span>
                        <span class="nav-text">Academic</span>
                        <span class="custom-chevron"><i class="fas fa-chevron-down"></i></span>
                    </button>
                </h2>
                <div id="academicCollapse" class="accordion-collapse collapse" aria-labelledby="academicHeading" data-bs-parent="#academicSidebarAccordion">
                    <div class="accordion-body p-0">
                        <nav class="nav flex-column ms-3">
                            <a class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('academic.dashboard')) active @endif" href="{{ route('academic.dashboard') }}">
                                <i class="fas fa-chalkboard me-2"></i> Academic Dashboard
                            </a>
                            <a class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('academic.students.*')) active @endif" href="{{ route('academic.students.index') }}">
                                <i class="fas fa-user-graduate me-2"></i> Students
                            </a>
                            <a class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('academic.classes.*')) active @endif" href="{{ route('academic.classes.index') }}">
                                <i class="fas fa-door-open me-2"></i> Classes
                            </a>
                            <a class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('academic.subjects.*')) active @endif" href="{{ route('academic.subjects.index') }}">
                                <i class="fas fa-book me-2"></i> Subjects
                            </a>
                            <a class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('academic.grading.*')) active @endif" href="{{ route('academic.grading.index') }}">
                                <i class="fas fa-clipboard-check me-2"></i> Grading
                            </a>
                            <a class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('academic.reports.*')) active @endif" href="{{ route('academic.reports.index') }}">
                                <i class="fas fa-chart-bar me-2"></i> Reports
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="sidebar-section">Examination</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="examinationSidebarAccordion">
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
                            <a href="{{ route('examination.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.dashboard')) active @endif">
                                <i class="fas fa-chart-line me-2"></i> Dashboard
                            </a>
                            <a href="{{ route('examination.exams.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.exams.*')) active @endif">
                                <i class="fas fa-file-alt me-2"></i> Exams
                            </a>
                            <a href="{{ route('examination.questions.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.questions.*')) active @endif">
                                <i class="fas fa-question-circle me-2"></i> Question Bank
                            </a>
                            <a href="{{ route('examination.categories.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.categories.*')) active @endif">
                                <i class="fas fa-folder-open me-2"></i> Question Categories
                            </a>
                            <a href="{{ route('examination.schedules.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.schedules.*')) active @endif">
                                <i class="fas fa-calendar-alt me-2"></i> Schedules
                            </a>
                            <a href="{{ route('examination.schedules.timetable') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.schedules.timetable')) active @endif">
                                <i class="fas fa-clock me-2"></i> Timetable
                            </a>
                            <a href="{{ route('examination.results.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.results.*')) active @endif">
                                <i class="fas fa-chart-bar me-2"></i> Results
                            </a>
                            <a href="{{ route('examination.proctoring.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.proctoring.*')) active @endif">
                                <i class="fas fa-eye me-2"></i> Proctoring
                            </a>
                            <a href="{{ route('examination.online.start', 1) }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.online.*')) active @endif">
                                <i class="fas fa-laptop me-2"></i> Online Exams
                            </a>
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
                            <a href="{{ route('examination.teacher.exams') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.teacher.exams')) active @endif">
                                <i class="fas fa-file-alt me-2"></i> My Exams
                            </a>
                            <a href="{{ route('examination.teacher.grade') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.teacher.grade')) active @endif">
                                <i class="fas fa-pen me-2"></i> Grade Exams
                            </a>
                            <a href="{{ route('examination.teacher.analytics') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('examination.teacher.analytics')) active @endif">
                                <i class="fas fa-chart-line me-2"></i> Exam Analytics
                            </a>
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
        <div class="sidebar-section">Finance</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="financeSidebarAccordion">
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
                            <a href="/finance" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('finance')) active @endif">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                            <a href="{{ route('finance.fees.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.fees.*')) active @endif">
                                <i class="fas fa-coins me-2"></i> Fee Structures
                            </a>
                            <a href="{{ route('finance.billing.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.billing.*')) active @endif">
                                <i class="fas fa-file-invoice-dollar me-2"></i> Billing & Invoices
                            </a>
                            <a href="{{ route('finance.payments.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.payments.*')) active @endif">
                                <i class="fas fa-credit-card me-2"></i> Payments
                            </a>
                            <a href="{{ route('finance.receipts.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.receipts.*')) active @endif">
                                <i class="fas fa-receipt me-2"></i> Receipts
                            </a>
                            <a href="{{ route('finance.reports.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.reports.*')) active @endif">
                                <i class="fas fa-chart-pie me-2"></i> Reports
                            </a>
                            <a href="{{ route('finance.settings.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.settings.*')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Settings
                            </a>
                            <a href="{{ route('finance.bank-reconciliation.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.bank-reconciliation.*')) active @endif">
                                <i class="fas fa-random me-2"></i> Bank Reconciliation
                            </a>
                            <a href="{{ route('finance.banks.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.banks.*')) active @endif">
                                <i class="fas fa-university me-2"></i> Multi-bank
                            </a>
                            <a href="{{ route('finance.ledger.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.ledger.*')) active @endif">
                                <i class="fas fa-book me-2"></i> General Ledger
                            </a>
                            <a href="{{ route('finance.online-payments.mpesa', ['invoice' => 1]) }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link">
                                <i class="fas fa-mobile-alt me-2"></i> Online Payments
                            </a>
                            <a href="{{ route('finance.forecasting.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.forecasting.*')) active @endif">
                                <i class="fas fa-chart-pie me-2"></i> Forecasting & Budgeting
                            </a>
                            <a href="{{ route('finance.taxes.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.taxes.*')) active @endif">
                                <i class="fas fa-percentage me-2"></i> Tax Management
                            </a>
                            <a href="{{ route('finance.roles.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('finance.roles.*')) active @endif">
                                <i class="fas fa-user-shield me-2"></i> Roles & Permissions
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="sidebar-section">Library</div>
        <div class="sidebar-section-divider"></div>
        <div class="accordion mb-2" id="librarySidebarAccordion">
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
                            <a href="/library" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('library')) active @endif">
                                <i class="fas fa-book me-2"></i> Library Dashboard
                            </a>
                            <a href="/library/books" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('library/books*')) active @endif">
                                <i class="fas fa-book-open me-2"></i> Books
                            </a>
                            <a href="/library/categories" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('library/categories*')) active @endif">
                                <i class="fas fa-tags me-2"></i> Categories
                            </a>
                            <a href="/library/authors" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('library/authors*')) active @endif">
                                <i class="fas fa-user-edit me-2"></i> Authors
                            </a>
                            <a href="/library/publishers" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('library/publishers*')) active @endif">
                                <i class="fas fa-building me-2"></i> Publishers
                            </a>
                            <a href="/library/members" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('library/members*')) active @endif">
                                <i class="fas fa-users me-2"></i> Members
                            </a>
                            <a href="/library/borrows" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('library/borrows*')) active @endif">
                                <i class="fas fa-exchange-alt me-2"></i> Borrows
                            </a>
                            <a href="/library/reports/borrowed" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('library/reports*')) active @endif">
                                <i class="fas fa-chart-bar me-2"></i> Reports
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
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
                        <a href="{{ route('timetables.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('timetables/dashboard')) active @endif">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('class_schedules.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('class_schedules.*')) active @endif">
                            <i class="icon-calendar"></i> Class Schedules
                        </a>
                        <a href="{{ route('teacher_availabilities.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('teacher_availabilities.*')) active @endif">
                            <i class="fas fa-user-clock me-2"></i> Teacher Availabilities
                        </a>
                        <a href="{{ route('rooms.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('rooms.*')) active @endif">
                            <i class="fas fa-door-open me-2"></i> Rooms
                        </a>
                        <a href="{{ route('room_allocations.index') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('room_allocations.*')) active @endif">
                            <i class="fas fa-th-large me-2"></i> Room Allocations
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        <div class="sidebar-section">Portal</div>
        <div class="sidebar-section-divider"></div>
        <a href="{{ route('portal.dashboard') }}" class="nav-link @if(request()->routeIs('portal.*')) active @endif" title="Student/Parent Portal">
            <span class="nav-icon"><i class="fas fa-user-friends"></i></span>
            <span class="nav-text">Student/Parent Portal</span>
        </a>
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
                            <a href="{{ route('attendance.dashboard') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('attendance.dashboard')) active @endif">
                                <i class="fas fa-chart-bar me-2"></i> Dashboard
                            </a>
                            <a href="{{ route('attendance.mark') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('attendance.mark')) active @endif">
                                <i class="fas fa-clipboard-check me-2"></i> Mark Attendance
                            </a>
                            <a href="{{ route('attendance.reports') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('attendance.reports')) active @endif">
                                <i class="fas fa-file-alt me-2"></i> Reports
                            </a>
                            <a href="{{ route('attendance.settings') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->routeIs('attendance.settings')) active @endif">
                                <i class="fas fa-cogs me-2"></i> Settings
                            </a>
                            <a href="{{ url('/attendance/biometric-logs') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('attendance/biometric-logs*')) active @endif">
                                <i class="fas fa-fingerprint me-2"></i> Biometric Logs
                            </a>
                            <a href="{{ url('/attendance/qr-logs') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('attendance/qr-logs*')) active @endif">
                                <i class="fas fa-qrcode me-2"></i> QR Logs
                            </a>
                            <a href="{{ url('/attendance/face-logs') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('attendance/face-logs*')) active @endif">
                                <i class="fas fa-user-circle me-2"></i> Face Logs
                            </a>
                            <a href="{{ url('/attendance/acknowledgment-logs') }}" class="nav-link d-flex align-items-center mb-1 text-white sidebar-link @if(request()->is('attendance/acknowledgment-logs*')) active @endif">
                                <i class="fas fa-check-double me-2"></i> Parent Acknowledgments
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="sidebar-section">System</div>
        <div class="sidebar-section-divider"></div>
        <a href="{{ route('localization.index') }}" class="nav-link @if(request()->routeIs('localization.*')) active @endif">
            <span class="nav-icon"><i class="fas fa-language"></i></span>
            <span class="nav-text">Localization</span>
        </a>
        <a href="{{ route('notification.index') }}" class="nav-link @if(request()->routeIs('notification.*')) active @endif">
            <span class="status-dot online"></span>
            <span class="nav-icon"><i class="fas fa-bell"></i></span>
            <span class="nav-text">Notifications</span>
            <span class="badge">3</span>
        </a>
        <a href="{{ route('api.index') }}" class="nav-link @if(request()->routeIs('api.*')) active @endif">
            <span class="status-dot online"></span>
            <span class="nav-icon"><i class="fas fa-plug"></i></span>
            <span class="nav-text">API</span>
            <span class="badge">New</span>
        </a>
    </nav>
    
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="d-flex align-items-center gap-2">
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebarNav');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');
            const collapseBtn = document.getElementById('sidebarCollapseBtn');
            const expandBtn = document.getElementById('sidebarExpandBtn');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarToggle2 = document.getElementById('sidebarToggle2');

            function setSidebarCollapsed(collapsed) {
                if (sidebar && collapseBtn && expandBtn) {
                    if (collapsed) {
                        sidebar.classList.add('collapsed');
                        collapseBtn.style.display = 'none';
                        expandBtn.style.display = 'flex';
                    } else {
                        sidebar.classList.remove('collapsed');
                        collapseBtn.style.display = 'flex';
                        expandBtn.style.display = 'none';
                    }
                    localStorage.setItem('sidebarCollapsed', collapsed ? '1' : '0');
                }
            }

            if (collapseBtn) {
                collapseBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    setSidebarCollapsed(true);
                });
            }
            if (expandBtn) {
                expandBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    setSidebarCollapsed(false);
                });
            }

            // Restore sidebar state on page load
            if (window.innerWidth > 991) {
                const wasCollapsed = localStorage.getItem('sidebarCollapsed') === '1';
                setSidebarCollapsed(wasCollapsed);
            }

            // Mobile sidebar toggle
            function toggleMobileSidebar() {
                if (sidebar && sidebarBackdrop) {
                    sidebar.classList.toggle('show');
                    sidebarBackdrop.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
                }
            }
            if (sidebarToggle) sidebarToggle.addEventListener('click', toggleMobileSidebar);
            if (sidebarToggle2) sidebarToggle2.addEventListener('click', toggleMobileSidebar);
            if (sidebarBackdrop) {
                sidebarBackdrop.addEventListener('click', function() {
                    if (sidebar) {
                        sidebar.classList.remove('show');
                        sidebarBackdrop.style.display = 'none';
                    }
                });
            }

            // Responsive behavior
            function handleResize() {
                if (window.innerWidth <= 991) {
                    if (sidebar) {
                        sidebar.classList.remove('collapsed');
                        sidebar.classList.remove('show');
                    }
                    if (sidebarBackdrop) sidebarBackdrop.style.display = 'none';
                } else {
                    const wasCollapsed = localStorage.getItem('sidebarCollapsed') === '1';
                    setSidebarCollapsed(wasCollapsed);
                }
            }
            window.addEventListener('resize', handleResize);

            // Dark mode toggle
            const darkModeToggle = document.getElementById('toggleDarkMode');
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
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
                });
                
                // Restore dark mode state
                if (localStorage.getItem('darkMode') === '1') {
                    darkModeToggle.click();
                }
            }
            
            // Add tooltips for collapsed sidebar
            if (window.innerWidth > 991 && sidebar) {
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

            // Night Mode toggle in user menu
            const themeToggleDropdown = document.getElementById('themeToggleDropdown');
            const themeIconDropdown = document.getElementById('themeIconDropdown');
            if (themeToggleDropdown) {
                themeToggleDropdown.addEventListener('click', function() {
                    document.body.classList.toggle('dark-mode');
                    if(document.body.classList.contains('dark-mode')) {
                        themeIconDropdown.classList.remove('fa-moon');
                        themeIconDropdown.classList.add('fa-sun');
                        themeToggleDropdown.innerHTML = '<i class="fas fa-sun me-2"></i> Daylight Mode';
                    } else {
                        themeIconDropdown.classList.remove('fa-sun');
                        themeIconDropdown.classList.add('fa-moon');
                        themeToggleDropdown.innerHTML = '<i class="fas fa-moon me-2"></i> Night Mode';
                    }
                });
            }
        });
    </script>
    <script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('/service-worker.js');
  });
}
</script>
</body>
</html> 