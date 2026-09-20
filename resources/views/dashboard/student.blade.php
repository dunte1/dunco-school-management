@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Student Dashboard</h1>
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
                                Current Class</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['current_class'] ? $stats['current_class']->name : 'Not Assigned' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
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
                                Total Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_subjects'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
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
                                Attendance Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['attendance_rate'] }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
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
                                Upcoming Exams</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['upcoming_exams']->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
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
                            <a href="{{ route('portal.academics') }}" class="btn btn-primary btn-block" >
                                <i class="fas fa-book me-2"></i>My Academics
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('portal.schedule') }}" class="btn btn-success btn-block" >
                                <i class="fas fa-calendar me-2"></i>My Schedule
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('portal.materials') }}" class="btn btn-warning btn-block" >
                                <i class="fas fa-file-pdf me-2"></i>Study Materials
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('portal.assignments') }}" class="btn btn-info btn-block" >
                                <i class="fas fa-tasks me-2"></i>Assignments
                            </a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('portal.finance') }}" class="btn btn-secondary btn-block" >
                                <i class="fas fa-dollar-sign me-2"></i>Finance
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('portal.communication') }}" class="btn btn-dark btn-block" >
                                <i class="fas fa-comments me-2"></i>Communication
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('portal.profile') }}" class="btn btn-outline-primary btn-block" >
                                <i class="fas fa-user me-2"></i>My Profile
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('portal.dashboard') }}" class="btn btn-outline-success btn-block" >
                                <i class="fas fa-chart-line me-2"></i>My Results
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
                    <h6 class="m-0 font-weight-bold text-primary">Upcoming Exams</h6>
                </div>
                <div class="card-body">
                    @if($stats['upcoming_exams'] && $stats['upcoming_exams']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Exam</th>
                                        <th>Subject</th>
                                        <th>Date</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['upcoming_exams'] as $exam)
                                    <tr>
                                        <td>{{ $exam->title ?? 'N/A' }}</td>
                                        <td>{{ $exam->subject->name ?? 'N/A' }}</td>
                                        <td>{{ $exam->start_date ? \Carbon\Carbon::parse($exam->start_date)->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $exam->duration ?? 'N/A' }} minutes</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No upcoming exams found.</p>
                        <p class="text-muted">Your upcoming exams will appear here.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Student Information</h6>
                </div>
                <div class="card-body">
                    @if($stats['current_class'])
                        <p><strong>Current Class:</strong> {{ $stats['current_class']->name ?? 'N/A' }}</p>
                        <p><strong>Class Teacher:</strong> {{ $stats['current_class']->teacher->name ?? 'N/A' }}</p>
                        <p><strong>Academic Year:</strong> {{ $stats['current_class']->academic_year ?? 'N/A' }}</p>
                        <p><strong>Subjects:</strong> {{ $stats['total_subjects'] }}</p>
                        <p><strong>Attendance:</strong> 
                            <span class="badge bg-{{ $stats['attendance_rate'] >= 80 ? 'success' : ($stats['attendance_rate'] >= 60 ? 'warning' : 'danger') }}">
                                {{ $stats['attendance_rate'] }}%
                            </span>
                        </p>
                    @else
                        <p class="text-muted">Student profile not found.</p>
                        <p class="text-muted">Please contact administrator to set up your student profile.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
