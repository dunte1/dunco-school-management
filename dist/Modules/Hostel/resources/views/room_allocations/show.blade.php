@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Room Allocation Details</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title">Student: {{ $roomAllocation->student->name ?? 'N/A' }}</h4>
            <p><strong>Bed:</strong> {{ $roomAllocation->bed->bed_number ?? 'N/A' }}</p>
            <p><strong>Room:</strong> {{ $roomAllocation->bed->room->name ?? 'N/A' }}</p>
            <p><strong>Hostel:</strong> {{ $roomAllocation->bed->room->hostel->name ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($roomAllocation->status) }}</p>
            <p><strong>Check In:</strong> {{ $roomAllocation->check_in }}</p>
            <p><strong>Notes:</strong> {{ $roomAllocation->notes }}</p>
        </div>
    </div>
    <a href="{{ route('hostel.room_allocations.edit', $roomAllocation) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('hostel.room_allocations.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection 