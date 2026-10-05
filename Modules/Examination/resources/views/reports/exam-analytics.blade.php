@extends('layouts.app')

@section('title', 'Exam Analytics')

@section('content')
<div class="container-xl py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a237e;">Exam Analytics</h2>
            <p class="text-muted mb-0">Detailed performance analysis for {{ $exam->name ?? 'Selected Exam' }}</p>
        </div>
        <div>
            <a href="{{ route('examination.reports.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Exam Overview -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['total_attempts'] ?? 0 }}</h3>
                    <p class="mb-0">Total Attempts</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['avg_score'] ?? 0 }}%</h3>
                    <p class="mb-0">Average Score</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['pass_rate'] ?? 0 }}%</h3>
                    <p class="mb-0">Pass Rate</p>
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
        <!-- Performance Chart -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>Performance Trends
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" height="300"></canvas>
                </div>
            </div>

            <!-- Question Analysis -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>Question Analysis
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Type</th>
                                    <th>Difficulty</th>
                                    <th>Avg Score</th>
                                    <th>Attempts</th>
                                    <th>Success Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="question-preview">
                                            <strong>Q1:</strong> Solve the equation x² + 5x + 6 = 0
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">Essay</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">Medium</span>
                                            <div class="progress" style="width: 60px; height: 8px;">
                                                <div class="progress-bar bg-warning" style="width: 60%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><strong>7.2/10</strong></td>
                                    <td>45</td>
                                    <td>
                                        <span class="text-success">78%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="question-preview">
                                            <strong>Q2:</strong> Explain Newton's laws of motion
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">Short Answer</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">Hard</span>
                                            <div class="progress" style="width: 60px; height: 8px;">
                                                <div class="progress-bar bg-danger" style="width: 80%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><strong>5.8/10</strong></td>
                                    <td>45</td>
                                    <td>
                                        <span class="text-warning">52%</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="question-preview">
                                            <strong>Q3:</strong> Calculate the derivative of x³
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">Multiple Choice</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">Easy</span>
                                            <div class="progress" style="width: 60px; height: 8px;">
                                                <div class="progress-bar bg-success" style="width: 30%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><strong>8.9/10</strong></td>
                                    <td>45</td>
                                    <td>
                                        <span class="text-success">92%</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Summary -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Score Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="scoreDistributionChart" height="200"></canvas>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Time Analysis
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Average Time</span>
                            <span>1h 25m</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" style="width: 71%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Fastest Completion</span>
                            <span>45m</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 38%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Slowest Completion</span>
                            <span>1h 58m</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: 98%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>Top Performers
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div class="fw-bold">John Doe</div>
                                <small class="text-muted">ID: 12345</small>
                            </div>
                            <span class="badge bg-success">95%</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div class="fw-bold">Jane Smith</div>
                                <small class="text-muted">ID: 12346</small>
                            </div>
                            <span class="badge bg-success">92%</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <div class="fw-bold">Mike Johnson</div>
                                <small class="text-muted">ID: 12347</small>
                            </div>
                            <span class="badge bg-primary">88%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Performance Chart
const ctx1 = document.getElementById('performanceChart').getContext('2d');
new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
        datasets: [{
            label: 'Average Score',
            data: [65, 72, 68, 75, 78, 82],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});

// Score Distribution Chart
const ctx2 = document.getElementById('scoreDistributionChart').getContext('2d');
new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['A (90-100)', 'B (80-89)', 'C (70-79)', 'D (60-69)', 'F (0-59)'],
        datasets: [{
            data: [15, 25, 30, 20, 10],
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
</script>
@endpush
@endsection
