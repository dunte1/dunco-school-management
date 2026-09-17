@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">{{ $exam->name }}</h1>
    <div class="bg-white rounded shadow p-4 space-y-2">
        <p><strong>Code:</strong> {{ $exam->code }}</p>
        <p><strong>Type:</strong> {{ $exam->type->name ?? '—' }}</p>
        <p><strong>Academic Year:</strong> {{ $exam->academic_year }} ({{ $exam->term }})</p>
        <p><strong>Dates:</strong> {{ $exam->start_date }} → {{ $exam->end_date }}</p>
        <p><strong>Total / Passing:</strong> {{ $exam->total_marks }} / {{ $exam->passing_marks }}</p>
        <p><strong>Status:</strong> {{ ucfirst($exam->status) }}</p>
        <p><strong>Results:</strong> {{ $exam->results_count ?? $exam->results()->count() }}</p>
    </div>
    <div class="mt-4 space-x-2">
        <a href="{{ route('examination.exams.edit', $exam) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</a>
        <a href="{{ route('examination.exams.results', $exam) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Results</a>
        <a href="{{ route('examination.exams.index') }}" class="text-blue-600 hover:underline">&larr; Back</a>
    </div>
</div>
@endsection
