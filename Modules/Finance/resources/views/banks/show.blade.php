@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Bank Account Details</h1>
    <div class="bg-white p-6 rounded shadow mb-6">
        <p><strong>Name:</strong> {{ $bank->name }}</p>
        <p><strong>Account Number:</strong> {{ $bank->account_number }}</p>
        <p><strong>Bank Name:</strong> {{ $bank->bank_name }}</p>
        <p><strong>Balance:</strong> {{ number_format($bank->balance, 2) }}</p>
    </div>
    <h2 class="text-xl font-semibold mb-2">Transfers</h2>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Type</th>
                <th class="px-4 py-2">Amount</th>
                <th class="px-4 py-2">Reference</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bank->outgoingTransfers as $transfer)
            <tr>
                <td class="border px-4 py-2">{{ $transfer->transfer_date }}</td>
                <td class="border px-4 py-2">Outgoing</td>
                <td class="border px-4 py-2">-{{ number_format($transfer->amount, 2) }}</td>
                <td class="border px-4 py-2">{{ $transfer->reference }}</td>
            </tr>
            @endforeach
            @foreach($bank->incomingTransfers as $transfer)
            <tr>
                <td class="border px-4 py-2">{{ $transfer->transfer_date }}</td>
                <td class="border px-4 py-2">Incoming</td>
                <td class="border px-4 py-2">+{{ number_format($transfer->amount, 2) }}</td>
                <td class="border px-4 py-2">{{ $transfer->reference }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('finance.banks.index') }}" class="text-blue-600 hover:underline mt-4 inline-block">Back to Bank Accounts</a>
</div>
@endsection 