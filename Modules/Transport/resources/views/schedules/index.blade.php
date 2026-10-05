@extends('layouts.app')

@section('title', 'Transport Schedules')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Transport Schedules</h4>
                    <a href="{{ route('transport.schedules.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Schedule
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Schedule #</th>
                                    <th>Route</th>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Departure</th>
                                    <th>Arrival</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules ?? [] as $schedule)
                                <tr>
                                    <td>{{ $schedule->schedule_number ?? 'N/A' }}</td>
                                    <td>{{ $schedule->route->name ?? 'N/A' }}</td>
                                    <td>{{ $schedule->vehicle->vehicle_number ?? 'N/A' }}</td>
                                    <td>{{ $schedule->driver->name ?? 'N/A' }}</td>
                                    <td>{{ $schedule->departure_time ?? 'N/A' }}</td>
                                    <td>{{ $schedule->arrival_time ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $schedule->status === 'scheduled' ? 'primary' : ($schedule->status === 'active' ? 'success' : ($schedule->status === 'completed' ? 'info' : 'warning')) }}">
                                            {{ ucfirst($schedule->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('transport.schedules.show', $schedule->id ?? 1) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('transport.schedules.edit', $schedule->id ?? 1) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No schedules found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection