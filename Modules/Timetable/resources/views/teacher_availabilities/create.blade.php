@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Add Teacher Availability</h2>
    <form action="{{ route('teacher_availabilities.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="teacher_id" class="form-label">Teacher</label>
            <select name="teacher_id" id="teacher_id" class="form-select" required>
                <option value="">Select Teacher</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="days_of_week" class="form-label">Days of Week</label>
            <select name="days_of_week[]" id="days_of_week" class="form-select" multiple required>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select>
            <small class="text-muted">Hold Ctrl (Cmd on Mac) to select multiple days.</small>
        </div>
        <div class="mb-3">
            <label for="start_time" class="form-label">Start Time</label>
            <input type="time" name="start_time" id="start_time" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="end_time" class="form-label">End Time</label>
            <input type="time" name="end_time" id="end_time" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Create Availability</button>
        <a href="{{ route('teacher_availabilities.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 