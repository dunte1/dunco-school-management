@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Notifications</h2>
    <ul class="list-group mb-3">
        @forelse($notifications as $notification)
            <li class="list-group-item">
                {{ $notification->data['message'] ?? $notification->type }}
                <span class="badge bg-{{ $notification->read_at ? 'secondary' : 'primary' }} float-end">
                    {{ $notification->read_at ? 'Read' : 'Unread' }}
                </span>
            </li>
        @empty
            <li class="list-group-item text-muted">No notifications found.</li>
        @endforelse
    </ul>
    {{ $notifications->links() }}
</div>
@endsection 