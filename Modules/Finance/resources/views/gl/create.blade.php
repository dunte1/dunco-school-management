@extends('layouts.app')

@section('title', 'Create General Ledger Entry - Finance Module')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Create General Ledger Entry</h1>
            <p class="text-muted mb-0">Add new entries to the general ledger</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.gl.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to General Ledger
            </a>
        </div>
    </div>

    <!-- Coming Soon Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle p-4 mx-auto" style="width: fit-content;">
                            <i class="fas fa-book text-success fa-3x"></i>
                        </div>
                    </div>
                    <h3 class="h4 mb-3">General Ledger Entry Creation</h3>
                    <p class="text-muted mb-4">
                        Advanced general ledger entry creation with double-entry bookkeeping 
                        validation, account mapping, and automated posting is coming soon.
                    </p>
                    <div class="row text-start">
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Features:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Double-entry validation</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Account code lookup</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Automatic balancing</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Reference tracking</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3">Integration:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Payment integration</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Invoice posting</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Bank reconciliation</li>
                                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Audit trail</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="badge bg-success fs-6 px-3 py-2">Coming Soon</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
