@extends('layouts.app')

@section('title', $onlineClass->title)

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('academic.online-classes.index') }}" style="color: #1a237e;">Online Classes</a></li>
                    <li class="breadcrumb-item active">{{ $onlineClass->title }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0" style="color: #1a237e; font-weight: 600;">{{ $onlineClass->title }}</h1>
        </div>
        <div class="d-flex gap-2">
            @if($onlineClass->status == 'ongoing' && $onlineClass->canBeJoined())
            <button class="btn btn-success btn-lg" onclick="joinClass()">
                <i class="fas fa-sign-in-alt me-2"></i>Join Class Now
            </button>
            @endif
            @if(auth()->user()->hasRole(['teacher', 'admin']) && $onlineClass->teacher_id == auth()->id())
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-cog me-2"></i>Manage
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('academic.online-classes.edit', $onlineClass->id) }}">
                        <i class="fas fa-edit me-2"></i>Edit Class
                    </a></li>
                    @if($onlineClass->status == 'scheduled')
                    <li><a class="dropdown-item" href="#" onclick="startClass()">
                        <i class="fas fa-play me-2"></i>Start Class
                    </a></li>
                    @endif
                    @if($onlineClass->status == 'ongoing')
                    <li><a class="dropdown-item" href="#" onclick="endClass()">
                        <i class="fas fa-stop me-2"></i>End Class
                    </a></li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteClass()">
                        <i class="fas fa-trash me-2"></i>Delete Class
                    </a></li>
                </ul>
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Class Details Card -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="color: #1a237e;">Class Information</h5>
                        <span class="badge fs-6 px-3 py-2 rounded-pill 
                            @if($onlineClass->status == 'scheduled') bg-primary
                            @elseif($onlineClass->status == 'ongoing') bg-success
                            @elseif($onlineClass->status == 'completed') bg-secondary
                            @else bg-danger @endif">
                            {{ strtoupper($onlineClass->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                    <i class="fas fa-chalkboard text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Academic Class</small>
                                    <span class="fw-semibold">{{ $onlineClass->academicClass->name }}</span>
                                </div>
                            </div>
                        </div>
                        @if($onlineClass->subject)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                    <i class="fas fa-book text-info"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Subject</small>
                                    <span class="fw-semibold">{{ $onlineClass->subject->name }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                    <i class="fas fa-user-tie text-success"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Teacher</small>
                                    <span class="fw-semibold">{{ $onlineClass->teacher->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                    <i class="fas fa-clock text-warning"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Duration</small>
                                    <span class="fw-semibold">{{ $onlineClass->duration }} minutes</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($onlineClass->description)
                    <div class="mt-4">
                        <h6 class="fw-semibold mb-2" style="color: #1a237e;">Description</h6>
                        <p class="text-muted mb-0">{{ $onlineClass->description }}</p>
                    </div>
                    @endif

                    @if($onlineClass->instructions)
                    <div class="mt-4">
                        <h6 class="fw-semibold mb-2" style="color: #1a237e;">Instructions</h6>
                        <p class="text-muted mb-0">{{ $onlineClass->instructions }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Meeting Information -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-video me-2"></i>Meeting Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Meeting Link</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $onlineClass->meeting_link }}" readonly>
                                <button class="btn btn-outline-primary" onclick="copyToClipboard('{{ $onlineClass->meeting_link }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        @if($onlineClass->meeting_id)
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Meeting ID</label>
                            <input type="text" class="form-control" value="{{ $onlineClass->meeting_id }}" readonly>
                        </div>
                        @endif
                        @if($onlineClass->meeting_password)
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="text" class="form-control" value="{{ $onlineClass->meeting_password }}" readonly>
                        </div>
                        @endif
                    </div>

                    @if($onlineClass->status == 'ongoing' && $onlineClass->canBeJoined())
                    <div class="mt-4">
                        <button class="btn btn-success btn-lg w-100" onclick="joinClass()">
                            <i class="fas fa-sign-in-alt me-2"></i>Join Meeting Now
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Materials -->
            @if($onlineClass->materials && count($onlineClass->materials) > 0)
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-file-alt me-2"></i>Class Materials
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($onlineClass->materials as $material)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 border rounded">
                                <i class="fas fa-file-pdf text-danger me-3"></i>
                                <div class="flex-grow-1">
                                    <a href="{{ $material }}" target="_blank" class="text-decoration-none">
                                        <span class="fw-semibold">Material {{ $loop->iteration }}</span>
                                    </a>
                                </div>
                                <a href="{{ $material }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Time Information -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-calendar-alt me-2"></i>Schedule
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                            <i class="fas fa-calendar text-primary"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Date</small>
                            <span class="fw-semibold">{{ $onlineClass->start_time->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                            <i class="fas fa-clock text-success"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Time</small>
                            <span class="fw-semibold">{{ $onlineClass->start_time->format('g:i A') }} - {{ $onlineClass->end_time->format('g:i A') }}</span>
                        </div>
                    </div>
                    @if($onlineClass->started_at)
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                            <i class="fas fa-play text-info"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Started At</small>
                            <span class="fw-semibold">{{ $onlineClass->started_at->format('g:i A') }}</span>
                        </div>
                    </div>
                    @endif
                    @if($onlineClass->ended_at)
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary bg-opacity-10 p-2 rounded me-3">
                            <i class="fas fa-stop text-secondary"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Ended At</small>
                            <span class="fw-semibold">{{ $onlineClass->ended_at->format('g:i A') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Attendance Summary -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-users me-2"></i>Attendance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 p-3 rounded mb-2">
                                <h4 class="mb-0 text-success fw-bold">{{ $onlineClass->participants_count }}</h4>
                                <small class="text-muted">Present</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-primary bg-opacity-10 p-3 rounded mb-2">
                                <h4 class="mb-0 text-primary fw-bold">{{ $onlineClass->attendance_percentage }}%</h4>
                                <small class="text-muted">Attendance</small>
                            </div>
                        </div>
                    </div>
                    @if($onlineClass->max_participants)
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Capacity</small>
                            <small class="text-muted">{{ $onlineClass->participants_count }}/{{ $onlineClass->max_participants }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: {{ ($onlineClass->participants_count / $onlineClass->max_participants) * 100 }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($onlineClass->status == 'ongoing' && $onlineClass->canBeJoined())
                        <button class="btn btn-success" onclick="joinClass()">
                            <i class="fas fa-sign-in-alt me-2"></i>Join Class
                        </button>
                        @endif
                        <button class="btn btn-outline-primary" onclick="copyMeetingLink()">
                            <i class="fas fa-copy me-2"></i>Copy Meeting Link
                        </button>
                        <button class="btn btn-outline-info" onclick="addToCalendar()">
                            <i class="fas fa-calendar-plus me-2"></i>Add to Calendar
                        </button>
                        @if(auth()->user()->hasRole(['teacher', 'admin']))
                        <button class="btn btn-outline-secondary" onclick="viewAttendance()">
                            <i class="fas fa-list me-2"></i>View Attendance
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function joinClass() {
    if (confirm('Join this online class?')) {
        fetch(`/academic/online-classes/{{ $onlineClass->id }}/join`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Open meeting link in new tab
                window.open('{{ $onlineClass->meeting_link }}', '_blank');
                showAlert('success', 'Successfully joined the class!');
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Failed to join class');
        });
    }
}

function startClass() {
    if (confirm('Start this online class?')) {
        fetch(`/academic/online-classes/{{ $onlineClass->id }}/start`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Class started successfully');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Failed to start class');
        });
    }
}

function endClass() {
    if (confirm('End this online class?')) {
        fetch(`/academic/online-classes/{{ $onlineClass->id }}/end`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Class ended successfully');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Failed to end class');
        });
    }
}

function deleteClass() {
    if (confirm('Are you sure you want to delete this online class?')) {
        fetch(`/academic/online-classes/{{ $onlineClass->id }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Class deleted successfully');
                setTimeout(() => window.location.href = '{{ route("academic.online-classes.index") }}', 1000);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Failed to delete class');
        });
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showAlert('success', 'Copied to clipboard!');
    }).catch(() => {
        showAlert('error', 'Failed to copy');
    });
}

function copyMeetingLink() {
    copyToClipboard('{{ $onlineClass->meeting_link }}');
}

function addToCalendar() {
    const startTime = '{{ $onlineClass->start_time->format("Y-m-d\TH:i:s") }}';
    const endTime = '{{ $onlineClass->end_time->format("Y-m-d\TH:i:s") }}';
    const title = encodeURIComponent('{{ $onlineClass->title }}');
    const description = encodeURIComponent('{{ $onlineClass->description }}');
    const location = encodeURIComponent('{{ $onlineClass->meeting_link }}');
    
    const calendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${title}&dates=${startTime}/${endTime}&details=${description}&location=${location}`;
    window.open(calendarUrl, '_blank');
}

function viewAttendance() {
    // Implement attendance view functionality
    alert('Attendance view functionality will be implemented here');
}

function showAlert(type, message) {
    // You can implement your preferred alert system here
    alert(message);
}
</script>
@endpush 

@section('title', $onlineClass->title)

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('academic.online-classes.index') }}" style="color: #1a237e;">Online Classes</a></li>
                    <li class="breadcrumb-item active">{{ $onlineClass->title }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0" style="color: #1a237e; font-weight: 600;">{{ $onlineClass->title }}</h1>
        </div>
        <div class="d-flex gap-2">
            @if($onlineClass->status == 'ongoing' && $onlineClass->canBeJoined())
            <button class="btn btn-success btn-lg" onclick="joinClass()">
                <i class="fas fa-sign-in-alt me-2"></i>Join Class Now
            </button>
            @endif
            @if(auth()->user()->hasRole(['teacher', 'admin']) && $onlineClass->teacher_id == auth()->id())
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-cog me-2"></i>Manage
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('academic.online-classes.edit', $onlineClass->id) }}">
                        <i class="fas fa-edit me-2"></i>Edit Class
                    </a></li>
                    @if($onlineClass->status == 'scheduled')
                    <li><a class="dropdown-item" href="#" onclick="startClass()">
                        <i class="fas fa-play me-2"></i>Start Class
                    </a></li>
                    @endif
                    @if($onlineClass->status == 'ongoing')
                    <li><a class="dropdown-item" href="#" onclick="endClass()">
                        <i class="fas fa-stop me-2"></i>End Class
                    </a></li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteClass()">
                        <i class="fas fa-trash me-2"></i>Delete Class
                    </a></li>
                </ul>
            </div>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Class Details Card -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="color: #1a237e;">Class Information</h5>
                        <span class="badge fs-6 px-3 py-2 rounded-pill 
                            @if($onlineClass->status == 'scheduled') bg-primary
                            @elseif($onlineClass->status == 'ongoing') bg-success
                            @elseif($onlineClass->status == 'completed') bg-secondary
                            @else bg-danger @endif">
                            {{ strtoupper($onlineClass->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                    <i class="fas fa-chalkboard text-primary"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Academic Class</small>
                                    <span class="fw-semibold">{{ $onlineClass->academicClass->name }}</span>
                                </div>
                            </div>
                        </div>
                        @if($onlineClass->subject)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                    <i class="fas fa-book text-info"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Subject</small>
                                    <span class="fw-semibold">{{ $onlineClass->subject->name }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                                    <i class="fas fa-user-tie text-success"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Teacher</small>
                                    <span class="fw-semibold">{{ $onlineClass->teacher->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 p-2 rounded me-3">
                                    <i class="fas fa-clock text-warning"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Duration</small>
                                    <span class="fw-semibold">{{ $onlineClass->duration }} minutes</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($onlineClass->description)
                    <div class="mt-4">
                        <h6 class="fw-semibold mb-2" style="color: #1a237e;">Description</h6>
                        <p class="text-muted mb-0">{{ $onlineClass->description }}</p>
                    </div>
                    @endif

                    @if($onlineClass->instructions)
                    <div class="mt-4">
                        <h6 class="fw-semibold mb-2" style="color: #1a237e;">Instructions</h6>
                        <p class="text-muted mb-0">{{ $onlineClass->instructions }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Meeting Information -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-video me-2"></i>Meeting Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Meeting Link</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ $onlineClass->meeting_link }}" readonly>
                                <button class="btn btn-outline-primary" onclick="copyToClipboard('{{ $onlineClass->meeting_link }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        @if($onlineClass->meeting_id)
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Meeting ID</label>
                            <input type="text" class="form-control" value="{{ $onlineClass->meeting_id }}" readonly>
                        </div>
                        @endif
                        @if($onlineClass->meeting_password)
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="text" class="form-control" value="{{ $onlineClass->meeting_password }}" readonly>
                        </div>
                        @endif
                    </div>

                    @if($onlineClass->status == 'ongoing' && $onlineClass->canBeJoined())
                    <div class="mt-4">
                        <button class="btn btn-success btn-lg w-100" onclick="joinClass()">
                            <i class="fas fa-sign-in-alt me-2"></i>Join Meeting Now
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Materials -->
            @if($onlineClass->materials && count($onlineClass->materials) > 0)
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-file-alt me-2"></i>Class Materials
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($onlineClass->materials as $material)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 border rounded">
                                <i class="fas fa-file-pdf text-danger me-3"></i>
                                <div class="flex-grow-1">
                                    <a href="{{ $material }}" target="_blank" class="text-decoration-none">
                                        <span class="fw-semibold">Material {{ $loop->iteration }}</span>
                                    </a>
                                </div>
                                <a href="{{ $material }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Time Information -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-calendar-alt me-2"></i>Schedule
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                            <i class="fas fa-calendar text-primary"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Date</small>
                            <span class="fw-semibold">{{ $onlineClass->start_time->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 p-2 rounded me-3">
                            <i class="fas fa-clock text-success"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Time</small>
                            <span class="fw-semibold">{{ $onlineClass->start_time->format('g:i A') }} - {{ $onlineClass->end_time->format('g:i A') }}</span>
                        </div>
                    </div>
                    @if($onlineClass->started_at)
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                            <i class="fas fa-play text-info"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Started At</small>
                            <span class="fw-semibold">{{ $onlineClass->started_at->format('g:i A') }}</span>
                        </div>
                    </div>
                    @endif
                    @if($onlineClass->ended_at)
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary bg-opacity-10 p-2 rounded me-3">
                            <i class="fas fa-stop text-secondary"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Ended At</small>
                            <span class="fw-semibold">{{ $onlineClass->ended_at->format('g:i A') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Attendance Summary -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-users me-2"></i>Attendance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="bg-success bg-opacity-10 p-3 rounded mb-2">
                                <h4 class="mb-0 text-success fw-bold">{{ $onlineClass->participants_count }}</h4>
                                <small class="text-muted">Present</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-primary bg-opacity-10 p-3 rounded mb-2">
                                <h4 class="mb-0 text-primary fw-bold">{{ $onlineClass->attendance_percentage }}%</h4>
                                <small class="text-muted">Attendance</small>
                            </div>
                        </div>
                    </div>
                    @if($onlineClass->max_participants)
                    <div class="mt-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Capacity</small>
                            <small class="text-muted">{{ $onlineClass->participants_count }}/{{ $onlineClass->max_participants }}</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: {{ ($onlineClass->participants_count / $onlineClass->max_participants) * 100 }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card border-0 shadow-sm" style="border-radius: 1rem;">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0 fw-bold" style="color: #1a237e;">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($onlineClass->status == 'ongoing' && $onlineClass->canBeJoined())
                        <button class="btn btn-success" onclick="joinClass()">
                            <i class="fas fa-sign-in-alt me-2"></i>Join Class
                        </button>
                        @endif
                        <button class="btn btn-outline-primary" onclick="copyMeetingLink()">
                            <i class="fas fa-copy me-2"></i>Copy Meeting Link
                        </button>
                        <button class="btn btn-outline-info" onclick="addToCalendar()">
                            <i class="fas fa-calendar-plus me-2"></i>Add to Calendar
                        </button>
                        @if(auth()->user()->hasRole(['teacher', 'admin']))
                        <button class="btn btn-outline-secondary" onclick="viewAttendance()">
                            <i class="fas fa-list me-2"></i>View Attendance
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function joinClass() {
    if (confirm('Join this online class?')) {
        fetch(`/academic/online-classes/{{ $onlineClass->id }}/join`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Open meeting link in new tab
                window.open('{{ $onlineClass->meeting_link }}', '_blank');
                showAlert('success', 'Successfully joined the class!');
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Failed to join class');
        });
    }
}

function startClass() {
    if (confirm('Start this online class?')) {
        fetch(`/academic/online-classes/{{ $onlineClass->id }}/start`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Class started successfully');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Failed to start class');
        });
    }
}

function endClass() {
    if (confirm('End this online class?')) {
        fetch(`/academic/online-classes/{{ $onlineClass->id }}/end`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Class ended successfully');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Failed to end class');
        });
    }
}

function deleteClass() {
    if (confirm('Are you sure you want to delete this online class?')) {
        fetch(`/academic/online-classes/{{ $onlineClass->id }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Class deleted successfully');
                setTimeout(() => window.location.href = '{{ route("academic.online-classes.index") }}', 1000);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Failed to delete class');
        });
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showAlert('success', 'Copied to clipboard!');
    }).catch(() => {
        showAlert('error', 'Failed to copy');
    });
}

function copyMeetingLink() {
    copyToClipboard('{{ $onlineClass->meeting_link }}');
}

function addToCalendar() {
    const startTime = '{{ $onlineClass->start_time->format("Y-m-d\TH:i:s") }}';
    const endTime = '{{ $onlineClass->end_time->format("Y-m-d\TH:i:s") }}';
    const title = encodeURIComponent('{{ $onlineClass->title }}');
    const description = encodeURIComponent('{{ $onlineClass->description }}');
    const location = encodeURIComponent('{{ $onlineClass->meeting_link }}');
    
    const calendarUrl = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${title}&dates=${startTime}/${endTime}&details=${description}&location=${location}`;
    window.open(calendarUrl, '_blank');
}

function viewAttendance() {
    // Implement attendance view functionality
    alert('Attendance view functionality will be implemented here');
}

function showAlert(type, message) {
    // You can implement your preferred alert system here
    alert(message);
}
</script>
@endpush 