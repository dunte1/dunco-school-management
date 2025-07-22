@extends('academic::layouts.app')

@section('content')
<div class="container">
    <h1>Enroll Student</h1>
    <form method="POST" action="{{ route('academic.students.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="admission_number" class="form-label">Admission Number</label>
            <input type="text" class="form-control" id="admission_number" name="admission_number" required>
        </div>
        <div class="mb-3">
            <label for="class_id" class="form-label">Class</label>
            <input type="number" class="form-control" id="class_id" name="class_id" required>
        </div>
        <div class="mb-3">
            <label for="student_id" class="form-label">Student ID</label>
            <input type="text" class="form-control" id="student_id" name="student_id" required>
        </div>
        <div class="mb-3">
            <label for="admission_date" class="form-label">Admission Date</label>
            <input type="date" class="form-control" id="admission_date" name="admission_date" required>
        </div>
        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
        </div>
        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="passport" class="form-label">Passport Photo</label>
            <input type="file" class="form-control" id="passport" name="passport" accept="image/*">
        </div>
        <div class="mb-3">
            <label for="medical_conditions" class="form-label">Medical Conditions</label>
            <textarea class="form-control" id="medical_conditions" name="medical_conditions"></textarea>
        </div>
        <div class="mb-3">
            <label for="disabilities" class="form-label">Disabilities</label>
            <textarea class="form-control" id="disabilities" name="disabilities"></textarea>
        </div>
        <div class="mb-3">
            <label for="allergies" class="form-label">Allergies</label>
            <textarea class="form-control" id="allergies" name="allergies"></textarea>
        </div>
        <div class="mb-3">
            <a href="{{ route('academic.students.bulk_import') }}" class="btn btn-outline-info">Bulk Enroll via Excel</a>
        </div>
        <button type="submit" class="btn btn-primary">Enroll</button>
        <a href="{{ route('academic.students.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 