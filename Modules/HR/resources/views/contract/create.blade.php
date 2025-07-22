@extends('hr::layouts.app')

@section('content')
<h2>Add Contract</h2>
<form method="POST" action="{{ route('hr.contract.store') }}">
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
        <label class="form-label">Contract Type</label>
        <select name="type" class="form-select" required>
            <option value="">Select Type</option>
            <option value="Permanent">Permanent</option>
            <option value="Contract">Contract</option>
            <option value="Probation">Probation</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Duration (months)</label>
        <input type="number" name="duration_months" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">On Probation?</label>
        <select name="on_probation" class="form-select">
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Probation End</label>
        <input type="date" name="probation_end" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Renewal Reminder</label>
        <input type="date" name="renewal_reminder" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Promotion (from → to)</label>
        <input type="text" name="promotion_from" class="form-control" placeholder="Old Position">
        <input type="text" name="promotion_to" class="form-control mt-2" placeholder="New Position">
        <input type="date" name="promotion_date" class="form-control mt-2" placeholder="Promotion Date">
        <input type="number" name="old_salary" class="form-control mt-2" placeholder="Old Salary">
        <input type="number" name="new_salary" class="form-control mt-2" placeholder="New Salary">
    </div>
    <div class="mb-3">
        <label class="form-label">Transfer (from → to department)</label>
        <select name="transfer_from_department" class="form-select">
            <option value="">From Department</option>
            @foreach($departments as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
            @endforeach
        </select>
        <select name="transfer_to_department" class="form-select mt-2">
            <option value="">To Department</option>
            @foreach($departments as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="{{ route('hr.contract.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection 