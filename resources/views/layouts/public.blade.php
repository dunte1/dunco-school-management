<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dunco School Management System')</title>
    <meta name="description" content="@yield('meta_description', 'A comprehensive, all-in-one school management platform for administrators, teachers, parents, and students.')">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icon-512x512.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800|inter-display:400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/css/public.css'])
    @stack('head')
</head>
<body class="font-sans antialiased">
    <div id="page-transition" class="page-transition-overlay"></div>

    @include('components.public-nav')

    <main>
        @yield('content')
    </main>

    @include('components.public-footer')

    @vite(['resources/js/public.js'])
    @stack('scripts')
</body>
</html>
