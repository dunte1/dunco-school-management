@extends('layouts.app')

@section('title', 'Manage Documents')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-cogs me-2"></i>Manage Documents</h4>
        <a href="{{ route('document.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h3 class="text-primary">{{ $stats['total'] }}</h3>
                <small class="text-muted">Total Documents</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h3 class="text-success">{{ $stats['active'] }}</h3>
                <small class="text-muted">Active</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h3 class="text-secondary">{{ $stats['inactive'] }}</h3>
                <small class="text-muted">Inactive</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center p-3">
                <h3 class="text-info">{{ $stats['categories'] }}</h3>
                <small class="text-muted">Categories</small>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Size</th>
                        <th>Visibility</th>
                        <th>Status</th>
                        <th>Uploaded</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td>{{ $doc->id }}</td>
                        <td>
                            <i class="{{ $doc->file_icon }} me-2"></i>
                            <a href="{{ route('document.show', $doc->id) }}">{{ $doc->title }}</a>
                        </td>
                        <td><span class="badge bg-secondary">{{ strtoupper($doc->file_type ?? 'N/A') }}</span></td>
                        <td>{{ $doc->category ? ucfirst($doc->category) : '-' }}</td>
                        <td>{{ $doc->formatted_size }}</td>
                        <td>
                            <span class="badge bg-{{ $doc->visibility === 'public' ? 'success' : ($doc->visibility === 'private' ? 'danger' : 'warning') }}">
                                {{ ucfirst(str_replace('_', ' ', $doc->visibility)) }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('document.toggle-status', $doc->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="badge bg-{{ $doc->is_active ? 'success' : 'secondary' }} border-0" style="cursor:pointer;">
                                    {{ $doc->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td>{{ $doc->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('document.show', $doc->id) }}" class="btn btn-sm btn-outline-primary me-1"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('document.edit', $doc->id) }}" class="btn btn-sm btn-outline-warning me-1"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('document.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No documents found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $documents->links() }}
        </div>
    </div>
</div>
@endsection
