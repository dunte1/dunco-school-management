@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Record Payment</h1>
    <form action="{{ route('finance.payments.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">Invoice</label>
            <select name="invoice_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Select Invoice --</option>
                @foreach($invoices as $invoice)
                    <option value="{{ $invoice->id }}">#{{ $invoice->id }} - {{ $invoice->student->name ?? '' }} ({{ number_format($invoice->total_amount, 2) }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Amount</label>
            <input type="number" name="amount" step="0.01" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Payment Date</label>
            <input type="date" name="payment_date" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Method</label>
            <input type="text" name="method" class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Reference</label>
            <input type="text" name="reference" class="w-full border rounded px-3 py-2">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Record</button>
        <a href="{{ route('finance.payments.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection 