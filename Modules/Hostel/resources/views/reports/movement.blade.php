@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Student Movement Logs</h1>

    @if(isset($summary))
    <div class="row mt-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="card-title">Total Visitors</h6>
                    <h3>{{ $summary['total_visitors'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h6 class="card-title">Today's Visitors</h6>
                    <h3 class="text-primary">{{ $summary['today_visitors'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h6 class="card-title">Currently In</h6>
                    <h3 class="text-success">{{ $summary['currently_in'] }}</h3>
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
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-2">
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search visitor...">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('hostel.reports.movement') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <table class="table table-bordered table-striped mt-3">
        <thead class="thead-dark">
            <tr>
                <th>Pass #</th>
                <th>Visitor Name</th>
                <th>Contact</th>
                <th>Student</th>
                <th>Hostel</th>
                <th>Purpose</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($visitors as $visitor)
                <tr>
                    <td>{{ $visitor->pass_number ?? '-' }}</td>
                    <td>{{ $visitor->visitor_name }}</td>
                    <td>{{ $visitor->visitor_contact }}</td>
                    <td>{{ $visitor->student->name ?? 'N/A' }}</td>
                    <td>{{ $visitor->hostel->name ?? 'N/A' }}</td>
                    <td>{{ $visitor->purpose ?? '-' }}</td>
                    <td>{{ $visitor->time_in ? $visitor->time_in->format('M d, Y H:i') : '-' }}</td>
                    <td>{{ $visitor->time_out ? $visitor->time_out->format('M d, Y H:i') : '-' }}</td>
                    <td>
                        @if($visitor->time_out)
                            <span class="badge badge-secondary">Left</span>
                        @else
                            <span class="badge badge-success">In Campus</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">No visitor records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $visitors->links() }}
</div>
@endsection
