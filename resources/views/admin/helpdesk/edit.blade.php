@extends('layouts.app')
@section('title','Edit Ticket')
@section('content')
<div class="container-fluid py-3">
  <h4 class="mb-3">Edit Ticket #{{ $ticket->id }}</h4>
  <div class="card shadow-sm">
    <div class="card-body">
      <form action="{{ route('admin.helpdesk.tickets.update', $ticket) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label">School ID</label>
            <input type="number" class="form-control" value="{{ $ticket->school_id }}" disabled>
          </div>
          <div class="col-md-9">
            <label class="form-label">Subject</label>
            <input type="text" class="form-control" name="subject" value="{{ old('subject', $ticket->subject) }}" required>
          </div>
          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="6">{{ old('description', $ticket->description) }}</textarea>
          </div>
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" required>
              @foreach(['open','pending','in_progress','resolved','closed'] as $s)
                <option value="{{ $s }}" @selected(old('status', $ticket->status)===$s)>{{ ucfirst($s) }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Priority</label>
            <select class="form-select" name="priority" required>
              @foreach(['low','medium','high','critical'] as $p)
                <option value="{{ $p }}" @selected(old('priority', $ticket->priority)===$p)>{{ ucfirst($p) }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Assigned To (User ID)</label>
            <input type="number" class="form-control" name="assigned_to" value="{{ old('assigned_to', $ticket->assigned_to) }}">
          </div>
        </div>
        <div class="mt-3 d-flex gap-2">
          <button class="btn btn-primary" type="submit">Update</button>
          <a href="{{ route('admin.helpdesk.index') }}" class="btn btn-outline-secondary">Back</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
