@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Hostel Statistics</h2>
    </div>

    <!-- Overview Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $totalStudents ?? 0 }}</h4>
                    <p class="mb-0">Total Students</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $activeStudents ?? 0 }}</h4>
                    <p class="mb-0">Active Students</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $totalRooms ?? 0 }}</h4>
                    <p class="mb-0">Total Rooms</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $occupancyRate ?? 0 }}%</h4>
                    <p class="mb-0">Occupancy Rate</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Students by Status -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Students by Status</h5>
                </div>
                <div class="card-body">
                    @if(isset($studentsByStatus) && count($studentsByStatus) > 0)
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
                                    @foreach($studentsByStatus as $status => $data)
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
                        <p class="text-muted text-center py-3">No student data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Room Occupancy -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Room Occupancy</h5>
                </div>
                <div class="card-body">
                    @if(isset($roomOccupancy) && count($roomOccupancy) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Hostel</th>
                                        <th>Occupied</th>
                                        <th>Available</th>
                                        <th>Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roomOccupancy as $hostel)
                                    <tr>
                                        <td>{{ $hostel->name }}</td>
                                        <td>{{ $hostel->occupied_rooms }}</td>
                                        <td>{{ $hostel->available_rooms }}</td>
                                        <td>{{ $hostel->occupancy_rate }}%</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No occupancy data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
