@extends('library::layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Add New Book</h2>
    <form action="{{ route('library.books.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="isbn" class="form-label">ISBN</label>
            <input type="text" class="form-control" id="isbn" name="isbn" required>
        </div>
        <div class="mb-3">
            <label for="barcode" class="form-label">Barcode</label>
            <input type="text" class="form-control" id="barcode" name="barcode">
        </div>
        <div class="mb-3">
            <label for="edition" class="form-label">Edition</label>
            <input type="text" class="form-control" id="edition" name="edition">
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="number" class="form-control" id="year" name="year">
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Category</label>
            <select class="form-select" id="category_id" name="category_id">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="author_id" class="form-label">Author</label>
            <select class="form-select" id="author_id" name="author_id">
                <option value="">Select Author</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="publisher_id" class="form-label">Publisher</label>
            <select class="form-select" id="publisher_id" name="publisher_id">
                <option value="">Select Publisher</option>
                @foreach($publishers as $publisher)
                    <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="cover_image" class="form-label">Cover Image URL</label>
            <input type="text" class="form-control" id="cover_image" name="cover_image">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="available">Available</option>
                <option value="borrowed">Borrowed</option>
                <option value="reserved">Reserved</option>
                <option value="lost">Lost</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Add Book</button>
        <a href="{{ route('library.books.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 