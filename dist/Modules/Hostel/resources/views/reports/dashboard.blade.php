@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Hostel Reports & Analytics</h1>
    <div class="list-group mt-4">
        <a href="{{ route('hostel.reports.occupancy') }}" class="list-group-item list-group-item-action">Occupancy Report</a>
        <a href="{{ route('hostel.reports.allocation') }}" class="list-group-item list-group-item-action">Room Allocation Report</a>
        <a href="{{ route('hostel.reports.maintenance') }}" class="list-group-item list-group-item-action">Maintenance Report</a>
        <a href="{{ route('hostel.reports.movement') }}" class="list-group-item list-group-item-action">Student Movement Logs</a>
        <a href="{{ route('hostel.reports.defaulters') }}" class="list-group-item list-group-item-action">Hostel Fee Defaulter List</a>
        <a href="{{ route('hostel.reports.damage') }}" class="list-group-item list-group-item-action">Damage/Fine Reports</a>
    </div>
</div>
@endsection
