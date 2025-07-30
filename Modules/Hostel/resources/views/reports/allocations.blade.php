@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Room Allocation Report</h1>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Student</th>
                <th>Bed</th>
                <th>Room</th>
                <th>Hostel</th>
                <th>Status</th>
                <th>Check In</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allocations as $allocation)
                <tr>
                    <td>{{ $allocation->student->name ?? 'N/A' }}</td>
                    <td>{{ $allocation->bed->bed_number ?? 'N/A' }}</td>
                    <td>{{ $allocation->bed->room->name ?? 'N/A' }}</td>
                    <td>{{ $allocation->bed->room->hostel->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($allocation->status) }}</td>
                    <td>{{ $allocation->check_in }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $allocations->links() }}
</div>
@endsection 

@section('content')
<div class="container">
    <h1>Room Allocation Report</h1>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Student</th>
                <th>Bed</th>
                <th>Room</th>
                <th>Hostel</th>
                <th>Status</th>
                <th>Check In</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allocations as $allocation)
                <tr>
                    <td>{{ $allocation->student->name ?? 'N/A' }}</td>
                    <td>{{ $allocation->bed->bed_number ?? 'N/A' }}</td>
                    <td>{{ $allocation->bed->room->name ?? 'N/A' }}</td>
                    <td>{{ $allocation->bed->room->hostel->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($allocation->status) }}</td>
                    <td>{{ $allocation->check_in }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $allocations->links() }}
</div>
@endsection
