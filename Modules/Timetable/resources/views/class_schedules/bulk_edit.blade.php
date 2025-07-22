@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Bulk Edit Schedules</h2>
    <form method="POST" action="{{ route('class_schedules.bulkUpdate') }}">
        @csrf
        @foreach($schedules as $s)
            <input type="hidden" name="schedule_ids[]" value="{{ $s->id }}">
        @endforeach
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Teacher</label>
                <select name="teacher_id" class="form-select">
                    <option value="">(No Change)</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Room</label>
                <select name="room_id" class="form-select">
                    <option value="">(No Change)</option>
                    @foreach($rooms as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Day</label>
                <select name="day_of_week" class="form-select">
                    <option value="">(No Change)</option>
                    <option>Monday</option>
                    <option>Tuesday</option>
                    <option>Wednesday</option>
                    <option>Thursday</option>
                    <option>Friday</option>
                    <option>Saturday</option>
                    <option>Sunday</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Start Time</label>
                <input type="time" name="start_time" class="form-control" placeholder="(No Change)">
            </div>
            <div class="col-md-2">
                <label class="form-label">End Time</label>
                <input type="time" name="end_time" class="form-control" placeholder="(No Change)">
            </div>
        </div>
        <button type="submit" class="btn btn-success">Update All</button>
        <a href="{{ route('class_schedules.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
    <div class="mt-4">
        <h5>Selected Schedules</h5>
        <ul class="list-group">
            @foreach($schedules as $s)
            <li class="list-group-item">
                ID: {{ $s->id }}, Class: {{ $s->class_id }}, Teacher: {{ $s->teacher_id }}, Room: {{ $s->room_id }}, {{ $s->day_of_week }} {{ $s->start_time }}-{{ $s->end_time }}
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection 