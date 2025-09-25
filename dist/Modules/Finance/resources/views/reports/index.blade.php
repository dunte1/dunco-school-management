@section('breadcrumb')
<div class="py-4 flex items-center text-sm text-gray-500">
    <a href="/" class="hover:underline text-blue-700 font-semibold">Dashboard</a>
    <span class="mx-2">&gt;</span>
    <span class="font-semibold text-blue-700">Finance</span>
    <span class="mx-2">&gt;</span>
    <span class="font-semibold">Reports</span>
</div>
@endsection

@extends('finance::layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-8 mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10 gap-4">
        <h2 class="text-2xl md:text-3xl font-bold text-blue-700">Finance Reports Dashboard</h2>
        <a href="{{ route('finance.reports.download-dashboard-pdf') }}" class="inline-flex items-center px-5 py-2 bg-gradient-to-r from-blue-600 to-blue-400 text-white rounded-lg shadow hover:scale-105 hover:bg-blue-700 transition transform duration-150"><i class="fas fa-file-pdf mr-2"></i>Download PDF</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
        <div class="bg-blue-50 rounded-lg p-5 text-center shadow-sm">
            <div class="mb-2 group"><i class="fas fa-coins fa-3x text-yellow-500 group-hover:scale-110 transition-transform duration-150"></i></div>
            <div class="text-2xl font-bold">KES {{ number_format($totalFeesCollected, 2) }}</div>
            <div class="text-sm text-gray-500">Total Fees Collected</div>
        </div>
        <div class="bg-blue-50 rounded-lg p-5 text-center shadow-sm">
            <div class="mb-2 group"><i class="fas fa-file-invoice-dollar fa-3x text-orange-500 group-hover:scale-110 transition-transform duration-150"></i></div>
            <div class="text-2xl font-bold">KES {{ number_format($outstandingBalances, 2) }}</div>
            <div class="text-sm text-gray-500">Outstanding Balances</div>
        </div>
        <div class="bg-blue-50 rounded-lg p-5 text-center shadow-sm">
            <div class="mb-2 group"><i class="fas fa-chart-line fa-3x text-green-500 group-hover:scale-110 transition-transform duration-150"></i></div>
            <div class="text-2xl font-bold">KES {{ number_format($income, 2) }}</div>
            <div class="text-sm text-gray-500">Income</div>
        </div>
        <div class="bg-blue-50 rounded-lg p-5 text-center shadow-sm">
            <div class="mb-2 group"><i class="fas fa-money-bill-wave fa-3x text-red-500 group-hover:scale-110 transition-transform duration-150"></i></div>
            <div class="text-2xl font-bold">KES {{ number_format($expenses, 2) }}</div>
            <div class="text-sm text-gray-500">Expenses</div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-10">
        <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2 flex items-center"><i class="fas fa-chart-area mr-2 text-blue-400"></i>Fee Collection Trend</h3>
            <div class="bg-gray-50 rounded-lg p-4 shadow-sm min-h-[180px] flex items-center justify-center">
                <canvas id="feeCollectionChart" height="120" class="w-full"></canvas>
                @if($feeTrend->isEmpty())
                    <span class="absolute text-gray-400 text-sm">No data available</span>
                @endif
            </div>
        </div>
        <div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2 flex items-center"><i class="fas fa-pie-chart mr-2 text-yellow-400"></i>Outstanding Balances Breakdown</h3>
            <div class="bg-gray-50 rounded-lg p-4 shadow-sm min-h-[180px] flex items-center justify-center">
                <canvas id="outstandingBalancesChart" height="120" class="w-full"></canvas>
                @if($outstandingByClass->isEmpty())
                    <span class="absolute text-gray-400 text-sm">No data available</span>
                @endif
            </div>
        </div>
    </div>
    <hr class="my-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
        <div class="bg-blue-50 rounded-lg p-6 flex flex-col justify-between shadow-sm group transition-transform duration-150 hover:scale-105">
            <div>
                <div class="mb-2"><i class="fas fa-coins fa-2x text-yellow-500 group-hover:scale-125 transition-transform duration-150"></i></div>
                <h4 class="font-bold mb-1">Fee Collection Report</h4>
                <p class="text-gray-500 text-sm mb-2">Detailed report of all fee collections, filters, and export options.</p>
            </div>
            <a href="{{ route('finance.reports.fee-collection') }}" class="btn bg-blue-600 text-white rounded px-4 py-2 mt-2 text-center shadow hover:bg-blue-700 hover:scale-105 transition">View Report</a>
        </div>
        <div class="bg-blue-50 rounded-lg p-6 flex flex-col justify-between shadow-sm group transition-transform duration-150 hover:scale-105">
            <div>
                <div class="mb-2"><i class="fas fa-file-invoice-dollar fa-2x text-orange-500 group-hover:scale-125 transition-transform duration-150"></i></div>
                <h4 class="font-bold mb-1">Outstanding Balances Report</h4>
                <p class="text-gray-500 text-sm mb-2">See all outstanding balances by student, class, or date range.</p>
            </div>
            <a href="{{ route('finance.reports.outstanding-balances') }}" class="btn bg-orange-500 text-white rounded px-4 py-2 mt-2 text-center shadow hover:bg-orange-600 hover:scale-105 transition">View Report</a>
        </div>
        <div class="bg-blue-50 rounded-lg p-6 flex flex-col justify-between shadow-sm group transition-transform duration-150 hover:scale-105">
            <div>
                <div class="mb-2"><i class="fas fa-chart-line fa-2x text-green-500 group-hover:scale-125 transition-transform duration-150"></i></div>
                <h4 class="font-bold mb-1">Income & Expense Report</h4>
                <p class="text-gray-500 text-sm mb-2">Track income and expenses, view trends, and download statements.</p>
            </div>
            <a href="{{ route('finance.reports.income-expense') }}" class="btn bg-green-500 text-white rounded px-4 py-2 mt-2 text-center shadow hover:bg-green-600 hover:scale-105 transition">View Report</a>
        </div>
    </div>
    <div class="mt-10">
        <div class="border-2 border-blue-200 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-6 shadow-sm flex items-center">
            <div class="flex-1">
                <h4 class="font-bold mb-1 text-blue-700">Recent Financial Activity <span class="ml-2 badge bg-info text-blue-800">Premium</span></h4>
                <p class="text-gray-500 text-sm">Upgrade to premium to view real-time financial activity, audit logs, and advanced analytics here.</p>
            </div>
            <div>
                <i class="fas fa-star fa-2x text-yellow-400 animate-pulse"></i>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const feeCollectionCtx = document.getElementById('feeCollectionChart').getContext('2d');
const feeTrendLabels = {!! json_encode(array_keys($feeTrend ? $feeTrend->toArray() : [])) !!};
const feeTrendData = {!! json_encode(array_values($feeTrend ? $feeTrend->toArray() : [])) !!};
new Chart(feeCollectionCtx, {
    type: 'line',
    data: {
        labels: feeTrendLabels,
        datasets: [{
            label: 'Fees Collected',
            data: feeTrendData,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: feeTrendData.length > 0 },
        },
        scales: {
            x: { display: feeTrendLabels.length > 0 },
            y: { display: feeTrendData.length > 0 }
        }
    }
});
const outstandingBalancesCtx = document.getElementById('outstandingBalancesChart').getContext('2d');
const outstandingLabels = {!! json_encode(array_keys($outstandingByClass ? $outstandingByClass->toArray() : [])) !!};
const outstandingData = {!! json_encode(array_values($outstandingByClass ? $outstandingByClass->toArray() : [])) !!};
new Chart(outstandingBalancesCtx, {
    type: 'doughnut',
    data: {
        labels: outstandingLabels,
        datasets: [{
            label: 'Outstanding',
            data: outstandingData,
            backgroundColor: [
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: outstandingData.length > 0 },
        }
    }
});
</script>
@endpush 