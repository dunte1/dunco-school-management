@extends('layouts.app')

@section('title', 'Reports Dashboard')

@section('content')
<div class="container-xl py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a237e;">Reports & Analytics</h2>
            <p class="text-muted mb-0">Comprehensive reporting and performance analytics</p>
        </div>
        <div>
            <button class="btn btn-primary" onclick="exportReport()">
                <i class="fas fa-download me-2"></i>Export Report
            </button>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['total_exams'] ?? 0 }}</h3>
                    <p class="mb-0">Total Exams</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['total_students'] ?? 0 }}</h3>
                    <p class="mb-0">Students</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['avg_score'] ?? 0 }}%</h3>
                    <p class="mb-0">Average Score</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['completion_rate'] ?? 0 }}%</h3>
                    <p class="mb-0">Completion Rate</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Reports -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Quick Reports
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <a href="{{ route('examination.reports.exam-analytics', ['examId' => 1]) }}" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-chart-line me-2"></i>Exam Analytics
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <a href="{{ route('examination.reports.student-performance') }}" class="btn btn-outline-success btn-lg">
                                    <i class="fas fa-user-graduate me-2"></i>Student Performance
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <a href="{{ route('examination.reports.proctoring') }}" class="btn btn-outline-warning btn-lg">
                                    <i class="fas fa-shield-alt me-2"></i>Proctoring Reports
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <button class="btn btn-outline-info btn-lg" onclick="customReport()">
                                    <i class="fas fa-cog me-2"></i>Custom Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Recent Reports
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-chart-line text-primary me-2"></i>
                                <strong>Mathematics Midterm Analytics</strong>
                                <br>
                                <small class="text-muted">Generated 2 hours ago</small>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-download"></i> Download
                                </button>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-user-graduate text-success me-2"></i>
                                <strong>Student Performance Report</strong>
                                <br>
                                <small class="text-muted">Generated 4 hours ago</small>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-download"></i> Download
                                </button>
                            </div>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-shield-alt text-warning me-2"></i>
                                <strong>Proctoring Summary</strong>
                                <br>
                                <small class="text-muted">Generated 1 day ago</small>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-download"></i> Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Summary -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Performance Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Mathematics</span>
                            <span>85%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" style="width: 85%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Physics</span>
                            <span>72%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 72%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Chemistry</span>
                            <span>68%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: 68%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Biology</span>
                            <span>91%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: 91%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Types -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Report Types
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        <span>Performance Analytics</span>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-users text-success me-2"></i>
                        <span>Student Reports</span>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-shield-alt text-warning me-2"></i>
                        <span>Proctoring Reports</span>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-graduation-cap text-info me-2"></i>
                        <span>Academic Reports</span>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-calendar text-secondary me-2"></i>
                        <span>Schedule Reports</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportReport() {
    alert('Export report feature coming soon!');
}

function customReport() {
    alert('Custom report builder coming soon!');
}
</script>
@endpush
@endsection
