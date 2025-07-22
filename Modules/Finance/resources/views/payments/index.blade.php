@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Payments</h1>
    <a href="{{ route('finance.payments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4 inline-block">Record Payment</a>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Invoice</th>
                <th class="px-4 py-2">Amount</th>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Method</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td class="border px-4 py-2">#{{ $payment->invoice_id }}</td>
                <td class="border px-4 py-2">{{ number_format($payment->amount, 2) }}</td>
                <td class="border px-4 py-2">{{ $payment->payment_date }}</td>
                <td class="border px-4 py-2">{{ $payment->method ?? '-' }}</td>
                <td class="border px-4 py-2">{{ ucfirst($payment->status) }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('finance.payments.show', $payment) }}" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 