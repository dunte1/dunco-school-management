@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transport.vehicles.index') }}">Vehicles</a></li>
                        <li class="breadcrumb-item active">Vehicle Details</li>
                    </ol>
                </div>
                <h4 class="page-title">Vehicle Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Vehicle Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Vehicle Number:</strong></td>
                                    <td>{{ $vehicle->vehicle_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Registration Number:</strong></td>
                                    <td>{{ $vehicle->registration_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Vehicle Type:</strong></td>
                                    <td><span class="badge bg-primary">{{ ucfirst($vehicle->vehicle_type) }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Brand:</strong></td>
                                    <td>{{ $vehicle->brand }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Model:</strong></td>
                                    <td>{{ $vehicle->model }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Year:</strong></td>
                                    <td>{{ $vehicle->year }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Capacity:</strong></td>
                                    <td>{{ $vehicle->capacity }} passengers</td>
                                </tr>
                                <tr>
                                    <td><strong>Fuel Type:</strong></td>
                                    <td><span class="badge bg-info">{{ ucfirst($vehicle->fuel_type) }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Mileage:</strong></td>
                                    <td>{{ number_format($vehicle->mileage) }} km</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($vehicle->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($vehicle->status == 'maintenance')
                                            <span class="badge bg-warning">Maintenance</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Insurance Expiry:</strong></td>
                                    <td>
                                        @if($vehicle->insurance_expiry->isPast())
                                            <span class="text-danger">{{ $vehicle->insurance_expiry->format('d M Y') }} (Expired)</span>
                                        @elseif($vehicle->insurance_expiry->diffInDays(now()) <= 30)
                                            <span class="text-warning">{{ $vehicle->insurance_expiry->format('d M Y') }} (Expiring Soon)</span>
                                        @else
                                            <span class="text-success">{{ $vehicle->insurance_expiry->format('d M Y') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Fitness Expiry:</strong></td>
                                    <td>
                                        @if($vehicle->fitness_expiry->isPast())
                                            <span class="text-danger">{{ $vehicle->fitness_expiry->format('d M Y') }} (Expired)</span>
                                        @elseif($vehicle->fitness_expiry->diffInDays(now()) <= 30)
                                            <span class="text-warning">{{ $vehicle->fitness_expiry->format('d M Y') }} (Expiring Soon)</span>
                                        @else
                                            <span class="text-success">{{ $vehicle->fitness_expiry->format('d M Y') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Permit Expiry:</strong></td>
                                    <td>
                                        @if($vehicle->permit_expiry->isPast())
                                            <span class="text-danger">{{ $vehicle->permit_expiry->format('d M Y') }} (Expired)</span>
                                        @elseif($vehicle->permit_expiry->diffInDays(now()) <= 30)
                                            <span class="text-warning">{{ $vehicle->permit_expiry->format('d M Y') }} (Expiring Soon)</span>
                                        @else
                                            <span class="text-success">{{ $vehicle->permit_expiry->format('d M Y') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Assigned Driver:</strong></td>
                                    <td>
                                        @if($vehicle->driver)
                                            <a href="{{ route('transport.drivers.show', $vehicle->driver) }}">{{ $vehicle->driver->name }}</a>
                                        @else
                                            <span class="text-muted">No driver assigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>School:</strong></td>
                                    <td>{{ $vehicle->school->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $vehicle->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $vehicle->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($vehicle->description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Description:</h5>
                            <p>{{ $vehicle->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('transport.vehicles.edit', $vehicle) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Vehicle
                        </a>
                        <a href="{{ route('transport.trips.create') }}?vehicle_id={{ $vehicle->id }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Schedule Trip
                        </a>
                        <a href="{{ route('transport.vehicles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Vehicles
                        </a>
                    </div>
                </div>
            </div>

            @if($vehicle->routes->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="header-title">Assigned Routes</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($vehicle->routes as $route)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ route('transport.routes.show', $route) }}">{{ $route->name }}</a>
                            <span class="badge bg-primary rounded-pill">{{ $route->stops->count() }} stops</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if($vehicle->trips->count() > 0)
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Recent Trips</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Trip Date</th>
                                    <th>Route</th>
                                    <th>Driver</th>
                                    <th>Status</th>
                                    <th>Passengers</th>
                                    <th>Distance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vehicle->trips->take(10) as $trip)
                                <tr>
                                    <td>{{ $trip->trip_date->format('d M Y') }}</td>
                                    <td>{{ $trip->route->name ?? 'N/A' }}</td>
                                    <td>{{ $trip->driver->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($trip->status == 'scheduled')
                                            <span class="badge bg-info">Scheduled</span>
                                        @elseif($trip->status == 'in_progress')
                                            <span class="badge bg-warning">In Progress</span>
                                        @elseif($trip->status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @else
                                            <span class="badge bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>{{ $trip->passenger_count ?? 0 }}</td>
                                    <td>{{ $trip->distance_covered ?? 0 }} km</td>
                                    <td>
                                        <a href="{{ route('transport.trips.show', $trip) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 