@extends('layouts.app')

@section('content')
<h2>Add Payroll</h2>
<form method="POST" action="{{ route('hr.payroll.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Staff</label>
        <select name="staff_id" class="form-select" required>
            <option value="">Select Staff</option>
            @foreach($staff as $s)
                <option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Payroll Period</label>
        <input type="month" name="payroll_period" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Basic Salary</label>
        <input type="number" name="basic_salary" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Allowances</label>
        <input type="number" name="allowances" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Bonuses</label>
        <input type="number" name="bonuses" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Deductions</label>
        <input type="number" name="deductions" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Net Salary</label>
        <input type="number" name="net_salary" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="{{ route('hr.payroll.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection 