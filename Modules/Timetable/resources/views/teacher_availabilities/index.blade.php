@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Teacher Availabilities</h2>
        <a href="{{ route('teacher_availabilities.create') }}" class="btn btn-primary">Add Availability</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="mb-3">
        <button class="btn btn-outline-secondary me-2" id="gridViewBtn">
            <i class="fas fa-th"></i> Weekly Grid View
        </button>
        <button class="btn btn-outline-secondary" id="calendarViewBtn">
            <i class="fas fa-calendar-alt"></i> Calendar View
        </button>
    </div>
    <div id="weeklyGridView" style="display:none;">
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Teacher</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                        <th>Sunday</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                    $teachers = $availabilities->pluck('teacher')->unique('id')->filter();
                    @endphp
                    @foreach($teachers as $teacher)
                    <tr>
                        <th class="text-start">{{ $teacher->first_name }} {{ $teacher->last_name }}</th>
                        @foreach($days as $day)
                            <td>
                                @php
                                    $slots = $teacher->availabilities->where('day_of_week', $day);
                                @endphp
                                @if($slots->isEmpty())
                                    <span class="text-muted">—</span>
                                @else
                                    @foreach($slots as $slot)
                                        <div class="badge bg-success mb-1">{{ $slot->start_time }} - {{ $slot->end_time }}</div><br>
                                    @endforeach
                                @endif
                            </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div id="calendarView" style="display:none;">
        <form class="mb-3">
            <label for="calendarTeacher" class="form-label">Select Teacher</label>
            <select id="calendarTeacher" class="form-select" onchange="showTeacherCalendar()">
                <option value="">-- Select --</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                @endforeach
            </select>
        </form>
        <div id="teacherCalendarContainer">
            <div class="alert alert-info">Select a teacher to view their weekly availability.</div>
        </div>
    </div>
    <script>
        document.getElementById('gridViewBtn').addEventListener('click', function() {
            document.getElementById('weeklyGridView').style.display = '';
            document.getElementById('calendarView').style.display = 'none';
        });
        document.getElementById('calendarViewBtn').addEventListener('click', function() {
            document.getElementById('weeklyGridView').style.display = 'none';
            document.getElementById('calendarView').style.display = '';
        });

        function showTeacherCalendar() {
            var teacherId = document.getElementById('calendarTeacher').value;
            var container = document.getElementById('teacherCalendarContainer');
            if (!teacherId) {
                container.innerHTML = '<div class="alert alert-info">Select a teacher to view their weekly availability.</div>';
                return;
            }
            var teachers = @json($teachers->keyBy('id'));
            var availabilities = @json($availabilities->groupBy('teacher_id'));
            var teacher = teachers[teacherId];
            var slots = availabilities[teacherId] || [];
            var days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            var html = '<h5 class="mb-3">' + teacher.first_name + ' ' + teacher.last_name + ' - Weekly Availability</h5>';
            html += '<table class="table table-bordered text-center"><thead><tr><th>Day</th><th>Available Times</th></tr></thead><tbody>';
            days.forEach(function(day) {
                html += '<tr><th>' + day + '</th><td>';
                var found = false;
                slots.forEach(function(slot) {
                    if (slot.day_of_week === day) {
                        html += '<span class="badge bg-success mb-1">' + slot.start_time + ' - ' + slot.end_time + '</span> ';
                        found = true;
                    }
                });
                if (!found) html += '<span class="text-muted">—</span>';
                html += '</td></tr>';
            });
            html += '</tbody></table>';
            container.innerHTML = html;
        }
    </script>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Teacher</th>
                <th>Day</th>
                <th>Start</th>
                <th>End</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($availabilities as $a)
                <tr>
                    <td>{{ $a->id }}</td>
                    <td>{{ $a->teacher ? $a->teacher->first_name . ' ' . $a->teacher->last_name : $a->teacher_id }}</td>
                    <td><span class="badge bg-primary">{{ $a->day_of_week }}</span></td>
                    <td><span class="badge bg-success">{{ $a->start_time }}</span></td>
                    <td><span class="badge bg-danger">{{ $a->end_time }}</span></td>
                    <td>
                        <a href="{{ route('teacher_availabilities.show', $a->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('teacher_availabilities.edit', $a->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('teacher_availabilities.destroy', $a->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this availability?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No availabilities found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $availabilities->links() }}
</div>
@endsection 