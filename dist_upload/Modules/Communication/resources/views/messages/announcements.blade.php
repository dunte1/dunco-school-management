@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Announcements</h1>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4">
    @forelse($announcements as $recipient)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="badge bg-{{ $recipient->broadcast->type === 'emergency' ? 'danger' : ($recipient->broadcast->type === 'reminder' ? 'warning' : 'info') }}">
                        {{ ucfirst($recipient->broadcast->type) }}
                    </span>
                    @if(!$recipient->is_read)
                        <span class="badge bg-primary">New</span>
                    @endif
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $recipient->broadcast->title }}</h5>
                    <p class="card-text text-muted">
                        {{ \Illuminate\Support\Str::limit($recipient->broadcast->content, 100) }}
                    </p>
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-user me-1"></i>
                            {{ $recipient->broadcast->creator->name }}
                        </small>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            {{ $recipient->broadcast->sent_at ? $recipient->broadcast->sent_at->format('M j, g:i A') : 'Not sent yet' }}
                        </small>
                    </div>
                    <a href="{{ route('communication.announcements.show', $recipient->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-1"></i>Read More
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-bell fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Announcements</h4>
                <p class="text-muted">You haven't received any announcements yet.</p>
            </div>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $announcements->links() }}
</div>
@endsection 