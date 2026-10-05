@extends('layouts.app')

@section('title', 'AI Features Dashboard')

@section('content')
<div class="container-xl py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a237e;">AI Features Dashboard</h2>
            <p class="text-muted mb-0">Leverage artificial intelligence to enhance your examination system</p>
        </div>
        <div>
            <a href="{{ route('examination.ai.question-generator') }}" class="btn btn-primary">
                <i class="fas fa-magic me-2"></i>Generate Questions
            </a>
        </div>
    </div>

    <!-- AI Features Overview -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-magic fa-3x mb-3"></i>
                    <h5>Question Generator</h5>
                    <p class="mb-0">AI-powered question creation from syllabus and notes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body text-center">
                    <i class="fas fa-shield-alt fa-3x mb-3"></i>
                    <h5>Cheating Detection</h5>
                    <p class="mb-0">Advanced AI analysis to detect suspicious behavior</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-brain fa-3x mb-3"></i>
                    <h5>Adaptive Questions</h5>
                    <p class="mb-0">Dynamic question difficulty based on student performance</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                    <h5>Analytics</h5>
                    <p class="mb-0">AI-driven insights and performance analytics</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
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
                                <a href="{{ route('examination.ai.question-generator') }}" class="btn btn-outline-primary btn-lg">
                                    <i class="fas fa-magic me-2"></i>Generate Questions
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <a href="{{ route('examination.ai.cheating-analysis') }}" class="btn btn-outline-warning btn-lg">
                                    <i class="fas fa-shield-alt me-2"></i>Cheating Analysis
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <button class="btn btn-outline-success btn-lg" onclick="showAdaptiveQuestions()">
                                    <i class="fas fa-brain me-2"></i>Adaptive Questions
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-grid">
                                <button class="btn btn-outline-info btn-lg" onclick="showAIAnalytics()">
                                    <i class="fas fa-chart-line me-2"></i>AI Analytics
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>AI Features Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Question Generation</span>
                        <span class="badge bg-success">Active</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Cheating Detection</span>
                        <span class="badge bg-success">Active</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Adaptive Learning</span>
                        <span class="badge bg-warning">Beta</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Analytics Engine</span>
                        <span class="badge bg-success">Active</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent AI Activity -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Recent AI Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-magic text-primary me-2"></i>
                                <strong>Question Generation</strong>
                                <br>
                                <small class="text-muted">Generated 15 questions for Mathematics Midterm</small>
                            </div>
                            <small class="text-muted">2 hours ago</small>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-shield-alt text-warning me-2"></i>
                                <strong>Cheating Analysis</strong>
                                <br>
                                <small class="text-muted">Detected 3 suspicious activities in Physics Final</small>
                            </div>
                            <small class="text-muted">4 hours ago</small>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-brain text-success me-2"></i>
                                <strong>Adaptive Questions</strong>
                                <br>
                                <small class="text-muted">Adjusted difficulty for Chemistry Quiz</small>
                            </div>
                            <small class="text-muted">1 day ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showAdaptiveQuestions() {
    alert('Adaptive Questions feature coming soon!');
}

function showAIAnalytics() {
    alert('AI Analytics feature coming soon!');
}
</script>
@endpush
@endsection
