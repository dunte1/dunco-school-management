<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Finance Module - Dunco School Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Inter', system-ui, -apple-system, sans-serif; 
        }
        .navbar-brand { 
            font-weight: 700; 
            color: #1a237e !important; 
        }
        .card { 
            border: none; 
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); 
            transition: transform 0.2s, box-shadow 0.2s; 
        }
        .card:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); 
        }
        .module-card { 
            text-decoration: none; 
            color: inherit; 
        }
        .module-card:hover { 
            text-decoration: none; 
            color: inherit; 
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(135deg, #162447 0%, #1a237e 60%, #3949ab 100%);
            color: #fff;
            z-index: 1030;
            overflow-y: auto;
            padding: 20px 0;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .sidebar-nav li {
            margin-bottom: 5px;
        }
        .sidebar-nav a {
            display: block;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-left-color: #1ea7ff;
        }
        .sidebar-nav i {
            width: 20px;
            margin-right: 10px;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0">
                <i class="fas fa-graduation-cap me-2"></i>
                DUNCO SMS
            </h4>
            <small class="text-muted">Finance Module</small>
        </div>
        
        <ul class="sidebar-nav">
            <li>
                <a href="/dashboard" class="d-flex align-items-center">
                    <i class="fas fa-home"></i>
                    <span>Main Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/finance" class="d-flex align-items-center active">
                    <i class="fas fa-chart-line"></i>
                    <span>Finance Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('finance.fees.index') }}" class="d-flex align-items-center">
                    <i class="fas fa-layer-group"></i>
                    <span>Fee Structures</span>
                </a>
            </li>
            <li>
                <a href="{{ route('finance.billing.index') }}" class="d-flex align-items-center">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Billing & Invoices</span>
                </a>
            </li>
            <li>
                <a href="{{ route('finance.payments.index') }}" class="d-flex align-items-center">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Payments</span>
                </a>
            </li>
            <li>
                <a href="{{ route('finance.receipts.index') }}" class="d-flex align-items-center">
                    <i class="fas fa-receipt"></i>
                    <span>Receipts</span>
                </a>
            </li>
            <li>
                <a href="{{ route('finance.banks.index') }}" class="d-flex align-items-center">
                    <i class="fas fa-university"></i>
                    <span>Bank Accounts</span>
                </a>
            </li>
            <li>
                <a href="{{ route('finance.settings.index') }}" class="d-flex align-items-center">
                    <i class="fas fa-cogs"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
            <div class="container-fluid">
                <button class="btn btn-link d-md-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="navbar-brand mb-0 h1">Finance Module</span>
                <div class="navbar-nav ms-auto">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                            <li><a class="dropdown-item" href="/profile">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }
    </script>
</body>
</html> 