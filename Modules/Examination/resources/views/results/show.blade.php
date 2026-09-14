@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Result Detail</h1>

    <div class="bg-white rounded shadow p-4 space-y-2">
        <p><strong>Exam:</strong> {{ $result->exam->name ?? '—' }}</p>
        <p><strong>Student:</strong> {{ $result->student->name ?? '—' }}</p>
        <p><strong>Marks:</strong> {{ $result->obtained_marks }} / {{ $result->total_marks }}</p>
        <p><strong>Percentage:</strong> {{ $result->percentage }}%</p>
        <p><strong>Grade:</strong> {{ $result->grade ?? '—' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($result->result_status ?? '—') }}</p>
        <p><strong>Published:</strong> {{ $result->is_published ? 'Yes' : 'No' }}</p>
        <p><strong>Remarks:</strong> {{ $result->remarks ?? '—' }}</p>
    </div>

    <div class="mt-4">
        <a href="{{ route('examination.results.edit', $result) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</a>
        <a href="{{ route('examination.results.index') }}" class="ml-2 text-blue-600 hover:underline">&larr; Back</a>
    </div>
</div>
@endsection
