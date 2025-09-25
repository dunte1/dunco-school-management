@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Edit Book: {{ $book->title }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('library.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $book->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="isbn" class="form-label">ISBN *</label>
                                    <input type="text" class="form-control @error('isbn') is-invalid @enderror" 
                                           id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}" required>
                                    @error('isbn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="author_id" class="form-label">Author</label>
                                    <select class="form-select @error('author_id') is-invalid @enderror" id="author_id" name="author_id">
                                        <option value="">Select Author</option>
                                        @foreach($authors as $author)
                                            <option value="{{ $author->id }}" {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>
                                                {{ $author->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('author_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="publisher_id" class="form-label">Publisher</label>
                                    <select class="form-select @error('publisher_id') is-invalid @enderror" id="publisher_id" name="publisher_id">
                                        <option value="">Select Publisher</option>
                                        @foreach($publishers as $publisher)
                                            <option value="{{ $publisher->id }}" {{ old('publisher_id', $book->publisher_id) == $publisher->id ? 'selected' : '' }}>
                                                {{ $publisher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('publisher_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="available" {{ old('status', $book->status) == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="borrowed" {{ old('status', $book->status) == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                                        <option value="reserved" {{ old('status', $book->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                        <option value="lost" {{ old('status', $book->status) == 'lost' ? 'selected' : '' }}>Lost</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edition" class="form-label">Edition</label>
                                    <input type="text" class="form-control @error('edition') is-invalid @enderror" 
                                           id="edition" name="edition" value="{{ old('edition', $book->edition) }}">
                                    @error('edition')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="year" class="form-label">Year</label>
                                    <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                           id="year" name="year" value="{{ old('year', $book->year) }}">
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="barcode" class="form-label">Barcode</label>
                                    <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                                           id="barcode" name="barcode" value="{{ old('barcode', $book->barcode) }}">
                                    @error('barcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $book->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cover_image" class="form-label">Cover Image URL</label>
                            <input type="text" class="form-control @error('cover_image') is-invalid @enderror" 
                                   id="cover_image" name="cover_image" value="{{ old('cover_image', $book->cover_image) }}">
                            @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ebook_file" class="form-label">E-Book File</label>
                            <input type="file" class="form-control @error('ebook_file') is-invalid @enderror" 
                                   id="ebook_file" name="ebook_file" accept=".pdf,.epub,.mp3">
                            <small class="form-text text-muted">Accepted formats: PDF, EPUB, MP3 (Max 20MB)</small>
                            @error('ebook_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('library.books.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Book</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 