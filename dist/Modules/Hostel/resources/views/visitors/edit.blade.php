@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Visitor Log</h1>
    <form action="{{ route('hostel.visitors.update', $hostelVisitor) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="hostel_id" class="form-label">Hostel</label>
            <select name="hostel_id" id="hostel_id" class="form-control" required>
                <option value="">Select Hostel</option>
                @foreach($hostels as $hostel)
                    <option value="{{ $hostel->id }}" {{ old('hostel_id', $hostelVisitor->hostel_id) == $hostel->id ? 'selected' : '' }}>{{ $hostel->name }}</option>
                @endforeach
            </select>
            @error('hostel_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="student_id" class="form-label">Student Visited</label>
            <select name="student_id" id="student_id" class="form-control" required>
                <option value="">Select Student</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ old('student_id', $hostelVisitor->student_id) == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                @endforeach
            </select>
            @error('student_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="visitor_name" class="form-label">Visitor Name</label>
            <input type="text" name="visitor_name" id="visitor_name" class="form-control" value="{{ old('visitor_name', $hostelVisitor->visitor_name) }}" required>
            @error('visitor_name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="visitor_contact" class="form-label">Visitor Contact</label>
            <input type="text" name="visitor_contact" id="visitor_contact" class="form-control" value="{{ old('visitor_contact', $hostelVisitor->visitor_contact) }}">
            @error('visitor_contact')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="purpose" class="form-label">Purpose</label>
            <input type="text" name="purpose" id="purpose" class="form-control" value="{{ old('purpose', $hostelVisitor->purpose) }}">
            @error('purpose')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="time_in" class="form-label">Time In</label>
            <input type="datetime-local" name="time_in" id="time_in" class="form-control" value="{{ old('time_in', $hostelVisitor->time_in) }}" required>
            @error('time_in')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="time_out" class="form-label">Time Out</label>
            <input type="datetime-local" name="time_out" id="time_out" class="form-control" value="{{ old('time_out', $hostelVisitor->time_out) }}">
            @error('time_out')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="pass_number" class="form-label">Pass Number</label>
            <input type="text" name="pass_number" id="pass_number" class="form-control" value="{{ old('pass_number', $hostelVisitor->pass_number) }}">
            @error('pass_number')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control">{{ old('notes', $hostelVisitor->notes) }}</textarea>
            @error('notes')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">Update Log</button>
        <a href="{{ route('hostel.visitors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 