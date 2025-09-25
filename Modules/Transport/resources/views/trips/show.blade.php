@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transport.trips.index') }}">Trips</a></li>
                        <li class="breadcrumb-item active">Trip Details</li>
                    </ol>
                </div>
                <h4 class="page-title">Trip Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Trip Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Trip Date:</strong></td>
                                    <td>{{ $trip->trip_date->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Start Time:</strong></td>
                                    <td>{{ $trip->start_time ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>End Time:</strong></td>
                                    <td>{{ $trip->end_time ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
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
                                </tr>
                                <tr>
                                    <td><strong>Passenger Count:</strong></td>
                                    <td>{{ $trip->passenger_count ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Distance Covered:</strong></td>
                                    <td>{{ $trip->distance_covered ?? 0 }} km</td>
                                </tr>
                                <tr>
                                    <td><strong>Fuel Consumed:</strong></td>
                                    <td>{{ $trip->fuel_consumed ?? 0 }} liters</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $trip->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Route:</strong></td>
                                    <td>
                                        @if($trip->route)
                                            <a href="{{ route('transport.routes.show', $trip->route) }}">{{ $trip->route->name }}</a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Vehicle:</strong></td>
                                    <td>
                                        @if($trip->vehicle)
                                            <a href="{{ route('transport.vehicles.show', $trip->vehicle) }}">{{ $trip->vehicle->vehicle_number }}</a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Driver:</strong></td>
                                    <td>
                                        @if($trip->driver)
                                            <a href="{{ route('transport.drivers.show', $trip->driver) }}">{{ $trip->driver->name }}</a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>School:</strong></td>
                                    <td>{{ $trip->school->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $trip->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($trip->notes)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Notes:</h5>
                            <p>{{ $trip->notes }}</p>
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
                        <a href="{{ route('transport.trips.edit', $trip) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Trip
                        </a>
                        <a href="{{ route('transport.trips.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Trips
                        </a>
                    </div>
                </div>
            </div>

            @if($trip->passengers->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="header-title">Passengers</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($trip->passengers as $passenger)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $passenger->name ?? 'Passenger ' . $loop->iteration }}</span>
                            <span class="badge bg-primary rounded-pill">{{ $passenger->pivot->pickup_location ?? 'N/A' }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 