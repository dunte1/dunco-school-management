<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Core Module')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body style="background:#f8fbff; font-family:'Poppins', 'Inter', sans-serif;">
    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1"><i class="fas fa-cogs me-2"></i>Core Module</span>
        </div>
    </nav>
    <main class="container py-4">
        @yield('content')
    </main>
</body>
</html> 