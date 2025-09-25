@extends('layouts.app')

@section('title', 'Finance Dashboard - Dunco School Management System')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Finance Dashboard</h1>
            <p class="text-muted mb-0">Manage all financial operations and transactions</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" onclick="location.reload()">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
            <a href="{{ route('finance.settings.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-cog me-2"></i>Settings
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-coins text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">Total Fees Collected</h6>
                            <h4 class="mb-0">KES {{ number_format($stats['total_fees_collected'] ?? 0, 2) }}</h4>
                            <small class="text-muted">This Month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-file-invoice-dollar text-warning fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">Outstanding Balances</h6>
                            <h4 class="mb-0">KES {{ number_format($stats['outstanding_balances'] ?? 0, 2) }}</h4>
                            <small class="text-muted">Pending Payments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-graduation-cap text-success fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">Active Students</h6>
                            <h4 class="mb-0">{{ number_format($stats['active_students'] ?? 0) }}</h4>
                            <small class="text-muted">Currently Enrolled</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-university text-info fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">Bank Accounts</h6>
                            <h4 class="mb-0">{{ number_format($stats['bank_accounts'] ?? 0) }}</h4>
                            <small class="text-muted">Configured Accounts</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Finance Management Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="h5 mb-3">Finance Management</h3>
        </div>
        
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: fit-content;">
                        <i class="fas fa-layer-group text-primary fa-2x"></i>
                    </div>
                    <h6 class="card-title">Fee Structures</h6>
                    <p class="text-muted small mb-0">{{ $stats['total_fees'] ?? 0 }} fees</p>
                    <a href="{{ route('finance.fees.index') }}" class="stretched-link"></a>
        </div>
    </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: fit-content;">
                        <i class="fas fa-file-invoice-dollar text-success fa-2x"></i>
                    </div>
                    <h6 class="card-title">Billing & Invoices</h6>
                    <p class="text-muted small mb-0">Manage billing</p>
                    <a href="{{ route('finance.billing.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: fit-content;">
                        <i class="fas fa-money-bill-wave text-warning fa-2x"></i>
                    </div>
                    <h6 class="card-title">Payments</h6>
                    <p class="text-muted small mb-0">{{ $stats['pending_payments'] ?? 0 }} pending</p>
                    <a href="{{ route('finance.payments.index') }}" class="stretched-link"></a>
                </div>
        </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: fit-content;">
                        <i class="fas fa-receipt text-info fa-2x"></i>
        </div>
                    <h6 class="card-title">Receipts</h6>
                    <p class="text-muted small mb-0">Generate receipts</p>
                    <a href="{{ route('finance.receipts.index') }}" class="stretched-link"></a>
        </div>
    </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-secondary bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: fit-content;">
                        <i class="fas fa-chart-line text-secondary fa-2x"></i>
                    </div>
                    <h6 class="card-title">Reports</h6>
                    <p class="text-muted small mb-0">Financial reports</p>
                    <a href="{{ route('finance.reports.index') }}" class="stretched-link"></a>
    </div>
        </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-dark bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: fit-content;">
                        <i class="fas fa-cogs text-dark fa-2x"></i>
        </div>
                    <h6 class="card-title">Settings</h6>
                    <p class="text-muted small mb-0">Configure finance</p>
                    <a href="{{ route('finance.settings.index') }}" class="stretched-link"></a>
        </div>
        </div>
        </div>
    </div>

    <!-- Banking & Advanced Features Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="h5 mb-3">Banking & Advanced Features</h3>
        </div>
        
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: fit-content;">
                        <i class="fas fa-university text-primary fa-2x"></i>
                    </div>
                    <h6 class="card-title">Bank Accounts</h6>
                    <p class="text-muted small mb-0">Manage accounts</p>
                    <a href="{{ route('finance.banks.index') }}" class="stretched-link"></a>
    </div>
        </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: fit-content;">
                        <i class="fas fa-exchange-alt text-success fa-2x"></i>
        </div>
                    <h6 class="card-title">Bank Reconciliation</h6>
                    <p class="text-muted small mb-0">Match transactions</p>
                    <a href="{{ route('finance.bank-reconciliation.index') }}" class="stretched-link"></a>
        </div>
        </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: fit-content;">
                        <i class="fas fa-book text-warning fa-2x"></i>
    </div>
                    <h6 class="card-title">General Ledger</h6>
                    <p class="text-muted small mb-0">View entries</p>
                    <a href="{{ route('finance.gl.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: fit-content;">
                        <i class="fas fa-chart-bar text-info fa-2x"></i>
                        </div>
                    <h6 class="card-title">Forecasting</h6>
                    <p class="text-muted small mb-0">Financial planning</p>
                    <a href="{{ route('finance.forecasting.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-secondary bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: fit-content;">
                        <i class="fas fa-credit-card text-secondary fa-2x"></i>
                    </div>
                    <h6 class="card-title">Online Payments</h6>
                    <p class="text-muted small mb-0">Payment gateways</p>
                    <a href="{{ route('finance.online-payments.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm h-100 text-decoration-none">
                <div class="card-body text-center">
                    <div class="bg-dark bg-opacity-10 rounded-circle p-3 mx-auto mb-3" style="width: fit-content;">
                        <i class="fas fa-user-tag text-dark fa-2x"></i>
                        </div>
                    <h6 class="card-title">Finance Roles</h6>
                    <p class="text-muted small mb-0">Manage permissions</p>
                    <a href="{{ route('finance.roles.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 