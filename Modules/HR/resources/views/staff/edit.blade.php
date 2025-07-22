@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-person-lines-fill"></i> Edit Staff</h4>
                </div>
                <div class="card-body">
<form method="POST" action="{{ route('hr.staff.update', $staff->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" value="{{ $staff->first_name }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" value="{{ $staff->last_name }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Other Names</label>
                                <input type="text" name="other_names" class="form-control" value="{{ $staff->other_names }}">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $staff->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ $staff->phone }}">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
        <option value="male" {{ $staff->gender == 'male' ? 'selected' : '' }}>Male</option>
        <option value="female" {{ $staff->gender == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" value="{{ $staff->dob }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ID Number</label>
                                <input type="text" name="id_number" class="form-control" value="{{ $staff->id_number }}">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Passport Number</label>
                                <input type="text" name="passport_number" class="form-control" value="{{ $staff->passport_number }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" value="{{ $staff->address }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="{{ $staff->city }}">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ $staff->country }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Job Title</label>
                                <input type="text" name="job_title" class="form-control" value="{{ $staff->job_title }}">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="role_id" class="form-select">
        <option value="">Select Role</option>
        @foreach($roles as $role)
            <option value="{{ $role->id }}" {{ $staff->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
        @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Department</label>
                                <select name="department_id" class="form-select">
        <option value="">Select Department</option>
        @foreach($departments as $department)
            <option value="{{ $department->id }}" {{ $staff->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" name="photo" class="form-control">
                            @if($staff->photo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $staff->photo) }}" width="100" class="img-thumbnail" alt="Staff Photo">
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">QR Code</label>
                            @if($staff->qr_code)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $staff->qr_code) }}" width="100" class="img-thumbnail" alt="Staff QR Code">
                                </div>
                            @endif
                        </div>
                        <h5 class="mt-4">Emergency Contact</h5>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Name</label>
                                <input type="text" name="emergency_contact_name" class="form-control" value="{{ $staff->emergency_contact_name }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phone</label>
                                <input type="text" name="emergency_contact_phone" class="form-control" value="{{ $staff->emergency_contact_phone }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Relation</label>
                                <input type="text" name="emergency_contact_relation" class="form-control" value="{{ $staff->emergency_contact_relation }}">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success me-2"><i class="bi bi-save"></i> Update</button>
                            <a href="{{ route('hr.staff.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 