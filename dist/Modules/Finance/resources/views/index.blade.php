@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="dashboard-section-title mb-0">Finance Dashboard</h2>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="refreshDashboard()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <a href="{{ route('finance.settings.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-cog"></i> Settings
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card stat-card glassmorphism text-center p-3">
                <span class="stat-icon mb-1"><i class="fas fa-coins text-warning"></i></span>
                <div class="fs-3 fw-bold">{{ number_format($stats['total_fees_collected'], 2) }}</div>
                <div class="fw-semibold small">Total Fees Collected (This Month)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card glassmorphism text-center p-3">
                <span class="stat-icon mb-1"><i class="fas fa-file-invoice-dollar text-info"></i></span>
                <div class="fs-3 fw-bold">{{ number_format($stats['outstanding_balances'], 2) }}</div>
                <div class="fw-semibold small">Outstanding Balances</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card glassmorphism text-center p-3">
                <span class="stat-icon mb-1"><i class="fas fa-user-graduate text-success"></i></span>
                <div class="fs-3 fw-bold">{{ $stats['active_students'] }}</div>
                <div class="fw-semibold small">Active Students</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card glassmorphism text-center p-3">
                <span class="stat-icon mb-1"><i class="fas fa-university text-primary"></i></span>
                <div class="fs-3 fw-bold">{{ $stats['bank_accounts'] }}</div>
                <div class="fw-semibold small">Bank Accounts</div>
            </div>
        </div>
    </div>
    <!-- Main Finance Modules -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">Finance Management</h4>
        </div>
    </div>
    
    <div class="row mb-4 g-3">
        <div class="col-md-2">
            <a href="{{ route('finance.fees.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-layer-group text-primary"></i></span>
                <div class="fw-bold mb-1">Fee Structures</div>
                <small class="text-muted">{{ $stats['total_fees'] }} fees</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.billing.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-file-invoice text-warning"></i></span>
                <div class="fw-bold mb-1">Billing & Invoices</div>
                <small class="text-muted">Manage billing</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.payments.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-cash-register text-success"></i></span>
                <div class="fw-bold mb-1">Payments</div>
                <small class="text-muted">{{ $stats['pending_payments'] }} pending</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.receipts.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-receipt text-info"></i></span>
                <div class="fw-bold mb-1">Receipts</div>
                <small class="text-muted">Generate receipts</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.reports.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-chart-line text-secondary"></i></span>
                <div class="fw-bold mb-1">Reports</div>
                <small class="text-muted">Financial reports</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.settings.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-cogs text-dark"></i></span>
                <div class="fw-bold mb-1">Settings</div>
                <small class="text-muted">Configure finance</small>
            </a>
        </div>
    </div>
    <!-- Banking & Advanced Features -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">Banking & Advanced Features</h4>
        </div>
    </div>
    
    <div class="row mb-4 g-3">
        <div class="col-md-2">
            <a href="{{ route('finance.bank-reconciliation.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-random text-primary"></i></span>
                <div class="fw-bold mb-1">Bank Reconciliation</div>
                <small class="text-muted">Reconcile accounts</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.banks.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-university text-info"></i></span>
                <div class="fw-bold mb-1">Multi-bank</div>
                <small class="text-muted">{{ $stats['bank_accounts'] }} accounts</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.gl.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-book text-secondary"></i></span>
                <div class="fw-bold mb-1">General Ledger</div>
                <small class="text-muted">GL entries</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.online-payments.mpesa', ['invoice' => 1]) }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-mobile-alt text-success"></i></span>
                <div class="fw-bold mb-1">Online Payments</div>
                <small class="text-muted">MPesa & PayPal</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.forecasting.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-chart-pie text-warning"></i></span>
                <div class="fw-bold mb-1">Forecasting</div>
                <small class="text-muted">Budget planning</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.taxes.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-percentage text-danger"></i></span>
                <div class="fw-bold mb-1">Tax Management</div>
                <small class="text-muted">{{ $stats['total_taxes'] }} taxes</small>
            </a>
        </div>
    </div>

    <!-- Quick Actions & Configuration -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">Quick Actions & Configuration</h4>
        </div>
    </div>
    
    <div class="row mb-4 g-3">
        <div class="col-md-2">
            <a href="{{ route('finance.fee-categories.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-tags text-primary"></i></span>
                <div class="fw-bold mb-1">Fee Categories</div>
                <small class="text-muted">{{ $stats['fee_categories'] }} categories</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.fee-types.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-list text-info"></i></span>
                <div class="fw-bold mb-1">Fee Types</div>
                <small class="text-muted">{{ $stats['fee_types'] }} types</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.roles.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-user-shield text-primary"></i></span>
                <div class="fw-bold mb-1">Roles & Permissions</div>
                <small class="text-muted">Access control</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.ledger.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-book-open text-success"></i></span>
                <div class="fw-bold mb-1">Ledger</div>
                <small class="text-muted">Account ledger</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.online-payments.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-credit-card text-warning"></i></span>
                <div class="fw-bold mb-1">Payment Gateway</div>
                <small class="text-muted">Gateway settings</small>
            </a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('finance.settings.index') }}" class="card module-card glassmorphism text-center p-3 text-decoration-none h-100 d-flex flex-column align-items-center justify-content-center">
                <span class="stat-icon mb-1"><i class="fas fa-sliders-h text-dark"></i></span>
                <div class="fw-bold mb-1">Finance Settings</div>
                <small class="text-muted">Configure system</small>
            </a>
        </div>
    </div>

    <!-- Recent Activity & Quick Overview -->
    <div class="row">
        <div class="col-md-6">
            <div class="card glassmorphism">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock text-primary"></i> Recent Payments</h5>
                </div>
                <div class="card-body">
                    @if($recentPayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->student->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No recent payments</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card glassmorphism">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle text-warning"></i> Outstanding Fees</h5>
                </div>
                <div class="card-body">
                    @if($outstandingFees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($outstandingFees->take(5) as $fee)
                                    <tr>
                                        <td>{{ $fee->student->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($fee->amount, 2) }}</td>
                                        <td>{{ $fee->due_date ? $fee->due_date->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No outstanding fees</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshDashboard() {
    location.reload();
}

// Auto-refresh dashboard every 5 minutes
setTimeout(function() {
    refreshDashboard();
}, 300000);
</script>
@endsection 