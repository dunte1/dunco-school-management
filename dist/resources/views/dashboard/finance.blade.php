@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Finance Dashboard</h1>
                <div class="text-muted">Welcome back, {{ Auth::user()->name }}!</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body text-center py-5">
                    <i class="fas fa-dollar-sign fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Finance Dashboard</h4>
                    <p class="text-muted">This dashboard is under development.</p>
                    <p class="text-muted">Please contact the administrator for more information.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




