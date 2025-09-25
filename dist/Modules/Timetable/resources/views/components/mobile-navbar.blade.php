<nav class="navbar navbar-light bg-light border-top fixed-bottom d-md-none" style="z-index:1030;">
    <div class="container-fluid justify-content-around">
        <a href="{{ route('timetable.calendar') }}" class="nav-link text-center">
            <i class="bi bi-calendar3" style="font-size:1.5rem;"></i><br>
            <small>Calendar</small>
        </a>
        <a href="{{ route('class_schedules.index') }}" class="nav-link text-center">
            <i class="bi bi-list-task" style="font-size:1.5rem;"></i><br>
            <small>Schedules</small>
        </a>
        <a href="{{ route('notification.index', [], false) }}" class="nav-link text-center">
            <i class="bi bi-bell" style="font-size:1.5rem;"></i><br>
            <small>Notifs</small>
        </a>
        <a href="{{ route('profile') }}" class="nav-link text-center">
            <i class="bi bi-person-circle" style="font-size:1.5rem;"></i><br>
            <small>Profile</small>
        </a>
    </div>
</nav>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .navbar.fixed-bottom {
        box-shadow: 0 -2px 8px rgba(0,0,0,0.05);
    }
    .navbar .nav-link.active, .navbar .nav-link:focus {
        color: #0d6efd;
    }
</style>
@endpush 