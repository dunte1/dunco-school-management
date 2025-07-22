@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Invoices</h1>
    <a href="{{ route('finance.billing.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4 inline-block">Create Invoice</a>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Student</th>
                <th class="px-4 py-2">Total Amount</th>
                <th class="px-4 py-2">Due Date</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td class="border px-4 py-2">{{ $invoice->student->name ?? '-' }}</td>
                <td class="border px-4 py-2">{{ number_format($invoice->total_amount, 2) }}</td>
                <td class="border px-4 py-2">{{ $invoice->due_date }}</td>
                <td class="border px-4 py-2">{{ ucfirst($invoice->status) }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('finance.billing.show', $invoice) }}" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 