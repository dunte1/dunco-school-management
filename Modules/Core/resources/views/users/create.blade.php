@php($errors = $errors ?? app('view')->shared('errors', new \Illuminate\Support\ViewErrorBag))

@extends('core::layouts.master')

@section('content')
<div class="container d-flex justify-content-center align-items-start" style="min-height: 80vh;">
    <div class="w-100" style="max-width: 600px;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm">
                <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('core.users.index') }}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create User</li>
            </ol>
        </nav>
        <div class="card shadow rounded-4">
                <div class="card-body">
                <h2 class="mb-4"><i class="fas fa-user-plus"></i> Create New User</h2>
                    <form action="{{ route('core.users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password *</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password *</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role Assignments</label>
                            <div id="role-assignments">
                                @foreach($roles as $role)
                                    <div class="row align-items-center mb-2">
                                        <div class="col-md-5">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="role_assignments[{{ $role->id }}][role_id]" value="{{ $role->id }}" id="role_{{ $role->id }}">
                                                <label class="form-check-label" for="role_{{ $role->id }}">
                                                    {{ $role->display_name ?? $role->name }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <select class="form-select" name="role_assignments[{{ $role->id }}][school_id]">
                                                <option value="">Global</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="form-text text-muted">Select roles and assign to a specific school or globally.</small>
                        </div>
                        <div class="mb-3">
                            <label for="primary_role_id" class="form-label">Primary Role</label>
                            <select class="form-select" id="primary_role_id" name="primary_role_id" required>
                                <option value="">Select Primary Role</option>
                                <!-- Options will be populated by JS based on selected roles -->
                            </select>
                            <small class="form-text text-muted">Choose the main role for dashboard landing.</small>
                        </div>
                        <div class="mb-3">
                            <label for="school_id" class="form-label">School</label>
                            <select class="form-select @error('school_id') is-invalid @enderror" id="school_id" name="school_id">
                                <option value="">Select School</option>
                                @foreach($schools as $school)
                                    <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                @endforeach
                            </select>
                            @error('school_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="e.g. +1234567890">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" placeholder="e.g. 123 Main St, City, Country">
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="avatar" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar">
                            @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active User</label>
                        </div>
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" id="force_password_reset" name="force_password_reset" value="1" {{ old('force_password_reset') ? 'checked' : '' }}>
                            <label class="form-check-label" for="force_password_reset">Force password reset on next login</label>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('core.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 

<script>
// Dynamically populate primary role dropdown based on checked roles
function updatePrimaryRoleOptions() {
    const roleAssignments = document.querySelectorAll('#role-assignments input[type=checkbox]:checked');
    const primaryRoleSelect = document.getElementById('primary_role_id');
    primaryRoleSelect.innerHTML = '<option value="">Select Primary Role</option>';
    roleAssignments.forEach(cb => {
        const roleId = cb.value;
        const label = cb.closest('.row').querySelector('label').innerText;
        primaryRoleSelect.innerHTML += `<option value="${roleId}">${label}</option>`;
    });
}
document.querySelectorAll('#role-assignments input[type=checkbox]').forEach(cb => {
    cb.addEventListener('change', updatePrimaryRoleOptions);
});
updatePrimaryRoleOptions();
</script> 