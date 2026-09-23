@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">My Exam Payments</h1>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payments as $payment)
                <tr>
                    <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $payment->reference }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->exam->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $payment->method) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' :
                               ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('examination.payments.show', $payment) }}" class="text-blue-600 hover:underline">View</a>
                        @if($payment->status === 'completed')
                            | <a href="{{ route('examination.payments.receipt', $payment) }}" class="text-green-600 hover:underline">Receipt</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">No payments found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $payments->links() }}</div>
</div>
@endsection
