@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Hostel Dashboard</h1>
        <div class="text-muted">Welcome back, {{ Auth::user()->name }}!</div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Rooms</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_rooms'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Occupied Rooms</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['occupied_rooms'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bed fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Wardens</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_wardens'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Pending Issues</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_issues'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('hostel.dashboard') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-tachometer-alt me-2"></i>Hostel Dashboard
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/hostel/rooms') }}" class="btn btn-success btn-block">
                                <i class="fas fa-door-open me-2"></i>Manage Rooms
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/hostel/allocations') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-exchange-alt me-2"></i>Allocations
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/hostel/issues') }}" class="btn btn-danger btn-block">
                                <i class="fas fa-tools me-2"></i>Maintenance Issues
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Occupancy Overview</h6>
                </div>
                <div class="card-body">
                    @php
                        $totalRooms = max($stats['total_rooms'] ?? 1, 1);
                        $occupiedRooms = $stats['occupied_rooms'] ?? 0;
                        $occupancyRate = round(($occupiedRooms / $totalRooms) * 100, 1);
                    @endphp
                    <div class="progress mb-3" style="height: 24px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $occupancyRate }}%;" aria-valuenow="{{ $occupancyRate }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $occupancyRate }}%
                        </div>
                    </div>
                    <p class="mb-1"><strong>Available Rooms:</strong> {{ $totalRooms - $occupiedRooms }}</p>
                    <p class="mb-1"><strong>Occupied Rooms:</strong> {{ $occupiedRooms }}</p>
                    <p class="mb-0"><strong>Total Rooms:</strong> {{ $stats['total_rooms'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Reports</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('hostel.reports.occupancy') }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-chart-pie me-2"></i>Occupancy Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('hostel.reports.allocation') }}" class="btn btn-outline-success btn-block">
                                <i class="fas fa-exchange-alt me-2"></i>Allocation Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('hostel.reports.maintenance') }}" class="btn btn-outline-warning btn-block">
                                <i class="fas fa-tools me-2"></i>Maintenance Report
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('hostel.reports.defaulters') }}" class="btn btn-outline-danger btn-block">
                                <i class="fas fa-exclamation-circle me-2"></i>Fee Defaulters
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
