@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create Leave Type</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('hr.leave-type.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">Leave Type Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="default_days">Default Days <span class="text-danger">*</span></label>
                            <input type="number" 
                                   class="form-control @error('default_days') is-invalid @enderror" 
                                   id="default_days" 
                                   name="default_days" 
                                   value="{{ old('default_days', 0) }}" 
                                   min="0" 
                                   required>
                            @error('default_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Number of days typically allocated for this leave type.</small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">
                                    Active
                                </label>
                            </div>
                            <small class="form-text text-muted">Active leave types will be available for selection when creating leave applications.</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Leave Type
                            </button>
                            <a href="{{ route('hr.leave-type.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
