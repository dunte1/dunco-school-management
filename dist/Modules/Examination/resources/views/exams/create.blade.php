@extends('layouts.app')

@section('title', 'Create Exam')

@section('content')
<div class="container-xl py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius:1.2rem;">
                <div class="card-header bg-white border-0" style="border-radius:1.2rem 1.2rem 0 0;">
                    <h3 class="mb-0 fw-bold" style="color:#1a237e;">Create New Exam</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('examination.exams.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Exam Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">Exam Code</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="academic_year" class="form-label">Academic Year</label>
                            <input type="text" class="form-control" id="academic_year" name="academic_year" required>
                        </div>
                        <div class="mb-3">
                            <label for="term" class="form-label">Term</label>
                            <input type="text" class="form-control" id="term" name="term" required>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="duration_minutes" class="form-label">Duration (minutes)</label>
                            <input type="number" class="form-control" id="duration_minutes" name="duration_minutes" min="1">
                        </div>
                        <div class="mb-3">
                            <label for="total_marks" class="form-label">Total Marks</label>
                            <input type="number" class="form-control" id="total_marks" name="total_marks" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="passing_marks" class="form-label">Passing Marks</label>
                            <input type="number" class="form-control" id="passing_marks" name="passing_marks" min="0" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="is_online" name="is_online">
                            <label class="form-check-label" for="is_online">Online Exam</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="enable_proctoring" name="enable_proctoring">
                            <label class="form-check-label" for="enable_proctoring">Enable Proctoring</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="show_results_immediately" name="show_results_immediately">
                            <label class="form-check-label" for="show_results_immediately">Show Results Immediately</label>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Exam</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 