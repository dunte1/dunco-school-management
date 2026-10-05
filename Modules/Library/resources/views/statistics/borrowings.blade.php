@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Borrowing Statistics</h2>
        <div>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-primary me-2">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>Export Excel
            </a>
        </div>
    </div>

    <!-- Borrowing Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $totalBorrowings ?? 0 }}</h4>
                    <p class="mb-0">Total Borrowings</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $activeBorrowings ?? 0 }}</h4>
                    <p class="mb-0">Active Borrowings</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $returnedBooks ?? 0 }}</h4>
                    <p class="mb-0">Returned Books</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $overdueBooks ?? 0 }}</h4>
                    <p class="mb-0">Overdue Books</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Borrowings by Month -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Borrowings by Month</h5>
                </div>
                <div class="card-body">
                    @if(isset($borrowingsByMonth) && $borrowingsByMonth->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Borrowings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($borrowingsByMonth as $month)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::createFromFormat('n', $month->month)->format('F Y') }}</td>
                                        <td>{{ $month->count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No borrowing data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Average Borrowing Duration -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Borrowing Duration</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h3 class="text-primary">{{ $averageBorrowingDuration ?? 0 }} days</h3>
                        <p class="text-muted">Average borrowing duration</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
