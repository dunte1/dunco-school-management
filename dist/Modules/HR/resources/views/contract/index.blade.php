@extends('layouts.app')

@section('content')
<h2>Contracts</h2>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="staff_id" class="form-select">
            <option value="">All Staff</option>
            @foreach($staff as $s)
                <option value="{{ $s->id }}" {{ request('staff_id') == $s->id ? 'selected' : '' }}>{{ $s->first_name }} {{ $s->last_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <select name="type" class="form-select">
            <option value="">All Types</option>
            <option value="Permanent" {{ request('type') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
            <option value="Contract" {{ request('type') == 'Contract' ? 'selected' : '' }}>Contract</option>
            <option value="Probation" {{ request('type') == 'Probation' ? 'selected' : '' }}>Probation</option>
        </select>
    </div>
    <div class="col-md-4">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('hr.contract.create') }}" class="btn btn-success">Add Contract</a>
    </div>
</form>
<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Staff</th>
            <th>Type</th>
            <th>Start</th>
            <th>End</th>
            <th>Duration (months)</th>
            <th>Probation</th>
            <th>Promotion</th>
            <th>Transfer</th>
        </tr>
    </thead>
    <tbody>
        @foreach($contracts as $contract)
        <tr>
            <td>{{ $contract->staff->first_name }} {{ $contract->staff->last_name }}</td>
            <td>{{ $contract->type }}</td>
            <td>{{ $contract->start_date }}</td>
            <td>{{ $contract->end_date }}</td>
            <td>{{ $contract->duration_months }}</td>
            <td>{{ $contract->on_probation ? 'Yes' : 'No' }} @if($contract->on_probation && $contract->probation_end) (ends {{ $contract->probation_end }}) @endif</td>
            <td>@if($contract->promotion_from && $contract->promotion_to) {{ $contract->promotion_from }} → {{ $contract->promotion_to }} ({{ $contract->promotion_date }}) @endif</td>
            <td>@if($contract->transfer_from_department && $contract->transfer_to_department) Dept {{ $contract->transfer_from_department }} → {{ $contract->transfer_to_department }} @endif</td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $contracts->links() }}
@endsection 