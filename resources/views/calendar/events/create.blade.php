@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Create Calendar Event</h5>
          <a href="{{ url('/dashboard') }}" class="btn btn-sm btn-outline-secondary">Back</a>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('calendar.events.store') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Title</label>
              <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Starts At</label>
                <input type="datetime-local" name="starts_at" class="form-control" required value="{{ old('starts_at', isset($date) ? $date.'T09:00' : '') }}">
              </div>
              <div class="col-md-6">
                <label class="form-label">Ends At</label>
                <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at', isset($date) ? $date.'T10:00' : '') }}">
              </div>
            </div>
            <div class="mt-3">
              <label class="form-label">Location</label>
              <input type="text" name="location" class="form-control" value="{{ old('location') }}">
            </div>
            <div class="mt-3">
              <label class="form-label">Visibility</label>
              <select name="visibility" class="form-select">
                <option value="all" {{ old('visibility')==='all' ? 'selected' : '' }}>All users</option>
                <option value="staff" {{ old('visibility')==='staff' ? 'selected' : '' }}>Staff only</option>
                <option value="students" {{ old('visibility')==='students' ? 'selected' : '' }}>Students only</option>
              </select>
            </div>
            <div class="mt-3">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            </div>
            <div class="mt-4 d-flex gap-2">
              <button type="submit" class="btn btn-primary">Create Event</button>
              <a href="{{ url('/dashboard') }}" class="btn btn-light">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
