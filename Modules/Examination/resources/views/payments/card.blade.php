@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Card Payment</h1>

    <div class="bg-white rounded shadow p-6">
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-purple-800">Pay {{ $payment->currency }} {{ number_format($payment->amount, 2) }}</p>
                    <p class="text-sm text-purple-600">Reference: {{ $payment->reference }}</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('examination.payments.confirm-card', $payment) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cardholder Name *</label>
                    <input type="text" name="cardholder_name" placeholder="JOHN DOE" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" required>
                    @error('cardholder_name')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Last 4 Digits of Card *</label>
                    <input type="text" name="card_last_four" maxlength="4" pattern="[0-9]{4}" placeholder="1234" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" required>
                    @error('card_last_four')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6 space-y-3">
                <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg font-medium hover:bg-purple-700 transition">
                    Process Card Payment
                </button>
                <a href="{{ route('examination.payments.select-method', $exam) }}" class="block text-center text-blue-600 hover:underline text-sm">Change Payment Method</a>
            </div>
        </form>
    </div>
</div>
@endsection
