@extends('layouts.auth')

@section('content')
<div class="card shadow">
    <div class="card-header text-white">
        <h4 class="mb-0">Magic Link Login</h4>
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
        
        <p class="text-muted mb-4">Enter your email address and we'll send you a magic link to login without a password.</p>
        
        <form method="POST" action="{{ route('magic.link.send') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn btn-primary w-100 mb-3">Send Magic Link</button>
            
            <div class="text-center">
                <p class="mb-0">Remember your password? <a href="{{ route('login') }}" class="text-decoration-none">Login here</a></p>
            </div>
        </form>
    </div>
</div>
@endsection 