<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="mb-4">
        <h1 class="fw-bold text-primary" style="font-size: 2rem; letter-spacing: -0.025em;">Examination Dashboard</h1>
        <p class="text-muted mb-0">Welcome to the Examination Management System</p>
    </div>
    
    <div class="glass-card p-4 mb-4">
        <div class="row g-4">
            <?php if(auth()->check() && auth()->user()->hasRole('student')): ?>
            <div class="col-md-4">
                <a href="<?php echo e(route('examination.student.exams')); ?>" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-book-open fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">Take Exam</h5>
                                    <p class="mb-0 text-muted small">Browse and start available exams</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-4">
                <a href="<?php echo e(route('examination.student.history')); ?>" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-history fa-2x text-info"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">My Attempts</h5>
                                    <p class="mb-0 text-muted small">View your exam attempts</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-4">
                <a href="<?php echo e(route('examination.student.results')); ?>" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-clipboard-check fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">My Results</h5>
                                    <p class="mb-0 text-muted small">See your results and feedback</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>
            
            <?php if(auth()->check() && auth()->user()->hasRole(['admin','teacher'])): ?>
            <div class="col-md-4">
                <a href="<?php echo e(route('examination.questions.index')); ?>" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-question-circle fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">Question Bank</h5>
                                    <p class="mb-0 text-muted small">Manage examination questions</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-4">
                <a href="<?php echo e(route('examination.schedules.index')); ?>" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-calendar-alt fa-2x text-info"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">Exam Schedules</h5>
                                    <p class="mb-0 text-muted small">Manage exam timetables</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-md-4">
                <a href="<?php echo e(route('examination.results.index')); ?>" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-clipboard-list fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark">Results Management</h5>
                                    <p class="mb-0 text-muted small">View and manage all exam results</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="row g-4">
        <div class="col-md-3">
            <div class="glass-card p-4 text-center">
                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                    <i class="fas fa-file-alt fa-2x text-primary"></i>
                </div>
                <h3 class="fw-bold text-primary mb-1">24</h3>
                <p class="text-muted mb-0">Total Exams</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4 text-center">
                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                    <i class="fas fa-users fa-2x text-success"></i>
                </div>
                <h3 class="fw-bold text-success mb-1">156</h3>
                <p class="text-muted mb-0">Active Students</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4 text-center">
                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                    <i class="fas fa-clock fa-2x text-warning"></i>
                </div>
                <h3 class="fw-bold text-warning mb-1">8</h3>
                <p class="text-muted mb-0">Upcoming Exams</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glass-card p-4 text-center">
                <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex p-3 mb-3">
                    <i class="fas fa-chart-line fa-2x text-info"></i>
                </div>
                <h3 class="fw-bold text-info mb-1">87%</h3>
                <p class="text-muted mb-0">Average Score</p>
            </div>
        </div>
    </div>
</div>

<style>
.hover-lift {
    transition: all 0.2s ease;
}

.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg) !important;
}
</style>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('examination::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Examination/resources/views/dashboard.blade.php ENDPATH**/ ?>