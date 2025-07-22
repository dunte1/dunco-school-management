@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Payment Details</h1>
    <div class="bg-white p-6 rounded shadow mb-4">
        <p><strong>Invoice:</strong> #{{ $payment->invoice_id }}</p>
        <p><strong>Amount:</strong> {{ number_format($payment->amount, 2) }}</p>
        <p><strong>Date:</strong> {{ $payment->payment_date }}</p>
        <p><strong>Method:</strong> {{ $payment->method ?? '-' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
        <p><strong>Reference:</strong> {{ $payment->reference ?? '-' }}</p>
    </div>
    <a href="{{ route('finance.payments.index') }}" class="text-blue-600 hover:underline">Back to Payments</a>
</div>
@endsection 