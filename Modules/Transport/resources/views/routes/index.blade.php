@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Routes</li>
                    </ol>
                </div>
                <h4 class="page-title">Transport Routes</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title">All Routes</h4>
                    <a href="{{ route('transport.routes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Route
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="{{ route('transport.routes.index') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2" 
                                       placeholder="Search routes..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Route Name</th>
                                    <th>Start Point</th>
                                    <th>End Point</th>
                                    <th>Stops</th>
                                    <th>Distance</th>
                                    <th>Status</th>
                                    <th>Vehicles</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($routes as $route)
                                <tr>
                                    <td>
                                        <a href="{{ route('transport.routes.show', $route) }}" class="text-body fw-bold">
                                            {{ $route->name }}
                                        </a>
                                    </td>
                                    <td>{{ $route->start_point }}</td>
                                    <td>{{ $route->end_point }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $route->stops->count() }} stops</span>
                                    </td>
                                    <td>{{ $route->distance ?? 0 }} km</td>
                                    <td>
                                        @if($route->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $route->vehicles->count() }} vehicles</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('transport.routes.show', $route) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('transport.routes.edit', $route) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('transport.routes.destroy', $route) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this route?')">
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
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-route fa-3x mb-3"></i>
                                            <h5>No routes found</h5>
                                            <p>Start by creating your first transport route.</p>
                                            <a href="{{ route('transport.routes.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Create Route
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($routes->hasPages())
                    <div class="row mt-3">
                        <div class="col-12">
                            {{ $routes->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 