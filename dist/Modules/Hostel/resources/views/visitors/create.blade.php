@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Log Visitor Entry</h1>
    <form action="{{ route('hostel.visitors.store') }}" method="POST">
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
            <label for="student_id" class="form-label">Student Visited</label>
            <select name="student_id" id="student_id" class="form-control" required>
                <option value="">Select Student</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                @endforeach
            </select>
            @error('student_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="visitor_name" class="form-label">Visitor Name</label>
            <input type="text" name="visitor_name" id="visitor_name" class="form-control" value="{{ old('visitor_name') }}" required>
            @error('visitor_name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="visitor_contact" class="form-label">Visitor Contact</label>
            <input type="text" name="visitor_contact" id="visitor_contact" class="form-control" value="{{ old('visitor_contact') }}">
            @error('visitor_contact')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="purpose" class="form-label">Purpose</label>
            <input type="text" name="purpose" id="purpose" class="form-control" value="{{ old('purpose') }}">
            @error('purpose')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="time_in" class="form-label">Time In</label>
            <input type="datetime-local" name="time_in" id="time_in" class="form-control" value="{{ old('time_in') }}" required>
            @error('time_in')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
            @error('notes')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">Log Entry</button>
        <a href="{{ route('hostel.visitors.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 