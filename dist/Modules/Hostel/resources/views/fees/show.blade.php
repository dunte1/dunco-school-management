@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Hostel Fee Details</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title">Student: {{ $hostelFee->student->name ?? 'N/A' }}</h4>
            <p><strong>Bed:</strong> {{ $hostelFee->bed->bed_number ?? 'N/A' }}</p>
            <p><strong>Room:</strong> {{ $hostelFee->room->name ?? 'N/A' }}</p>
            <p><strong>Hostel:</strong> {{ $hostelFee->hostel->name ?? 'N/A' }}</p>
            <p><strong>Amount:</strong> {{ $hostelFee->amount }}</p>
            <p><strong>Status:</strong> {{ ucfirst($hostelFee->status) }}</p>
            <p><strong>Due Date:</strong> {{ $hostelFee->due_date }}</p>
            <p><strong>Paid At:</strong> {{ $hostelFee->paid_at }}</p>
            <p><strong>Fine:</strong> {{ $hostelFee->fine }}</p>
            <p><strong>Notes:</strong> {{ $hostelFee->notes }}</p>
        </div>
    </div>
    <a href="{{ route('hostel.fees.edit', $hostelFee) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('hostel.fees.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection 