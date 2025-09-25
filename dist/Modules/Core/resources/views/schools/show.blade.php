@extends('core::layouts.master')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>School Details</h1>
            <div>
                <a href="{{ route('core.schools.edit', $school->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit School
                </a>
                <a href="{{ route('core.schools.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Schools
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-school"></i> School Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Name:</strong></div>
                            <div class="col-md-9">{{ $school->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Code:</strong></div>
                            <div class="col-md-9">{{ $school->code }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Motto:</strong></div>
                            <div class="col-md-9">{{ $school->motto ?? 'No motto set' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Domain:</strong></div>
                            <div class="col-md-9">{{ $school->domain }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Theme:</strong></div>
                            <div class="col-md-9">
                                <span class="badge bg-{{ $school->theme }}">{{ ucfirst($school->theme) }}</span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Status:</strong></div>
                            <div class="col-md-9">
                                @if($school->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Created:</strong></div>
                            <div class="col-md-9">{{ $school->created_at->format('F j, Y \a\t g:i A') }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Last Updated:</strong></div>
                            <div class="col-md-9">{{ $school->updated_at->format('F j, Y \a\t g:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-image"></i> School Logo
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        @if($school->logo)
                            <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="img-fluid rounded" style="max-height: 200px;">
                        @else
                            <div class="bg-light rounded p-4">
                                <i class="fas fa-school fa-3x text-muted"></i>
                                <p class="text-muted mt-2">No logo uploaded</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar"></i> Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <h4 class="text-primary">{{ $school->users->count() }}</h4>
                                <small class="text-muted">Users</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success">{{ $school->auditLogs->count() }}</h4>
                                <small class="text-muted">Audit Logs</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($school->users->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users"></i> School Users ({{ $school->users->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Roles</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($school->users->take(10) as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-secondary">{{ $role->display_name ?? $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $user->created_at->format('M j, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($school->users->count() > 10)
                            <div class="text-center mt-3">
                                <small class="text-muted">Showing first 10 users of {{ $school->users->count() }} total</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection 