@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-book me-2"></i>Library Search</h3>
        @if(Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.library.search') }}" class="d-flex align-items-center gap-2">
            <input type="hidden" name="query" value="{{ $query }}">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('portal.library.search') }}" method="GET" class="d-flex gap-2">
                <input type="hidden" name="student_id" value="{{ request('student_id') }}">
                <input type="text" name="query" class="form-control form-control-lg" placeholder="Search by title or author..." value="{{ $query }}">
                <button type="submit" class="btn btn-primary btn-lg">Search</button>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header"><h5 class="mb-0">Search Results</h5></div>
        <div class="card-body">
            @if(isset($query))
                @if($books->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>ISBN</th>
                                <th>Availability</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                            <tr>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td>{{ $book->isbn }}</td>
                                <td>
                                    @if($book->is_available)
                                    <span class="badge bg-success">Available</span>
                                    @else
                                    <span class="badge bg-danger">Checked Out</span>
                                    @endif
                                </td>
                                <td>
                                    @if($book->is_ebook && $book->ebook_url)
                                    <a href="{{ $book->ebook_url }}" class="btn btn-sm btn-outline-primary" target="_blank">Read E-Book</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted py-5"><i class="fas fa-search fa-3x mb-3"></i><p class="fs-4">No books found matching your query.</p></div>
                @endif
            @else
            <div class="text-center text-muted py-5"><i class="fas fa-book-open fa-3x mb-3"></i><p class="fs-4">Start by searching for a book.</p></div>
            @endif
        </div>
    </div>
</div>
@endsection 