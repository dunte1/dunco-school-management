@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="auth-logo mb-3">
                            @if(!empty($branding['logo_url']))
                                <img src="{{ $branding['logo_url'] }}" alt="{{ $branding['school_name'] ?? 'Logo' }}" class="img-fluid" style="max-height: 60px;">
                            @else
                                <div class="logo-placeholder">
                                    <i class="fas fa-graduation-cap fa-3x text-primary"></i>
                                </div>
                            @endif
                        </div>
                        <h4 class="fw-bold text-dark">Reset Password</h4>
                        <p class="text-muted">Please enter your email address and new password to reset your account.</p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <input id="email" 
                                   type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ $email ?? old('email') }}" 
                                   required 
                                   autocomplete="email" 
                                   autofocus
                                   placeholder="Enter your email address">
                            
                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-lock me-2"></i>New Password
                            </label>
                            <div class="input-group">
                                <input id="password" 
                                       type="password" 
                                       class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Enter new password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            @error('password')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                            
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Password must be at least 8 characters long.
                                </small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                <i class="fas fa-lock me-2"></i>Confirm New Password
                            </label>
                            <div class="input-group">
                                <input id="password_confirmation" 
                                       type="password" 
                                       class="form-control form-control-lg" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Confirm new password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-key me-2"></i>
                                Reset Password
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="mb-0">
                                Remember your password? 
                                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                                    <i class="fas fa-sign-in-alt me-1"></i>
                                    Back to Login
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Requirements -->
            <div class="text-center mt-4">
                <div class="card border-0 bg-light">
                    <div class="card-body p-3">
                        <h6 class="card-title text-muted mb-2">
                            <i class="fas fa-shield-alt me-2"></i>
                            Password Requirements
                        </h6>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li><i class="fas fa-check text-success me-1"></i> At least 8 characters long</li>
                            <li><i class="fas fa-check text-success me-1"></i> Mix of letters and numbers</li>
                            <li><i class="fas fa-check text-success me-1"></i> Special characters recommended</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.auth-logo img {
    max-height: 60px;
    width: auto;
}

.logo-placeholder {
    color: #007bff;
}

.form-control-lg {
    padding: 0.75rem 1rem;
    font-size: 1rem;
    border-radius: 0.5rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control-lg:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
}

.card {
    border-radius: 1rem;
    overflow: hidden;
}

.alert {
    border-radius: 0.75rem;
    border: none;
}

.invalid-feedback {
    display: block;
    font-size: 0.875rem;
}

@media (max-width: 576px) {
    .container {
        padding: 1rem;
    }
    
    .card-body {
        padding: 2rem 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    togglePassword.addEventListener('click', function() {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    togglePasswordConfirmation.addEventListener('click', function() {
        const type = passwordConfirmation.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmation.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });
});
</script>
@endsection
