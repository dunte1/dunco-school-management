@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">Class Schedules</h3>
        <a href="{{ route('class_schedules.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Class Schedule
        </a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <select name="academic_class_id" class="form-select" onchange="this.form.submit()">
                <option value="">All Classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" @if(request('academic_class_id') == $class->id) selected @endif>{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="teacher_id" class="form-select" onchange="this.form.submit()">
                <option value="">All Teachers</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @if(request('teacher_id') == $teacher->id) selected @endif>{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="room_id" class="form-select" onchange="this.form.submit()">
                <option value="">All Rooms</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" @if(request('room_id') == $room->id) selected @endif>{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="day_of_week" class="form-select" onchange="this.form.submit()">
                <option value="">All Days</option>
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                    <option value="{{ $day }}" @if(request('day_of_week') == $day) selected @endif>{{ $day }}</option>
                @endforeach
            </select>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Class</th>
                    <th>Teacher</th>
                    <th>Room</th>
                    <th>Timetable</th>
                    <th>Day</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->academicClass->name ?? '-' }}</td>
                        <td>{{ $schedule->teacher->first_name ?? '' }} {{ $schedule->teacher->last_name ?? '' }}</td>
                        <td>{{ $schedule->room->name ?? '-' }}</td>
                        <td>{{ $schedule->timetable->name ?? '-' }}</td>
                        <td>{{ $schedule->day_of_week }}</td>
                        <td>{{ $schedule->start_time }}</td>
                        <td>{{ $schedule->end_time }}</td>
                        <td>
                            <a href="{{ route('class_schedules.show', $schedule->id) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('class_schedules.edit', $schedule->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('class_schedules.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">No class schedules found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div>{{ $schedules->links() }}</div>
    </div>
</div>
@endsection 