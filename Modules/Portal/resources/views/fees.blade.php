@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-money-bill me-2"></i>Fees</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.fees') }}" class="d-flex align-items-center gap-2">
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
        {{-- Fee Summary Cards --}}
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ number_format($totalFees, 2) }}</h4>
                    <p class="mb-0">Total Fees</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ number_format($paidFees, 2) }}</h4>
                    <p class="mb-0">Paid Fees</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ number_format($pendingFees, 2) }}</h4>
                    <p class="mb-0">Pending Fees</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-percentage fa-2x mb-2"></i>
                    <h4 class="mb-1">{{ $totalFees > 0 ? round(($paidFees / $totalFees) * 100, 1) : 0 }}%</h4>
                    <p class="mb-0">Payment Progress</p>
                </div>
            </div>
        </div>

        {{-- Fee Details --}}
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Fee Details</h5>
                </div>
                <div class="card-body">
                    @if($fees->count())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fee Type</th>
                                        <th>Amount</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fees as $fee)
                                        @php
                                            $isOverdue = $fee->due_date && $fee->due_date < now() && $fee->status !== 'paid';
                                            $rowClass = $isOverdue ? 'table-danger' : '';
                                        @endphp
                                        <tr class="{{ $rowClass }}">
                                            <td><strong>{{ $fee->fee_type ?? 'Unknown Fee' }}</strong></td>
                                            <td>{{ number_format($fee->amount, 2) }}</td>
                                            <td>{{ $fee->due_date ? $fee->due_date->format('M d, Y') : 'N/A' }}</td>
                                            <td>
                                                @if($fee->status === 'paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @elseif($isOverdue)
                                                    <span class="badge bg-danger">Overdue</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($fee->status !== 'paid')
                                                    <button class="btn btn-sm btn-primary">Pay Now</button>
                                                @else
                                                    <button class="btn btn-sm btn-secondary">View Receipt</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-list fa-2x mb-2"></i>
                            <p>No fee records available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
