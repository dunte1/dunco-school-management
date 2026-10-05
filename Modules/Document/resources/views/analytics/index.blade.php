@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Document Analytics</h2>
        <div>
            <a href="{{ route('document.analytics.export') }}" class="btn btn-success">
                <i class="fas fa-download me-2"></i>Export Analytics
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-chart-line fa-2x text-primary mb-3"></i>
                    <h5 class="card-title">Usage Analytics</h5>
                    <p class="card-text">Document usage patterns and trends</p>
                    <a href="{{ route('document.analytics.usage') }}" class="btn btn-outline-primary">View Analytics</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-hdd fa-2x text-success mb-3"></i>
                    <h5 class="card-title">Storage Analytics</h5>
                    <p class="card-text">Storage usage and optimization insights</p>
                    <a href="{{ route('document.analytics.storage') }}" class="btn btn-outline-success">View Analytics</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-tachometer-alt fa-2x text-info mb-3"></i>
                    <h5 class="card-title">Performance Analytics</h5>
                    <p class="card-text">System performance and response times</p>
                    <a href="{{ route('document.analytics.performance') }}" class="btn btn-outline-info">View Analytics</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-warning mb-3"></i>
                    <h5 class="card-title">User Analytics</h5>
                    <p class="card-text">User engagement and activity metrics</p>
                    <a href="{{ route('document.analytics.users') }}" class="btn btn-outline-warning">View Analytics</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Overview Dashboard</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <h3 class="text-primary">{{ $totalDocuments ?? 0 }}</h3>
                    <p class="text-muted">Total Documents</p>
                </div>
                <div class="col-md-3 text-center">
                    <h3 class="text-success">{{ $activeUsers ?? 0 }}</h3>
                    <p class="text-muted">Active Users</p>
                </div>
                <div class="col-md-3 text-center">
                    <h3 class="text-info">{{ $totalViews ?? 0 }}</h3>
                    <p class="text-muted">Total Views</p>
                </div>
                <div class="col-md-3 text-center">
                    <h3 class="text-warning">{{ number_format(($storageUsed ?? 0) / 1024 / 1024, 2) }} MB</h3>
                    <p class="text-muted">Storage Used</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
