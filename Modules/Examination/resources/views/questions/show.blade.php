@extends('examination::layouts.app')

@section('title', 'Question Details')

@section('content')
<style>
    .question-detail-card {
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
    
    .question-content {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }
    
    .question-text {
        font-size: 1.1rem;
        line-height: 1.6;
        color: #374151;
        margin-bottom: 1rem;
    }
    
    .options-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .option-item {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
    }
    
    .option-item.correct {
        background: #d1fae5;
        border-color: #10b981;
    }
    
    .option-label {
        font-weight: 600;
        color: #374151;
        margin-right: 0.5rem;
        min-width: 30px;
    }
    
    .option-text {
        color: #64748b;
        flex: 1;
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
            <div class="question-detail-card">
                <div class="detail-header">
                    <h1 class="detail-title">
                        <i class="fas fa-question-circle me-3"></i>
                        Question Details
                    </h1>
                </div>
                
                <div class="question-content">
                    <div class="question-text">
                        <strong>What is the derivative of x²?</strong>
                    </div>
                    
                    <ul class="options-list">
                        <li class="option-item">
                            <span class="option-label">A.</span>
                            <span class="option-text">x</span>
                        </li>
                        <li class="option-item correct">
                            <span class="option-label">B.</span>
                            <span class="option-text">2x</span>
                        </li>
                        <li class="option-item">
                            <span class="option-label">C.</span>
                            <span class="option-text">x²</span>
                        </li>
                        <li class="option-item">
                            <span class="option-label">D.</span>
                            <span class="option-text">2x²</span>
                        </li>
                    </ul>
                    
                    <div class="mt-3">
                        <strong>Correct Answer:</strong> B (2x)
                    </div>
                    
                    <div class="mt-3">
                        <strong>Explanation:</strong> The derivative of x² is 2x using the power rule of differentiation.
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="#" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i>
                        Edit Question
                    </a>
                    <a href="{{ route('examination.questions.index') }}" class="btn-action btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Back to Questions
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


