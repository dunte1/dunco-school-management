<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Models\Payment;
use Modules\Finance\Models\Invoice;
use Modules\Finance\Models\Fee;
use Modules\Finance\Models\FeeCategory;
use Modules\Finance\Models\FeeType;
use Modules\Finance\Models\LedgerEntry;

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
        }

        return view('finance::reports.outstanding_balances', compact('balances', 'total'));
    }

    public function incomeExpense(Request $request)
    {
        $income = 0.0;
        $expenses = 0.0;
        $incomeTransactions = collect();
        $expenseTransactions = collect();

        try {
            if (Schema::hasTable('payments')) {
                $incomeQuery = Payment::query();
                $this->applyDateRange($incomeQuery, $request, 'payment_date');
                $income = (float) $incomeQuery->sum('amount');
                $incomeTransactions = (clone $incomeQuery)->orderByDesc('payment_date')->get();
            }
        } catch (\Throwable $e) {
        }

        try {
            if (Schema::hasTable('ledger_entries')) {
                $expenseQuery = LedgerEntry::where('type', 'expense');
                if ($from = $request->input('from')) {
                    $expenseQuery->whereDate('date', '>=', $from);
                }
                if ($to = $request->input('to')) {
                    $expenseQuery->whereDate('date', '<=', $to);
                }
                $expenses = (float) $expenseQuery->sum('debit');
                $expenseTransactions = $expenseQuery->orderByDesc('date')->get();
            }
        } catch (\Throwable $e) {
        }

        $netIncome = $income - $expenses;

        return view('finance::reports.income_expense', compact('income', 'expenses', 'netIncome', 'incomeTransactions', 'expenseTransactions'));
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
        }

        return view('finance::reports.dashboard_pdf', $data);
    }

    public function feeReport(Request $request)
    {
        $query = Fee::with(['category', 'type']);

        if ($request->filled('fee_category_id')) {
            $query->where('fee_category_id', $request->fee_category_id);
        }
        if ($request->filled('fee_type_id')) {
            $query->where('fee_type_id', $request->fee_type_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $fees = $query->orderBy('name')->get();
        $categories = FeeCategory::all();
        $types = FeeType::all();
        $totalAmount = (float) $fees->sum('amount');

        $byCategory = $fees->groupBy(fn ($f) => $f->category->name ?? 'Uncategorized')
            ->map(fn ($group) => [
                'count' => $group->count(),
                'total' => (float) $group->sum('amount'),
            ]);

        $byType = $fees->groupBy(fn ($f) => $f->type->name ?? 'Uncategorized')
            ->map(fn ($group) => [
                'count' => $group->count(),
                'total' => (float) $group->sum('amount'),
            ]);

        return view('finance::reports.fees', compact('fees', 'categories', 'types', 'totalAmount', 'byCategory', 'byType'));
    }

    public function paymentReport(Request $request)
    {
        $query = Payment::with('invoice');
        $this->applyDateRange($query, $request, 'payment_date');

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('reference', 'like', '%'.$request->search.'%')
                    ->orWhere('mpesa_transaction_code', 'like', '%'.$request->search.'%');
            });
        }

        $payments = $query->orderByDesc('payment_date')->paginate(50);

        $totalAmount = (float) Payment::query()
            ->when($request->filled('from'), fn ($q) => $q->whereDate('payment_date', '>=', $request->from))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('payment_date', '<=', $request->to))
            ->sum('amount');

        $byMethod = Payment::query()
            ->when($request->filled('from'), fn ($q) => $q->whereDate('payment_date', '>=', $request->from))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('payment_date', '<=', $request->to))
            ->select('method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('method')
            ->get();

        $byStatus = Payment::query()
            ->when($request->filled('from'), fn ($q) => $q->whereDate('payment_date', '>=', $request->from))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('payment_date', '<=', $request->to))
            ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('status')
            ->get();

        return view('finance::reports.payments', compact('payments', 'totalAmount', 'byMethod', 'byStatus'));
    }

    public function incomeReport(Request $request)
    {
        $query = Payment::query();
        $this->applyDateRange($query, $request, 'payment_date');

        $groupBy = $request->input('group_by', 'month');
        $dateFormat = match ($groupBy) {
            'day' => '%Y-%m-%d',
            'week' => '%Y-W%u',
            'month' => '%Y-%m',
            'year' => '%Y',
            default => '%Y-%m',
        };

        $incomeByPeriod = (clone $query)
            ->select(
                DB::raw("DATE_FORMAT(payment_date, '{$dateFormat}') as period"),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $totalIncome = (float) (clone $query)->sum('amount');

        $byInvoice = Invoice::with('payments')
            ->whereHas('payments', function ($q) use ($request) {
                $this->applyDateRange($q, $request, 'payment_date');
            })
            ->withSum('payments', 'amount')
            ->orderByDesc('created_at')
            ->paginate(30);

        $recentPayments = $query->orderByDesc('payment_date')->limit(20)->get();

        return view('finance::reports.income', compact('incomeByPeriod', 'totalIncome', 'byInvoice', 'recentPayments', 'groupBy'));
    }

    public function expenseReport(Request $request)
    {
        $query = LedgerEntry::where('type', 'expense');

        if ($from = $request->input('from')) {
            $query->whereDate('date', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->whereDate('date', '<=', $to);
        }
        if ($request->filled('account')) {
            $query->where('account', $request->account);
        }

        $expenses = $query->orderByDesc('date')->paginate(50);

        $totalExpenses = (float) LedgerEntry::where('type', 'expense')
            ->when($request->filled('from'), fn ($q) => $q->whereDate('date', '>=', $request->from))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('date', '<=', $request->to))
            ->sum('debit');

        $byAccount = LedgerEntry::where('type', 'expense')
            ->when($request->filled('from'), fn ($q) => $q->whereDate('date', '>=', $request->from))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('date', '<=', $request->to))
            ->select('account', DB::raw('SUM(debit) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('account')
            ->orderByDesc('total')
            ->get();

        $accounts = LedgerEntry::where('type', 'expense')->distinct()->pluck('account');

        return view('finance::reports.expenses', compact('expenses', 'totalExpenses', 'byAccount', 'accounts'));
    }

    public function balanceSheet()
    {
        $assets = LedgerEntry::where('type', 'asset')
            ->select('account', DB::raw('SUM(debit) - SUM(credit) as balance'))
            ->groupBy('account')
            ->get();

        $liabilities = LedgerEntry::where('type', 'liability')
            ->select('account', DB::raw('SUM(credit) - SUM(debit) as balance'))
            ->groupBy('account')
            ->get();

        $equity = LedgerEntry::where('type', 'equity')
            ->select('account', DB::raw('SUM(credit) - SUM(debit) as balance'))
            ->groupBy('account')
            ->get();

        $totalAssets = (float) $assets->sum('balance');
        $totalLiabilities = (float) $liabilities->sum('balance');
        $totalEquity = (float) $equity->sum('balance');

        return view('finance::reports.balance-sheet', compact(
            'assets', 'liabilities', 'equity',
            'totalAssets', 'totalLiabilities', 'totalEquity'
        ));
    }

    public function profitLoss(Request $request)
    {
        $from = $request->input('from', now()->startOfYear()->toDateString());
        $to = $request->input('to', now()->toDateString());

        $income = LedgerEntry::where('type', 'income')
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->select('account', DB::raw('SUM(credit) - SUM(debit) as total'))
            ->groupBy('account')
            ->get();

        $expenses = LedgerEntry::where('type', 'expense')
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->select('account', DB::raw('SUM(debit) - SUM(credit) as total'))
            ->groupBy('account')
            ->get();

        $totalIncome = (float) $income->sum('total');
        $totalExpenses = (float) $expenses->sum('total');
        $netProfit = $totalIncome - $totalExpenses;

        return view('finance::reports.profit-loss', compact('income', 'expenses', 'totalIncome', 'totalExpenses', 'netProfit', 'from', 'to'));
    }

    public function cashFlow(Request $request)
    {
        $from = $request->input('from', now()->startOfYear()->toDateString());
        $to = $request->input('to', now()->toDateString());

        $inflows = Payment::whereDate('payment_date', '>=', $from)
            ->whereDate('payment_date', '<=', $to)
            ->select('method', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('method')
            ->get();

        $outflows = LedgerEntry::where('type', 'expense')
            ->whereDate('date', '>=', $from)
            ->whereDate('date', '<=', $to)
            ->select('account', DB::raw('SUM(debit) as total'))
            ->groupBy('account')
            ->get();

        $totalInflows = (float) $inflows->sum('total');
        $totalOutflows = (float) $outflows->sum('total');
        $netCashFlow = $totalInflows - $totalOutflows;

        $monthlyFlow = collect();
        $start = \Carbon\Carbon::parse($from);
        $end = \Carbon\Carbon::parse($to);

        while ($start->lte($end)) {
            $month = $start->copy()->startOfMonth();
            $monthEnd = $start->copy()->endOfMonth();
            $monthIn = (float) Payment::whereDate('payment_date', '>=', $month)->whereDate('payment_date', '<=', $monthEnd)->sum('amount');
            $monthOut = (float) LedgerEntry::where('type', 'expense')->whereDate('date', '>=', $month)->whereDate('date', '<=', $monthEnd)->sum('debit');

            $monthlyFlow->push([
                'month' => $month->format('M Y'),
                'inflow' => $monthIn,
                'outflow' => $monthOut,
                'net' => $monthIn - $monthOut,
            ]);

            $start->addMonth();
        }

        return view('finance::reports.cash-flow', compact(
            'inflows', 'outflows', 'totalInflows', 'totalOutflows', 'netCashFlow', 'monthlyFlow', 'from', 'to'
        ));
    }

    protected function dashboardData(): array
    {
        $totalCollected = 0.0;
        $outstanding = 0.0;
        $income = 0.0;
        $expenses = 0.0;
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

        try {
            if (Schema::hasTable('ledger_entries')) {
                $expenses = (float) LedgerEntry::where('type', 'expense')
                    ->where('created_at', '>=', now()->subDays(30))
                    ->sum('debit');
            }
        } catch (\Throwable $e) {
        }

        try {
            if (class_exists(\Modules\Academic\Models\StudentFee::class) && Schema::hasTable('student_fees')) {
                $fees = \Modules\Academic\Models\StudentFee::query()
                    ->whereIn('status', ['unpaid', 'partial'])
                    ->get();

                $outstanding = (float) $fees->sum(function ($fee) {
                    return max(0, (float) $fee->amount - (float) $fee->payments()->sum('amount'));
                });

                $outstandingByClass = $fees
                    ->groupBy(fn ($fee) => optional($fee->student)->class_id ?? 'Unassigned')
                    ->map(fn ($group) => (float) $group->sum(fn ($fee) => max(0, (float) $fee->amount - (float) $fee->payments()->sum('amount'))));
            }
        } catch (\Throwable $e) {
        }

        return [
            'totalFeesCollected' => $totalCollected,
            'outstandingBalances' => $outstanding,
            'income' => $income,
            'expenses' => $expenses,
            'netIncome' => $income - $expenses,
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
        $this->applyDateRange($query, $request, 'payment_date');

        return $query;
    }

    protected function applyDateRange($query, Request $request, string $column = 'created_at'): void
    {
        if ($from = $request->input('from')) {
            $query->whereDate($column, '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->whereDate($column, '<=', $to);
        }
    }
}
