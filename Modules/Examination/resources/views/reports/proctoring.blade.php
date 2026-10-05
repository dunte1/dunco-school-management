@extends('layouts.app')

@section('title', 'Proctoring Reports')

@section('content')
<div class="container-xl py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a237e;">
                <i class="fas fa-shield-alt me-2"></i>Proctoring Reports
            </h2>
            <p class="text-muted mb-0">Monitor exam integrity and proctoring violations</p>
        </div>
        <div>
            <a href="{{ route('examination.reports.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('examination.reports.proctoring') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="exam_id" class="form-label">Select Exam</label>
                    <select name="exam_id" id="exam_id" class="form-select">
                        <option value="">All Proctored Exams</option>
                        @foreach($report['exams'] ?? [] as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                {{ $exam->name }} - {{ $exam->academic_year }} {{ $exam->term }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="date_range" class="form-label">Date Range</label>
                    <input type="text" name="date_range" id="date_range" class="form-control" 
                           value="{{ request('date_range') }}" placeholder="Select date range">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('examination.reports.proctoring') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $report['summary']['total_attempts'] ?? 0 }}</h3>
                    <p class="mb-0">Total Proctored Attempts</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $report['summary']['total_violations'] ?? 0 }}</h3>
                    <p class="mb-0">Total Violations</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $report['summary']['flagged_attempts'] ?? 0 }}</h3>
                    <p class="mb-0">Flagged Attempts</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $report['summary']['clean_attempts'] ?? 0 }}</h3>
                    <p class="mb-0">Clean Attempts</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Violation Types Chart -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Violation Types
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="violationTypesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Violations Over Time
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="violationsOverTimeChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Violations Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Detailed Violations
            </h5>
        </div>
        <div class="card-body">
            @if(isset($report['violations']) && count($report['violations']) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Exam</th>
                                <th>Violation Type</th>
                                <th>Severity</th>
                                <th>Timestamp</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($report['violations'] as $violation)
                                <tr class="{{ $violation['severity'] === 'high' ? 'table-danger' : ($violation['severity'] === 'medium' ? 'table-warning' : '') }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                {{ substr($violation['student_name'], 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $violation['student_name'] }}</div>
                                                <small class="text-muted">{{ $violation['student_id'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ $violation['exam_name'] }}</div>
                                            <small class="text-muted">{{ $violation['exam_date'] }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $violation['type'] === 'face_detection' ? 'info' : ($violation['type'] === 'tab_switch' ? 'warning' : 'danger') }}">
                                            {{ ucfirst(str_replace('_', ' ', $violation['type'])) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $violation['severity'] === 'high' ? 'danger' : ($violation['severity'] === 'medium' ? 'warning' : 'success') }}">
                                            {{ ucfirst($violation['severity']) }}
                                        </span>
                                    </td>
                                    <td>{{ $violation['timestamp'] }}</td>
                                    <td>{{ $violation['description'] }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewViolationDetails({{ $violation['id'] }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="approveViolation({{ $violation['id'] }})">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="rejectViolation({{ $violation['id'] }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shield-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Violations Found</h5>
                    <p class="text-muted">No proctoring violations detected for the selected criteria.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Export Options -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Export Proctoring Report</h6>
                    <p class="text-muted mb-0">Download detailed proctoring data in various formats</p>
                </div>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-success" onclick="exportReport('csv')">
                        <i class="fas fa-file-csv me-2"></i>CSV
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="exportReport('excel')">
                        <i class="fas fa-file-excel me-2"></i>Excel
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="exportReport('pdf')">
                        <i class="fas fa-file-pdf me-2"></i>PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Violation Details Modal -->
<div class="modal fade" id="violationDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Violation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="violationDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize date range picker
flatpickr("#date_range", {
    mode: "range",
    dateFormat: "Y-m-d",
    maxDate: "today"
});

// Violation Types Chart
const violationTypesCtx = document.getElementById('violationTypesChart').getContext('2d');
new Chart(violationTypesCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($report['chart_data']['violation_types']['labels'] ?? []) !!},
        datasets: [{
            data: {!! json_encode($report['chart_data']['violation_types']['data'] ?? []) !!},
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Violations Over Time Chart
const violationsOverTimeCtx = document.getElementById('violationsOverTimeChart').getContext('2d');
new Chart(violationsOverTimeCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($report['chart_data']['violations_over_time']['labels'] ?? []) !!},
        datasets: [{
            label: 'Violations',
            data: {!! json_encode($report['chart_data']['violations_over_time']['data'] ?? []) !!},
            borderColor: '#FF6384',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// View violation details
function viewViolationDetails(violationId) {
    // This would typically make an AJAX call to get detailed violation data
    document.getElementById('violationDetailsContent').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;
    
    $('#violationDetailsModal').modal('show');
    
    // Simulate loading violation details
    setTimeout(() => {
        document.getElementById('violationDetailsContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Violation Information</h6>
                    <p><strong>Type:</strong> Face Detection</p>
                    <p><strong>Severity:</strong> Medium</p>
                    <p><strong>Timestamp:</strong> 2024-01-15 14:30:25</p>
                </div>
                <div class="col-md-6">
                    <h6>Student Information</h6>
                    <p><strong>Name:</strong> John Doe</p>
                    <p><strong>Student ID:</strong> STU001</p>
                    <p><strong>Exam:</strong> Mathematics Final</p>
                </div>
            </div>
            <div class="mt-3">
                <h6>Description</h6>
                <p>Student's face was not detected for more than 30 seconds during the exam.</p>
            </div>
        `;
    }, 1000);
}

// Approve violation
function approveViolation(violationId) {
    if (confirm('Are you sure you want to approve this violation?')) {
        // Make AJAX call to approve violation
        console.log('Approving violation:', violationId);
        // Add success message
        alert('Violation approved successfully!');
    }
}

// Reject violation
function rejectViolation(violationId) {
    if (confirm('Are you sure you want to reject this violation?')) {
        // Make AJAX call to reject violation
        console.log('Rejecting violation:', violationId);
        // Add success message
        alert('Violation rejected successfully!');
    }
}

// Export report
function exportReport(format) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("examination.reports.export") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const reportType = document.createElement('input');
    reportType.type = 'hidden';
    reportType.name = 'report_type';
    reportType.value = 'proctoring';
    
    const formatInput = document.createElement('input');
    formatInput.type = 'hidden';
    formatInput.name = 'format';
    formatInput.value = format;
    
    form.appendChild(csrfToken);
    form.appendChild(reportType);
    form.appendChild(formatInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
@endpush
