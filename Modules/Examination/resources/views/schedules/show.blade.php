@extends('examination::layouts.app')

@section('title', 'Schedule Details')

@section('content')
<style>
    .schedule-detail-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06), 0 4px 16px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.8);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }
    
    .detail-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .detail-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }
    
    .detail-info {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #374151;
    }
    
    .info-value {
        color: #64748b;
        font-weight: 500;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }
    
    .btn-action {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    
    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .btn-back {
        background: #f1f5f9;
        color: #64748b;
        border: 2px solid #e2e8f0;
    }
    
    .btn-back:hover {
        background: #e2e8f0;
        color: #374151;
        text-decoration: none;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="schedule-detail-card">
                <div class="detail-header">
                    <h1 class="detail-title">
                        <i class="fas fa-calendar-check me-3"></i>
                        Schedule Details
                    </h1>
                </div>
                
                <div class="detail-info">
                    <div class="info-row">
                        <span class="info-label">Exam Name:</span>
                        <span class="info-value">Mathematics Final Exam</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Exam Code:</span>
                        <span class="info-value">MATH-101</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date:</span>
                        <span class="info-value">December 15, 2024</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Time:</span>
                        <span class="info-value">09:00 AM</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Duration:</span>
                        <span class="info-value">3 hours</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Venue:</span>
                        <span class="info-value">Room 201</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Subject:</span>
                        <span class="info-value">Mathematics</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Exam Type:</span>
                        <span class="info-value">Final</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <span class="badge bg-warning text-dark">Upcoming</span>
                        </span>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="#" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i>
                        Edit Schedule
                    </a>
                    <a href="{{ route('examination.schedules.index') }}" class="btn-action btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Back to Schedules
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


