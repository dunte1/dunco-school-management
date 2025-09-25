@extends('layouts.app')

@section('content')
<style>
    .examination-premium-card {
        background: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        border: 1px solid var(--gray-200);
        padding: 2rem;
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
        background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-dark) 50%, var(--warning-color) 100%);
    }
    
    .examination-header-premium {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        margin-bottom: 2rem;
    }
    
    .examination-header-premium .icon {
        font-size: 2.5rem;
        color: var(--white);
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        border-radius: var(--border-radius);
        padding: 1rem 1.2rem;
        box-shadow: var(--shadow-md);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .examination-header-premium h1 {
        font-weight: 700;
        font-size: 2rem;
        color: var(--gray-800);
        letter-spacing: -0.025em;
        margin-bottom: 0.5rem;
    }
    
    .examination-header-premium p {
        color: var(--gray-600);
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
        background: var(--white);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
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
        background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    }
    
    .examination-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
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
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: var(--white);
    }
    
    .examination-stat-icon.blue { 
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); 
    }
    
    .examination-stat-icon.green { 
        background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%); 
    }
    
    .examination-stat-icon.orange { 
        background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%); 
    }
    
    .examination-stat-icon.purple { 
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); 
    }
    
    .examination-stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 0.25rem;
    }
    
    .examination-stat-label {
        color: var(--gray-600);
        font-weight: 500;
        font-size: 0.875rem;
    }
    
    .examination-feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .examination-feature-card {
        background: var(--white);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .examination-feature-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        text-decoration: none;
        color: inherit;
    }
    
    .examination-feature-icon {
        width: 56px;
        height: 56px;
        border-radius: var(--border-radius);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--white);
        margin-bottom: 1rem;
    }
    
    .examination-feature-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 0.5rem;
    }
    
    .examination-feature-description {
        color: var(--gray-600);
        font-size: 0.875rem;
        line-height: 1.5;
    }
    
    .examination-action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .examination-action-btn {
        padding: 0.75rem 1.5rem;
        border-radius: var(--border-radius);
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .examination-action-btn.primary {
        background: var(--primary-color);
        color: var(--white);
    }
    
    .examination-action-btn.primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
        color: var(--white);
    }
    
    .examination-action-btn.secondary {
        background: var(--gray-100);
        color: var(--gray-700);
        border: 1px solid var(--gray-300);
    }
    
    .examination-action-btn.secondary:hover {
        background: var(--gray-200);
        color: var(--gray-800);
        transform: translateY(-1px);
        box-shadow: var(--shadow-sm);
    }
</style>

<div class="container py-4">
<div class="examination-premium-card">
    <div class="examination-header-premium">
            <div class="icon">
                <i class="fas fa-file-alt"></i>
            </div>
        <div>
                <h1>Examination Management System</h1>
                <p>Comprehensive exam management and assessment platform</p>
        </div>
    </div>

    <div class="examination-stats-grid">
        <div class="examination-stat-card">
            <div class="examination-stat-header">
                <div class="examination-stat-icon blue">
                    <i class="fas fa-file-alt"></i>
                </div>
            </div>
            <div class="examination-stat-number">24</div>
            <div class="examination-stat-label">Total Exams</div>
        </div>

        <div class="examination-stat-card">
            <div class="examination-stat-header">
                <div class="examination-stat-icon green">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="examination-stat-number">156</div>
                <div class="examination-stat-label">Active Students</div>
        </div>

        <div class="examination-stat-card">
            <div class="examination-stat-header">
                <div class="examination-stat-icon orange">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="examination-stat-number">8</div>
                <div class="examination-stat-label">Upcoming Exams</div>
        </div>

        <div class="examination-stat-card">
            <div class="examination-stat-header">
                <div class="examination-stat-icon purple">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
                <div class="examination-stat-number">87%</div>
            <div class="examination-stat-label">Average Score</div>
        </div>
    </div>

        <div class="examination-feature-grid">
            <a href="{{ route('examination.questions.index') }}" class="examination-feature-card">
                <div class="examination-feature-icon" style="background: var(--primary-color);">
                <i class="fas fa-question-circle"></i>
            </div>
                <div class="examination-feature-title">Question Bank</div>
                <div class="examination-feature-description">
                    Create, manage, and organize examination questions with multiple choice, essay, and other formats.
                </div>
            </a>
            
            <a href="{{ route('examination.schedules.index') }}" class="examination-feature-card">
                <div class="examination-feature-icon" style="background: var(--info-color);">
                <i class="fas fa-calendar-alt"></i>
            </div>
                <div class="examination-feature-title">Exam Scheduling</div>
                <div class="examination-feature-description">
                    Schedule exams, set time limits, and manage exam sessions with automatic notifications.
                </div>
            </a>
            
            <a href="{{ route('examination.proctoring.index') }}" class="examination-feature-card">
                <div class="examination-feature-icon" style="background: var(--warning-color);">
                <i class="fas fa-eye"></i>
            </div>
                <div class="examination-feature-title">Proctoring</div>
                <div class="examination-feature-description">
                    Monitor exams in real-time with advanced proctoring features and security measures.
            </div>
            </a>
            
            <a href="{{ route('examination.results.index') }}" class="examination-feature-card">
                <div class="examination-feature-icon" style="background: var(--success-color);">
                    <i class="fas fa-clipboard-check"></i>
        </div>
                <div class="examination-feature-title">Results & Analytics</div>
                <div class="examination-feature-description">
                    Generate detailed reports, analyze performance, and provide comprehensive feedback.
            </div>
            </a>
        </div>

        <div class="examination-action-buttons">
            <a href="{{ route('examination.dashboard') }}" class="examination-action-btn primary">
                <i class="fas fa-tachometer-alt"></i>
                Go to Dashboard
            </a>
            <a href="{{ route('examination.questions.index') }}" class="examination-action-btn secondary">
                <i class="fas fa-question-circle"></i>
                Manage Questions
            </a>
            <a href="{{ route('examination.schedules.index') }}" class="examination-action-btn secondary">
                <i class="fas fa-calendar-alt"></i>
                View Schedules
            </a>
        </div>
    </div>
</div>
@endsection
