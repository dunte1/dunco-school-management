@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Bed Details</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title">Bed {{ $bed->bed_number }}</h4>
            <p><strong>Room:</strong> {{ $bed->room->name ?? 'N/A' }}</p>
            <p><strong>Hostel:</strong> {{ $bed->room->hostel->name ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($bed->status) }}</p>
            <p><strong>Price:</strong> {{ $bed->price }}</p>
            <p><strong>Description:</strong> {{ $bed->description }}</p>
        </div>
    </div>
    <a href="{{ route('hostel.beds.edit', $bed) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('hostel.beds.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection 