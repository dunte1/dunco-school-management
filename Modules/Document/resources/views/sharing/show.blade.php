@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Share Details</h2>
        <div>
            <a href="{{ route('document.sharing.edit', $share) }}" class="btn btn-warning me-2">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <a href="{{ route('document.sharing.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Sharing
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Document</h6>
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-file-{{ $share->document->file_type == 'pdf' ? 'pdf' : 'alt' }} me-3 text-primary fa-2x"></i>
                                <div>
                                    <h5 class="mb-0">{{ $share->document->title }}</h5>
                                    <small class="text-muted">{{ $share->document->file_name }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Permission Level</h6>
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-{{ $share->permission == 'view' ? 'eye' : ($share->permission == 'comment' ? 'comment' : 'edit') }} me-3 text-{{ $share->permission == 'view' ? 'info' : ($share->permission == 'comment' ? 'warning' : 'success') }} fa-2x"></i>
                                <div>
                                    <h5 class="mb-0">{{ ucfirst($share->permission) }}</h5>
                                    <small class="text-muted">
                                        @if($share->permission == 'view')
                                            Can only view the document
                                        @elseif($share->permission == 'comment')
                                            Can view and add comments
                                        @else
                                            Can modify the document
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Shared With</h6>
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                @if($share->shared_with_user)
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                        {{ substr($share->shared_with_user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h5 class="mb-0">{{ $share->shared_with_user->name }}</h5>
                                        <small class="text-muted">{{ $share->shared_with_user->email }}</small>
                                    </div>
                                @elseif($share->shared_with_group)
                                    <i class="fas fa-users me-3 text-info fa-2x"></i>
                                    <div>
                                        <h5 class="mb-0">{{ $share->shared_with_group->name }}</h5>
                                        <small class="text-muted">Group</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Status</h6>
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-{{ $share->is_active ? 'check-circle' : 'times-circle' }} me-3 text-{{ $share->is_active ? 'success' : 'danger' }} fa-2x"></i>
                                <div>
                                    <h5 class="mb-0">{{ $share->is_active ? 'Active' : 'Inactive' }}</h5>
                                    <small class="text-muted">
                                        @if($share->is_active)
                                            This share is currently active
                                        @else
                                            This share has been disabled
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($share->message)
                    <div class="mb-4">
                        <h6>Message</h6>
                        <div class="p-3 bg-light rounded">
                            <p class="mb-0">{{ $share->message }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Created</h6>
                            <p class="text-muted">{{ $share->created_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Last Updated</h6>
                            <p class="text-muted">{{ $share->updated_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>

                    @if($share->expires_at)
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Expires</h6>
                            <p class="text-muted">{{ \Carbon\Carbon::parse($share->expires_at)->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Time Remaining</h6>
                            <p class="text-muted">
                                @php
                                    $expiresAt = \Carbon\Carbon::parse($share->expires_at);
                                    $now = \Carbon\Carbon::now();
                                    if ($expiresAt->isFuture()) {
                                        echo $expiresAt->diffForHumans();
                                    } else {
                                        echo 'Expired ' . $expiresAt->diffForHumans();
                                    }
                                @endphp
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('document.show', $share->document) }}" class="btn btn-primary">
                            <i class="fas fa-eye me-2"></i>View Document
                        </a>
                        
                        <a href="{{ route('document.download', $share->document) }}" class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>Download Document
                        </a>
                        
                        <a href="{{ route('document.sharing.edit', $share) }}" class="btn btn-outline-warning">
                            <i class="fas fa-edit me-2"></i>Edit Share
                        </a>
                        
                        @if($share->is_active)
                        <form action="{{ route('document.sharing.toggle', $share) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-pause me-2"></i>Disable Share
                            </button>
                        </form>
                        @else
                        <form action="{{ route('document.sharing.toggle', $share) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="fas fa-play me-2"></i>Enable Share
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Share Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 class="text-primary">{{ $share->view_count ?? 0 }}</h4>
                        <p class="text-muted mb-0">Total Views</p>
                    </div>
                    
                    <div class="text-center mb-3">
                        <h4 class="text-success">{{ $share->download_count ?? 0 }}</h4>
                        <p class="text-muted mb-0">Downloads</p>
                    </div>
                    
                    <div class="text-center">
                        <h4 class="text-info">{{ $share->comment_count ?? 0 }}</h4>
                        <p class="text-muted mb-0">Comments</p>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Once you delete this share, there is no going back. Please be certain.</p>
                    <form action="{{ route('document.sharing.destroy', $share) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Are you sure you want to revoke this share? This action cannot be undone.')">
                            <i class="fas fa-trash me-2"></i>Revoke Share
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
