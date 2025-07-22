@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Auto-Generation Preview</h2>
    <div class="mb-4">
        <h5>Scheduled Assignments</h5>
        @if(!empty($results) && count($results))
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Class</th>
                        <th>Teacher</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>Start</th>
                        <th>End</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $row)
                    <tr>
                        <td>{{ $row['class_name'] }}</td>
                        <td>{{ $row['teacher_name'] }}</td>
                        <td>{{ $row['room_name'] }}</td>
                        <td>{{ $row['day_of_week'] }}</td>
                        <td>{{ $row['start_time'] }}</td>
                        <td>{{ $row['end_time'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-warning">No assignments could be generated with the current constraints.</div>
        @endif
    </div>
    @if(!empty($results) && count($results))
    <form method="POST" action="{{ route('timetables.autogenerate.save') }}">
        @csrf
        <input type="hidden" name="assignments" value='@json($results)'>
        <input type="hidden" name="timetable_id" value="{{ $data['timetable_id'] }}">
        <button type="submit" class="btn btn-success mt-4">Save Generated Timetable</button>
    </form>
    @endif
    <div class="mb-4">
        <h5>Unassigned Classes</h5>
        @if(!empty($unassigned) && count($unassigned))
        <ul class="list-group">
            @foreach($unassigned as $ua)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ $ua['class_name'] }}</span>
                <span class="badge bg-danger">{{ $ua['reason'] }}</span>
            </li>
            @endforeach
        </ul>
        @else
        <div class="alert alert-success">All classes were assigned a slot.</div>
        @endif
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection 