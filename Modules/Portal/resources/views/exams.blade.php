@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-file-alt me-2"></i>My Exams</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.exams') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    <div class="row g-4">
        {{-- Exam Summary Cards --}}
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $pendingExams }}</h4>
                    <p class="mb-0">Upcoming Exams</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $completedExams }}</h4>
                    <p class="mb-0">Completed Exams</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $averageScore }}</h4>
                    <p class="mb-0">Average Score</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-list-alt fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $totalExams }}</h4>
                    <p class="mb-0">Total Exams</p>
                </div>
            </div>
        </div>

        {{-- Next Exam Alert --}}
        @if($nextExam)
        <div class="col-lg-12">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fas fa-bell me-2"></i>
                <div>
                    <strong>Next Exam:</strong> {{ $nextExam->subject->name ?? 'Unknown Subject' }} - {{ $nextExam->exam_type }} 
                    on {{ $nextExam->exam_date ? $nextExam->exam_date->format('M d, Y') : 'TBD' }}
                    @if($nextExam->exam_date)
                        <span class="badge bg-warning ms-2">
                            {{ $nextExam->exam_date->diffForHumans() }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Upcoming Exams --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Upcoming Exams</h5>
                </div>
                <div class="card-body">
                    @if($upcomingExams->count())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Subject</th>
                                        <th>Exam Type</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Duration</th>
                                        <th>Room</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingExams as $exam)
                                        <tr>
                                            <td><strong>{{ $exam->subject->name ?? 'Unknown Subject' }}</strong></td>
                                            <td>{{ $exam->exam_type ?? '-' }}</td>
                                            <td>{{ $exam->exam_date ? $exam->exam_date->format('M d, Y') : 'TBD' }}</td>
                                            <td>{{ $exam->start_time ?? '-' }}</td>
                                            <td>{{ $exam->duration ?? '-' }}</td>
                                            <td>{{ $exam->room ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-warning">Upcoming</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-plus fa-2x mb-2"></i>
                            <p>No upcoming exams scheduled.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Exam Schedule by Subject --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Exam Schedule by Subject</h5>
                </div>
                <div class="card-body">
                    @if($examSchedule->count())
                        <div class="list-group list-group-flush">
                            @foreach($examSchedule as $subject => $exams)
                            <div class="list-group-item">
                                <h6 class="mb-2">{{ $subject }}</h6>
                                @foreach($exams as $exam)
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">{{ $exam->exam_type }}</small>
                                    <small class="text-muted">{{ $exam->exam_date ? $exam->exam_date->format('M d') : 'TBD' }}</small>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-book fa-2x mb-2"></i>
                            <p>No exam schedule available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Past Exam Results --}}
        <div class="col-lg-12 printable-area">
            <div class="card" id="exam-results-section">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Past Exam Results</h5>
                    <button class="btn btn-sm btn-outline-primary no-print" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Results
                    </button>
                </div>
                <div class="card-body">
                    @if($pastExams->count())
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Subject</th>
                                        <th>Exam Type</th>
                                        <th>Date</th>
                                        <th>Marks Obtained</th>
                                        <th>Total Marks</th>
                                        <th>Percentage</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pastExams as $exam)
                                        @php
                                            $percentage = $exam->total_marks > 0 ? round(($exam->marks_obtained / $exam->total_marks) * 100, 1) : 0;
                                            $gradeClass = '';
                                            if ($percentage >= 80) $gradeClass = 'table-success';
                                            elseif ($percentage >= 60) $gradeClass = 'table-warning';
                                            else $gradeClass = 'table-danger';
                                        @endphp
                                        <tr class="{{ $gradeClass }}">
                                            <td><strong>{{ $exam->subject->name ?? 'Unknown Subject' }}</strong></td>
                                            <td>{{ $exam->exam_type ?? '-' }}</td>
                                            <td>{{ $exam->exam_date ? $exam->exam_date->format('M d, Y') : '-' }}</td>
                                            <td>{{ $exam->marks_obtained ?? 'N/A' }}</td>
                                            <td>{{ $exam->total_marks ?? 'N/A' }}</td>
                                            <td><strong>{{ $percentage }}%</strong></td>
                                            <td>
                                                @if($exam->grade)
                                                    <span class="badge bg-primary">{{ $exam->grade }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($exam->status === 'completed')
                                                    <span class="badge bg-success">Completed</span>
                                                @elseif($exam->status === 'missed')
                                                    <span class="badge bg-danger">Missed</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($exam->status ?? 'Unknown') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $exam->remarks ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <p>No past exam results available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    body > *:not(.printable-area) {
        display: none !important;
    }
    .printable-area {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
    .no-print {
        display: none !important;
    }
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
}
</style>
@endpush
