@extends('examination::layouts.app')

@section('title', 'Create Exam Schedule')

@section('content')
<style>
    .schedule-form-card {
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
    
    .form-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .form-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }
    
    .form-subtitle {
        color: #64748b;
        font-size: 1.1rem;
        font-weight: 500;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        color: #374151;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-control {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .form-select {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        width: 100%;
        margin-top: 1rem;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
    }
    
    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        margin-top: 1rem;
    }
    
    .btn-cancel:hover {
        background: #e2e8f0;
        color: #374151;
        text-decoration: none;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="schedule-form-card">
                <div class="form-header">
                    <h1 class="form-title">
                        <i class="fas fa-calendar-plus me-3"></i>
                        Create Exam Schedule
                    </h1>
                    <p class="form-subtitle">Schedule a new examination with detailed information</p>
                </div>
                
                <form method="POST" action="{{ route('examination.schedules.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exam_name" class="form-label">Exam Name</label>
                                <input type="text" class="form-control" id="exam_name" name="exam_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exam_code" class="form-label">Exam Code</label>
                                <input type="text" class="form-control" id="exam_code" name="exam_code" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exam_date" class="form-label">Exam Date</label>
                                <input type="date" class="form-control" id="exam_date" name="exam_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exam_time" class="form-label">Exam Time</label>
                                <input type="time" class="form-control" id="exam_time" name="exam_time" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="duration" class="form-label">Duration (hours)</label>
                                <input type="number" class="form-control" id="duration" name="duration" min="0.5" step="0.5" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="venue" class="form-label">Venue</label>
                                <input type="text" class="form-control" id="venue" name="venue" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subject" class="form-label">Subject</label>
                                <select class="form-select" id="subject" name="subject" required>
                                    <option value="">Select Subject</option>
                                    <option value="mathematics">Mathematics</option>
                                    <option value="physics">Physics</option>
                                    <option value="chemistry">Chemistry</option>
                                    <option value="biology">Biology</option>
                                    <option value="english">English</option>
                                    <option value="history">History</option>
                                    <option value="geography">Geography</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exam_type" class="form-label">Exam Type</label>
                                <select class="form-select" id="exam_type" name="exam_type" required>
                                    <option value="">Select Type</option>
                                    <option value="midterm">Midterm</option>
                                    <option value="final">Final</option>
                                    <option value="quiz">Quiz</option>
                                    <option value="assignment">Assignment</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter exam description..."></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save me-2"></i>
                                Create Schedule
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('examination.schedules.index') }}" class="btn-cancel">
                                <i class="fas fa-times me-2"></i>
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


