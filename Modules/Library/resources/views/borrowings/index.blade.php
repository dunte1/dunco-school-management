@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Library Borrowings</h2>
        <div>
            <a href="{{ route('library.borrowings.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus me-2"></i>New Borrowing
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('library.borrowings.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Book title or member name">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                        </select>
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
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('library.borrowings.index') }}" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Borrowings Table -->
    <div class="card">
        <div class="card-body">
            @if($borrowings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Member</th>
                                <th>Borrowed Date</th>
                                <th>Due Date</th>
                                <th>Returned Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowings as $borrowing)
                            <tr>
                                <td>
                                    <strong>{{ $borrowing->book->title ?? 'Unknown Book' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $borrowing->book->author ?? 'Unknown Author' }}</small>
                                </td>
                                <td>{{ $borrowing->member->name ?? 'Unknown Member' }}</td>
                                <td>{{ $borrowing->borrowed_at ? $borrowing->borrowed_at->format('M d, Y') : '-' }}</td>
                                <td>{{ $borrowing->due_at ? $borrowing->due_at->format('M d, Y') : '-' }}</td>
                                <td>{{ $borrowing->returned_at ? $borrowing->returned_at->format('M d, Y') : '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $borrowing->status == 'returned' ? 'success' : ($borrowing->status == 'overdue' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($borrowing->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('library.borrowings.show', $borrowing) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('library.borrowings.edit', $borrowing) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('library.borrowings.destroy', $borrowing) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this borrowing record?')">
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
                    {{ $borrowings->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No borrowings found</h5>
                    <p class="text-muted">Start by creating a new borrowing record.</p>
                    <a href="{{ route('library.borrowings.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Borrowing
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
