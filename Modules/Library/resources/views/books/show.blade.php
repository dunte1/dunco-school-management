@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $book->title }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" class="img-fluid" alt="Book Cover">
                            @else
                                <div class="bg-light p-4 text-center">
                                    <i class="fas fa-book fa-3x text-muted"></i>
                                    <p class="mt-2 text-muted">No Cover Image</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">ISBN:</th>
                                    <td>{{ $book->isbn ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Author:</th>
                                    <td>{{ $book->author->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Publisher:</th>
                                    <td>{{ $book->publisher->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td>{{ $book->category->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Edition:</th>
                                    <td>{{ $book->edition ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Year:</th>
                                    <td>{{ $book->year ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $book->status == 'available' ? 'success' : ($book->status == 'borrowed' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($book->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($book->barcode)
                                <tr>
                                    <th>Barcode:</th>
                                    <td>{{ $book->barcode }}</td>
                                </tr>
                                @endif
                            </table>
                            
                            @if($book->description)
                            <div class="mt-3">
                                <h6>Description:</h6>
                                <p>{{ $book->description }}</p>
                            </div>
                            @endif
                            
                            @if($book->ebook_file_path)
                            <div class="mt-3">
                                <h6>E-Book:</h6>
                                <a href="{{ asset('storage/' . $book->ebook_file_path) }}" class="btn btn-sm btn-primary" target="_blank">
                                    <i class="fas fa-download"></i> Download E-Book
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('library.books.edit', $book->id) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('library.books.index') }}" class="btn btn-secondary">Back to List</a>
                    <form action="{{ route('library.books.destroy', $book->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this book?')">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 