@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Memberships Report</h2>
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
                    <h5 class="mb-0">Membership Statistics</h5>
                </div>
                <div class="card-body">
                    @if($membershipStats->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Membership Type</th>
                                        <th>Count</th>
                                        <th>Percentage</th>
                                        <th>Visual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($membershipStats as $stat)
                                    <tr>
                                        <td>{{ $stat->type }}</td>
                                        <td>{{ $stat->count }}</td>
                                        <td>{{ $stat->percentage }}%</td>
                                        <td>
                                            <div class="progress" style="width: 200px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $stat->percentage }}%">
                                                    {{ $stat->percentage }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No membership data available</h5>
                            <p class="text-muted">There are no memberships recorded yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Summary</h5>
                </div>
                <div class="card-body">
                    @if($membershipStats->count() > 0)
                        @php
                            $totalMemberships = $membershipStats->sum('count');
                        @endphp
                        <div class="text-center">
                            <h3 class="text-primary">{{ $totalMemberships }}</h3>
                            <p class="text-muted">Total Memberships</p>
                        </div>
                        
                        <hr>
                        
                        <h6>Top Membership Type:</h6>
                        @php
                            $topType = $membershipStats->sortByDesc('count')->first();
                        @endphp
                        <p class="mb-1"><strong>{{ $topType->type }}</strong></p>
                        <p class="text-muted">{{ $topType->count }} members ({{ $topType->percentage }}%)</p>
                    @else
                        <p class="text-muted text-center">No data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
