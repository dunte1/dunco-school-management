@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Hostel Issue Details</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title">Issue: {{ $hostelIssue->issue_type }}</h4>
            <p><strong>Room:</strong> {{ $hostelIssue->room->name ?? 'N/A' }}</p>
            <p><strong>Bed:</strong> {{ $hostelIssue->bed->bed_number ?? 'N/A' }}</p>
            <p><strong>Student:</strong> {{ $hostelIssue->student->name ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($hostelIssue->status) }}</p>
            <p><strong>Priority:</strong> {{ ucfirst($hostelIssue->priority) }}</p>
            <p><strong>Description:</strong> {{ $hostelIssue->description }}</p>
            <p><strong>Assigned To:</strong> {{ $hostelIssue->assignedTo->name ?? 'N/A' }}</p>
            <p><strong>Resolved At:</strong> {{ $hostelIssue->resolved_at }}</p>
            <p><strong>Resolution Notes:</strong> {{ $hostelIssue->resolution_notes }}</p>
        </div>
    </div>
    <a href="{{ route('hostel.issues.edit', $hostelIssue) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('hostel.issues.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection


@section('content')
<div class="container">
    <h1>Hostel Issue Details</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title">Issue: {{ $hostelIssue->issue_type }}</h4>
            <p><strong>Room:</strong> {{ $hostelIssue->room->name ?? 'N/A' }}</p>
            <p><strong>Bed:</strong> {{ $hostelIssue->bed->bed_number ?? 'N/A' }}</p>
            <p><strong>Student:</strong> {{ $hostelIssue->student->name ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($hostelIssue->status) }}</p>
            <p><strong>Priority:</strong> {{ ucfirst($hostelIssue->priority) }}</p>
            <p><strong>Description:</strong> {{ $hostelIssue->description }}</p>
            <p><strong>Assigned To:</strong> {{ $hostelIssue->assignedTo->name ?? 'N/A' }}</p>
            <p><strong>Resolved At:</strong> {{ $hostelIssue->resolved_at }}</p>
            <p><strong>Resolution Notes:</strong> {{ $hostelIssue->resolution_notes }}</p>
        </div>
    </div>
    <a href="{{ route('hostel.issues.edit', $hostelIssue) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('hostel.issues.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
