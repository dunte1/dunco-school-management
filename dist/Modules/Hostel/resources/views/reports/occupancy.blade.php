@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Occupancy Report</h1>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Hostel</th>
                <th>Room</th>
                <th>Total Beds</th>
                <th>Available</th>
                <th>Occupied</th>
                <th>Maintenance</th>
                <th>Reserved</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hostels as $hostel)
                @foreach($hostel->rooms as $room)
                    <tr>
                        <td>{{ $hostel->name }}</td>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->beds->count() }}</td>
                        <td>{{ $room->beds->where('status', 'available')->count() }}</td>
                        <td>{{ $room->beds->where('status', 'occupied')->count() }}</td>
                        <td>{{ $room->beds->where('status', 'maintenance')->count() }}</td>
                        <td>{{ $room->beds->where('status', 'reserved')->count() }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection
