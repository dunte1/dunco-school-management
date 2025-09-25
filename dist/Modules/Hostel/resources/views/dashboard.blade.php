@extends('layouts.app')

@section('title', 'Hostel Dashboard')

@section('content')
<div class="container py-4">
    <h1 class="mb-4"><i class="fas fa-bed"></i> Hostel Dashboard</h1>
    <div class="alert alert-info">Welcome to the Hostel Management Dashboard.</div>

    <!-- Quick Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Hostels</h6>
                    <h3 class="card-text">{{ $stats['hostels'] ?? '0' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Available Rooms</h6>
                    <h3 class="card-text">{{ $stats['available_rooms'] ?? '0' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Current Allocations</h6>
                    <h3 class="card-text">{{ $stats['allocations'] ?? '0' }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title text-muted">Pending Issues</h6>
                    <h3 class="card-text">{{ $stats['issues'] ?? '0' }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Hostels</h5>
                    <p class="card-text">Manage hostel facilities</p>
                    <a href="{{ route('hostel.hostels.index') }}" class="btn btn-primary">View Hostels</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Rooms</h5>
                    <p class="card-text">Manage room allocations</p>
                    <a href="{{ route('hostel.rooms.index') }}" class="btn btn-primary">View Rooms</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Students</h5>
                    <p class="card-text">Manage student allocations</p>
                    <a href="{{ route('hostel.room_allocations.index') }}" class="btn btn-primary">View Allocations</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Reports</h5>
                    <p class="card-text">View hostel reports</p>
                    <a href="{{ route('hostel.reports.dashboard') }}" class="btn btn-primary">View Reports</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity / Alerts -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-bell"></i> Recent Activity & Alerts
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        @forelse($recentActivity ?? [] as $activity)
                            <li>{{ $activity }}</li>
                        @empty
                            <li>No recent activity or alerts.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
