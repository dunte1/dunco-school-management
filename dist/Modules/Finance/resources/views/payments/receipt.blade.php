@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-8 bg-white rounded shadow max-w-lg mt-8">
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold">Payment Receipt</h1>
        <p class="text-gray-600">#{{ $payment->id }}</p>
    </div>
    <div class="mb-4">
        <strong>Student:</strong> {{ $payment->invoice->student->name ?? '-' }}<br>
        <strong>Invoice:</strong> #{{ $payment->invoice_id }}<br>
        <strong>Amount Paid:</strong> {{ number_format($payment->amount, 2) }}<br>
        <strong>Date:</strong> {{ $payment->payment_date }}<br>
        <strong>Method:</strong> {{ $payment->method ?? '-' }}<br>
        <strong>Reference:</strong> {{ $payment->reference ?? '-' }}<br>
    </div>
    <div class="mb-4">
        <strong>Status:</strong> {{ ucfirst($payment->status) }}<br>
    </div>
    <div class="text-center mt-8">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Print Receipt</button>
        <a href="{{ route('finance.payments.index') }}" class="ml-4 text-gray-600 hover:underline">Back to Payments</a>
    </div>
</div>
@endsection 