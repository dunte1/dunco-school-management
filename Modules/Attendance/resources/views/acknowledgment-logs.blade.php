@extends('layouts.app')

@section('title', 'Acknowledgment Logs')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-check-double me-2"></i>Acknowledgment Logs</h4>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-search me-1"></i> Search</button>
                    <a href="{{ route('attendance.acknowledgment-logs') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th>Attendance Status</th>
                        <th>Acknowledged By</th>
                        <th>Method</th>
                        <th>Acknowledged At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->first_name ?? 'N/A' }} {{ $log->last_name ?? '' }}</td>
                        <td>{{ $log->date ?? '-' }}</td>
                        <td>
                            @if(($log->attendance_status ?? '') === 'present')
                                <span class="badge bg-success">Present</span>
                            @elseif(($log->attendance_status ?? '') === 'absent')
                                <span class="badge bg-danger">Absent</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($log->attendance_status ?? '-') }}</span>
                            @endif
                        </td>
                        <td>{{ $log->acknowledged_by_name ?? '-' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $log->method ?? '-')) }}</td>
                        <td>{{ $log->created_at ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No acknowledgment logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
