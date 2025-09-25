@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Timetable Settings</h2>
    <div class="card">
        <div class="card-body">
            <form>
                <div class="mb-3">
                    <label class="form-label">Default Time Slots</label>
                    <ul>
                        @foreach(config('timetable.default_time_slots') as $slot)
                            <li>{{ $slot }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="mb-3">
                    <label class="form-label">Teaching Hours Per Week</label>
                    <input type="number" class="form-control" value="{{ config('timetable.teaching_hours_per_week') }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Periods Per Day</label>
                    <input type="number" class="form-control" value="{{ config('timetable.periods_per_day') }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Schedule Format</label>
                    <input type="text" class="form-control" value="{{ config('timetable.schedule_format') }}" readonly>
                </div>
                <div class="alert alert-info">Editing and saving settings can be enabled here in the future.</div>
            </form>
        </div>
    </div>
</div>
@endsection 