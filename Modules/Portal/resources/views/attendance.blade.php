@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-calendar-check me-2"></i>My Attendance</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.attendance') }}" class="d-flex align-items-center gap-2">
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
        {{-- Attendance Summary Cards --}}
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-percentage fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $attendancePercentage }}%</h4>
                    <p class="mb-0">Attendance Rate</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $presentDays }}</h4>
                    <p class="mb-0">Days Present</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $absentDays }}</h4>
                    <p class="mb-0">Days Absent</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $lateDays }}</h4>
                    <p class="mb-0">Days Late</p>
                </div>
            </div>
        </div>

        {{-- Current Month Calendar --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Current Month Attendance</h5>
                </div>
                <div class="card-body">
                    @if($currentMonthAttendance->count())
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Time</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($currentMonthAttendance as $record)
                                        @php
                                            $statusClass = '';
                                            switch($record->status) {
                                                case 'present': $statusClass = 'table-success'; break;
                                                case 'absent': $statusClass = 'table-danger'; break;
                                                case 'late': $statusClass = 'table-warning'; break;
                                                default: $statusClass = 'table-secondary';
                                            }
                                        @endphp
                                        <tr class="{{ $statusClass }}">
                                            <td>{{ $record->date ? $record->date->format('M d, Y') : '-' }}</td>
                                            <td>{{ $record->subject->name ?? 'All Subjects' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $record->status === 'present' ? 'success' : ($record->status === 'absent' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($record->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $record->time ?? '-' }}</td>
                                            <td>{{ $record->remarks ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                            <p>No attendance records for current month.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Attendance by Subject --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i>Attendance by Subject</h5>
                </div>
                <div class="card-body">
                    @if($attendanceBySubject->count())
                        <div class="list-group list-group-flush">
                            @foreach($attendanceBySubject as $subject => $stats)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $subject }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $stats['present'] }}/{{ $stats['total'] }} days</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ $stats['percentage'] >= 90 ? 'success' : ($stats['percentage'] >= 75 ? 'warning' : 'danger') }}">
                                        {{ $stats['percentage'] }}%
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-book fa-2x mb-2"></i>
                            <p>No subject-specific attendance data.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Detailed Attendance History --}}
        <div class="col-lg-12 printable-area">
            <div class="card" id="attendance-section">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Detailed Attendance History</h5>
                    <button class="btn btn-sm btn-outline-primary no-print" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Report
                    </button>
                </div>
                <div class="card-body">
                    @if($groupedAttendance->count())
                        @foreach($groupedAttendance as $month => $records)
                            <div class="mb-4">
                                <h5 class="fw-bold border-bottom pb-2">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</h5>
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th>Day</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Time</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($records as $record)
                                                @php
                                                    $statusClass = '';
                                                    switch($record->status) {
                                                        case 'present': $statusClass = 'table-success'; break;
                                                        case 'absent': $statusClass = 'table-danger'; break;
                                                        case 'late': $statusClass = 'table-warning'; break;
                                                        default: $statusClass = 'table-secondary';
                                                    }
                                                @endphp
                                                <tr class="{{ $statusClass }}">
                                                    <td>{{ $record->date ? $record->date->format('M d, Y') : '-' }}</td>
                                                    <td>{{ $record->date ? $record->date->format('D') : '-' }}</td>
                                                    <td>{{ $record->subject->name ?? 'All Subjects' }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $record->status === 'present' ? 'success' : ($record->status === 'absent' ? 'danger' : 'warning') }}">
                                                            {{ ucfirst($record->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $record->time ?? '-' }}</td>
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
                            <p>No attendance history available.</p>
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
