@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Core Settings</h1>
        <div class="text-muted">System configuration and management</div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Schools</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\School::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-school fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\User::count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Roles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ class_exists(\Spatie\Permission\Models\Role::class) ? \Spatie\Permission\Models\Role::count() : 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tag fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Permissions</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ class_exists(\Spatie\Permission\Models\Permission::class) ? \Spatie\Permission\Models\Permission::count() : 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-key fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Links</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/schools') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-school me-2"></i>Manage Schools
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/users') }}" class="btn btn-success btn-block">
                                <i class="fas fa-users me-2"></i>Manage Users
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/roles') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-user-tag me-2"></i>Manage Roles
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/permissions') }}" class="btn btn-info btn-block">
                                <i class="fas fa-key me-2"></i>Manage Permissions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">System Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>Application:</strong> {{ config('app.name', 'School Management') }}</p>
                    <p><strong>Environment:</strong> {{ ucfirst(config('app.env', 'production')) }}</p>
                    <p><strong>PHP Version:</strong> {{ PHP_VERSION }}</p>
                    <p><strong>Laravel Version:</strong> {{ app()->version() }}</p>
                    <p><strong>Modules Loaded:</strong> {{ count(app('modules')->getModules()) ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
