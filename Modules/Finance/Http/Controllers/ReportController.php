<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Models\Payment;

class ReportController extends Controller
{
    public function index()
    {
        return view('finance::reports.index', $this->dashboardData());
    }

    public function feeCollection(Request $request)
    {
        $query = $this->paymentQuery($request);
        $payments = $query ? (clone $query)->orderByDesc('payment_date')->paginate(50) : collect();
        $total = $query ? (float) (clone $query)->sum('amount') : 0.0;

        return view('finance::reports.fee_collection', compact('payments', 'total'));
    }

    public function outstandingBalances(Request $request)
    {
        $balances = collect();
        $total = 0.0;

        try {
            if (class_exists(\Modules\Academic\Models\StudentFee::class) && Schema::hasTable('student_fees')) {
                $balances = \Modules\Academic\Models\StudentFee::with('student')
                    ->whereIn('status', ['unpaid', 'partial'])
                    ->get();

                $total = (float) $balances->sum(function ($fee) {
                    $paid = (float) $fee->payments()->sum('amount');

                    return max(0, (float) $fee->amount - $paid);
                });
            }
        } catch (\Throwable $e) {
            // Leave empty on schema mismatch.
        }

        return view('finance::reports.outstanding_balances', compact('balances', 'total'));
    }

    public function incomeExpense(Request $request)
    {
        $income = 0.0;
        $expenses = 0.0;

        try {
            if (Schema::hasTable('payments')) {
                $query = Payment::query();
                $this->applyDateRange($query, $request);
                $income = (float) $query->sum('amount');
            }
        } catch (\Throwable $e) {
            // Leave as zero.
        }

        return view('finance::reports.income_expense', compact('income', 'expenses'));
    }

    public function downloadDashboardPdf()
    {
        $data = $this->dashboardData() + ['generatedAt' => now()];

        try {
            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('finance::reports.dashboard_pdf', $data);

                return $pdf->download('finance-dashboard-'.$data['generatedAt']->format('Y-m-d').'.pdf');
            }
        } catch (\Throwable $e) {
            // Fall back to HTML view below.
        }

        return view('finance::reports.dashboard_pdf', $data);
    }

    // --- Additional (non-routed) report views kept for completeness ---------

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

    // --- Helpers ------------------------------------------------------------

    /**
     * @return array{totalFeesCollected: float, outstandingBalances: float, income: float, expenses: float}
     */
    protected function dashboardData(): array
    {
        $totalCollected = 0.0;
        $outstanding = 0.0;
        $income = 0.0;
        $feeTrend = collect();
        $outstandingByClass = collect();

        try {
            if (Schema::hasTable('payments')) {
                $totalCollected = (float) Payment::query()->sum('amount');
                $income = (float) Payment::query()->where('created_at', '>=', now()->subDays(30))->sum('amount');

                for ($i = 5; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $feeTrend->put(
                        $month->format('M Y'),
                        (float) Payment::query()
                            ->whereYear('payment_date', $month->year)
                            ->whereMonth('payment_date', $month->month)
                            ->sum('amount')
                    );
                }
            }
        } catch (\Throwable $e) {
        }

        $fees = collect();
        try {
            if (class_exists(\Modules\Academic\Models\StudentFee::class) && Schema::hasTable('student_fees')) {
                $fees = \Modules\Academic\Models\StudentFee::query()
                    ->whereIn('status', ['unpaid', 'partial'])
                    ->get();

                $outstanding = (float) $fees->sum(function ($fee) {
                    return max(0, (float) $fee->amount - (float) $fee->payments()->sum('amount'));
                });

                $outstandingByClass = $fees
                    ->groupBy(function ($fee) {
                        return optional($fee->student)->class_id ?? 'Unassigned';
                    })
                    ->map(function ($group) {
                        return (float) $group->sum(function ($fee) {
                            return max(0, (float) $fee->amount - (float) $fee->payments()->sum('amount'));
                        });
                    });
            }
        } catch (\Throwable $e) {
        }

        return [
            'totalFeesCollected' => $totalCollected,
            'outstandingBalances' => $outstanding,
            'income' => $income,
            'expenses' => 0.0,
            'feeTrend' => $feeTrend,
            'outstandingByClass' => $outstandingByClass,
            'month' => now()->format('F Y'),
            'studentId' => null,
            'total' => $totalCollected,
        ];
    }

    protected function paymentQuery(Request $request)
    {
        try {
            if (! Schema::hasTable('payments')) {
                return null;
            }
        } catch (\Throwable $e) {
            return null;
        }

        $query = Payment::query();
        $this->applyDateRange($query, $request);

        return $query;
    }

    protected function applyDateRange($query, Request $request): void
    {
        if ($from = $request->input('from')) {
            $query->whereDate('payment_date', '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->whereDate('payment_date', '<=', $to);
        }
    }
}
