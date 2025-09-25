<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h2 class="dashboard-section-title mb-4">Finance Dashboard</h2>
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card stat-card glassmorphism text-center p-3">
                <span class="stat-icon mb-1"><i class="fas fa-coins text-warning"></i></span>
                <div class="fs-3 fw-bold">--</div>
                <div class="fw-semibold small">Total Fees Collected</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card glassmorphism text-center p-3">
                <span class="stat-icon mb-1"><i class="fas fa-file-invoice-dollar text-info"></i></span>
                <div class="fs-3 fw-bold">--</div>
                <div class="fw-semibold small">Outstanding Balances</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card glassmorphism text-center p-3">
                <span class="stat-icon mb-1"><i class="fas fa-user-graduate text-success"></i></span>
                <div class="fs-3 fw-bold">--</div>
                <div class="fw-semibold small">Active Students</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card glassmorphism text-center p-3">
                <span class="stat-icon mb-1"><i class="fas fa-university text-primary"></i></span>
                <div class="fs-3 fw-bold">--</div>
                <div class="fw-semibold small">Bank Accounts</div>
            </div>
        </div>
    </div>
    <div class="row mb-4 g-3">
        <div class="col-md-2">
            <a href="<?php echo e(route('finance.fees.index')); ?>" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-layer-group text-primary"></i></span>
                <div class="fw-bold mb-1">Fee Structures</div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?php echo e(route('finance.billing.index')); ?>" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-file-invoice text-warning"></i></span>
                <div class="fw-bold mb-1">Billing & Invoices</div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?php echo e(route('finance.payments.index')); ?>" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-cash-register text-success"></i></span>
                <div class="fw-bold mb-1">Payments</div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?php echo e(route('finance.receipts.index')); ?>" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-receipt text-info"></i></span>
                <div class="fw-bold mb-1">Receipts</div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?php echo e(route('finance.reports.index')); ?>" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-chart-line text-secondary"></i></span>
                <div class="fw-bold mb-1">Reports</div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?php echo e(route('finance.settings.index')); ?>" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-cogs text-dark"></i></span>
                <div class="fw-bold mb-1">Settings</div>
            </a>
        </div>
    </div>
    <div class="row mb-4 g-3">
        <div class="col-md-2">
            <a href="<?php echo e(route('finance.bank-reconciliation.index')); ?>" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-random text-primary"></i></span>
                <div class="fw-bold mb-1">Bank Reconciliation</div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?php echo e(route('finance.banks.index')); ?>" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-university text-info"></i></span>
                <div class="fw-bold mb-1">Multi-bank</div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?php echo e(route('finance.ledger.index')); ?>" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-book text-secondary"></i></span>
                <div class="fw-bold mb-1">General Ledger</div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?php echo e(route('finance.online-payments.mpesa', ['invoice' => 1])); ?>" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-mobile-alt text-success"></i></span>
                <div class="fw-bold mb-1">Online Payments</div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?php echo e(route('finance.forecasting.index')); ?>" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-chart-pie text-warning"></i></span>
                <div class="fw-bold mb-1">Forecasting & Budgeting</div>
            </a>
        </div>
        <div class="col-md-2">
            <a href="<?php echo e(route('finance.taxes.index')); ?>" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-percentage text-danger"></i></span>
                <div class="fw-bold mb-1">Tax Management</div>
            </a>
        </div>
        <div class="col-md-2 mt-3">
            <a href="<?php echo e(route('finance.roles.index')); ?>" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-user-shield text-primary"></i></span>
                <div class="fw-bold mb-1">Roles & Permissions</div>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\resources\views\finance\index.blade.php ENDPATH**/ ?>