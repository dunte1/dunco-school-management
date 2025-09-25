@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Transport</li>
                    </ol>
                </div>
                <h4 class="page-title">Transport Dashboard</h4>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Total Vehicles">Total Vehicles</h5>
                            <h3 class="mt-3 mb-3">{{ $stats['total_vehicles'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> {{ $stats['active_vehicles'] }} Active
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary rounded">
                                <i class="fas fa-bus font-20 text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Total Drivers">Total Drivers</h5>
                            <h3 class="mt-3 mb-3">{{ $stats['total_drivers'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> {{ $stats['active_drivers'] }} Active
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success rounded">
                                <i class="fas fa-user-tie font-20 text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Total Routes">Total Routes</h5>
                            <h3 class="mt-3 mb-3">{{ $stats['total_routes'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-success me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> {{ $stats['active_routes'] }} Active
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info rounded">
                                <i class="fas fa-route font-20 text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="text-muted fw-normal mt-0" title="Today's Trips">Today's Trips</h5>
                            <h3 class="mt-3 mb-3">{{ $stats['today_trips'] }}</h3>
                            <p class="mb-0 text-muted">
                                <span class="text-warning me-2">
                                    <i class="mdi mdi-arrow-up-bold"></i> {{ $stats['scheduled_trips'] }} Scheduled
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning rounded">
                                <i class="fas fa-route font-20 text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quick Actions</h5>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('transport.vehicles.create') }}" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-plus me-2"></i>Add Vehicle
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('transport.drivers.create') }}" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-user-plus me-2"></i>Add Driver
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('transport.routes.create') }}" class="btn btn-info btn-lg w-100">
                                <i class="fas fa-map-marked-alt me-2"></i>Add Route
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('transport.trips.create') }}" class="btn btn-warning btn-lg w-100">
                                <i class="fas fa-calendar-plus me-2"></i>Schedule Trip
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Trips -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Recent Trips</h5>
                        <a href="{{ route('transport.trips.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTrips as $trip)
                                <tr>
                                    <td>{{ $trip->trip_date->format('M d, Y') }}</td>
                                    <td>{{ $trip->vehicle->vehicle_number ?? 'N/A' }}</td>
                                    <td>{{ $trip->driver->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $trip->status === 'completed' ? 'success' : ($trip->status === 'in_progress' ? 'warning' : 'info') }}">
                                            {{ ucfirst(str_replace('_', ' ', $trip->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No recent trips found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Vehicles -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Active Vehicles</h5>
                        <a href="{{ route('transport.vehicles.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Type</th>
                                    <th>Driver</th>
                                    <th>Capacity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeVehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->vehicle_number }}</td>
                                    <td>{{ ucfirst($vehicle->vehicle_type) }}</td>
                                    <td>{{ $vehicle->driver->name ?? 'Unassigned' }}</td>
                                    <td>{{ $vehicle->capacity }} seats</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No active vehicles found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Trips -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title">Upcoming Trips</h5>
                        <a href="{{ route('transport.trips.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Route</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingTrips as $trip)
                                <tr>
                                    <td>{{ $trip->trip_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($trip->start_time)->format('H:i') }}</td>
                                    <td>{{ $trip->vehicle->vehicle_number ?? 'N/A' }}</td>
                                    <td>{{ $trip->driver->name ?? 'N/A' }}</td>
                                    <td>{{ $trip->route->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-info">Scheduled</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No upcoming trips found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
