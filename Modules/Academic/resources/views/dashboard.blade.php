@extends('layouts.app')

@section('content')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Poppins:wght@700&display=swap" rel="stylesheet">
    <div class="container py-4">
        <div class="d-flex align-items-center mb-4">
            <span style="font-size:2rem;color:#1a237e;margin-right:0.7rem;"><i class="fas fa-graduation-cap"></i></span>
            <h2 class="fw-bold mb-0" style="font-family:'Poppins',sans-serif;">Academic Dashboard</h2>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3" style="font-size:2rem;color:#1a73e8;"><i class="fas fa-chalkboard-teacher"></i></div>
                        <div>
                            <div class="text-uppercase small text-primary fw-bold">Total Classes</div>
                            <div class="fw-bold fs-4">{{ $totalClasses ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3" style="font-size:2rem;color:#43a047;"><i class="fas fa-book"></i></div>
                        <div>
                            <div class="text-uppercase small text-success fw-bold">Total Subjects</div>
                            <div class="fw-bold fs-4">{{ $totalSubjects ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3" style="font-size:2rem;color:#1a237e;"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <div class="text-uppercase small text-info fw-bold">Active Classes</div>
                            <div class="fw-bold fs-4">{{ $activeClasses ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3" style="font-size:2rem;color:#fbc02d;"><i class="fas fa-users"></i></div>
                        <div>
                            <div class="text-uppercase small text-warning fw-bold">Total Students</div>
                            <div class="fw-bold fs-4">{{ $totalStudents ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="fw-bold mb-3" style="color:#1a237e;">Quick Actions</div>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('academic.classes.index') }}" class="btn btn-outline-primary"><i class="fas fa-chalkboard-teacher me-2"></i>Manage Classes</a>
                    <a href="{{ route('academic.subjects.index') }}" class="btn btn-outline-success"><i class="fas fa-book me-2"></i>Manage Subjects</a>
                    <a href="{{ route('academic.students.create') }}" class="btn btn-outline-info"><i class="fas fa-user-plus me-2"></i>Student Enrollment</a>
                    <a href="{{ route('academic.reports.index') }}" class="btn btn-outline-warning"><i class="fas fa-chart-bar me-2"></i>Academic Reports</a>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="fw-bold mb-3" style="color:#1a237e;">Timetable Management</div>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('class_schedules.index') }}" class="btn btn-outline-primary"><i class="fas fa-calendar-alt me-2"></i>Class Schedules</a>
                    <a href="{{ route('rooms.index') }}" class="btn btn-outline-success"><i class="fas fa-door-open me-2"></i>Rooms</a>
                    <a href="{{ route('teacher_availabilities.index') }}" class="btn btn-outline-info"><i class="fas fa-user-clock me-2"></i>Teacher Availabilities</a>
                </div>
            </div>
        </div>
        <div class="card mt-4">
    <div class="card-header fw-bold">Timetable Exports</div>
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ url('/api/v1/class-schedules/export/csv') }}" class="btn btn-outline-primary" target="_blank">
                <i class="fas fa-file-csv"></i> Class Schedules (CSV)
            </a>
            <a href="{{ url('/api/v1/class-schedules/export/pdf') }}" class="btn btn-outline-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Class Schedules (PDF)
            </a>
            <a href="{{ url('/api/v1/teacher-availabilities/export/csv') }}" class="btn btn-outline-primary" target="_blank">
                <i class="fas fa-file-csv"></i> Teacher Availabilities (CSV)
            </a>
            <a href="{{ url('/api/v1/teacher-availabilities/export/pdf') }}" class="btn btn-outline-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Teacher Availabilities (PDF)
            </a>
            <a href="{{ url('/api/v1/rooms/export/csv') }}" class="btn btn-outline-primary" target="_blank">
                <i class="fas fa-file-csv"></i> Rooms (CSV)
            </a>
            <a href="{{ url('/api/v1/rooms/export/pdf') }}" class="btn btn-outline-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Rooms (PDF)
            </a>
            <a href="{{ url('/api/v1/room-allocations/export/csv') }}" class="btn btn-outline-primary" target="_blank">
                <i class="fas fa-file-csv"></i> Room Allocations (CSV)
            </a>
            <a href="{{ url('/api/v1/room-allocations/export/pdf') }}" class="btn btn-outline-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Room Allocations (PDF)
            </a>
        </div>
    </div>
</div>
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="fw-bold mb-2" style="color:#1a237e;">Recent Classes <a href="{{ route('academic.classes.index') }}" class="btn btn-link btn-sm float-end">View All</a></div>
                        @if(isset($recentClasses) && count($recentClasses))
                            <ul class="list-group list-group-flush">
                                @foreach($recentClasses as $class)
                                    <li class="list-group-item">{{ $class->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-muted">No classes found.</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="fw-bold mb-2" style="color:#1a237e;">Recent Subjects <a href="{{ route('academic.subjects.index') }}" class="btn btn-link btn-sm float-end">View All</a></div>
                        @if(isset($recentSubjects) && count($recentSubjects))
                            <ul class="list-group list-group-flush">
                                @foreach($recentSubjects as $subject)
                                    <li class="list-group-item">{{ $subject->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-muted">No subjects found.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .dashboard-bg-gradient {
            background: linear-gradient(135deg, #101c36 0%, #1a237e 60%, #1ea7ff 100%);
        }
        .glassmorphism {
            background: rgba(24,34,56,0.92) !important;
            box-shadow: 0 8px 32px 0 rgba(30,167,255,0.18), 0 1.5px 6px 0 rgba(21,101,192,0.13);
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(30,167,255,0.18);
        }
        .dashboard-title {
            font-family: 'Poppins', 'Inter', sans-serif;
            font-size: 2.8rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: #eaf6fb;
            text-shadow: 0 2px 12px rgba(30,167,255,0.18);
        }
        .dashboard-section-title {
            font-family: 'Poppins', 'Inter', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 1.2px;
            color: #1ea7ff;
            text-shadow: 0 1px 8px rgba(30,167,255,0.13);
        }
        .stat-card, .module-card {
            font-family: 'Inter', 'Poppins', sans-serif;
            transition: box-shadow 0.22s, transform 0.22s, background 0.22s, border 0.22s;
            cursor: pointer;
        }
        .card-title {
            font-size: 1.25rem;
            font-family: 'Poppins',sans-serif;
            font-weight: 700;
            letter-spacing: 1px;
            color: #eaf6fb;
            text-shadow: 0 1px 8px rgba(30,167,255,0.13);
        }
        .card-text, .fw-semibold {
            color: #b0bec5;
            font-size: 1.05rem;
            letter-spacing: 0.5px;
        }
        .stat-card .fs-1, .stat-card .fw-bold {
            color: #fff;
            text-shadow: 0 1px 8px rgba(30,167,255,0.13);
        }
        .stat-card {
            border-radius: 1.5rem;
            min-height: 180px;
            min-width: 0;
            padding: 1.5rem 1.25rem 1.25rem 1.25rem;
            margin-bottom: 0.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .stat-card:hover {
            box-shadow: 0 16px 48px rgba(30,167,255,0.28), 0 2px 8px rgba(30,167,255,0.10);
            transform: translateY(-8px) scale(1.045);
            background: rgba(30,167,255,0.10);
            border: 1.5px solid #1ea7ff;
            z-index: 3;
        }
        .module-card {
            border-radius: 2rem;
            min-height: 240px;
            min-width: 0;
            padding: 2.5rem 1.5rem 1.5rem 1.5rem;
            margin-bottom: 0.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .module-card:hover {
            box-shadow: 0 16px 48px rgba(30,167,255,0.28), 0 2px 8px rgba(30,167,255,0.10);
            transform: translateY(-8px) scale(1.045);
            background: rgba(30,167,255,0.10);
            border: 1.5px solid #1ea7ff;
            z-index: 3;
        }
        .mini-progress svg {
            display: block;
            margin: 0 auto;
        }
        .row.g-4 {
            row-gap: 2.5rem !important;
            column-gap: 2rem !important;
        }
        @media (max-width: 991.98px) {
            .dashboard-title { font-size: 2.1rem; }
            .dashboard-section-title { font-size: 1.15rem; }
            .stat-card, .module-card {
                min-height: 120px;
                padding: 1.2rem 0.7rem 1.2rem 0.7rem;
            }
            .row.g-4 {
                row-gap: 1.5rem !important;
                column-gap: 1rem !important;
            }
        }
        @media (max-width: 767.98px) {
            .dashboard-title { font-size: 1.5rem; }
            .dashboard-section-title { font-size: 1rem; }
            .card-title { font-size: 1rem; }
            .card-text { font-size: 0.95rem; }
            .stat-card, .module-card {
                min-height: 100px;
                padding: 1rem 0.5rem 1rem 0.5rem;
            }
            .row.g-4 {
                row-gap: 1rem !important;
                column-gap: 0.5rem !important;
            }
            .module-card .card-body {
                padding: 2rem 0.5rem !important;
            }
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding-left: 2.5rem;
            padding-right: 2.5rem;
        }
        @media (max-width: 991.98px) {
            .container {
                padding-left: 1.2rem;
                padding-right: 1.2rem;
            }
        }
        @media (max-width: 767.98px) {
            .container {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            .dashboard-bg-gradient {
                padding-top: 0.5rem !important;
                padding-bottom: 0.5rem !important;
            }
            .mb-4, .mt-5, .mb-5 {
                margin-bottom: 1.2rem !important;
                margin-top: 1.2rem !important;
            }
        }
        .btn, .btn-lg, .btn-outline-primary, .btn-outline-success, .btn-outline-info, .btn-outline-warning, .btn-outline-secondary, .btn-primary, .btn-info, .btn-warning {
            transition: box-shadow 0.18s, background 0.18s, color 0.18s, transform 0.18s;
            position: relative;
            overflow: hidden;
        }
        .btn:active::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            width: 120%;
            height: 120%;
            background: rgba(30,167,255,0.18);
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0.7);
            animation: ripple 0.4s linear;
            z-index: 1;
        }
        @keyframes ripple {
            0% { opacity: 0.5; transform: translate(-50%, -50%) scale(0.7); }
            100% { opacity: 0; transform: translate(-50%, -50%) scale(1.5); }
        }
        .btn:hover, .btn:focus {
            box-shadow: 0 4px 16px rgba(30,167,255,0.18);
            transform: translateY(-2px) scale(1.03);
            z-index: 2;
        }
        /* Micro-interactions */
        .stat-card:active i, .module-card:active i {
            animation: icon-bounce 0.35s cubic-bezier(.68,-0.55,.27,1.55);
        }
        @keyframes icon-bounce {
            0% { transform: scale(1); }
            30% { transform: scale(1.25) translateY(-8px); }
            60% { transform: scale(0.95) translateY(2px); }
            100% { transform: scale(1); }
        }
        /* Skeleton Loader */
        .skeleton {
            background: linear-gradient(90deg, #1a237e 25%, #283593 50%, #1a237e 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.2s infinite linear;
            border-radius: 8px;
            min-height: 24px;
        }
        @keyframes skeleton-loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        .text-muted, .activity-feed-time, #dashboard-time {
            color: #eaf6fb !important;
            opacity: 0.85;
            text-shadow: 0 1px 8px rgba(30,167,255,0.13);
        }
        .activity-feed-label, .activity-feed-entity {
            color: #fff !important;
            font-weight: 600;
            text-shadow: 0 1px 8px rgba(30,167,255,0.13);
        }
        .activity-feed-action {
            color: #b0bec5 !important;
            font-weight: 400;
        }
        .activity-feed {
            font-size: 1.08rem;
        }
        /* Data Viz Progress Ring */
        .progress-ring {
            transform: rotate(-90deg);
        }
        .progress-ring__circle {
            transition: stroke-dashoffset 0.7s cubic-bezier(.4,0,.2,1);
        }
        /* Floating Quick Actions */
        .fab-container {
            position: fixed;
            bottom: 2.2rem;
            right: 2.2rem;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
            align-items: flex-end;
        }
        .fab-btn {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #1ea7ff;
            color: #fff;
            box-shadow: 0 4px 24px rgba(30,167,255,0.22);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.7rem;
            border: none;
            outline: none;
            cursor: pointer;
            transition: background 0.18s, box-shadow 0.18s, transform 0.18s;
            position: relative;
        }
        .fab-btn.fab-school { background: #00e5ff; color: #0d133d; }
        .fab-btn.fab-role { background: #ffb300; color: #0d133d; }
        .fab-btn:hover, .fab-btn:focus {
            background: #1565c0;
            box-shadow: 0 8px 32px rgba(30,167,255,0.28);
            transform: scale(1.08);
        }
        .fab-btn.fab-school:hover { background: #00bcd4; }
        .fab-btn.fab-role:hover { background: #ff9800; }
        .fab-tooltip {
            position: absolute;
            right: 110%;
            top: 50%;
            transform: translateY(-50%);
            background: #222b45;
            color: #fff;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.98rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.18s;
            box-shadow: 0 2px 8px rgba(30,167,255,0.13);
        }
        .fab-btn:hover .fab-tooltip, .fab-btn:focus .fab-tooltip {
            opacity: 1;
        }
        @media (max-width: 767.98px) {
            .fab-container {
                bottom: 1.1rem;
                right: 1.1rem;
                gap: 0.7rem;
            }
            .fab-btn {
                width: 44px;
                height: 44px;
                font-size: 1.2rem;
            }
            .fab-tooltip {
                font-size: 0.85rem;
                padding: 4px 10px;
            }
        }
        /* Activity Feed Enhancements */
        .activity-feed-header {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            font-size: 1.18rem;
            font-weight: 700;
            color: #1ea7ff;
            letter-spacing: 1px;
            margin-bottom: 0.7rem;
        }
        .activity-feed-header .fa-bell {
            color: #ffb300;
            font-size: 1.3rem;
            filter: drop-shadow(0 0 6px #ffb30088);
        }
        .activity-feed-divider {
            width: 100%;
            height: 2.5px;
            background: linear-gradient(90deg, #1ea7ff 0%, #ffb300 100%);
            border-radius: 2px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(30,167,255,0.13);
        }
        .activity-feed-section {
            max-height: 220px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #1ea7ff #101c36;
        }
        .activity-feed-section::-webkit-scrollbar {
            width: 6px;
        }
        .activity-feed-section::-webkit-scrollbar-thumb {
            background: #1ea7ff;
            border-radius: 4px;
        }
        .activity-feed-section::-webkit-scrollbar-track {
            background: #101c36;
        }
        .activity-feed-viewall {
            display: block;
            text-align: right;
            color: #1ea7ff;
            font-size: 0.98rem;
            margin-top: 0.5rem;
            text-decoration: none;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: color 0.18s;
        }
        .activity-feed-viewall:hover {
            color: #ffb300;
        }
        .stat-card .stat-icon {
            font-size: 1.35rem;
            margin-bottom: 0.3rem;
            color: #b0bec5;
            opacity: 0.85;
            transition: color 0.2s, opacity 0.2s;
        }
        .stat-card:hover .stat-icon {
            color: #1ea7ff;
            opacity: 1;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Live time update
        setInterval(function() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true };
            document.getElementById('dashboard-time').textContent = now.toLocaleString('en-US', options);
        }, 1000);

        document.addEventListener('DOMContentLoaded', function() {
            // Chart.js donut charts for statistics
            const statData = [
                {{ $stats['schools'] ?? 0 }},
                {{ $stats['users'] ?? 0 }},
                {{ $stats['roles'] ?? 0 }},
                {{ $stats['permissions'] ?? 0 }},
                {{ $stats['audit_logs'] ?? 0 }}
            ];
            const statMax = Math.max(...statData, 1);
            const statColors = ['#1ea7ff', '#22c55e', '#ffb300', '#6ec1e4', '#b0bec5'];
            statData.forEach((val, i) => {
                const ctx = document.getElementById('statChart'+i);
                if(ctx) {
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [val, Math.max(statMax-val, 0)],
                                backgroundColor: [statColors[i], '#101c36'],
                                borderWidth: 0,
                            }],
                        },
                        options: {
                            cutout: '70%',
                            plugins: { legend: { display: false } },
                            animation: { animateRotate: true, duration: 900 },
                            responsive: false,
                        }
                    });
                }
            });
        });
    </script>
@endsection 