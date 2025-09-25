@extends('layouts.app')

@section('title', 'Transport Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transport Reports</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Monthly Trips</h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($monthlyTrips) && $monthlyTrips->count() > 0)
                                        <canvas id="monthlyTripsChart"></canvas>
                                    @else
                                        <p class="text-muted">No trip data available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Vehicle Utilization</h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($vehicleUtilization) && $vehicleUtilization->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Vehicle</th>
                                                        <th>Trips This Month</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($vehicleUtilization as $vehicle)
                                                    <tr>
                                                        <td>{{ $vehicle->vehicle_number }}</td>
                                                        <td>{{ $vehicle->trips_count }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">No vehicle utilization data available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Driver Performance</h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($driverPerformance) && $driverPerformance->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Driver</th>
                                                        <th>Trips This Month</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($driverPerformance as $driver)
                                                    <tr>
                                                        <td>{{ $driver->name }}</td>
                                                        <td>{{ $driver->trips_count }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">No driver performance data available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($monthlyTrips) && $monthlyTrips->count() > 0)
    const ctx = document.getElementById('monthlyTripsChart').getContext('2d');
    const monthlyTripsData = @json($monthlyTrips);
    
    const labels = monthlyTripsData.map(item => {
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                           'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return monthNames[item.month - 1];
    });
    
    const data = monthlyTripsData.map(item => item.count);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Trips',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    @endif
});
</script>
@endpush
@endsection 