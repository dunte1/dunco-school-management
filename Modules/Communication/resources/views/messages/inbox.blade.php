@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Inbox</h1>
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search messages...">
        <select name="filter" class="form-select">
            <option value="">All</option>
            <option value="unread" @if(request('filter')=='unread') selected @endif>Unread</option>
            <option value="starred" @if(request('filter')=='starred') selected @endif>Starred</option>
        </select>
        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
    </form>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="POST" action="{{ route('communication.bulkAction') }}">
    @csrf
    <div class="mb-2 d-flex gap-2">
        <button type="submit" name="action" value="read" class="btn btn-sm btn-outline-success">Mark as Read</button>
        <button type="submit" name="action" value="unread" class="btn btn-sm btn-outline-warning">Mark as Unread</button>
        <button type="submit" name="action" value="star" class="btn btn-sm btn-outline-info">Star</button>
        <button type="submit" name="action" value="unstar" class="btn btn-sm btn-outline-secondary">Unstar</button>
        <button type="submit" name="action" value="delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete selected messages?')">Delete</button>
    </div>
    @if($messages->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th></th>
                        <th>From</th>
                        <th>Subject & Preview</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $recipient)
                        <tr @if(!$recipient->is_read) style="font-weight:bold;" @endif>
                            <td><input type="checkbox" name="ids[]" value="{{ $recipient->id }}"></td>
                            <td>
                                <form method="POST" action="{{ route('communication.toggleStar', $recipient->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0" title="Star/Unstar">
                                        @if($recipient->is_starred)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-muted"></i>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td>{{ $recipient->message->sender->name ?? 'Unknown' }}</td>
                            <td>
                                <a href="{{ route('communication.message.show', $recipient->id) }}" class="text-decoration-none">
                                    <strong>{{ $recipient->message->subject ?? '(No Subject)' }}</strong><br>
                                    <span class="text-muted small">{{ \Illuminate\Support\Str::limit(strip_tags($recipient->message->body), 50) }}</span>
                                </a>
                            </td>
                            <td>{{ $recipient->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                @if($recipient->is_read)
                                    <span class="badge bg-success">Read</span>
                                @else
                                    <span class="badge bg-warning">Unread</span>
                                @endif
                            </td>
                            <td class="d-flex gap-1">
                                <form method="POST" action="{{ route('communication.markAsRead', $recipient->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Mark as Read"><i class="fas fa-envelope-open"></i></button>
                                </form>
                                <form method="POST" action="{{ route('communication.markAsUnread', $recipient->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Mark as Unread"><i class="fas fa-envelope"></i></button>
                                </form>
                                <form method="POST" action="{{ route('communication.delete', $recipient->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this message?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $messages->links() }}
    @else
        <p>No messages found.</p>
    @endif
</form>
@push('scripts')
<script>
    document.getElementById('select-all')?.addEventListener('change', function(e) {
        document.querySelectorAll('input[name="ids[]"]').forEach(cb => cb.checked = e.target.checked);
    });
</script>
@endpush
@endsection 