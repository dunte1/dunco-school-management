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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .auth-header {
            text-align: center;
            padding: 2rem 0 1rem 0;
        }
        .auth-header h1 {
            color: #1a237e;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .auth-header p {
            color: #666;
            margin-bottom: 0;
        }
        .auth-logo {
            font-size: 2.5rem;
            color: #1a237e;
            margin-bottom: 1rem;
        }
        .form-control:focus {
            border-color: #1a237e;
            box-shadow: 0 0 0 0.2rem rgba(26, 35, 126, 0.25);
        }
        .btn-primary {
            background: #1a237e;
            border-color: #1a237e;
        }
        .btn-primary:hover {
            background: #0d133d;
            border-color: #0d133d;
        }
        .card-header {
            background: #1a237e !important;
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="auth-container">
                    <div class="auth-header">
                        <div class="auth-logo">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h1>DUNCO SMS</h1>
                        <p>School Management System</p>
                    </div>
                    
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 