@foreach($recentLogs as $log)
    <div class="mb-3 p-3 rounded-xl @if($log->severity === 'critical') bg-danger text-white @elseif($log->severity === 'high') bg-warning text-dark @else bg-light @endif">
        <div class="fw-bold">
            {{ $log->attempt->student->name ?? 'Unknown Student' }}
            <span class="text-muted small">({{ $log->attempt->exam->name ?? 'Unknown Exam' }})</span>
        </div>
        <div class="small">
            <span class="badge bg-secondary">{{ strtoupper($log->event_type) }}</span>
            <span class="ms-2">{{ $log->description }}</span>
        </div>
        <div class="small">
            <span class="badge @if($log->severity === 'critical') bg-danger @elseif($log->severity === 'high') bg-warning text-dark @else bg-info @endif">
                {{ ucfirst($log->severity) }}
            </span>
            <span class="ms-2 text-muted">{{ $log->created_at->diffForHumans() }}</span>
        </div>
    </div>
@endforeach
@if($recentLogs->isEmpty())
    <div class="text-muted">No recent proctoring alerts.</div>
@endif 