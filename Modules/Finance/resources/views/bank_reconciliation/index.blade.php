@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Bank Reconciliation</h1>
    <form action="{{ route('finance.bank-reconciliation.import') }}" method="POST" enctype="multipart/form-data" class="mb-6 bg-white p-4 rounded shadow">
        @csrf
        <label class="block mb-2">Import Bank Statement (CSV/Excel):</label>
        <input type="file" name="statement" class="mb-2">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Import</button>
        <span class="ml-2 text-gray-500">(Import feature coming soon)</span>
    </form>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Amount</th>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Reference</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Matched Payment</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td class="border px-4 py-2">{{ $transaction->date }}</td>
                <td class="border px-4 py-2">{{ number_format($transaction->amount, 2) }}</td>
                <td class="border px-4 py-2">{{ $transaction->description }}</td>
                <td class="border px-4 py-2">{{ $transaction->reference }}</td>
                <td class="border px-4 py-2">{{ ucfirst($transaction->status) }}</td>
                <td class="border px-4 py-2">
                    @if($transaction->matchedPayment)
                        #{{ $transaction->matchedPayment->id }}
                    @else
                        -
                    @endif
                </td>
                <td class="border px-4 py-2">
                    @if(!$transaction->matchedPayment)
                    <form action="{{ route('finance.bank-reconciliation.match', $transaction) }}" method="POST" class="inline">
                        @csrf
                        <select name="payment_id" class="border rounded px-2 py-1">
                            @foreach(\Modules\Finance\app\Models\Payment::all() as $payment)
                                <option value="{{ $payment->id }}">#{{ $payment->id }} ({{ number_format($payment->amount, 2) }})</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700 ml-2">Match</button>
                    </form>
                    @endif
                    <form action="{{ route('finance.bank-reconciliation.status', $transaction) }}" method="POST" class="inline ml-2">
                        @csrf
                        <select name="status" class="border rounded px-2 py-1">
                            <option value="unreconciled" @if($transaction->status=='unreconciled') selected @endif>Unreconciled</option>
                            <option value="reconciled" @if($transaction->status=='reconciled') selected @endif>Reconciled</option>
                            <option value="flagged" @if($transaction->status=='flagged') selected @endif>Flagged</option>
                        </select>
                        <button type="submit" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 ml-2">Update</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 