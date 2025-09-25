@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-calendar-alt me-2"></i>Schedule</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.schedule') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    {{-- Class Timetable --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Class Timetable</h5>
        </div>
        <div class="card-body">
            @if($classTimetable->count())
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Room</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classTimetable->groupBy('day_of_week') as $day => $entries)
                    @foreach($entries as $entry)
                    <tr>
                        @if($loop->first)
                        <td rowspan="{{ $entries->count() }}" class="align-middle fw-bold">{{ ucfirst($day) }}</td>
                        @endif
                        <td>{{ $entry->start_time }}</td>
                        <td>{{ $entry->end_time }}</td>
                        <td>{{ $entry->subject->name ?? '-' }}</td>
                        <td>{{ $entry->teacher->name ?? '-' }}</td>
                        <td>{{ $entry->room->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center text-muted py-4">
                <i class="fas fa-calendar-times fa-2x mb-2"></i>
                <p>No class timetable available for this class.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Exam Timetable --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Exam Timetable</h5>
        </div>
        <div class="card-body">
            @if($examTimetable->count())
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Subject</th>
                        <th>Exam Type</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($examTimetable as $exam)
                    <tr>
                        <td>
                            @if($exam->subjects->count())
                            @foreach($exam->subjects as $subject)
                            <span class="badge bg-primary">{{ $subject->name }}</span>
                            @endforeach
                            @else
                            -
                            @endif
                        </td>
                        <td>{{ ucfirst($exam->exam_type) }}</td>
                        <td>{{ $exam->start_date->format('M d, Y') }}</td>
                        <td>{{ $exam->start_date->format('H:i') }} - {{ $exam->end_date->format('H:i') }}</td>
                        <td>
                            @php
                                $now = now();
                                $isUpcoming = $exam->start_date->isFuture();
                                $isOngoing = $now->between($exam->start_date, $exam->end_date);
                            @endphp
                            <span class="badge bg-{{ $isOngoing ? 'success' : ($isUpcoming ? 'info' : 'secondary') }}">
                                {{ $isOngoing ? 'Ongoing' : ($isUpcoming ? 'Upcoming' : 'Past') }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center text-muted py-4">
                <i class="fas fa-calendar-check fa-2x mb-2"></i>
                <p>No exams scheduled for this class.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 