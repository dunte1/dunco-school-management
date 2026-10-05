@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Transport Statistics</h2>
    </div>

    <!-- Overview Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $totalVehicles ?? 0 }}</h4>
                    <p class="mb-0">Total Vehicles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $activeDrivers ?? 0 }}</h4>
                    <p class="mb-0">Active Drivers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $totalTrips ?? 0 }}</h4>
                    <p class="mb-0">Total Trips</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $totalRoutes ?? 0 }}</h4>
                    <p class="mb-0">Active Routes</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Vehicle Statistics -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Vehicle Status</h5>
                </div>
                <div class="card-body">
                    @if(isset($vehicleStatus) && count($vehicleStatus) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Count</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vehicleStatus as $status => $data)
                                    <tr>
                                        <td>{{ ucfirst($status) }}</td>
                                        <td>{{ $data['count'] }}</td>
                                        <td>{{ $data['percentage'] }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No vehicle data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Trip Statistics -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Trip Status</h5>
                </div>
                <div class="card-body">
                    @if(isset($tripStatus) && count($tripStatus) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Count</th>
                                        <th>Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tripStatus as $status => $data)
                                    <tr>
                                        <td>{{ ucfirst($status) }}</td>
                                        <td>{{ $data['count'] }}</td>
                                        <td>{{ $data['percentage'] }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No trip data available</p>
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
                            <a href="{{ route('transport.statistics.trips') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-route me-2"></i>Trip Statistics
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('transport.statistics.vehicles') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-bus me-2"></i>Vehicle Statistics
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('transport.statistics.revenue') }}" class="btn btn-outline-warning w-100">
                                <i class="fas fa-dollar-sign me-2"></i>Revenue Statistics
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('transport.statistics.maintenance') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-wrench me-2"></i>Maintenance Statistics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
