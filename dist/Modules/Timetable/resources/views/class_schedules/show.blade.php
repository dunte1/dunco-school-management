@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Class Schedule Details</h2>
    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Class:</strong> {{ $class_schedule->academicClass->name ?? '-' }}</p>
            <p><strong>Teacher:</strong> {{ $class_schedule->teacher->first_name ?? '' }} {{ $class_schedule->teacher->last_name ?? '' }} ({{ $class_schedule->teacher->email ?? '' }})</p>
            <p><strong>Room:</strong> {{ $class_schedule->room->name ?? '-' }}</p>
            <p><strong>Timetable:</strong> {{ $class_schedule->timetable->name ?? '-' }}</p>
            <p><strong>Day of Week:</strong> {{ $class_schedule->day_of_week }}</p>
            <p><strong>Start Time:</strong> {{ $class_schedule->start_time }}</p>
            <p><strong>End Time:</strong> {{ $class_schedule->end_time }}</p>
        </div>
    </div>
    <a href="{{ route('class_schedules.edit', $class_schedule->id) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('class_schedules.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection 