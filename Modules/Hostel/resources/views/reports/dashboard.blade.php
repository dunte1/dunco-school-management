@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Hostel Reports & Analytics</h1>

    @if(isset($stats))
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Hostels</h5>
                    <h2>{{ $stats['total_hostels'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Occupancy Rate</h5>
                    <h2>{{ $stats['occupancy_rate'] ?? 0 }}%</h2>
                    <small>{{ $stats['occupied_beds'] ?? 0 }} / {{ $stats['total_beds'] ?? 0 }} beds</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Open Issues</h5>
                    <h2>{{ $stats['open_issues'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Fee Pending</h5>
                    <h2>{{ $stats['fee_pending'] ?? 0 }}</h2>
                    <small>&#8358;{{ number_format($stats['fee_pending_amount'] ?? 0, 2) }}</small>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="list-group mt-4">
        <a href="{{ route('hostel.reports.occupancy') }}" class="list-group-item list-group-item-action">Occupancy Report</a>
        <a href="{{ route('hostel.reports.allocation') }}" class="list-group-item list-group-item-action">Room Allocation Report</a>
        <a href="{{ route('hostel.reports.maintenance') }}" class="list-group-item list-group-item-action">Maintenance Report</a>
        <a href="{{ route('hostel.reports.movement') }}" class="list-group-item list-group-item-action">Student Movement Logs</a>
        <a href="{{ route('hostel.reports.defaulters') }}" class="list-group-item list-group-item-action">Hostel Fee Defaulter List</a>
        <a href="{{ route('hostel.reports.damage') }}" class="list-group-item list-group-item-action">Damage/Fine Reports</a>
    </div>
</div>
@endsection
