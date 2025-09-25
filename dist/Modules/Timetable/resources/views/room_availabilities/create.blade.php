@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Add Room Availability</h2>
    <form action="{{ route('room_availabilities.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="room_id" class="form-label">Room</label>
            <select name="room_id" id="room_id" class="form-control" required>
                <option value="">Select Room</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="day_of_week" class="form-label">Day of Week</label>
            <input type="text" name="day_of_week" id="day_of_week" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="start_time" class="form-label">Start Time</label>
            <input type="time" name="start_time" id="start_time" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="end_time" class="form-label">End Time</label>
            <input type="time" name="end_time" id="end_time" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Add Availability</button>
        <a href="{{ route('room_availabilities.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 