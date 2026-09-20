@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Hostel Fee Defaulter List</h1>

    @if(isset($summary))
    <div class="row mt-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title">Total Unpaid</h6>
                    <h3 class="text-danger">{{ $summary['total_unpaid'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h6 class="card-title">Total Overdue</h6>
                    <h3 class="text-warning">{{ $summary['total_overdue'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-danger">
                <div class="card-body">
                    <h6 class="card-title">Unpaid Amount</h6>
                    <h3>&#8358;{{ number_format($summary['total_unpaid_amount'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-dark">
                <div class="card-body">
                    <h6 class="card-title">Total Fine</h6>
                    <h3>&#8358;{{ number_format($summary['total_fine'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>
    @endif

    <form method="GET" class="row mb-3">
        <div class="col-md-3">
            <select name="hostel_id" class="form-control">
                <option value="">All Hostels</option>
                @foreach($allHostels as $hostel)
                    <option value="{{ $hostel->id }}" {{ request('hostel_id') == $hostel->id ? 'selected' : '' }}>
                        {{ $hostel->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-control">
                <option value="">All Unpaid</option>
                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Due from">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Due to">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('hostel.reports.defaulters') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <table class="table table-bordered table-striped mt-3">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Student</th>
                <th>Hostel</th>
                <th>Room</th>
                <th>Bed</th>
                <th>Amount</th>
                <th>Fine</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @forelse($defaulters as $fee)
                <tr>
                    <td>{{ $fee->id }}</td>
                    <td>{{ $fee->student->name ?? 'N/A' }}</td>
                    <td>{{ $fee->hostel->name ?? 'N/A' }}</td>
                    <td>{{ $fee->room->name ?? '-' }}</td>
                    <td>{{ $fee->bed->bed_number ?? '-' }}</td>
                    <td>&#8358;{{ number_format($fee->amount, 2) }}</td>
                    <td>&#8358;{{ number_format($fee->fine ?? 0, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $fee->status === 'overdue' ? 'danger' : 'warning' }}">
                            {{ ucfirst($fee->status) }}
                        </span>
                    </td>
                    <td>{{ $fee->due_date ? $fee->due_date->format('M d, Y') : '-' }}</td>
                    <td>{{ Str::limit($fee->notes, 30) ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted">No fee defaulters found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $defaulters->links() }}
</div>
@endsection
