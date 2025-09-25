@extends('layouts.app')

@section('title', 'Proctoring Dashboard')

@section('content')
<style>
    .proctoring-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }
    .proctoring-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1a237e;
        letter-spacing: 1px;
    }
    .proctoring-table {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 8px rgba(30,167,255,0.04);
        border: 1px solid #e3e9f7;
        overflow: hidden;
    }
    .proctoring-table th {
        background: #f3f6fa;
        font-weight: 700;
        color: #22304a;
        font-size: 1.04rem;
        border-bottom: 1px solid #e3e9f7;
        letter-spacing: 0.5px;
        padding: 1rem 0.7rem;
        text-align: left;
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .proctoring-table td {
        vertical-align: middle;
        font-size: 1.03rem;
        background: transparent;
        color: #22304a;
        padding: 0.9rem 0.7rem;
    }
    .proctoring-table tbody tr:hover {
        background: #f5faff;
        transition: background 0.2s;
    }
    .proctoring-severity {
        border-radius: 0.7rem;
        padding: 0.3rem 1rem;
        font-size: 0.97rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    .proctoring-severity.low { background: #e3f0ff; color: #2563eb; }
    .proctoring-severity.medium { background: #fff7e6; color: #ffb300; }
    .proctoring-severity.high { background: #ffe4e6; color: #e53935; }
    .proctoring-severity.critical { background: #f87171; color: #fff; }
    .proctoring-status {
        border-radius: 0.7rem;
        padding: 0.3rem 1rem;
        font-size: 0.97rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    .proctoring-status.resolved { background: #dcfce7; color: #16a34a; }
    .proctoring-status.unresolved { background: #fef3c7; color: #d97706; }
    .proctoring-action-icons a {
        color: #1ea7ff;
        margin-right: 0.7rem;
        font-size: 1.2rem;
        transition: color 0.18s, transform 0.18s;
    }
    .proctoring-action-icons a:hover {
        color: #2563eb;
        transform: scale(1.15);
    }
</style>
<div class="container-xl py-4">
    <div class="proctoring-header">
        <div class="proctoring-title">Proctoring Dashboard</div>
        <div class="text-muted">Monitor and resolve exam proctoring events</div>
    </div>
    <div class="proctoring-table mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $log->event_type)) }}</td>
                        <td><span class="proctoring-severity {{ strtolower($log->severity) }}">{{ strtoupper($log->severity) }}</span></td>
                        <td>
                            <span class="proctoring-status {{ $log->is_resolved ? 'resolved' : 'unresolved' }}">
                                {{ $log->is_resolved ? 'Resolved' : 'Unresolved' }}
                            </span>
                        </td>
                        <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                        <td class="proctoring-action-icons">
                            <a href="#" title="View"><i class="fas fa-eye"></i></a>
                            @if(!$log->is_resolved)
                            <a href="#" title="Resolve"><i class="fas fa-check"></i></a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No proctoring events found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 