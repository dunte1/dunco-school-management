@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Document Reports</h2>
        <div>
            <a href="{{ route('document.reports.export') }}" class="btn btn-success">
                <i class="fas fa-download me-2"></i>Export Report
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-file-upload fa-2x text-primary mb-3"></i>
                    <h5 class="card-title">Upload Reports</h5>
                    <p class="card-text">View document upload statistics and trends</p>
                    <a href="{{ route('document.reports.uploads') }}" class="btn btn-outline-primary">View Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-download fa-2x text-success mb-3"></i>
                    <h5 class="card-title">Download Reports</h5>
                    <p class="card-text">Track document download activity</p>
                    <a href="{{ route('document.reports.downloads') }}" class="btn btn-outline-success">View Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-share fa-2x text-info mb-3"></i>
                    <h5 class="card-title">Sharing Reports</h5>
                    <p class="card-text">Monitor document sharing activity</p>
                    <a href="{{ route('document.reports.sharing') }}" class="btn btn-outline-info">View Report</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-hdd fa-2x text-warning mb-3"></i>
                    <h5 class="card-title">Storage Reports</h5>
                    <p class="card-text">Analyze storage usage and capacity</p>
                    <a href="{{ route('document.reports.storage') }}" class="btn btn-outline-warning">View Report</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Quick Statistics</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    <h3 class="text-primary">{{ $totalDocuments ?? 0 }}</h3>
                    <p class="text-muted">Total Documents</p>
                </div>
                <div class="col-md-3 text-center">
                    <h3 class="text-success">{{ $totalDownloads ?? 0 }}</h3>
                    <p class="text-muted">Total Downloads</p>
                </div>
                <div class="col-md-3 text-center">
                    <h3 class="text-info">{{ $totalShares ?? 0 }}</h3>
                    <p class="text-muted">Active Shares</p>
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
