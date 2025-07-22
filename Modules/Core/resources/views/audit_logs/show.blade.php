<x-core::layouts.master>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Audit Log Details</h1>
            <a href="{{ route('core.audit-logs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Audit Logs
            </a>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Log Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>ID:</strong></div>
                            <div class="col-md-9">{{ $log->id }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>User:</strong></div>
                            <div class="col-md-9">{{ optional($log->user)->name ?? 'System' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Action:</strong></div>
                            <div class="col-md-9">
                                @switch($log->action)
                                    @case('created')
                                        <span class="badge bg-success">Created</span>
                                        @break
                                    @case('updated')
                                        <span class="badge bg-warning">Updated</span>
                                        @break
                                    @case('deleted')
                                        <span class="badge bg-danger">Deleted</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($log->action) }}</span>
                                @endswitch
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Model:</strong></div>
                            <div class="col-md-9">{{ $log->model_type }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Record ID:</strong></div>
                            <div class="col-md-9">{{ $log->model_id }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>IP Address:</strong></div>
                            <div class="col-md-9">{{ $log->ip_address ?? 'N/A' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Created:</strong></div>
                            <div class="col-md-9">{{ $log->created_at ? $log->created_at->format('M d, Y H:i:s') : 'Unknown' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Changes</h5>
                    </div>
                    <div class="card-body">
                        @if($log->changes)
                            <div class="mb-3">
                                <h6>Old Values:</h6>
                                <pre class="bg-light p-2 rounded">{{ json_encode($log->changes['old'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                            </div>
                            <div class="mb-3">
                                <h6>New Values:</h6>
                                <pre class="bg-light p-2 rounded">{{ json_encode($log->changes['new'] ?? [], JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @else
                            <p class="text-muted">No changes recorded</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-core::layouts.master> 