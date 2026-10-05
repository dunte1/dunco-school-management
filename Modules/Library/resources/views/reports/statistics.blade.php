@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Library Statistics Report</h2>
        <div>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-primary me-2">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </a>
            <a href="{{ route('library.reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Reports
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Key Metrics</h5>
                </div>
                <div class="card-body">
                    @if($statistics->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Metric</th>
                                        <th>Value</th>
                                        <th>Change</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($statistics as $stat)
                                    <tr>
                                        <td>{{ $stat->metric }}</td>
                                        <td>
                                            <strong>{{ number_format($stat->value) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ strpos($stat->change, '+') === 0 ? 'success' : (strpos($stat->change, '-') === 0 ? 'danger' : 'secondary') }}">
                                                {{ $stat->change }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($stat->metric === 'Overdue Books' && $stat->value > 0)
                                                <span class="badge bg-warning">Needs Attention</span>
                                            @elseif($stat->metric === 'Overdue Books' && $stat->value == 0)
                                                <span class="badge bg-success">Good</span>
                                            @else
                                                <span class="badge bg-info">Normal</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No statistics available</h5>
                            <p class="text-muted">There is no data to generate statistics yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Summary</h5>
                </div>
                <div class="card-body">
                    @if($statistics->count() > 0)
                        @php
                            $overdueBooks = $statistics->where('metric', 'Overdue Books')->first()->value ?? 0;
                            $totalBooks = $statistics->where('metric', 'Total Books')->first()->value ?? 0;
                            $activeMembers = $statistics->where('metric', 'Active Members')->first()->value ?? 0;
                        @endphp
                        
                        <div class="text-center mb-3">
                            <h4 class="text-primary">{{ $totalBooks }}</h4>
                            <p class="text-muted mb-0">Total Books</p>
                        </div>
                        
                        <div class="text-center mb-3">
                            <h4 class="text-success">{{ $activeMembers }}</h4>
                            <p class="text-muted mb-0">Active Members</p>
                        </div>
                        
                        <div class="text-center mb-3">
                            <h4 class="text-{{ $overdueBooks > 0 ? 'danger' : 'success' }}">{{ $overdueBooks }}</h4>
                            <p class="text-muted mb-0">Overdue Books</p>
                        </div>
                        
                        <hr>
                        
                        <div class="text-center">
                            @if($overdueBooks == 0)
                                <span class="badge bg-success fs-6">All Good!</span>
                            @else
                                <span class="badge bg-warning fs-6">Action Required</span>
                            @endif
                        </div>
                    @else
                        <p class="text-muted text-center">No data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
