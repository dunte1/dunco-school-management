@extends('layouts.app')

@section('title', 'Budget Forecast Details - Finance Module')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Budget Forecast Details</h1>
            <p class="text-muted mb-0">View detailed budget forecast information</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.forecasting.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Forecasting
            </a>
            <a href="{{ route('finance.forecasting.edit', 1) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>Edit Forecast
            </a>
        </div>
    </div>

    <!-- Coming Soon Card -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <div class="bg-info bg-opacity-10 rounded-circle p-4 mx-auto" style="width: fit-content;">
                            <i class="fas fa-chart-pie text-info fa-3x"></i>
                        </div>
                    </div>
                    <h3 class="h4 mb-3">Detailed Budget View</h3>
                    <p class="text-muted mb-4">
                        Detailed budget forecast views with interactive charts, 
                        variance analysis, and comprehensive reporting are coming soon.
                    </p>
                    <div class="row text-start">
                        <div class="col-md-4">
                            <h6 class="fw-semibold mb-3">Visual Analytics:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-chart-bar text-primary me-2"></i>Interactive charts</li>
                                <li class="mb-2"><i class="fas fa-chart-line text-primary me-2"></i>Trend analysis</li>
                                <li class="mb-2"><i class="fas fa-chart-pie text-primary me-2"></i>Category breakdown</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-semibold mb-3">Reporting:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-file-pdf text-danger me-2"></i>PDF reports</li>
                                <li class="mb-2"><i class="fas fa-file-excel text-success me-2"></i>Excel export</li>
                                <li class="mb-2"><i class="fas fa-print text-info me-2"></i>Print views</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-semibold mb-3">Analysis:</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-calculator text-warning me-2"></i>Variance calculations</li>
                                <li class="mb-2"><i class="fas fa-percentage text-warning me-2"></i>Performance metrics</li>
                                <li class="mb-2"><i class="fas fa-bullseye text-warning me-2"></i>Goal tracking</li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="badge bg-info fs-6 px-3 py-2">Coming Soon</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
