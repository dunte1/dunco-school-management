@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">M-Pesa Payment</h1>

    <div class="bg-white rounded shadow p-6">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-green-800">Pay {{ $payment->currency }} {{ number_format($payment->amount, 2) }}</p>
                    <p class="text-sm text-green-600">Reference: {{ $payment->reference }}</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('examination.payments.confirm-mpesa', $payment) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">M-Pesa Phone Number *</label>
                    <input type="tel" name="phone" placeholder="0712345678" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                    <p class="text-xs text-gray-500 mt-1">Enter the phone number registered with M-Pesa</p>
                    @error('phone')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">M-Pesa Transaction Code *</label>
                    <input type="text" name="transaction_code" placeholder="QKH4B7V2S1" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                    <p class="text-xs text-gray-500 mt-1">Enter the confirmation code received from Safaricom</p>
                    @error('transaction_code')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-6 space-y-3">
                <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-medium hover:bg-green-700 transition">
                    Submit Payment
                </button>
                <a href="{{ route('examination.payments.select-method', $exam) }}" class="block text-center text-blue-600 hover:underline text-sm">Change Payment Method</a>
            </div>
        </form>
    </div>
</div>
@endsection
