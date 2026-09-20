@extends('layouts.auth')
@section('title', 'Register - Dunco SMS')

@section('content')
<div class="auth-form-header">
    <div class="logo-mobile">
        <div class="logo-mobile-icon"><i class="fas fa-graduation-cap"></i></div>
        <span class="logo-mobile-text">Dunco SMS</span>
    </div>
    <h1>Create an account</h1>
    <p>Get started with Dunco School Management System</p>
</div>

<div class="auth-card">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="form-group">
            <label for="name" class="form-label">Full name</label>
            <input id="name" type="text" name="name" class="form-input @error('name') error @enderror" value="{{ old('name') }}" required autofocus placeholder="John Doe">
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" name="email" class="form-input @error('email') error @enderror" value="{{ old('email') }}" required placeholder="you@example.com">
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input id="password" type="password" name="password" class="form-input @error('password') error @enderror" required placeholder="Create a password">
                <button type="button" class="input-toggle" onclick="togglePassword(this)" aria-label="Toggle password visibility">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm password</label>
            <div class="input-group">
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" required placeholder="Confirm your password">
                <button type="button" class="input-toggle" onclick="togglePassword(this)" aria-label="Toggle password visibility">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <button type="submit" class="btn-primary" style="margin-top: 0.5rem;">Create account</button>
    </form>

    <div class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
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
