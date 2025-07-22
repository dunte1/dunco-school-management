@extends('academic::layouts.app')

@section('content')
<div class="container">
    <h1>Edit Grading Scale</h1>
    <form method="POST" action="{{ route('academic.grading.update', $scale->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $scale->name }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ $scale->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('academic.grading.index') }}" class="btn btn-secondary">Cancel</a>
    </form>

    <hr>
    <h2>Grades</h2>
    <form method="POST" action="{{ route('academic.grading.grade.store', $scale->id) }}" class="mb-4">
        @csrf
        <div class="row">
            <div class="col">
                <input type="text" name="name" class="form-control" placeholder="Grade Name (e.g. A)" required>
            </div>
            <div class="col">
                <input type="number" name="min_score" class="form-control" placeholder="Min Score" min="0" max="100" required>
            </div>
            <div class="col">
                <input type="number" name="max_score" class="form-control" placeholder="Max Score" min="0" max="100" required>
            </div>
            <div class="col">
                <input type="text" name="description" class="form-control" placeholder="Description">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Add Grade</button>
            </div>
        </div>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Min</th>
                <th>Max</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scale->grades as $grade)
                <tr>
                    <form method="POST" action="{{ route('academic.grading.grade.update', [$scale->id, $grade->id]) }}">
                        @csrf
                        @method('PUT')
                        <td><input type="text" name="name" value="{{ $grade->name }}" class="form-control" required></td>
                        <td><input type="number" name="min_score" value="{{ $grade->min_score }}" class="form-control" min="0" max="100" required></td>
                        <td><input type="number" name="max_score" value="{{ $grade->max_score }}" class="form-control" min="0" max="100" required></td>
                        <td><input type="text" name="description" value="{{ $grade->description }}" class="form-control"></td>
                        <td>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </form>
                    <form method="POST" action="{{ route('academic.grading.grade.destroy', [$scale->id, $grade->id]) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                        </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 