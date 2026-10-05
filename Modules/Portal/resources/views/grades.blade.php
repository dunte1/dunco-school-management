@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-star me-2"></i>My Grades</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.grades') }}" class="d-flex align-items-center gap-2">
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
        {{-- Grade Summary Cards --}}
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $averagePercentage }}%</h4>
                    <p class="mb-0">Average Score</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-trophy fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $academicRecords->count() }}</h4>
                    <p class="mb-0">Total Exams</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $groupedGrades->count() }}</h4>
                    <p class="mb-0">Terms</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-book fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $academicRecords->unique('subject_id')->count() }}</h4>
                    <p class="mb-0">Subjects</p>
                </div>
            </div>
        </div>

        {{-- Grade Distribution Chart --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Grade Distribution</h5>
                </div>
                <div class="card-body">
                    @if($gradeDistribution->count())
                        <div class="row">
                            @foreach($gradeDistribution as $grade => $count)
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="badge bg-primary me-2" style="width: 20px; height: 20px;">{{ $grade }}</div>
                                    <div class="flex-grow-1">
                                        <div class="progress" style="height: 8px;">
                                            @php
                                                $percentage = $academicRecords->count() > 0 ? ($count / $academicRecords->count()) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                    <small class="text-muted ms-2">{{ $count }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-chart-pie fa-2x mb-2"></i>
                            <p>No grade data available for visualization.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Grades --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Grades</h5>
                </div>
                <div class="card-body">
                    @if($academicRecords->take(5)->count())
                        <div class="list-group list-group-flush">
                            @foreach($academicRecords->take(5) as $record)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $record->subject->name ?? 'Unknown Subject' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $record->exam_type }} - Term {{ $record->term }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary">{{ $record->grade }}</span>
                                    <br>
                                    <small class="text-muted">{{ $record->marks_obtained }}/{{ $record->total_marks }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-clock fa-2x mb-2"></i>
                            <p>No recent grades to display.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detailed Grade History --}}
        <div class="col-lg-12 printable-area">
            <div class="card" id="grades-section">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Detailed Grade History</h5>
                    <button class="btn btn-sm btn-outline-primary no-print" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Report
                    </button>
                </div>
                <div class="card-body">
                    @if($groupedGrades->count())
                        @foreach($groupedGrades as $term => $records)
                            <div class="mb-4">
                                <h5 class="fw-bold border-bottom pb-2">{{ $term }}</h5>
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
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($records as $record)
                                                @php
                                                    $percentage = $record->total_marks > 0 ? round(($record->marks_obtained / $record->total_marks) * 100, 1) : 0;
                                                    $gradeClass = '';
                                                    if ($percentage >= 80) $gradeClass = 'table-success';
                                                    elseif ($percentage >= 60) $gradeClass = 'table-warning';
                                                    else $gradeClass = 'table-danger';
                                                @endphp
                                                <tr class="{{ $gradeClass }}">
                                                    <td><strong>{{ $record->subject->name ?? 'Unknown Subject' }}</strong></td>
                                                    <td>{{ $record->exam_type ?? '-' }}</td>
                                                    <td>{{ $record->exam_date ? $record->exam_date->format('M d, Y') : '-' }}</td>
                                                    <td>{{ $record->marks_obtained }}</td>
                                                    <td>{{ $record->total_marks }}</td>
                                                    <td><strong>{{ $percentage }}%</strong></td>
                                                    <td><span class="badge bg-primary">{{ $record->grade }}</span></td>
                                                    <td>{{ $record->remarks ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-file-alt fa-2x mb-2"></i>
                            <p>No grade history available.</p>
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
