@extends('layouts.app')

@section('title', 'Modules Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-puzzle-piece me-2 text-primary"></i>Modules</h1>
            <p class="text-muted mb-0">Manage public feature modules</p>
        </div>
        <a href="{{ route('admin.cms.modules.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Module
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Module</th>
                            <th>Slug</th>
                            <th>Active</th>
                            <th>Sort</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modules as $module)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary me-2"><i class="fas {{ $module->icon ?? 'fa-cube' }}"></i></span>
                                    <div>
                                        <div class="fw-semibold">{{ $module->name }}</div>
                                        <small class="text-muted">{{ Str::limit($module->short_description, 60) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><code>{{ $module->slug }}</code></td>
                            <td>
                                @if($module->is_active)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Active</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Inactive</span>
                                @endif
                            </td>
                            <td>{{ $module->sort_order }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.cms.modules.edit', $module) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.cms.modules.destroy', $module) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this module?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-puzzle-piece fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No modules found</p>
                                <a href="{{ route('admin.cms.modules.create') }}" class="btn btn-primary btn-sm mt-3">
                                    <i class="fas fa-plus me-1"></i> Add First Module
                                </a>
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
