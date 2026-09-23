@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Payment Receipt</h1>

    <div class="bg-white rounded shadow p-6">
        <div class="text-center mb-6">
            <h2 class="text-xl font-bold text-green-600">Payment Confirmed</h2>
            <p class="text-sm text-gray-500">Thank you for your payment</p>
        </div>

        <div class="border-t border-b py-4 mb-4">
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Receipt No:</span>
                    <span class="font-mono font-medium">{{ $payment->reference }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Date:</span>
                    <span class="font-medium">{{ $payment->paid_at?->format('M d, Y H:i') ?? $payment->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Student:</span>
                    <span class="font-medium">{{ $payment->student->name ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Exam:</span>
                    <span class="font-medium">{{ $payment->exam->name ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Exam Code:</span>
                    <span class="font-medium">{{ $payment->exam->code ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Payment Method:</span>
                    <span class="font-medium capitalize">{{ str_replace('_', ' ', $payment->method) }}</span>
                </div>
                @if($payment->transaction_code)
                <div class="flex justify-between">
                    <span class="text-gray-500">Transaction Code:</span>
                    <span class="font-medium">{{ $payment->transaction_code }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="text-center mb-6">
            <p class="text-3xl font-bold text-green-600">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</p>
            <p class="text-sm text-gray-500">Amount Paid</p>
        </div>

        <div class="text-center text-xs text-gray-400 mb-4">
            <p>This is a computer-generated receipt.</p>
            <p>For any queries, contact the finance office.</p>
        </div>

        <div class="space-y-3">
            <a href="{{ route('examination.exams.show', $payment->exam) }}" class="block w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition text-center">
                Proceed to Exam
            </a>
            <button onclick="window.print()" class="block w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-200 transition text-center w-full">
                Print Receipt
            </button>
        </div>
    </div>
</div>
@endsection
