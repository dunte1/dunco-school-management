@extends('core::layouts.master')
@section('title', 'Edit User')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fas fa-user-edit fa-lg me-2"></i>
                <h4 class="mb-0">Edit User</h4>
                </div>
                <div class="card-body">
                <form action="{{ route('core.users.update', $user->id) }}" method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Leave blank to keep current password">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Leave blank to keep the current password</div>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                        <div class="mb-3">
                            <label class="form-label">Role Assignments</label>
                            <div id="role-assignments">
                                @foreach($roles as $role)
                                    @php
                                        $assignment = $user->roles->firstWhere('id', $role->id);
                                        $pivotSchoolId = $assignment ? $assignment->pivot->school_id : null;
                                    @endphp
                                    <div class="row align-items-center mb-2">
                                        <div class="col-md-5">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="role_assignments[{{ $role->id }}][role_id]" value="{{ $role->id }}" id="role_{{ $role->id }}" {{ $assignment ? 'checked' : '' }}>
                                                <label class="form-check-label" for="role_{{ $role->id }}">
                                        {{ $role->display_name ?? $role->name }}
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <select class="form-select" name="role_assignments[{{ $role->id }}][school_id]">
                                                <option value="" {{ is_null($pivotSchoolId) ? 'selected' : '' }}>Global</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->id }}" {{ $pivotSchoolId == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
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
                                    <option value="{{ $school->id }}" {{ old('school_id', $user->school_id) == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                @endforeach
                            </select>
                            @error('school_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="e.g. +1234567890">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $user->address) }}" placeholder="e.g. 123 Main St, City, Country">
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="avatar" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle mt-2" width="40" height="40">
                            @endif
                            @error('avatar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active User</label>
                            </div>
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" id="force_password_reset" name="force_password_reset" value="1" {{ old('force_password_reset', $user->force_password_reset) ? 'checked' : '' }}>
                            <label class="form-check-label" for="force_password_reset">Force password reset on next login</label>
                        </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('core.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 
<script>
function updatePrimaryRoleOptions() {
    const roleAssignments = document.querySelectorAll('#role-assignments input[type=checkbox]:checked');
    const primaryRoleSelect = document.getElementById('primary_role_id');
    const currentPrimary = '{{ old('primary_role_id', $user->primary_role_id) }}';
    primaryRoleSelect.innerHTML = '<option value="">Select Primary Role</option>';
    roleAssignments.forEach(cb => {
        const roleId = cb.value;
        const label = cb.closest('.row').querySelector('label').innerText;
        const selected = (roleId == currentPrimary) ? 'selected' : '';
        primaryRoleSelect.innerHTML += `<option value="${roleId}" ${selected}>${label}</option>`;
    });
}
document.querySelectorAll('#role-assignments input[type=checkbox]').forEach(cb => {
    cb.addEventListener('change', updatePrimaryRoleOptions);
});
updatePrimaryRoleOptions();
</script> 