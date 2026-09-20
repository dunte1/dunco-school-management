@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Occupancy Report</h1>

    @if(isset($summary))
    <div class="row mt-3 mb-4">
        <div class="col-md-2">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title">Total Beds</h6>
                    <h3>{{ $summary['total_beds'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h6 class="card-title">Available</h6>
                    <h3 class="text-success">{{ $summary['available'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h6 class="card-title">Occupied</h6>
                    <h3 class="text-primary">{{ $summary['occupied'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h6 class="card-title">Maintenance</h6>
                    <h3 class="text-warning">{{ $summary['maintenance'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center border-info">
                <div class="card-body">
                    <h6 class="card-title">Reserved</h6>
                    <h3 class="text-info">{{ $summary['reserved'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card text-center border-dark">
                <div class="card-body">
                    <h6 class="card-title">Occupancy</h6>
                    <h3>{{ $summary['occupancy_rate'] }}%</h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    <form method="GET" class="row mb-3">
        <div class="col-md-4">
            <select name="hostel_id" class="form-control">
                <option value="">All Hostels</option>
                @foreach($allHostels as $hostel)
                    <option value="{{ $hostel->id }}" {{ request('hostel_id') == $hostel->id ? 'selected' : '' }}>
                        {{ $hostel->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('hostel.reports.occupancy') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <table class="table table-bordered table-striped mt-4">
        <thead class="thead-dark">
            <tr>
                <th>Hostel</th>
                <th>Room</th>
                <th>Type</th>
                <th>Total Beds</th>
                <th>Available</th>
                <th>Occupied</th>
                <th>Maintenance</th>
                <th>Reserved</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hostels as $hostel)
                @forelse($hostel->rooms as $room)
                    <tr>
                        <td>{{ $hostel->name }}</td>
                        <td>{{ $room->name }}</td>
                        <td>{{ ucfirst($room->type ?? 'N/A') }}</td>
                        <td>{{ $room->beds->count() }}</td>
                        <td><span class="badge badge-success">{{ $room->beds->where('status', 'available')->count() }}</span></td>
                        <td><span class="badge badge-primary">{{ $room->beds->where('status', 'occupied')->count() }}</span></td>
                        <td><span class="badge badge-warning">{{ $room->beds->where('status', 'maintenance')->count() }}</span></td>
                        <td><span class="badge badge-info">{{ $room->beds->where('status', 'reserved')->count() }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td>{{ $hostel->name }}</td>
                        <td colspan="7" class="text-muted">No rooms configured</td>
                    </tr>
                @endforelse
            @empty
                <tr>
                    <td colspan="8" class="text-center">No hostels found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
