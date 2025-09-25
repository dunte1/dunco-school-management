

<?php $__env->startSection('title', 'Start Online Exam'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .exam-start-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    .exam-start-card {
        background: white;
        border-radius: 2rem;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        padding: 3rem;
        max-width: 600px;
        width: 100%;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .exam-start-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
    }
    
    .exam-icon {
        font-size: 4rem;
        color: #667eea;
        margin-bottom: 1.5rem;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .exam-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1a202c;
        margin-bottom: 1rem;
        line-height: 1.2;
    }
    
    .exam-subtitle {
        font-size: 1.2rem;
        color: #718096;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    .exam-details {
        background: #f7fafc;
        border-radius: 1rem;
        padding: 2rem;
        margin: 2rem 0;
        border: 1px solid #e2e8f0;
    }
    
    .exam-detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .exam-detail-item:last-child {
        border-bottom: none;
    }
    
    .exam-detail-label {
        font-weight: 600;
        color: #4a5568;
    }
    
    .exam-detail-value {
        font-weight: 700;
        color: #2d3748;
    }
    
    .exam-warning {
        background: #fff5f5;
        border: 1px solid #fed7d7;
        border-radius: 1rem;
        padding: 1.5rem;
        margin: 2rem 0;
        text-align: left;
    }
    
    .exam-warning h4 {
        color: #c53030;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .exam-warning ul {
        color: #742a2a;
        margin: 0;
        padding-left: 1.5rem;
    }
    
    .exam-warning li {
        margin-bottom: 0.5rem;
    }
    
    .exam-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }
    
    .btn-start-exam {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 1rem;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-start-exam:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-cancel {
        background: #e2e8f0;
        color: #4a5568;
        border: none;
        padding: 1rem 2rem;
        border-radius: 1rem;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-cancel:hover {
        background: #cbd5e0;
        color: #2d3748;
        text-decoration: none;
    }
    
    .proctoring-notice {
        background: #ebf8ff;
        border: 1px solid #bee3f8;
        border-radius: 1rem;
        padding: 1rem;
        margin: 1rem 0;
        text-align: left;
    }
    
    .proctoring-notice h5 {
        color: #2b6cb0;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>

<div class="exam-start-container">
    <div class="exam-start-card">
        <div class="exam-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        
        <h1 class="exam-title">Ready to Start?</h1>
        <p class="exam-subtitle">You are about to begin your online examination. Please review the details below before proceeding.</p>
        
        <div class="exam-details">
            <div class="exam-detail-item">
                <span class="exam-detail-label">Exam Name:</span>
                <span class="exam-detail-value">Mathematics Final Exam</span>
            </div>
            <div class="exam-detail-item">
                <span class="exam-detail-label">Duration:</span>
                <span class="exam-detail-value">180 minutes</span>
            </div>
            <div class="exam-detail-item">
                <span class="exam-detail-label">Total Questions:</span>
                <span class="exam-detail-value">50 questions</span>
            </div>
            <div class="exam-detail-item">
                <span class="exam-detail-label">Proctoring:</span>
                <span class="exam-detail-value">Enabled</span>
            </div>
            <div class="exam-detail-item">
                <span class="exam-detail-label">Auto-submit:</span>
                <span class="exam-detail-value">Yes</span>
            </div>
        </div>
        
        <div class="proctoring-notice">
            <h5><i class="fas fa-shield-alt"></i> Proctoring Active</h5>
            <p class="mb-0">This exam uses AI proctoring. Your screen, camera, and microphone will be monitored during the exam.</p>
        </div>
        
        <div class="exam-warning">
            <h4><i class="fas fa-exclamation-triangle"></i> Important Instructions</h4>
            <ul>
                <li>Ensure you have a stable internet connection</li>
                <li>Close all other applications and browser tabs</li>
                <li>Do not switch between windows or tabs during the exam</li>
                <li>Keep your face visible to the camera at all times</li>
                <li>Do not use any external devices or materials</li>
                <li>Once started, you cannot pause or restart the exam</li>
            </ul>
        </div>
        
        <div class="exam-actions">
            <a href="<?php echo e(route('examination.online.start', 1)); ?>" class="btn-start-exam">
                <i class="fas fa-play"></i>
                Start Exam Now
            </a>
            <a href="<?php echo e(route('examination.exams.index')); ?>" class="btn-cancel">
                <i class="fas fa-times"></i>
                Cancel
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation dialog for starting exam
    const startButton = document.querySelector('.btn-start-exam');
    startButton.addEventListener('click', function(e) {
        if (!confirm('Are you sure you want to start the exam? You cannot pause or restart once begun.')) {
            e.preventDefault();
        }
    });
    
    // Add countdown timer (optional)
    let countdown = 10;
    const countdownElement = document.createElement('div');
    countdownElement.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #667eea; color: white; padding: 1rem; border-radius: 0.5rem; font-weight: bold; z-index: 1000;';
    document.body.appendChild(countdownElement);
    
    const timer = setInterval(() => {
        countdown--;
        countdownElement.textContent = `Auto-start in: ${countdown}s`;
        
        if (countdown <= 0) {
            clearInterval(timer);
            countdownElement.remove();
            // Auto-start exam after countdown
            // window.location.href = startButton.href;
        }
    }, 1000);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Examination/resources/views/online/start.blade.php ENDPATH**/ ?>