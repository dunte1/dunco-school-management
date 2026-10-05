@extends('layouts.app')

@section('title', 'Arrears Aging Report - Finance Module')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 text-primary">
                <i class="fas fa-clock me-2"></i>Arrears Aging Report
            </h1>
            <p class="text-muted mb-0">Track and analyze overdue payments by aging categories</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter me-2"></i>Filter
            </button>
            <a href="{{ route('finance.reports.aging', ['export' => 'csv']) }}" class="btn btn-outline-success">
                <i class="fas fa-file-csv me-2"></i>Export CSV
            </a>
            <button class="btn btn-primary" onclick="printReport()">
                <i class="fas fa-print me-2"></i>Print Report
            </button>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle text-success fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">0 - 30 Days</h6>
                            <h4 class="mb-0 text-success">{{ number_format($buckets['0_30'] ?? 0, 2) }} KES</h4>
                            <small class="text-muted">{{ $overdue->where('days_past_due', '<=', 30)->count() }} invoices</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">31 - 60 Days</h6>
                            <h4 class="mb-0 text-warning">{{ number_format($buckets['31_60'] ?? 0, 2) }} KES</h4>
                            <small class="text-muted">{{ $overdue->whereBetween('days_past_due', [31, 60])->count() }} invoices</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-exclamation-circle text-danger fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">61 - 90 Days</h6>
                            <h4 class="mb-0 text-danger">{{ number_format($buckets['61_90'] ?? 0, 2) }} KES</h4>
                            <small class="text-muted">{{ $overdue->whereBetween('days_past_due', [61, 90])->count() }} invoices</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-dark bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-times-circle text-dark fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">90+ Days</h6>
                            <h4 class="mb-0 text-dark">{{ number_format($buckets['90_plus'] ?? 0, 2) }} KES</h4>
                            <small class="text-muted">{{ $overdue->where('days_past_due', '>', 90)->count() }} invoices</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aging Chart -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Aging Analysis
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="agingChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-pie-chart me-2 text-primary"></i>Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="distributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Overdue Invoices -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2 text-primary"></i>Top Overdue Invoices
                    </h5>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-2 justify-content-md-end">
                        <div class="input-group" style="max-width: 300px;">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Search invoices..." id="searchInput">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="invoicesTable">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 py-3 px-4">Invoice Details</th>
                            <th class="border-0 py-3 px-4">Student</th>
                            <th class="border-0 py-3 px-4">Due Date</th>
                            <th class="border-0 py-3 px-4">Outstanding Amount</th>
                            <th class="border-0 py-3 px-4">Days Past Due</th>
                            <th class="border-0 py-3 px-4">Status</th>
                            <th class="border-0 py-3 px-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overdue as $row)
                        <tr class="invoice-row" data-invoice-id="{{ $row['id'] }}">
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-file-invoice text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-primary">#{{ $row['id'] }}</div>
                                        <small class="text-muted">Invoice</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                        <i class="fas fa-user text-success"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $row['student_id'] }}</div>
                                        <small class="text-muted">Student ID</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($row['due_date'])->format('d M Y') }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($row['due_date'])->format('h:i A') }}</small>
                            </td>
                            <td class="py-3 px-4">
                                <div class="fw-bold text-danger">{{ number_format($row['outstanding'], 2) }} KES</div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="badge bg-{{ $row['days_past_due'] <= 30 ? 'success' : ($row['days_past_due'] <= 60 ? 'warning' : ($row['days_past_due'] <= 90 ? 'danger' : 'dark')) }}">
                                    {{ $row['days_past_due'] }} days
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="badge bg-danger">Overdue</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" title="View Details" onclick="viewInvoice('{{ $row['id'] }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-success" title="Send Reminder" onclick="sendReminder('{{ $row['id'] }}')">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" title="Record Payment" onclick="recordPayment('{{ $row['id'] }}')">
                                        <i class="fas fa-credit-card"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                                    <h5>No overdue invoices</h5>
                                    <p>All invoices are up to date!</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Aging Categories Breakdown -->
    <div class="row mt-4">
        @foreach(['current' => '0-30 days', 'over30' => '31-60 days', 'over60' => '61-90 days', 'over90' => '> 90 days'] as $key => $label)
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-{{ $key === 'current' ? 'success' : ($key === 'over30' ? 'warning' : ($key === 'over60' ? 'danger' : 'dark')) }} text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-{{ $key === 'current' ? 'check-circle' : ($key === 'over30' ? 'exclamation-triangle' : ($key === 'over60' ? 'exclamation-circle' : 'times-circle')) }} me-2"></i>
                        {{ $label }}
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0 py-2 px-3">Student</th>
                                    <th class="border-0 py-2 px-3">Invoice</th>
                                    <th class="border-0 py-2 px-3">Due Date</th>
                                    <th class="border-0 py-2 px-3">Amount</th>
                                    <th class="border-0 py-2 px-3">Paid</th>
                                    <th class="border-0 py-2 px-3">Outstanding</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($out[$key] ?? [] as $inv)
                                <tr>
                                    <td class="py-2 px-3">{{ $inv->student->name ?? ('#'.$inv->student_id) }}</td>
                                    <td class="py-2 px-3">#{{ $inv->id }}</td>
                                    <td class="py-2 px-3">{{ \Carbon\Carbon::parse($inv->due_date)->format('d M Y') }}</td>
                                    <td class="py-2 px-3">{{ number_format($inv->total_amount, 2) }}</td>
                                    <td class="py-2 px-3">{{ number_format($inv->payments->sum('amount'), 2) }}</td>
                                    <td class="py-2 px-3 fw-bold text-danger">{{ number_format($inv->total_amount - $inv->payments->sum('amount'), 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3 text-muted">
                                        <i class="fas fa-check-circle me-2"></i>No invoices in this category
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-filter me-2"></i>Filter Aging Report
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('finance.reports.aging') }}">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Aging Category</label>
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                <option value="0-30" {{ request('category') === '0-30' ? 'selected' : '' }}>0-30 Days</option>
                                <option value="31-60" {{ request('category') === '31-60' ? 'selected' : '' }}>31-60 Days</option>
                                <option value="61-90" {{ request('category') === '61-90' ? 'selected' : '' }}>61-90 Days</option>
                                <option value="90+" {{ request('category') === '90+' ? 'selected' : '' }}>90+ Days</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Minimum Amount</label>
                            <input type="number" name="min_amount" class="form-control" placeholder="0.00" value="{{ request('min_amount') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="{{ route('finance.reports.aging') }}" class="btn btn-outline-warning">Clear Filters</a>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('.invoice-row');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Print report
function printReport() {
    window.print();
}

// View invoice
function viewInvoice(invoiceId) {
    // Implement view invoice logic
    console.log('Viewing invoice:', invoiceId);
}

// Send reminder
function sendReminder(invoiceId) {
    // Implement send reminder logic
    console.log('Sending reminder for invoice:', invoiceId);
}

// Record payment
function recordPayment(invoiceId) {
    // Implement record payment logic
    console.log('Recording payment for invoice:', invoiceId);
}

// Charts
const agingCtx = document.getElementById('agingChart').getContext('2d');
new Chart(agingCtx, {
    type: 'bar',
    data: {
        labels: ['0-30 Days', '31-60 Days', '61-90 Days', '90+ Days'],
        datasets: [{
            label: 'Outstanding Amount (KES)',
            data: [
                {{ $buckets['0_30'] ?? 0 }},
                {{ $buckets['31_60'] ?? 0 }},
                {{ $buckets['61_90'] ?? 0 }},
                {{ $buckets['90_plus'] ?? 0 }}
            ],
            backgroundColor: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(33, 37, 41, 0.8)'
            ],
            borderColor: [
                'rgba(40, 167, 69, 1)',
                'rgba(255, 193, 7, 1)',
                'rgba(220, 53, 69, 1)',
                'rgba(33, 37, 41, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Outstanding Amounts by Aging Category'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'KES ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

const distributionCtx = document.getElementById('distributionChart').getContext('2d');
new Chart(distributionCtx, {
    type: 'doughnut',
    data: {
        labels: ['0-30 Days', '31-60 Days', '61-90 Days', '90+ Days'],
        datasets: [{
            data: [
                {{ $buckets['0_30'] ?? 0 }},
                {{ $buckets['31_60'] ?? 0 }},
                {{ $buckets['61_90'] ?? 0 }},
                {{ $buckets['90_plus'] ?? 0 }}
            ],
            backgroundColor: [
                'rgba(40, 167, 69, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(33, 37, 41, 0.8)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush


