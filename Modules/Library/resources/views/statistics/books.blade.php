@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Book Statistics</h2>
        <div>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-primary me-2">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="btn btn-success">
                <i class="fas fa-file-excel me-2"></i>Export Excel
            </a>
        </div>
    </div>

    <!-- Book Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $totalBooks ?? 0 }}</h4>
                    <p class="mb-0">Total Books</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $availableBooks ?? 0 }}</h4>
                    <p class="mb-0">Available Books</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $borrowedBooks ?? 0 }}</h4>
                    <p class="mb-0">Borrowed Books</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $totalCategories ?? 0 }}</h4>
                    <p class="mb-0">Categories</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Books by Category -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Books by Category</h5>
                </div>
                <div class="card-body">
                    @if(isset($booksByCategory) && $booksByCategory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booksByCategory as $category)
                                    <tr>
                                        <td>{{ $category->name ?? 'Uncategorized' }}</td>
                                        <td>{{ $category->books_count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No category data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Books by Author -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top Authors</h5>
                </div>
                <div class="card-body">
                    @if(isset($topAuthors) && $topAuthors->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Author</th>
                                        <th>Books</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topAuthors as $author)
                                    <tr>
                                        <td>{{ $author->name ?? 'Unknown' }}</td>
                                        <td>{{ $author->books_count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No author data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
