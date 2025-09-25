@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Ledger Entry Details</h1>
    <div class="bg-white p-6 rounded shadow">
        <p><strong>Date:</strong> {{ $entry->date }}</p>
        <p><strong>Account:</strong> {{ $entry->account }}</p>
        <p><strong>Type:</strong> {{ ucfirst($entry->type) }}</p>
        <p><strong>Description:</strong> {{ $entry->description }}</p>
        <p><strong>Debit:</strong> {{ number_format($entry->debit, 2) }}</p>
        <p><strong>Credit:</strong> {{ number_format($entry->credit, 2) }}</p>
        <p><strong>Reference:</strong> {{ $entry->reference }}</p>
        <p><strong>Related:</strong> {{ $entry->related_type }} #{{ $entry->related_id }}</p>
    </div>
    <a href="{{ route('finance.gl.index') }}" class="text-blue-600 hover:underline mt-4 inline-block">Back to General Ledger</a>
</div>
@endsection 