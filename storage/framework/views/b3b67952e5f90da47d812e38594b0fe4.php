<?php $__env->startSection('title', 'Examination Management System'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .examination-premium-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08), 0 8px 16px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.8);
        padding: 3rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(20px);
    }
    
    .examination-premium-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 25%, #7c3aed 50%, #dc2626 75%, #ea580c 100%);
        border-radius: 24px 24px 0 0;
    }
    
    .examination-header-premium {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 3rem;
        position: relative;
    }
    
    .examination-header-premium .icon {
        font-size: 3rem;
        color: #ffffff;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 20px;
        padding: 1.5rem 1.8rem;
        box-shadow: 0 12px 24px rgba(59, 130, 246, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .examination-header-premium .icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transform: rotate(45deg);
        animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }
    
    .examination-header-premium h1 {
        font-weight: 800;
        font-size: 2.5rem;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        letter-spacing: -0.025em;
        margin-bottom: 0.75rem;
        line-height: 1.2;
    }
    
    .examination-header-premium p {
        color: #64748b;
        font-size: 1.25rem;
        margin: 0;
        font-weight: 500;
        line-height: 1.5;
    }
    
    .examination-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }
    
    .examination-stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06), 0 4px 16px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.8);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }
    
    .examination-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 20px 20px 0 0;
    }
    
    .examination-stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12), 0 8px 24px rgba(0, 0, 0, 0.06);
    }
    
    .examination-stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    
    .examination-stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #ffffff;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        position: relative;
        overflow: hidden;
    }
    
    .examination-stat-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }
    
    .examination-stat-card:hover .examination-stat-icon::before {
        transform: translateX(100%);
    }
    
    .examination-stat-icon.blue { 
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); 
    }
    
    .examination-stat-icon.green { 
        background: linear-gradient(135deg, #10b981 0%, #059669 100%); 
    }
    
    .examination-stat-icon.orange { 
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); 
    }
    
    .examination-stat-icon.purple { 
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); 
    }
    
    .examination-stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        line-height: 1;
    }
    
    .examination-stat-label {
        color: #64748b;
        font-weight: 600;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .examination-feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }
    
    .examination-feature-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06), 0 4px 16px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.8);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: inherit;
        display: block;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }
    
    .examination-feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 20px 20px 0 0;
    }
    
    .examination-feature-card:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12), 0 8px 24px rgba(0, 0, 0, 0.06);
        text-decoration: none;
        color: inherit;
    }
    
    .examination-feature-icon {
        width: 72px;
        height: 72px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #ffffff;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        position: relative;
        overflow: hidden;
    }
    
    .examination-feature-icon::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s ease;
    }
    
    .examination-feature-card:hover .examination-feature-icon::before {
        transform: translateX(100%);
    }
    
    .examination-feature-title {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.75rem;
        line-height: 1.3;
    }
    
    .examination-feature-description {
        color: #64748b;
        font-size: 1rem;
        line-height: 1.6;
        font-weight: 500;
    }
    
    .examination-action-buttons {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 2rem;
    }
    
    .examination-action-btn {
        padding: 1rem 2rem;
        border-radius: 16px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.1rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .examination-action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }
    
    .examination-action-btn:hover::before {
        left: 100%;
    }
    
    .examination-action-btn.primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: #ffffff;
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
    }
    
    .examination-action-btn.primary:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(59, 130, 246, 0.4);
        color: #ffffff;
    }
    
    .examination-action-btn.secondary {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #475569;
        border: 1px solid #cbd5e1;
    }
    
    .examination-action-btn.secondary:hover {
        background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        color: #334155;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .examination-premium-card {
            padding: 2rem;
            margin: 1rem;
        }
        
        .examination-header-premium {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }
        
        .examination-header-premium h1 {
            font-size: 2rem;
        }
        
        .examination-stats-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .examination-feature-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .examination-action-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .examination-action-btn {
            width: 100%;
            max-width: 300px;
            justify-content: center;
        }
    }
    
    /* Animation for cards on load */
    .examination-stat-card,
    .examination-feature-card {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(30px);
    }
    
    .examination-stat-card:nth-child(1) { animation-delay: 0.1s; }
    .examination-stat-card:nth-child(2) { animation-delay: 0.2s; }
    .examination-stat-card:nth-child(3) { animation-delay: 0.3s; }
    .examination-stat-card:nth-child(4) { animation-delay: 0.4s; }
    
    .examination-feature-card:nth-child(1) { animation-delay: 0.5s; }
    .examination-feature-card:nth-child(2) { animation-delay: 0.6s; }
    .examination-feature-card:nth-child(3) { animation-delay: 0.7s; }
    .examination-feature-card:nth-child(4) { animation-delay: 0.8s; }
    
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
            <a href="<?php echo e(route('examination.questions.index')); ?>" class="examination-feature-card">
                <div class="examination-feature-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="examination-feature-title">Question Bank</div>
                <div class="examination-feature-description">
                    Create, manage, and organize examination questions with multiple choice, essay, and other formats.
                </div>
            </a>
            
            <a href="<?php echo e(route('examination.schedules.index')); ?>" class="examination-feature-card">
                <div class="examination-feature-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="examination-feature-title">Exam Scheduling</div>
                <div class="examination-feature-description">
                    Schedule exams, set time limits, and manage exam sessions with automatic notifications.
                </div>
            </a>
            
            <a href="<?php echo e(route('examination.proctoring.index')); ?>" class="examination-feature-card">
                <div class="examination-feature-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="examination-feature-title">Proctoring</div>
                <div class="examination-feature-description">
                    Monitor exams in real-time with advanced proctoring features and security measures.
                </div>
            </a>
            
            <a href="<?php echo e(route('examination.results.index')); ?>" class="examination-feature-card">
                <div class="examination-feature-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="examination-feature-title">Results & Analytics</div>
                <div class="examination-feature-description">
                    Generate detailed reports, analyze performance, and provide comprehensive feedback.
                </div>
            </a>
        </div>

        <div class="examination-action-buttons">
            <a href="<?php echo e(route('examination.dashboard')); ?>" class="examination-action-btn primary">
                <i class="fas fa-tachometer-alt"></i>
                Go to Dashboard
            </a>
            <a href="<?php echo e(route('examination.questions.index')); ?>" class="examination-action-btn secondary">
                <i class="fas fa-question-circle"></i>
                Manage Questions
            </a>
            <a href="<?php echo e(route('examination.schedules.index')); ?>" class="examination-action-btn secondary">
                <i class="fas fa-calendar-alt"></i>
                View Schedules
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('examination::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Examination/resources/views/index.blade.php ENDPATH**/ ?>