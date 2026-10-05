@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Share Document</h2>
        <a href="{{ route('document.sharing.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Sharing
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('document.sharing.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="document_id" class="form-label">Select Document</label>
                            <select class="form-select @error('document_id') is-invalid @enderror" id="document_id" name="document_id" required>
                                <option value="">Choose a document...</option>
                                @foreach($documents as $document)
                                <option value="{{ $document->id }}" {{ old('document_id') == $document->id ? 'selected' : '' }}>
                                    {{ $document->title }} ({{ $document->file_name }})
                                </option>
                                @endforeach
                            </select>
                            @error('document_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Share With</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="share_type" id="share_user" value="user" {{ old('share_type', 'user') == 'user' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="share_user">
                                            Specific User
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="share_type" id="share_group" value="group" {{ old('share_type') == 'group' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="share_group">
                                            User Group
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3" id="user-selection" style="display: {{ old('share_type', 'user') == 'user' ? 'block' : 'none' }}">
                            <label for="user_id" class="form-label">Select User</label>
                            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                                <option value="">Choose a user...</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="group-selection" style="display: {{ old('share_type') == 'group' ? 'block' : 'none' }}">
                            <label for="group_id" class="form-label">Select Group</label>
                            <select class="form-select @error('group_id') is-invalid @enderror" id="group_id" name="group_id">
                                <option value="">Choose a group...</option>
                                @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('group_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="permission" class="form-label">Permission Level</label>
                            <select class="form-select @error('permission') is-invalid @enderror" id="permission" name="permission" required>
                                <option value="view" {{ old('permission', 'view') == 'view' ? 'selected' : '' }}>View Only</option>
                                <option value="comment" {{ old('permission') == 'comment' ? 'selected' : '' }}>View & Comment</option>
                                <option value="edit" {{ old('permission') == 'edit' ? 'selected' : '' }}>Edit</option>
                            </select>
                            @error('permission')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Expiration Date (Optional)</label>
                            <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message (Optional)</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="3" 
                                      placeholder="Add a personal message...">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_user" name="notify_user" value="1" checked>
                                <label class="form-check-label" for="notify_user">
                                    Send notification to recipient
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-share me-2"></i>Share Document
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Sharing Guidelines</h5>
                </div>
                <div class="card-body">
                    <h6>Permission Levels:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-eye text-info me-2"></i><strong>View:</strong> Can only view the document</li>
                        <li><i class="fas fa-comment text-warning me-2"></i><strong>Comment:</strong> Can view and add comments</li>
                        <li><i class="fas fa-edit text-success me-2"></i><strong>Edit:</strong> Can modify the document</li>
                    </ul>
                    
                    <hr>
                    
                    <h6>Security Tips:</h6>
                    <ul class="list-unstyled small">
                        <li>• Set expiration dates for sensitive documents</li>
                        <li>• Use appropriate permission levels</li>
                        <li>• Review shared documents regularly</li>
                        <li>• Only share with trusted users</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const shareTypeRadios = document.querySelectorAll('input[name="share_type"]');
    const userSelection = document.getElementById('user-selection');
    const groupSelection = document.getElementById('group-selection');
    
    shareTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'user') {
                userSelection.style.display = 'block';
                groupSelection.style.display = 'none';
                document.getElementById('user_id').required = true;
                document.getElementById('group_id').required = false;
            } else if (this.value === 'group') {
                userSelection.style.display = 'none';
                groupSelection.style.display = 'block';
                document.getElementById('user_id').required = false;
                document.getElementById('group_id').required = true;
            }
        });
    });
});
</script>
@endsection
