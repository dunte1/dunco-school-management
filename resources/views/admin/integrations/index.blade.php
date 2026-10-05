@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-plug me-2"></i>Integrations</h3>
        <button class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Add Integration
        </button>
    </div>

    <div class="row g-4">
        @foreach($integrations as $integration)
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $integration['name'] }}</h5>
                    <span class="badge bg-{{ $integration['status'] === 'connected' ? 'success' : 'secondary' }}">
                        {{ ucfirst($integration['status']) }}
                    </span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">{{ $integration['description'] }}</p>
                    
                    <div class="mb-3">
                        <small class="text-muted">Last Sync:</small>
                        <div>{{ $integration['last_sync'] ? $integration['last_sync']->format('M d, Y H:i') : 'Never' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Sync Frequency:</small>
                        <div>{{ $integration['sync_frequency'] }}</div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        @if($integration['status'] === 'connected')
                            <button class="btn btn-sm btn-outline-primary">Configure</button>
                            <button class="btn btn-sm btn-outline-secondary">Sync Now</button>
                            <button class="btn btn-sm btn-outline-danger">Disconnect</button>
                        @else
                            <button class="btn btn-sm btn-primary">Connect</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
