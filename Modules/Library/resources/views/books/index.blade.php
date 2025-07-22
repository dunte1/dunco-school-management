@extends('library::layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Books</h2>
    <a href="{{ route('library.books.create') }}" class="btn btn-primary">Add Book</a>
</div>
<table class="table table-bordered table-hover bg-white">
    <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Category</th>
            <th>Publisher</th>
            <th>ISBN</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($books as $book)
        <tr>
            <td>{{ $book->title }}</td>
            <td>{{ $book->author->name ?? '-' }}</td>
            <td>{{ $book->category->name ?? '-' }}</td>
            <td>{{ $book->publisher->name ?? '-' }}</td>
            <td>{{ $book->isbn }}</td>
            <td>{{ ucfirst($book->status) }}</td>
            <td>
                <a href="{{ route('library.books.show', $book) }}" class="btn btn-sm btn-info">View</a>
                <a href="{{ route('library.books.edit', $book) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('library.books.destroy', $book) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this book?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection 