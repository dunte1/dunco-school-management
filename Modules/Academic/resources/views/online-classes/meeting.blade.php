@extends('layouts.app')

@section('title', 'Join Online Class')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Meeting Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-1" style="color: #1a237e;">
                        <i class="fas fa-video me-2"></i>{{ $onlineClass->title }}
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-user me-1"></i>Teacher: {{ $onlineClass->teacher->name }} | 
                        <i class="fas fa-clock me-1"></i>{{ $onlineClass->start_time->format('M d, Y \a\t g:i A') }}
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex gap-2 justify-content-end">
                        <button class="btn btn-outline-secondary" onclick="toggleFullscreen()">
                            <i class="fas fa-expand me-2"></i>Fullscreen
                        </button>
                        <a href="{{ route('academic.online-classes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Meeting Area -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-video me-2"></i>Meeting Room
                    </h5>
                </div>
                <div class="card-body p-0">
                    <!-- Meeting Status -->
                    <div class="meeting-status p-3" id="meetingStatus">
                        @if($onlineClass->status === 'scheduled')
                            <div class="alert alert-info text-center">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <h5>Class Not Started Yet</h5>
                                <p class="mb-0">This class will start at {{ $onlineClass->start_time->format('M d, Y \a\t g:i A') }}</p>
                                <div class="mt-3">
                                    <div class="countdown-timer" id="countdownTimer">
                                        <span id="countdown"></span>
                                    </div>
                                </div>
                            </div>
                        @elseif($onlineClass->status === 'ongoing')
                            <div class="alert alert-success text-center">
                                <i class="fas fa-play-circle fa-2x mb-2"></i>
                                <h5>Class is Live!</h5>
                                <p class="mb-0">The class is currently in progress</p>
                            </div>
                        @elseif($onlineClass->status === 'completed')
                            <div class="alert alert-warning text-center">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <h5>Class Completed</h5>
                                <p class="mb-0">This class has ended</p>
                            </div>
                        @endif
                    </div>

                    <!-- Meeting Link/Embed -->
                    <div class="meeting-container p-3">
                        @if($onlineClass->platform === 'zoom')
                            <div class="zoom-meeting">
                                <div class="meeting-info p-3 bg-light rounded mb-3">
                                    <h6><i class="fas fa-info-circle me-2"></i>Meeting Information</h6>
                                    <p class="mb-1"><strong>Meeting ID:</strong> {{ $onlineClass->meeting_id ?? 'N/A' }}</p>
                                    @if($onlineClass->meeting_password)
                                        <p class="mb-1"><strong>Password:</strong> {{ $onlineClass->meeting_password }}</p>
                                    @endif
                                    <p class="mb-0"><strong>Duration:</strong> {{ $onlineClass->start_time->diffInMinutes($onlineClass->end_time) }} minutes</p>
                                </div>
                                
                                <div class="text-center">
                                    <a href="{{ $onlineClass->meeting_link }}" 
                                       class="btn btn-primary btn-lg" 
                                       target="_blank"
                                       id="joinMeetingBtn">
                                        <i class="fas fa-video me-2"></i>Join Zoom Meeting
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="generic-meeting">
                                <div class="meeting-info p-3 bg-light rounded mb-3">
                                    <h6><i class="fas fa-link me-2"></i>Meeting Link</h6>
                                    <p class="mb-0">{{ $onlineClass->meeting_link }}</p>
                                </div>
                                
                                <div class="text-center">
                                    <a href="{{ $onlineClass->meeting_link }}" 
                                       class="btn btn-primary btn-lg" 
                                       target="_blank">
                                        <i class="fas fa-external-link-alt me-2"></i>Join Meeting
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Class Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Class Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Subject:</strong><br>
                        {{ $onlineClass->subject->name ?? 'General' }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Academic Class:</strong><br>
                        {{ $onlineClass->academicClass->name }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Start Time:</strong><br>
                        {{ $onlineClass->start_time->format('M d, Y \a\t g:i A') }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>End Time:</strong><br>
                        {{ $onlineClass->end_time->format('M d, Y \a\t g:i A') }}
                    </div>
                    
                    @if($onlineClass->max_participants)
                    <div class="mb-3">
                        <strong>Max Participants:</strong><br>
                        {{ $onlineClass->max_participants }}
                    </div>
                    @endif
                    
                    @if($onlineClass->is_recording_allowed)
                    <div class="mb-3">
                        <span class="badge bg-info">
                            <i class="fas fa-record-vinyl me-1"></i>Recording Allowed
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Instructions -->
            @if($onlineClass->instructions)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-list me-2"></i>Instructions
                    </h6>
                </div>
                <div class="card-body">
                    <p>{{ $onlineClass->instructions }}</p>
                </div>
            </div>
            @endif

            <!-- Materials -->
            @if($onlineClass->materials && count($onlineClass->materials) > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-paperclip me-2"></i>Materials
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($onlineClass->materials as $material)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-file me-2"></i>
                            <a href="{{ $material['url'] ?? '#' }}" target="_blank">
                                {{ $material['name'] ?? 'Material' }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Attendance Status -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-user-check me-2"></i>Your Attendance
                    </h6>
                </div>
                <div class="card-body">
                    <div class="attendance-status" id="attendanceStatus">
                        <div class="text-center">
                            <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Attendance will be marked when you join</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Meeting Controls Modal -->
<div class="modal fade" id="meetingControlsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Meeting Controls</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-6">
                        <button class="btn btn-outline-primary w-100" onclick="markAttendance()">
                            <i class="fas fa-check me-2"></i>Mark Attendance
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-outline-info w-100" onclick="openMeetingInNewTab()">
                            <i class="fas fa-external-link-alt me-2"></i>Open in New Tab
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.meeting-container {
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.countdown-timer {
    font-size: 1.5rem;
    font-weight: bold;
    color: #007bff;
}

.meeting-status .alert {
    border: none;
    border-radius: 10px;
}

.meeting-info {
    border-left: 4px solid #007bff;
}

.attendance-status {
    min-height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endpush

@push('scripts')
<script>
// Countdown timer
function updateCountdown() {
    const startTime = new Date('{{ $onlineClass->start_time->toISOString() }}').getTime();
    const now = new Date().getTime();
    const distance = startTime - now;

    if (distance < 0) {
        document.getElementById('countdown').innerHTML = "Class has started!";
        document.getElementById('joinMeetingBtn').style.display = 'inline-block';
        return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    let countdownText = "";
    if (days > 0) countdownText += days + "d ";
    if (hours > 0) countdownText += hours + "h ";
    if (minutes > 0) countdownText += minutes + "m ";
    countdownText += seconds + "s";

    document.getElementById('countdown').innerHTML = countdownText;
}

// Update countdown every second
setInterval(updateCountdown, 1000);
updateCountdown();

// Toggle fullscreen
function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
    } else {
        document.exitFullscreen();
    }
}

// Mark attendance
function markAttendance() {
    fetch('{{ route("academic.online-classes.join", $onlineClass) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('attendanceStatus').innerHTML = `
                <div class="text-center">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <p class="text-success mb-0">Attendance Marked!</p>
                    <small class="text-muted">You joined at ${new Date().toLocaleTimeString()}</small>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Open meeting in new tab
function openMeetingInNewTab() {
    window.open('{{ $onlineClass->meeting_link }}', '_blank');
}

// Auto-refresh page every 30 seconds to check for status updates
setInterval(function() {
    // Only refresh if class is scheduled and not yet started
    @if($onlineClass->status === 'scheduled')
    location.reload();
    @endif
}, 30000);
</script>
@endpush
