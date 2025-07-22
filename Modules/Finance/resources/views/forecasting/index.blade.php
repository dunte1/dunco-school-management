@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Budgets & Forecasting</h1>
    <a href="{{ route('finance.forecasting.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4 inline-block">Add Budget</a>
    <a href="{{ route('finance.forecasting.variance') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block ml-2">Variance Analysis</a>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Category</th>
                <th class="px-4 py-2">Period</th>
                <th class="px-4 py-2">Amount</th>
                <th class="px-4 py-2">Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($budgets as $budget)
            <tr>
                <td class="border px-4 py-2">{{ $budget->category }}</td>
                <td class="border px-4 py-2">{{ $budget->period }}</td>
                <td class="border px-4 py-2">{{ number_format($budget->amount, 2) }}</td>
                <td class="border px-4 py-2">{{ ucfirst($budget->type) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 