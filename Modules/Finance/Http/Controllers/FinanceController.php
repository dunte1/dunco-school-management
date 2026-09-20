<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Finance\Models\Fee;
use Modules\Finance\Models\FeeCategory;
use Modules\Finance\Models\FeeType;
use Modules\Academic\Models\StudentFee;
use Modules\Academic\Models\StudentPayment;
use Modules\Finance\Models\BankAccount;
use Modules\Finance\Models\Tax;
use Modules\Finance\Entities\FinanceSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FinanceController extends Controller
{
    /**
     * Display the finance dashboard with all submodules.
     */
    public function index()
    {
        $stats = $this->getFinanceStats();
        $recentPayments = $this->getRecentPayments();
        $recentFees = $this->getRecentFees();
        $outstandingFees = $this->getOutstandingFees();
        $quickActions = $this->getQuickActions();
        
        return view('finance::index', compact(
            'stats',
            'recentPayments', 
            'recentFees',
            'outstandingFees',
            'quickActions'
        ));
    }

    /**
     * Get finance statistics
     */
    private function getFinanceStats()
    {
        try {
            $currentMonth = now()->startOfMonth();
            
            // Check if tables exist and have data before querying
            $totalFeesCollected = 0;
            $outstandingBalances = 0;
            $activeStudents = 0;
            $bankAccounts = 0;
            $totalFees = 0;
            $feeCategories = 0;
            $feeTypes = 0;
            $totalTaxes = 0;
            $pendingPayments = 0;
            
            // Only query if table exists and has data
            try {
                if (Schema::hasTable('student_payments')) {
                    $totalFeesCollected = StudentPayment::whereMonth('payment_date', $currentMonth->month)
                        ->whereYear('payment_date', $currentMonth->year)
                        ->sum('amount') ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning('FinanceController: Error querying table - ' . $e->getMessage());
            }
            
            try {
                if (Schema::hasTable('student_fees')) {
                    $outstandingBalances = StudentFee::where('status', '!=', 'paid')->sum('amount') ?? 0;
                    $pendingPayments = StudentFee::where('status', '!=', 'paid')->count() ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning('FinanceController: Error querying table - ' . $e->getMessage());
            }
            
            try {
                if (Schema::hasTable('academic_students')) {
                    $activeStudents = \Modules\Academic\Models\Student::where('is_active', true)->count() ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning('FinanceController: Error querying table - ' . $e->getMessage());
            }
            
            try {
                if (Schema::hasTable('bank_accounts')) {
                    $bankAccounts = BankAccount::count() ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning('FinanceController: Error querying table - ' . $e->getMessage());
            }
            
            try {
                if (Schema::hasTable('fees')) {
                    $totalFees = Fee::count() ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning('FinanceController: Error querying table - ' . $e->getMessage());
            }
            
            try {
                if (Schema::hasTable('fee_categories')) {
                    $feeCategories = FeeCategory::count() ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning('FinanceController: Error querying table - ' . $e->getMessage());
            }
            
            try {
                if (Schema::hasTable('fee_types')) {
                    $feeTypes = FeeType::count() ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning('FinanceController: Error querying table - ' . $e->getMessage());
            }
            
            try {
                if (Schema::hasTable('taxes')) {
                    $totalTaxes = Tax::count() ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning('FinanceController: Error querying table - ' . $e->getMessage());
            }
            
            return [
                'total_fees_collected' => $totalFeesCollected,
                'outstanding_balances' => $outstandingBalances,
                'active_students' => $activeStudents,
                'bank_accounts' => $bankAccounts,
                'total_fees' => $totalFees,
                'fee_categories' => $feeCategories,
                'fee_types' => $feeTypes,
                'total_taxes' => $totalTaxes,
                'monthly_revenue' => $totalFeesCollected, // Reuse the same calculation
                'pending_payments' => $pendingPayments,
            ];
        } catch (\Exception $e) {
            // Return default values if there's an error
            return [
                'total_fees_collected' => 0,
                'outstanding_balances' => 0,
                'active_students' => 0,
                'bank_accounts' => 0,
                'total_fees' => 0,
                'fee_categories' => 0,
                'fee_types' => 0,
                'total_taxes' => 0,
                'monthly_revenue' => 0,
                'pending_payments' => 0,
            ];
        }
    }

    /**
     * Get recent payments
     */
    private function getRecentPayments()
    {
        try {
            if (Schema::hasTable('student_payments')) {
                return StudentPayment::orderBy('payment_date', 'desc')
                ->limit(5)
                ->get();
            }
            return collect();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get recent fees
     */
    private function getRecentFees()
    {
        try {
            if (Schema::hasTable('fees')) {
                return Fee::orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            }
            return collect();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get outstanding fees
     */
    private function getOutstandingFees()
    {
        try {
            if (Schema::hasTable('student_fees')) {
                return StudentFee::where('status', '!=', 'paid')
                ->orderBy('due_date', 'asc')
                ->limit(10)
                ->get();
            }
            return collect();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get quick actions data
     */
    private function getQuickActions()
    {
        try {
            $feeCategories = 0;
            $feeTypes = 0;
            $pendingPayments = 0;
            $bankAccounts = 0;
            $taxes = 0;
            $settings = 0;

            try {
                if (Schema::hasTable('fee_categories')) {
                    $feeCategories = FeeCategory::count() ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning('FinanceController: Error counting fee_categories - ' . $e->getMessage());
            }

            try {
                if (Schema::hasTable('fee_types')) {
                    $feeTypes = FeeType::count() ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning('FinanceController: Error counting fee_types - ' . $e->getMessage());
            }

            try {
                if (Schema::hasTable('student_fees')) {
                    $pendingPayments = StudentFee::where('status', '!=', 'paid')->count() ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning('FinanceController: Error counting pending_payments - ' . $e->getMessage());
            }

            try {
                if (Schema::hasTable('bank_accounts')) {
                    $bankAccounts = BankAccount::count() ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning('FinanceController: Error counting bank_accounts - ' . $e->getMessage());
            }

            try {
                if (Schema::hasTable('taxes')) {
                    $taxes = Tax::count() ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning('FinanceController: Error counting taxes - ' . $e->getMessage());
            }

            try {
                if (Schema::hasTable('finance_settings')) {
                    $settings = FinanceSetting::count() ?? 0;
                }
            } catch (\Exception $e) {
                Log::warning('FinanceController: Error counting finance_settings - ' . $e->getMessage());
            }

            return [
                'fee_categories' => $feeCategories,
                'fee_types' => $feeTypes,
                'pending_payments' => $pendingPayments,
                'bank_accounts' => $bankAccounts,
                'taxes' => $taxes,
                'settings' => $settings,
            ];
        } catch (\Exception $e) {
            Log::error('FinanceController: Failed to get quick actions - ' . $e->getMessage());
            return [
                'fee_categories' => 0,
                'fee_types' => 0,
                'pending_payments' => 0,
                'bank_accounts' => 0,
                'taxes' => 0,
                'settings' => 0,
            ];
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('finance::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'fee_category_id' => 'nullable|exists:fee_categories,id',
                'fee_type_id' => 'nullable|exists:fee_types,id',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date',
            ]);

            $fee = Fee::create($validated);

            return redirect()->route('finance.index')
                ->with('success', 'Fee created successfully.');
        } catch (\Exception $e) {
            Log::error('FinanceController: Failed to store fee - ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create fee: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $fee = Fee::findOrFail($id);
            return view('finance::show', compact('fee'));
        } catch (\Exception $e) {
            Log::error('FinanceController: Failed to show fee - ' . $e->getMessage());
            return redirect()->route('finance.index')
                ->with('error', 'Fee not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $fee = Fee::findOrFail($id);
            $feeCategories = FeeCategory::orderBy('name')->get();
            $feeTypes = FeeType::orderBy('name')->get();
            return view('finance::edit', compact('fee', 'feeCategories', 'feeTypes'));
        } catch (\Exception $e) {
            Log::error('FinanceController: Failed to edit fee - ' . $e->getMessage());
            return redirect()->route('finance.index')
                ->with('error', 'Fee not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $fee = Fee::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'amount' => 'required|numeric|min:0',
                'fee_category_id' => 'nullable|exists:fee_categories,id',
                'fee_type_id' => 'nullable|exists:fee_types,id',
                'description' => 'nullable|string',
                'due_date' => 'nullable|date',
            ]);

            $fee->update($validated);

            return redirect()->route('finance.index')
                ->with('success', 'Fee updated successfully.');
        } catch (\Exception $e) {
            Log::error('FinanceController: Failed to update fee - ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update fee: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $fee = Fee::findOrFail($id);
            $fee->delete();

            return redirect()->route('finance.index')
                ->with('success', 'Fee deleted successfully.');
        } catch (\Exception $e) {
            Log::error('FinanceController: Failed to destroy fee - ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete fee: ' . $e->getMessage());
        }
    }
}
