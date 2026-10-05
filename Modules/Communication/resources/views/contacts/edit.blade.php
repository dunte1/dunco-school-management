@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Edit Contact</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('communication.contacts.show', $contact) }}" class="btn btn-outline-primary">
            <i class="fas fa-eye me-2"></i>View Contact
        </a>
        <a href="{{ route('communication.contacts.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Contacts
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('communication.contacts.update', $contact) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $contact->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category" class="form-label">Category *</label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="student" {{ old('category', $contact->category) == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="parent" {{ old('category', $contact->category) == 'parent' ? 'selected' : '' }}>Parent</option>
                            <option value="teacher" {{ old('category', $contact->category) == 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="staff" {{ old('category', $contact->category) == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="admin" {{ old('category', $contact->category) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="external" {{ old('category', $contact->category) == 'external' ? 'selected' : '' }}>External</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $contact->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone', $contact->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="organization" class="form-label">Organization</label>
                        <input type="text" class="form-control @error('organization') is-invalid @enderror" 
                               id="organization" name="organization" value="{{ old('organization', $contact->organization) }}">
                        @error('organization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="position" class="form-label">Position/Title</label>
                        <input type="text" class="form-control @error('position') is-invalid @enderror" 
                               id="position" name="position" value="{{ old('position', $contact->position) }}">
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control @error('notes') is-invalid @enderror" 
                          id="notes" name="notes" rows="3">{{ old('notes', $contact->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label">Groups</label>
                <div class="row">
                    @php
                        $selectedGroups = old('groups', $contact->groups->pluck('id')->toArray());
                    @endphp
                    @foreach($groups as $group)
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="groups[]" 
                                       value="{{ $group->id }}" id="group_{{ $group->id }}" 
                                       {{ in_array($group->id, $selectedGroups) ? 'checked' : '' }}>
                                <label class="form-check-label" for="group_{{ $group->id }}">
                                    {{ $group->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="form-text">Select groups to add this contact to</div>
                @error('groups')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                           {{ old('is_active', $contact->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active Contact
                    </label>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Contact
                </button>
                <a href="{{ route('communication.contacts.show', $contact) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
