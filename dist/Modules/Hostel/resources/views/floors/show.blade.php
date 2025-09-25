@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Floor Details</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title">{{ $floor->name }}</h4>
            <p><strong>Block:</strong> {{ $floor->block }}</p>
            <p><strong>Hostel:</strong> {{ $floor->hostel->name ?? 'N/A' }}</p>
            <p><strong>Description:</strong> {{ $floor->description }}</p>
        </div>
    </div>
    <a href="{{ route('hostel.floors.edit', $floor) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('hostel.floors.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
