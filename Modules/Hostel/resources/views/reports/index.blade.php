@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Hostel Reports</h2>
    </div>

    <div class="row">
        <!-- Reports Cards -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-chart-pie fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Occupancy Report</h5>
                    <p class="card-text">View room occupancy statistics and trends</p>
                    <a href="{{ route('hostel.reports.occupancy') }}" class="btn btn-primary">
                        <i class="fas fa-eye me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-bed fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Room Allocation</h5>
                    <p class="card-text">Track room assignments and allocations</p>
                    <a href="{{ route('hostel.reports.allocation') }}" class="btn btn-success">
                        <i class="fas fa-eye me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-tools fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Maintenance Report</h5>
                    <p class="card-text">Monitor maintenance requests and issues</p>
                    <a href="{{ route('hostel.reports.maintenance') }}" class="btn btn-warning">
                        <i class="fas fa-eye me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-exchange-alt fa-3x text-info mb-3"></i>
                    <h5 class="card-title">Movement Report</h5>
                    <p class="card-text">Track student movements and transfers</p>
                    <a href="{{ route('hostel.reports.movement') }}" class="btn btn-info">
                        <i class="fas fa-eye me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h5 class="card-title">Fee Defaulters</h5>
                    <p class="card-text">Identify students with outstanding fees</p>
                    <a href="{{ route('hostel.reports.defaulters') }}" class="btn btn-danger">
                        <i class="fas fa-eye me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="fas fa-hammer fa-3x text-secondary mb-3"></i>
                    <h5 class="card-title">Damage Report</h5>
                    <p class="card-text">Report and track property damages</p>
                    <a href="{{ route('hostel.reports.damage') }}" class="btn btn-secondary">
                        <i class="fas fa-eye me-2"></i>View Report
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Statistics -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h3 class="text-primary">{{ $totalStudents ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Students</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="text-success">{{ $activeStudents ?? 0 }}</h3>
                            <p class="text-muted mb-0">Active Students</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="text-info">{{ $totalRooms ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Rooms</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3 class="text-warning">{{ $occupancyRate ?? 0 }}%</h3>
                            <p class="text-muted mb-0">Occupancy Rate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Export Options</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('hostel.reports.export') }}?type=occupancy" class="btn btn-outline-primary w-100">
                                <i class="fas fa-file-excel me-2"></i>Export Occupancy
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('hostel.reports.export') }}?type=students" class="btn btn-outline-success w-100">
                                <i class="fas fa-file-excel me-2"></i>Export Students
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('hostel.reports.export') }}?type=fees" class="btn btn-outline-warning w-100">
                                <i class="fas fa-file-excel me-2"></i>Export Fees
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('hostel.reports.export') }}?type=maintenance" class="btn btn-outline-info w-100">
                                <i class="fas fa-file-excel me-2"></i>Export Maintenance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
