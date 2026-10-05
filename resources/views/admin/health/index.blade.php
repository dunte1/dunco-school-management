@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-heartbeat me-2"></i>System Health</h3>
        <button class="btn btn-outline-secondary" onclick="location.reload()">
            <i class="fas fa-sync-alt me-1"></i>Refresh
        </button>
    </div>

    <div class="row g-4">
        @foreach($healthChecks as $service => $check)
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-capitalize">{{ str_replace('_', ' ', $service) }}</h5>
                    <span class="badge bg-{{ $check['status'] === 'healthy' ? 'success' : ($check['status'] === 'warning' ? 'warning' : 'danger') }}">
                        {{ ucfirst($check['status']) }}
                    </span>
                </div>
                <div class="card-body">
                    <p class="mb-2">{{ $check['message'] }}</p>
                    @if($check['response_time'])
                    <small class="text-muted">Response Time: {{ $check['response_time'] }}</small>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">System Status Summary</h5>
                </div>
                <div class="card-body">
                    @php
                        $healthyCount = collect($healthChecks)->where('status', 'healthy')->count();
                        $totalCount = count($healthChecks);
                        $healthPercentage = $totalCount > 0 ? round(($healthyCount / $totalCount) * 100, 1) : 0;
                    @endphp
                    
                    <div class="text-center">
                        <div class="h2 mb-2">{{ $healthPercentage }}%</div>
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-{{ $healthPercentage >= 90 ? 'success' : ($healthPercentage >= 70 ? 'warning' : 'danger') }}" 
                                 style="width: {{ $healthPercentage }}%"></div>
                        </div>
                        <p class="text-muted">{{ $healthyCount }} of {{ $totalCount }} services are healthy</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
