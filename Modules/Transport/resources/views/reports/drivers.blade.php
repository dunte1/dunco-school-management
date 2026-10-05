@extends('layouts.app')

@section('title', 'Driver Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Driver Reports</h3>
                    <div class="card-tools">
                        <a href="{{ route('transport.reports.export', ['type' => 'drivers']) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>License Number</th>
                                    <th>Assigned Vehicle</th>
                                    <th>Status</th>
                                    <th>Total Trips</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($drivers as $driver)
                                <tr>
                                    <td>{{ $driver->name }}</td>
                                    <td>{{ $driver->phone }}</td>
                                    <td>{{ $driver->license_number }}</td>
                                    <td>{{ $driver->vehicle->vehicle_number ?? 'Unassigned' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $driver->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($driver->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $driver->trips_count }}</td>
                                    <td>
                                        <a href="{{ route('transport.drivers.show', $driver->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No drivers found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $drivers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
