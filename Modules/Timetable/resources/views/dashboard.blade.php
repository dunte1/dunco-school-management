@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Timetable Dashboard</h2>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="fs-1 text-primary"><i class="fas fa-calendar-alt"></i></div>
                    <h4 class="card-title">Schedules</h4>
                    <p class="card-text fs-3 fw-bold">{{ $totalSchedules }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="fs-1 text-success"><i class="fas fa-door-open"></i></div>
                    <h4 class="card-title">Rooms</h4>
                    <p class="card-text fs-3 fw-bold">{{ $totalRooms }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="fs-1 text-info"><i class="fas fa-th-large"></i></div>
                    <h4 class="card-title">Room Allocations</h4>
                    <p class="card-text fs-3 fw-bold">{{ $totalAllocations }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="fs-1 text-warning"><i class="fas fa-user-clock"></i></div>
                    <h4 class="card-title">Teachers</h4>
                    <p class="card-text fs-3 fw-bold">{{ $totalTeachers }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex flex-wrap gap-3 justify-content-center">
                    <a href="{{ route('class_schedules.index') }}" class="btn btn-outline-primary btn-lg"><i class="fas fa-calendar-alt me-2"></i>Class Schedules</a>
                    <a href="{{ route('teacher_availabilities.index') }}" class="btn btn-outline-info btn-lg"><i class="fas fa-user-clock me-2"></i>Teacher Availabilities</a>
                    <a href="{{ route('rooms.index') }}" class="btn btn-outline-success btn-lg"><i class="fas fa-door-open me-2"></i>Rooms</a>
                    <a href="{{ route('room_allocations.index') }}" class="btn btn-outline-dark btn-lg"><i class="fas fa-th-large me-2"></i>Room Allocations</a>
                </div>
            </div>
        </div>
    </div>
    @php
        $allTimetables = \App\Models\Modules\Timetable\Models\Timetable::all();
        $allTeachers = \App\Models\User::whereHas('roles', function($q){ $q->where('name', 'teacher'); })->get();
        $allClasses = \Modules\Academic\Models\AcademicClass::all();
        $allRooms = \Modules\Timetable\Models\Room::all();
    @endphp
    <div class="row mb-4">
        <div class="col-md-12">
            <form class="card shadow-sm p-3 mb-3" method="GET" action="">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label for="timetable_id" class="form-label">Timetable</label>
                        <select name="timetable_id" id="timetable_id" class="form-select">
                            <option value="">All</option>
                            @foreach($allTimetables as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="teacher_id" class="form-label">Teacher</label>
                        <select name="teacher_id" id="teacher_id" class="form-select">
                            <option value="">All</option>
                            @foreach($allTeachers as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="class_id" class="form-label">Class</label>
                        <select name="class_id" id="class_id" class="form-select">
                            <option value="">All</option>
                            @foreach($allClasses as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="room_id" class="form-label">Room</label>
                        <select name="room_id" id="room_id" class="form-select">
                            <option value="">All</option>
                            @foreach($allRooms as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 d-flex gap-2">
                        <button type="submit" formaction="{{ route('class_schedules.export.pdf') }}" class="btn btn-outline-secondary btn-lg"><i class="fas fa-file-pdf me-2"></i>Filter & Export PDF</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end gap-2">
            <button class="btn btn-outline-primary btn-lg mb-3" data-bs-toggle="modal" data-bs-target="#autoGenerateModal">
                <i class="fas fa-magic me-2"></i>Auto-Generate Timetable
            </button>
            <button class="btn btn-outline-secondary btn-lg mb-3" data-bs-toggle="modal" data-bs-target="#printTimetableModal">
                <i class="fas fa-print me-2"></i>Print Timetable
            </button>
            <a href="{{ route('timetables.reports') }}" class="btn btn-outline-info btn-lg mb-3">
                <i class="fas fa-chart-bar me-2"></i>Reports
            </a>
            @if(auth()->user() && auth()->user()->hasRole('admin'))
            <a href="{{ route('audit_logs.index') }}" class="btn btn-outline-dark btn-lg mb-3">
                <i class="fas fa-clipboard-list me-2"></i>Audit Logs
            </a>
            @endif
            @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('timetable_manager')))
            <a href="{{ route('timetable.analytics') }}" class="btn btn-outline-primary btn-lg mb-3">
                <i class="fas fa-chart-line me-2"></i>Analytics
            </a>
            @endif
            @if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('timetable_manager')))
            <a href="{{ route('timetable.conflicts') }}" class="btn btn-outline-danger btn-lg mb-3">
                <i class="fas fa-exclamation-triangle me-2"></i>Conflicts
            </a>
            @endif
        </div>
    </div>
    <!-- Auto-Generate Modal -->
    <div class="modal fade" id="autoGenerateModal" tabindex="-1" aria-labelledby="autoGenerateModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form method="POST" action="{{ route('timetables.autogenerate') }}">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="autoGenerateModalLabel">Auto-Generate Timetable</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="timetable_id" class="form-label">Target Timetable</label>
                <select name="timetable_id" id="timetable_id" class="form-select" required>
                  <option value="">Select Timetable</option>
                  @foreach($allTimetables as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Constraints</label>
                <div class="row g-2">
                  <div class="col-md-4">
                    <label>Classes</label>
                    <select name="class_ids[]" class="form-select" multiple>
                      @foreach($allClasses as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label>Teachers</label>
                    <select name="teacher_ids[]" class="form-select" multiple>
                      @foreach($allTeachers as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label>Rooms</label>
                    <select name="room_ids[]" class="form-select" multiple>
                      @foreach($allRooms as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label>Days of Week</label>
                <select name="days[]" class="form-select" multiple>
                  <option value="Monday">Monday</option>
                  <option value="Tuesday">Tuesday</option>
                  <option value="Wednesday">Wednesday</option>
                  <option value="Thursday">Thursday</option>
                  <option value="Friday">Friday</option>
                  <option value="Saturday">Saturday</option>
                  <option value="Sunday">Sunday</option>
                </select>
              </div>
              <div class="mb-3">
                <label>Time Slots (e.g. 08:00-09:00)</label>
                <input type="text" name="time_slots" class="form-control" placeholder="Comma-separated, e.g. 08:00-09:00,09:00-10:00">
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="avoid_double_booking" id="avoid_double_booking" checked>
                <label class="form-check-label" for="avoid_double_booking">Avoid double-booking (teachers/rooms)</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="enforce_availability" id="enforce_availability" checked>
                <label class="form-check-label" for="enforce_availability">Enforce teacher/room availability</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Auto-Generate</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Print Timetable Modal -->
    <div class="modal fade" id="printTimetableModal" tabindex="-1" aria-labelledby="printTimetableModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="GET" action="{{ route('timetables.print') }}" target="_blank">
            <div class="modal-header">
              <h5 class="modal-title" id="printTimetableModalLabel">Print Timetable</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="timetable_id_print" class="form-label">Timetable</label>
                <select name="timetable_id" id="timetable_id_print" class="form-select">
                  <option value="">All</option>
                  @foreach($allTimetables as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="class_id_print" class="form-label">Class</label>
                <select name="class_id" id="class_id_print" class="form-select">
                  <option value="">All</option>
                  @foreach($allClasses as $c)
                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="teacher_id_print" class="form-label">Teacher</label>
                <select name="teacher_id" id="teacher_id_print" class="form-select">
                  <option value="">All</option>
                  @foreach($allTeachers as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="room_id_print" class="form-label">Room</label>
                <select name="room_id" id="room_id_print" class="form-select">
                  <option value="">All</option>
                  @foreach($allRooms as $r)
                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Print</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection 