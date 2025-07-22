@extends('layouts.app')

@section('content')
<h2>Edit Payroll</h2>
<form method="POST" action="{{ route('hr.payroll.update', $payroll->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Staff</label>
        <select name="staff_id" class="form-select" required>
            <option value="">Select Staff</option>
            @foreach($staff as $s)
                <option value="{{ $s->id }}" {{ $payroll->staff_id == $s->id ? 'selected' : '' }}>{{ $s->first_name }} {{ $s->last_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Payroll Period</label>
        <input type="month" name="payroll_period" class="form-control" value="{{ $payroll->payroll_period }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Basic Salary</label>
        <input type="number" name="basic_salary" class="form-control" value="{{ $payroll->basic_salary }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Allowances</label>
        <input type="number" name="allowances" class="form-control" value="{{ $payroll->allowances }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Bonuses</label>
        <input type="number" name="bonuses" class="form-control" value="{{ $payroll->bonuses }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Deductions</label>
        <input type="number" name="deductions" class="form-control" value="{{ $payroll->deductions }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Net Salary</label>
        <input type="number" name="net_salary" class="form-control" value="{{ $payroll->net_salary }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="pending" {{ $payroll->status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ $payroll->status == 'paid' ? 'selected' : '' }}>Paid</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('hr.payroll.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
