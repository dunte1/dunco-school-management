<?php

namespace Modules\Finance\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Finance\Models\Payment;
use Modules\Finance\Models\Receipt;
use Modules\Finance\Models\Invoice;
use Carbon\Carbon;

class MpesaService
{
    protected $baseUrl;
    protected $consumerKey;
    protected $consumerSecret;
    protected $shortCode;
    protected $passkey;
    protected $callbackUrl;

    public function __construct()
    {
        $this->baseUrl = config('mpesa.base_url', 'https://sandbox.safaricom.co.ke');
        $this->consumerKey = config('mpesa.consumer_key');
        $this->consumerSecret = config('mpesa.consumer_secret');
        $this->shortCode = config('mpesa.short_code');
        $this->passkey = config('mpesa.passkey');
        $this->callbackUrl = config('mpesa.callback_url');
    }

    /**
     * Initiate STK Push for M-Pesa payment
     */
    public function initiateSTKPush($phoneNumber, $amount, $invoiceId, $accountReference = null)
    {
        try {
            // Get access token
            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                throw new \Exception('Failed to get M-Pesa access token');
            }

            // Validate required credentials
            if (empty($this->passkey)) {
                throw new \Exception('M-Pesa passkey is not configured');
            }
            
            // Prepare STK Push request
            $timestamp = Carbon::now()->format('YmdHis');
            $password = base64_encode($this->shortCode . $this->passkey . $timestamp);
            
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);
            $accountReference = $accountReference ?: $invoiceId; // Remove duplicate INV- prefix

            $requestData = [
                'BusinessShortCode' => $this->shortCode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => (int) $amount,
                'PartyA' => $phoneNumber,
                'PartyB' => $this->shortCode,
                'PhoneNumber' => $phoneNumber,
                'CallBackURL' => $this->callbackUrl, // Remove duplicate /api/mpesa/callback
                'AccountReference' => $accountReference,
                'TransactionDesc' => 'School Fee Payment - Invoice ' . $invoiceId
            ];

            Log::info('M-Pesa STK Push Request', [
                'url' => $this->baseUrl . '/mpesa/stkpush/v1/processrequest',
                'request_data' => $requestData,
                'access_token_length' => strlen($accessToken)
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/mpesa/stkpush/v1/processrequest', $requestData);

            $responseData = $response->json();
            
            Log::info('M-Pesa STK Push Response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'response_data' => $responseData
            ]);

            if ($response->successful() && isset($responseData['ResponseCode']) && $responseData['ResponseCode'] == '0') {
                // Log successful STK Push initiation
                Log::info('STK Push initiated successfully', [
                    'invoice_id' => $invoiceId,
                    'phone_number' => $phoneNumber,
                    'amount' => $amount,
                    'checkout_request_id' => $responseData['CheckoutRequestID']
                ]);

                return [
                    'success' => true,
                    'checkout_request_id' => $responseData['CheckoutRequestID'],
                    'customer_message' => $responseData['CustomerMessage'],
                    'merchant_request_id' => $responseData['MerchantRequestID']
                ];
            } else {
                Log::error('STK Push failed', [
                    'response' => $responseData,
                    'invoice_id' => $invoiceId
                ]);

                return [
                    'success' => false,
                    'error' => $responseData['errorMessage'] ?? 'STK Push failed'
                ];
            }

        } catch (\Exception $e) {
            Log::error('STK Push exception', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoiceId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Handle M-Pesa callback
     */
    public function handleCallback($callbackData)
    {
        try {
            Log::info('M-Pesa callback received', $callbackData);

            $body = $callbackData['Body'];
            $stkCallback = $body['stkCallback'];
            $merchantRequestId = $stkCallback['MerchantRequestID'];
            $checkoutRequestId = $stkCallback['CheckoutRequestID'];
            $resultCode = $stkCallback['ResultCode'];
            $resultDesc = $stkCallback['ResultDesc'];

            if ($resultCode == 0) {
                // Payment successful
                $callbackMetadata = $stkCallback['CallbackMetadata']['Item'];
                $amount = $this->getCallbackItem($callbackMetadata, 'Amount');
                $mpesaReceiptNumber = $this->getCallbackItem($callbackMetadata, 'MpesaReceiptNumber');
                $transactionDate = $this->getCallbackItem($callbackMetadata, 'TransactionDate');
                $phoneNumber = $this->getCallbackItem($callbackMetadata, 'PhoneNumber');

                // Extract invoice ID from account reference
                $accountReference = $this->getCallbackItem($callbackMetadata, 'AccountReference');
                $invoiceId = $this->extractInvoiceId($accountReference);

                if ($invoiceId) {
                    $this->processSuccessfulPayment($invoiceId, $amount, $mpesaReceiptNumber, $phoneNumber, $transactionDate);
                }

                return [
                    'success' => true,
                    'message' => 'Payment processed successfully'
                ];
            } else {
                // Payment failed
                Log::warning('M-Pesa payment failed', [
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc,
                    'merchant_request_id' => $merchantRequestId
                ]);

                return [
                    'success' => false,
                    'message' => 'Payment failed: ' . $resultDesc
                ];
            }

        } catch (\Exception $e) {
            Log::error('M-Pesa callback processing error', [
                'error' => $e->getMessage(),
                'callback_data' => $callbackData
            ]);

            return [
                'success' => false,
                'message' => 'Callback processing failed'
            ];
        }
    }

    /**
     * Process successful payment
     */
    protected function processSuccessfulPayment($invoiceId, $amount, $mpesaReceiptNumber, $phoneNumber, $transactionDate)
    {
        try {
            // Find the invoice
            $invoice = Invoice::find($invoiceId);
            if (!$invoice) {
                Log::error('Invoice not found for M-Pesa payment', ['invoice_id' => $invoiceId]);
                return;
            }

            // Create payment record
            $payment = Payment::create([
                'invoice_id' => $invoiceId,
                'amount' => $amount,
                'payment_date' => Carbon::now(),
                'method' => 'mpesa',
                'status' => 'completed',
                'reference' => $mpesaReceiptNumber,
                'mpesa_transaction_id' => $mpesaReceiptNumber,
                'phone_number' => $phoneNumber,
                'transaction_date' => $transactionDate,
            ]);

            // Update invoice status
            $invoice->update(['status' => 'paid']);

            // Generate receipt
            $receipt = $this->generateReceipt($payment);

            // Send email receipt
            $this->sendEmailReceipt($receipt, $invoice);

            // Update finance module
            $this->updateFinanceModule($payment);

            Log::info('M-Pesa payment processed successfully', [
                'payment_id' => $payment->id,
                'invoice_id' => $invoiceId,
                'amount' => $amount
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing M-Pesa payment', [
                'error' => $e->getMessage(),
                'invoice_id' => $invoiceId
            ]);
        }
    }

    /**
     * Generate receipt for payment
     */
    protected function generateReceipt($payment)
    {
        try {
            $receipt = Receipt::create([
                'receipt_number' => 'RCP-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'status' => 'issued',
                'printed_at' => Carbon::now(),
            ]);

            return $receipt;

        } catch (\Exception $e) {
            Log::error('Error generating receipt', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id
            ]);
            return null;
        }
    }

    /**
     * Send email receipt
     */
    protected function sendEmailReceipt($receipt, $invoice)
    {
        try {
            if (!$receipt || !$invoice) {
                return;
            }

            $student = $invoice->student;
            $parent = $student ? $student->parent : null;

            if (!$parent || !$parent->email) {
                Log::warning('No parent email found for receipt', [
                    'invoice_id' => $invoice->id,
                    'student_id' => $student ? $student->id : null
                ]);
                return;
            }

            $receiptData = [
                'receipt' => $receipt,
                'payment' => $receipt->payment,
                'invoice' => $invoice,
                'student' => $student,
                'parent' => $parent,
            ];

            Mail::send('finance::emails.receipt', $receiptData, function ($message) use ($parent, $receipt) {
                $message->to($parent->email, $parent->name)
                    ->subject('Payment Receipt - ' . $receipt->receipt_number);
            });

            Log::info('Receipt email sent successfully', [
                'receipt_id' => $receipt->id,
                'email' => $parent->email
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending receipt email', [
                'error' => $e->getMessage(),
                'receipt_id' => $receipt ? $receipt->id : null
            ]);
        }
    }

    /**
     * Update finance module with payment
     */
    protected function updateFinanceModule($payment)
    {
        try {
            // Update bank account balance
            $bankAccount = \DB::table('bank_accounts')->where('is_active', true)->first();
            if ($bankAccount) {
                \DB::table('bank_accounts')
                    ->where('id', $bankAccount->id)
                    ->increment('balance', $payment->amount);
            }

            // Update financial reports
            $this->updateFinancialReports($payment);

            Log::info('Finance module updated with payment', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating finance module', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id
            ]);
        }
    }

    /**
     * Update financial reports
     */
    protected function updateFinancialReports($payment)
    {
        // This would update various financial reports and analytics
        // Implementation depends on your specific reporting structure
    }

    /**
     * Get M-Pesa access token
     */
    public function getAccessToken()
    {
        try {
            $url = $this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials';
            
            Log::info('M-Pesa Access Token Request', [
                'url' => $url,
                'consumer_key' => $this->consumerKey,
                'consumer_secret_set' => !empty($this->consumerSecret)
            ]);
            
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get($url);

            Log::info('M-Pesa Access Token Response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'] ?? null;
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Error getting M-Pesa access token', [
                'error' => $e->getMessage(),
                'url' => $url ?? 'not set'
            ]);
            return null;
        }
    }

    /**
     * Format phone number for M-Pesa
     */
    protected function formatPhoneNumber($phoneNumber)
    {
        // Remove any non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Add country code if not present
        if (strlen($phoneNumber) == 9) {
            $phoneNumber = '254' . $phoneNumber;
        } elseif (strlen($phoneNumber) == 10 && substr($phoneNumber, 0, 1) == '0') {
            $phoneNumber = '254' . substr($phoneNumber, 1);
        }

        return $phoneNumber;
    }

    /**
     * Get callback item from metadata
     */
    protected function getCallbackItem($metadata, $name)
    {
        foreach ($metadata as $item) {
            if ($item['Name'] == $name) {
                return $item['Value'];
            }
        }
        return null;
    }

    /**
     * Extract invoice ID from account reference
     */
    protected function extractInvoiceId($accountReference)
    {
        if (preg_match('/INV-(\d+)/', $accountReference, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Query transaction status
     */
    public function queryTransactionStatus($checkoutRequestId)
    {
        try {
            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                throw new \Exception('Failed to get M-Pesa access token');
            }

            $timestamp = Carbon::now()->format('YmdHis');
            $password = base64_encode($this->shortCode . $this->passkey . $timestamp);

            $requestData = [
                'BusinessShortCode' => $this->shortCode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'CheckoutRequestID' => $checkoutRequestId
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/mpesa/stkpushquery/v1/query', $requestData);

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Error querying transaction status', [
                'error' => $e->getMessage(),
                'checkout_request_id' => $checkoutRequestId
            ]);
            return null;
        }
    }
}
