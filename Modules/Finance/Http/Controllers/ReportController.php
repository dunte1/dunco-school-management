<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportController extends Controller
{
    public function index()
    {
        return view('finance::reports.index');
    }

    public function feeReport()
    {
        return view('finance::reports.fees');
    }

    public function paymentReport()
    {
        return view('finance::reports.payments');
    }

    public function incomeReport()
    {
        return view('finance::reports.income');
    }

    public function expenseReport()
    {
        return view('finance::reports.expenses');
    }

    public function balanceSheet()
    {
        return view('finance::reports.balance-sheet');
    }

    public function profitLoss()
    {
        return view('finance::reports.profit-loss');
    }

    public function cashFlow()
    {
        return view('finance::reports.cash-flow');
    }

    public function export(Request $request)
    {
        // Export logic
        return response()->download('report.pdf');
    }
}
