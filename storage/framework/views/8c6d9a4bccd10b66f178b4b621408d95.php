<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Examination | Dunco School Management System'); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --light-bg: #f8fafc;
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
            --border-radius: 0.75rem;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--gray-800);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            line-height: 1.6;
        }

        .examination-navbar {
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(10px);
        }

        .examination-navbar .navbar-brand {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.025em;
        }

        .examination-navbar .nav-link {
            color: var(--gray-600);
            font-weight: 500;
            border-radius: var(--border-radius);
            margin: 0 0.25rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
            position: relative;
        }

        .examination-navbar .nav-link:hover {
            color: var(--primary-color);
            background: var(--gray-50);
        }

        .examination-navbar .nav-link.active {
            color: var(--primary-color);
            background: var(--primary-color);
            color: var(--white);
        }

        .examination-content {
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .glass-card {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-200);
            backdrop-filter: blur(10px);
        }

        .table-premium {
            background: var(--white);
            color: var(--gray-800);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .table-premium th {
            background: var(--gray-50);
            color: var(--gray-700);
            font-weight: 600;
            border-bottom: 2px solid var(--gray-200);
            padding: 1rem;
        }

        .table-premium td {
            color: var(--gray-700);
            padding: 1rem;
            border-bottom: 1px solid var(--gray-100);
            vertical-align: middle;
        }

        .table-premium tbody tr:hover {
            background: var(--gray-50);
        }

        .form-control, .form-select {
            background: var(--white);
            color: var(--gray-800);
            border: 1px solid var(--gray-300);
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            background: var(--white);
            color: var(--gray-800);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgb(37 99 235 / 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--gray-400);
        }

        .btn {
            border-radius: var(--border-radius);
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline-primary {
            background: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: var(--white);
        }

        .btn-info {
            background: var(--info-color);
            color: var(--white);
        }

        .btn-warning {
            background: var(--warning-color);
            color: var(--white);
        }

        .btn-danger {
            background: var(--danger-color);
            color: var(--white);
        }

        .btn-success {
            background: var(--success-color);
            color: var(--white);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .modal-content {
            background: var(--white);
            border-radius: var(--border-radius);
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            border-bottom: 1px solid var(--gray-200);
            padding: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-title {
            color: var(--gray-800);
            font-weight: 600;
        }

        .form-label {
            color: var(--gray-700);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-uppercase {
            letter-spacing: 0.05em;
        }

        .shadow-lg {
            box-shadow: var(--shadow-lg) !important;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }

        .position-fixed {
            position: fixed !important;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }

        /* Breadcrumb styling */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 1rem;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            color: var(--gray-400);
        }
        
        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .breadcrumb-item a:hover {
            color: var(--primary-dark);
        }
        
        .breadcrumb-item.active {
            color: var(--gray-600);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .examination-navbar .nav-link {
                margin: 0.25rem 0;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<nav class="navbar navbar-expand-lg examination-navbar shadow-sm py-3">
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
                    <a class="nav-link <?php if(request()->routeIs('examination.dashboard')): ?> active <?php endif; ?>" href="<?php echo e(route('examination.dashboard')); ?>">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                </li>
                <?php if(auth()->check() && auth()->user()->hasRole('student')): ?>
                <li class="nav-item">
                    <a class="nav-link <?php if(request()->routeIs('examination.student.exams')): ?> active <?php endif; ?>" href="<?php echo e(route('examination.student.exams')); ?>">
                        <i class="fas fa-book-open me-1"></i> My Exams
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if(request()->routeIs('examination.student.history')): ?> active <?php endif; ?>" href="<?php echo e(route('examination.student.history')); ?>">
                        <i class="fas fa-history me-1"></i> My Attempts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if(request()->routeIs('examination.student.results')): ?> active <?php endif; ?>" href="<?php echo e(route('examination.student.results')); ?>">
                        <i class="fas fa-clipboard-check me-1"></i> My Results
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link <?php if(request()->routeIs('examination.questions.*')): ?> active <?php endif; ?>" href="<?php echo e(route('examination.questions.index')); ?>">
                        <i class="fas fa-question-circle me-1"></i> Questions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if(request()->routeIs('examination.categories.*')): ?> active <?php endif; ?>" href="<?php echo e(route('examination.categories.index')); ?>">
                        <i class="fas fa-folder me-1"></i> Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if(request()->routeIs('examination.schedules.*')): ?> active <?php endif; ?>" href="<?php echo e(route('examination.schedules.index')); ?>">
                        <i class="fas fa-calendar-alt me-1"></i> Schedules
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?php if(request()->routeIs('examination.proctoring.*')): ?> active <?php endif; ?>" href="#" id="proctoringDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-eye me-1"></i> Proctoring
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="proctoringDropdown">
                        <li><a class="dropdown-item" href="<?php echo e(route('examination.proctoring.index')); ?>">Proctoring Home</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('examination.proctoring.dashboard')); ?>">Proctor Dashboard</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if(request()->routeIs('examination.results.*')): ?> active <?php endif; ?>" href="<?php echo e(route('examination.results.index')); ?>">
                        <i class="fas fa-clipboard-list me-1"></i> Results
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container examination-content">
    <?php echo $__env->yieldContent('content'); ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html> <?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Examination/resources/views/layouts/app.blade.php ENDPATH**/ ?>