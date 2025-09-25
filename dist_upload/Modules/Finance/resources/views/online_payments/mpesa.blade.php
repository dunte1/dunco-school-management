@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto max-w-2xl p-6">
    <h1 class="text-3xl font-semibold mb-6 text-gray-800">Pay with MPESA</h1>

    <div class="bg-white p-6 rounded shadow-sm border mb-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Invoice Summary</h2>
        <ul class="text-gray-600 space-y-2">
            <li><strong>Invoice #:</strong> {{ $invoice->id }}</li>
            <li><strong>Student:</strong> {{ $invoice->student->name ?? '-' }}</li>
            <li><strong>Total Amount:</strong> KES {{ number_format($invoice->total_amount, 2) }}</li>
            <li><strong>Status:</strong> <span class="uppercase">{{ $invoice->status }}</span></li>
        </ul>
    </div>

    <form id="mpesa-form" action="{{ route('finance.online-payments.mpesa.callback') }}" method="POST" class="bg-white p-6 rounded shadow-sm border">
        @csrf
        <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
        <input type="hidden" name="amount" value="{{ $invoice->total_amount }}">

        <div class="mb-4">
            <label class="block font-medium mb-1 text-gray-700">MPESA Phone Number</label>
            <input
                type="text"
                name="mpesa_phone"
                placeholder="e.g. 0712345678"
                class="w-full border px-4 py-2 rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300"
                required
            >
        </div>

        <div class="mb-6">
            <label class="block font-medium mb-1 text-gray-700">MPESA Transaction Code</label>
            <input
                type="text"
                name="mpesa_transaction_code"
                placeholder="e.g. QW123456XYZ"
                class="w-full border px-4 py-2 rounded shadow-sm focus:outline-none focus:ring focus:border-blue-300"
                required
            >
        </div>

        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded shadow">
            Simulate MPESA Payment
        </button>
    </form>
</div>
@endsection
