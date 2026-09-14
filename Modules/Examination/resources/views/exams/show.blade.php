@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Exam: {{ $exam->name ?? '—' }}</h1>

    <div class="bg-white rounded shadow p-4 space-y-2">
        <p><strong>Code:</strong> {{ $exam->code ?? '—' }}</p>
        <p><strong>Type:</strong> {{ $exam->type->name ?? '—' }}</p>
        <p><strong>Academic Year:</strong> {{ $exam->academic_year ?? '—' }} ({{ $exam->term ?? '—' }})</p>
        <p><strong>Dates:</strong> {{ optional($exam->start_date)->format('Y-m-d') ?? '—' }} → {{ optional($exam->end_date)->format('Y-m-d') ?? '—' }}</p>
        <p><strong>Total / Passing:</strong> {{ $exam->total_marks ?? 0 }} / {{ $exam->passing_marks ?? 0 }}</p>
        <p><strong>Status:</strong> {{ ucfirst($exam->status ?? '—') }}</p>
    </div>

    <div class="mt-4">
        <a href="{{ route('examination.exams.edit', $exam) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</a>
        <a href="{{ route('examination.exams.results', $exam) }}" class="ml-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Results</a>
        <a href="{{ route('examination.exams.index') }}" class="ml-2 text-blue-600 hover:underline">&larr; Back</a>
    </div>
</div>
@endsection
