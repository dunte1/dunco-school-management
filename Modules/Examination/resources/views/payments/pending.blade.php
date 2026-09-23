@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Payment Pending</h1>

    <div class="bg-white rounded shadow p-6 text-center">
        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h2 class="text-xl font-semibold text-gray-800 mb-2">Payment Submitted</h2>
        <p class="text-gray-500 mb-6">Your payment is being verified. You will be notified once it is confirmed.</p>

        <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Reference:</span>
                    <span class="font-medium">{{ $payment->reference }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Amount:</span>
                    <span class="font-medium">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Method:</span>
                    <span class="font-medium capitalize">{{ str_replace('_', ' ', $payment->method) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status:</span>
                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Pending Verification</span>
                </div>
                @if($payment->transaction_code)
                <div class="flex justify-between">
                    <span class="text-gray-500">Transaction Code:</span>
                    <span class="font-medium">{{ $payment->transaction_code }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="space-y-3">
            <a href="{{ route('examination.exams.show', $payment->exam) }}" class="block w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition text-center">
                Back to Exam
            </a>
            <a href="{{ route('examination.payments.show', $payment) }}" class="block w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-200 transition text-center">
                View Payment Details
            </a>
        </div>
    </div>
</div>
@endsection
