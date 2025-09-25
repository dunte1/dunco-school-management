@extends('layouts.app')

@section('content')
<h2>Apply for Leave</h2>
<form method="POST" action="{{ route('hr.leave.store') }}">
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
        <label class="form-label">Leave Type</label>
        <select name="type" class="form-select" required>
            <option value="">Select Type</option>
            @foreach($types as $type)
                <option value="{{ $type->name }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Reason</label>
        <input type="text" name="reason" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Submit</button>
    <a href="{{ route('hr.leave.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection 