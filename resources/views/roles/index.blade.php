@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Roles & Permissions</h2>
            <p class="text-muted">Manage access levels across the school system</p>
        </div>
        <button class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Role
        </button>
    </div>

    <!-- Summary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary rounded p-3 me-3">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['total_roles'] }}</h3>
                        <p class="text-muted mb-0">Total Roles</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success rounded p-3 me-3">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                    <div>
                        <h3 class="mb-0">{{ $stats['active_roles'] }}</h3>
                        <p class="text-muted mb-0">Active Roles</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" class="form-control ps-5" placeholder="Search roles...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Status</option>
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option>All Modules</option>
                        <option>Academic</option>
                        <option>Finance</option>
                        <option>HR</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary w-100">Clear</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Roles Table -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Roles List</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Role Name</th>
                                    <th>Display Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Users</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary rounded-circle p-2 me-2">
                                                <i class="fas fa-user-shield text-white"></i>
                                            </div>
                                            <strong>{{ $role['name'] }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $role['display_name'] }}</td>
                                    <td>{{ Str::limit($role['description'], 50) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $role['status'] === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($role['status']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $role['users_count'] }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions Panel -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Role Permissions</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="fas fa-shield-alt fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Select a role to manage permissions</h6>
                        <p class="text-muted small">Click on any role from the list to view and edit its permissions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
