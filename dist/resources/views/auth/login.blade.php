@extends('layouts.auth')

@section('content')
<div class="card shadow">
    <div class="card-header text-white">
        <h4 class="mb-0">Login</h4>
    </div>
    <div class="card-body p-4">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
            
            <div class="text-center">
                <p class="mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register here</a></p>
                <p class="mb-0 mt-2">Forgot your password? <a href="{{ route('magic.link') }}" class="text-decoration-none">Login with Magic Link</a></p>
            </div>
        </form>
    </div>
</div>
@endsection
