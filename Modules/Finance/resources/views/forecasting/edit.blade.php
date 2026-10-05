@extends('layouts.app')

@section('title', 'Edit Budget Forecast - Finance Module')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Edit Budget Forecast</h1>
            <p class="text-muted mb-0">Modify existing budget forecasts</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.forecasting.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Forecasting
            </a>
        </div>
    </div>

    <!-- Coming Soon Card -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-4 mx-auto" style="width: fit-content;">
                            <i class="fas fa-edit text-warning fa-3x"></i>
                        </div>
                    </div>
                    <h3 class="h4 mb-3">Edit Budget Forecast</h3>
                    <p class="text-muted mb-4">
                        Budget editing functionality is currently under development. 
                        This feature will allow you to modify existing forecasts, 
                        update budget allocations, and track changes over time.
                    </p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Budget editing features will be available in the next update.
                    </div>
                    <div class="mt-4">
                        <span class="badge bg-warning fs-6 px-3 py-2">In Development</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
