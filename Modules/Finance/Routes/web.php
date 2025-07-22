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

// Payment Gateway Routes (must be outside the admin group for portal access)
Route::post('finance/payment/pay/{fee_id}', [PaymentController::class, 'pay'])->name('finance.payment.pay');
Route::get('finance/payment/success', [PaymentController::class, 'success'])->name('finance.payment.success');
Route::get('finance/payment/cancel', [PaymentController::class, 'cancel'])->name('finance.payment.cancel');
Route::post('finance/payment/mpesa-stk/{fee_id}', [PaymentController::class, 'mpesaStkPush'])->name('finance.payment.mpesa-stk');
Route::post('finance/payment/mpesa-callback', [PaymentController::class, 'mpesaCallback'])->name('finance.payment.mpesa-callback');
Route::post('finance/payment/bank-transfer/{fee_id}', [PaymentController::class, 'submitBankTransfer'])->name('finance.payment.bank-transfer');

Route::prefix('finance')->name('finance.')->group(function () {
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
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
}); 