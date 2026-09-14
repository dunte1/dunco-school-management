@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Edit Receipt #{{ $receipt->id ?? '' }}</h1>

    <form method="POST" action="{{ route('finance.receipts.update', $receipt->id ?? 0) }}" class="bg-white rounded shadow p-4 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1">Amount</label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $receipt->amount ?? '') }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block mb-1">Reference</label>
            <input type="text" name="reference" value="{{ old('reference', $receipt->reference ?? '') }}" class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        <a href="{{ route('finance.receipts.index') }}" class="ml-2 text-blue-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
