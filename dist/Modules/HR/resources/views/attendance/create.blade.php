@extends('layouts.app')

@section('content')
<h2>Add Attendance</h2>
<form method="POST" action="{{ route('hr.attendance.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Staff</label>
        <select name="staff_id" class="form-select" required>
            <option value="">Select Staff</option>
            @foreach($staff as $s)
                <option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Date</label>
        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Clock In</label>
        <input type="time" name="clock_in" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Clock Out</label>
        <input type="time" name="clock_out" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="present">Present</option>
            <option value="absent">Absent</option>
            <option value="leave">Leave</option>
            <option value="late">Late</option>
            <option value="half-day">Half-day</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Remarks</label>
        <input type="text" name="remarks" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="{{ route('hr.attendance.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection 