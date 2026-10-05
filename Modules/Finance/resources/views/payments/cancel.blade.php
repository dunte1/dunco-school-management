@extends('layouts.app')

@section('title', 'Payment Cancelled - Finance Module')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Cancel Card -->
            <div class="card border-0 shadow-lg text-center">
                <div class="card-body p-5">
                    <!-- Cancel Icon -->
                    <div class="mb-4">
                        <div class="cancel-icon mx-auto">
                            <i class="fas fa-times-circle text-warning" style="font-size: 4rem;"></i>
                        </div>
                    </div>

                    <!-- Cancel Message -->
                    <h2 class="text-warning mb-3">Payment Cancelled</h2>
                    <p class="text-muted mb-4">
                        Your payment was cancelled. No charges have been made to your account.
                    </p>

                    <!-- Payment Details -->
                    <div class="bg-light rounded p-4 mb-4">
                        <h6 class="text-muted mb-3">Payment Details</h6>
                        <div class="row text-start">
                            <div class="col-6">
                                <small class="text-muted">Invoice ID:</small>
                                <div class="fw-bold">{{ session('invoice_id', 'N/A') }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Amount:</small>
                                <div class="fw-bold text-muted">KES {{ number_format(session('payment_amount', 0), 2) }}</div>
                            </div>
                            <div class="col-6 mt-2">
                                <small class="text-muted">Payment Method:</small>
                                <div class="fw-bold">{{ session('payment_method', 'M-Pesa') }}</div>
                            </div>
                            <div class="col-6 mt-2">
                                <small class="text-muted">Date:</small>
                                <div class="fw-bold">{{ now()->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('finance.online-payments.mpesa') }}" class="btn btn-success">
                            <i class="fas fa-mobile-alt me-2"></i>Try Payment Again
                        </a>
                        <a href="{{ route('finance.payments.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>View All Payments
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </div>

                    <!-- Additional Information -->
                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            If you continue to experience issues, please contact our support team.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-question-circle me-2 text-info"></i>Need Help?
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Common Issues</h6>
                            <ul class="small text-start">
                                <li>Insufficient M-Pesa balance</li>
                                <li>Network connectivity issues</li>
                                <li>Incorrect phone number</li>
                                <li>M-Pesa service unavailable</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Contact Support</h6>
                            <p class="small mb-1">
                                <i class="fas fa-phone me-2"></i>+254 700 000 000
                            </p>
                            <p class="small mb-1">
                                <i class="fas fa-envelope me-2"></i>support@duncowebsolutions.co.ke
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alternative Payment Methods -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-credit-card me-2 text-primary"></i>Alternative Payment Methods
                    </h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-university text-primary fa-2x mb-2"></i>
                                <h6 class="mb-1">Bank Transfer</h6>
                                <small class="text-muted">Direct bank transfer</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-money-bill-wave text-success fa-2x mb-2"></i>
                                <h6 class="mb-1">Cash Payment</h6>
                                <small class="text-muted">Pay at school office</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-file-invoice text-info fa-2x mb-2"></i>
                                <h6 class="mb-1">Cheque</h6>
                                <small class="text-muted">Post-dated cheques</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.cancel-icon {
    animation: shake 0.6s ease-in-out;
}

@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    10%, 30%, 50%, 70%, 90% {
        transform: translateX(-5px);
    }
    20%, 40%, 60%, 80% {
        transform: translateX(5px);
    }
}

.card {
    animation: slideInUp 0.5s ease-out;
}

@keyframes slideInUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>
@endsection
