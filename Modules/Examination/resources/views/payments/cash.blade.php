@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Cash Payment</h1>

    <div class="bg-white rounded shadow p-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-yellow-800 mb-2">Cash Payment Instructions</h3>
            <div class="space-y-2 text-sm text-yellow-700">
                <p>1. Visit the <strong>Finance Office</strong> during business hours (8:00 AM - 4:00 PM)</p>
                <p>2. Present your reference number: <strong>{{ $payment->reference }}</strong></p>
                <p>3. Pay <strong>{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</strong></p>
                <p>4. Collect your payment receipt</p>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <p class="text-sm text-gray-600"><strong>Exam:</strong> {{ $exam->name }}</p>
            <p class="text-sm text-gray-600"><strong>Amount:</strong> {{ $payment->currency }} {{ number_format($payment->amount, 2) }}</p>
            <p class="text-sm text-gray-600"><strong>Reference:</strong> {{ $payment->reference }}</p>
        </div>

        <form method="POST" action="{{ route('examination.payments.confirm-cash', $payment) }}">
            @csrf
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-green-800">
                    <strong>Note:</strong> Clicking "Confirm Cash Payment" below will mark your payment as completed. This should only be done after you have physically paid at the finance office.
                </p>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-medium hover:bg-green-700 transition" onclick="return confirm('Have you already paid at the finance office?')">
                Confirm Cash Payment
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="{{ route('examination.payments.select-method', $exam) }}" class="text-blue-600 hover:underline text-sm">Change Payment Method</a>
        </div>
    </div>
</div>
@endsection
