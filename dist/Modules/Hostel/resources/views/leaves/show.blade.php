@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Leave Request Details</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title">Student: {{ $leaveRequest->student->name ?? 'N/A' }}</h4>
            <p><strong>Warden:</strong> {{ $leaveRequest->warden->name ?? 'N/A' }}</p>
            <p><strong>Reason:</strong> {{ $leaveRequest->reason }}</p>
            <p><strong>From:</strong> {{ $leaveRequest->from_date }}</p>
            <p><strong>To:</strong> {{ $leaveRequest->to_date }}</p>
            <p><strong>Status:</strong> {{ ucfirst($leaveRequest->status) }}</p>
            <p><strong>Emergency Contact:</strong> {{ $leaveRequest->emergency_contact }}</p>
            <p><strong>Guardian Notified:</strong> {{ $leaveRequest->guardian_notified ? 'Yes' : 'No' }}</p>
            <p><strong>Notes:</strong> {{ $leaveRequest->notes }}</p>
        </div>
    </div>
    <a href="{{ route('hostel.leave_requests.edit', $leaveRequest) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('hostel.leave_requests.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
