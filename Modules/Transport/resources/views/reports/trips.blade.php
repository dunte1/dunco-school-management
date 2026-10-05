@extends('layouts.app')

@section('title', 'Trip Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Trip Reports</h3>
                    <div class="card-tools">
                        <a href="{{ route('transport.reports.export', ['type' => 'trips']) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Route</th>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($trips as $trip)
                                <tr>
                                    <td>{{ $trip->id }}</td>
                                    <td>{{ $trip->route->name ?? 'N/A' }}</td>
                                    <td>{{ $trip->vehicle->vehicle_number ?? 'N/A' }}</td>
                                    <td>{{ $trip->driver->name ?? 'N/A' }}</td>
                                    <td>{{ $trip->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $trip->status === 'completed' ? 'success' : ($trip->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($trip->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('transport.trips.show', $trip->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No trips found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $trips->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
