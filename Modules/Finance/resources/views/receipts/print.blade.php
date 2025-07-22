@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white rounded shadow p-8 max-w-lg mx-auto border border-gray-300">
        <div class="flex justify-between items-center mb-6">
            <img src="/images/school-logo.png" alt="School Logo" class="h-12">
            <div class="text-right">
                <div class="font-bold text-lg">{{ config('app.name', 'School Name') }}</div>
                <div class="text-sm text-gray-500">{{ config('app.address', 'School Address') }}</div>
            </div>
        </div>
        <h2 class="text-2xl font-bold mb-4 text-center">Payment Receipt</h2>
        <div class="mb-2"><strong>Receipt #:</strong> {{ $receipt->id }}</div>
        <div class="mb-2"><strong>Invoice #:</strong> {{ $receipt->invoice ? $receipt->invoice->id : '-' }}</div>
        @if($receipt->invoice && $receipt->invoice->student)
            <div class="mb-2"><strong>Student Name:</strong> {{ $receipt->invoice->student->name }}</div>
            <div class="mb-2"><strong>Admission #:</strong> {{ $receipt->invoice->student->admission_number }}</div>
            <div class="mb-2"><strong>Class:</strong> {{ $receipt->invoice->student->class->name ?? '-' }}</div>
        @endif
        <div class="mb-2"><strong>Amount Paid:</strong> {{ number_format($receipt->amount, 2) }}</div>
        <div class="mb-2"><strong>Date:</strong> {{ $receipt->payment_date ? \Carbon\Carbon::parse($receipt->payment_date)->format('d M Y') : '-' }}</div>
        <div class="mb-2"><strong>Payment Method:</strong> {{ $receipt->method }}</div>
        <div class="mb-2"><strong>Status:</strong> {{ ucfirst($receipt->status) }}</div>
        <div class="mb-2"><strong>Reference:</strong> {{ $receipt->reference ?? '-' }}</div>
        @if($receipt->invoice && $receipt->invoice->items->count())
            <div class="mt-6 mb-2 font-semibold">Payment Breakdown:</div>
            <table class="w-full text-sm mb-4">
                <thead>
                    <tr>
                        <th class="text-left">Fee</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receipt->invoice->items as $item)
                        <tr>
                            <td>{{ $item->fee->name ?? '-' }}</td>
                            <td class="text-right">{{ number_format($item->amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <div class="mt-6 text-center text-gray-500 text-sm">Thank you for your payment.</div>
    </div>
</div>
@if(app('request')->routeIs('finance.receipts.print'))
<script>window.onload = function() { window.print(); }</script>
@endif
@endsection 