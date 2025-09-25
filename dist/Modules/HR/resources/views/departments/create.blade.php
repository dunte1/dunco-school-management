@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Add Department</h2>
        <a href="{{ route('hr.departments.index') }}" class="btn btn-secondary">Back</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('hr.departments.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <input type="text" name="type" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">School ID</label>
                    <input type="number" name="school_id" class="form-control">
                </div>
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('hr.departments.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection 