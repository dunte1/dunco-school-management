@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Finance Dashboard</h1>
        <div class="text-muted">Welcome back, {{ Auth::user()->name }}!</div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Invoices</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_invoices'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice-dollar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Monthly Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($stats['monthly_revenue'] ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Fee Structures</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_fees'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-check-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/finance') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-tachometer-alt me-2"></i>Finance Overview
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/finance/invoices') }}" class="btn btn-warning btn-block">
                                <i class="fas fa-file-invoice me-2"></i>Invoices
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/finance/payments') }}" class="btn btn-success btn-block">
                                <i class="fas fa-money-bill-wave me-2"></i>Payments
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ url('/finance/fees') }}" class="btn btn-info btn-block">
                                <i class="fas fa-tags me-2"></i>Fee Structures
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Summary</h6>
                </div>
                <div class="card-body">
                    @php
                        $totalRevenue = $stats['total_revenue'] ?? 0;
                        $monthlyRevenue = $stats['monthly_revenue'] ?? 0;
                        $revenueRate = $totalRevenue > 0 ? round(($monthlyRevenue / $totalRevenue) * 100, 1) : 0;
                    @endphp
                    <div class="progress mb-3" style="height: 24px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min($revenueRate, 100) }}%;" aria-valuenow="{{ $revenueRate }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $revenueRate }}% This Month
                        </div>
                    </div>
                    <p class="mb-1"><strong>Total Revenue:</strong> ${{ number_format($totalRevenue, 2) }}</p>
                    <p class="mb-1"><strong>Monthly Revenue:</strong> ${{ number_format($monthlyRevenue, 2) }}</p>
                    <p class="mb-1"><strong>Pending Invoices:</strong> {{ $stats['pending_invoices'] ?? 0 }}</p>
                    <p class="mb-0"><strong>Fee Structures:</strong> {{ $stats['total_fees'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Payments</h6>
                </div>
                <div class="card-body">
                    @php
                        $recentPayments = \Modules\Finance\Models\Payment::with('invoice.student')->latest()->take(5)->get();
                    @endphp
                    @if($recentPayments && $recentPayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Invoice #</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Reference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->invoice->student->name ?? 'N/A' }}</td>
                                        <td>{{ $payment->invoice->invoice_number ?? 'N/A' }}</td>
                                        <td>${{ number_format($payment->amount ?? 0, 2) }}</td>
                                        <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No recent payments</h5>
                            <p class="text-muted">Payment records will appear here once transactions are made.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
