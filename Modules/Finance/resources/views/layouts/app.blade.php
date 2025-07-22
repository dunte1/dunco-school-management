<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Module</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 min-h-screen text-gray-900">
    <nav class="bg-gradient-to-r from-blue-700 via-blue-600 to-blue-500 p-4 shadow flex items-center justify-between">
        <div class="text-2xl font-bold tracking-widest text-white uppercase">Finance</div>
        <ul class="flex space-x-6">
            <li><a href="{{ route('finance.roles.index') }}" class="uppercase text-blue-100 hover:text-white font-semibold">Roles</a></li>
            <li><a href="{{ route('finance.settings.index') }}" class="uppercase text-blue-100 hover:text-white font-semibold">Settings</a></li>
            <!-- Add more nav links as needed -->
        </ul>
    </nav>
    <div class="w-full max-w-6xl mx-auto px-4 md:px-8">
        @yield('breadcrumb')
    </div>
    <main class="flex justify-center items-start py-8">
        <div class="w-full max-w-6xl mx-auto px-4 md:px-8">
            @yield('content')
        </div>
    </main>
</body>
</html> 