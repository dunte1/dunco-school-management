@extends('portal::layouts.master')

@section('content')
<div class="container mt-5">
    <h1>Online Student Application</h1>
    <form method="POST" action="{{ route('portal.apply.submit') }}" enctype="multipart/form-data" class="mt-4">
        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Student Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="date_of_birth" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="">Select</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="class_id" class="form-label">Class Applying For</label>
                <input type="number" class="form-control" id="class_id" name="class_id" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="parent_name" class="form-label">Parent/Guardian Name</label>
                <input type="text" class="form-control" id="parent_name" name="parent_name" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="parent_contact" class="form-label">Parent/Guardian Contact (Email or Phone)</label>
                <input type="text" class="form-control" id="parent_contact" name="parent_contact" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="birth_certificate" class="form-label">Birth Certificate (PDF/JPG/PNG)</label>
                <input type="file" class="form-control" id="birth_certificate" name="birth_certificate" accept="application/pdf,image/*" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="passport_photo" class="form-label">Passport Photo</label>
                <input type="file" class="form-control" id="passport_photo" name="passport_photo" accept="image/*" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit Application</button>
    </form>
</div>
@endsection 