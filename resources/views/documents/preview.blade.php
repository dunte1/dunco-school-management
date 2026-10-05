<!DOCTYPE html>
@extends('layouts.app')

@section('title', 'Document Template Preview')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-eye text-primary me-2"></i>
                Template Preview: {{ ucwords(str_replace('_', ' ', $documentType)) }}
            </h1>
            <p class="text-muted">Preview of {{ ucwords(str_replace('_', ' ', $documentType)) }} template</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Print Preview
            </button>
            <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Documents
            </a>
        </div>
    </div>

    <!-- Template Preview -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-file-alt me-2"></i>
                {{ ucwords(str_replace('_', ' ', $documentType)) }} Template
            </h6>
        </div>
        <div class="card-body">
            @if($sampleData)
                <!-- Show actual document template -->
                @switch($documentType)
                    @case('student_id_card')
                        @if($sampleData)
                            @include('documents.student_id_card', ['student' => $sampleData, 'school' => $school, 'standards' => $standards ?? [], 'watermark' => $watermark ?? null, 'qr_code' => $qr_code ?? null, 'barcode' => $barcode ?? null])
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h5 class="text-warning">No Student Data Available</h5>
                                <p class="text-muted">No student data found for student ID card preview.</p>
                            </div>
                        @endif
                        @break
                    @case('staff_id_card')
                        @if($sampleData)
                            @include('documents.staff_id_card', ['staff' => $sampleData, 'school' => $school, 'standards' => $standards ?? [], 'watermark' => $watermark ?? null, 'qr_code' => $qr_code ?? null, 'barcode' => $barcode ?? null])
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h5 class="text-warning">No Staff Data Available</h5>
                                <p class="text-muted">No staff data found for staff ID card preview.</p>
                            </div>
                        @endif
                        @break
                    @case('result_slip')
                        @if($sampleData)
                            @php
                                // Ensure student relationship is loaded
                                if (!$sampleData->relationLoaded('student')) {
                                    $sampleData->load('student');
                                }
                                $student = $sampleData->student;
                            @endphp
                            @if($student)
                                @include('documents.result_slip', ['result' => $sampleData, 'student' => $student, 'school' => $school, 'standards' => $standards ?? [], 'watermark' => $watermark ?? null, 'qr_code' => $qr_code ?? null])
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                    <h5 class="text-warning">No Student Data Available</h5>
                                    <p class="text-muted">No student data found for result slip preview.</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h5 class="text-warning">No Sample Data Available</h5>
                                <p class="text-muted">No sample data found for result slip preview.</p>
                            </div>
                        @endif
                        @break
                    @case('exam_card')
                        @if($sampleData)
                            @include('documents.exam_card', ['student' => $sampleData, 'exam' => $exam ?? null, 'school' => $school, 'standards' => $standards ?? [], 'watermark' => $watermark ?? null, 'qr_code' => $qr_code ?? null, 'timetable' => $timetable ?? collect()])
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h5 class="text-warning">No Student Data Available</h5>
                                <p class="text-muted">No student data found for exam card preview.</p>
                            </div>
                        @endif
                        @break
                    @case('library_card')
                        @if($sampleData)
                            @include('documents.library_card', ['user' => $sampleData, 'school' => $school, 'standards' => $standards ?? [], 'watermark' => $watermark ?? null, 'qr_code' => $qr_code ?? null, 'barcode' => $barcode ?? null])
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h5 class="text-warning">No User Data Available</h5>
                                <p class="text-muted">No user data found for library card preview.</p>
                            </div>
                        @endif
                        @break
                    @case('fee_receipt')
                        @if($sampleData)
                            @php
                                // Ensure student relationship is loaded
                                if (!$sampleData->relationLoaded('student')) {
                                    $sampleData->load('student');
                                }
                                $student = $sampleData->student;
                            @endphp
                            @if($student)
                                @include('documents.fee_receipt', ['payment' => $sampleData, 'student' => $student, 'school' => $school, 'standards' => $standards ?? [], 'watermark' => $watermark ?? null, 'qr_code' => $qr_code ?? null])
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                    <h5 class="text-warning">No Student Data Available</h5>
                                    <p class="text-muted">No student data found for fee receipt preview.</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h5 class="text-warning">No Sample Data Available</h5>
                                <p class="text-muted">No sample data found for fee receipt preview.</p>
                            </div>
                        @endif
                        @break
                    @default
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Template Preview Not Available</h5>
                            <p class="text-muted">The template for {{ ucwords(str_replace('_', ' ', $documentType)) }} is not available for preview.</p>
                        </div>
                @endswitch
            @else
                <!-- Show placeholder template -->
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Sample Data Not Available</h5>
                    <p class="text-muted">No sample data available for {{ ucwords(str_replace('_', ' ', $documentType)) }} preview.</p>
                    <div class="mt-3">
                        <a href="{{ route('documents.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Documents
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
@media print {
    .container-fluid > div:not(.card),
    .btn,
    .card-header {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .card-body {
        padding: 0 !important;
    }
}
</style>

<script>
// Fix for potential null reference errors
document.addEventListener('DOMContentLoaded', function() {
    // Ensure all elements exist before accessing their properties
    const elements = document.querySelectorAll('[style]');
    elements.forEach(function(element) {
        if (element && element.style) {
            // Element exists and has style property
        }
    });
    
    // Fix for any potential null references in the preview
    const printButton = document.querySelector('button[onclick="window.print()"]');
    if (printButton) {
        printButton.addEventListener('click', function(e) {
            e.preventDefault();
            if (typeof window.print === 'function') {
                window.print();
            }
        });
    }
});
</script>
@endsection
