@extends('layouts.app')

@section('content')
<style>
    .examination-premium-card {
        background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
        border-radius: 1.5rem;
        box-shadow: 0 8px 32px 0 rgba(30,167,255,0.08);
        border: 1px solid #e3e9f7;
        padding: 2.5rem 2rem 2rem 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .examination-premium-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #1ea7ff 0%, #1565c0 50%, #ffb300 100%);
    }
    .examination-header-premium {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        margin-bottom: 2rem;
    }
    .examination-header-premium .icon {
        font-size: 2.8rem;
        color: #fff;
        background: linear-gradient(135deg, #1ea7ff 0%, #1565c0 100%);
        border-radius: 1.2rem;
        padding: 1rem 1.2rem;
        box-shadow: 0 4px 16px rgba(30,167,255,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .examination-header-premium h1 {
        font-weight: 700;
        font-size: 2.2rem;
        color: #1a237e;
        letter-spacing: 1.2px;
        margin-bottom: 0.5rem;
        text-shadow: none;
    }
    .examination-header-premium p {
        color: #5a6c7d;
        font-size: 1.1rem;
        margin: 0;
        font-weight: 500;
    }
    .examination-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    .examination-stat-card {
        background: #fff;
        border-radius: 1.2rem;
        padding: 1.8rem;
        box-shadow: 0 4px 16px rgba(30,167,255,0.06);
        border: 1px solid #e3e9f7;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .examination-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #1ea7ff 0%, #1565c0 100%);
    }
    .examination-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(30,167,255,0.12);
    }
    .examination-stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .examination-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: #fff;
    }
    .examination-stat-icon.blue { background: linear-gradient(135deg, #1ea7ff 0%, #1565c0 100%); }
    .examination-stat-icon.green { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); }
    .examination-stat-icon.orange { background: linear-gradient(135deg, #ffb300 0%, #f59e0b 100%); }
    .examination-stat-icon.purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .examination-stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        color: #1a237e;
        margin-bottom: 0.5rem;
    }
    .examination-stat-label {
        color: #5a6c7d;
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .examination-stat-change {
        font-size: 0.9rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    .examination-stat-change.positive { color: #22c55e; }
    .examination-stat-change.negative { color: #e53935; }
    .examination-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2.5rem;
    }
    .examination-action-btn {
        background: #fff;
        border: 2px solid #e3e9f7;
        border-radius: 1rem;
        padding: 1.5rem;
        text-decoration: none;
        color: #1a237e;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.8rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(30,167,255,0.04);
    }
    .examination-action-btn:hover {
        border-color: #1ea7ff;
        background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(30,167,255,0.12);
        color: #1a237e;
        text-decoration: none;
    }
    .examination-action-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1ea7ff 0%, #1565c0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #fff;
        box-shadow: 0 4px 12px rgba(30,167,255,0.15);
    }
    .examination-action-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin: 0;
    }
    .examination-action-desc {
        font-size: 0.9rem;
        color: #5a6c7d;
        margin: 0;
    }
    .examination-recent-section {
        background: #fff;
        border-radius: 1.2rem;
        padding: 2rem;
        box-shadow: 0 4px 16px rgba(30,167,255,0.06);
        border: 1px solid #e3e9f7;
    }
    .examination-recent-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }
    .examination-recent-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a237e;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    .examination-recent-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border-radius: 0.8rem;
        margin-bottom: 0.8rem;
        transition: background 0.2s ease;
        border: 1px solid transparent;
    }
    .examination-recent-item:hover {
        background: #f8f9ff;
        border-color: #e3e9f7;
    }
    .examination-recent-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #1ea7ff 0%, #1565c0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: #fff;
    }
    .examination-recent-content {
        flex: 1;
    }
    .examination-recent-title-item {
        font-weight: 600;
        color: #1a237e;
        margin-bottom: 0.2rem;
    }
    .examination-recent-meta {
        font-size: 0.9rem;
        color: #5a6c7d;
    }
    .examination-recent-status {
        padding: 0.3rem 0.8rem;
        border-radius: 0.6rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .examination-recent-status.active { background: #dcfce7; color: #16a34a; }
    .examination-recent-status.completed { background: #dbeafe; color: #2563eb; }
    .examination-recent-status.pending { background: #fef3c7; color: #d97706; }
    @media (max-width: 768px) {
        .examination-premium-card { padding: 1.5rem 1rem; }
        .examination-header-premium h1 { font-size: 1.8rem; }
        .examination-stats-grid { grid-template-columns: 1fr; }
        .examination-actions-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>

<div class="examination-premium-card">
    <div class="examination-header-premium">
        <span class="icon"><i class="fas fa-file-alt"></i></span>
        <div>
            <h1>Examination Dashboard</h1>
            <p>Manage exams, schedules, and results efficiently</p>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="examination-stats-grid">
        <div class="examination-stat-card">
            <div class="examination-stat-header">
                <div class="examination-stat-icon blue">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <div class="examination-stat-number">24</div>
            <div class="examination-stat-label">Total Exams</div>
            <div class="examination-stat-change positive">
                <i class="fas fa-arrow-up"></i>
                +12% from last month
            </div>
        </div>

        <div class="examination-stat-card">
            <div class="examination-stat-header">
                <div class="examination-stat-icon green">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="examination-stat-number">8</div>
            <div class="examination-stat-label">Active Schedules</div>
            <div class="examination-stat-change positive">
                <i class="fas fa-arrow-up"></i>
                +3 this week
            </div>
        </div>

        <div class="examination-stat-card">
            <div class="examination-stat-header">
                <div class="examination-stat-icon orange">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="examination-stat-number">156</div>
            <div class="examination-stat-label">Students Enrolled</div>
            <div class="examination-stat-change positive">
                <i class="fas fa-arrow-up"></i>
                +8% from last week
            </div>
        </div>

        <div class="examination-stat-card">
            <div class="examination-stat-header">
                <div class="examination-stat-icon purple">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="examination-stat-number">87.5%</div>
            <div class="examination-stat-label">Average Score</div>
            <div class="examination-stat-change positive">
                <i class="fas fa-arrow-up"></i>
                +2.3% improvement
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="examination-actions-grid">
        <a href="{{ route('examination.exams.create') }}" class="examination-action-btn">
            <div class="examination-action-icon">
                <i class="fas fa-plus"></i>
            </div>
            <h4 class="examination-action-title">Create Exam</h4>
            <p class="examination-action-desc">Set up a new examination</p>
        </a>
        <a href="{{ route('examination.questions.index') }}" class="examination-action-btn">
            <div class="examination-action-icon">
                <i class="fas fa-question-circle"></i>
            </div>
            <h4 class="examination-action-title">Question Bank</h4>
            <p class="examination-action-desc">Manage all questions</p>
        </a>
        <a href="{{ route('examination.schedules.index') }}" class="examination-action-btn">
            <div class="examination-action-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h4 class="examination-action-title">Schedules</h4>
            <p class="examination-action-desc">View and manage exam schedules</p>
        </a>
        <a href="{{ route('examination.proctoring.index') }}" class="examination-action-btn">
            <div class="examination-action-icon">
                <i class="fas fa-eye"></i>
            </div>
            <h4 class="examination-action-title">Proctoring</h4>
            <p class="examination-action-desc">Monitor exam sessions</p>
        </a>
        <a href="{{ route('examination.results.index') }}" class="examination-action-btn">
            <div class="examination-action-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h4 class="examination-action-title">Results</h4>
            <p class="examination-action-desc">View exam results</p>
        </a>
        <a href="{{ route('examination.schedules.timetable') }}" class="examination-action-btn">
            <div class="examination-action-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h4 class="examination-action-title">Timetable</h4>
            <p class="examination-action-desc">See all exam timetables</p>
        </a>
    </div>

    <!-- Recent Activities -->
    <div class="examination-recent-section">
        <div class="examination-recent-header">
            <h3 class="examination-recent-title">
                <i class="fas fa-history"></i>
                Recent Activities
            </h3>
            <a href="#" class="btn btn-outline-primary btn-sm">View All</a>
        </div>

        <div class="examination-recent-item">
            <div class="examination-recent-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="examination-recent-content">
                <div class="examination-recent-title-item">Mathematics Final Exam</div>
                <div class="examination-recent-meta">Created by Dr. Smith • 2 hours ago</div>
            </div>
            <span class="examination-recent-status active">Active</span>
        </div>

        <div class="examination-recent-item">
            <div class="examination-recent-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="examination-recent-content">
                <div class="examination-recent-title-item">Physics Midterm Schedule</div>
                <div class="examination-recent-meta">Scheduled for Dec 15, 2024 • 1 day ago</div>
            </div>
            <span class="examination-recent-status pending">Pending</span>
        </div>

        <div class="examination-recent-item">
            <div class="examination-recent-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
            <div class="examination-recent-content">
                <div class="examination-recent-title-item">Chemistry Lab Results</div>
                <div class="examination-recent-meta">Results published • 3 days ago</div>
            </div>
            <span class="examination-recent-status completed">Completed</span>
        </div>

        <div class="examination-recent-item">
            <div class="examination-recent-icon">
                <i class="fas fa-question-circle"></i>
            </div>
            <div class="examination-recent-content">
                <div class="examination-recent-title-item">Biology Question Bank Updated</div>
                <div class="examination-recent-meta">50 new questions added • 1 week ago</div>
            </div>
            <span class="examination-recent-status completed">Completed</span>
        </div>

        <div class="examination-recent-item">
            <div class="examination-recent-icon">
                <i class="fas fa-eye"></i>
            </div>
            <div class="examination-recent-content">
                <div class="examination-recent-title-item">English Proctoring Alert</div>
                <div class="examination-recent-meta">Suspicious activity detected • 2 weeks ago</div>
            </div>
            <span class="examination-recent-status pending">Pending</span>
        </div>
    </div>
</div>
@endsection
