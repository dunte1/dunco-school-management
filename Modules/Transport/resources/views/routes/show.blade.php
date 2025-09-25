@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transport.routes.index') }}">Routes</a></li>
                        <li class="breadcrumb-item active">Route Details</li>
                    </ol>
                </div>
                <h4 class="page-title">Route Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Route Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Route Name:</strong></td>
                                    <td>{{ $route->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Start Point:</strong></td>
                                    <td>{{ $route->start_point }}</td>
                                </tr>
                                <tr>
                                    <td><strong>End Point:</strong></td>
                                    <td>{{ $route->end_point }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Distance:</strong></td>
                                    <td>{{ $route->distance ?? 0 }} km</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($route->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>School:</strong></td>
                                    <td>{{ $route->school->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $route->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $route->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($route->description)
                            <div class="mb-3">
                                <h5>Description:</h5>
                                <p>{{ $route->description }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($route->stops->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="header-title">Route Stops</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Stop Name</th>
                                    <th>Location</th>
                                    <th>Sequence</th>
                                    <th>Pickup Time</th>
                                    <th>Drop Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($route->stops->sortBy('sequence') as $stop)
                                <tr>
                                    <td>{{ $stop->stop_name }}</td>
                                    <td>{{ $stop->location }}</td>
                                    <td><span class="badge bg-primary">{{ $stop->sequence }}</span></td>
                                    <td>{{ $stop->pickup_time ?? 'N/A' }}</td>
                                    <td>{{ $stop->drop_time ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('transport.routes.edit', $route) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Route
                        </a>
                        <a href="{{ route('transport.trips.create') }}?route_id={{ $route->id }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Schedule Trip
                        </a>
                        <a href="{{ route('transport.routes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Routes
                        </a>
                    </div>
                </div>
            </div>

            @if($route->vehicles->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="header-title">Assigned Vehicles</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($route->vehicles as $vehicle)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ route('transport.vehicles.show', $vehicle) }}">{{ $vehicle->vehicle_number }}</a>
                            <span class="badge bg-primary rounded-pill">{{ $vehicle->vehicle_type }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>

    @if($route->trips->count() > 0)
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
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Status</th>
                                    <th>Passengers</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($route->trips->take(10) as $trip)
                                <tr>
                                    <td>{{ $trip->trip_date->format('d M Y') }}</td>
                                    <td>{{ $trip->vehicle->vehicle_number ?? 'N/A' }}</td>
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