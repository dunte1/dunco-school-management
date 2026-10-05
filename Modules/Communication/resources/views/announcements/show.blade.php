@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">{{ $announcement->title }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('communication.announcements.edit', $announcement) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-2"></i>Edit Announcement
        </a>
        <a href="{{ route('communication.announcements.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Announcements
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Announcement Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Title:</strong>
                        <p>{{ $announcement->title }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Type:</strong>
                        <p><span class="badge bg-info">{{ ucfirst($announcement->type) }}</span></p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Priority:</strong>
                        @php
                            $priorityColors = [
                                'low' => 'bg-secondary',
                                'normal' => 'bg-primary',
                                'high' => 'bg-warning',
                                'urgent' => 'bg-danger'
                            ];
                        @endphp
                        <p><span class="badge {{ $priorityColors[$announcement->priority] ?? 'bg-secondary' }}">{{ ucfirst($announcement->priority) }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        @if($announcement->isExpired())
                            <p><span class="badge bg-danger">Expired</span></p>
                        @elseif($announcement->isPublished())
                            <p><span class="badge bg-success">Published</span></p>
                        @else
                            <p><span class="badge bg-warning">Draft</span></p>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Content:</strong>
                    <div class="border rounded p-3 bg-light">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Published At:</strong>
                        <p>{{ $announcement->published_at ? $announcement->published_at->format('F j, Y \a\t g:i A') : 'Not published' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Expires At:</strong>
                        <p>{{ $announcement->expires_at ? $announcement->expires_at->format('F j, Y \a\t g:i A') : 'No expiry' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Announcement Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Created By:</strong>
                    <p>{{ $announcement->creator->name ?? 'Unknown' }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Created At:</strong>
                    <p>{{ $announcement->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Last Updated:</strong>
                    <p>{{ $announcement->updated_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Actions:</strong>
                    <div class="d-grid gap-2">
                        @if(!$announcement->isPublished())
                            <form method="POST" action="{{ route('communication.announcements.publish', $announcement) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-paper-plane me-2"></i>Publish Now
                                </button>
                            </form>
                        @endif
                        
                        <form method="POST" action="{{ route('communication.announcements.toggle-status', $announcement) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-toggle-{{ $announcement->is_active ? 'off' : 'on' }} me-2"></i>
                                {{ $announcement->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('communication.announcements.destroy', $announcement) }}" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-2"></i>Delete Announcement
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">0</h4>
                            <small class="text-muted">Views</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-0">0</h4>
                        <small class="text-muted">Reads</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
