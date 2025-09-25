@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Trips</li>
                    </ol>
                </div>
                <h4 class="page-title">Transport Trips</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title">All Trips</h4>
                    <a href="{{ route('transport.trips.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Schedule New Trip
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="{{ route('transport.trips.index') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                       placeholder="Search trips..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('transport.trips.index', ['status' => 'scheduled']) }}" 
                                   class="btn btn-outline-info {{ request('status') == 'scheduled' ? 'active' : '' }}">
                                    Scheduled
                                </a>
                                <a href="{{ route('transport.trips.index', ['status' => 'in_progress']) }}" 
                                   class="btn btn-outline-warning {{ request('status') == 'in_progress' ? 'active' : '' }}">
                                    In Progress
                                </a>
                                <a href="{{ route('transport.trips.index', ['status' => 'completed']) }}" 
                                   class="btn btn-outline-success {{ request('status') == 'completed' ? 'active' : '' }}">
                                    Completed
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Trip Date</th>
                                    <th>Route</th>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Status</th>
                                    <th>Passengers</th>
                                    <th>Distance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trips as $trip)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $trip->trip_date->format('d M Y') }}</span>
                                    </td>
                                    <td>
                                        @if($trip->route)
                                            <a href="{{ route('transport.routes.show', $trip->route) }}">
                                                {{ $trip->route->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($trip->vehicle)
                                            <a href="{{ route('transport.vehicles.show', $trip->vehicle) }}">
                                                {{ $trip->vehicle->vehicle_number }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($trip->driver)
                                            <a href="{{ route('transport.drivers.show', $trip->driver) }}">
                                                {{ $trip->driver->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $trip->start_time ?? 'N/A' }}</td>
                                    <td>{{ $trip->end_time ?? 'N/A' }}</td>
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
                                    <td>
                                        <span class="badge bg-primary">{{ $trip->passenger_count ?? 0 }}</span>
                                    </td>
                                    <td>{{ $trip->distance_covered ?? 0 }} km</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('transport.trips.show', $trip) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('transport.trips.edit', $trip) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('transport.trips.destroy', $trip) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this trip?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-route fa-3x mb-3"></i>
                                            <h5>No trips found</h5>
                                            <p>Start by scheduling your first transport trip.</p>
                                            <a href="{{ route('transport.trips.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Schedule Trip
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($trips->hasPages())
                    <div class="row mt-3">
                        <div class="col-12">
                            {{ $trips->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 