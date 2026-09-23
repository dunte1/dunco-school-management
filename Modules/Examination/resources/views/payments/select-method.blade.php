@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Select Payment Method</h1>

    <div class="bg-white rounded shadow p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold">{{ $exam->name }}</h2>
                <p class="text-sm text-gray-500">{{ $exam->code }}</p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-green-600">{{ $exam->currency ?? 'KES' }} {{ number_format($exam->fee_amount, 2) }}</p>
                <p class="text-sm text-gray-500">{{ $exam->fee_description ?? 'Exam Fee' }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('examination.payments.process', $exam) }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- M-Pesa -->
            <label class="cursor-pointer">
                <input type="radio" name="method" value="mpesa" class="hidden peer" required>
                <div class="border-2 border-gray-200 peer-checked:border-green-500 rounded-lg p-6 text-center hover:border-green-300 transition">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">M-Pesa</h3>
                    <p class="text-sm text-gray-500">Pay via Safaricom M-Pesa STK Push</p>
                </div>
            </label>

            <!-- Bank Transfer -->
            <label class="cursor-pointer">
                <input type="radio" name="method" value="bank_transfer" class="hidden peer">
                <div class="border-2 border-gray-200 peer-checked:border-blue-500 rounded-lg p-6 text-center hover:border-blue-300 transition">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Bank Transfer</h3>
                    <p class="text-sm text-gray-500">Transfer to our bank account</p>
                </div>
            </label>

            <!-- Card -->
            <label class="cursor-pointer">
                <input type="radio" name="method" value="card" class="hidden peer">
                <div class="border-2 border-gray-200 peer-checked:border-purple-500 rounded-lg p-6 text-center hover:border-purple-300 transition">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Credit/Debit Card</h3>
                    <p class="text-sm text-gray-500">Pay with Visa or Mastercard</p>
                </div>
            </label>

            <!-- Cash -->
            <label class="cursor-pointer">
                <input type="radio" name="method" value="cash" class="hidden peer">
                <div class="border-2 border-gray-200 peer-checked:border-yellow-500 rounded-lg p-6 text-center hover:border-yellow-300 transition">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-800">Cash</h3>
                    <p class="text-sm text-gray-500">Pay at the finance office</p>
                </div>
            </label>
        </div>

        @error('method')
            <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
        @enderror

        <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-medium hover:bg-green-700 transition">
            Continue to Payment
        </button>
    </form>

    <div class="mt-4 text-center">
        <a href="{{ route('examination.exams.show', $exam) }}" class="text-blue-600 hover:underline text-sm">&larr; Back to Exam</a>
    </div>
</div>
@endsection
