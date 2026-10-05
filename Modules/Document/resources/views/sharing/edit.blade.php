@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Edit Document Share</h2>
        <a href="{{ route('document.sharing.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Sharing
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('document.sharing.update', $share) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Document</label>
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-file-{{ $share->document->file_type == 'pdf' ? 'pdf' : 'alt' }} me-3 text-primary fa-2x"></i>
                                <div>
                                    <h6 class="mb-0">{{ $share->document->title }}</h6>
                                    <small class="text-muted">{{ $share->document->file_name }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Shared With</label>
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                @if($share->shared_with_user)
                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                        {{ substr($share->shared_with_user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $share->shared_with_user->name }}</h6>
                                        <small class="text-muted">{{ $share->shared_with_user->email }}</small>
                                    </div>
                                @elseif($share->shared_with_group)
                                    <i class="fas fa-users me-3 text-info fa-2x"></i>
                                    <div>
                                        <h6 class="mb-0">{{ $share->shared_with_group->name }}</h6>
                                        <small class="text-muted">Group</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="permission" class="form-label">Permission Level</label>
                            <select class="form-select @error('permission') is-invalid @enderror" id="permission" name="permission" required>
                                <option value="view" {{ old('permission', $share->permission) == 'view' ? 'selected' : '' }}>View Only</option>
                                <option value="comment" {{ old('permission', $share->permission) == 'comment' ? 'selected' : '' }}>View & Comment</option>
                                <option value="edit" {{ old('permission', $share->permission) == 'edit' ? 'selected' : '' }}>Edit</option>
                            </select>
                            @error('permission')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Expiration Date</label>
                            <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" name="expires_at" 
                                   value="{{ old('expires_at', $share->expires_at ? \Carbon\Carbon::parse($share->expires_at)->format('Y-m-d\TH:i') : '') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="3" 
                                      placeholder="Add a personal message...">{{ old('message', $share->message) }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $share->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (uncheck to disable this share)
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_user" name="notify_user" value="1">
                                <label class="form-check-label" for="notify_user">
                                    Send notification about changes
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('document.sharing.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Share
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Share Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Created</h6>
                        <p class="text-muted mb-0">{{ $share->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Last Updated</h6>
                        <p class="text-muted mb-0">{{ $share->updated_at->format('M d, Y g:i A') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Status</h6>
                        <span class="badge bg-{{ $share->is_active ? 'success' : 'secondary' }}">
                            {{ $share->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    @if($share->expires_at)
                    <div class="mb-3">
                        <h6>Expires</h6>
                        <p class="text-muted mb-0">{{ \Carbon\Carbon::parse($share->expires_at)->format('M d, Y g:i A') }}</p>
                    </div>
                    @endif
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
