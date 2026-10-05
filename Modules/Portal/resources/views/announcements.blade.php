@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-bullhorn me-2"></i>Announcements</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.announcements') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    <div class="row g-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bullhorn me-2"></i>School Announcements</h5>
                </div>
                <div class="card-body">
                    @if($announcements->count())
                        <div class="list-group list-group-flush">
                            @foreach($announcements as $announcement)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $announcement->title ?? 'No Title' }}</h6>
                                        <p class="mb-1 text-muted">{{ $announcement->content ?? 'No content' }}</p>
                                        <small class="text-muted">
                                            {{ $announcement->created_at ? $announcement->created_at->format('M d, Y H:i') : 'N/A' }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        @if($announcement->is_important)
                                            <span class="badge bg-danger">Important</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-bullhorn fa-2x mb-2"></i>
                            <p>No announcements available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
