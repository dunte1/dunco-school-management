@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">{{ $schedule->title }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('communication.schedules.edit', $schedule) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-2"></i>Edit Schedule
        </a>
        <a href="{{ route('communication.schedules.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Schedules
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Schedule Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Title:</strong>
                        <p>{{ $schedule->title }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Type:</strong>
                        <p><span class="badge bg-info">{{ ucfirst($schedule->type) }}</span></p>
                    </div>
                </div>
                
                @if($schedule->description)
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p>{{ $schedule->description }}</p>
                    </div>
                @endif
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Frequency:</strong>
                        <p><span class="badge bg-secondary">{{ ucfirst($schedule->frequency) }}</span></p>
                        @if($schedule->frequency === 'weekly' && $schedule->days_of_week)
                            <small class="text-muted">
                                @php
                                    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                    $selectedDays = collect($schedule->days_of_week)->map(function($day) use ($days) {
                                        return $days[$day];
                                    })->implode(', ');
                                @endphp
                                Days: {{ $selectedDays }}
                            </small>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong>Time:</strong>
                        <p>{{ $schedule->time->format('g:i A') }}</p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Start Date:</strong>
                        <p>{{ $schedule->start_date->format('F j, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>End Date:</strong>
                        <p>{{ $schedule->end_date ? $schedule->end_date->format('F j, Y') : 'No end date' }}</p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <p>
                            <span class="badge {{ $schedule->isActive() ? 'bg-success' : 'bg-secondary' }}">
                                {{ $schedule->isActive() ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>Next Run:</strong>
                        @if($schedule->isActive())
                            @php
                                $nextRun = $schedule->getNextRunDate();
                            @endphp
                            @if($nextRun)
                                <p class="text-primary">{{ $nextRun->format('F j, Y \a\t g:i A') }}</p>
                            @else
                                <p class="text-muted">Not scheduled</p>
                            @endif
                        @else
                            <p class="text-muted">Inactive</p>
                        @endif
                    </div>
                </div>
                
                @if($schedule->template)
                    <div class="mb-3">
                        <strong>Template:</strong>
                        <p>
                            <span class="badge bg-light text-dark">{{ $schedule->template->name }}</span>
                            <br>
                            <small class="text-muted">{{ $schedule->template->subject }}</small>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Schedule Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Created By:</strong>
                    <p>{{ $schedule->creator->name ?? 'Unknown' }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Created At:</strong>
                    <p>{{ $schedule->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Last Updated:</strong>
                    <p>{{ $schedule->updated_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Actions:</strong>
                    <div class="d-grid gap-2">
                        @if($schedule->isActive())
                            <form method="POST" action="{{ route('communication.schedules.run-now', $schedule) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-play me-2"></i>Run Now
                                </button>
                            </form>
                        @endif
                        
                        <form method="POST" action="{{ route('communication.schedules.toggle-status', $schedule) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-toggle-{{ $schedule->is_active ? 'off' : 'on' }} me-2"></i>
                                {{ $schedule->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('communication.schedules.destroy', $schedule) }}" onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-2"></i>Delete Schedule
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">0</h4>
                            <small class="text-muted">Executions</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-0">0</h4>
                        <small class="text-muted">Success Rate</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
