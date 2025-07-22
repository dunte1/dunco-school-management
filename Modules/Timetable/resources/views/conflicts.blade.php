@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Timetable Conflicts</h2>
    <div class="mb-4">
        @if(count($conflicts))
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Teacher/Room/Student</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Affected Schedules</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($conflicts as $conflict)
                    <tr>
                        <td>{{ $conflict['type'] }}</td>
                        <td>{{ $conflict['teacher'] ?? $conflict['room'] ?? $conflict['student'] ?? '-' }}</td>
                        <td>{{ $conflict['day'] }}</td>
                        <td>{{ $conflict['time'] }}</td>
                        <td>
                            <ul class="list-group">
                                @foreach($conflict['schedules'] as $s)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        Class: {{ $s->class->name ?? $s->class_id }}<br>
                                        Teacher: {{ $s->teacher->name ?? $s->teacher_id }}<br>
                                        Room: {{ $s->room->name ?? $s->room_id }}<br>
                                        {{ $s->start_time }} - {{ $s->end_time }}
                                    </span>
                                    <span>
                                        <a href="{{ route('class_schedules.edit', $s->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        @php $suggestion = app(Modules\Timetable\Http\Controllers\TimetableController::class)->suggestNextAvailableSlot($s); @endphp
                                        <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#autoResolveModal{{ $s->id }}">Auto-Resolve</a>
                                        <!-- Modal -->
                                        <div class="modal fade" id="autoResolveModal{{ $s->id }}" tabindex="-1" aria-labelledby="autoResolveModalLabel{{ $s->id }}" aria-hidden="true">
                                          <div class="modal-dialog">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="autoResolveModalLabel{{ $s->id }}">Auto-Resolve Suggestion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                              </div>
                                              <div class="modal-body">
                                                @if($suggestion)
                                                <p>Suggested slot:</p>
                                                <ul>
                                                  <li>Day: <strong>{{ $suggestion['day'] }}</strong></li>
                                                  <li>Start: <strong>{{ $suggestion['start_time'] }}</strong></li>
                                                  <li>End: <strong>{{ $suggestion['end_time'] }}</strong></li>
                                                </ul>
                                                <form method="POST" action="{{ route('class_schedules.update', $s->id) }}">
                                                  @csrf
                                                  @method('PUT')
                                                  <input type="hidden" name="day_of_week" value="{{ $suggestion['day'] }}">
                                                  <input type="hidden" name="start_time" value="{{ $suggestion['start_time'] }}">
                                                  <input type="hidden" name="end_time" value="{{ $suggestion['end_time'] }}">
                                                  <input type="hidden" name="class_id" value="{{ $s->class_id }}">
                                                  <input type="hidden" name="teacher_id" value="{{ $s->teacher_id }}">
                                                  <input type="hidden" name="room_id" value="{{ $s->room_id }}">
                                                  <input type="hidden" name="timetable_id" value="{{ $s->timetable_id }}">
                                                  <button type="submit" class="btn btn-success">Accept Suggestion</button>
                                                </form>
                                                @else
                                                <div class="alert alert-warning">No available slot found for auto-resolve.</div>
                                                @endif
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <form action="{{ route('class_schedules.destroy', $s->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this schedule?')">Delete</button>
                                        </form>
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-success">No timetable conflicts detected!</div>
        @endif
    </div>
</div>
@endsection 