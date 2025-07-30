@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Hostel Fee Defaulter List</h1>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Student</th>
                <th>Hostel</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($defaulters as $fee)
                <tr>
                    <td>{{ $fee->student->name ?? 'N/A' }}</td>
                    <td>{{ $fee->hostel->name ?? 'N/A' }}</td>
                    <td>{{ $fee->amount }}</td>
                    <td>{{ ucfirst($fee->status) }}</td>
                    <td>{{ $fee->due_date }}</td>
                    <td>{{ $fee->notes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $defaulters->links() }}
</div>
@endsection
