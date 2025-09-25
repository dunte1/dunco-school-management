@extends('layouts.app')

@section('content')
<h2>Leave Balances</h2>
<p class="text-muted">(Feature coming soon: This will show remaining leave days per staff and leave type.)</p>
<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Staff</th>
            @foreach($types as $type)
                <th>{{ $type->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($staff as $s)
        <tr>
            <td>{{ $s->first_name }} {{ $s->last_name }}</td>
            @foreach($types as $type)
                <td>-</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
@endsection 