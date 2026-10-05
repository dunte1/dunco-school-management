@extends('layouts.app')

@section('title', 'Online Attendance Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Online Attendance Records</h4>
                    <div>
                        <a href="{{ route('academic.online-attendance.create') }}" class="btn btn-primary me-2">
                            <i class="fas fa-plus me-2"></i>New Record
                        </a>
                        <a href="{{ route('academic.online-attendance.statistics') }}" class="btn btn-info">
                            <i class="fas fa-chart-bar me-2"></i>Statistics
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('academic.online-attendance.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-2">
                                <input type="text" name="search" class="form-control" placeholder="Search students..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="online_class_id" class="form-control">
                                    <option value="">All Classes</option>
                                    @foreach($onlineClasses as $class)
                                        <option value="{{ $class->id }}" {{ request('online_class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="student_id" class="form-control">
                                    <option value="">All Students</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                                    <option value="excused" {{ request('status') == 'excused' ? 'selected' : '' }}>Excused</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary me-2">Filter</button>
                                <a href="{{ route('academic.online-attendance.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>

                    <!-- Online Attendance Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Online Class</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Join Time</th>
                                    <th>Leave Time</th>
                                    <th>Duration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendanceRecords as $record)
                                <tr>
                                    <td>{{ $record->student->first_name ?? '' }} {{ $record->student->last_name ?? '' }}</td>
                                    <td>{{ $record->onlineClass->title ?? 'N/A' }}</td>
                                    <td>{{ $record->attendance_date ? \Carbon\Carbon::parse($record->attendance_date)->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $record->status == 'present' ? 'success' : ($record->status == 'late' ? 'warning' : ($record->status == 'excused' ? 'info' : 'danger')) }}">
                                            {{ ucfirst($record->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $record->join_time ?? 'N/A' }}</td>
                                    <td>{{ $record->leave_time ?? 'N/A' }}</td>
                                    <td>{{ $record->duration_minutes ? $record->duration_minutes . ' min' : 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('academic.online-attendance.show', $record->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('academic.online-attendance.edit', $record->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('academic.online-attendance.destroy', $record->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No online attendance records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $attendanceRecords->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
