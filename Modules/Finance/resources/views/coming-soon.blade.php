@extends('layouts.app')

@section('title', 'Coming Soon - Finance Module')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Coming Soon</h1>
            <p class="text-muted mb-0">Exciting new features are being developed</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Finance
            </a>
        </div>
    </div>

    <!-- Coming Soon Features Grid -->
    <div class="row">
        <!-- Advanced Reporting -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-chart-pie fa-3x text-white"></i>
                    </div>
                    <h5 class="card-title fw-bold">Advanced Analytics</h5>
                    <p class="card-text small">Interactive dashboards, custom reports, and real-time analytics with drill-down capabilities.</p>
                    <div class="mt-3">
                        <span class="badge bg-white text-dark px-3 py-2">Q2 2024</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile App -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-mobile-alt fa-3x text-white"></i>
                    </div>
                    <h5 class="card-title fw-bold">Mobile Finance App</h5>
                    <p class="card-text small">Native mobile app for iOS and Android with offline capabilities and push notifications.</p>
                    <div class="mt-3">
                        <span class="badge bg-white text-dark px-3 py-2">Q3 2024</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Integration -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-robot fa-3x text-white"></i>
                    </div>
                    <h5 class="card-title fw-bold">AI-Powered Insights</h5>
                    <p class="card-text small">Machine learning algorithms for predictive analytics, fraud detection, and automated recommendations.</p>
                    <div class="mt-3">
                        <span class="badge bg-white text-dark px-3 py-2">Q4 2024</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Multi-Currency -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-globe fa-3x text-white"></i>
                    </div>
                    <h5 class="card-title fw-bold">Multi-Currency Support</h5>
                    <p class="card-text small">Support for multiple currencies with real-time exchange rates and automatic conversions.</p>
                    <div class="mt-3">
                        <span class="badge bg-white text-dark px-3 py-2">Q2 2024</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Integration -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-plug fa-3x text-white"></i>
                    </div>
                    <h5 class="card-title fw-bold">API & Integrations</h5>
                    <p class="card-text small">RESTful API, webhooks, and integrations with popular accounting software and banking systems.</p>
                    <div class="mt-3">
                        <span class="badge bg-white text-dark px-3 py-2">Q3 2024</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Security -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333;">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt fa-3x" style="color: #1ea7ff;"></i>
                    </div>
                    <h5 class="card-title fw-bold" style="color: #1ea7ff;">Enhanced Security</h5>
                    <p class="card-text small">Two-factor authentication, biometric login, and advanced encryption for maximum data protection.</p>
                    <div class="mt-3">
                        <span class="badge bg-primary text-white px-3 py-2">Q1 2024</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <h3 class="h4 mb-4 text-center">Development Progress</h3>
                    <div class="row">
                        <div class="col-md-3 text-center mb-4">
                            <div class="progress-circle mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: conic-gradient(#1ea7ff 0deg 288deg, #e9ecef 288deg 360deg); display: flex; align-items: center; justify-content: center;">
                                <span class="fw-bold text-primary">80%</span>
                            </div>
                            <h6 class="fw-bold">Core Features</h6>
                            <small class="text-muted">Basic finance operations</small>
                        </div>
                        <div class="col-md-3 text-center mb-4">
                            <div class="progress-circle mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: conic-gradient(#22c55e 0deg 180deg, #e9ecef 180deg 360deg); display: flex; align-items: center; justify-content: center;">
                                <span class="fw-bold text-success">50%</span>
                            </div>
                            <h6 class="fw-bold">Advanced Features</h6>
                            <small class="text-muted">Reporting & analytics</small>
                        </div>
                        <div class="col-md-3 text-center mb-4">
                            <div class="progress-circle mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: conic-gradient(#ffb300 0deg 72deg, #e9ecef 72deg 360deg); display: flex; align-items: center; justify-content: center;">
                                <span class="fw-bold text-warning">20%</span>
                            </div>
                            <h6 class="fw-bold">Mobile App</h6>
                            <small class="text-muted">Native applications</small>
                        </div>
                        <div class="col-md-3 text-center mb-4">
                            <div class="progress-circle mx-auto mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: conic-gradient(#6ec1e4 0deg 36deg, #e9ecef 36deg 360deg); display: flex; align-items: center; justify-content: center;">
                                <span class="fw-bold text-info">10%</span>
                            </div>
                            <h6 class="fw-bold">AI Features</h6>
                            <small class="text-muted">Machine learning</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Newsletter Signup -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #1ea7ff 0%, #1565c0 100%); color: white;">
                <div class="card-body text-center p-5">
                    <h3 class="h4 mb-3">Stay Updated</h3>
                    <p class="mb-4">Get notified when new features are released and receive exclusive updates about the finance module.</p>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="email" class="form-control form-control-lg" placeholder="Enter your email address">
                                <button class="btn btn-light btn-lg" type="button">
                                    <i class="fas fa-bell me-2"></i>Subscribe
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
