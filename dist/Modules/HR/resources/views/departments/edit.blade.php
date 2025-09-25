@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Edit Department</h2>
        <a href="{{ route('hr.departments.index') }}" class="btn btn-secondary">Back</a>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('hr.departments.update', $department->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $department->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <input type="text" name="type" class="form-control" value="{{ $department->type }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">School ID</label>
                    <input type="number" name="school_id" class="form-control" value="{{ $department->school_id }}">
                </div>
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('hr.departments.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection 