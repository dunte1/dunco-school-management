@extends('layouts.app')

@section('title', 'Vehicle Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Vehicle Reports</h3>
                    <div class="card-tools">
                        <a href="{{ route('transport.reports.export', ['type' => 'vehicles']) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Vehicle Number</th>
                                    <th>Type</th>
                                    <th>Brand/Model</th>
                                    <th>Driver</th>
                                    <th>Status</th>
                                    <th>Total Trips</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vehicles as $vehicle)
                                <tr>
                                    <td>{{ $vehicle->vehicle_number }}</td>
                                    <td>{{ ucfirst($vehicle->vehicle_type) }}</td>
                                    <td>{{ $vehicle->brand }} {{ $vehicle->model }}</td>
                                    <td>{{ $vehicle->driver->name ?? 'Unassigned' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $vehicle->status === 'active' ? 'success' : ($vehicle->status === 'maintenance' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($vehicle->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $vehicle->trips_count }}</td>
                                    <td>
                                        <a href="{{ route('transport.vehicles.show', $vehicle->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No vehicles found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $vehicles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
