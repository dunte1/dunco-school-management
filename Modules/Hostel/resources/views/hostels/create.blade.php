@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Hostel</h1>
    <form action="{{ route('hostel.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}">
            @error('location')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="gender_restriction" class="form-label">Gender Restriction</label>
            <select name="gender_restriction" id="gender_restriction" class="form-control" required>
                <option value="male" {{ old('gender_restriction') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender_restriction') == 'female' ? 'selected' : '' }}>Female</option>
                <option value="mixed" {{ old('gender_restriction') == 'mixed' ? 'selected' : '' }}>Mixed</option>
            </select>
            @error('gender_restriction')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="school_id" class="form-label">School/Campus (optional)</label>
            <input type="number" name="school_id" id="school_id" class="form-control" value="{{ old('school_id') }}">
            @error('school_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
            @error('description')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">Create Hostel</button>
        <a href="{{ route('hostel.hostels.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 