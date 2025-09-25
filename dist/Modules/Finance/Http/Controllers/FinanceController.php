<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        // Use hardcoded data for now to avoid database issues
        $stats = [
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
        
        $recentPayments = collect();
        $recentFees = collect();
        $outstandingFees = collect();
        $quickActions = [
            'fee_categories' => 0,
            'fee_types' => 0,
            'pending_payments' => 0,
            'bank_accounts' => 0,
            'taxes' => 0,
            'settings' => 0,
        ];
        
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
                // Table doesn't exist or has issues
            }
            
            try {
                if (Schema::hasTable('student_fees')) {
                    $outstandingBalances = StudentFee::where('status', '!=', 'paid')->sum('amount') ?? 0;
                    $pendingPayments = StudentFee::where('status', '!=', 'paid')->count() ?? 0;
                }
            } catch (\Exception $e) {
                // Table doesn't exist or has issues
            }
            
            try {
                if (Schema::hasTable('academic_students')) {
                    $activeStudents = \Modules\Academic\Models\Student::where('is_active', true)->count() ?? 0;
                }
            } catch (\Exception $e) {
                // Table doesn't exist or has issues
            }
            
            try {
                if (Schema::hasTable('bank_accounts')) {
                    $bankAccounts = BankAccount::count() ?? 0;
                }
            } catch (\Exception $e) {
                // Table doesn't exist or has issues
            }
            
            try {
                if (Schema::hasTable('fees')) {
                    $totalFees = Fee::count() ?? 0;
                }
            } catch (\Exception $e) {
                // Table doesn't exist or has issues
            }
            
            try {
                if (Schema::hasTable('fee_categories')) {
                    $feeCategories = FeeCategory::count() ?? 0;
                }
            } catch (\Exception $e) {
                // Table doesn't exist or has issues
            }
            
            try {
                if (Schema::hasTable('fee_types')) {
                    $feeTypes = FeeType::count() ?? 0;
                }
            } catch (\Exception $e) {
                // Table doesn't exist or has issues
            }
            
            try {
                if (Schema::hasTable('taxes')) {
                    $totalTaxes = Tax::count() ?? 0;
                }
            } catch (\Exception $e) {
                // Table doesn't exist or has issues
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
            } catch (\Exception $e) {}
            
            try {
                if (Schema::hasTable('fee_types')) {
                    $feeTypes = FeeType::count() ?? 0;
                }
            } catch (\Exception $e) {}
            
            try {
                if (Schema::hasTable('student_fees')) {
                    $pendingPayments = StudentFee::where('status', '!=', 'paid')->count() ?? 0;
                }
            } catch (\Exception $e) {}
            
            try {
                if (Schema::hasTable('bank_accounts')) {
                    $bankAccounts = BankAccount::count() ?? 0;
                }
            } catch (\Exception $e) {}
            
            try {
                if (Schema::hasTable('taxes')) {
                    $taxes = Tax::count() ?? 0;
                }
            } catch (\Exception $e) {}
            
            try {
                if (Schema::hasTable('finance_settings')) {
                    $settings = FinanceSetting::count() ?? 0;
                }
            } catch (\Exception $e) {}
            
            return [
                'fee_categories' => $feeCategories,
                'fee_types' => $feeTypes,
                'pending_payments' => $pendingPayments,
                'bank_accounts' => $bankAccounts,
                'taxes' => $taxes,
                'settings' => $settings,
            ];
        } catch (\Exception $e) {
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
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('finance::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('finance::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
