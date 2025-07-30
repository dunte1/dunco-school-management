@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Leave Request</h1>
    <form action="{{ route('hostel.leave_requests.update', $leaveRequest) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="student_id" class="form-label">Student</label>
            <select name="student_id" id="student_id" class="form-control" required>
                <option value="">Select Student</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ old('student_id', $leaveRequest->student_id) == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                @endforeach
            </select>
            @error('student_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="warden_id" class="form-label">Warden (optional)</label>
            <select name="warden_id" id="warden_id" class="form-control">
                <option value="">Select Warden</option>
                @foreach($wardens as $warden)
                    <option value="{{ $warden->id }}" {{ old('warden_id', $leaveRequest->warden_id) == $warden->id ? 'selected' : '' }}>{{ $warden->name }}</option>
                @endforeach
            </select>
            @error('warden_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="reason" class="form-label">Reason</label>
            <input type="text" name="reason" id="reason" class="form-control" value="{{ old('reason', $leaveRequest->reason) }}" required>
            @error('reason')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="from_date" class="form-label">From Date</label>
            <input type="date" name="from_date" id="from_date" class="form-control" value="{{ old('from_date', $leaveRequest->from_date) }}" required>
            @error('from_date')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="to_date" class="form-label">To Date</label>
            <input type="date" name="to_date" id="to_date" class="form-control" value="{{ old('to_date', $leaveRequest->to_date) }}" required>
            @error('to_date')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="emergency_contact" class="form-label">Emergency Contact</label>
            <input type="text" name="emergency_contact" id="emergency_contact" class="form-control" value="{{ old('emergency_contact', $leaveRequest->emergency_contact) }}">
            @error('emergency_contact')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="pending" {{ old('status', $leaveRequest->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ old('status', $leaveRequest->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ old('status', $leaveRequest->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="cancelled" {{ old('status', $leaveRequest->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            @error('status')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="guardian_notified" class="form-label">Guardian Notified</label>
            <select name="guardian_notified" id="guardian_notified" class="form-control" required>
                <option value="0" {{ old('guardian_notified', $leaveRequest->guardian_notified) == 0 ? 'selected' : '' }}>No</option>
                <option value="1" {{ old('guardian_notified', $leaveRequest->guardian_notified) == 1 ? 'selected' : '' }}>Yes</option>
            </select>
            @error('guardian_notified')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control">{{ old('notes', $leaveRequest->notes) }}</textarea>
            @error('notes')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">Update Request</button>
        <a href="{{ route('hostel.leave_requests.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 