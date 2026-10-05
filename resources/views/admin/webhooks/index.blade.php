@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-link me-2"></i>Webhooks</h3>
        <button class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Webhook
        </button>
    </div>

    <div class="row g-4">
        @foreach($webhooks as $webhook)
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $webhook['name'] }}</h5>
                    <span class="badge bg-{{ $webhook['status'] === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($webhook['status']) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>URL:</strong>
                        <code class="d-block mt-1 p-2 bg-light rounded">{{ $webhook['url'] }}</code>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Events:</strong>
                        <div class="mt-1">
                            @foreach($webhook['events'] as $event)
                            <span class="badge bg-info me-1">{{ $event }}</span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted">Last Triggered</small>
                            <div>{{ $webhook['last_triggered'] ? $webhook['last_triggered']->format('M d, Y H:i') : 'Never' }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Success Rate</small>
                            <div class="d-flex align-items-center">
                                <div class="progress me-2" style="width: 60px; height: 6px;">
                                    <div class="progress-bar bg-{{ $webhook['success_rate'] >= 95 ? 'success' : ($webhook['success_rate'] >= 80 ? 'warning' : 'danger') }}" 
                                         style="width: {{ $webhook['success_rate'] }}%"></div>
                                </div>
                                <span>{{ $webhook['success_rate'] }}%</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <div class="btn-group w-100" role="group">
                            <button class="btn btn-sm btn-outline-primary">Edit</button>
                            <button class="btn btn-sm btn-outline-secondary">Test</button>
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection