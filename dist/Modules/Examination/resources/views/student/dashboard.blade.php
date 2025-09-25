@extends('layouts.app')

@section('title', 'My Exams Dashboard')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0" style="color: #1a237e; font-weight: 600;">
                <i class="fas fa-graduation-cap me-2"></i>My Exams Dashboard
            </h1>
            <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}! Here's your exam overview.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('examination.student.exams') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Browse Exams
            </a>
            @if($activeAttempt)
            <a href="{{ route('examination.online.start', $activeAttempt->exam) }}" class="btn btn-success">
                <i class="fas fa-play me-2"></i>Continue Exam
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Upcoming Exams</h6>
                            <h3 class="mb-0 fw-bold">{{ $upcomingExams->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Live Exams</h6>
                            <h3 class="mb-0 fw-bold">{{ $ongoingExams->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-broadcast-tower fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Completed</h6>
                            <h3 class="mb-0 fw-bold">{{ $recentAttempts->where('status', 'completed')->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Average Score</h6>
                            <h3 class="mb-0 fw-bold">
                                {{ $recentResults->count() > 0 ? round($recentResults->avg('score'), 1) : 'N/A' }}%
                            </h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-chart-line fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Upcoming Exams -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-calendar-alt me-2"></i>Upcoming Exams
                    </h5>
                    <a href="{{ route('examination.student.exams') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($upcomingExams->count() > 0)
                    <div class="space-y-3">
                        @foreach($upcomingExams as $exam)
                        <div class="d-flex align-items-center p-3 border rounded" style="background: #f8f9fa;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold" style="color: #1a237e;">{{ $exam->name }}</h6>
                                <div class="d-flex align-items-center gap-3 text-muted small">
                                    <span><i class="fas fa-clock me-1"></i>{{ $exam->start_date->format('M d, g:i A') }}</span>
                                    <span><i class="fas fa-hourglass-half me-1"></i>{{ $exam->duration_minutes }} min</span>
                                    <span class="badge bg-primary">{{ $exam->examType->name }}</span>
                                </div>
                            </div>
                            <div class="ms-3">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewExam({{ $exam->id }})">
                                    <i class="fas fa-eye me-1"></i>View
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No upcoming exams scheduled</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Live Exams -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-broadcast-tower me-2"></i>Live Exams
                    </h5>
                    <a href="{{ route('examination.student.exams') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($ongoingExams->count() > 0)
                    <div class="space-y-3">
                        @foreach($ongoingExams as $exam)
                        <div class="d-flex align-items-center p-3 border rounded" style="background: #e8f5e8;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold" style="color: #1a237e;">{{ $exam->name }}</h6>
                                <div class="d-flex align-items-center gap-3 text-muted small">
                                    <span><i class="fas fa-clock me-1"></i>Ends {{ $exam->end_date->format('g:i A') }}</span>
                                    <span><i class="fas fa-hourglass-half me-1"></i>{{ $exam->duration_minutes }} min</span>
                                    <span class="badge bg-success">LIVE</span>
                                </div>
                            </div>
                            <div class="ms-3">
                                <button class="btn btn-sm btn-success" onclick="startExam({{ $exam->id }})">
                                    <i class="fas fa-play me-1"></i>Start
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-broadcast-tower fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No live exams at the moment</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Attempts -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-history me-2"></i>Recent Attempts
                    </h5>
                    <a href="{{ route('examination.student.history') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentAttempts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Exam</th>
                                    <th>Status</th>
                                    <th>Started</th>
                                    <th>Duration</th>
                                    <th>Score</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttempts as $attempt)
                                <tr>
                                    <td>
                                        <div>
                                            <span class="fw-semibold">{{ $attempt->exam->name }}</span>
                                            <br>
                                            <small class="text-muted">{{ $attempt->exam->examType->name }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($attempt->status == 'completed') bg-success
                                            @elseif($attempt->status == 'in_progress') bg-warning
                                            @elseif($attempt->status == 'started') bg-info
                                            @else bg-secondary @endif">
                                            {{ strtoupper($attempt->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $attempt->created_at->format('M d, g:i A') }}</td>
                                    <td>
                                        @if($attempt->started_at && $attempt->completed_at)
                                            {{ $attempt->started_at->diffInMinutes($attempt->completed_at) }} min
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($attempt->status == 'completed' && $attempt->score !== null)
                                            <span class="fw-semibold">{{ $attempt->score }}%</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($attempt->status == 'completed')
                                        <a href="{{ route('examination.online.result', $attempt->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Result
                                        </a>
                                        @elseif(in_array($attempt->status, ['started', 'in_progress']))
                                        <a href="{{ route('examination.online.start', $attempt->exam) }}" 
                                           class="btn btn-sm btn-success">
                                            <i class="fas fa-play me-1"></i>Continue
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No exam attempts yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Results -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-trophy me-2"></i>Recent Results
                    </h5>
                    <a href="{{ route('examination.student.results') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentResults->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentResults as $result)
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold">{{ $result->exam->name }}</h6>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold 
                                        @if($result->score >= 80) text-success
                                        @elseif($result->score >= 60) text-warning
                                        @else text-danger @endif">
                                        {{ $result->score }}%
                                    </span>
                                    <span class="text-muted small">{{ $result->created_at->format('M d') }}</span>
                                </div>
                            </div>
                            <div class="ms-3">
                                <a href="{{ route('examination.online.result', $result->exam_attempt_id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No results available yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Active Attempt Alert -->
    @if($activeAttempt)
    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 1rem;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
            <div class="flex-grow-1">
                <h6 class="alert-heading mb-1">You have an active exam!</h6>
                <p class="mb-0">You have an ongoing exam: <strong>{{ $activeAttempt->exam->name }}</strong></p>
            </div>
            <a href="{{ route('examination.online.start', $activeAttempt->exam) }}" class="btn btn-warning">
                <i class="fas fa-play me-2"></i>Continue Exam
            </a>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function viewExam(examId) {
    window.location.href = `/examination/student/exams/${examId}`;
}

function startExam(examId) {
    if (confirm('Start this exam?')) {
        fetch(`/examination/student/exams/${examId}/start`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            alert('Failed to start exam');
        });
    }
}
</script>
@endpush 

@section('title', 'My Exams Dashboard')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0" style="color: #1a237e; font-weight: 600;">
                <i class="fas fa-graduation-cap me-2"></i>My Exams Dashboard
            </h1>
            <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}! Here's your exam overview.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('examination.student.exams') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Browse Exams
            </a>
            @if($activeAttempt)
            <a href="{{ route('examination.online.start', $activeAttempt->exam) }}" class="btn btn-success">
                <i class="fas fa-play me-2"></i>Continue Exam
            </a>
            @endif
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Upcoming Exams</h6>
                            <h3 class="mb-0 fw-bold">{{ $upcomingExams->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Live Exams</h6>
                            <h3 class="mb-0 fw-bold">{{ $ongoingExams->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-broadcast-tower fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Completed</h6>
                            <h3 class="mb-0 fw-bold">{{ $recentAttempts->where('status', 'completed')->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Average Score</h6>
                            <h3 class="mb-0 fw-bold">
                                {{ $recentResults->count() > 0 ? round($recentResults->avg('score'), 1) : 'N/A' }}%
                            </h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-chart-line fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Upcoming Exams -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-calendar-alt me-2"></i>Upcoming Exams
                    </h5>
                    <a href="{{ route('examination.student.exams') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($upcomingExams->count() > 0)
                    <div class="space-y-3">
                        @foreach($upcomingExams as $exam)
                        <div class="d-flex align-items-center p-3 border rounded" style="background: #f8f9fa;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold" style="color: #1a237e;">{{ $exam->name }}</h6>
                                <div class="d-flex align-items-center gap-3 text-muted small">
                                    <span><i class="fas fa-clock me-1"></i>{{ $exam->start_date->format('M d, g:i A') }}</span>
                                    <span><i class="fas fa-hourglass-half me-1"></i>{{ $exam->duration_minutes }} min</span>
                                    <span class="badge bg-primary">{{ $exam->examType->name }}</span>
                                </div>
                            </div>
                            <div class="ms-3">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewExam({{ $exam->id }})">
                                    <i class="fas fa-eye me-1"></i>View
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No upcoming exams scheduled</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Live Exams -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-broadcast-tower me-2"></i>Live Exams
                    </h5>
                    <a href="{{ route('examination.student.exams') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($ongoingExams->count() > 0)
                    <div class="space-y-3">
                        @foreach($ongoingExams as $exam)
                        <div class="d-flex align-items-center p-3 border rounded" style="background: #e8f5e8;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold" style="color: #1a237e;">{{ $exam->name }}</h6>
                                <div class="d-flex align-items-center gap-3 text-muted small">
                                    <span><i class="fas fa-clock me-1"></i>Ends {{ $exam->end_date->format('g:i A') }}</span>
                                    <span><i class="fas fa-hourglass-half me-1"></i>{{ $exam->duration_minutes }} min</span>
                                    <span class="badge bg-success">LIVE</span>
                                </div>
                            </div>
                            <div class="ms-3">
                                <button class="btn btn-sm btn-success" onclick="startExam({{ $exam->id }})">
                                    <i class="fas fa-play me-1"></i>Start
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-broadcast-tower fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No live exams at the moment</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Attempts -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-history me-2"></i>Recent Attempts
                    </h5>
                    <a href="{{ route('examination.student.history') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentAttempts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Exam</th>
                                    <th>Status</th>
                                    <th>Started</th>
                                    <th>Duration</th>
                                    <th>Score</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttempts as $attempt)
                                <tr>
                                    <td>
                                        <div>
                                            <span class="fw-semibold">{{ $attempt->exam->name }}</span>
                                            <br>
                                            <small class="text-muted">{{ $attempt->exam->examType->name }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($attempt->status == 'completed') bg-success
                                            @elseif($attempt->status == 'in_progress') bg-warning
                                            @elseif($attempt->status == 'started') bg-info
                                            @else bg-secondary @endif">
                                            {{ strtoupper($attempt->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $attempt->created_at->format('M d, g:i A') }}</td>
                                    <td>
                                        @if($attempt->started_at && $attempt->completed_at)
                                            {{ $attempt->started_at->diffInMinutes($attempt->completed_at) }} min
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($attempt->status == 'completed' && $attempt->score !== null)
                                            <span class="fw-semibold">{{ $attempt->score }}%</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($attempt->status == 'completed')
                                        <a href="{{ route('examination.online.result', $attempt->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Result
                                        </a>
                                        @elseif(in_array($attempt->status, ['started', 'in_progress']))
                                        <a href="{{ route('examination.online.start', $attempt->exam) }}" 
                                           class="btn btn-sm btn-success">
                                            <i class="fas fa-play me-1"></i>Continue
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No exam attempts yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Results -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-trophy me-2"></i>Recent Results
                    </h5>
                    <a href="{{ route('examination.student.results') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recentResults->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentResults as $result)
                        <div class="d-flex align-items-center p-3 border rounded">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold">{{ $result->exam->name }}</h6>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold 
                                        @if($result->score >= 80) text-success
                                        @elseif($result->score >= 60) text-warning
                                        @else text-danger @endif">
                                        {{ $result->score }}%
                                    </span>
                                    <span class="text-muted small">{{ $result->created_at->format('M d') }}</span>
                                </div>
                            </div>
                            <div class="ms-3">
                                <a href="{{ route('examination.online.result', $result->exam_attempt_id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No results available yet</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Active Attempt Alert -->
    @if($activeAttempt)
    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius: 1rem;">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
            <div class="flex-grow-1">
                <h6 class="alert-heading mb-1">You have an active exam!</h6>
                <p class="mb-0">You have an ongoing exam: <strong>{{ $activeAttempt->exam->name }}</strong></p>
            </div>
            <a href="{{ route('examination.online.start', $activeAttempt->exam) }}" class="btn btn-warning">
                <i class="fas fa-play me-2"></i>Continue Exam
            </a>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function viewExam(examId) {
    window.location.href = `/examination/student/exams/${examId}`;
}

function startExam(examId) {
    if (confirm('Start this exam?')) {
        fetch(`/examination/student/exams/${examId}/start`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            alert('Failed to start exam');
        });
    }
}
</script>
@endpush 