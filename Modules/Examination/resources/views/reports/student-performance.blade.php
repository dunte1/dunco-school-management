@extends('layouts.app')

@section('title', 'Student Performance')

@section('content')
<div class="container-xl py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a237e;">Student Performance</h2>
            <p class="text-muted mb-0">Individual and class performance analytics</p>
        </div>
        <div>
            <a href="{{ route('examination.reports.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Performance Overview -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['total_students'] ?? 0 }}</h3>
                    <p class="mb-0">Total Students</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['avg_performance'] ?? 0 }}%</h3>
                    <p class="mb-0">Average Performance</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['improvement_rate'] ?? 0 }}%</h3>
                    <p class="mb-0">Improvement Rate</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['active_students'] ?? 0 }}</h3>
                    <p class="mb-0">Active Students</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Class</label>
                    <select class="form-select" name="class_id">
                        <option value="">All Classes</option>
                        <option value="1">Form 1A</option>
                        <option value="2">Form 1B</option>
                        <option value="3">Form 2A</option>
                        <option value="4">Form 2B</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Subject</label>
                    <select class="form-select" name="subject_id">
                        <option value="">All Subjects</option>
                        <option value="1">Mathematics</option>
                        <option value="2">Physics</option>
                        <option value="3">Chemistry</option>
                        <option value="4">Biology</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Performance Range</label>
                    <select class="form-select" name="performance_range">
                        <option value="">All Ranges</option>
                        <option value="excellent">Excellent (90-100%)</option>
                        <option value="good">Good (80-89%)</option>
                        <option value="average">Average (70-79%)</option>
                        <option value="below_average">Below Average (60-69%)</option>
                        <option value="poor">Poor (0-59%)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Student Performance List -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-user-graduate me-2"></i>Student Performance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Exams Taken</th>
                                    <th>Average Score</th>
                                    <th>Improvement</th>
                                    <th>Rank</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                JD
                                            </div>
                                            <div>
                                                <div class="fw-bold">John Doe</div>
                                                <small class="text-muted">ID: 12345</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Form 1A</td>
                                    <td>8</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold text-success me-2">92%</span>
                                            <div class="progress" style="width: 60px; height: 8px;">
                                                <div class="progress-bar bg-success" style="width: 92%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-success">
                                            <i class="fas fa-arrow-up me-1"></i>+5%
                                        </span>
                                    </td>
                                    <td><span class="badge bg-success">1st</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewStudentDetails(1)">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success text-white rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                JS
                                            </div>
                                            <div>
                                                <div class="fw-bold">Jane Smith</div>
                                                <small class="text-muted">ID: 12346</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Form 1A</td>
                                    <td>8</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold text-primary me-2">88%</span>
                                            <div class="progress" style="width: 60px; height: 8px;">
                                                <div class="progress-bar bg-primary" style="width: 88%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-success">
                                            <i class="fas fa-arrow-up me-1"></i>+3%
                                        </span>
                                    </td>
                                    <td><span class="badge bg-primary">2nd</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewStudentDetails(2)">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-warning text-dark rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                MJ
                                            </div>
                                            <div>
                                                <div class="fw-bold">Mike Johnson</div>
                                                <small class="text-muted">ID: 12347</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Form 1B</td>
                                    <td>7</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold text-warning me-2">75%</span>
                                            <div class="progress" style="width: 60px; height: 8px;">
                                                <div class="progress-bar bg-warning" style="width: 75%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-danger">
                                            <i class="fas fa-arrow-down me-1"></i>-2%
                                        </span>
                                    </td>
                                    <td><span class="badge bg-warning">15th</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewStudentDetails(3)">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Analytics -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Performance Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" height="200"></canvas>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy me-2"></i>Top Performers
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning me-2">1</span>
                                <div>
                                    <div class="fw-bold">John Doe</div>
                                    <small class="text-muted">Form 1A</small>
                                </div>
                            </div>
                            <span class="text-success fw-bold">92%</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-secondary me-2">2</span>
                                <div>
                                    <div class="fw-bold">Jane Smith</div>
                                    <small class="text-muted">Form 1A</small>
                                </div>
                            </div>
                            <span class="text-primary fw-bold">88%</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning me-2">3</span>
                                <div>
                                    <div class="fw-bold">Sarah Wilson</div>
                                    <small class="text-muted">Form 1B</small>
                                </div>
                            </div>
                            <span class="text-info fw-bold">85%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Class Comparison
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Form 1A</span>
                            <span>89%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 89%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Form 1B</span>
                            <span>76%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" style="width: 76%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Form 2A</span>
                            <span>82%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-info" style="width: 82%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Student Details Modal -->
<div class="modal fade" id="studentDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Student Performance Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Student Information</h6>
                        <p><strong>Name:</strong> John Doe</p>
                        <p><strong>ID:</strong> 12345</p>
                        <p><strong>Class:</strong> Form 1A</p>
                        <p><strong>Email:</strong> john.doe@school.com</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Performance Summary</h6>
                        <p><strong>Average Score:</strong> 92%</p>
                        <p><strong>Exams Taken:</strong> 8</p>
                        <p><strong>Rank:</strong> 1st</p>
                        <p><strong>Improvement:</strong> +5%</p>
                    </div>
                </div>
                
                <h6 class="mt-3">Exam History</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Exam</th>
                                <th>Score</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Mathematics Midterm</td>
                                <td>95%</td>
                                <td>2024-10-10</td>
                                <td><span class="badge bg-success">Completed</span></td>
                            </tr>
                            <tr>
                                <td>Physics Final</td>
                                <td>88%</td>
                                <td>2024-10-08</td>
                                <td><span class="badge bg-success">Completed</span></td>
                            </tr>
                            <tr>
                                <td>Chemistry Quiz</td>
                                <td>92%</td>
                                <td>2024-10-05</td>
                                <td><span class="badge bg-success">Completed</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Generate Report</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Performance Chart
const ctx = document.getElementById('performanceChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Excellent (90-100%)', 'Good (80-89%)', 'Average (70-79%)', 'Below Average (60-69%)', 'Poor (0-59%)'],
        datasets: [{
            data: [25, 35, 25, 10, 5],
            backgroundColor: [
                '#28a745',
                '#17a2b8',
                '#ffc107',
                '#fd7e14',
                '#dc3545'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

function viewStudentDetails(studentId) {
    const modal = new bootstrap.Modal(document.getElementById('studentDetailsModal'));
    modal.show();
}
</script>
@endpush
@endsection
