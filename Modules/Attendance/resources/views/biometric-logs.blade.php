@extends('layouts.app')

@section('title', 'Biometric Attendance Logs')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-fingerprint me-2"></i>Biometric Attendance Logs</h4>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Student ID</label>
                    <input type="number" name="student_id" class="form-control" placeholder="Enter student ID" value="{{ request('student_id') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-search me-1"></i> Search</button>
                    <a href="{{ route('attendance.biometric-logs') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Device</th>
                        <th>Status</th>
                        <th>Confidence</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->first_name ?? 'N/A' }} {{ $log->last_name ?? '' }}</td>
                        <td>{{ $log->date ?? '-' }}</td>
                        <td>{{ $log->time ?? '-' }}</td>
                        <td>{{ $log->device_id ?? '-' }}</td>
                        <td>
                            @if(($log->status ?? '') === 'matched')
                                <span class="badge bg-success">Matched</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ ucfirst($log->status ?? 'unknown') }}</span>
                            @endif
                        </td>
                        <td>{{ $log->confidence ?? '-' }}%</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No biometric logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
