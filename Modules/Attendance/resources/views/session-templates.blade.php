@extends('layouts.app')

@section('title', 'Session Templates')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-clock me-2"></i>Attendance Session Templates</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
            <i class="fas fa-plus me-1"></i> New Template
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Template Name</th>
                        <th>Session Name</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $template)
                    <tr>
                        <td>{{ $template->id }}</td>
                        <td>{{ $template->template_name ?? '-' }}</td>
                        <td>{{ $template->session_name }}</td>
                        <td>{{ $template->start_time }}</td>
                        <td>{{ $template->end_time }}</td>
                        <td>
                            @if($template->is_active ?? true)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No session templates found. Create one to get started.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $templates->links() }}
        </div>
    </div>

    <div class="modal fade" id="createTemplateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="#">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create Session Template</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Template Name</label>
                            <input type="text" name="template_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Session Name</label>
                            <input type="text" name="session_name" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Start Time</label>
                                <input type="time" name="start_time" class="form-control" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">End Time</label>
                                <input type="time" name="end_time" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
