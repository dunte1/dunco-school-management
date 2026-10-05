@extends('layouts.app')

@section('title', 'Grading Dashboard')

@section('content')
<div class="container-xl py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a237e;">Grading Dashboard</h2>
            <p class="text-muted mb-0">Advanced grading and assessment management</p>
        </div>
        <div>
            <a href="{{ route('examination.grading.index') }}" class="btn btn-primary">
                <i class="fas fa-check-circle me-2"></i>Grade Questions
            </a>
        </div>
    </div>

    <!-- Grading Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['pending_grades'] ?? 0 }}</h3>
                    <p class="mb-0">Pending Grades</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['completed_grades'] ?? 0 }}</h3>
                    <p class="mb-0">Completed Grades</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['disputes'] ?? 0 }}</h3>
                    <p class="mb-0">Grade Disputes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $stats['rubrics'] ?? 0 }}</h3>
                    <p class="mb-0">Active Rubrics</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <a href="{{ route('examination.grading.index') }}" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-check-circle me-2"></i>Grade Questions
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <a href="{{ route('examination.grading.rubrics.index') }}" class="btn btn-outline-success btn-lg">
                                    <i class="fas fa-list-check me-2"></i>Manage Rubrics
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <a href="{{ route('examination.grading.disputes.index') }}" class="btn btn-outline-warning btn-lg">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Grade Disputes
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <button class="btn btn-outline-info btn-lg" onclick="batchGrade()">
                                    <i class="fas fa-layer-group me-2"></i>Batch Grade
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Grading Activity -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Recent Grading Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>Mathematics Midterm</strong>
                                <br>
                                <small class="text-muted">Graded 25 questions by John Smith</small>
                            </div>
                            <small class="text-muted">2 hours ago</small>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                <strong>Physics Final</strong>
                                <br>
                                <small class="text-muted">New dispute raised by student</small>
                            </div>
                            <small class="text-muted">4 hours ago</small>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-list-check text-info me-2"></i>
                                <strong>Chemistry Quiz</strong>
                                <br>
                                <small class="text-muted">Rubric updated by Jane Doe</small>
                            </div>
                            <small class="text-muted">1 day ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grading Tools -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Grading Tools
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Rubric-Based Grading</h6>
                        <p class="small text-muted">Use predefined rubrics for consistent evaluation</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">Multi-Teacher Moderation</h6>
                        <p class="small text-muted">Collaborative grading with multiple reviewers</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">Dispute Resolution</h6>
                        <p class="small text-muted">Handle grade disputes and appeals</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">Batch Processing</h6>
                        <p class="small text-muted">Grade multiple questions simultaneously</p>
                    </div>
                </div>
            </div>

            <!-- Grading Progress -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Grading Progress
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Mathematics Midterm</span>
                            <span>75%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" style="width: 75%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Physics Final</span>
                            <span>45%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" style="width: 45%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Chemistry Quiz</span>
                            <span>100%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function batchGrade() {
    alert('Batch grading feature coming soon!');
}
</script>
@endpush
@endsection
