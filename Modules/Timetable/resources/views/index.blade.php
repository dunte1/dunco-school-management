<x-timetable::layouts.master>
    <div class="container py-5">
        <h2 class="fw-bold mb-4" style="font-family:'Poppins',sans-serif;letter-spacing:1px;color:#1a237e;">
            Timetable Dashboard
        </h2>
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card shadow-sm text-center p-3">
                    <div class="fw-bold">Classes Scheduled</div>
                    <div class="display-6">{{ $totalSchedules }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center p-3">
                    <div class="fw-bold">Teachers Scheduled</div>
                    <div class="display-6">{{ $totalTeachers }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center p-3">
                    <div class="fw-bold">Rooms Allocated</div>
                    <div class="display-6">{{ $totalAllocations }}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center p-3">
                    <div class="fw-bold">Conflicts</div>
                    <div class="display-6 text-{{ $conflicts > 0 ? 'danger' : 'success' }}">{{ $conflicts }}</div>
                </div>
            </div>
        </div>
        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <div class="fw-bold mb-2">Teacher Availability Today</div>
                    <span class="badge bg-success">Available: {{ $availableTeachers }}</span>
                    <span class="badge bg-danger ms-2">Unavailable: {{ $unavailableTeachers }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <div class="fw-bold mb-2">Timetable Status</div>
                    <span class="badge bg-{{ $status == 'complete' ? 'success' : ($status == 'partial' ? 'warning' : 'danger') }}">{{ ucfirst($status) }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <div class="fw-bold mb-2">Quick Links</div>
                    <a href="{{ route('class_schedules.create') }}" class="btn btn-primary btn-sm mb-1">+ Create Schedule</a>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm p-3">
                    <div class="fw-bold mb-2">Upcoming Schedules</div>
                    <table class="table table-sm table-bordered mb-0">
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
                            @forelse($upcomingSchedules as $s)
                                <tr>
                                    <td>{{ $s->class->name ?? $s->class_id }}</td>
                                    <td>{{ $s->teacher->name ?? $s->teacher_id }}</td>
                                    <td>{{ $s->room->name ?? $s->room_id }}</td>
                                    <td>{{ $s->day_of_week }}</td>
                                    <td>{{ $s->start_time }}</td>
                                    <td>{{ $s->end_time }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">No upcoming schedules.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('class_schedules.index') }}" class="card module-card border-0 shadow-sm text-decoration-none h-100 glassmorphism p-4 d-flex flex-column align-items-center justify-content-center">
                    <span class="stat-icon mb-2" style="font-size:2rem;"><i class="fas fa-calendar-alt text-primary"></i></span>
                    <div class="fw-bold mb-1" style="font-size:1.22rem;">Class Schedules</div>
                    <div class="text-muted text-center small">View and manage all class schedules.</div>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('teacher_availabilities.index') }}" class="card module-card border-0 shadow-sm text-decoration-none h-100 glassmorphism p-4 d-flex flex-column align-items-center justify-content-center">
                    <span class="stat-icon mb-2" style="font-size:2rem;"><i class="fas fa-user-clock text-success"></i></span>
                    <div class="fw-bold mb-1" style="font-size:1.22rem;">Teacher Availabilities</div>
                    <div class="text-muted text-center small">Manage teacher availability for scheduling.</div>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('rooms.index') }}" class="card module-card border-0 shadow-sm text-decoration-none h-100 glassmorphism p-4 d-flex flex-column align-items-center justify-content-center">
                    <span class="stat-icon mb-2" style="font-size:2rem;"><i class="fas fa-door-open text-info"></i></span>
                    <div class="fw-bold mb-1" style="font-size:1.22rem;">Rooms</div>
                    <div class="text-muted text-center small">Manage rooms and their details.</div>
                </a>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('room_allocations.index') }}" class="card module-card border-0 shadow-sm text-decoration-none h-100 glassmorphism p-4 d-flex flex-column align-items-center justify-content-center">
                    <span class="stat-icon mb-2" style="font-size:2rem;"><i class="fas fa-th-large text-warning"></i></span>
                    <div class="fw-bold mb-1" style="font-size:1.22rem;">Room Allocations</div>
                    <div class="text-muted text-center small">Allocate rooms to class schedules.</div>
                </a>
            </div>
        </div>
    </div>
</x-timetable::layouts.master>
