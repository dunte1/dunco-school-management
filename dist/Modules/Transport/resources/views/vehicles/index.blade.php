@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transport.index') }}">Transport</a></li>
                        <li class="breadcrumb-item active">Vehicles</li>
                    </ol>
                </div>
                <h4 class="page-title">Vehicles Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5 class="card-title">All Vehicles</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('transport.vehicles.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Vehicle
                            </a>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('transport.vehicles.index') }}">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Search vehicles..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Vehicle Number</th>
                                    <th>Type</th>
                                    <th>Brand/Model</th>
                                    <th>Driver</th>
                                    <th>Status</th>
                                    <th>Capacity</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vehicles as $vehicle)
                                <tr>
                                    <td>
                                        <strong>{{ $vehicle->vehicle_number }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $vehicle->registration_number }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($vehicle->vehicle_type) }}</span>
                                    </td>
                                    <td>{{ $vehicle->brand }} {{ $vehicle->model }} ({{ $vehicle->year }})</td>
                                    <td>{{ $vehicle->driver->name ?? 'Unassigned' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $vehicle->status === 'active' ? 'success' : ($vehicle->status === 'maintenance' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($vehicle->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $vehicle->capacity }} seats</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('transport.vehicles.show', $vehicle) }}" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('transport.vehicles.edit', $vehicle) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('transport.vehicles.destroy', $vehicle) }}" 
                                                  style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
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

                    <!-- Pagination -->
                    <div class="row mt-3">
                        <div class="col-12">
                            {{ $vehicles->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 