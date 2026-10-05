@extends('layouts.app')
@section('title','New Ticket')
@section('content')
<div class="container-fluid py-3">
  <h4 class="mb-3">Create Ticket</h4>
  <div class="card shadow-sm">
    <div class="card-body">
      <form action="{{ route('admin.helpdesk.tickets.store') }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">School ID</label>
            <input type="number" class="form-control" name="school_id" value="{{ old('school_id') }}">
          </div>
          <div class="col-md-9">
            <label class="form-label">Subject</label>
            <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" required>
          </div>
          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="6">{{ old('description') }}</textarea>
          </div>
          <div class="col-md-3">
            <label class="form-label">Priority</label>
            <select class="form-select" name="priority" required>
              @foreach(['low','medium','high','critical'] as $p)
                <option value="{{ $p }}" @selected(old('priority','medium')===$p)>{{ ucfirst($p) }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="mt-3 d-flex gap-2">
          <button class="btn btn-primary" type="submit">Save</button>
          <a href="{{ route('admin.helpdesk.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
