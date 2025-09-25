@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Record Bank Transfer</h1>
    <form action="{{ route('finance.banks.storeTransfer') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">From Account</label>
            <select name="from_account_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Select Account --</option>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block mb-1">To Account</label>
            <select name="to_account_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Select Account --</option>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Amount</label>
            <input type="number" name="amount" step="0.01" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Transfer Date</label>
            <input type="date" name="transfer_date" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Reference</label>
            <input type="text" name="reference" class="w-full border rounded px-3 py-2">
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Record Transfer</button>
        <a href="{{ route('finance.banks.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection 