@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Transport Dashboard</h1>
        <div class="text-muted">Welcome back, {{ Auth::user()->name }}!</div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Vehicles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_vehicles'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bus fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Routes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_routes'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-route fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Drivers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_drivers'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-id-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Scheduled Trips</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['scheduled_trips'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marked-alt fa-2x text-gray-300"></i>
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
                            <a href="{{ route('transport.index') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-tachometer-alt me-2"></i>Transport Overview
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/transport/vehicles') }}" class="btn btn-success btn-block">
                                <i class="fas fa-bus me-2"></i>Manage Vehicles
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/transport/routes') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-route me-2"></i>Manage Routes
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('transport.reports') }}" class="btn btn-info btn-block">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Transport Overview</h6>
                </div>
                <div class="card-body">
                    @php
                        $totalVehicles = $stats['total_vehicles'] ?? 0;
                        $activeRoutes = $stats['active_routes'] ?? 0;
                        $totalDrivers = $stats['total_drivers'] ?? 0;
                    @endphp
                    <div class="progress mb-3" style="height: 24px;">
                        @php
                            $driverVehicleRatio = $totalVehicles > 0 ? min(round(($totalDrivers / $totalVehicles) * 100), 100) : 0;
                        @endphp
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $driverVehicleRatio }}%;" aria-valuenow="{{ $driverVehicleRatio }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $driverVehicleRatio }}% Driver-Vehicle Ratio
                        </div>
                    </div>
                    <p class="mb-1"><strong>Total Vehicles:</strong> {{ $totalVehicles }}</p>
                    <p class="mb-1"><strong>Active Routes:</strong> {{ $activeRoutes }}</p>
                    <p class="mb-1"><strong>Total Drivers:</strong> {{ $totalDrivers }}</p>
                    <p class="mb-0"><strong>Scheduled Trips:</strong> {{ $stats['scheduled_trips'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Upcoming Trips</h6>
                </div>
                <div class="card-body">
                    @php
                        $upcomingTrips = \Modules\Transport\Models\Trip::where('date', '>=', now()->toDateString())->latest()->take(5)->get();
                    @endphp
                    @if($upcomingTrips && $upcomingTrips->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Route</th>
                                        <th>Vehicle</th>
                                        <th>Driver</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingTrips as $trip)
                                    <tr>
                                        <td>{{ $trip->route->name ?? 'N/A' }}</td>
                                        <td>{{ $trip->vehicle->plate_number ?? 'N/A' }}</td>
                                        <td>{{ $trip->driver->name ?? 'N/A' }}</td>
                                        <td>{{ $trip->date ? \Carbon\Carbon::parse($trip->date)->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ ($trip->status ?? '') === 'completed' ? 'success' : (($trip->status ?? '') === 'ongoing' ? 'warning' : 'primary') }}">
                                                {{ ucfirst($trip->status ?? 'scheduled') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bus fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No upcoming trips</h5>
                            <p class="text-muted">Scheduled trips will appear here once created.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
