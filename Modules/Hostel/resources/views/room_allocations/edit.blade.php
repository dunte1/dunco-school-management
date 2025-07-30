@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Room Allocation</h1>
    <form action="{{ route('hostel.room_allocations.update', $roomAllocation) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="student_id" class="form-label">Student</label>
            <select name="student_id" id="student_id" class="form-control" required>
                <option value="">Select Student</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ old('student_id', $roomAllocation->student_id) == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                @endforeach
            </select>
            @error('student_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Allocation Type</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="allocation_type" id="manual" value="manual" {{ old('allocation_type', $roomAllocation->allocation_type) == 'manual' ? 'checked' : '' }} onclick="toggleAllocationType()">
                <label class="form-check-label" for="manual">Manual</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="allocation_type" id="auto" value="auto" {{ old('allocation_type', $roomAllocation->allocation_type) == 'auto' ? 'checked' : '' }} onclick="toggleAllocationType()">
                <label class="form-check-label" for="auto">Auto</label>
            </div>
            @error('allocation_type')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div id="manualFields" style="display: {{ old('allocation_type', $roomAllocation->allocation_type) == 'manual' ? 'block' : 'none' }};">
            <div class="mb-3">
                <label for="bed_id" class="form-label">Bed (Manual)</label>
                <select name="bed_id" id="bed_id" class="form-control">
                    <option value="">Select Bed</option>
                    @foreach($beds as $bed)
                        <option value="{{ $bed->id }}" {{ old('bed_id', $roomAllocation->bed_id) == $bed->id ? 'selected' : '' }}>
                            {{ $bed->bed_number }} - Room: {{ $bed->room->name ?? 'N/A' }} - Hostel: {{ $bed->room->hostel->name ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
                @error('bed_id')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
        </div>
        <div id="autoFields" style="display: {{ old('allocation_type', $roomAllocation->allocation_type) == 'auto' ? 'block' : 'none' }};">
            <div class="mb-3">
                <label for="preferences[gender]" class="form-label">Preferred Gender (optional)</label>
                <select name="preferences[gender]" id="preferences_gender" class="form-control">
                    <option value="">No Preference</option>
                    <option value="male" {{ old('preferences.gender', $roomAllocation->preferences['gender'] ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('preferences.gender', $roomAllocation->preferences['gender'] ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="mixed" {{ old('preferences.gender', $roomAllocation->preferences['gender'] ?? '') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                </select>
            </div>
            <!-- Add more preference fields as needed -->
        </div>
        <div class="mb-3">
            <label for="check_in" class="form-label">Check In</label>
            <input type="date" name="check_in" id="check_in" class="form-control" value="{{ old('check_in', $roomAllocation->check_in ? $roomAllocation->check_in->format('Y-m-d') : '') }}">
            @error('check_in')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control">{{ old('notes', $roomAllocation->notes) }}</textarea>
            @error('notes')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">Update Allocation</button>
        <a href="{{ route('hostel.room_allocations.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script>
function toggleAllocationType() {
    var manual = document.getElementById('manual').checked;
    document.getElementById('manualFields').style.display = manual ? 'block' : 'none';
    document.getElementById('autoFields').style.display = manual ? 'none' : 'block';
}
window.onload = toggleAllocationType;
</script>
@endsection
