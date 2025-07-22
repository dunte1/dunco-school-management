@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Examination Dashboard</h1>
    <div class="bg-white rounded shadow p-6 mb-6">
        <p>Welcome to the Examination Dashboard. This is a placeholder page.</p>
    </div>
    <div class="row mb-4">
        @if(auth()->check() && auth()->user()->hasRole('student'))
        <div class="col-md-4 mb-3">
            <a href="{{ route('examination.student.exams') }}" class="card h-100 text-decoration-none text-dark shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-book-open fa-2x text-primary me-3"></i>
                    <div>
                        <h5 class="fw-bold mb-1">Take Exam</h5>
                        <p class="mb-0 text-muted">Browse and start available exams</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('examination.student.history') }}" class="card h-100 text-decoration-none text-dark shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-history fa-2x text-info me-3"></i>
                    <div>
                        <h5 class="fw-bold mb-1">My Attempts</h5>
                        <p class="mb-0 text-muted">View your exam attempts</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('examination.student.results') }}" class="card h-100 text-decoration-none text-dark shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-clipboard-check fa-2x text-success me-3"></i>
                    <div>
                        <h5 class="fw-bold mb-1">My Results</h5>
                        <p class="mb-0 text-muted">See your results and feedback</p>
                    </div>
                </div>
            </a>
        </div>
        @endif
        @if(auth()->check() && auth()->user()->hasRole(['admin','teacher']))
        <div class="col-md-4 mb-3">
            <a href="{{ route('examination.results.index') }}" class="card h-100 text-decoration-none text-dark shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-clipboard-list fa-2x text-warning me-3"></i>
                    <div>
                        <h5 class="fw-bold mb-1">Results Management</h5>
                        <p class="mb-0 text-muted">View and manage all exam results</p>
                    </div>
                </div>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection 