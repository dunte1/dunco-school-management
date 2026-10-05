@extends('layouts.app')

@section('title', 'HR Statistics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">HR Statistics Dashboard</h4>
                    <div class="btn-group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="exportStatistics('pdf')"><i class="fas fa-file-pdf me-2"></i>Export as PDF</a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportStatistics('excel')"><i class="fas fa-file-excel me-2"></i>Export as Excel</a></li>
                            <li><a class="dropdown-item" href="#" onclick="printStatistics()"><i class="fas fa-print me-2"></i>Print Statistics</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Statistics Navigation -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Statistics Modules</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <a href="{{ route('hr.statistics.attendance') }}" class="btn btn-outline-primary btn-lg w-100 mb-3">
                                                <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                                                Attendance Statistics
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('hr.statistics.payroll') }}" class="btn btn-outline-success btn-lg w-100 mb-3">
                                                <i class="fas fa-chart-pie fa-2x mb-2"></i><br>
                                                Payroll Statistics
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('hr.statistics.performance') }}" class="btn btn-outline-info btn-lg w-100 mb-3">
                                                <i class="fas fa-chart-line fa-2x mb-2"></i><br>
                                                Performance Statistics
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Key Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="text-primary">{{ $stats['total_employees'] ?? 0 }}</h2>
                                    <p class="mb-0">Total Employees</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="text-success">{{ $stats['active_employees'] ?? 0 }}</h2>
                                    <p class="mb-0">Active</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="text-warning">{{ $stats['on_leave'] ?? 0 }}</h2>
                                    <p class="mb-0">On Leave</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="text-info">{{ $stats['departments'] ?? 0 }}</h2>
                                    <p class="mb-0">Departments</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="text-danger">{{ $stats['pending_requests'] ?? 0 }}</h2>
                                    <p class="mb-0">Pending</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h2 class="text-secondary">{{ $stats['avg_attendance'] ?? 0 }}%</h2>
                                    <p class="mb-0">Avg Attendance</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Attendance Statistics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h3 class="text-success">{{ $attendance_stats['present_today'] ?? 0 }}</h3>
                                                <p class="mb-0">Present Today</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h3 class="text-danger">{{ $attendance_stats['absent_today'] ?? 0 }}</h3>
                                                <p class="mb-0">Absent Today</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h4 class="text-primary">{{ $attendance_stats['late_arrivals'] ?? 0 }}</h4>
                                                <p class="mb-0">Late Arrivals</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h4 class="text-warning">{{ $attendance_stats['early_departures'] ?? 0 }}</h4>
                                                <p class="mb-0">Early Departures</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Leave Statistics</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="text-center">
                                                <h3 class="text-warning">{{ $leave_stats['pending'] ?? 0 }}</h3>
                                                <p class="mb-0">Pending</p>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-center">
                                                <h3 class="text-success">{{ $leave_stats['approved'] ?? 0 }}</h3>
                                                <p class="mb-0">Approved</p>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-center">
                                                <h3 class="text-danger">{{ $leave_stats['rejected'] ?? 0 }}</h3>
                                                <p class="mb-0">Rejected</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h4 class="text-info">{{ $leave_stats['total_days'] ?? 0 }}</h4>
                                                <p class="mb-0">Total Leave Days</p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h4 class="text-secondary">{{ $leave_stats['avg_days'] ?? 0 }}</h4>
                                                <p class="mb-0">Avg Days/Request</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Department Breakdown -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Department Breakdown</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Department</th>
                                                    <th>Total Staff</th>
                                                    <th>Present Today</th>
                                                    <th>On Leave</th>
                                                    <th>Attendance Rate</th>
                                                    <th>Avg Performance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($department_stats) && count($department_stats) > 0)
                                                    @foreach($department_stats as $dept)
                                                        <tr>
                                                            <td>
                                                                <strong>{{ $dept->name ?? 'N/A' }}</strong>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-primary">{{ $dept->total_staff ?? 0 }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-success">{{ $dept->present_today ?? 0 }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-warning">{{ $dept->on_leave ?? 0 }}</span>
                                                            </td>
                                                            <td>
                                                                <div class="progress" style="height: 20px;">
                                                                    <div class="progress-bar" role="progressbar" style="width: {{ $dept->attendance_rate ?? 0 }}%">
                                                                        {{ $dept->attendance_rate ?? 0 }}%
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-info">{{ $dept->avg_performance ?? 0 }}/10</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center">No department data available.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Trends -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Monthly Trends (Last 6 Months)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if(isset($monthly_trends) && count($monthly_trends) > 0)
                                            @foreach($monthly_trends as $month => $data)
                                                <div class="col-md-2">
                                                    <div class="card text-center">
                                                        <div class="card-body">
                                                            <h6 class="text-muted">{{ $month }}</h6>
                                                            <h4 class="text-primary">{{ $data['attendance'] ?? 0 }}%</h4>
                                                            <small class="text-muted">Attendance</small>
                                                            <hr>
                                                            <h5 class="text-success">{{ $data['leaves'] ?? 0 }}</h5>
                                                            <small class="text-muted">Leave Days</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-md-12">
                                                <p class="text-center text-muted">No trend data available.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportStatistics(format) {
    const url = new URL(window.location);
    url.searchParams.set('export', format);
    window.open(url.toString(), '_blank');
}

function printStatistics() {
    window.print();
}

// Print styles
const printStyles = `
    @media print {
        .btn, .dropdown, .card-header .btn-group { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .container-fluid { padding: 0 !important; }
        .table { font-size: 12px !important; }
        .progress { height: 15px !important; }
    }
`;

// Add print styles to head
const style = document.createElement('style');
style.textContent = printStyles;
document.head.appendChild(style);
</script>
@endsection
