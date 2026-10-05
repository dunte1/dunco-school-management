@extends('layouts.app')

@section('title', 'HR Analytics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">HR Analytics Dashboard</h4>
                    <div class="btn-group">
                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="exportAnalytics('pdf')"><i class="fas fa-file-pdf me-2"></i>Export as PDF</a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportAnalytics('excel')"><i class="fas fa-file-excel me-2"></i>Export as Excel</a></li>
                            <li><a class="dropdown-item" href="#" onclick="printAnalytics()"><i class="fas fa-print me-2"></i>Print Dashboard</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Analytics Navigation -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Analytics Modules</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <a href="{{ route('hr.analytics.attendance') }}" class="btn btn-outline-primary btn-lg w-100 mb-3">
                                                <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                                                Attendance Analytics
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('hr.analytics.payroll') }}" class="btn btn-outline-success btn-lg w-100 mb-3">
                                                <i class="fas fa-chart-pie fa-2x mb-2"></i><br>
                                                Payroll Analytics
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('hr.analytics.performance') }}" class="btn btn-outline-info btn-lg w-100 mb-3">
                                                <i class="fas fa-chart-line fa-2x mb-2"></i><br>
                                                Performance Analytics
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Key Metrics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-gradient-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>{{ $metrics['attendance_rate'] ?? 0 }}%</h3>
                                            <p class="mb-0">Attendance Rate</p>
                                            <small>Last 30 days</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-chart-line fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>{{ $metrics['productivity_score'] ?? 0 }}</h3>
                                            <p class="mb-0">Productivity Score</p>
                                            <small>Average rating</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-star fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>{{ $metrics['overtime_hours'] ?? 0 }}</h3>
                                            <p class="mb-0">Overtime Hours</p>
                                            <small>This month</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>{{ $metrics['cost_per_employee'] ?? 0 }}</h3>
                                            <p class="mb-0">Cost per Employee</p>
                                            <small>Monthly average</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-dollar-sign fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Attendance Trends</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="attendanceChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Department Distribution</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="departmentChart" width="400" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Table -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Top Performers</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Rank</th>
                                                    <th>Staff Member</th>
                                                    <th>Department</th>
                                                    <th>Performance Score</th>
                                                    <th>Attendance Rate</th>
                                                    <th>Overtime Hours</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($top_performers ?? [] as $index => $performer)
                                                    <tr>
                                                        <td>
                                                            <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }}">
                                                                #{{ $index + 1 }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $performer->name ?? 'N/A' }}</td>
                                                        <td>{{ $performer->department ?? 'N/A' }}</td>
                                                        <td>
                                                            <div class="progress" style="height: 20px;">
                                                                <div class="progress-bar" role="progressbar" style="width: {{ $performer->score ?? 0 }}%">
                                                                    {{ $performer->score ?? 0 }}%
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $performer->attendance ?? 0 }}%</td>
                                                        <td>{{ $performer->overtime ?? 0 }}h</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center">No performance data available.</td>
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Attendance Chart
const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
new Chart(attendanceCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Attendance Rate (%)',
            data: [85, 87, 89, 88, 90, 92],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// Department Chart
const departmentCtx = document.getElementById('departmentChart').getContext('2d');
new Chart(departmentCtx, {
    type: 'doughnut',
    data: {
        labels: ['Teaching', 'Administration', 'Support', 'Management'],
        datasets: [{
            data: [40, 25, 20, 15],
            backgroundColor: [
                'rgba(255, 99, 132, 0.8)',
                'rgba(54, 162, 235, 0.8)',
                'rgba(255, 205, 86, 0.8)',
                'rgba(75, 192, 192, 0.8)'
            ]
        }]
    },
    options: {
        responsive: true
    }
});

function exportAnalytics(format) {
    const url = new URL(window.location);
    url.searchParams.set('export', format);
    window.open(url.toString(), '_blank');
}

function printAnalytics() {
    window.print();
}

// Print styles
const printStyles = `
    @media print {
        .btn, .dropdown, .card-header .btn-group { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .container-fluid { padding: 0 !important; }
        canvas { max-width: 100% !important; height: auto !important; }
    }
`;

// Add print styles to head
const style = document.createElement('style');
style.textContent = printStyles;
document.head.appendChild(style);
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}
.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #1e7e34);
}
.bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #e0a800);
}
.bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #138496);
}
</style>
@endsection
