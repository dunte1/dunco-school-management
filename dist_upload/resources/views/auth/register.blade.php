@extends('layouts.auth')

@section('content')
<div class="card shadow">
    <div class="card-header text-white">
        <h4 class="mb-0">Register</h4>
    </div>
    <div class="card-body p-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required>
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
            
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input id="password_confirmation" type="password" class="form-control" 
                       name="password_confirmation" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>
            
            <div class="text-center">
                <p class="mb-0">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Login here</a></p>
            </div>
        </form>
    </div>
</div>
@endsection 