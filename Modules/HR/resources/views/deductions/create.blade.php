@extends('layouts.app')

@section('title', 'Create New Deduction')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create New Deduction</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('hr.deductions.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Deduction Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Deduction Type <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                        <option value="">Select Deduction Type</option>
                                        <option value="tax" {{ old('type') == 'tax' ? 'selected' : '' }}>Tax</option>
                                        <option value="insurance" {{ old('type') == 'insurance' ? 'selected' : '' }}>Insurance</option>
                                        <option value="loan" {{ old('type') == 'loan' ? 'selected' : '' }}>Loan</option>
                                        <option value="advance" {{ old('type') == 'advance' ? 'selected' : '' }}>Advance</option>
                                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" 
                                           value="{{ old('amount') }}" step="0.01" min="0" placeholder="Enter amount">
                                    <div class="form-text">Leave empty for percentage-based deductions</div>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="percentage" class="form-label">Percentage</label>
                                    <input type="number" name="percentage" id="percentage" class="form-control @error('percentage') is-invalid @enderror" 
                                           value="{{ old('percentage') }}" step="0.01" min="0" max="100" placeholder="Enter percentage">
                                    <div class="form-text">Leave empty for fixed amount deductions</div>
                                    @error('percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" 
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label for="is_active" class="form-check-label">Active Deduction</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                              rows="4" placeholder="Describe the deduction details...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('hr.deductions.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Deduction</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const amountInput = document.getElementById('amount');
    const percentageInput = document.getElementById('percentage');
    
    function toggleInputs() {
        const type = typeSelect.value;
        
        if (type === 'percentage') {
            amountInput.disabled = true;
            amountInput.value = '';
            percentageInput.disabled = false;
        } else if (type === 'tax' || type === 'insurance' || type === 'loan' || type === 'advance') {
            amountInput.disabled = false;
            percentageInput.disabled = true;
            percentageInput.value = '';
        } else {
            amountInput.disabled = false;
            percentageInput.disabled = false;
        }
    }
    
    typeSelect.addEventListener('change', toggleInputs);
    toggleInputs(); // Initialize on page load
});
</script>
@endsection
