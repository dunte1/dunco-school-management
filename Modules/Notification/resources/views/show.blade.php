@extends('layouts.app')

@section('title', 'Notification Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-envelope-open me-2"></i>Notification #{{ $notification->id }}</h4>
        <div>
            @if($notification->status === 'failed')
                <form action="{{ route('notification.send', $notification->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success me-2"><i class="fas fa-redo me-1"></i> Retry</button>
                </form>
            @endif
            <a href="{{ route('notification.edit', $notification->id) }}" class="btn btn-warning me-2"><i class="fas fa-edit me-1"></i> Edit</a>
            <a href="{{ route('notification.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0">Notification Details</h5></div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="180">Subject</th>
                            <td>{{ $notification->subject }}</td>
                        </tr>
                        <tr>
                            <th>Recipient Email</th>
                            <td>{{ $notification->recipient_email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Recipient Phone</th>
                            <td>{{ $notification->recipient_phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Channel</th>
                            <td><span class="badge bg-info">{{ ucfirst($notification->channel) }}</span></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $badge = match($notification->status) {
                                        'sent' => 'bg-success',
                                        'failed' => 'bg-danger',
                                        'pending' => 'bg-warning text-dark',
                                        'read' => 'bg-primary',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">{{ ucfirst($notification->status) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Template</th>
                            <td>{{ $notification->template->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Error</th>
                            <td class="text-danger">{{ $notification->error_message ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Sent At</th>
                            <td>{{ $notification->sent_at ? $notification->sent_at->format('M d, Y h:i A') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Read At</th>
                            <td>{{ $notification->read_at ? $notification->read_at->format('M d, Y h:i A') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td>{{ $notification->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0">Message Preview</h5></div>
                <div class="card-body">
                    <div class="border rounded p-3 bg-light">
                        <strong>{{ $notification->subject }}</strong>
                        <hr>
                        <div style="white-space: pre-wrap;">{{ $notification->body }}</div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header"><h5 class="mb-0">Actions</h5></div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('notification.edit', $notification->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    @if($notification->status === 'failed')
                        <form action="{{ route('notification.send', $notification->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-success w-100"><i class="fas fa-redo me-1"></i> Retry Send</button>
                        </form>
                    @endif
                    <form action="{{ route('notification.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger w-100"><i class="fas fa-trash me-1"></i> Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
