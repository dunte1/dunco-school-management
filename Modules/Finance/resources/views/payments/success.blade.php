@extends('layouts.app')

@section('title', 'Payment Successful - Finance Module')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Success Card -->
            <div class="card border-0 shadow-lg text-center">
                <div class="card-body p-5">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <div class="success-icon mx-auto">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <h2 class="text-success mb-3">Payment Successful!</h2>
                    <p class="text-muted mb-4">
                        Your payment has been processed successfully. You will receive a receipt via email shortly.
                    </p>

                    <!-- Payment Details -->
                    <div class="bg-light rounded p-4 mb-4">
                        <h6 class="text-muted mb-3">Payment Details</h6>
                        <div class="row text-start">
                            <div class="col-6">
                                <small class="text-muted">Transaction ID:</small>
                                <div class="fw-bold">{{ session('payment_reference', 'N/A') }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Amount Paid:</small>
                                <div class="fw-bold text-success">KES {{ number_format(session('payment_amount', 0), 2) }}</div>
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
                        <a href="{{ route('finance.payments.index') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>View All Payments
                        </a>
                        <a href="{{ route('finance.receipts.index') }}" class="btn btn-outline-success">
                            <i class="fas fa-receipt me-2"></i>View Receipts
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                    </div>

                    <!-- Additional Information -->
                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            A receipt has been sent to your email address. Please keep this for your records.
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
                            <h6 class="text-muted">Contact Support</h6>
                            <p class="small mb-1">
                                <i class="fas fa-phone me-2"></i>+254 700 000 000
                            </p>
                            <p class="small mb-1">
                                <i class="fas fa-envelope me-2"></i>support@duncowebsolutions.co.ke
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Business Hours</h6>
                            <p class="small mb-1">Monday - Friday: 8:00 AM - 5:00 PM</p>
                            <p class="small mb-1">Saturday: 9:00 AM - 1:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.success-icon {
    animation: bounceIn 0.6s ease-in-out;
}

@keyframes bounceIn {
    0% {
        transform: scale(0.3);
        opacity: 0;
    }
    50% {
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
        opacity: 1;
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
