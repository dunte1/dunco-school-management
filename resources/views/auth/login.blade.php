@extends('layouts.auth')
@section('title', 'Login - Dunco SMS')

@section('content')
<div class="auth-form-header">
    <div class="logo-mobile">
        <div class="logo-mobile-icon"><i class="fas fa-graduation-cap"></i></div>
        <span class="logo-mobile-text">Dunco SMS</span>
    </div>
    <h1>Welcome back</h1>
    <p>Sign in to your account to continue</p>
</div>

<div class="auth-card">
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" name="email" class="form-input @error('email') error @enderror" value="{{ old('email') }}" required autofocus placeholder="you@example.com">
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input id="password" type="password" name="password" class="form-input @error('password') error @enderror" required placeholder="Enter your password">
                <button type="button" class="input-toggle" onclick="togglePassword(this)" aria-label="Toggle password visibility">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-row">
            <div class="form-check">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Remember me</label>
            </div>
            <a href="{{ route('magic.link') }}" class="form-forgot">Forgot password?</a>
        </div>
        <button type="submit" class="btn-primary">Sign in</button>
    </form>

    <div class="auth-footer">
        Don't have an account? <a href="{{ route('register') }}">Create one</a>
    </div>
</div>

@push('scripts')
<script>
function togglePassword(btn) {
    const input = btn.previousElementSibling;
    const icon = btn.querySelector('i');
    if (input.type === 'password') { input.type = 'text'; icon.classList.replace('fa-eye', 'fa-eye-slash'); }
    else { input.type = 'password'; icon.classList.replace('fa-eye-slash', 'fa-eye'); }
}
</script>
@endpush
@endsection
