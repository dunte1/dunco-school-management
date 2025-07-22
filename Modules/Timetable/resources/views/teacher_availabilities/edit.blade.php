@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Edit Teacher Availability</h2>
    <form action="{{ route('teacher_availabilities.update', $availability->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="teacher_id" class="form-label">Teacher</label>
            <select name="teacher_id" id="teacher_id" class="form-select" required>
                <option value="">Select Teacher</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @if($teacher->id == $availability->teacher_id) selected @endif>{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="day_of_week" class="form-label">Day of Week</label>
            <select name="day_of_week" id="day_of_week" class="form-select" required>
                <option value="Monday" @if($availability->day_of_week == 'Monday') selected @endif>Monday</option>
                <option value="Tuesday" @if($availability->day_of_week == 'Tuesday') selected @endif>Tuesday</option>
                <option value="Wednesday" @if($availability->day_of_week == 'Wednesday') selected @endif>Wednesday</option>
                <option value="Thursday" @if($availability->day_of_week == 'Thursday') selected @endif>Thursday</option>
                <option value="Friday" @if($availability->day_of_week == 'Friday') selected @endif>Friday</option>
                <option value="Saturday" @if($availability->day_of_week == 'Saturday') selected @endif>Saturday</option>
                <option value="Sunday" @if($availability->day_of_week == 'Sunday') selected @endif>Sunday</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="start_time" class="form-label">Start Time</label>
            <input type="time" name="start_time" id="start_time" class="form-control" value="{{ $availability->start_time }}" required>
        </div>
        <div class="mb-3">
            <label for="end_time" class="form-label">End Time</label>
            <input type="time" name="end_time" id="end_time" class="form-control" value="{{ $availability->end_time }}" required>
        </div>
        <button type="submit" class="btn btn-success">Update Availability</button>
        <a href="{{ route('teacher_availabilities.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 