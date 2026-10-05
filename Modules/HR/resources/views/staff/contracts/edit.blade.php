@extends('layouts.app')

@section('title', 'Edit Staff Contract')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Staff Contract</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('hr.staff.contracts.update', $contract->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="staff_id" class="form-label">Staff Member <span class="text-danger">*</span></label>
                                    <select name="staff_id" id="staff_id" class="form-select @error('staff_id') is-invalid @enderror" required>
                                        <option value="">Select Staff Member</option>
                                        @foreach($staff as $staffMember)
                                            <option value="{{ $staffMember->id }}" {{ (old('staff_id', $contract->staff_id) == $staffMember->id) ? 'selected' : '' }}>
                                                {{ $staffMember->first_name }} {{ $staffMember->last_name }} ({{ $staffMember->staff_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('staff_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                                    <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ (old('department_id', $contract->department_id) == $department->id) ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                                    <input type="text" name="position" id="position" class="form-control @error('position') is-invalid @enderror" 
                                           value="{{ old('position', $contract->position) }}" required>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contract_type" class="form-label">Contract Type <span class="text-danger">*</span></label>
                                    <select name="contract_type" id="contract_type" class="form-select @error('contract_type') is-invalid @enderror" required>
                                        <option value="">Select Contract Type</option>
                                        <option value="permanent" {{ old('contract_type', $contract->contract_type) == 'permanent' ? 'selected' : '' }}>Permanent</option>
                                        <option value="temporary" {{ old('contract_type', $contract->contract_type) == 'temporary' ? 'selected' : '' }}>Temporary</option>
                                        <option value="probation" {{ old('contract_type', $contract->contract_type) == 'probation' ? 'selected' : '' }}>Probation</option>
                                        <option value="intern" {{ old('contract_type', $contract->contract_type) == 'intern' ? 'selected' : '' }}>Intern</option>
                                    </select>
                                    @error('contract_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror" 
                                           value="{{ old('start_date', $contract->start_date) }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror" 
                                           value="{{ old('end_date', $contract->end_date) }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="salary" class="form-label">Salary</label>
                                    <input type="number" name="salary" id="salary" class="form-control @error('salary') is-invalid @enderror" 
                                           value="{{ old('salary', $contract->salary) }}" step="0.01" min="0">
                                    @error('salary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status', $contract->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $contract->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="expired" {{ old('status', $contract->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                        <option value="terminated" {{ old('status', $contract->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                    </select>
                                    @error('status')
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
                                              rows="3">{{ old('description', $contract->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="document" class="form-label">Contract Document</label>
                                    <input type="file" name="document" id="document" class="form-control @error('document') is-invalid @enderror" 
                                           accept=".pdf,.doc,.docx">
                                    <div class="form-text">Accepted formats: PDF, DOC, DOCX (Max: 10MB). Leave empty to keep current document.</div>
                                    @if($contract->document_path)
                                        <div class="mt-2">
                                            <small class="text-muted">Current document: {{ basename($contract->document_path) }}</small>
                                        </div>
                                    @endif
                                    @error('document')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('hr.staff.contracts.show', $contract->id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Contract</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
