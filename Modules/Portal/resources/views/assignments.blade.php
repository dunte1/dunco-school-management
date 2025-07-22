@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-book-open me-2"></i>Assignments</h3>
        @if(Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.assignments') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Assignments & Homework</h5>
        </div>
        <div class="card-body">
            @if($assignments->count())
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Subject</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Submission</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $assignment)
                    <tr>
                        <td>{{ $assignment->name }}</td>
                        <td>
                            @if($assignment->subjects->count())
                            @foreach($assignment->subjects as $subject)
                            <span class="badge bg-primary">{{ $subject->name }}</span>
                            @endforeach
                            @endif
                        </td>
                        <td>{{ $assignment->end_date->format('M d, Y') }}</td>
                        <td><span class="badge bg-{{ $assignment->status_color }}">{{ ucfirst($assignment->status) }}</span></td>
                        <td>
                            @if(Auth::user()->hasRole('student'))
                                @if($assignment->end_date->isFuture())
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#submissionModal">Submit</button>
                                @else
                                <span class="text-muted">Closed</span>
                                @endif
                            @else
                            <span class="text-muted">View only</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center text-muted py-4">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <p>No assignments or homework found.</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Submission Modal --}}
<div class="modal fade" id="submissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Submit Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Upload File</label>
                        <input type="file" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Or type your answer</label>
                        <textarea class="form-control" rows="5"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection 