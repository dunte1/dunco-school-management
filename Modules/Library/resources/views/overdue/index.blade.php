@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Overdue Books</h2>
        <div>
            <a href="{{ route('library.overdue.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus me-2"></i>Manage Overdue
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('library.overdue.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Book title or member name">
                    </div>
                    <div class="col-md-3">
                        <label for="member_id" class="form-label">Member</label>
                        <select class="form-select" id="member_id" name="member_id">
                            <option value="">All Members</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="overdue_days" class="form-label">Overdue Days</label>
                        <select class="form-select" id="overdue_days" name="overdue_days">
                            <option value="">All Overdue</option>
                            <option value="1" {{ request('overdue_days') == '1' ? 'selected' : '' }}>1+ days</option>
                            <option value="7" {{ request('overdue_days') == '7' ? 'selected' : '' }}>7+ days</option>
                            <option value="30" {{ request('overdue_days') == '30' ? 'selected' : '' }}>30+ days</option>
                            <option value="90" {{ request('overdue_days') == '90' ? 'selected' : '' }}>90+ days</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('library.overdue.index') }}" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Overdue Books Table -->
    <div class="card">
        <div class="card-body">
            @if($overdueBooks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Member</th>
                                <th>Borrowed Date</th>
                                <th>Due Date</th>
                                <th>Days Overdue</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overdueBooks as $book)
                            <tr>
                                <td>
                                    <strong>{{ $book->book->title ?? 'Unknown Book' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $book->book->author ?? 'Unknown Author' }}</small>
                                </td>
                                <td>{{ $book->member->name ?? 'Unknown Member' }}</td>
                                <td>{{ $book->borrowed_at ? $book->borrowed_at->format('M d, Y') : '-' }}</td>
                                <td>{{ $book->due_at ? $book->due_at->format('M d, Y') : '-' }}</td>
                                <td>
                                    @if($book->due_at)
                                        @php
                                            $daysOverdue = $book->due_at->diffInDays(now(), false);
                                        @endphp
                                        <span class="badge bg-danger">{{ $daysOverdue }} days</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('library.overdue.show', $book) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('library.overdue.edit', $book) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('library.overdue.destroy', $book) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this overdue record?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $overdueBooks->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="text-success">No overdue books found</h5>
                    <p class="text-muted">All books are returned on time! Great job!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
