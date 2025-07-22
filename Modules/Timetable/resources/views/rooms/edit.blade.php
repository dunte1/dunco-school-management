@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Edit Room</h2>
    <form action="{{ route('rooms.update', $room->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Room Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $room->name }}" required>
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">Capacity</label>
            <input type="number" name="capacity" id="capacity" class="form-control" value="{{ $room->capacity }}" min="1" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control" value="{{ $room->location }}">
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Room Type</label>
            <select name="type" id="type" class="form-select">
                <option value="">Select Type</option>
                <option value="Lecture Room" @if($room->type == 'Lecture Room') selected @endif>Lecture Room</option>
                <option value="Lab" @if($room->type == 'Lab') selected @endif>Lab</option>
                <option value="Hall" @if($room->type == 'Hall') selected @endif>Hall</option>
                <option value="Studio" @if($room->type == 'Studio') selected @endif>Studio</option>
                <option value="Other" @if($room->type == 'Other') selected @endif>Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="equipment" class="form-label">Equipment</label>
            <input type="text" name="equipment" id="equipment" class="form-control" value="{{ $room->equipment }}" placeholder="e.g. Projector, Computers, Whiteboard">
            <small class="text-muted">Comma-separated list (e.g. Projector, Computers, Whiteboard)</small>
        </div>
        <button type="submit" class="btn btn-success">Update Room</button>
        <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 