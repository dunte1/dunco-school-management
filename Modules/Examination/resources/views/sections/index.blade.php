@extends('layouts.app')

@section('title', 'Exam Sections - ' . $exam->name)

@section('content')
<div class="container-xl py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a237e;">Exam Sections</h2>
            <p class="text-muted mb-0">{{ $exam->name }} ({{ $exam->code }})</p>
        </div>
        <div>
            <a href="{{ route('examination.sections.create', $exam) }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Section
            </a>
            <a href="{{ route('examination.exams.show', $exam) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Exam
            </a>
        </div>
    </div>

    @if($sections->count() > 0)
        <div class="row" id="sections-container">
            @foreach($sections as $section)
                <div class="col-md-6 col-lg-4 mb-4" data-section-id="{{ $section->id }}">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ $section->name }}</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('examination.sections.edit', [$exam, $section]) }}">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('examination.sections.add-questions', [$exam, $section]) }}">
                                        <i class="fas fa-plus me-2"></i>Add Questions
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('examination.sections.destroy', [$exam, $section]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($section->description)
                                <p class="text-muted small mb-3">{{ $section->description }}</p>
                            @endif
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="border-end">
                                        <h6 class="text-primary mb-1">{{ $section->question_count }}</h6>
                                        <small class="text-muted">Questions</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h6 class="text-success mb-1">{{ $section->total_marks }}</h6>
                                    <small class="text-muted">Marks</small>
                                </div>
                            </div>

                            @if($section->is_optional)
                                <div class="mt-3">
                                    <span class="badge bg-warning">Optional</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">Order: {{ $section->order }}</small>
                                <div class="drag-handle" style="cursor: move;">
                                    <i class="fas fa-grip-vertical text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No sections created yet</h5>
            <p class="text-muted">Create your first section to organize questions into parts.</p>
            <a href="{{ route('examination.sections.create', $exam) }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create First Section
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('sections-container');
    if (container) {
        const sortable = Sortable.create(container, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function(evt) {
                const sections = Array.from(container.children).map((item, index) => ({
                    id: item.dataset.sectionId,
                    order: index + 1
                }));

                fetch('{{ route("examination.sections.reorder", $exam) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ sections: sections })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        console.log('Sections reordered successfully');
                    }
                })
                .catch(error => {
                    console.error('Error reordering sections:', error);
                });
            }
        });
    }
});
</script>
@endpush
@endsection
