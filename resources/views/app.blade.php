<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Dunco School Management System') }}</title>
    
    <!-- Simple styling for tests -->
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: #f8f9fa; padding: 20px; margin-bottom: 20px; border-radius: 5px; }
        .content { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name', 'Dunco School Management System') }}</h1>
            @auth
                <p>Welcome, {{ auth()->user()->name }}!</p>
                <a href="{{ route('dashboard') }}">Dashboard</a> |
                <a href="{{ route('profile.edit') }}">Profile</a> |
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #007bff; text-decoration: underline; cursor: pointer;">Logout</button>
                </form>
            @else
                <p>Please <a href="{{ route('login') }}">login</a> or <a href="{{ route('register') }}">register</a></p>
            @endauth
        </div>
        
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>
</html>
