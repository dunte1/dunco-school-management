@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Receipts</h1>
    <div class="bg-white rounded shadow p-6">
        <form method="GET" class="mb-4 flex flex-wrap gap-2 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Receipt #, Invoice #, Method..." class="border rounded px-3 py-2 w-64">
            <input type="date" name="from" value="{{ request('from') }}" class="border rounded px-3 py-2">
            <input type="date" name="to" value="{{ request('to') }}" class="border rounded px-3 py-2">
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            @if(request('search') || request('from') || request('to'))
                <a href="{{ route('finance.receipts.index') }}" class="ml-2 text-gray-600 hover:underline">Reset</a>
            @endif
        </form>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th class="px-4 py-2">Receipt #</th>
                    <th class="px-4 py-2">Invoice #</th>
                    <th class="px-4 py-2">Amount</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Method</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receipts as $receipt)
                <tr>
                    <td class="border px-4 py-2">{{ $receipt->id }}</td>
                    <td class="border px-4 py-2">{{ $receipt->invoice ? $receipt->invoice->id : '-' }}</td>
                    <td class="border px-4 py-2">{{ number_format($receipt->amount, 2) }}</td>
                    <td class="border px-4 py-2">{{ $receipt->payment_date ? \Carbon\Carbon::parse($receipt->payment_date)->format('d M Y') : '-' }}</td>
                    <td class="border px-4 py-2">{{ $receipt->method }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($receipt->status) }}</td>
                    <td class="border px-4 py-2 flex gap-2">
                        <a href="{{ route('finance.receipts.show', $receipt) }}" class="text-blue-600 hover:underline" title="View"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('finance.receipts.print', $receipt) }}" class="text-green-600 hover:underline" title="Print" target="_blank"><i class="fas fa-print"></i></a>
                        <a href="{{ route('finance.receipts.download', $receipt) }}" class="text-purple-600 hover:underline" title="Download PDF"><i class="fas fa-file-pdf"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">No receipts found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 