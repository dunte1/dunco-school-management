<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Models\Budget;
use Modules\Finance\Models\LedgerEntry;
use Modules\Finance\Models\Payment;

class ForecastingController extends Controller
{
    public function index(Request $request)
    {
        $query = Budget::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('period')) {
            $query->where('period', $request->period);
        }

        $budgets = $query->orderByDesc('period')->orderBy('category')->paginate(30);

        $periods = Budget::distinct()->pluck('period')->sort()->reverse();
        $totalBudgeted = (float) Budget::sum('amount');

        $actualIncome = 0.0;
        $actualExpenses = 0.0;
        try {
            if (Schema::hasTable('payments')) {
                $actualIncome = (float) Payment::sum('amount');
            }
            if (Schema::hasTable('ledger_entries')) {
                $actualExpenses = (float) LedgerEntry::where('type', 'expense')->sum('debit');
            }
        } catch (\Throwable $e) {
        }

        return view('finance::forecasting.index', compact('budgets', 'periods', 'totalBudgeted', 'actualIncome', 'actualExpenses'));
    }

    public function create()
    {
        $periods = Budget::distinct()->pluck('period')->sort()->reverse();

        return view('finance::forecasting.create', compact('periods'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => 'required|string|max:255',
            'period' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string|max:500',
        ]);

        Budget::create([
            'category' => $data['category'],
            'period' => $data['period'],
            'amount' => $data['amount'],
            'type' => $data['type'],
        ]);

        return redirect()->route('finance.forecasting.index')
            ->with('success', 'Budget entry created successfully.');
    }

    public function show($id)
    {
        $budget = Budget::findOrFail($id);
        $actual = $this->getActualForBudget($budget);

        $variance = $budget->amount - $actual;
        $variancePercent = $budget->amount > 0 ? round(($variance / $budget->amount) * 100, 2) : 0;

        return view('finance::forecasting.show', compact('budget', 'actual', 'variance', 'variancePercent'));
    }

    public function edit($id)
    {
        $budget = Budget::findOrFail($id);
        $periods = Budget::distinct()->pluck('period')->sort()->reverse();

        return view('finance::forecasting.edit', compact('budget', 'periods'));
    }

    public function update(Request $request, $id)
    {
        $budget = Budget::findOrFail($id);

        $data = $request->validate([
            'category' => 'required|string|max:255',
            'period' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string|max:500',
        ]);

        $budget->update([
            'category' => $data['category'],
            'period' => $data['period'],
            'amount' => $data['amount'],
            'type' => $data['type'],
        ]);

        return redirect()->route('finance.forecasting.index')
            ->with('success', 'Budget entry updated successfully.');
    }

    public function destroy($id)
    {
        Budget::findOrFail($id)->delete();

        return redirect()->route('finance.forecasting.index')
            ->with('success', 'Budget entry deleted successfully.');
    }

    public function variance(Request $request)
    {
        $period = $request->input('period', now()->format('Y-m'));

        $budgets = Budget::where('period', $period)->get();

        $variances = $budgets->map(function ($budget) use ($period) {
            $actual = $this->getActualForBudget($budget);
            $variance = $budget->amount - $actual;
            $variancePercent = $budget->amount > 0 ? round(($variance / $budget->amount) * 100, 2) : 0;
            $status = 'on_track';
            if ($variance < 0) {
                $status = 'over_budget';
            } elseif ($variancePercent > 10) {
                $status = 'under_budget';
            }

            return [
                'id' => $budget->id,
                'category' => $budget->category,
                'type' => $budget->type,
                'budgeted' => $budget->amount,
                'actual' => $actual,
                'variance' => $variance,
                'variance_percent' => $variancePercent,
                'status' => $status,
            ];
        });

        $totalBudgeted = (float) $variances->sum('budgeted');
        $totalActual = (float) $variances->sum('actual');
        $totalVariance = $totalBudgeted - $totalActual;

        $periods = Budget::distinct()->pluck('period')->sort()->reverse();

        return view('finance::forecasting.variance', compact('variances', 'period', 'periods', 'totalBudgeted', 'totalActual', 'totalVariance'));
    }

    protected function getActualForBudget(Budget $budget): float
    {
        $period = $budget->period;
        [$year, $month] = explode('-', $period);

        if ($budget->type === 'income') {
            return (float) Payment::whereYear('payment_date', $year)
                ->whereMonth('payment_date', $month)
                ->sum('amount');
        }

        return (float) LedgerEntry::where('type', 'expense')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('debit');
    }
}
