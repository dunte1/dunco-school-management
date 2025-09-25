@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Add Timetable</h2>
    <form action="{{ route('timetables.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="academic_year" class="form-label">Academic Year</label>
            <input type="text" name="academic_year" id="academic_year" class="form-control">
        </div>
        <div class="mb-3">
            <label for="term" class="form-label">Term</label>
            <input type="text" name="term" id="term" class="form-control">
        </div>
        <div class="mb-3">
            <label for="school_level" class="form-label">School Level</label>
            <input type="text" name="school_level" id="school_level" class="form-control">
        </div>
        <div class="mb-3">
            <label for="is_active" class="form-label">Active</label>
            <select name="is_active" id="is_active" class="form-select">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Create Timetable</button>
        <a href="{{ route('timetables.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 