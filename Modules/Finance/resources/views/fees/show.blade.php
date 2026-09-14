@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Fee: {{ $fee->name ?? '—' }}</h1>

    <ul class="bg-white rounded shadow p-4 space-y-2">
        <li><strong>Amount:</strong> {{ number_format((float) ($fee->amount ?? 0), 2) }}</li>
        <li><strong>Category:</strong> {{ $fee->category->name ?? '—' }}</li>
        <li><strong>Type:</strong> {{ $fee->type->name ?? '—' }}</li>
        <li><strong>Description:</strong> {{ $fee->description ?? '—' }}</li>
    </ul>

    <a href="{{ route('finance.fees.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">&larr; Back</a>
</div>
@endsection
