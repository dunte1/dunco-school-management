@extends('layouts.app')

@section('title', 'Financial Documents')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-money-bill-wave text-primary me-2"></i>
                Financial Documents
            </h1>
            <p class="text-muted">Generate fee receipts and financial reports</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="bulkGenerateReceipts()">
                <i class="fas fa-download me-1"></i> Bulk Generate Receipts
            </button>
            <button class="btn btn-outline-info" onclick="previewFinanceTemplates()">
                <i class="fas fa-eye me-1"></i> Preview Templates
            </button>
        </div>
    </div>

    <!-- Fee Receipts Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-receipt me-2"></i>
                Fee Receipts
            </h6>
            <span class="badge bg-light text-dark">{{ $payments->count() }} Payments Available</span>
        </div>
        <div class="card-body">
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Payment Details</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments->take(15) as $payment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($payment->student && $payment->student->passport)
                                            <img src="{{ asset('storage/' . $payment->student->passport) }}" 
                                                 class="rounded-circle me-2" 
                                                 width="32" height="32" 
                                                 alt="Student Photo">
                                        @else
                                            <div class="rounded-circle bg-secondary me-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 32px; height: 32px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $payment->student->name ?? $payment->payer_name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $payment->student->admission_number ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ $payment->payment_type ?? 'Fee Payment' }}</div>
                                    <small class="text-muted">{{ $payment->description ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-success">{{ number_format($payment->amount, 2) }}</div>
                                    <small class="text-muted">{{ $payment->currency ?? 'USD' }}</small>
                                </td>
                                <td>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    @if($payment->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($payment->status ?? 'Unknown') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('documents.fee.receipt', $payment->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Generate Receipt">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-info" 
                                                onclick="previewFeeReceipt({{ $payment->id }})" 
                                                title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" 
                                                onclick="generateInvoice({{ $payment->id }})" 
                                                title="Generate Invoice">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($payments->count() > 15)
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-outline-primary">View All {{ $payments->count() }} Payments</a>
                    </div>
                @endif
            @else
                <div class="text-center py-4">
                    <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Payments Available</h5>
                    <p class="text-muted">No fee payments have been recorded yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Financial Reports Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-chart-line me-2"></i>
                Financial Reports
            </h6>
            <span class="badge bg-light text-dark">Generate Financial Reports</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-file-invoice-dollar fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Invoice</h5>
                            <p class="card-text">Generate professional invoices for fee payments and services.</p>
                            <button class="btn btn-primary" onclick="showInvoiceModal()">
                                <i class="fas fa-plus me-1"></i> Generate Invoice
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-bar fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Financial Statement</h5>
                            <p class="card-text">Create comprehensive financial statements and reports.</p>
                            <button class="btn btn-success" onclick="showStatementModal()">
                                <i class="fas fa-plus me-1"></i> Generate Statement
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-info">
                        <div class="card-body text-center">
                            <i class="fas fa-calculator fa-3x text-info mb-3"></i>
                            <h5 class="card-title">Fee Structure</h5>
                            <p class="card-text">Generate fee structure documents for different classes and programs.</p>
                            <button class="btn btn-info" onclick="showFeeStructureModal()">
                                <i class="fas fa-plus me-1"></i> Generate Structure
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Payments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $payments->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Amount
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($payments->sum('amount'), 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Receipts Generated
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Reports Generated
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Generation Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Generate Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="invoiceForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="client_name" class="form-label">Client Name</label>
                                <input type="text" class="form-control" id="client_name" name="client_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="invoice_number" class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="INV-{{ strtoupper(uniqid()) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="invoice_date" class="form-label">Invoice Date</label>
                                <input type="date" class="form-control" id="invoice_date" name="invoice_date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="service_description" class="form-label">Service Description</label>
                        <textarea class="form-control" id="service_description" name="service_description" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="currency" class="form-label">Currency</label>
                                <select class="form-select" id="currency" name="currency" required>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                    <option value="KES">KES</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="generateInvoiceFromForm()">
                    <i class="fas fa-download me-1"></i> Generate Invoice
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Actions -->
<script>
function bulkGenerateReceipts() {
    // Navigate to bulk generation with receipts selected
    const modal = document.getElementById('bulkGenerateModal');
    if (modal) {
        document.getElementById('bulkDocumentType').value = 'fee_receipts';
        document.getElementById('bulkSelection').value = 'all_payments';
        var bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
    } else {
        // Fallback: redirect to dashboard with bulk generation
        window.location.href = '{{ route("documents.index") }}';
    }
}

function previewFinanceTemplates() {
    // Open template preview in new window
    window.open('{{ route("documents.templates.preview", "fee_receipt") }}', '_blank');
}

function previewFeeReceipt(paymentId) {
    // Implement fee receipt preview
    window.open(`{{ route('documents.templates.preview', 'fee_receipt') }}?payment_id=${paymentId}`, '_blank');
}

function generateInvoice(paymentId) {
    // Show invoice modal
    showInvoiceModal();
}

function showInvoiceModal() {
    // Show invoice modal
    var modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
    modal.show();
}

function showStatementModal() {
    // Show financial statement modal
    var modal = new bootstrap.Modal(document.getElementById('statementModal'));
    modal.show();
}

function showFeeStructureModal() {
    // Show fee structure modal
    var modal = new bootstrap.Modal(document.getElementById('feeStructureModal'));
    modal.show();
}

function generateInvoiceFromForm() {
    // Generate invoice from form data
    var form = document.getElementById('invoiceForm');
    var formData = new FormData(form);
    
    // For now, show a success message
    alert('Invoice generation feature will be implemented soon!');
    
    // Close the modal
    var modal = bootstrap.Modal.getInstance(document.getElementById('invoiceModal'));
    if (modal) {
        modal.hide();
    }
}
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.card-header {
    border-bottom: none;
}
</style>
@endsection
