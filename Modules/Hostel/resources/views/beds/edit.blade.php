@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Bed</h1>
    <form action="{{ route('hostel.beds.update', $bed) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="room_id" class="form-label">Room</label>
            <select name="room_id" id="room_id" class="form-control" required>
                <option value="">Select Room</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ old('room_id', $bed->room_id) == $room->id ? 'selected' : '' }}>{{ $room->name }} ({{ $room->hostel->name ?? 'N/A' }})</option>
                @endforeach
            </select>
            @error('room_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="bed_number" class="form-label">Bed Number</label>
            <input type="text" name="bed_number" id="bed_number" class="form-control" value="{{ old('bed_number', $bed->bed_number) }}" required>
            @error('bed_number')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="available" {{ old('status', $bed->status) == 'available' ? 'selected' : '' }}>Available</option>
                <option value="occupied" {{ old('status', $bed->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                <option value="maintenance" {{ old('status', $bed->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                <option value="reserved" {{ old('status', $bed->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
            </select>
            @error('status')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price', $bed->price) }}">
            @error('price')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $bed->description) }}</textarea>
            @error('description')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">Update Bed</button>
        <a href="{{ route('hostel.beds.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 