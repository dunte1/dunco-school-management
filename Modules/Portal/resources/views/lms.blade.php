@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-graduation-cap me-2"></i>LMS - My Courses</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.lms') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    @if($courses->count())
    <div class="row g-4">
        @foreach($courses as $course)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm course-card">
                <img src="{{ $course->thumbnail }}" class="card-img-top" alt="{{ $course->title }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $course->title }}</h5>
                    <p class="card-text text-muted">By {{ $course->instructor }}</p>
                    <div class="mt-auto">
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $course->progress }}%;" aria-valuenow="{{ $course->progress }}" aria-valuemin="0" aria-valuemax="100">{{ $course->progress }}%</div>
                        </div>
                        <p class="mb-2"><small><strong>Next Due:</strong> {{ $course->next_due }} ({{ $course->due_date->diffForHumans() }})</small></p>
                        <a href="#" class="btn btn-primary w-100">Go to Course</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center text-muted py-5"><i class="fas fa-book-reader fa-3x mb-3"></i><p class="fs-4">No courses enrolled yet.</p></div>
    @endif
</div>
@endsection

@push('styles')
<style>
.course-card {
    transition: transform .2s ease-in-out, box-shadow .2s ease-in-out;
}
.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
</style>
@endpush 