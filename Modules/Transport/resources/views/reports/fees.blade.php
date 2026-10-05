@extends('layouts.app')

@section('title', 'Fee Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fee Reports</h3>
                    <div class="card-tools">
                        <a href="{{ route('transport.reports.export', ['type' => 'fees']) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
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
                                @forelse($fees as $fee)
                                <tr>
                                    <td>{{ $fee->student->name ?? 'N/A' }}</td>
                                    <td>{{ $fee->route->name ?? 'N/A' }}</td>
                                    <td>KSh {{ number_format($fee->amount, 2) }}</td>
                                    <td>{{ $fee->due_date->format('M d, Y') }}</td>
                                    <td>{{ ucfirst($fee->fee_type) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $fee->status === 'paid' ? 'success' : ($fee->status === 'overdue' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($fee->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('transport.fees.show', $fee->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
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
                    
                    <div class="d-flex justify-content-center">
                        {{ $fees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
