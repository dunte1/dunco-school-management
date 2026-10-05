@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Announcements</h1>
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                <option value="academic" {{ request('type') == 'academic' ? 'selected' : '' }}>Academic</option>
                <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>Event</option>
                <option value="emergency" {{ request('type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
            </select>
            <select name="priority" class="form-select">
                <option value="">All Priorities</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
            </select>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search announcements...">
            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
        </form>
        <a href="{{ route('communication.announcements.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Announcement
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($announcements->count())
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th>Expires</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($announcements as $announcement)
                    <tr>
                        <td>
                            <strong>{{ $announcement->title }}</strong>
                            <br>
                            <small class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($announcement->content), 100) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($announcement->type) }}</span>
                        </td>
                        <td>
                            @php
                                $priorityColors = [
                                    'low' => 'bg-secondary',
                                    'normal' => 'bg-primary',
                                    'high' => 'bg-warning',
                                    'urgent' => 'bg-danger'
                                ];
                            @endphp
                            <span class="badge {{ $priorityColors[$announcement->priority] ?? 'bg-secondary' }}">
                                {{ ucfirst($announcement->priority) }}
                            </span>
                        </td>
                        <td>
                            @if($announcement->isExpired())
                                <span class="badge bg-danger">Expired</span>
                            @elseif($announcement->isPublished())
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-warning">Draft</span>
                            @endif
                        </td>
                        <td>{{ $announcement->published_at ? $announcement->published_at->format('Y-m-d H:i') : 'Not published' }}</td>
                        <td>{{ $announcement->expires_at ? $announcement->expires_at->format('Y-m-d H:i') : 'No expiry' }}</td>
                        <td>{{ $announcement->creator->name ?? 'Unknown' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('communication.announcements.show', $announcement) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('communication.announcements.edit', $announcement) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(!$announcement->isPublished())
                                    <form method="POST" action="{{ route('communication.announcements.publish', $announcement) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('communication.announcements.toggle-status', $announcement) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-toggle-{{ $announcement->is_active ? 'off' : 'on' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('communication.announcements.destroy', $announcement) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $announcements->links() }}
@else
    <div class="text-center py-5">
        <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
        <h4>No announcements found</h4>
        <p class="text-muted">Create your first announcement to get started.</p>
        <a href="{{ route('communication.announcements.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Announcement
        </a>
    </div>
@endif
@endsection
