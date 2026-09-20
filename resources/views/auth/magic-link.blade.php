@extends('layouts.auth')
@section('title', 'Magic Link Login - Dunco SMS')

@section('content')
<div class="auth-form-header">
    <div class="logo-mobile">
        <div class="logo-mobile-icon"><i class="fas fa-graduation-cap"></i></div>
        <span class="logo-mobile-text">Dunco SMS</span>
    </div>
    <h1>Magic Link Login</h1>
    <p>Enter your email to receive a login link</p>
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

    <form method="POST" action="{{ route('magic.link.send') }}">
        @csrf
        <div class="form-group">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" name="email" class="form-input @error('email') error @enderror" value="{{ old('email') }}" required autofocus placeholder="you@example.com">
            @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn-primary">Send Magic Link</button>
    </form>

    <div class="auth-footer">
        Prefer password? <a href="{{ route('login') }}">Sign in with password</a>
    </div>
</div>
@endsection
