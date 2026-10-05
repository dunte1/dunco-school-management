@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-book me-2"></i>Library</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.library') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    <div class="row g-4">
        {{-- Library Summary Cards --}}
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-book-open fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $borrowedBooks->count() }}</h4>
                    <p class="mb-0">Borrowed Books</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $overdueBooks->count() }}</h4>
                    <p class="mb-0">Overdue Books</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $availableBooks->count() }}</h4>
                    <p class="mb-0">Available Books</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h4 class="mb-1">7 Days</h4>
                    <p class="mb-0">Loan Period</p>
                </div>
            </div>
        </div>

        {{-- Borrowed Books --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book-open me-2"></i>My Borrowed Books</h5>
                </div>
                <div class="card-body">
                    @if($borrowedBooks->count())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Book Title</th>
                                        <th>Author</th>
                                        <th>Borrowed Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($borrowedBooks as $book)
                                        @php
                                            $isOverdue = $book->due_date && $book->due_date < now();
                                            $rowClass = $isOverdue ? 'table-danger' : '';
                                        @endphp
                                        <tr class="{{ $rowClass }}">
                                            <td><strong>{{ $book->title ?? 'Unknown Book' }}</strong></td>
                                            <td>{{ $book->author ?? 'Unknown Author' }}</td>
                                            <td>{{ $book->borrowed_date ? $book->borrowed_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>{{ $book->due_date ? $book->due_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                @if($isOverdue)
                                                    <span class="badge bg-danger">Overdue</span>
                                                @else
                                                    <span class="badge bg-success">On Time</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-book-open fa-2x mb-2"></i>
                            <p>No books currently borrowed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Overdue Books Alert --}}
        <div class="col-lg-4">
            @if($overdueBooks->count())
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Overdue Books</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($overdueBooks as $book)
                        <div class="list-group-item">
                            <h6 class="mb-1">{{ $book->title ?? 'Unknown Book' }}</h6>
                            <small class="text-muted">Due: {{ $book->due_date ? $book->due_date->format('M d, Y') : 'N/A' }}</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @else
            <div class="card bg-light">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                    <h5 class="mb-1">All Good!</h5>
                    <p class="text-muted mb-0">No overdue books.</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Available Books --}}
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-search me-2"></i>Available Books</h5>
                </div>
                <div class="card-body">
                    @if($availableBooks->count())
                        <div class="row">
                            @foreach($availableBooks->take(8) as $book)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $book->title ?? 'Unknown Book' }}</h6>
                                        <p class="card-text text-muted">{{ $book->author ?? 'Unknown Author' }}</p>
                                        <small class="text-muted">{{ $book->category ?? 'General' }}</small>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-sm btn-primary w-100">Borrow</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-search fa-2x mb-2"></i>
                            <p>No available books to display.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
