@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Academic Management</h1>
        <div class="text-muted">Academic programs, classes, and curriculum</div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_students'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Classes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_classes'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Teachers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_teachers'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chalkboard-teacher fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Subjects</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_subjects'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Links</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/academic/students') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-user-graduate me-2"></i>Students
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/academic/classes') }}" class="btn btn-success btn-block">
                                <i class="fas fa-chalkboard me-2"></i>Classes
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/academic/subjects') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-book me-2"></i>Subjects
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/academic/assignments') }}" class="btn btn-info btn-block">
                                <i class="fas fa-tasks me-2"></i>Assignments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Academic Overview</h6>
                </div>
                <div class="card-body">
                    <p><strong>Academic Year:</strong> {{ $stats['academic_year'] ?? 'Not Set' }}</p>
                    <p><strong>Active Students:</strong> {{ $stats['total_students'] ?? 0 }}</p>
                    <p><strong>Active Classes:</strong> {{ $stats['total_classes'] ?? 0 }}</p>
                    <p class="mb-0"><strong>Teaching Staff:</strong> {{ $stats['total_teachers'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Examinations</h6>
                </div>
                <div class="card-body">
                    @php
                        $exams = \Modules\Examination\Models\Exam::latest()->take(5)->get();
                    @endphp
                    @if($exams && $exams->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Exam</th>
                                        <th>Subject</th>
                                        <th>Start Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($exams as $exam)
                                    <tr>
                                        <td>{{ $exam->title ?? 'N/A' }}</td>
                                        <td>{{ $exam->subject->name ?? 'N/A' }}</td>
                                        <td>{{ $exam->start_date ? \Carbon\Carbon::parse($exam->start_date)->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ ($exam->status ?? '') === 'completed' ? 'success' : (($exam->status ?? '') === 'ongoing' ? 'warning' : 'primary') }}">
                                                {{ ucfirst($exam->status ?? 'pending') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No recent examinations</h5>
                            <p class="text-muted">Examinations will appear here once created.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
