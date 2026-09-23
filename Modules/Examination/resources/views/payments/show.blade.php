@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Payment Details</h1>

    <div class="bg-white rounded shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Reference: {{ $payment->reference }}</h2>
            <span class="px-3 py-1 text-sm font-medium rounded-full
                {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' :
                   ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                {{ ucfirst($payment->status) }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Exam</p>
                <p class="font-medium">{{ $payment->exam->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500">Amount</p>
                <p class="font-medium text-lg">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</p>
            </div>
            <div>
                <p class="text-gray-500">Payment Method</p>
                <p class="font-medium capitalize">{{ str_replace('_', ' ', $payment->method) }}</p>
            </div>
            <div>
                <p class="text-gray-500">Date</p>
                <p class="font-medium">{{ $payment->paid_at?->format('M d, Y H:i') ?? $payment->created_at->format('M d, Y H:i') }}</p>
            </div>
            @if($payment->transaction_code)
            <div>
                <p class="text-gray-500">Transaction Code</p>
                <p class="font-medium">{{ $payment->transaction_code }}</p>
            </div>
            @endif
            @if($payment->phone)
            <div>
                <p class="text-gray-500">Phone</p>
                <p class="font-medium">{{ $payment->phone }}</p>
            </div>
            @endif
            @if($payment->notes)
            <div class="col-span-2">
                <p class="text-gray-500">Notes</p>
                <p class="font-medium">{{ $payment->notes }}</p>
            </div>
            @endif
        </div>

        <div class="mt-6 space-x-2">
            <a href="{{ route('examination.payments.receipt', $payment) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">View Receipt</a>
            <a href="{{ route('examination.payments.index') }}" class="text-blue-600 hover:underline">&larr; Back to Payments</a>
        </div>
    </div>
</div>
@endsection
