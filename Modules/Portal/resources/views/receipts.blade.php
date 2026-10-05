@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-receipt me-2"></i>Receipts</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.receipts') }}" class="d-flex align-items-center gap-2">
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
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Payment Receipts</h5>
                </div>
                <div class="card-body">
                    @if($receipts->count())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Receipt No</th>
                                        <th>Payment Date</th>
                                        <th>Fee Type</th>
                                        <th>Amount</th>
                                        <th>Category</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receipts as $receipt)
                                    <tr>
                                        <td><strong>{{ $receipt->receipt_no ?? 'N/A' }}</strong></td>
                                        <td>{{ $receipt->payment_date ? $receipt->payment_date->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $receipt->fee_type ?? 'Unknown' }}</td>
                                        <td>{{ number_format($receipt->amount, 2) }}</td>
                                        <td>{{ $receipt->category ?? 'General' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Download</button>
                                            <button class="btn btn-sm btn-secondary">Print</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-file-invoice fa-2x mb-2"></i>
                            <p>No receipts available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
