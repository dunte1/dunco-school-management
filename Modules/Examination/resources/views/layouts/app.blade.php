<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Examination | Dunco School Management System')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0a2342 60%, #1a237e 100%);
            color: #f5f7fa;
            font-family: 'Inter', 'Poppins', Arial, sans-serif;
            min-height: 100vh;
        }
        .examination-navbar {
            background: rgba(10, 35, 66, 0.98);
            border-bottom: 2px solid #2196f3;
            box-shadow: 0 4px 24px 0 rgba(33,150,243,0.10);
        }
        .examination-navbar .navbar-brand {
            color: #1ea7ff;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .examination-navbar .nav-link {
            color: #f5f7fa;
            font-weight: 500;
            border-radius: 1rem;
            margin-right: 1rem;
            transition: background 0.2s, color 0.2s;
        }
        .examination-navbar .nav-link.active, .examination-navbar .nav-link:hover {
            background: #2196f3;
            color: #fff;
        }
        .examination-content {
            margin-top: 2.5rem;
            margin-bottom: 2rem;
        }
        .glass-card {
            background: rgba(255,255,255,0.10);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(30,167,255,0.10), 0 1.5px 6px 0 rgba(21,101,192,0.10);
            border: 1px solid rgba(255,255,255,0.18);
        }
        .table-premium {
            background: rgba(255,255,255,0.13);
            color: #f5f7fa;
            border-radius: 1rem;
            overflow: hidden;
        }
        .table-premium th, .table-premium td {
            background: rgba(255,255,255,0.13) !important;
            color: #f5f7fa !important;
        }
        .table-premium thead th {
            color: #90caf9 !important;
            font-weight: 700;
            letter-spacing: 1px;
            background: rgba(33, 150, 243, 0.18) !important;
        }
        .table-premium tbody tr {
            border-bottom: 1px solid rgba(33,150,243,0.10);
        }
        .rounded-xl {
            border-radius: 1.5rem !important;
        }
        .form-control, .form-select {
            background: rgba(255,255,255,0.18) !important;
            color: #0a2342 !important;
            border: 1px solid #2196f3 !important;
        }
        .form-control:focus, .form-select:focus {
            background: #fff !important;
            color: #0a2342 !important;
            border: 1.5px solid #1ea7ff !important;
            box-shadow: 0 0 0 2px #2196f355;
        }
        .btn-primary, .btn-outline-primary {
            background: linear-gradient(90deg, #0a2342 60%, #2196f3 100%);
            color: #fff;
            border: none;
        }
        .btn-primary:hover, .btn-outline-primary:hover {
            background: #2196f3;
            color: #fff;
        }
        .btn, .form-control, .form-select {
            border-radius: 1.5rem !important;
        }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar navbar-expand-lg examination-navbar shadow-sm py-2">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <i class="fas fa-file-alt me-2"></i> Examination
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#examinationNavbar" aria-controls="examinationNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="examinationNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('examination.dashboard')) active @endif" href="{{ route('examination.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                </li>
                @if(auth()->check() && auth()->user()->hasRole('student'))
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('examination.student.exams')) active @endif" href="{{ route('examination.student.exams') }}">
                        <i class="fas fa-book-open me-1"></i> My Exams
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('examination.student.history')) active @endif" href="{{ route('examination.student.history') }}">
                        <i class="fas fa-history me-1"></i> My Attempts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('examination.student.results')) active @endif" href="{{ route('examination.student.results') }}">
                        <i class="fas fa-clipboard-check me-1"></i> My Results
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('examination.questions.*')) active @endif" href="{{ route('examination.questions.index') }}">
                        <i class="fas fa-question-circle me-1"></i> Questions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('examination.schedules.*')) active @endif" href="{{ route('examination.schedules.index') }}">
                        <i class="fas fa-calendar-alt me-1"></i> Schedules
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle @if(request()->routeIs('examination.proctoring.*')) active @endif" href="#" id="proctoringDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-eye me-1"></i> Proctoring
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="proctoringDropdown">
                        <li><a class="dropdown-item" href="{{ route('examination.proctoring.index') }}">Proctoring Home</a></li>
                        <li><a class="dropdown-item" href="{{ route('examination.proctoring.dashboard') }}">Proctor Dashboard</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('examination.results.*')) active @endif" href="{{ route('examination.results.index') }}">
                        <i class="fas fa-clipboard-list me-1"></i> Results
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container examination-content">
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html> 