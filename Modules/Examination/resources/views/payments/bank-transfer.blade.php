@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Bank Transfer Payment</h1>

    <div class="bg-white rounded shadow p-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-blue-800 mb-2">Bank Details</h3>
            <div class="space-y-1 text-sm text-blue-700">
                <p><strong>Bank:</strong> KCB Bank</p>
                <p><strong>Account Name:</strong> Dunco School Management</p>
                <p><strong>Account Number:</strong> 1234567890</p>
                <p><strong>Branch:</strong> Nairobi Main</p>
                <p><strong>Amount:</strong> {{ $payment->currency }} {{ number_format($payment->amount, 2) }}</p>
                <p><strong>Reference:</strong> {{ $payment->reference }}</p>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-yellow-800">
                <strong>Important:</strong> Please include your reference number <strong>{{ $payment->reference }}</strong> in the transfer description. Your payment will be verified within 24 hours.
            </p>
        </div>

        <form method="POST" action="{{ route('examination.payments.confirm-bank', $payment) }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bank Transfer Reference *</label>
                    <input type="text" name="reference" placeholder="e.g., SBQKR4T7P2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <p class="text-xs text-gray-500 mt-1">Enter the transaction reference from your bank</p>
                    @error('reference')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                    <textarea name="notes" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Any additional information..."></textarea>
                </div>
            </div>

            <div class="mt-6 space-y-3">
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                    Submit Bank Transfer
                </button>
                <a href="{{ route('examination.payments.select-method', $exam) }}" class="block text-center text-blue-600 hover:underline text-sm">Change Payment Method</a>
            </div>
        </form>
    </div>
</div>
@endsection
