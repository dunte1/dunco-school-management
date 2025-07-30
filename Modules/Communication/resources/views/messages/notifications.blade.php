@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Notification Center</h1>
    <div class="d-flex gap-2">
        <form action="{{ route('communication.notifications.markAllRead') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm">Mark All Read</button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="type" class="form-label">Category</label>
                <select name="type" id="type" class="form-select">
                    <option value="">All Categories</option>
                    <option value="academic" @if(request('type') == 'academic') selected @endif>Academic</option>
                    <option value="finance" @if(request('type') == 'finance') selected @endif>Finance</option>
                    <option value="events" @if(request('type') == 'events') selected @endif>Events</option>
                    <option value="system" @if(request('type') == 'system') selected @endif>System</option>
                    <option value="communication" @if(request('type') == 'communication') selected @endif>Communication</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">All</option>
                    <option value="unread" @if(request('status') == 'unread') selected @endif>Unread</option>
                    <option value="read" @if(request('status') == 'read') selected @endif>Read</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block w-100">Filter</button>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <a href="{{ route('communication.notifications') }}" class="btn btn-outline-secondary d-block w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<!-- Notifications List -->
<div class="notifications-list">
    @forelse($notifications as $notification)
        <div class="card mb-3 notification-item @if(!$notification->read_at) border-primary @endif" data-id="{{ $notification->id }}">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 me-3">
                        <i class="{{ $notification->icon }} fa-2x text-{{ $notification->color }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <h6 class="card-title mb-1">{{ $notification->title }}</h6>
                            <div class="d-flex gap-1">
                                @if(!$notification->read_at)
                                    <button class="btn btn-sm btn-outline-primary mark-read-btn" data-id="{{ $notification->id }}">
                                        <i class="fas fa-check"></i>
                                    </button>
                                @endif
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <p class="card-text text-muted mb-2">
                            @if(isset($notification->data['message']))
                                {{ $notification->data['message'] }}
                            @endif
                        </p>
                        <div class="d-flex gap-2">
                            <span class="badge bg-{{ $notification->color }}">{{ ucfirst($notification->type) }}</span>
                            @if(!$notification->read_at)
                                <span class="badge bg-primary">New</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="fas fa-bell fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No Notifications</h4>
            <p class="text-muted">You're all caught up! No new notifications.</p>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $notifications->links() }}
</div>

@push('scripts')
<script>
// Mark notification as read
document.querySelectorAll('.mark-read-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const notificationId = this.dataset.id;
        const notificationItem = this.closest('.notification-item');
        
        fetch(`/communication/notifications/${notificationId}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                notificationItem.classList.remove('border-primary');
                this.remove();
                updateNotificationCount();
            }
        });
    });
});

// Update notification count
function updateNotificationCount() {
    fetch('/communication/notifications/count')
        .then(response => response.json())
        .then(data => {
            const countBadge = document.getElementById('notification-count');
            if (data.count > 0) {
                countBadge.textContent = data.count;
                countBadge.style.display = 'inline';
            } else {
                countBadge.style.display = 'none';
            }
        });
}

// Update count on page load
updateNotificationCount();

// Update count every 30 seconds
setInterval(updateNotificationCount, 30000);
</script>
@endpush
@endsection 