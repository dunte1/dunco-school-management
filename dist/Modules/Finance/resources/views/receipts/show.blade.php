@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Receipt #{{ $receipt->id }}</h1>
    <div class="bg-white rounded shadow p-6 max-w-lg mx-auto">
        <div class="mb-2"><strong>Invoice #:</strong> {{ $receipt->invoice ? $receipt->invoice->id : '-' }}</div>
        <div class="mb-2"><strong>Amount:</strong> {{ number_format($receipt->amount, 2) }}</div>
        <div class="mb-2"><strong>Date:</strong> {{ $receipt->payment_date ? \Carbon\Carbon::parse($receipt->payment_date)->format('d M Y') : '-' }}</div>
        <div class="mb-2"><strong>Method:</strong> {{ $receipt->method }}</div>
        <div class="mb-2"><strong>Status:</strong> {{ ucfirst($receipt->status) }}</div>
        <div class="mb-2"><strong>Reference:</strong> {{ $receipt->reference ?? '-' }}</div>
        <a href="{{ route('finance.receipts.print', $receipt) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mr-2" target="_blank">Print</a>
        <a href="{{ route('finance.receipts.download', $receipt) }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">Download PDF</a>
        <a href="{{ route('finance.receipts.index') }}" class="ml-2 text-gray-600 hover:underline">Back to Receipts</a>
    </div>
</div>
@endsection 