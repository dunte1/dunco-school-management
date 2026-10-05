@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0"><i class="fas fa-file-alt me-2"></i>Exam Schedule</h1>
        <a href="{{ url('/examinations/create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Create Exam</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-4"><input type="text" class="form-control" placeholder="Search subject or class..." /></div>
                <div class="col-md-3"><input type="date" class="form-control" /></div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option value="">All Types</option>
                        <option>Midterm</option>
                        <option>Final</option>
                        <option>Quiz</option>
                    </select>
                </div>
                <div class="col-md-2 text-end"><button class="btn btn-outline-secondary w-100">Filter</button></div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Subject</th>
                            <th>Class</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Supervisor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($exams ?? []) as $e)
                            <tr>
                                <td>{{ $e['subject'] ?? '' }}</td>
                                <td>{{ $e['class'] ?? '' }}</td>
                                <td>{{ $e['date'] ?? '' }}</td>
                                <td>{{ $e['time'] ?? '' }}</td>
                                <td>{{ $e['supervisor'] ?? '' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-muted">No exams scheduled.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ ($exams ?? null) ? $exams->links() : '' }}</div>
        </div>
    </div>
</div>
@endsection
