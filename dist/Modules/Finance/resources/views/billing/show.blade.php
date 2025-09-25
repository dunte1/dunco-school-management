@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Invoice Details</h1>
    <div class="bg-white p-6 rounded shadow mb-4">
        <p><strong>Student:</strong> {{ $invoice->student->name ?? '-' }}</p>
        <p><strong>Due Date:</strong> {{ $invoice->due_date }}</p>
        <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
    </div>
    <div class="bg-white p-6 rounded shadow mb-4">
        <h2 class="text-xl font-semibold mb-2">Fees</h2>
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">Fee</th>
                    <th class="px-4 py-2">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td class="border px-4 py-2">{{ $item->fee->name ?? '-' }}</td>
                    <td class="border px-4 py-2">{{ number_format($item->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4 text-right">
            <strong>Total: {{ number_format($invoice->total_amount, 2) }}</strong>
        </div>
    </div>
    <a href="{{ route('finance.billing.index') }}" class="text-blue-600 hover:underline">Back to Invoices</a>
</div>
@endsection 