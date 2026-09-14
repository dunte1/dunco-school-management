@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Exam Type: {{ $type->name }}</h1>

    <div class="bg-white rounded shadow p-4 space-y-2">
        <p><strong>Name:</strong> {{ $type->name }}</p>
        <p><strong>Code:</strong> {{ $type->code }}</p>
        <p><strong>Description:</strong> {{ $type->description ?? '—' }}</p>
        <p><strong>Online:</strong> {{ $type->is_online ? 'Yes' : 'No' }}</p>
        <p><strong>Active:</strong> {{ $type->is_active ? 'Yes' : 'No' }}</p>
    </div>

    <div class="mt-4">
        <a href="{{ route('examination.exam-types.edit', $type) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</a>
        <a href="{{ route('examination.exam-types.index') }}" class="ml-2 text-blue-600 hover:underline">&larr; Back</a>
    </div>
</div>
@endsection
