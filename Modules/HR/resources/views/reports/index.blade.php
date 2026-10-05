@extends('layouts.app')

@section('title', 'HR Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">HR Reports</h4>
                    <div class="btn-group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="exportReport('pdf')"><i class="fas fa-file-pdf me-2"></i>Export as PDF</a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportReport('excel')"><i class="fas fa-file-excel me-2"></i>Export as Excel</a></li>
                            <li><a class="dropdown-item" href="#" onclick="printReport()"><i class="fas fa-print me-2"></i>Print Report</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Report Selection -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Select Report Type</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="{{ route('hr.reports.attendance') }}" class="btn btn-outline-primary btn-lg w-100 mb-3">
                                                <i class="fas fa-clock fa-2x mb-2"></i><br>
                                                Attendance Report
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('hr.reports.leave') }}" class="btn btn-outline-success btn-lg w-100 mb-3">
                                                <i class="fas fa-calendar-alt fa-2x mb-2"></i><br>
                                                Leave Report
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('hr.reports.payroll') }}" class="btn btn-outline-warning btn-lg w-100 mb-3">
                                                <i class="fas fa-money-bill-wave fa-2x mb-2"></i><br>
                                                Payroll Report
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('hr.reports.performance') }}" class="btn btn-outline-info btn-lg w-100 mb-3">
                                                <i class="fas fa-chart-line fa-2x mb-2"></i><br>
                                                Performance Report
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $stats['total_staff'] ?? 0 }}</h4>
                                            <p class="mb-0">Total Staff</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-users fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $stats['present_today'] ?? 0 }}</h4>
                                            <p class="mb-0">Present Today</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $stats['on_leave'] ?? 0 }}</h4>
                                            <p class="mb-0">On Leave</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-calendar-times fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $stats['pending_approvals'] ?? 0 }}</h4>
                                            <p class="mb-0">Pending Approvals</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-hourglass-half fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Recent Activities</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Activity</th>
                                                    <th>Staff</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recent_activities ?? [] as $activity)
                                                    <tr>
                                                        <td>{{ $activity->created_at ? $activity->created_at->format('M d, Y H:i') : 'N/A' }}</td>
                                                        <td>{{ $activity->description ?? 'N/A' }}</td>
                                                        <td>{{ $activity->staff_name ?? 'N/A' }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $activity->status == 'completed' ? 'success' : ($activity->status == 'pending' ? 'warning' : 'secondary') }}">
                                                                {{ ucfirst($activity->status ?? 'unknown') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">No recent activities found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
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
function exportReport(format) {
    const url = new URL(window.location);
    url.searchParams.set('export', format);
    window.open(url.toString(), '_blank');
}

function printReport() {
    window.print();
}

// Print styles
const printStyles = `
    @media print {
        .btn, .dropdown, .card-header .btn-group { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .container-fluid { padding: 0 !important; }
    }
`;

// Add print styles to head
const style = document.createElement('style');
style.textContent = printStyles;
document.head.appendChild(style);
</script>
@endsection
