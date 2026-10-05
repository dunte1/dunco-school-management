@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Library Statistics</h2>
        <div>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-primary me-2">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>Export Excel
            </a>
        </div>
    </div>

    <!-- Overview Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $totalBooks ?? 0 }}</h4>
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
                            <h4 class="mb-0">{{ $activeMembers ?? 0 }}</h4>
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
                            <h4 class="mb-0">{{ $totalBorrowings ?? 0 }}</h4>
                            <p class="mb-0">Total Borrowings</p>
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
                            <h4 class="mb-0">{{ $overdueBooks ?? 0 }}</h4>
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
        <!-- Borrowing Trends -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Borrowing Trends</h5>
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
                                        <td>{{ \Carbon\Carbon::createFromFormat('n', $month->month)->format('F') }}</td>
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

        <!-- Overdue Analysis -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Overdue Analysis</h5>
                </div>
                <div class="card-body">
                    @if(isset($overdueAnalysis) && count($overdueAnalysis) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Period</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($overdueAnalysis as $period => $count)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', $period)) }}</td>
                                        <td>{{ $count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No overdue data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Average Borrowing Duration -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Average Borrowing Duration</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h3 class="text-primary">{{ $averageBorrowingDuration ?? 0 }} days</h3>
                        <p class="text-muted">Average time books are borrowed</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Fines -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Total Fines</h5>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h3 class="text-danger">KSh {{ number_format($totalFines ?? 0, 2) }}</h3>
                        <p class="text-muted">Total fines collected</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
