@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Broadcasts</h1>
    <a href="{{ route('communication.broadcasts.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Create Broadcast
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Manage Broadcasts</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Target</th>
                        <th>Status</th>
                        <th>Recipients</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($broadcasts as $broadcast)
                        <tr>
                            <td>
                                <strong>{{ $broadcast->title }}</strong>
                                @if($broadcast->scheduled_at)
                                    <br><small class="text-muted">Scheduled: {{ $broadcast->scheduled_at->format('M j, g:i A') }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $broadcast->type === 'emergency' ? 'danger' : ($broadcast->type === 'reminder' ? 'warning' : 'info') }}">
                                    {{ ucfirst($broadcast->type) }}
                                </span>
                            </td>
                            <td>{{ $broadcast->target_display }}</td>
                            <td>
                                @if($broadcast->sent_at)
                                    <span class="badge bg-success">Sent</span>
                                @elseif($broadcast->scheduled_at)
                                    <span class="badge bg-warning">Scheduled</span>
                                @else
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td>
                                @if($broadcast->sent_at)
                                    {{ $broadcast->recipients->count() }} recipients
                                    <br><small class="text-muted">{{ $broadcast->recipients->where('is_read', true)->count() }} read</small>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $broadcast->created_at->format('M j, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('communication.broadcasts.show', $broadcast->id) }}" class="btn btn-outline-primary">View</a>
                                    @if(!$broadcast->sent_at)
                                        <form action="{{ route('communication.broadcasts.send', $broadcast->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" onclick="return confirm('Send this broadcast?')">Send</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-bullhorn fa-2x text-muted mb-2"></i>
                                <p class="text-muted">No broadcasts yet. Create your first broadcast announcement!</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center">
            {{ $broadcasts->links() }}
        </div>
    </div>
</div>
@endsection 