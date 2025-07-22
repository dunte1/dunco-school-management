@extends('core::layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Edit School: {{ $school->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('core.schools.update', $school->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">School Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $school->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">School Code *</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code', $school->code) }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="motto" class="form-label">Motto</label>
                            <input type="text" class="form-control @error('motto') is-invalid @enderror" 
                                   id="motto" name="motto" value="{{ old('motto', $school->motto) }}">
                            @error('motto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="domain" class="form-label">Domain</label>
                            <input type="text" class="form-control @error('domain') is-invalid @enderror" 
                                   id="domain" name="domain" value="{{ old('domain', $school->domain) }}" 
                                   placeholder="e.g., school.example.com">
                            @error('domain')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <h6 class="mt-4 mb-2">Branding & Academic</h6>
                        <div class="mb-3">
                            <label for="level" class="form-label">Academic Level</label>
                            <select class="form-control @error('level') is-invalid @enderror" id="level" name="level">
                                <option value="">Select Level</option>
                                @foreach($levels as $key => $label)
                                    <option value="{{ $key }}" {{ old('level', $school->level) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('level')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo">
                            @if($school->logo)
                                <img src="{{ asset('storage/' . $school->logo) }}" alt="Logo" height="40" class="mt-2">
                            @endif
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <h6 class="mt-4 mb-2">Contact Info</h6>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $school->phone) }}" placeholder="e.g. +1234567890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $school->email) }}" placeholder="e.g. info@school.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $school->address) }}" placeholder="e.g. 123 Main St, City, Country">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <h6 class="mt-4 mb-2">Administration</h6>
                        <div class="mb-3">
                            <label for="admin_ids" class="form-label">School Admins</label>
                            <select class="form-control @error('admin_ids') is-invalid @enderror" id="admin_ids" name="admin_ids[]" multiple>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (collect(old('admin_ids', $adminIds))->contains($user->id)) ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('admin_ids')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $school->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label">Enable School</label>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('core.schools.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update School
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection