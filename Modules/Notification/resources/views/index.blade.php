@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-bell me-2"></i>Notification Center</h4>
        <a href="{{ route('notification.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Send Notification</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h3 class="text-primary">{{ $stats['total'] }}</h3>
                <small class="text-muted">Total Notifications</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h3 class="text-success">{{ $stats['sent'] }}</h3>
                <small class="text-muted">Sent</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h3 class="text-danger">{{ $stats['failed'] }}</h3>
                <small class="text-muted">Failed</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h3 class="text-warning">{{ $stats['pending'] }}</h3>
                <small class="text-muted">Pending</small>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Channel</label>
                    <select name="channel" class="form-select">
                        <option value="">All</option>
                        <option value="email" {{ request('channel') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ request('channel') == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="both" {{ request('channel') == 'both' ? 'selected' : '' }}>Both</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('notification.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Recipient</th>
                        <th>Channel</th>
                        <th>Status</th>
                        <th>Sent At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notif)
                    <tr>
                        <td>{{ $notif->id }}</td>
                        <td>{{ Str::limit($notif->subject, 30) }}</td>
                        <td>{{ $notif->recipient_email ?? $notif->recipient_phone ?? '-' }}</td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($notif->channel) }}</span>
                        </td>
                        <td>
                            @php
                                $badge = match($notif->status) {
                                    'sent' => 'bg-success',
                                    'failed' => 'bg-danger',
                                    'pending' => 'bg-warning text-dark',
                                    'read' => 'bg-primary',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $badge }}">{{ ucfirst($notif->status) }}</span>
                        </td>
                        <td>{{ $notif->sent_at ? $notif->sent_at->format('M d, Y h:i A') : '-' }}</td>
                        <td>
                            <a href="{{ route('notification.show', $notif->id) }}" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-eye"></i></a>
                            @if($notif->status === 'failed')
                                <form action="{{ route('notification.send', $notif->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success me-1"><i class="fas fa-redo"></i></button>
                                </form>
                            @endif
                            <form action="{{ route('notification.destroy', $notif->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No notifications found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $notifications->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
