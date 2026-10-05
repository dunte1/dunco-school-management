@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Edit Group</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('communication.groups.show', $group) }}" class="btn btn-outline-primary">
            <i class="fas fa-eye me-2"></i>View Group
        </a>
        <a href="{{ route('communication.groups.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Groups
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('communication.groups.update', $group) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="name" class="form-label">Group Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $group->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="type" class="form-label">Group Type *</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="general" {{ old('type', $group->type) == 'general' ? 'selected' : '' }}>General</option>
                            <option value="academic" {{ old('type', $group->type) == 'academic' ? 'selected' : '' }}>Academic</option>
                            <option value="staff" {{ old('type', $group->type) == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="students" {{ old('type', $group->type) == 'students' ? 'selected' : '' }}>Students</option>
                            <option value="parents" {{ old('type', $group->type) == 'parents' ? 'selected' : '' }}>Parents</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description', $group->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Group Members</label>
                <div class="row">
                    @php
                        $selectedMembers = old('members', $group->members->pluck('id')->toArray());
                    @endphp
                    @foreach($users as $user)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="members[]" 
                                       value="{{ $user->id }}" id="user_{{ $user->id }}" 
                                       {{ in_array($user->id, $selectedMembers) ? 'checked' : '' }}>
                                <label class="form-check-label" for="user_{{ $user->id }}">
                                    {{ $user->name }} ({{ $user->email }})
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="form-text">Select members to add to this group. The group creator will remain as admin.</div>
                @error('members')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                           {{ old('is_active', $group->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active Group
                    </label>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Group
                </button>
                <a href="{{ route('communication.groups.show', $group) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
