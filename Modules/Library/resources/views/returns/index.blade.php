@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Library Returns</h2>
        <div>
            <a href="{{ route('library.returns.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus me-2"></i>New Return
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('library.returns.index') }}">
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
                        <label for="date_from" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('library.returns.index') }}" class="btn btn-outline-secondary">Clear</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Returns Table -->
    <div class="card">
        <div class="card-body">
            @if($returns->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Member</th>
                                <th>Borrowed Date</th>
                                <th>Due Date</th>
                                <th>Returned Date</th>
                                <th>Days Overdue</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returns as $return)
                            <tr>
                                <td>
                                    <strong>{{ $return->book->title ?? 'Unknown Book' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $return->book->author ?? 'Unknown Author' }}</small>
                                </td>
                                <td>{{ $return->member->name ?? 'Unknown Member' }}</td>
                                <td>{{ $return->borrowed_at ? $return->borrowed_at->format('M d, Y') : '-' }}</td>
                                <td>{{ $return->due_at ? $return->due_at->format('M d, Y') : '-' }}</td>
                                <td>{{ $return->returned_at ? $return->returned_at->format('M d, Y') : '-' }}</td>
                                <td>
                                    @if($return->returned_at && $return->due_at)
                                        @php
                                            $daysOverdue = $return->due_at->diffInDays($return->returned_at, false);
                                        @endphp
                                        @if($daysOverdue > 0)
                                            <span class="badge bg-danger">{{ $daysOverdue }} days</span>
                                        @else
                                            <span class="badge bg-success">On time</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('library.returns.show', $return) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('library.returns.edit', $return) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('library.returns.destroy', $return) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this return record?')">
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
                    {{ $returns->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-undo fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No returns found</h5>
                    <p class="text-muted">No books have been returned yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
