@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Teacher Dashboard</h1>
                <div class="text-muted">Welcome back, {{ Auth::user()->name }}!</div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                My Classes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_classes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_students'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Exams</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_exams'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Today's Attendance</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['recent_attendance']->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('academic.students.index') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-user-graduate me-2"></i>View Students
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('examination.teacher.exams') }}" class="btn btn-success btn-block">
                                <i class="fas fa-file-alt me-2"></i>My Exams
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('examination.teacher.grade') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-pen me-2"></i>Grade Exams
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('attendance.index') }}" class="btn btn-info btn-block">
                                <i class="fas fa-user-check me-2"></i>Mark Attendance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Attendance</h6>
                </div>
                <div class="card-body">
                    @if($stats['recent_attendance'] && $stats['recent_attendance']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['recent_attendance'] as $attendance)
                                    <tr>
                                        <td>{{ $attendance->student->name ?? 'N/A' }}</td>
                                        <td>{{ $attendance->date ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ ($attendance->status ?? 'unknown') == 'present' ? 'success' : (($attendance->status ?? 'unknown') == 'absent' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($attendance->status ?? 'unknown') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No recent attendance records found.</p>
                        <p class="text-muted">Attendance records will appear here once you start marking attendance.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Teacher Information</h6>
                </div>
                <div class="card-body">
                    @if($teacher && $teacher->count() > 0)
                        @php $teacher = $teacher->first(); @endphp
                        <p><strong>Name:</strong> {{ $teacher->first_name ?? $teacher->name ?? 'N/A' }} {{ $teacher->last_name ?? '' }}</p>
                        <p><strong>Department:</strong> {{ $teacher->department->name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $teacher->email ?? 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ $teacher->phone ?? 'N/A' }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ ($teacher->status ?? 'active') == 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($teacher->status ?? 'active') }}
                            </span>
                        </p>
                    @else
                        <p class="text-muted">Teacher profile not found.</p>
                        <p class="text-muted">Please contact administrator to set up your teacher profile.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 