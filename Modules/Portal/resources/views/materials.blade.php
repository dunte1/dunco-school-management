@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-book me-2"></i>Materials</h3>
        @if(Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.materials') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    {{-- Course/Subject Overview --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Course/Subject Overview</h5>
        </div>
        <div class="card-body">
            @if($classSubjects->count())
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Subject</th>
                        <th>Teachers</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classSubjects as $subject)
                    <tr>
                        <td>{{ $subject->name }}</td>
                        <td>
                            @if($subject->teachers->count())
                            @foreach($subject->teachers as $teacher)
                            <span class="badge bg-primary">{{ $teacher->name }}</span>
                            @endforeach
                            @endif
                        </td>
                        <td>{{ $subject->description ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center text-muted py-4">
                <i class="fas fa-book-open fa-2x mb-2"></i>
                <p>No subjects found for this class.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Syllabus / Study Materials --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Syllabus / Study Materials</h5>
        </div>
        <div class="card-body">
            @if($classSubjects->count())
                @foreach($classSubjects as $subject)
                <div class="mb-3">
                    <h6 class="fw-semibold">{{ $subject->name }}</h6>
                    @if(isset($subjectResourcesBySubject[$subject->id]) && $subjectResourcesBySubject[$subject->id]->count())
                    <ul class="list-group">
                        @foreach($subjectResourcesBySubject[$subject->id] as $res)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file-alt me-2"></i>{{ $res->title }}</span>
                            <span>
                                @if($res->type === 'PDF' && $res->file_path)
                                    {{-- Check if it's a demo file or a real one --}}
                                    @if(Str::startsWith($res->file_path, 'demo/'))
                                        <a href="{{ asset('storage/' . $res->file_path) }}" class="btn btn-sm btn-outline-primary" download>Download</a>
                                    @else
                                        <a href="{{ Storage::url($res->file_path) }}" class="btn btn-sm btn-outline-primary" download>Download</a>
                                    @endif
                                @elseif($res->type === 'Link' && $res->url)
                                    <a href="{{ $res->url }}" class="btn btn-sm btn-outline-secondary" target="_blank">View</a>
                                @endif
                            </span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-folder-open fa-lg mb-2"></i>
                        <p>No study materials for this subject.</p>
                    </div>
                    @endif
                </div>
                @endforeach
            @else
            <div class="text-center text-muted py-4">
                <i class="fas fa-book-reader fa-2x mb-2"></i>
                <p>No subjects found for this class.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 