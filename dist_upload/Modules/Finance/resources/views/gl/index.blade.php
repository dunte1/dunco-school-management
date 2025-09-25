@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">General Ledger</h1>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Account</th>
                <th class="px-4 py-2">Type</th>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Debit</th>
                <th class="px-4 py-2">Credit</th>
                <th class="px-4 py-2">Actions</th>
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
                <td class="border px-4 py-2">
                    <a href="{{ route('finance.gl.show', $entry) }}" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 