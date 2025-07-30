@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Authors</h2>
        <a href="{{ route('library.authors.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Author
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Authors ({{ $authors->count() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Website</th>
                            <th>Books Count</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($authors as $author)
                        <tr>
                            <td>
                                <strong>{{ $author->name }}</strong>
                                @if($author->biography)
                                    <br><small class="text-muted">{{ Str::limit($author->biography, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $author->email ?? '-' }}</td>
                            <td>
                                @if($author->website)
                                    <a href="{{ $author->website }}" target="_blank">{{ $author->website }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $author->books->count() }}</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('library.authors.show', $author) }}" class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('library.authors.edit', $author) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('library.authors.destroy', $author) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete" 
                                                onclick="return confirm('Are you sure you want to delete this author?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-user-edit fa-2x mb-3"></i>
                                    <p>No authors found.</p>
                                    <a href="{{ route('library.authors.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Add First Author
                                    </a>
                                </div>
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