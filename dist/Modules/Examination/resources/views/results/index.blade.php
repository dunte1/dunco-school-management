@extends('layouts.app')

@section('title', 'Exam Results')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0" style="color: #1a237e; font-weight: 600;">
                <i class="fas fa-poll me-2"></i>Exam Results
            </h1>
            <p class="text-muted mb-0">View all exam results and performance analytics</p>
        </div>
        <div>
            <a href="{{ route('examination.dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Total Results</h6>
                            <h3 class="mb-0 fw-bold">{{ $results->total() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-list-ol fa-2x opacity-75"></i>
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
                            <h6 class="mb-1 opacity-75">Published</h6>
                            <h3 class="mb-0 fw-bold">{{ $results->where('is_published', true)->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
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
                            <h6 class="mb-1 opacity-75">Average Score</h6>
                            <h3 class="mb-0 fw-bold">
                                {{ $results->count() > 0 ? round($results->avg('percentage'), 1) : 'N/A' }}%
                            </h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-chart-line fa-2x opacity-75"></i>
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
                            <h6 class="mb-1 opacity-75">Drafts</h6>
                            <h3 class="mb-0 fw-bold">{{ $results->where('is_published', false)->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-file-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
        <div class="card-body">
            @if($results->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Exam</th>
                            <th>Student</th>
                            <th>Score</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $result)
                        <tr>
                            <td>{{ $results->firstItem() + $loop->index }}</td>
                            <td>
                                <span class="fw-semibold">{{ $result->exam->name ?? '-' }}</span>
                                <br>
                                <small class="text-muted">{{ $result->exam->examType->name ?? '' }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $result->student->name ?? '-' }}</span>
                                <br>
                                <small class="text-muted">{{ $result->student->email ?? '' }}</small>
                            </td>
                            <td>
                                <span class="fw-bold {{ $result->percentage >= 60 ? 'text-success' : 'text-danger' }}">
                                    {{ $result->percentage ?? '-' }}%
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $result->is_published ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $result->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>{{ $result->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('examination.results.show', $result->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $results->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-poll fa-4x text-muted mb-3"></i>
                <h4 class="text-muted mb-2">No Results Found</h4>
                <p class="text-muted mb-0">There are no exam results to display at the moment.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 