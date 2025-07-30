{{-- Create a new academics view file --}}
@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-graduation-cap me-2"></i>Academics</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.academics') }}" class="d-flex align-items-center gap-2">
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
        {{-- Progress Report --}}
        <div class="col-lg-12 no-print">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Progress Report - {{ date('Y') }}</h5></div>
                <div class="card-body">
                    @if($progressRecords->count())
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Exam Type</th>
                                <th>Term</th>
                                <th>Marks</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($progressRecords as $rec)
                            <tr>
                                <td>{{ $rec->subject->name ?? '-' }}</td>
                                <td>{{ $rec->exam_type ?? '-' }}</td>
                                <td>{{ $rec->term ?? '-' }}</td>
                                <td>{{ $rec->marks_obtained }}/{{ $rec->total_marks }}</td>
                                <td><span class="badge bg-primary">{{ $rec->grade }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                        <p>No progress records found for this year.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- Full Grade History / Transcript --}}
        <div class="col-lg-12 printable-area">
            <div class="card" id="transcript-section">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Full Grade History / Transcript</h5>
                    <button class="btn btn-sm btn-outline-primary no-print" onclick="window.print()"><i class="fas fa-print me-2"></i>Print Transcript</button>
                </div>
                <div class="card-body">
                    @if($examResults->count())
                        @php $grouped = $examResults->groupBy(['academic_year', 'term']); @endphp
                        @foreach($grouped as $year => $terms)
                            <div class="mb-4">
                                <h5 class="fw-bold">Academic Year: {{ $year }}</h5>
                                @foreach($terms as $term => $records)
                                    <div class="mb-2">
                                        <strong>Term: {{ $term }}</strong>
                                        <table class="table table-sm table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Exam Type</th>
                                                    <th>Date</th>
                                                    <th>Marks</th>
                                                    <th>Grade</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($records as $rec)
                                                    <tr>
                                                        <td>{{ $rec->subject->name ?? '-' }}</td>
                                                        <td>{{ $rec->exam_type ?? '-' }}</td>
                                                        <td>{{ $rec->exam_date->format('M d, Y') }}</td>
                                                        <td>{{ $rec->marks_obtained }}/{{ $rec->total_marks }}</td>
                                                        <td>{{ $rec->grade }}</td>
                                                        <td>{{ $rec->remarks ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-file-signature fa-2x mb-2"></i>
                        <p>No transcript data available.</p>
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
}
</style>
@endpush 