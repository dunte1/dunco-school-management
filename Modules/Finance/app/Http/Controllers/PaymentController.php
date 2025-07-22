<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Finance\Entities\FinanceSetting;
use Modules\Academic\Models\StudentFee;
use App\Models\User;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PDF;
use Illuminate\Support\Facades\Notification;
use Modules\Notification\Notifications\GeneralNotification;
use Modules\Finance\Services\MpesaService;
use Modules\Finance\Entities\MpesaTransaction;


class PaymentController extends Controller
{
    private $client;
    private $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
        $settings = FinanceSetting::find(1)?->settings ?? [];
        if (!empty($settings['paypal_enabled'])) {
            $environment = $settings['paypal_mode'] === 'live'
                ? new ProductionEnvironment($settings['paypal_live_client_id'], $settings['paypal_live_client_secret'])
                : new SandboxEnvironment($settings['paypal_sandbox_client_id'], $settings['paypal_sandbox_client_secret']);
            $this->client = new PayPalHttpClient($environment);
        }
    }

    public function pay(Request $request, $fee_id)
    {
        $fee = StudentFee::findOrFail($fee_id);
        $this->authorize('pay', $fee);
        $amount = $request->input('amount', $fee->outstanding_amount);

        if ($amount > $fee->outstanding_amount) {
            return back()->with('error', 'Payment amount cannot exceed the balance due.');
        }
        
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => "{$fee->id}_{$amount}",
                "amount" => [
                    "value" => $amount,
                    "currency_code" => "USD" // Or get from settings
                ]
            ]],
            "application_context" => [
                "cancel_url" => route('finance.payment.cancel'),
                "return_url" => route('finance.payment.success')
            ]
        ];

        try {
            $response = $this->client->execute($request);
            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    return redirect()->away($link->href);
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('portal.finance')->with('error', 'Error creating PayPal payment: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $request = new OrdersCaptureRequest($request->token);
        $request->prefer('return=representation');

        try {
            $response = $this->client->execute($request);
            
            if ($response->result->status === 'COMPLETED') {
                $referenceData = explode('_', $response->result->purchase_units[0]->reference_id);
                $fee_id = $referenceData[0];
                $paidAmount = $referenceData[1];

                $fee = StudentFee::findOrFail($fee_id);
                
                // Update fee status
                if ($paidAmount >= $fee->outstanding_amount) {
                    $fee->status = 'paid';
                } else {
                    $fee->status = 'partial';
                }
                $fee->save();
                
                // Create a payment record
                 $payment = \Modules\Academic\Models\StudentPayment::create([
                    'student_id' => $fee->student_id,
                    'student_fee_id' => $fee->id,
                    'amount' => $paidAmount,
                    'payment_date' => now(),
                    'method' => 'paypal',
                    'reference' => $response->result->id,
                    'notes' => session('payment_notes')
                ]);
                session()->forget('payment_notes');

                // Generate and store receipt
                $receiptPath = $this->generateReceipt($payment);

                // Notify user
                $user = auth()->user();
                Notification::send($user, new GeneralNotification(
                    'Payment Successful',
                    'Your payment for ' . $fee->category . ' was successful.',
                    'portal.finance',
                    $receiptPath
                ));

                return redirect()->route('portal.finance')->with('success', 'Payment successful!');
            }
        } catch (\Exception $e) {
            return redirect()->route('portal.finance')->with('error', 'Error capturing PayPal payment: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('portal.finance')->with('error', 'Payment was cancelled.');
    }
    
    public function mpesaStkPush(Request $request, $fee_id)
    {
        $request->validate([
            'phone' => 'required|numeric|digits_between:10,12',
            'amount' => 'required|numeric|min:1'
        ]);
        $fee = StudentFee::findOrFail($fee_id);
        $this->authorize('pay', $fee);
        $amount = $request->input('amount');

        if ($amount > $fee->outstanding_amount) {
            return back()->with('error', 'Payment amount cannot exceed the balance due.');
        }
        
        $settings = FinanceSetting::find(1)?->settings ?? [];
        
        try {
            $response = $this->mpesaService->stkPush(
                $amount,
                $request->phone,
                $settings['mpesa_shortcode'],
                $settings['mpesa_passkey'],
                route('finance.payment.mpesa-callback'),
                'DUNCO-'.$fee->id, // AccountReference
                'Fee Payment' // TransactionDesc
            );

            if (isset($response['ResponseCode']) && $response['ResponseCode'] == 0) {
                MpesaTransaction::create([
                    'student_fee_id' => $fee->id,
                    'checkout_request_id' => $response['CheckoutRequestID'],
                    'amount' => $amount,
                    'notes' => $request->input('notes')
                ]);
                return back()->with('success', 'STK push sent. Please enter your PIN.');
            } else {
                return back()->with('error', $response['ResponseDescription'] ?? 'M-Pesa STK push failed.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error initiating M-Pesa payment: ' . $e->getMessage());
        }
    }

    public function mpesaCallback(Request $request)
    {
        $callbackData = $request->all();
        \Illuminate\Support\Facades\Log::info('M-Pesa Callback:', $callbackData);
        
        $resultCode = $callbackData['Body']['stkCallback']['ResultCode'];
        $checkoutRequestId = $callbackData['Body']['stkCallback']['CheckoutRequestID'];
        $transaction = MpesaTransaction::where('checkout_request_id', $checkoutRequestId)->first();

        if (!$transaction) {
            \Illuminate\Support\Facades\Log::error("M-Pesa callback for unknown CheckoutRequestID: $checkoutRequestId");
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Rejected']);
        }

        if ($resultCode === 0) {
            $transaction->update(['status' => 'completed']);
            $meta = $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'];
            $amount = collect($meta)->firstWhere('Name', 'Amount')['Value'];
            $receipt = collect($meta)->firstWhere('Name', 'MpesaReceiptNumber')['Value'];
            
            $fee = $transaction->studentFee;
            
            if ($transaction->amount >= $fee->outstanding_amount) {
                $fee->status = 'paid';
            } else {
                $fee->status = 'partial';
            }
            $fee->save();
            
            $payment = \Modules\Academic\Models\StudentPayment::create([
                'student_id' => $fee->student_id,
                'student_fee_id' => $fee->id,
                'amount' => $transaction->amount,
                'payment_date' => now(),
                'method' => 'mpesa',
                'reference' => $receipt,
                'notes' => $transaction->notes
            ]);

            $receiptPath = $this->generateReceipt($payment);
            $user = $fee->student->user;
            if ($user) {
                Notification::send($user, new GeneralNotification(
                    'Payment Successful',
                    'Your M-Pesa payment for ' . $fee->category . ' was successful.',
                    'portal.finance',
                    $receiptPath
                ));
            }

        } else {
            $transaction->update(['status' => 'failed']);
        }
        
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function submitBankTransfer(Request $request, $fee_id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'bank_reference' => 'required|string|max:255',
            'payment_slip' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $fee = StudentFee::findOrFail($fee_id);
        $this->authorize('pay', $fee);

        $path = $request->file('payment_slip')->store('payment_slips', 'public');

        \Modules\Academic\Models\StudentPayment::create([
            'student_id' => $fee->student_id,
            'student_fee_id' => $fee->id,
            'amount' => $request->input('amount'),
            'payment_date' => now(),
            'method' => 'bank_transfer',
            'status' => 'pending',
            'reference' => $request->input('bank_reference'),
            'notes' => $request->input('notes'),
            'attachment_path' => $path,
        ]);

        return back()->with('success', 'Bank transfer submitted for confirmation.');
    }
    
    private function generateReceipt($payment)
    {
        $data = [
            'payment' => $payment,
        ];
        $pdf = PDF::loadView('finance::receipts.detailed_template', $data);
        $fileName = 'receipt-' . $payment->id . '-' . time() . '.pdf';
        $path = 'public/receipts/' . $fileName;
        \Illuminate\Support\Facades\Storage::put($path, $pdf->output());
        return $path;
    }
} 