@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Overdue Books Report</h2>
        <div>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="btn btn-primary me-2">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </a>
            <a href="{{ route('library.reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Reports
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Overdue Books</h5>
        </div>
        <div class="card-body">
            @if($overdueBooks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Book Title</th>
                                <th>Member Name</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Days Overdue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overdueBooks as $index => $book)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $book->book_title }}</td>
                                <td>{{ $book->member_name }}</td>
                                <td>{{ $book->borrow_date }}</td>
                                <td>{{ $book->due_date }}</td>
                                <td>
                                    <span class="badge bg-danger">{{ $book->days_overdue }} days</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="text-success">No overdue books!</h5>
                    <p class="text-muted">All books have been returned on time.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
