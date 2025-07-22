@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-uppercase" style="color:#6ec1e4;">{{ $student->full_name ?? $student->name }} - Attendance History</h2>
    <div class="mb-3">
        <span class="badge bg-success">Present: {{ $presentDays }}</span>
        <span class="badge bg-danger">Absent: {{ $absentDays }}</span>
        <span class="badge bg-warning text-dark">Late: {{ $lateDays }}</span>
        <span class="badge bg-info text-dark">Excused: {{ $excusedDays }}</span>
        <span class="badge bg-primary">Attendance Rate: {{ $attendanceRate }}%</span>
    </div>
    <div class="table-responsive bg-dark rounded shadow p-3">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Class</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceRecords as $record)
                <tr>
                    <td>{{ $record->date->format('Y-m-d') }}</td>
                    <td>{{ $record->class->name ?? '-' }}</td>
                    <td><span class="badge bg-{{ $record->status_color }} text-uppercase">{{ ucfirst($record->status) }}</span></td>
                    <td>{{ $record->remarks }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $attendanceRecords->links() }}
    </div>
</div>
@endsection
@push('styles')
<style>
body { background: #0a1931; color: #eaf6fb; }
.table-dark th, .table-dark td { color: #eaf6fb; }
.badge.bg-success { background: #00bcd4; }
.badge.bg-danger { background: #e53935; }
.badge.bg-warning { background: #ffb300; color: #222; }
.badge.bg-info { background: #6ec1e4; color: #222; }
.badge.bg-primary { background: #1565c0; }
</style>
@endpush