@extends('finance::layouts.app')

@section('title', 'Billing & Invoices - Finance Module')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Billing & Invoices</h1>
            <p class="text-muted mb-0">Manage student invoices and billing</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Finance
            </a>
            <a href="{{ route('finance.billing.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create Invoice
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Invoices Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Invoices List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Student</th>
                            <th class="px-4 py-3">Total Amount</th>
                            <th class="px-4 py-3">Due Date</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td class="px-4 py-3">
                                <div>
                                    <strong>{{ $invoice->student->name ?? 'Unknown Student' }}</strong>
                                    @if($invoice->student)
                                        <br><small class="text-muted">ID: {{ $invoice->student->id }}</small>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <strong class="text-success">KES {{ number_format($invoice->total_amount, 2) }}</strong>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge bg-info">{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') : 'No due date' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($invoice->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($invoice->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($invoice->status === 'overdue')
                                    <span class="badge bg-danger">Overdue</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($invoice->status) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('finance.billing.show', $invoice) }}" class="btn btn-sm btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('finance.billing.edit', $invoice) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('finance.billing.destroy', $invoice) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this invoice?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                                    <h5>No Invoices Found</h5>
                                    <p>Start by creating your first invoice.</p>
                                    <a href="{{ route('finance.billing.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Create First Invoice
                                    </a>
                                </div>
                </td>
            </tr>
                        @endforelse
        </tbody>
    </table>
            </div>
        </div>
    </div>
</div>
@endsection 