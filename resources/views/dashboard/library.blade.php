@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Library Dashboard</h1>
        <div class="text-muted">Welcome back, {{ Auth::user()->name }}!</div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_books'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Members</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_members'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Active Borrowings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_borrowings'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-reader fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Overdue Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['overdue_books'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('library.dashboard') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-tachometer-alt me-2"></i>Library Dashboard
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('library.books.search') }}" class="btn btn-success btn-block">
                                <i class="fas fa-search me-2"></i>Search Books
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('library.reports.borrowed') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-book-reader me-2"></i>Borrowed Books
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('library.reports.overdue') }}" class="btn btn-danger btn-block">
                                <i class="fas fa-exclamation-circle me-2"></i>Overdue Books
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Library Overview</h6>
                </div>
                <div class="card-body">
                    @php
                        $totalBooks = $stats['total_books'] ?? 1;
                        $activeBorrowings = $stats['active_borrowings'] ?? 0;
                        $borrowingRate = $totalBooks > 0 ? round(($activeBorrowings / $totalBooks) * 100, 1) : 0;
                    @endphp
                    <div class="progress mb-3" style="height: 24px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min($borrowingRate, 100) }}%;" aria-valuenow="{{ $borrowingRate }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $borrowingRate }}% Borrowed
                        </div>
                    </div>
                    <p class="mb-1"><strong>Total Books:</strong> {{ $stats['total_books'] ?? 0 }}</p>
                    <p class="mb-1"><strong>Total Members:</strong> {{ $stats['total_members'] ?? 0 }}</p>
                    <p class="mb-1"><strong>Active Borrowings:</strong> {{ $stats['active_borrowings'] ?? 0 }}</p>
                    <p class="mb-0"><strong>Overdue Books:</strong> {{ $stats['overdue_books'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Reports</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('library.reports.index') }}" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-chart-bar me-2"></i>All Reports
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('library.reports.most_borrowed') }}" class="btn btn-outline-success btn-block">
                                <i class="fas fa-chart-line me-2"></i>Most Borrowed
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('library.reports.overdue') }}" class="btn btn-outline-danger btn-block">
                                <i class="fas fa-exclamation-triangle me-2"></i>Overdue Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
