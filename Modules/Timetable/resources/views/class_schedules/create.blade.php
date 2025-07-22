@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Add Class Schedule</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('class_schedules.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="academic_class_id" class="form-label">Class</label>
            <select name="academic_class_id" id="academic_class_id" class="form-select" required>
                <option value="">Select Class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @if(old('academic_class_id') == $class->id) selected @endif>{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="teacher_id" class="form-label">Teacher</label>
            <select name="teacher_id" id="teacher_id" class="form-select" required>
                <option value="">Select Teacher</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @if(old('teacher_id') == $teacher->id) selected @endif>{{ $teacher->first_name }} {{ $teacher->last_name }} ({{ $teacher->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="room_id" class="form-label">Room</label>
            <select name="room_id" id="room_id" class="form-select" required>
                <option value="">Select Room</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" @if(old('room_id') == $room->id) selected @endif>{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="timetable_id" class="form-label">Timetable</label>
            <select name="timetable_id" id="timetable_id" class="form-select" required>
                <option value="">Select Timetable</option>
                @foreach($timetables as $timetable)
                    <option value="{{ $timetable->id }}" @if(old('timetable_id') == $timetable->id) selected @endif>{{ $timetable->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="day_of_week" class="form-label">Day of Week</label>
            <select name="day_of_week" id="day_of_week" class="form-select" required>
                <option value="">Select Day</option>
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                    <option value="{{ $day }}" @if(old('day_of_week') == $day) selected @endif>{{ $day }}</option>
                @endforeach
            </select>
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="start_time" class="form-label">Start Time</label>
                <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}" required>
            </div>
            <div class="col">
                <label for="end_time" class="form-label">End Time</label>
                <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}" required>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('class_schedules.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 