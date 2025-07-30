@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Library Books</h2>
        <a href="{{ route('library.books.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Book
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('library.books.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Search books..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="author" class="form-select">
                        <option value="">All Authors</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                        <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search me-1"></i>Search
                    </button>
                    <a href="{{ route('library.books.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Books Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Books ({{ $books->total() }})</h5>
</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Publisher</th>
            <th>ISBN</th>
            <th>Status</th>
                            <th width="150">Actions</th>
        </tr>
    </thead>
    <tbody>
                        @forelse($books as $book)
                        <tr>
                            <td>
                                <strong>{{ $book->title }}</strong>
                                @if($book->edition)
                                    <br><small class="text-muted">{{ $book->edition }}</small>
                                @endif
                            </td>
            <td>{{ $book->author->name ?? '-' }}</td>
                            <td>
                                @if($book->category)
                                    <span class="badge bg-info">{{ $book->category->name }}</span>
                                @else
                                    -
                                @endif
                            </td>
            <td>{{ $book->publisher->name ?? '-' }}</td>
                            <td><code>{{ $book->isbn }}</code></td>
                            <td>
                                <span class="badge bg-{{ $book->status == 'available' ? 'success' : ($book->status == 'borrowed' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($book->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('library.books.show', $book) }}" class="btn btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('library.books.edit', $book) }}" class="btn btn-outline-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                <form action="{{ route('library.books.destroy', $book) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete" 
                                                onclick="return confirm('Are you sure you want to delete this book?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-book fa-2x mb-3"></i>
                                    <p>No books found.</p>
                                    <a href="{{ route('library.books.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Add First Book
                                    </a>
                                </div>
            </td>
        </tr>
                        @endforelse
    </tbody>
</table>
            </div>
        </div>
        @if($books->hasPages())
        <div class="card-footer">
            {{ $books->links() }}
        </div>
        @endif
    </div>
</div>
@endsection 