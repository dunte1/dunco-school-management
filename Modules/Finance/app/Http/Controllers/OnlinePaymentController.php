<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\Payment;
use Modules\Finance\Models\Invoice;
use Modules\Finance\Entities\FinanceSetting;
use Modules\Finance\Services\MpesaService;
use Modules\Finance\Services\CardPaymentService;
use Modules\Finance\Services\FinanceNotificationService;
use App\Models\AuditLog;

class OnlinePaymentController extends Controller
{
    /**
     * Initiate an MPESA payment for a given invoice.
     */
    public function payWithMpesa(Request $request, Invoice $invoice)
    {
        // Get settings
        $settings = FinanceSetting::find(1)?->settings ?? [];
        $mpesaApiKey = $settings['mpesa_api_key'] ?? null;

        // Validate input
        $data = $request->validate([
            'mpesa_phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:1',
        ]);

        // Use the MPESA service
        $mpesa = new MpesaService($mpesaApiKey);
        $result = $mpesa->sendPayment($data['mpesa_phone'], $data['amount'], 'INV-' . $invoice->id);

        // Log the attempt
        AuditLog::log(
            'finance.mpesa.attempt',
            'MPESA payment attempt',
            null,
            [
                'invoice_id' => $invoice->id,
                'phone' => $data['mpesa_phone'],
                'amount' => $data['amount'],
                'result' => $result,
            ]
        );

        if ($result['success']) {
            // Record payment
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $data['amount'],
                'payment_date' => now(),
                'method' => 'MPESA',
                'status' => 'completed',
                'mpesa_transaction_code' => $result['transaction_code'],
                'mpesa_phone' => $data['mpesa_phone'],
            ]);
            // Update invoice status
            $totalPaid = $invoice->payments()->sum('amount');
            $invoice->status = $totalPaid >= $invoice->total_amount ? 'paid' : 'partial';
            $invoice->save();

            // Audit log
            AuditLog::log(
                'finance.mpesa.success',
                'MPESA payment successful',
                null,
                [
                    'invoice_id' => $invoice->id,
                    'payment_id' => $payment->id,
                    'transaction_code' => $result['transaction_code'],
                ]
            );

            // Send notification
            $user = $invoice->user ?? auth()->user();
            $notificationService = new FinanceNotificationService($settings);
            $notificationService->sendPaymentNotification($user, 'payment_success', [
                'amount' => $data['amount'],
                'invoice_id' => $invoice->id,
            ]);

            return response()->json(['success' => true, 'message' => 'MPESA payment recorded successfully.']);
        } else {
            // Audit log failure
            AuditLog::log(
                'finance.mpesa.failed',
                'MPESA payment failed',
                null,
                [
                    'invoice_id' => $invoice->id,
                    'phone' => $data['mpesa_phone'],
                    'amount' => $data['amount'],
                    'result' => $result,
                ]
            );
            return response()->json(['success' => false, 'message' => 'MPESA payment failed.'], 400);
        }
    }

    public function payWithCard(Request $request, Invoice $invoice)
    {
        // Get settings
        $settings = FinanceSetting::find(1)?->settings ?? [];
        $cardApiKey = $settings['card_api_key'] ?? null;

        // Validate input
        $data = $request->validate([
            'card_number' => 'required|string|min:12|max:19',
            'amount' => 'required|numeric|min:1',
        ]);

        // Use the Card Payment service
        $cardService = new CardPaymentService($cardApiKey);
        $result = $cardService->sendPayment($data['card_number'], $data['amount'], 'INV-' . $invoice->id);

        // Log the attempt
        AuditLog::log(
            'finance.card.attempt',
            'Card payment attempt',
            null,
            [
                'invoice_id' => $invoice->id,
                'card_number' => substr($data['card_number'], -4),
                'amount' => $data['amount'],
                'result' => $result,
            ]
        );

        if ($result['success']) {
        // Record payment
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'amount' => $data['amount'],
                'payment_date' => now(),
                'method' => 'CARD',
                'status' => 'completed',
                'card_transaction_code' => $result['transaction_code'],
                'card_number' => substr($data['card_number'], -4),
            ]);
            // Update invoice status
            $totalPaid = $invoice->payments()->sum('amount');
            $invoice->status = $totalPaid >= $invoice->total_amount ? 'paid' : 'partial';
            $invoice->save();

            // Audit log
            AuditLog::log(
                'finance.card.success',
                'Card payment successful',
                null,
                [
                    'invoice_id' => $invoice->id,
                    'payment_id' => $payment->id,
                    'transaction_code' => $result['transaction_code'],
                ]
            );

            // Send notification
            $user = $invoice->user ?? auth()->user();
            $notificationService = new FinanceNotificationService($settings);
            $notificationService->sendPaymentNotification($user, 'payment_success', [
                'amount' => $data['amount'],
                'invoice_id' => $invoice->id,
            ]);

            return response()->json(['success' => true, 'message' => 'Card payment recorded successfully.']);
        } else {
            // Audit log failure
            AuditLog::log(
                'finance.card.failed',
                'Card payment failed',
                null,
                [
                    'invoice_id' => $invoice->id,
                    'card_number' => substr($data['card_number'], -4),
                    'amount' => $data['amount'],
                    'result' => $result,
                ]
            );
            return response()->json(['success' => false, 'message' => 'Card payment failed.'], 400);
        }
    }

    public function submitBankTransfer(Request $request, Invoice $invoice)
    {
        $settings = FinanceSetting::find(1)?->settings ?? [];

        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
            'bank_reference' => 'required|string|max:255',
        ]);

        // Log the submission
        AuditLog::log(
            'finance.bank_transfer.submitted',
            'Bank transfer submitted for manual confirmation',
            null,
            [
                'invoice_id' => $invoice->id,
                'amount' => $data['amount'],
                'bank_reference' => $data['bank_reference'],
            ]
        );

        // Store as pending payment (not completed until confirmed)
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $data['amount'],
            'payment_date' => now(),
            'method' => 'BANK',
            'status' => 'pending',
            'bank_reference' => $data['bank_reference'],
        ]);

        // (Stub) Notify admin/finance manager for confirmation
        // TODO: Implement real notification

        return response()->json(['success' => true, 'message' => 'Bank transfer submitted for confirmation.']);
    }

    public function confirmBankTransfer(Request $request, Payment $payment)
    {
        $settings = FinanceSetting::find(1)?->settings ?? [];

        // Only allow if user is admin/finance_manager
        if (!auth()->user()->hasAnyRole(['admin', 'finance_manager'])) {
            abort(403, 'Unauthorized');
        }

        $oldStatus = $payment->status;
        $payment->status = 'completed';
        $payment->save();

        // Update invoice status
        $invoice = $payment->invoice;
        $totalPaid = $invoice->payments()->where('status', 'completed')->sum('amount');
        $invoice->status = $totalPaid >= $invoice->total_amount ? 'paid' : 'partial';
        $invoice->save();

        // Audit log
        AuditLog::log(
            'finance.bank_transfer.confirmed',
            'Bank transfer manually confirmed',
            ['status' => $oldStatus],
            ['status' => $payment->status]
        );

        // Send notification
        $user = $payment->invoice->user ?? auth()->user();
        $notificationService = new FinanceNotificationService($settings);
        $notificationService->sendPaymentNotification($user, 'payment_success', [
            'amount' => $payment->amount,
            'invoice_id' => $payment->invoice->id,
        ]);

        return response()->json(['success' => true, 'message' => 'Bank transfer confirmed.']);
    }

    public function mpesa(Request $request)
    {
        $invoiceId = $request->query('invoice');
        $invoice = \Modules\Finance\Models\Invoice::find($invoiceId);
        if (!$invoice) {
            abort(404, 'Invoice not found');
        }
        return view('finance::online_payments.mpesa', compact('invoice'));
    }

    /**
     * Handle MPESA payment form submission (stub).
     */
    public function mpesaCallback(Request $request)
    {
        // TODO: Implement real MPESA payment logic here
        return response()->json(['success' => true, 'message' => 'MPESA callback received (stub).']);
    }
}
