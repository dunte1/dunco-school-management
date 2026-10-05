@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Notification Delivery Reports</h1>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Template</th>
                        <th>Channel</th>
                        <th>Recipient</th>
                        <th>Status</th>
                        <th>Response</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($logs ?? []) as $log)
                        <tr>
                            <td>{{ $log->sent_at?->toDayDateTimeString() }}</td>
                            <td>{{ $log->template->name ?? '-' }}</td>
                            <td>{{ strtoupper($log->channel) }}</td>
                            <td>{{ $log->recipient }}</td>
                            <td><span class="badge bg-{{ $log->status === 'sent' ? 'success' : 'danger' }}">{{ $log->status }}</span></td>
                            <td class="text-truncate" style="max-width:420px;">{{ Str::limit($log->response, 200) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-muted p-3">No delivery logs yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($logs))
            <div class="card-footer">{{ $logs->links() }}</div>
        @endif
    </div>
</div>
@endsection


