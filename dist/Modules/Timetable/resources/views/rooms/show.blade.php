@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Room Details</h2>
    <div class="card mb-3">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $room->id }}</p>
            <p><strong>Name:</strong> {{ $room->name }}</p>
            <p><strong>Capacity:</strong> {{ $room->capacity }}</p>
            <p><strong>Location:</strong> {{ $room->location }}</p>
            <p><strong>Type:</strong> {{ $room->type ?? '-' }}</p>
            <p><strong>Equipment:</strong>
                @if($room->equipment)
                    @foreach(explode(',', $room->equipment) as $item)
                        <span class="badge bg-info text-dark me-1">{{ trim($item) }}</span>
                    @endforeach
                @else
                    <span class="text-muted">—</span>
                @endif
            </p>
        </div>
    </div>
    <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection 