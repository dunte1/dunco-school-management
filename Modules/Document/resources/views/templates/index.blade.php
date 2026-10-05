@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Document Templates</h2>
        <a href="{{ route('document.templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Template
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Template</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-{{ $template->file_type == 'pdf' ? 'pdf' : 'alt' }} me-2 text-primary"></i>
                                    <div>
                                        <h6 class="mb-0">{{ $template->name }}</h6>
                                        <small class="text-muted">{{ $template->description }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($template->category)
                                    <span class="badge bg-info">{{ $template->category->name }}</span>
                                @else
                                    <span class="text-muted">No category</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ ucfirst($template->type) }}</span>
                            </td>
                            <td>{{ $template->created_by_user->name ?? 'Unknown' }}</td>
                            <td>{{ $template->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $template->is_active ? 'success' : 'secondary' }}">
                                    {{ $template->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('document.templates.show', $template) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('document.templates.edit', $template) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('document.templates.use', $template) }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-copy"></i>
                                    </a>
                                    <form action="{{ route('document.templates.destroy', $template) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to delete this template?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No templates found</h5>
                                <p class="text-muted">Create your first document template to get started.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
