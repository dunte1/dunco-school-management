@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Room</h1>
    <form action="{{ route('hostel.rooms.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="hostel_id" class="form-label">Hostel</label>
            <select name="hostel_id" id="hostel_id" class="form-control" required>
                <option value="">Select Hostel</option>
                @foreach($hostels as $hostel)
                    <option value="{{ $hostel->id }}" {{ old('hostel_id') == $hostel->id ? 'selected' : '' }}>{{ $hostel->name }}</option>
                @endforeach
            </select>
            @error('hostel_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="floor_id" class="form-label">Floor</label>
            <select name="floor_id" id="floor_id" class="form-control" required>
                <option value="">Select Floor</option>
                @foreach($floors as $floor)
                    <option value="{{ $floor->id }}" {{ old('floor_id') == $floor->id ? 'selected' : '' }}>{{ $floor->name }}</option>
                @endforeach
            </select>
            @error('floor_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="single" {{ old('type') == 'single' ? 'selected' : '' }}>Single</option>
                <option value="double" {{ old('type') == 'double' ? 'selected' : '' }}>Double</option>
                <option value="triple" {{ old('type') == 'triple' ? 'selected' : '' }}>Triple</option>
            </select>
            @error('type')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="ac" class="form-label">AC</label>
            <input type="checkbox" name="ac" id="ac" value="1" {{ old('ac') ? 'checked' : '' }}>
            @error('ac')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="ensuite" class="form-label">Ensuite</label>
            <input type="checkbox" name="ensuite" id="ensuite" value="1" {{ old('ensuite') ? 'checked' : '' }}>
            @error('ensuite')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">Capacity</label>
            <input type="number" name="capacity" id="capacity" class="form-control" value="{{ old('capacity') }}" required min="1">
            @error('capacity')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="amenities" class="form-label">Amenities (comma separated)</label>
            <input type="text" name="amenities[]" id="amenities" class="form-control" value="{{ old('amenities') ? implode(',', old('amenities')) : '' }}">
            @error('amenities')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="layout_image" class="form-label">Layout Image (URL or path)</label>
            <input type="text" name="layout_image" id="layout_image" class="form-control" value="{{ old('layout_image') }}">
            @error('layout_image')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="price_per_bed" class="form-label">Price per Bed</label>
            <input type="number" step="0.01" name="price_per_bed" id="price_per_bed" class="form-control" value="{{ old('price_per_bed') }}">
            @error('price_per_bed')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                <option value="reserved" {{ old('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
            </select>
            @error('status')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            @error('description')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">Create Room</button>
        <a href="{{ route('hostel.rooms.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 