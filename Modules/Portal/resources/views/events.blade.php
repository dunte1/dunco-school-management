@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-calendar-alt me-2"></i>School Events</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.events') }}" class="d-flex align-items-center gap-2">
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
        {{-- Next Event Alert --}}
        @if($nextEvent)
        <div class="col-lg-12">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="fas fa-bell me-2"></i>
                <div>
                    <strong>Next Event:</strong> {{ $nextEvent->title ?? 'Unknown Event' }} 
                    on {{ $nextEvent->start_date ? $nextEvent->start_date->format('M d, Y') : 'TBD' }}
                    @if($nextEvent->start_date)
                        <span class="badge bg-warning ms-2">
                            {{ $nextEvent->start_date->diffForHumans() }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Upcoming Events --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Upcoming Events</h5>
                </div>
                <div class="card-body">
                    @if($upcomingEvents->count())
                        <div class="list-group list-group-flush">
                            @foreach($upcomingEvents as $event)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $event->title ?? 'Unknown Event' }}</h6>
                                        <p class="mb-1 text-muted">{{ $event->description ?? 'No description available' }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $event->start_date ? $event->start_date->format('M d, Y') : 'TBD' }}
                                            @if($event->end_date && $event->end_date != $event->start_date)
                                                - {{ $event->end_date->format('M d, Y') }}
                                            @endif
                                        </small>
                                        @if($event->location)
                                        <br><small class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $event->location }}
                                        </small>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        @if($event->category)
                                            <span class="badge bg-primary">{{ $event->category }}</span>
                                        @endif
                                        @if($event->is_important)
                                            <span class="badge bg-danger ms-1">Important</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-plus fa-2x mb-2"></i>
                            <p>No upcoming events scheduled.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Events by Category --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Events by Category</h5>
                </div>
                <div class="card-body">
                    @if($eventsByCategory->count())
                        <div class="list-group list-group-flush">
                            @foreach($eventsByCategory as $category => $events)
                            <div class="list-group-item">
                                <h6 class="mb-2">{{ $category }}</h6>
                                <small class="text-muted">{{ $events->count() }} event(s)</small>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-tags fa-2x mb-2"></i>
                            <p>No event categories available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Past Events --}}
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Past Events</h5>
                </div>
                <div class="card-body">
                    @if($pastEvents->count())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Event</th>
                                        <th>Date</th>
                                        <th>Location</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pastEvents as $event)
                                    <tr>
                                        <td>
                                            <strong>{{ $event->title ?? 'Unknown Event' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $event->description ?? 'No description' }}</small>
                                        </td>
                                        <td>{{ $event->start_date ? $event->start_date->format('M d, Y') : 'TBD' }}</td>
                                        <td>{{ $event->location ?? 'TBD' }}</td>
                                        <td>
                                            @if($event->category)
                                                <span class="badge bg-secondary">{{ $event->category }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Completed</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-history fa-2x mb-2"></i>
                            <p>No past events available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
