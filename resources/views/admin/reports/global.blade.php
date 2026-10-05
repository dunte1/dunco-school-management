@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-3">Global Report</h1>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="small text-muted">Schools</div>
                <div class="fs-3 fw-bold">{{ number_format($summary['schools']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="small text-muted">Users</div>
                <div class="fs-3 fw-bold">{{ number_format($summary['users']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="small text-muted">Students</div>
                <div class="fs-3 fw-bold">{{ number_format($summary['students']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="small text-muted">Teachers</div>
                <div class="fs-3 fw-bold">{{ number_format($summary['teachers']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="small text-muted">Payments (Total)</div>
                <div class="fs-3 fw-bold">{{ number_format($summary['payments'], 2) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100"><div class="card-body">
                <div class="small text-muted">Overdue Invoices</div>
                <div class="fs-3 fw-bold">{{ number_format($summary['invoices_overdue']) }}</div>
            </div></div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">Recent Activities</div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @forelse($summary['recent_logs'] as $log)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ $log->action ?? 'Activity' }}</span>
                        <span class="text-muted small">{{ $log->created_at?->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="list-group-item">No recent activity.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection


