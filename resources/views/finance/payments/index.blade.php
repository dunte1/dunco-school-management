@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3"><i class="fas fa-credit-card me-2"></i>Fee Payments</h1>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-4"><input type="text" class="form-control" placeholder="Search by student or ref..." /></div>
                <div class="col-md-3"><input type="date" class="form-control" /></div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option value="">All Methods</option>
                        <option>Cash</option>
                        <option>Card</option>
                        <option>Bank Transfer</option>
                    </select>
                </div>
                <div class="col-md-2 text-end"><button class="btn btn-outline-secondary w-100">Filter</button></div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($payments ?? []) as $p)
                            <tr>
                                <td>{{ $p['student'] ?? '' }}</td>
                                <td>KES {{ number_format($p['amount'] ?? 0) }}</td>
                                <td>{{ $p['method'] ?? '' }}</td>
                                <td>{{ $p['date'] ?? '' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-muted">No payments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ ($payments ?? null) ? $payments->links() : '' }}</div>
        </div>
    </div>
</div>
@endsection
