@extends('academic::layouts.app')

@section('content')
<div class="container">
    <h1>Grading Scales</h1>
    <a href="{{ route('academic.grading.create') }}" class="btn btn-primary mb-3">Add Grading Scale</a>
    @foreach($scales as $scale)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>{{ $scale->name }}</span>
                <a href="{{ route('academic.grading.edit', $scale->id) }}" class="btn btn-sm btn-secondary">Edit</a>
            </div>
            <div class="card-body">
                <p>{{ $scale->description }}</p>
                <h5>Grades</h5>
                <ul>
                    @foreach($scale->grades as $grade)
                        <li>{{ $grade->name }}: {{ $grade->min_score }} - {{ $grade->max_score }} ({{ $grade->description }})</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endforeach
</div>
@endsection 