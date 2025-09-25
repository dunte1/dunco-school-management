@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Announcement</h1>
    <form action="{{ route('hostel.announcements.store') }}" method="POST">
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
            <label for="warden_id" class="form-label">Warden</label>
            <select name="warden_id" id="warden_id" class="form-control" required>
                <option value="">Select Warden</option>
                @foreach($wardens as $warden)
                    <option value="{{ $warden->id }}" {{ old('warden_id') == $warden->id ? 'selected' : '' }}>{{ $warden->user->name ?? 'Warden #'.$warden->id }}</option>
                @endforeach
            </select>
            @error('warden_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
            @error('title')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea name="message" id="message" class="form-control" required>{{ old('message') }}</textarea>
            @error('message')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="attachment" class="form-label">Attachment (URL or path)</label>
            <input type="text" name="attachment" id="attachment" class="form-control" value="{{ old('attachment') }}">
            @error('attachment')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="audience" class="form-label">Audience</label>
            <select name="audience" id="audience" class="form-control" required>
                <option value="all" {{ old('audience') == 'all' ? 'selected' : '' }}>All</option>
                <option value="residents" {{ old('audience') == 'residents' ? 'selected' : '' }}>Residents</option>
                <option value="staff" {{ old('audience') == 'staff' ? 'selected' : '' }}>Staff</option>
            </select>
            @error('audience')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="published_at" class="form-label">Published At</label>
            <input type="datetime-local" name="published_at" id="published_at" class="form-control" value="{{ old('published_at') }}">
            @error('published_at')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">Create Announcement</button>
        <a href="{{ route('hostel.announcements.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 