@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3"><i class="fas fa-book-open me-2"></i>Library Transactions</h1>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-4"><input type="text" class="form-control" placeholder="Search by book or student..." /></div>
                <div class="col-md-3"><input type="date" class="form-control" /></div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option value="">All Actions</option>
                        <option>Borrowed</option>
                        <option>Returned</option>
                        <option>Overdue</option>
                    </select>
                </div>
                <div class="col-md-2 text-end"><button class="btn btn-outline-secondary w-100">Filter</button></div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Book</th>
                            <th>Action</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($transactions ?? []) as $t)
                            <tr>
                                <td>{{ $t['student'] ?? '' }}</td>
                                <td>{{ $t['book'] ?? '' }}</td>
                                <td>{{ $t['action'] ?? '' }}</td>
                                <td>{{ $t['date'] ?? '' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-muted">No transactions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ ($transactions ?? null) ? $transactions->links() : '' }}</div>
        </div>
    </div>
</div>
@endsection
