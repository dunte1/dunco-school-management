@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Library Dashboard</h2>
        <div>
            <a href="{{ route('library.books.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus me-2"></i>Add Book
            </a>
            <a href="{{ route('library.books.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-book me-2"></i>View All Books
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $totalBooks }}</h4>
                            <p class="mb-0">Total Books</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $activeMembers }}</h4>
                            <p class="mb-0">Active Members</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $booksBorrowed }}</h4>
                            <p class="mb-0">Books Borrowed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exchange-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $overdueBooks }}</h4>
                            <p class="mb-0">Overdue Books</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Books -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Books</h5>
                </div>
                <div class="card-body">
                    @if($recentBooks->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentBooks as $book)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $book->title }}</h6>
                                    <small class="text-muted">
                                        {{ $book->author->name ?? 'Unknown Author' }} • 
                                        {{ $book->category->name ?? 'Uncategorized' }}
                                    </small>
                                </div>
                                <span class="badge bg-{{ $book->status == 'available' ? 'success' : 'warning' }}">
                                    {{ ucfirst($book->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No books added yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Books by Status -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Books by Status</h5>
                </div>
                <div class="card-body">
                    @if($booksByStatus->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($booksByStatus as $status)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-capitalize">{{ $status->status }}</span>
                                <span class="badge bg-primary">{{ $status->count }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No books found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('library.books.create') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-plus me-2"></i>Add Book
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('library.authors.index') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-user-edit me-2"></i>Manage Authors
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('library.categories.index') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-tags me-2"></i>Manage Categories
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('library.reports.borrowed') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-chart-bar me-2"></i>View Reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 