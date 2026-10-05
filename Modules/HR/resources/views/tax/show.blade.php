@extends('layouts.app')

@section('title', 'Tax Rate Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Tax Rate Details</h4>
                    <div class="btn-group">
                        <a href="{{ route('hr.tax.edit', $tax->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('hr.tax.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Tax Name:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <h5 class="mb-0">{{ $tax->name }}</h5>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Tax Rate:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-primary fs-6">{{ $tax->formatted_rate }}</span>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Amount Range:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-info fs-6">{{ $tax->formatted_range }}</span>
                                </div>
                            </div>
                            
                            @if($tax->fixed_amount > 0)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Fixed Amount:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-success fs-6">{{ number_format($tax->fixed_amount, 2) }} {{ config('app.currency_symbol', 'KSh') }}</span>
                                </div>
                            </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-{{ $tax->is_active ? 'success' : 'secondary' }} fs-6">
                                        {{ $tax->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($tax->description)
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Description:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <div class="border rounded p-3 bg-light">
                                        {{ $tax->description }}
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Created:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $tax->created_at->format('F d, Y \a\t g:i A') }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Last Updated:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $tax->updated_at->format('F d, Y \a\t g:i A') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Quick Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('hr.tax.edit', $tax->id) }}" class="btn btn-warning">
                                            <i class="fas fa-edit me-2"></i>Edit Tax Rate
                                        </a>
                                        <a href="{{ route('hr.tax.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Create New Tax Rate
                                        </a>
                                        <a href="{{ route('hr.tax.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-list me-2"></i>View All Tax Rates
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Tax Calculation Example</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="example_amount" class="form-label">Test Amount</label>
                                        <input type="number" id="example_amount" class="form-control" placeholder="Enter amount to test" step="0.01" min="0">
                                    </div>
                                    <div id="tax_calculation" class="text-center text-muted">
                                        Enter an amount to see tax calculation
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const exampleAmountInput = document.getElementById('example_amount');
    const taxCalculationDiv = document.getElementById('tax_calculation');
    const taxRate = {{ $tax->rate }};
    const fixedAmount = {{ $tax->fixed_amount }};
    const minAmount = {{ $tax->min_amount }};
    const maxAmount = {{ $tax->max_amount || 'null' }};
    const isActive = {{ $tax->is_active ? 'true' : 'false' }};
    
    function calculateTax() {
        const amount = parseFloat(exampleAmountInput.value) || 0;
        
        if (amount === 0) {
            taxCalculationDiv.innerHTML = '<span class="text-muted">Enter an amount to see tax calculation</span>';
            return;
        }
        
        if (!isActive) {
            taxCalculationDiv.innerHTML = '<span class="text-warning">This tax rate is inactive</span>';
            return;
        }
        
        // Check if amount is within the tax bracket
        if (minAmount > 0 && amount < minAmount) {
            taxCalculationDiv.innerHTML = '<span class="text-info">Amount below minimum threshold ({{ number_format(minAmount, 2) }})</span>';
            return;
        }
        
        if (maxAmount && amount > maxAmount) {
            taxCalculationDiv.innerHTML = '<span class="text-info">Amount above maximum threshold ({{ number_format(maxAmount, 2) }})</span>';
            return;
        }
        
        const percentageTax = (amount * taxRate) / 100;
        const totalTax = percentageTax + fixedAmount;
        
        taxCalculationDiv.innerHTML = `
            <div class="row text-center">
                <div class="col-6">
                    <small class="text-muted">Percentage Tax</small><br>
                    <strong>{{ number_format(percentageTax, 2) }} {{ config('app.currency_symbol', 'KSh') }}</strong>
                </div>
                <div class="col-6">
                    <small class="text-muted">Total Tax</small><br>
                    <strong class="text-primary">{{ number_format(totalTax, 2) }} {{ config('app.currency_symbol', 'KSh') }}</strong>
                </div>
            </div>
        `;
    }
    
    exampleAmountInput.addEventListener('input', calculateTax);
});
</script>
@endsection
