<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\Payment;
use Modules\Finance\Models\Invoice;
use Modules\Finance\Models\LedgerEntry;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        $totalFeesCollected = Payment::where('status', 'successful')->sum('amount');
        $outstandingBalances = Invoice::where('status', 'unpaid')->sum('total_amount');
        $income = LedgerEntry::where('type', 'credit')->sum('credit');
        $expenses = LedgerEntry::where('type', 'debit')->sum('debit');

        // Fee Collection Trend (last 6 months, PHP grouping)
        $payments = Payment::where('status', 'successful')
            ->orderBy('payment_date', 'desc')
            ->get();
        $feeTrend = $payments->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->payment_date)->format('Y-m');
        })->map(function($group) {
            return $group->sum('amount');
        })->sortKeys()->take(-6);

        // Outstanding Balances Breakdown (by student, PHP grouping)
        $unpaidInvoices = Invoice::where('status', 'unpaid')->get();
        $outstandingByClass = $unpaidInvoices->groupBy('student_id')->map(function($group) {
            return $group->sum('total_amount');
        })->take(4);

        return view('finance::reports.index', compact(
            'totalFeesCollected', 'outstandingBalances', 'income', 'expenses', 'feeTrend', 'outstandingByClass'
        ));
    }

    public function downloadDashboardPdf()
    {
        $totalFeesCollected = Payment::where('status', 'successful')->sum('amount');
        $outstandingBalances = Invoice::where('status', 'unpaid')->sum('total_amount');
        $income = LedgerEntry::where('type', 'credit')->sum('credit');
        $expenses = LedgerEntry::where('type', 'debit')->sum('debit');
        $payments = Payment::where('status', 'successful')
            ->orderBy('payment_date', 'desc')
            ->get();
        $feeTrend = $payments->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->payment_date)->format('Y-m');
        })->map(function($group) {
            return $group->sum('amount');
        })->sortKeys()->take(-6);
        $unpaidInvoices = Invoice::where('status', 'unpaid')->get();
        $outstandingByClass = $unpaidInvoices->groupBy('student_id')->map(function($group) {
            return $group->sum('total_amount');
        })->take(4);
        $pdf = PDF::loadView('finance::reports.dashboard_pdf', compact(
            'totalFeesCollected', 'outstandingBalances', 'income', 'expenses', 'feeTrend', 'outstandingByClass'
        ));
        return $pdf->download('finance_dashboard.pdf');
    }

    public function feeCollection(Request $request)
    {
        // Placeholder: Replace with real data aggregation
        $data = [];
        return view('finance::reports.fee_collection', compact('data'));
    }

    public function outstandingBalances(Request $request)
    {
        // Placeholder: Replace with real data aggregation
        $data = [];
        return view('finance::reports.outstanding_balances', compact('data'));
    }

    public function incomeExpense(Request $request)
    {
        // Placeholder: Replace with real data aggregation
        $data = [];
        return view('finance::reports.income_expense', compact('data'));
    }
} 