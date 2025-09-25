@extends('layouts.app')

@section('title', 'Online Classes')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0" style="color: #1a237e; font-weight: 600;">
                <i class="fas fa-video me-2"></i>Online Classes
            </h1>
            <p class="text-muted mb-0">Manage and join virtual classroom sessions</p>
        </div>
        <div class="d-flex gap-2">
            @if(auth()->user()->hasRole(['teacher', 'admin']))
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClassModal">
                <i class="fas fa-plus me-2"></i>Schedule Class
            </button>
            @endif
            <button class="btn btn-outline-secondary" onclick="refreshPage()">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Upcoming Classes</h6>
                            <h3 class="mb-0 fw-bold">{{ $onlineClasses->where('status', 'scheduled')->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Live Classes</h6>
                            <h3 class="mb-0 fw-bold">{{ $onlineClasses->where('status', 'ongoing')->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-broadcast-tower fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Completed</h6>
                            <h3 class="mb-0 fw-bold">{{ $onlineClasses->where('status', 'completed')->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Total Classes</h6>
                            <h3 class="mb-0 fw-bold">{{ $onlineClasses->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-chalkboard-teacher fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
        <div class="card-body">
            <form method="GET" action="{{ route('academic.online-classes.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="color: #1a237e;">Search</label>
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}" placeholder="Search classes, subjects...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="color: #1a237e;">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Live</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="color: #1a237e;">Date</label>
                        <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search me-1"></i>Filter
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Classes Grid -->
    @if($onlineClasses->count() > 0)
    <div class="row">
        @foreach($onlineClasses as $class)
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1rem; transition: transform 0.2s;">
                <div class="card-body p-4">
                    <!-- Status Badge -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge fs-6 px-3 py-2 rounded-pill 
                            @if($class->status == 'scheduled') bg-primary
                            @elseif($class->status == 'ongoing') bg-success
                            @elseif($class->status == 'completed') bg-secondary
                            @else bg-danger @endif">
                            {{ strtoupper($class->status) }}
                        </span>
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('academic.online-classes.show', $class->id) }}">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a></li>
                                @if(auth()->user()->hasRole(['teacher', 'admin']) && $class->teacher_id == auth()->id())
                                <li><a class="dropdown-item" href="{{ route('academic.online-classes.edit', $class->id) }}">
                                    <i class="fas fa-edit me-2"></i>Edit
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteClass({{ $class->id }})">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </a></li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Class Title -->
                    <h5 class="card-title mb-2" style="color: #1a237e; font-weight: 600;">{{ $class->title }}</h5>
                    
                    <!-- Class Info -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-chalkboard me-2 text-muted"></i>
                            <span class="text-muted">{{ $class->academicClass->name }}</span>
                        </div>
                        @if($class->subject)
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-book me-2 text-muted"></i>
                            <span class="text-muted">{{ $class->subject->name }}</span>
                        </div>
                        @endif
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-user-tie me-2 text-muted"></i>
                            <span class="text-muted">{{ $class->teacher->name }}</span>
                        </div>
                    </div>

                    <!-- Time Info -->
                    <div class="mb-3 p-3" style="background: #f8f9fa; border-radius: 0.5rem;">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-clock me-2 text-primary"></i>
                            <span class="fw-semibold">{{ $class->start_time->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                            <span>{{ $class->start_time->format('g:i A') }} - {{ $class->end_time->format('g:i A') }}</span>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($class->description)
                    <p class="text-muted mb-3" style="font-size: 0.9rem;">{{ Str::limit($class->description, 100) }}</p>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 mt-auto">
                        @if($class->status == 'ongoing' && $class->canBeJoined())
                        <button class="btn btn-success flex-fill" onclick="joinClass({{ $class->id }})">
                            <i class="fas fa-sign-in-alt me-2"></i>Join Now
                        </button>
                        @elseif($class->status == 'scheduled')
                        <button class="btn btn-outline-primary flex-fill" onclick="viewClass({{ $class->id }})">
                            <i class="fas fa-eye me-2"></i>View Details
                        </button>
                        @elseif($class->status == 'completed')
                        <button class="btn btn-outline-secondary flex-fill" onclick="viewClass({{ $class->id }})">
                            <i class="fas fa-play me-2"></i>View Recording
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $onlineClasses->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-video fa-4x text-muted"></i>
        </div>
        <h4 class="text-muted mb-2">No Online Classes Found</h4>
        <p class="text-muted mb-4">There are no online classes scheduled at the moment.</p>
        @if(auth()->user()->hasRole(['teacher', 'admin']))
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClassModal">
            <i class="fas fa-plus me-2"></i>Schedule Your First Class
        </button>
        @endif
    </div>
    @endif
</div>

<!-- Create Class Modal -->
@if(auth()->user()->hasRole(['teacher', 'admin']))
<div class="modal fade" id="createClassModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 1rem;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" style="color: #1a237e;">
                    <i class="fas fa-plus me-2"></i>Schedule Online Class
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createClassForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Class Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Academic Class <span class="text-danger">*</span></label>
                            <select class="form-select" name="academic_class_id" required>
                                <option value="">Select Class</option>
                                @foreach($academicClasses ?? [] as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Subject</label>
                            <select class="form-select" name="subject_id">
                                <option value="">Select Subject</option>
                                @foreach($subjects ?? [] as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Start Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" name="start_time" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">End Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" name="end_time" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Meeting Link <span class="text-danger">*</span></label>
                            <input type="url" class="form-control" name="meeting_link" 
                                   placeholder="https://zoom.us/j/..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Meeting ID</label>
                            <input type="text" class="form-control" name="meeting_id" 
                                   placeholder="123 456 7890">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Meeting Password</label>
                            <input type="text" class="form-control" name="meeting_password" 
                                   placeholder="Optional">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Max Participants</label>
                            <input type="number" class="form-control" name="max_participants" 
                                   min="1" placeholder="30">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_recording_allowed" id="recordingAllowed">
                                <label class="form-check-label" for="recordingAllowed">
                                    Allow Recording
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Instructions</label>
                            <textarea class="form-control" name="instructions" rows="3" 
                                      placeholder="Any special instructions for students..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createClass()">
                    <i class="fas fa-save me-2"></i>Schedule Class
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
function refreshPage() {
    window.location.reload();
}

function clearFilters() {
    window.location.href = '{{ route("academic.online-classes.index") }}';
}

function joinClass(classId) {
    if (confirm('Join this online class?')) {
        fetch(`/academic/online-classes/${classId}/join`, {
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
                window.open(data.meeting_link, '_blank');
                // Show success message
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

function viewClass(classId) {
    window.location.href = `/academic/online-classes/${classId}`;
}

function deleteClass(classId) {
    if (confirm('Are you sure you want to delete this online class?')) {
        fetch(`/academic/online-classes/${classId}`, {
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
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Failed to delete class');
        });
    }
}

function createClass() {
    const form = document.getElementById('createClassForm');
    const formData = new FormData(form);

    fetch('/academic/online-classes', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Online class scheduled successfully');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Failed to schedule class');
    });
}

function showAlert(type, message) {
    // You can implement your preferred alert system here
    alert(message);
}
</script>
@endpush 

@section('title', 'Online Classes')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0" style="color: #1a237e; font-weight: 600;">
                <i class="fas fa-video me-2"></i>Online Classes
            </h1>
            <p class="text-muted mb-0">Manage and join virtual classroom sessions</p>
        </div>
        <div class="d-flex gap-2">
            @if(auth()->user()->hasRole(['teacher', 'admin']))
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClassModal">
                <i class="fas fa-plus me-2"></i>Schedule Class
            </button>
            @endif
            <button class="btn btn-outline-secondary" onclick="refreshPage()">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Upcoming Classes</h6>
                            <h3 class="mb-0 fw-bold">{{ $onlineClasses->where('status', 'scheduled')->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Live Classes</h6>
                            <h3 class="mb-0 fw-bold">{{ $onlineClasses->where('status', 'ongoing')->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-broadcast-tower fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Completed</h6>
                            <h3 class="mb-0 fw-bold">{{ $onlineClasses->where('status', 'completed')->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <div class="card-body text-white">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 opacity-75">Total Classes</h6>
                            <h3 class="mb-0 fw-bold">{{ $onlineClasses->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <i class="fas fa-chalkboard-teacher fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem;">
        <div class="card-body">
            <form method="GET" action="{{ route('academic.online-classes.index') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" style="color: #1a237e;">Search</label>
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}" placeholder="Search classes, subjects...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="color: #1a237e;">Status</label>
                        <select class="form-select" name="status">
                            <option value="">All Status</option>
                            <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Live</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" style="color: #1a237e;">Date</label>
                        <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fas fa-search me-1"></i>Filter
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Classes Grid -->
    @if($onlineClasses->count() > 0)
    <div class="row">
        @foreach($onlineClasses as $class)
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1rem; transition: transform 0.2s;">
                <div class="card-body p-4">
                    <!-- Status Badge -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge fs-6 px-3 py-2 rounded-pill 
                            @if($class->status == 'scheduled') bg-primary
                            @elseif($class->status == 'ongoing') bg-success
                            @elseif($class->status == 'completed') bg-secondary
                            @else bg-danger @endif">
                            {{ strtoupper($class->status) }}
                        </span>
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('academic.online-classes.show', $class->id) }}">
                                    <i class="fas fa-eye me-2"></i>View Details
                                </a></li>
                                @if(auth()->user()->hasRole(['teacher', 'admin']) && $class->teacher_id == auth()->id())
                                <li><a class="dropdown-item" href="{{ route('academic.online-classes.edit', $class->id) }}">
                                    <i class="fas fa-edit me-2"></i>Edit
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="deleteClass({{ $class->id }})">
                                    <i class="fas fa-trash me-2"></i>Delete
                                </a></li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Class Title -->
                    <h5 class="card-title mb-2" style="color: #1a237e; font-weight: 600;">{{ $class->title }}</h5>
                    
                    <!-- Class Info -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-chalkboard me-2 text-muted"></i>
                            <span class="text-muted">{{ $class->academicClass->name }}</span>
                        </div>
                        @if($class->subject)
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-book me-2 text-muted"></i>
                            <span class="text-muted">{{ $class->subject->name }}</span>
                        </div>
                        @endif
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-user-tie me-2 text-muted"></i>
                            <span class="text-muted">{{ $class->teacher->name }}</span>
                        </div>
                    </div>

                    <!-- Time Info -->
                    <div class="mb-3 p-3" style="background: #f8f9fa; border-radius: 0.5rem;">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-clock me-2 text-primary"></i>
                            <span class="fw-semibold">{{ $class->start_time->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                            <span>{{ $class->start_time->format('g:i A') }} - {{ $class->end_time->format('g:i A') }}</span>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($class->description)
                    <p class="text-muted mb-3" style="font-size: 0.9rem;">{{ Str::limit($class->description, 100) }}</p>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 mt-auto">
                        @if($class->status == 'ongoing' && $class->canBeJoined())
                        <button class="btn btn-success flex-fill" onclick="joinClass({{ $class->id }})">
                            <i class="fas fa-sign-in-alt me-2"></i>Join Now
                        </button>
                        @elseif($class->status == 'scheduled')
                        <button class="btn btn-outline-primary flex-fill" onclick="viewClass({{ $class->id }})">
                            <i class="fas fa-eye me-2"></i>View Details
                        </button>
                        @elseif($class->status == 'completed')
                        <button class="btn btn-outline-secondary flex-fill" onclick="viewClass({{ $class->id }})">
                            <i class="fas fa-play me-2"></i>View Recording
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $onlineClasses->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-video fa-4x text-muted"></i>
        </div>
        <h4 class="text-muted mb-2">No Online Classes Found</h4>
        <p class="text-muted mb-4">There are no online classes scheduled at the moment.</p>
        @if(auth()->user()->hasRole(['teacher', 'admin']))
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClassModal">
            <i class="fas fa-plus me-2"></i>Schedule Your First Class
        </button>
        @endif
    </div>
    @endif
</div>

<!-- Create Class Modal -->
@if(auth()->user()->hasRole(['teacher', 'admin']))
<div class="modal fade" id="createClassModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow" style="border-radius: 1rem;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" style="color: #1a237e;">
                    <i class="fas fa-plus me-2"></i>Schedule Online Class
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createClassForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Class Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Academic Class <span class="text-danger">*</span></label>
                            <select class="form-select" name="academic_class_id" required>
                                <option value="">Select Class</option>
                                @foreach($academicClasses ?? [] as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Subject</label>
                            <select class="form-select" name="subject_id">
                                <option value="">Select Subject</option>
                                @foreach($subjects ?? [] as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Start Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" name="start_time" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">End Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" name="end_time" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Meeting Link <span class="text-danger">*</span></label>
                            <input type="url" class="form-control" name="meeting_link" 
                                   placeholder="https://zoom.us/j/..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Meeting ID</label>
                            <input type="text" class="form-control" name="meeting_id" 
                                   placeholder="123 456 7890">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Meeting Password</label>
                            <input type="text" class="form-control" name="meeting_password" 
                                   placeholder="Optional">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Max Participants</label>
                            <input type="number" class="form-control" name="max_participants" 
                                   min="1" placeholder="30">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_recording_allowed" id="recordingAllowed">
                                <label class="form-check-label" for="recordingAllowed">
                                    Allow Recording
                                </label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Instructions</label>
                            <textarea class="form-control" name="instructions" rows="3" 
                                      placeholder="Any special instructions for students..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="createClass()">
                    <i class="fas fa-save me-2"></i>Schedule Class
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
function refreshPage() {
    window.location.reload();
}

function clearFilters() {
    window.location.href = '{{ route("academic.online-classes.index") }}';
}

function joinClass(classId) {
    if (confirm('Join this online class?')) {
        fetch(`/academic/online-classes/${classId}/join`, {
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
                window.open(data.meeting_link, '_blank');
                // Show success message
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

function viewClass(classId) {
    window.location.href = `/academic/online-classes/${classId}`;
}

function deleteClass(classId) {
    if (confirm('Are you sure you want to delete this online class?')) {
        fetch(`/academic/online-classes/${classId}`, {
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
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Failed to delete class');
        });
    }
}

function createClass() {
    const form = document.getElementById('createClassForm');
    const formData = new FormData(form);

    fetch('/academic/online-classes', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Online class scheduled successfully');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        showAlert('error', 'Failed to schedule class');
    });
}

function showAlert(type, message) {
    // You can implement your preferred alert system here
    alert(message);
}
</script>
@endpush 