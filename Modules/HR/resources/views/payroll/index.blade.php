@extends('layouts.app')

@section('content')
    <h2>Payroll</h2>
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="month" name="payroll_period" class="form-control" value="{{ request('payroll_period') }}">
        </div>
        <div class="col-md-3">
            <select name="staff_id" class="form-select">
                <option value="">All Staff</option>
                @foreach($staff as $s)
                    <option value="{{ $s->id }}" {{ request('staff_id') == $s->id ? 'selected' : '' }}>{{ $s->first_name }} {{ $s->last_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('hr.payroll.create') }}" class="btn btn-success">Add Payroll</a>
        </div>
    </form>
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Staff</th>
                <th>Period</th>
                <th>Basic Salary</th>
                <th>Allowances</th>
                <th>Bonuses</th>
                <th>Deductions</th>
                <th>Net Salary</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payrolls as $payroll)
            <tr>
                <td>{{ $payroll->staff->first_name }} {{ $payroll->staff->last_name }}</td>
                <td>{{ $payroll->payroll_period }}</td>
                <td>{{ number_format($payroll->basic_salary, 2) }}</td>
                <td>{{ number_format($payroll->allowances, 2) }}</td>
                <td>{{ number_format($payroll->bonuses, 2) }}</td>
                <td>{{ number_format($payroll->deductions, 2) }}</td>
                <td><strong>{{ number_format($payroll->net_salary, 2) }}</strong></td>
                <td><span class="badge bg-{{ $payroll->status == 'paid' ? 'success' : 'warning' }}">{{ ucfirst($payroll->status) }}</span></td>
                <td>
                    @if($payroll->status == 'pending')
                        <form action="{{ route('hr.payroll.markPaid', $payroll->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Mark Paid</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $payrolls->links() }}
@endsection 