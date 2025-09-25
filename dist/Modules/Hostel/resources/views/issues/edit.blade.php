@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Hostel Issue</h1>
    <form action="{{ route('hostel.issues.update', $hostelIssue) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="room_id" class="form-label">Room</label>
            <select name="room_id" id="room_id" class="form-control">
                <option value="">Select Room</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ old('room_id', $hostelIssue->room_id) == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                @endforeach
            </select>
            @error('room_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="bed_id" class="form-label">Bed</label>
            <select name="bed_id" id="bed_id" class="form-control">
                <option value="">Select Bed</option>
                @foreach($beds as $bed)
                    <option value="{{ $bed->id }}" {{ old('bed_id', $hostelIssue->bed_id) == $bed->id ? 'selected' : '' }}>{{ $bed->bed_number }}</option>
                @endforeach
            </select>
            @error('bed_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="student_id" class="form-label">Student (if reporting for a student)</label>
            <select name="student_id" id="student_id" class="form-control">
                <option value="">Select Student</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ old('student_id', $hostelIssue->student_id) == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                @endforeach
            </select>
            @error('student_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="reported_by" class="form-label">Reported By</label>
            <input type="number" name="reported_by" id="reported_by" class="form-control" value="{{ old('reported_by', $hostelIssue->reported_by) }}" required>
            @error('reported_by')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="assigned_to" class="form-label">Assigned To (Staff)</label>
            <input type="number" name="assigned_to" id="assigned_to" class="form-control" value="{{ old('assigned_to', $hostelIssue->assigned_to) }}">
            @error('assigned_to')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="issue_type" class="form-label">Issue Type</label>
            <input type="text" name="issue_type" id="issue_type" class="form-control" value="{{ old('issue_type', $hostelIssue->issue_type) }}" required>
            @error('issue_type')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" required>{{ old('description', $hostelIssue->description) }}</textarea>
            @error('description')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="priority" class="form-label">Priority</label>
            <select name="priority" id="priority" class="form-control" required>
                <option value="low" {{ old('priority', $hostelIssue->priority) == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ old('priority', $hostelIssue->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ old('priority', $hostelIssue->priority) == 'high' ? 'selected' : '' }}>High</option>
            </select>
            @error('priority')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="open" {{ old('status', $hostelIssue->status) == 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ old('status', $hostelIssue->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ old('status', $hostelIssue->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ old('status', $hostelIssue->status) == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
            @error('status')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="resolved_at" class="form-label">Resolved At</label>
            <input type="date" name="resolved_at" id="resolved_at" class="form-control" value="{{ old('resolved_at', $hostelIssue->resolved_at) }}">
            @error('resolved_at')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="resolution_notes" class="form-label">Resolution Notes</label>
            <textarea name="resolution_notes" id="resolution_notes" class="form-control">{{ old('resolution_notes', $hostelIssue->resolution_notes) }}</textarea>
            @error('resolution_notes')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">Update Issue</button>
        <a href="{{ route('hostel.issues.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 