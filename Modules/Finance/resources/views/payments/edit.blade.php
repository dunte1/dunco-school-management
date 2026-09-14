@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Edit Payment #{{ $payment->id ?? '' }}</h1>

    <form method="POST" action="{{ route('finance.payments.update', $payment->id ?? 0) }}" class="bg-white rounded shadow p-4 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1">Invoice ID</label>
            <input type="text" name="invoice_id" value="{{ old('invoice_id', $payment->invoice_id ?? '') }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block mb-1">Amount</label>
            <input type="number" step="0.01" name="amount" value="{{ old('amount', $payment->amount ?? '') }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block mb-1">Payment Date</label>
            <input type="date" name="payment_date" value="{{ old('payment_date', optional($payment->payment_date ?? null)->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block mb-1">Method</label>
            <input type="text" name="method" value="{{ old('method', $payment->method ?? '') }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block mb-1">Status</label>
            <input type="text" name="status" value="{{ old('status', $payment->status ?? '') }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block mb-1">Reference</label>
            <input type="text" name="reference" value="{{ old('reference', $payment->reference ?? '') }}" class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        <a href="{{ route('finance.payments.index') }}" class="ml-2 text-blue-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
