@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Account Ledgers</h1>
    <form method="GET" action="{{ route('finance.ledger.index') }}" class="mb-4">
        <div class="flex gap-4">
            <div>
                <label class="block mb-1">Account</label>
                <input type="text" name="account" value="{{ request('account') }}" class="border rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1">Type</label>
                <input type="text" name="type" value="{{ request('type') }}" class="border rounded px-3 py-2">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            </div>
        </div>
    </form>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Account</th>
                <th class="px-4 py-2">Type</th>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Debit</th>
                <th class="px-4 py-2">Credit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entries as $entry)
            <tr>
                <td class="border px-4 py-2">{{ $entry->date }}</td>
                <td class="border px-4 py-2">{{ $entry->account }}</td>
                <td class="border px-4 py-2">{{ ucfirst($entry->type) }}</td>
                <td class="border px-4 py-2">{{ $entry->description }}</td>
                <td class="border px-4 py-2">{{ number_format($entry->debit, 2) }}</td>
                <td class="border px-4 py-2">{{ number_format($entry->credit, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 