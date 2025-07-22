@extends('core::layouts.master')

@section('title', 'User Details')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>User Details</h1>
        <div>
            <a href="{{ route('core.users.edit', $user->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit User
            </a>
            <a href="{{ route('core.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user"></i> User Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Name:</strong></div>
                        <div class="col-md-9">{{ $user->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Email:</strong></div>
                        <div class="col-md-9">{{ $user->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>School:</strong></div>
                        <div class="col-md-9">{{ $user->school->name ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Email Verified:</strong></div>
                        <div class="col-md-9">
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Yes ({{ $user->email_verified_at->format('F j, Y') }})</span>
                            @else
                                <span class="badge bg-warning">No</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Created:</strong></div>
                        <div class="col-md-9">{{ $user->created_at->format('F j, Y \a\t g:i A') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Last Updated:</strong></div>
                        <div class="col-md-9">{{ $user->updated_at->format('F j, Y \a\t g:i A') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3"><strong>Last Login:</strong></div>
                        <div class="col-md-9">
                            @if($user->last_login_at)
                                {{ $user->last_login_at->format('F j, Y \a\t g:i A') }}
                            @else
                                <span class="text-muted">Never</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-tag"></i> Roles ({{ $user->roles->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->roles->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($user->roles as $role)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $role->display_name ?? $role->name }}</strong>
                                        @if($role->description)
                                            <br><small class="text-muted">{{ $role->description }}</small>
                                        @endif
                                    </div>
                                    @if($role->is_system)
                                        <span class="badge bg-primary">System</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No roles assigned to this user.</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-key"></i> Permissions
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $userPermissions = $user->roles->flatMap->permissions->unique('id');
                    @endphp
                    @if($userPermissions->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($userPermissions->take(5) as $permission)
                                <div class="list-group-item">
                                    <strong>{{ $permission->display_name ?? $permission->name }}</strong>
                                    @if($permission->module)
                                        <br><small class="text-muted">{{ $permission->module }}</small>
                                    @endif
                                </div>
                            @endforeach
                            @if($userPermissions->count() > 5)
                                <div class="list-group-item text-center">
                                    <small class="text-muted">+{{ $userPermissions->count() - 5 }} more permissions</small>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">No permissions assigned to this user.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 