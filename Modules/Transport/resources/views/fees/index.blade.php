@extends('layouts.app')

@section('title', 'Transport Fees')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Transport Fees</h4>
                    <a href="{{ route('transport.fees.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Fee
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Route</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Fee Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fees ?? [] as $fee)
                                <tr>
                                    <td>{{ $fee->student->name ?? 'N/A' }}</td>
                                    <td>{{ $fee->route->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($fee->amount ?? 0, 2) }}</td>
                                    <td>{{ $fee->due_date ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($fee->fee_type ?? 'N/A') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $fee->status === 'paid' ? 'success' : ($fee->status === 'pending' ? 'warning' : ($fee->status === 'overdue' ? 'danger' : 'secondary')) }}">
                                            {{ ucfirst($fee->status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('transport.fees.show', $fee->id ?? 1) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('transport.fees.edit', $fee->id ?? 1) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No fees found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
