@extends('layouts.app')

@section('title', 'Examination Management')

@section('content')
<style>
    body, .examination-premium-bg { 
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important; 
        min-height: 100vh;
    }
    
    .exam-header-section {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        padding: 2.5rem 0;
        margin-bottom: 2rem;
        border-radius: 0 0 2rem 2rem;
        box-shadow: 0 10px 30px rgba(30, 64, 175, 0.2);
    }
    
    .exam-header-content h1 {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .exam-header-content p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .exam-stat-card {
        background: rgba(255,255,255,0.95);
        border-radius: 1.5rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
        border: 1px solid rgba(255,255,255,0.2);
        padding: 2rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        min-height: 120px;
    }
    
    .exam-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4);
    }
    
    .exam-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }
    
    .exam-stat-icon {
        font-size: 2.5rem;
        border-radius: 1.2rem;
        padding: 1rem;
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 70px;
        height: 70px;
    }
    
    .exam-stat-icon.success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .exam-stat-icon.warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .exam-stat-icon.purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    
    .exam-stat-content {
        flex: 1;
    }
    
    .exam-stat-label {
        color: #64748b;
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .exam-stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.25rem;
        line-height: 1;
    }
    
    .exam-stat-change {
        font-size: 0.9rem;
        font-weight: 600;
        color: #10b981;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .exam-quick-actions {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .exam-action-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        color: white;
        border: none;
        border-radius: 1rem;
        padding: 1rem 1.5rem;
        font-size: 1rem;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }
    
    .exam-action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .exam-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .exam-action-btn:hover::before {
        left: 100%;
    }
    
    .exam-action-btn i {
        font-size: 1.1rem;
    }
    
    .exam-section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .exam-section-title i {
        color: #3b82f6;
        font-size: 1.3rem;
    }
    
    .exam-table-container {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .exam-table {
        margin: 0;
    }
    
    .exam-table th {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        font-weight: 700;
        color: #334155;
        font-size: 0.95rem;
        border-bottom: 2px solid #e2e8f0;
        padding: 1.25rem 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .exam-table td {
        vertical-align: middle;
        font-size: 0.95rem;
        color: #475569;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .exam-table tbody tr:hover {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        transition: all 0.2s ease;
    }
    
    .exam-status {
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .exam-status.draft { 
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); 
        color: #92400e; 
    }
    .exam-status.published { 
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); 
        color: #1e40af; 
    }
    .exam-status.ongoing { 
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); 
        color: #166534; 
    }
    .exam-status.completed { 
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); 
        color: #3730a3; 
    }
    
    .exam-feature-badge {
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        color: #3730a3;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 0.5rem;
        margin: 0.1rem;
        padding: 0.25rem 0.75rem;
        display: inline-block;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .exam-action-icons a {
        color: #64748b;
        margin-right: 0.75rem;
        font-size: 1.1rem;
        transition: all 0.2s ease;
        padding: 0.5rem;
        border-radius: 0.5rem;
    }
    
    .exam-action-icons a:hover {
        color: #3b82f6;
        background: #f1f5f9;
        transform: scale(1.1);
    }
    
    .upcoming-exam-card {
        background: white;
        border-radius: 1.2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    
    .upcoming-exam-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    
    .upcoming-exam-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .upcoming-exam-body {
        padding: 1.5rem;
    }
    
    .exam-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    
    .exam-meta {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .exam-badges {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    
    .exam-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .exam-badge.primary { background: #dbeafe; color: #1e40af; }
    .exam-badge.purple { background: #e0e7ff; color: #3730a3; }
    
    .exam-date {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }
    
    .exam-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .view-exam-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
        color: white;
        border: none;
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    
    .view-exam-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        color: white;
        text-decoration: none;
    }
    
    @media (max-width: 991.98px) {
        .exam-stat-card { 
            flex-direction: column; 
            text-align: center;
            padding: 1.5rem 1rem;
        }
        .exam-quick-actions { 
            flex-direction: column; 
            gap: 0.75rem; 
        }
        .exam-action-btn {
            justify-content: center;
        }
    }
</style>

<div class="examination-premium-bg">
    <!-- Header Section -->
    <div class="exam-header-section">
    <div class="container-xl">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="exam-header-content">
                        <h1><i class="fas fa-file-alt me-3"></i>Examination Management</h1>
                        <p>Comprehensive exam management and assessment platform</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="exam-quick-actions justify-content-end">
                        <a href="{{ route('examination.exams.create') }}" class="exam-action-btn">
                            <i class="fas fa-plus"></i> Create Exam
                        </a>
                        <a href="{{ route('examination.questions.index') }}" class="exam-action-btn">
                            <i class="fas fa-database"></i> Question Bank
                        </a>
                    </div>
                </div>
            </div>
            </div>
        </div>

    <div class="container-xl">
        <!-- Statistics Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="exam-stat-card">
                    <div class="exam-stat-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="exam-stat-content">
                        <div class="exam-stat-label">Total Exams</div>
                        <div class="exam-stat-value">{{ $exams->total() ?? 24 }}</div>
                        <div class="exam-stat-change">
                            <i class="fas fa-arrow-up"></i> +12% this month
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="exam-stat-card">
                    <div class="exam-stat-icon success">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="exam-stat-content">
                        <div class="exam-stat-label">Active Students</div>
                        <div class="exam-stat-value">156</div>
                        <div class="exam-stat-change">
                            <i class="fas fa-arrow-up"></i> +8% this week
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="exam-stat-card">
                    <div class="exam-stat-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="exam-stat-content">
                        <div class="exam-stat-label">Upcoming Exams</div>
                        <div class="exam-stat-value">{{ $upcomingExams->count() ?? 8 }}</div>
                        <div class="exam-stat-change">
                            <i class="fas fa-calendar"></i> Next: 3 days
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="exam-stat-card">
                    <div class="exam-stat-icon purple">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="exam-stat-content">
                        <div class="exam-stat-label">Average Score</div>
                        <div class="exam-stat-value">87%</div>
                        <div class="exam-stat-change">
                            <i class="fas fa-arrow-up"></i> +5% improvement
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Exams Section -->
        @if(isset($upcomingExams) && $upcomingExams->count() > 0)
        <div class="mb-5">
            <div class="exam-section-title">
                <i class="fas fa-calendar-alt"></i>
                Upcoming Exams
            </div>
            <div class="row g-4">
                @foreach($upcomingExams as $exam)
                <div class="col-md-6 col-lg-4">
                    <div class="upcoming-exam-card">
                        <div class="upcoming-exam-header">
                            <div class="exam-name">{{ $exam->name ?? 'Sample Exam' }}</div>
                            <div class="exam-meta">{{ $exam->examType->name ?? 'Final Exam' }} • {{ $exam->academic_year ?? '2024' }} - {{ $exam->term ?? 'Term 1' }}</div>
                                </div>
                        <div class="upcoming-exam-body">
                            <div class="exam-badges">
                                <span class="exam-badge primary">{{ $exam->is_online ?? true ? 'ONLINE' : 'OFFLINE' }}</span>
                                @if($exam->enable_proctoring ?? false)
                                <span class="exam-badge purple">Proctored</span>
                                    @endif
                                </div>
                            <div class="exam-date">
                                <i class="fas fa-calendar me-2"></i>
                                Starts: {{ $exam->start_date ? $exam->start_date->format('M d, Y') : 'Dec 15, 2024' }}
                            </div>
                            <div class="exam-actions">
                                <span class="exam-status {{ strtolower($exam->status ?? 'published') }}">{{ strtoupper($exam->status ?? 'PUBLISHED') }}</span>
                                <a href="#" class="view-exam-btn">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- All Exams Table -->
        <div class="exam-section-title">
            <i class="fas fa-list"></i>
            All Examinations
        </div>
        <div class="exam-table-container">
            <div class="table-responsive">
                <table class="table exam-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-file-alt me-2"></i>Exam Name</th>
                            <th><i class="fas fa-tag me-2"></i>Type</th>
                            <th><i class="fas fa-calendar me-2"></i>Schedule</th>
                            <th><i class="fas fa-signal me-2"></i>Status</th>
                            <th><i class="fas fa-star me-2"></i>Features</th>
                            <th class="text-center"><i class="fas fa-cogs me-2"></i>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($exams) && $exams->count() > 0)
                        @foreach($exams as $exam)
                        <tr>
                            <td>
                                    <div class="fw-bold" style="color:#1e293b;">{{ $exam->name }}</div>
                                <div class="text-muted">{{ $exam->code }}</div>
                            </td>
                                <td>
                                    <span class="badge bg-info text-dark">{{ $exam->examType->name ?? 'Final Exam' }}</span>
                                </td>
                                <td>
                                    <div>{{ $exam->start_date ? $exam->start_date->format('M d, Y') : 'Dec 15, 2024' }}</div>
                                    <div class="text-muted">{{ $exam->duration_minutes ?? 120 }} min</div>
                                </td>
                                <td>
                                    <span class="exam-status {{ strtolower($exam->status ?? 'published') }}">{{ strtoupper($exam->status ?? 'PUBLISHED') }}</span>
                            </td>
                            <td>
                                    @if($exam->is_online ?? true)
                                    <span class="exam-feature-badge">Online</span>
                                @endif
                                    @if($exam->enable_proctoring ?? false)
                                    <span class="exam-feature-badge">Proctored</span>
                                @endif
                                    @if($exam->show_results_immediately ?? false)
                                    <span class="exam-feature-badge">Instant Results</span>
                                @endif
                            </td>
                                <td class="exam-action-icons text-center">
                                    <a href="#" title="View Details"><i class="fas fa-eye"></i></a>
                                    <a href="#" title="Edit Exam"><i class="fas fa-edit"></i></a>
                                    <a href="#" title="Delete Exam"><i class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                            <!-- Sample data for demonstration -->
                            <tr>
                                <td>
                                    <div class="fw-bold" style="color:#1e293b;">Mathematics Final Exam</div>
                                    <div class="text-muted">MATH-2024-001</div>
                                </td>
                                <td><span class="badge bg-info text-dark">Final Exam</span></td>
                                <td>
                                    <div>Dec 15, 2024</div>
                                    <div class="text-muted">120 min</div>
                                </td>
                                <td><span class="exam-status published">PUBLISHED</span></td>
                                <td>
                                    <span class="exam-feature-badge">Online</span>
                                    <span class="exam-feature-badge">Proctored</span>
                                </td>
                                <td class="exam-action-icons text-center">
                                    <a href="#" title="View Details"><i class="fas fa-eye"></i></a>
                                    <a href="#" title="Edit Exam"><i class="fas fa-edit"></i></a>
                                    <a href="#" title="Delete Exam"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="fw-bold" style="color:#1e293b;">Physics Midterm</div>
                                    <div class="text-muted">PHYS-2024-002</div>
                                </td>
                                <td><span class="badge bg-warning text-dark">Midterm</span></td>
                                <td>
                                    <div>Dec 20, 2024</div>
                                    <div class="text-muted">90 min</div>
                                </td>
                                <td><span class="exam-status ongoing">ONGOING</span></td>
                                <td>
                                    <span class="exam-feature-badge">Online</span>
                                    <span class="exam-feature-badge">Instant Results</span>
                                </td>
                                <td class="exam-action-icons text-center">
                                    <a href="#" title="View Details"><i class="fas fa-eye"></i></a>
                                    <a href="#" title="Edit Exam"><i class="fas fa-edit"></i></a>
                                    <a href="#" title="Delete Exam"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            @if(isset($exams) && $exams->hasPages())
            <div class="d-flex justify-content-center p-4">
                {{ $exams->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 