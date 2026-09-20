@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Dashboard</h1>
                <div class="text-muted">Welcome back, {{ Auth::user()->name }}!</div>
            </div>
        </div>
    </div>

    <!-- Accessible Modules -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Available Modules</h6>
                </div>
                <div class="card-body">
                    @if($accessibleModules && count($accessibleModules) > 0)
                        <div class="row">
                            @foreach($accessibleModules as $module)
                            <div class="col-md-3 mb-3">
                                <div class="card border-left-primary shadow h-100">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    {{ $module['name'] ?? 'Module' }}
                                                </div>
                                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                                    {{ $module['description'] ?? 'No description available' }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-cube fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                        @if(isset($module['route']))
                                        <div class="mt-3">
                                            <a href="{{ route($module['route']) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-arrow-right me-1"></i>Access
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No modules available</h5>
                            <p class="text-muted">You don't have access to any modules at the moment.</p>
                            <p class="text-muted">Please contact your administrator to get the necessary permissions.</p>
                        </div>
                    @endif
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
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-block" >
                                <i class="fas fa-user me-2"></i>My Profile
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ url('/notifications') }}" class="btn btn-success btn-block" >
                                <i class="fas fa-comments me-2"></i>Messages
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('settings.global') }}" class="btn btn-warning btn-block" >
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ url('/help') }}" class="btn btn-info btn-block" >
                                <i class="fas fa-question-circle me-2"></i>Help
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No recent activity</h5>
                        <p class="text-muted">Your recent activities will appear here once you start using the system.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>Role:</strong> 
                        @try
                            @if(Auth::user()->roles && Auth::user()->roles->count() > 0)
                                @foreach(Auth::user()->roles as $role)
                                    <span class="badge bg-primary me-1">{{ $role->display_name ?? $role->name }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary">No roles assigned</span>
                            @endif
                        @catch(Exception $e)
                            <span class="badge bg-secondary">Role information unavailable</span>
                        @endtry
                    </p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ Auth::user()->is_active ? 'success' : 'danger' }}">
                            {{ Auth::user()->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                    <p><strong>Member Since:</strong> {{ Auth::user()->created_at ? \Carbon\Carbon::parse(Auth::user()->created_at)->format('M d, Y') : 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
