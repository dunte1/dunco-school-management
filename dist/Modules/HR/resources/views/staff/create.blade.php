@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-person-plus"></i> Add Staff</h4>
                </div>
                <div class="card-body">
<form method="POST" action="{{ route('hr.staff.store') }}" enctype="multipart/form-data">
    @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Other Names</label>
                                <input type="text" name="other_names" class="form-control">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">ID Number</label>
                                <input type="text" name="id_number" class="form-control">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Passport Number</label>
                                <input type="text" name="passport_number" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Job Title</label>
                                <input type="text" name="job_title" class="form-control">
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="role_id" class="form-select">
        <option value="">Select Role</option>
        @foreach($roles as $role)
            <option value="{{ $role->id }}">{{ $role->name }}</option>
        @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Department</label>
                                <select name="department_id" class="form-select">
        <option value="">Select Department</option>
        @foreach($departments as $department)
            <option value="{{ $department->id }}">{{ $department->name }}</option>
        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" name="photo" class="form-control" onchange="previewPhoto(event)">
                            <div class="mt-2">
                                <img id="photoPreview" src="#" alt="Photo Preview" style="display:none; max-width:100px;" class="img-thumbnail">
                            </div>
                        </div>
                        <h5 class="mt-4">Emergency Contact</h5>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Name</label>
                                <input type="text" name="emergency_contact_name" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Phone</label>
                                <input type="text" name="emergency_contact_phone" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Relation</label>
                                <input type="text" name="emergency_contact_relation" class="form-control">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success me-2"><i class="bi bi-save"></i> Save</button>
                            <a href="{{ route('hr.staff.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
</form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function previewPhoto(event) {
    const [file] = event.target.files;
    if (file) {
        const preview = document.getElementById('photoPreview');
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
}
</script>
@endsection 