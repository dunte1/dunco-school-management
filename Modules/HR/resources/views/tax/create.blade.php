@extends('layouts.app')

@section('title', 'Create New Tax Rate')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create New Tax Rate</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('hr.tax.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tax Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required placeholder="e.g., Income Tax, PAYE, etc.">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rate" class="form-label">Tax Rate (%) <span class="text-danger">*</span></label>
                                    <input type="number" name="rate" id="rate" class="form-control @error('rate') is-invalid @enderror" 
                                           value="{{ old('rate') }}" step="0.01" min="0" max="100" required placeholder="e.g., 15.5">
                                    <div class="form-text">Enter the percentage rate (0-100)</div>
                                    @error('rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fixed_amount" class="form-label">Fixed Amount</label>
                                    <input type="number" name="fixed_amount" id="fixed_amount" class="form-control @error('fixed_amount') is-invalid @enderror" 
                                           value="{{ old('fixed_amount') }}" step="0.01" min="0" placeholder="e.g., 1000">
                                    <div class="form-text">Additional fixed amount (optional)</div>
                                    @error('fixed_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_amount" class="form-label">Minimum Amount</label>
                                    <input type="number" name="min_amount" id="min_amount" class="form-control @error('min_amount') is-invalid @enderror" 
                                           value="{{ old('min_amount') }}" step="0.01" min="0" placeholder="e.g., 10000">
                                    <div class="form-text">Minimum salary amount for this tax bracket</div>
                                    @error('min_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_amount" class="form-label">Maximum Amount</label>
                                    <input type="number" name="max_amount" id="max_amount" class="form-control @error('max_amount') is-invalid @enderror" 
                                           value="{{ old('max_amount') }}" step="0.01" min="0" placeholder="e.g., 50000">
                                    <div class="form-text">Maximum salary amount for this tax bracket (leave empty for unlimited)</div>
                                    @error('max_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                              rows="4" placeholder="Describe the tax rate details...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" 
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label for="is_active" class="form-check-label">Active Tax Rate</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('hr.tax.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Tax Rate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const minAmountInput = document.getElementById('min_amount');
    const maxAmountInput = document.getElementById('max_amount');
    
    // Validate that max amount is greater than min amount
    function validateAmounts() {
        const minAmount = parseFloat(minAmountInput.value) || 0;
        const maxAmount = parseFloat(maxAmountInput.value) || 0;
        
        if (maxAmount > 0 && minAmount > 0 && maxAmount <= minAmount) {
            maxAmountInput.setCustomValidity('Maximum amount must be greater than minimum amount');
        } else {
            maxAmountInput.setCustomValidity('');
        }
    }
    
    minAmountInput.addEventListener('input', validateAmounts);
    maxAmountInput.addEventListener('input', validateAmounts);
});
</script>
@endsection
