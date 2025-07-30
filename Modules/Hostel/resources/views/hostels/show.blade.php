@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Hostel Details</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title">{{ $hostel->name }}</h4>
            <p><strong>Location:</strong> {{ $hostel->location }}</p>
            <p><strong>Gender Restriction:</strong> {{ ucfirst($hostel->gender_restriction) }}</p>
            <p><strong>School/Campus:</strong> {{ $hostel->school_id ?? 'N/A' }}</p>
            <p><strong>Description:</strong> {{ $hostel->description }}</p>
        </div>
    </div>
    <a href="{{ route('hostel.edit', $hostel) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('hostel.hostels.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection 