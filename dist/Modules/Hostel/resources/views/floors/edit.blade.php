@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Floor</h1>
    <form action="{{ route('hostel.floors.update', $floor) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="hostel_id" class="form-label">Hostel</label>
            <select name="hostel_id" id="hostel_id" class="form-control" required>
                <option value="">Select Hostel</option>
                @foreach($hostels as $hostel)
                    <option value="{{ $hostel->id }}" {{ old('hostel_id', $floor->hostel_id) == $hostel->id ? 'selected' : '' }}>{{ $hostel->name }}</option>
                @endforeach
            </select>
            @error('hostel_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $floor->name) }}" required>
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="block" class="form-label">Block</label>
            <input type="text" name="block" id="block" class="form-control" value="{{ old('block', $floor->block) }}">
            @error('block')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $floor->description) }}</textarea>
            @error('description')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">Update Floor</button>
        <a href="{{ route('hostel.floors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
