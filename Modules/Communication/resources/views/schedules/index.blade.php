@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Communication Schedules</h1>
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="email" {{ request('type') == 'email' ? 'selected' : '' }}>Email</option>
                <option value="sms" {{ request('type') == 'sms' ? 'selected' : '' }}>SMS</option>
                <option value="notification" {{ request('type') == 'notification' ? 'selected' : '' }}>Notification</option>
            </select>
            <select name="frequency" class="form-select">
                <option value="">All Frequencies</option>
                <option value="once" {{ request('frequency') == 'once' ? 'selected' : '' }}>Once</option>
                <option value="daily" {{ request('frequency') == 'daily' ? 'selected' : '' }}>Daily</option>
                <option value="weekly" {{ request('frequency') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ request('frequency') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="yearly" {{ request('frequency') == 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search schedules...">
            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
        </form>
        <a href="{{ route('communication.schedules.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Schedule
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($schedules->count())
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Frequency</th>
                    <th>Next Run</th>
                    <th>Status</th>
                    <th>Template</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                    <tr>
                        <td>
                            <strong>{{ $schedule->title }}</strong>
                            @if($schedule->description)
                                <br>
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($schedule->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($schedule->type) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($schedule->frequency) }}</span>
                            @if($schedule->frequency === 'weekly' && $schedule->days_of_week)
                                <br>
                                <small class="text-muted">
                                    @php
                                        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                                        $selectedDays = collect($schedule->days_of_week)->map(function($day) use ($days) {
                                            return $days[$day];
                                        })->implode(', ');
                                    @endphp
                                    {{ $selectedDays }}
                                </small>
                            @endif
                        </td>
                        <td>
                            @if($schedule->isActive())
                                @php
                                    $nextRun = $schedule->getNextRunDate();
                                @endphp
                                @if($nextRun)
                                    <span class="text-primary">{{ $nextRun->format('M j, Y g:i A') }}</span>
                                @else
                                    <span class="text-muted">Not scheduled</span>
                                @endif
                            @else
                                <span class="text-muted">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $schedule->isActive() ? 'bg-success' : 'bg-secondary' }}">
                                {{ $schedule->isActive() ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            @if($schedule->template)
                                <span class="badge bg-light text-dark">{{ $schedule->template->name }}</span>
                            @else
                                <span class="text-muted">No template</span>
                            @endif
                        </td>
                        <td>{{ $schedule->creator->name ?? 'Unknown' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('communication.schedules.show', $schedule) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('communication.schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($schedule->isActive())
                                    <form method="POST" action="{{ route('communication.schedules.run-now', $schedule) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('communication.schedules.toggle-status', $schedule) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-toggle-{{ $schedule->is_active ? 'off' : 'on' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('communication.schedules.destroy', $schedule) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this schedule?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $schedules->links() }}
@else
    <div class="text-center py-5">
        <i class="fas fa-clock fa-3x text-muted mb-3"></i>
        <h4>No schedules found</h4>
        <p class="text-muted">Create your first communication schedule to get started.</p>
        <a href="{{ route('communication.schedules.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Schedule
        </a>
    </div>
@endif
@endsection
