@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Fee Category: {{ $category->name ?? '—' }}</h1>

    <div class="bg-white rounded shadow p-4">
        <p><strong>Name:</strong> {{ $category->name ?? '—' }}</p>
        <p class="mt-2"><strong>Description:</strong> {{ $category->description ?? '—' }}</p>
    </div>

    <a href="{{ route('finance.fee-categories.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">&larr; Back</a>
</div>
@endsection
