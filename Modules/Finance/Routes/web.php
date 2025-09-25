<?php

use Illuminate\Support\Facades\Route;
use Modules\Finance\Http\Controllers\FeeController;
use Modules\Finance\Http\Controllers\FeeCategoryController;
use Modules\Finance\Http\Controllers\FeeTypeController;
use Modules\Finance\Http\Controllers\PaymentController;
use Modules\Finance\Http\Controllers\BankReconciliationController;
use Modules\Finance\Http\Controllers\LedgerController;
use Modules\Finance\Http\Controllers\GLController;
use Modules\Finance\Http\Controllers\MultiBankController;
use Modules\Finance\Http\Controllers\ForecastingController;
use Modules\Finance\Http\Controllers\OnlinePaymentController;
use Modules\Finance\Http\Controllers\BillingController;
use Modules\Finance\Http\Controllers\ReportController;
use Modules\Finance\Http\Controllers\ReceiptController;
use Modules\Finance\Http\Controllers\TaxController;
use Modules\Finance\Http\Controllers\SettingsController;
use Modules\Finance\Http\Controllers\FinanceController;

// Test route to check if finance module is loading
Route::get('finance/test', function () {
    return 'Finance module is working!';
})->name('finance.test');

// Test route for main layout
Route::get('finance/layout-test', function () {
    return view('layouts.app', ['content' => '<div class="container-fluid"><h1>Layout Test</h1><p>This is a test of the main layout.</p></div>']);
})->name('finance.layout-test');

// Test route for fees without controller
Route::get('finance/fees-test', function () {
    return view('finance::fees.index', ['fees' => collect()]);
})->name('finance.fees.test');

// Test route for fee-types without controller
Route::get('finance/fee-categories-test', function () {
    return view('finance::fee_categories.index', ['categories' => collect()]);
})->name('finance.fee-categories.test');

// Test route for fee-types with controller and error handling
Route::get('finance/fee-types-debug', function () {
    try {
        $controller = new \Modules\Finance\Http\Controllers\FeeTypeController();
        return $controller->index();
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('finance.fee-types.debug');

// Test route for FeeType model instantiation
Route::get('finance/fee-type-model-test', function () {
    try {
        $model = new \Modules\Finance\Models\FeeType();
        return response()->json(['success' => 'FeeType model instantiated successfully']);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
})->name('finance.fee-type-model-test');

// Test route to check database tables
Route::get('finance/db-test', function () {
    try {
        $tables = ['fees', 'fee_categories', 'fee_types', 'payments', 'taxes', 'bank_accounts', 'bank_transactions', 'ledger_entries', 'invoices', 'finance_roles', 'finance_settings'];
        $results = [];
        
        foreach ($tables as $table) {
            try {
                $exists = \Illuminate\Support\Facades\Schema::hasTable($table);
                $results[$table] = $exists ? 'EXISTS' : 'MISSING';
            } catch (Exception $e) {
                $results[$table] = 'ERROR: ' . $e->getMessage();
            }
        }
        
        return response()->json($results);
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('finance.db.test');

// Payment Gateway Routes (must be outside the admin group for portal access)
Route::post('finance/payment/pay/{fee_id}', [PaymentController::class, 'pay'])->name('finance.payment.pay');
Route::get('finance/payment/success', [PaymentController::class, 'success'])->name('finance.payment.success');
Route::get('finance/payment/cancel', [PaymentController::class, 'cancel'])->name('finance.payment.cancel');
Route::post('finance/payment/mpesa-stk/{fee_id}', [PaymentController::class, 'mpesaStkPush'])->name('finance.payment.mpesa-stk');
Route::post('finance/payment/mpesa-callback', [PaymentController::class, 'mpesaCallback'])->name('finance.payment.mpesa-callback');
Route::post('finance/payment/bank-transfer/{fee_id}', [PaymentController::class, 'submitBankTransfer'])->name('finance.payment.bank-transfer');

Route::prefix('finance')->name('finance.')->group(function () {
    // Main Finance Dashboard
    Route::get('/', [FinanceController::class, 'index'])->name('index');
    
    Route::resource('fees', FeeController::class)->names([
        'index'   => 'fees.index',
        'create'  => 'fees.create',
        'store'   => 'fees.store',
        'show'    => 'fees.show',
        'edit'    => 'fees.edit',
        'update'  => 'fees.update',
        'destroy' => 'fees.destroy',
    ]);
    Route::resource('fee-categories', FeeCategoryController::class)->names([
        'index'   => 'fee-categories.index',
        'create'  => 'fee-categories.create',
        'store'   => 'fee-categories.store',
        'show'    => 'fee-categories.show',
        'edit'    => 'fee-categories.edit',
        'update'  => 'fee-categories.update',
        'destroy' => 'fee-categories.destroy',
    ]);
    Route::resource('fee-types', FeeTypeController::class)->names([
        'index'   => 'fee-types.index',
        'create'  => 'fee-types.create',
        'store'   => 'fee-types.store',
        'show'    => 'fee-types.show',
        'edit'    => 'fee-types.edit',
        'update'  => 'fee-types.update',
        'destroy' => 'fee-types.destroy',
    ]);
    Route::resource('payments', PaymentController::class);
    Route::get('bank-reconciliation', [BankReconciliationController::class, 'index'])->name('bank-reconciliation.index');
    Route::post('bank-reconciliation/import', [BankReconciliationController::class, 'import'])->name('bank-reconciliation.import');
    Route::post('bank-reconciliation/{transaction}/match', [BankReconciliationController::class, 'match'])->name('bank-reconciliation.match');
    Route::patch('bank-reconciliation/{transaction}/status', [BankReconciliationController::class, 'updateStatus'])->name('bank-reconciliation.update-status');
    Route::get('ledger', [LedgerController::class, 'index'])->name('ledger.index');
    Route::get('gl', [GLController::class, 'index'])->name('gl.index');
    Route::get('gl/{entry}', [GLController::class, 'show'])->name('gl.show');
    Route::resource('multi-banks', MultiBankController::class)->names([
        'index'   => 'banks.index',
        'create'  => 'banks.create',
        'store'   => 'banks.store',
        'show'    => 'banks.show',
        'edit'    => 'banks.edit',
        'update'  => 'banks.update',
        'destroy' => 'banks.destroy',
    ]);
    Route::get('forecasting', [ForecastingController::class, 'index'])->name('forecasting.index');
    Route::get('forecasting/create', [ForecastingController::class, 'create'])->name('forecasting.create');
    Route::post('forecasting', [ForecastingController::class, 'store'])->name('forecasting.store');
    Route::get('forecasting/variance', [ForecastingController::class, 'variance'])->name('forecasting.variance');
    Route::get('online-payments/mpesa', [OnlinePaymentController::class, 'mpesa'])->name('online-payments.mpesa');
    Route::post('online-payments/mpesa/callback', [OnlinePaymentController::class, 'mpesaCallback'])->name('online-payments.mpesa.callback');
    Route::resource('online-payments', OnlinePaymentController::class);
    Route::resource('billing', BillingController::class);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/download-dashboard-pdf', [ReportController::class, 'downloadDashboardPdf'])->name('reports.download-dashboard-pdf');
    Route::get('reports/fee-collection', [ReportController::class, 'feeCollection'])->name('reports.fee-collection');
    Route::get('reports/outstanding-balances', [ReportController::class, 'outstandingBalances'])->name('reports.outstanding-balances');
    Route::get('reports/income-expense', [ReportController::class, 'incomeExpense'])->name('reports.income-expense');
    Route::resource('receipts', ReceiptController::class);
    Route::resource('taxes', TaxController::class);
    Route::resource('roles', \Modules\Finance\Http\Controllers\FinanceRoleController::class)->names([
        'index'   => 'roles.index',
        'create'  => 'roles.create',
        'store'   => 'roles.store',
        'show'    => 'roles.show',
        'edit'    => 'roles.edit',
        'update'  => 'roles.update',
        'destroy' => 'roles.destroy',
    ]);
    Route::get('banks/transfer', [MultiBankController::class, 'transfer'])->name('banks.transfer');
    Route::post('banks/transfer', [MultiBankController::class, 'storeTransfer'])->name('banks.storeTransfer');
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
}); 
