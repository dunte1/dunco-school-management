@extends('layouts.auth')

@section('title', 'Forgot Password')

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
                        <h4 class="fw-bold text-dark">Forgot Password?</h4>
                        <p class="text-muted">No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <input id="email" 
                                   type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}" 
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

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>
                                Email Password Reset Link
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

            <!-- Additional Help -->
            <div class="text-center mt-4">
                <div class="card border-0 bg-light">
                    <div class="card-body p-3">
                        <h6 class="card-title text-muted mb-2">
                            <i class="fas fa-question-circle me-2"></i>
                            Need Help?
                        </h6>
                        <p class="card-text small text-muted mb-0">
                            If you're having trouble accessing your account, please contact your system administrator or IT support team.
                        </p>
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
@endsection
