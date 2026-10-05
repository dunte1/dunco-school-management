@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-server me-2"></i>System Information</h3>
    </div>

    <div class="row g-4">
        <!-- Environment Information -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Environment</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>Application Name:</strong></td>
                                    <td>{{ $systemInfo['environment']['app_name'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Environment:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $systemInfo['environment']['app_env'] === 'production' ? 'success' : 'warning' }}">
                                            {{ $systemInfo['environment']['app_env'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Debug Mode:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $systemInfo['environment']['app_debug'] === 'Yes' ? 'danger' : 'success' }}">
                                            {{ $systemInfo['environment']['app_debug'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>App URL:</strong></td>
                                    <td>{{ $systemInfo['environment']['app_url'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Timezone:</strong></td>
                                    <td>{{ $systemInfo['environment']['timezone'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Locale:</strong></td>
                                    <td>{{ $systemInfo['environment']['locale'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Server Information -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-server me-2"></i>Server</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>PHP Version:</strong></td>
                                    <td>{{ $systemInfo['php_version'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Laravel Version:</strong></td>
                                    <td>{{ $systemInfo['laravel_version'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Operating System:</strong></td>
                                    <td>{{ $systemInfo['server']['os'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Server Software:</strong></td>
                                    <td>{{ $systemInfo['server']['server_software'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Memory Limit:</strong></td>
                                    <td>{{ $systemInfo['server']['memory_limit'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Max Execution Time:</strong></td>
                                    <td>{{ $systemInfo['server']['max_execution_time'] }} seconds</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Information -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-database me-2"></i>Database</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>Database Name:</strong></td>
                                    <td>{{ $systemInfo['database']['name'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Driver:</strong></td>
                                    <td>{{ $systemInfo['database']['driver'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Host:</strong></td>
                                    <td>{{ $systemInfo['database']['host'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Port:</strong></td>
                                    <td>{{ $systemInfo['database']['port'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $systemInfo['database']['status'] === 'Connected' ? 'success' : 'danger' }}">
                                            {{ $systemInfo['database']['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Storage Information -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-hdd me-2"></i>Storage</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>Total Space:</strong></td>
                                    <td>{{ $systemInfo['storage']['total_space'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Used Space:</strong></td>
                                    <td>{{ $systemInfo['storage']['used_space'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Free Space:</strong></td>
                                    <td>{{ $systemInfo['storage']['free_space'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Usage:</strong></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $systemInfo['storage']['usage_percentage'] > 80 ? 'danger' : ($systemInfo['storage']['usage_percentage'] > 60 ? 'warning' : 'success') }}" 
                                                 style="width: {{ $systemInfo['storage']['usage_percentage'] }}%">
                                                {{ $systemInfo['storage']['usage_percentage'] }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cache Information -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Cache</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>Driver:</strong></td>
                                    <td>{{ $systemInfo['cache']['driver'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Prefix:</strong></td>
                                    <td>{{ $systemInfo['cache']['prefix'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $systemInfo['cache']['status'] === 'Working' ? 'success' : 'warning' }}">
                                            {{ $systemInfo['cache']['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modules Information -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-puzzle-piece me-2"></i>Modules</h5>
                </div>
                <div class="card-body">
                    @if(count($systemInfo['modules']) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Module Name</th>
                                        <th>Version</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($systemInfo['modules'] as $module)
                                    <tr>
                                        <td><strong>{{ $module['name'] }}</strong></td>
                                        <td>{{ $module['version'] }}</td>
                                        <td>{{ $module['description'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $module['enabled'] ? 'success' : 'danger' }}">
                                                {{ $module['enabled'] ? 'Enabled' : 'Disabled' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-puzzle-piece fa-2x mb-2"></i>
                            <p>No modules found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
