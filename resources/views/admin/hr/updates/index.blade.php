@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3"><i class="fas fa-users me-2"></i>HR & Staff Updates</h1>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-5"><input type="text" class="form-control" placeholder="Search staff or update..." /></div>
                <div class="col-md-3"><input type="date" class="form-control" /></div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option value="">All Types</option>
                        <option>New Hire</option>
                        <option>Promotion</option>
                        <option>Transfer</option>
                    </select>
                </div>
                <div class="col-md-2 text-end"><button class="btn btn-outline-secondary w-100">Filter</button></div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Staff</th>
                            <th>Type</th>
                            <th>Details</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="4" class="text-muted">No updates found. Connect to your data source.</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <nav><ul class="pagination mb-0"><li class="page-item disabled"><span class="page-link">Pagination</span></li></ul></nav>
            </div>
        </div>
    </div>
</div>
@endsection
