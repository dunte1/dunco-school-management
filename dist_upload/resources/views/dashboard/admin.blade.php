@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Admin Dashboard</h1>
                <div class="text-muted">System Overview</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_students'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Teachers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_teachers'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Schools</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_schools'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-school fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('core.users.index') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-users me-2"></i>Users
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('core.roles.index') }}" class="btn btn-success btn-block">
                                <i class="fas fa-user-shield me-2"></i>Roles
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('core.permissions.index') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-key me-2"></i>Permissions
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('core.schools.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-school me-2"></i>Schools
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('core.audit_logs.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-clipboard-list me-2"></i>Audit Logs
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('settings.index') }}" class="btn btn-dark btn-block">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent System Activities</h6>
                </div>
                <div class="card-body">
                    @if($stats['recent_activities']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['recent_activities'] as $activity)
                                    <tr>
                                        <td>{{ $activity->user->name ?? 'System' }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $activity->action }}</span>
                                        </td>
                                        <td>{{ $activity->description }}</td>
                                        <td>{{ $activity->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No recent activities found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 