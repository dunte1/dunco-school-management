@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Room Availabilities</h2>
        <a href="{{ route('room_availabilities.create') }}" class="btn btn-primary">Add Availability</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="mb-3">
        <button class="btn btn-outline-secondary me-2" id="tableViewBtn">
            <i class="fas fa-list"></i> Table View
        </button>
        <button class="btn btn-outline-secondary" id="gridViewBtn">
            <i class="fas fa-th"></i> Weekly Grid View
        </button>
    </div>
    <div id="tableView">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room</th>
                    <th>Type</th>
                    <th>Equipment</th>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($availabilities as $a)
                    <tr>
                        <td>{{ $a->id }}</td>
                        <td>{{ $a->room->name ?? $a->room_id }}</td>
                        <td>{{ $a->room->type ?? '-' }}</td>
                        <td>
                            @if($a->room->equipment)
                                @foreach(explode(',', $a->room->equipment) as $item)
                                    <span class="badge bg-info text-dark me-1">{{ trim($item) }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td><span class="badge bg-primary">{{ $a->day_of_week }}</span></td>
                        <td><span class="badge bg-success">{{ $a->start_time }}</span></td>
                        <td><span class="badge bg-danger">{{ $a->end_time }}</span></td>
                        <td>
                            <a href="{{ route('room_availabilities.edit', $a->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('room_availabilities.destroy', $a->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this availability?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $availabilities->links() }}
    </div>
    <div id="weeklyGridView" style="display:none;">
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Room</th>
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
                    $rooms = $availabilities->pluck('room')->unique('id')->filter();
                    @endphp
                    @foreach($rooms as $room)
                    <tr>
                        <th class="text-start">{{ $room->name }}<br><small class="text-muted">{{ $room->type ?? '-' }}</small></th>
                        @foreach($days as $day)
                            <td>
                                @php
                                    $slots = $room->availabilities->where('day_of_week', $day);
                                @endphp
                                @if($slots->isEmpty())
                                    <span class="text-muted">—</span>
                                @else
                                    @foreach($slots as $slot)
                                        @php
                                            $conflict = $slots->where('start_time', $slot->start_time)->where('end_time', $slot->end_time)->count() > 1;
                                        @endphp
                                        <div class="badge {{ $conflict ? 'bg-danger' : 'bg-success' }} mb-1">
                                            {{ $slot->start_time }} - {{ $slot->end_time }}
                                            @if($conflict)
                                                <span class="fw-bold">Conflict!</span>
                                            @endif
                                        </div><br>
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
    <script>
        document.getElementById('tableViewBtn').addEventListener('click', function() {
            document.getElementById('tableView').style.display = '';
            document.getElementById('weeklyGridView').style.display = 'none';
        });
        document.getElementById('gridViewBtn').addEventListener('click', function() {
            document.getElementById('tableView').style.display = 'none';
            document.getElementById('weeklyGridView').style.display = '';
        });
    </script>
</div>
@endsection 