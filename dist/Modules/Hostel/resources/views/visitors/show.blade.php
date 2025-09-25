@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Visitor Log Details</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title">Visitor: {{ $hostelVisitor->visitor_name }}</h4>
            <p><strong>Student Visited:</strong> {{ $hostelVisitor->student->name ?? 'N/A' }}</p>
            <p><strong>Hostel:</strong> {{ $hostelVisitor->hostel->name ?? 'N/A' }}</p>
            <p><strong>Time In:</strong> {{ $hostelVisitor->time_in }}</p>
            <p><strong>Time Out:</strong> {{ $hostelVisitor->time_out ?? '-' }}</p>
            <p><strong>Pass Number:</strong> {{ $hostelVisitor->pass_number }}</p>
            <p><strong>Purpose:</strong> {{ $hostelVisitor->purpose }}</p>
            <p><strong>Notes:</strong> {{ $hostelVisitor->notes }}</p>
        </div>
    </div>
    <a href="{{ route('hostel.visitors.edit', $hostelVisitor) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('hostel.visitors.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection 