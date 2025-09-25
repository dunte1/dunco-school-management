@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Variance Analysis</h1>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Category</th>
                <th class="px-4 py-2">Period</th>
                <th class="px-4 py-2">Budgeted</th>
                <th class="px-4 py-2">Actual</th>
                <th class="px-4 py-2">Variance</th>
            </tr>
        </thead>
        <tbody>
            <!-- Placeholder rows -->
            <tr>
                <td class="border px-4 py-2">Tuition</td>
                <td class="border px-4 py-2">2024-07</td>
                <td class="border px-4 py-2">10000.00</td>
                <td class="border px-4 py-2">9500.00</td>
                <td class="border px-4 py-2">-500.00</td>
            </tr>
        </tbody>
    </table>
    <a href="{{ route('finance.forecasting.index') }}" class="text-blue-600 hover:underline mt-4 inline-block">Back to Budgets</a>
</div>
@endsection 