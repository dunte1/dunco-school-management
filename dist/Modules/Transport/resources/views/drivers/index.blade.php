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
                        <li class="breadcrumb-item active">Drivers</li>
                    </ol>
                </div>
                <h4 class="page-title">Drivers Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5 class="card-title">All Drivers</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('transport.drivers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add Driver
                            </a>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('transport.drivers.index') }}">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Search drivers..." value="{{ request('search') }}">
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
                                    <th>Name</th>
                                    <th>License Number</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Experience</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($drivers as $driver)
                                <tr>
                                    <td>
                                        <strong>{{ $driver->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $driver->email ?? 'No email' }}</small>
                                    </td>
                                    <td>
                                        {{ $driver->license_number }}
                                        <br>
                                        <small class="text-muted">Expires: {{ $driver->license_expiry->format('M d, Y') }}</small>
                                    </td>
                                    <td>{{ $driver->phone }}</td>
                                    <td>
                                        <span class="badge bg-{{ $driver->status === 'active' ? 'success' : ($driver->status === 'inactive' ? 'secondary' : 'danger') }}">
                                            {{ ucfirst($driver->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $driver->experience_years }} years</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('transport.drivers.show', $driver) }}" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('transport.drivers.edit', $driver) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('transport.drivers.destroy', $driver) }}" 
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
                                    <td colspan="6" class="text-center">No drivers found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row mt-3">
                        <div class="col-12">
                            {{ $drivers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 