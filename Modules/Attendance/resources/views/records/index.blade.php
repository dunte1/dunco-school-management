@extends('layouts.app')

@section('title', 'Attendance Records')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Attendance Records</h4>
                </div>
                <div class="card-body">
                    <p>Attendance records management will be implemented here.</p>
                    <p>Records found: {{ $records->count() ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
