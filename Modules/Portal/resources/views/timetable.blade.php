@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-clock me-2"></i>My Timetable</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.timetable') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    <div class="row g-4">
        {{-- Today's Schedule --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Today's Schedule ({{ $today }})</h5>
                </div>
                <div class="card-body">
                    @if($todaySchedule->count())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Time</th>
                                        <th>Subject</th>
                                        <th>Teacher</th>
                                        <th>Room</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todaySchedule as $period)
                                        @php
                                            $isCurrent = $currentPeriod && $currentPeriod->id === $period->id;
                                            $rowClass = $isCurrent ? 'table-primary' : '';
                                        @endphp
                                        <tr class="{{ $rowClass }}">
                                            <td>
                                                <strong>{{ $period->start_time }} - {{ $period->end_time }}</strong>
                                                @if($isCurrent)
                                                    <span class="badge bg-primary ms-2">Current</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ $period->subject->name ?? 'Unknown Subject' }}</strong></td>
                                            <td>{{ $period->teacher->name ?? 'TBD' }}</td>
                                            <td>{{ $period->room ?? 'TBD' }}</td>
                                            <td>
                                                @if($isCurrent)
                                                    <span class="badge bg-primary">In Progress</span>
                                                @elseif($period->start_time > now()->format('H:i:s'))
                                                    <span class="badge bg-warning">Upcoming</span>
                                                @else
                                                    <span class="badge bg-success">Completed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-day fa-2x mb-2"></i>
                            <p>No classes scheduled for today.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Current Period Info --}}
        <div class="col-lg-4">
            @if($currentPeriod)
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-play-circle fa-3x mb-3"></i>
                    <h4 class="mb-2">Current Period</h4>
                    <h5 class="mb-1">{{ $currentPeriod->subject->name ?? 'Unknown Subject' }}</h5>
                    <p class="mb-2">{{ $currentPeriod->teacher->name ?? 'TBD' }}</p>
                    <p class="mb-0">{{ $currentPeriod->start_time }} - {{ $currentPeriod->end_time }}</p>
                    <small class="d-block mt-2">Room: {{ $currentPeriod->room ?? 'TBD' }}</small>
                </div>
            </div>
            @else
            <div class="card bg-light">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-3x mb-3 text-muted"></i>
                    <h5 class="mb-1">No Current Class</h5>
                    <p class="text-muted mb-0">You're currently free!</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Weekly Schedule --}}
        <div class="col-lg-12 printable-area">
            <div class="card" id="weekly-schedule-section">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-week me-2"></i>Weekly Schedule</h5>
                    <button class="btn btn-sm btn-outline-primary no-print" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Schedule
                    </button>
                </div>
                <div class="card-body">
                    @if($scheduleByDay->count())
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 120px;">Time</th>
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
                                        $timeSlots = [
                                            '08:00:00' => '08:00 - 09:00',
                                            '09:00:00' => '09:00 - 10:00',
                                            '10:00:00' => '10:00 - 11:00',
                                            '11:00:00' => '11:00 - 12:00',
                                            '12:00:00' => '12:00 - 13:00',
                                            '13:00:00' => '13:00 - 14:00',
                                            '14:00:00' => '14:00 - 15:00',
                                            '15:00:00' => '15:00 - 16:00',
                                        ];
                                    @endphp
                                    
                                    @foreach($timeSlots as $startTime => $timeLabel)
                                        <tr>
                                            <td class="fw-bold">{{ $timeLabel }}</td>
                                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                @php
                                                    $period = $scheduleByDay->get($day, collect())->where('start_time', $startTime)->first();
                                                @endphp
                                                <td class="text-center">
                                                    @if($period)
                                                        <div class="p-2">
                                                            <strong>{{ $period->subject->name ?? 'Unknown' }}</strong><br>
                                                            <small class="text-muted">{{ $period->teacher->name ?? 'TBD' }}</small><br>
                                                            <small class="text-muted">{{ $period->room ?? 'TBD' }}</small>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-week fa-2x mb-2"></i>
                            <p>No weekly schedule available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    body > *:not(.printable-area) {
        display: none !important;
    }
    .printable-area {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
    .no-print {
        display: none !important;
    }
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
}
</style>
@endpush
