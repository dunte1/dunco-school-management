@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-chart-line me-2"></i>System Monitoring</h3>
        <button class="btn btn-outline-secondary" onclick="location.reload()">
            <i class="fas fa-sync-alt me-1"></i>Refresh
        </button>
    </div>

    <div class="row g-4">
        <!-- System Metrics -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">System Metrics</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 mb-1">{{ $systemMetrics['cpu_usage'] }}%</div>
                                <small class="text-muted">CPU Usage</small>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-{{ $systemMetrics['cpu_usage'] > 80 ? 'danger' : ($systemMetrics['cpu_usage'] > 60 ? 'warning' : 'success') }}" 
                                         style="width: {{ $systemMetrics['cpu_usage'] }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 mb-1">{{ $systemMetrics['memory_usage'] }}%</div>
                                <small class="text-muted">Memory Usage</small>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-{{ $systemMetrics['memory_usage'] > 80 ? 'danger' : ($systemMetrics['memory_usage'] > 60 ? 'warning' : 'success') }}" 
                                         style="width: {{ $systemMetrics['memory_usage'] }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 mb-1">{{ $systemMetrics['disk_usage'] }}%</div>
                                <small class="text-muted">Disk Usage</small>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar bg-{{ $systemMetrics['disk_usage'] > 80 ? 'danger' : ($systemMetrics['disk_usage'] > 60 ? 'warning' : 'success') }}" 
                                         style="width: {{ $systemMetrics['disk_usage'] }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 mb-1">{{ $systemMetrics['active_users'] }}</div>
                                <small class="text-muted">Active Users</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Data -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Performance Metrics</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 mb-1">{{ $performanceData['avg_response_time'] }}ms</div>
                                <small class="text-muted">Avg Response Time</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 mb-1">{{ $performanceData['requests_per_minute'] }}</div>
                                <small class="text-muted">Requests/Min</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 mb-1">{{ $performanceData['error_rate'] }}%</div>
                                <small class="text-muted">Error Rate</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div class="h4 mb-1">{{ $performanceData['uptime'] }}</div>
                                <small class="text-muted">Uptime</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Logs -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Error Logs</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Level</th>
                                    <th>Message</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($errorLogs as $log)
                                <tr>
                                    <td>{{ $log['timestamp']->format('M d, Y H:i:s') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $log['level'] === 'ERROR' ? 'danger' : 'warning' }}">
                                            {{ $log['level'] }}
                                        </span>
                                    </td>
                                    <td>{{ $log['message'] }}</td>
                                    <td>{{ $log['count'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
