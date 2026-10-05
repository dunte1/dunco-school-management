@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Document Sharing</h2>
        <a href="{{ route('document.sharing.create') }}" class="btn btn-primary">
            <i class="fas fa-share me-2"></i>Share Document
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
                            <th>Document</th>
                            <th>Shared With</th>
                            <th>Permission</th>
                            <th>Shared By</th>
                            <th>Shared At</th>
                            <th>Expires At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shares as $share)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-{{ $share->document->file_type == 'pdf' ? 'pdf' : 'alt' }} me-2 text-primary"></i>
                                    <div>
                                        <h6 class="mb-0">{{ $share->document->title }}</h6>
                                        <small class="text-muted">{{ $share->document->file_name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($share->shared_with_user)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ substr($share->shared_with_user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $share->shared_with_user->name }}</h6>
                                            <small class="text-muted">{{ $share->shared_with_user->email }}</small>
                                        </div>
                                    </div>
                                @elseif($share->shared_with_group)
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-users me-2 text-info"></i>
                                        <div>
                                            <h6 class="mb-0">{{ $share->shared_with_group->name }}</h6>
                                            <small class="text-muted">Group</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Public Link</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $share->permission == 'view' ? 'info' : ($share->permission == 'edit' ? 'warning' : 'success') }}">
                                    {{ ucfirst($share->permission) }}
                                </span>
                            </td>
                            <td>{{ $share->shared_by_user->name ?? 'Unknown' }}</td>
                            <td>{{ $share->created_at->format('M d, Y g:i A') }}</td>
                            <td>
                                @if($share->expires_at)
                                    {{ \Carbon\Carbon::parse($share->expires_at)->format('M d, Y g:i A') }}
                                @else
                                    <span class="text-muted">Never</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $share->is_active ? 'success' : 'secondary' }}">
                                    {{ $share->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('document.sharing.show', $share) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('document.sharing.edit', $share) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('document.sharing.destroy', $share) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to revoke this share?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-share fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No shared documents found</h5>
                                <p class="text-muted">Start sharing documents to see them here.</p>
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
