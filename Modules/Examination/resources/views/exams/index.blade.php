@extends('layouts.app')

@section('title', 'Examination Dashboard')

@section('content')
<style>
    body, .examination-premium-bg { background: #f7fafd !important; }
    .exam-stat-card {
        background: rgba(255,255,255,0.85);
        border-radius: 1.2rem;
        box-shadow: 0 4px 24px 0 rgba(30,167,255,0.07);
        border: 1px solid #e3e9f7;
        padding: 2rem 1.5rem 1.5rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        transition: box-shadow 0.18s, transform 0.18s;
        position: relative;
        overflow: hidden;
        min-width: 220px;
    }
    .exam-stat-card:hover {
        box-shadow: 0 8px 32px rgba(30,167,255,0.13);
        transform: translateY(-2px) scale(1.03);
    }
    .exam-stat-icon {
        font-size: 2.2rem;
        border-radius: 1rem;
        padding: 0.7rem 1.1rem;
        background: linear-gradient(135deg, #1ea7ff 0%, #1565c0 100%);
        color: #fff;
        box-shadow: 0 2px 12px rgba(30,167,255,0.10);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .exam-stat-content {
        flex: 1;
    }
    .exam-stat-label {
        color: #5a6c7d;
        font-size: 1.08rem;
        font-weight: 600;
        margin-bottom: 0.2rem;
        letter-spacing: 0.5px;
    }
    .exam-stat-value {
        font-size: 2.1rem;
        font-weight: 800;
        color: #1a237e;
        margin-bottom: 0.1rem;
    }
    .exam-stat-change {
        font-size: 0.97rem;
        font-weight: 600;
        color: #22c55e;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    .exam-quick-actions {
        display: flex;
        gap: 1.2rem;
        margin-bottom: 2.2rem;
        flex-wrap: wrap;
    }
    .exam-action-btn {
        background: linear-gradient(135deg, #1ea7ff 0%, #1565c0 100%);
        color: #fff;
        border: none;
        border-radius: 1rem;
        padding: 1.2rem 2rem;
        font-size: 1.1rem;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(30,167,255,0.08);
        display: flex;
        align-items: center;
        gap: 0.8rem;
        transition: background 0.18s, box-shadow 0.18s, transform 0.18s;
        cursor: pointer;
        outline: none;
        margin-bottom: 0.5rem;
    }
    .exam-action-btn:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1ea7ff 100%);
        box-shadow: 0 8px 24px rgba(30,167,255,0.13);
        transform: translateY(-2px) scale(1.04);
    }
    .exam-action-btn i {
        font-size: 1.3rem;
    }
    .exam-section-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a237e;
        margin-bottom: 1.2rem;
        letter-spacing: 0.5px;
    }
    .exam-table {
        background: #fff;
        border-radius: 1.2rem;
        box-shadow: 0 2px 8px rgba(30,167,255,0.04);
        border: 1px solid #e3e9f7;
        overflow: hidden;
        margin-bottom: 2.5rem;
    }
    .exam-table th {
        background: #f3f6fa;
        font-weight: 700;
        color: #22304a;
        font-size: 1.04rem;
        border-bottom: 1px solid #e3e9f7;
        letter-spacing: 0.5px;
        padding: 1rem 0.7rem;
        text-align: left;
        position: sticky;
        top: 0;
        z-index: 2;
    }
    .exam-table td {
        vertical-align: middle;
        font-size: 1.03rem;
        background: transparent;
        color: #22304a;
        padding: 0.9rem 0.7rem;
    }
    .exam-table tbody tr:hover {
        background: #f5faff;
        transition: background 0.2s;
    }
    .exam-status {
        border-radius: 0.7rem;
        padding: 0.3rem 1rem;
        font-size: 0.97rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    .exam-status.draft { background: #fff7e6; color: #ffb300; }
    .exam-status.published { background: #e3f0ff; color: #2563eb; }
    .exam-status.ongoing { background: #dcfce7; color: #16a34a; }
    .exam-status.completed { background: #dbeafe; color: #1ea7ff; }
    .exam-feature-badge {
        background: #e3f0ff;
        color: #2563eb;
        font-size: 0.97rem;
        font-weight: 500;
        border-radius: 0.7rem;
        margin: 0 0.2rem 0.2rem 0;
        padding: 0.32rem 0.85rem;
        display: inline-block;
        box-shadow: none;
        letter-spacing: 0.2px;
    }
    .exam-action-icons a {
        color: #1ea7ff;
        margin-right: 0.7rem;
        font-size: 1.2rem;
        transition: color 0.18s, transform 0.18s;
    }
    .exam-action-icons a:hover {
        color: #2563eb;
        transform: scale(1.15);
    }
    @media (max-width: 991.98px) {
        .exam-stat-card { flex-direction: column; align-items: flex-start; }
        .exam-quick-actions { flex-direction: column; gap: 0.7rem; }
    }
</style>
<div class="examination-premium-bg py-4">
    <div class="container-xl">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <div>
                <h1 class="display-5 fw-bold mb-1" style="color:#1a237e;">Examination Management</h1>
                <div class="text-muted mb-2" style="font-size:1.15rem;">Manage exams, questions, schedules, and results in one place</div>
            </div>
            <div class="exam-quick-actions">
                <a href="{{ route('examination.exams.create') }}" class="exam-action-btn"><i class="fas fa-plus"></i> Create Exam</a>
                <a href="{{ route('examination.questions.index') }}" class="exam-action-btn"><i class="fas fa-database"></i> Question Bank</a>
                <a href="{{ route('examination.schedules.index') }}" class="exam-action-btn"><i class="fas fa-calendar-alt"></i> Schedules</a>
                <a href="{{ route('examination.proctoring.index') }}" class="exam-action-btn"><i class="fas fa-eye"></i> Proctoring</a>
                <a href="{{ route('examination.online-exams.create') }}" class="exam-action-btn"><i class="fas fa-bolt"></i> Online Exam Creation</a>
            </div>
        </div>
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="exam-stat-card">
                    <span class="exam-stat-icon"><i class="fas fa-file-alt"></i></span>
                    <div class="exam-stat-content">
                        <div class="exam-stat-label">Total Exams</div>
                        <div class="exam-stat-value">{{ $exams->total() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="exam-stat-card">
                    <span class="exam-stat-icon" style="background:linear-gradient(135deg,#22c55e 0%,#16a34a 100%);"><i class="fas fa-check-circle"></i></span>
                    <div class="exam-stat-content">
                        <div class="exam-stat-label">Active Exams</div>
                        <div class="exam-stat-value">{{ $exams->where('status', 'ongoing')->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="exam-stat-card">
                    <span class="exam-stat-icon" style="background:linear-gradient(135deg,#ffb300 0%,#f59e0b 100%);"><i class="fas fa-clock"></i></span>
                    <div class="exam-stat-content">
                        <div class="exam-stat-label">Upcoming</div>
                        <div class="exam-stat-value">{{ $upcomingExams->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="exam-stat-card">
                    <span class="exam-stat-icon" style="background:linear-gradient(135deg,#8b5cf6 0%,#7c3aed 100%);"><i class="fas fa-bolt"></i></span>
                    <div class="exam-stat-content">
                        <div class="exam-stat-label">Online Exams</div>
                        <div class="exam-stat-value">{{ $exams->where('is_online', true)->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        @if($upcomingExams->count() > 0)
        <div class="mb-4">
            <div class="exam-section-title mb-3"><i class="fas fa-calendar-alt me-2"></i>Upcoming Exams</div>
            <div class="row g-3">
                @foreach($upcomingExams as $exam)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm border-0 mb-2" style="border-radius:1.1rem;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <div class="fw-bold" style="font-size:1.13rem; color:#1a237e;">{{ $exam->name }}</div>
                                    <div class="text-muted" style="font-size:0.98rem;">{{ $exam->examType->name }} • {{ $exam->academic_year }} - {{ $exam->term }}</div>
                                </div>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-primary">{{ $exam->is_online ? 'ONLINE' : 'OFFLINE' }}</span>
                                    @if($exam->enable_proctoring)
                                    <span class="badge bg-purple">Proctored</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-muted mb-1" style="font-size:0.97rem;">Starts: {{ $exam->start_date->format('M d, Y') }}</div>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <span class="exam-status {{ strtolower($exam->status) }}">{{ strtoupper($exam->status) }}</span>
                                <a href="#" class="btn btn-sm btn-outline-primary ms-auto">View</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        <div class="exam-section-title"><i class="fas fa-list me-2"></i>All Exams</div>
        <div class="exam-table mb-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Exam</th>
                            <th>Type</th>
                            <th>Schedule</th>
                            <th>Status</th>
                            <th>Features</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $exam)
                        <tr>
                            <td>
                                <div class="fw-bold" style="color:#1a237e;">{{ $exam->name }}</div>
                                <div class="text-muted">{{ $exam->code }}</div>
                            </td>
                            <td><span class="badge bg-info text-dark">{{ $exam->examType->name }}</span></td>
                            <td>
                                <div>{{ $exam->start_date->format('M d, Y') }}</div>
                                <div class="text-muted">{{ $exam->duration_minutes }} min</div>
                            </td>
                            <td><span class="exam-status {{ strtolower($exam->status) }}">{{ strtoupper($exam->status) }}</span></td>
                            <td>
                                @if($exam->is_online)
                                    <span class="exam-feature-badge">Online</span>
                                @endif
                                @if($exam->enable_proctoring)
                                    <span class="exam-feature-badge">Proctored</span>
                                @endif
                                @if($exam->show_results_immediately)
                                    <span class="exam-feature-badge">Instant Results</span>
                                @endif
                            </td>
                            <td class="exam-action-icons">
                                <a href="#" title="View"><i class="fas fa-eye"></i></a>
                                <a href="#" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="#" title="Delete"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
                {{ $exams->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 