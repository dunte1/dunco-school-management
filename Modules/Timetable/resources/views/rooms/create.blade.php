@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Add Room</h2>
    <form action="{{ route('rooms.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Room Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">Capacity</label>
            <input type="number" name="capacity" id="capacity" class="form-control" min="1" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control">
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Room Type</label>
            <select name="type" id="type" class="form-select">
                <option value="">Select Type</option>
                <option value="Lecture Room">Lecture Room</option>
                <option value="Lab">Lab</option>
                <option value="Hall">Hall</option>
                <option value="Studio">Studio</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="equipment" class="form-label">Equipment</label>
            <input type="text" name="equipment" id="equipment" class="form-control" placeholder="e.g. Projector, Computers, Whiteboard">
            <small class="text-muted">Comma-separated list (e.g. Projector, Computers, Whiteboard)</small>
        </div>
        <button type="submit" class="btn btn-success">Create Room</button>
        <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 