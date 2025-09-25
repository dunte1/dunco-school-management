@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Teacher Availability Details</h2>
    <div class="card mb-3">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $availability->id }}</p>
            <p><strong>Teacher ID:</strong> {{ $availability->teacher_id }}</p>
            <p><strong>Day of Week:</strong> {{ $availability->day_of_week }}</p>
            <p><strong>Start Time:</strong> {{ $availability->start_time }}</p>
            <p><strong>End Time:</strong> {{ $availability->end_time }}</p>
        </div>
    </div>
    <a href="{{ route('teacher_availabilities.edit', $availability->id) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('teacher_availabilities.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection 