<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MPesaController extends Controller
{
    use ApiResponse;

    // M-PESA configuration from your production .env
    private $config = [
        'environment' => 'sandbox',
        'base_url' => 'https://sandbox.safaricom.co.ke',
        'consumer_key' => 'KtupyCyTvY4AeYtaz7V1bWM2Q9bRYzrGfwZy5D2zexmlB3sQ',
        'consumer_secret' => 'kPLjbuiGGJiP7Hfn88GpoakQW6ckI0KDEWFTOFne5MGn8bnrlG2ufAUYlo3U3qhj',
        'short_code' => '174379',
        'passkey' => 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919',
        'callback_url' => 'https://multischool.duncowebsolutions.co.ke/api/mpesa/callback',
        'result_url' => 'https://multischool.duncowebsolutions.co.ke/api/mpesa/result',
    ];

    public function initiatePayment(Request $request)
    {
        try {
            $user = $request->user();
            $amount = $request->input('amount');
            $phoneNumber = $request->input('phone_number');
            $accountReference = $request->input('account_reference');
            $transactionDesc = $request->input('transaction_desc', 'School Fee Payment');

            // Validate input
            $request->validate([
                'amount' => 'required|numeric|min:1',
                'phone_number' => 'required|string|min:10|max:15',
                'account_reference' => 'required|string|max:40',
            ]);

            // Format phone number
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);
            
            // Get access token
            $accessToken = $this->getAccessToken();
            
            // Generate timestamp
            $timestamp = date('YmdHis');
            
            // Generate password
            $password = base64_encode($this->config['short_code'] . $this->config['passkey'] . $timestamp);
            
            // Prepare STK Push request
            $stkPushData = [
                'BusinessShortCode' => $this->config['short_code'],
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => (int) $amount,
                'PartyA' => $formattedPhone,
                'PartyB' => $this->config['short_code'],
                'PhoneNumber' => $formattedPhone,
                'CallBackURL' => $this->config['callback_url'],
                'AccountReference' => $accountReference,
                'TransactionDesc' => $transactionDesc,
            ];

            // Send STK Push request
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($this->config['base_url'] . '/mpesa/stkpush/v1/processrequest', $stkPushData);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // Store payment request in database
                $paymentRequest = \App\Models\PaymentRequest::create([
                    'user_id' => $user->id,
                    'merchant_request_id' => $responseData['MerchantRequestID'],
                    'checkout_request_id' => $responseData['CheckoutRequestID'],
                    'amount' => $amount,
                    'phone_number' => $formattedPhone,
                    'account_reference' => $accountReference,
                    'transaction_desc' => $transactionDesc,
                    'status' => 'pending',
                    'school_id' => $user->school_id,
                ]);

                return $this->successResponse([
                    'merchant_request_id' => $responseData['MerchantRequestID'],
                    'checkout_request_id' => $responseData['CheckoutRequestID'],
                    'response_code' => $responseData['ResponseCode'],
                    'response_description' => $responseData['ResponseDescription'],
                    'customer_message' => $responseData['CustomerMessage'],
                ], 'Payment initiated successfully');
            } else {
                Log::error('M-PESA STK Push failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return $this->errorResponse('Failed to initiate payment. Please try again.', 500);
            }
        } catch (\Exception $e) {
            Log::error('M-PESA payment initiation error: ' . $e->getMessage());
            return $this->errorResponse('Failed to initiate payment.', 500);
        }
    }

    public function checkPaymentStatus(Request $request, $checkoutRequestId)
    {
        try {
            $user = $request->user();
            
            // Get payment request from database
            $paymentRequest = \App\Models\PaymentRequest::where('checkout_request_id', $checkoutRequestId)
                ->where('user_id', $user->id)
                ->first();

            if (!$paymentRequest) {
                return $this->errorResponse('Payment request not found.', 404);
            }

            // If payment is already completed, return the status
            if ($paymentRequest->status === 'completed') {
                return $this->successResponse([
                    'merchant_request_id' => $paymentRequest->merchant_request_id,
                    'checkout_request_id' => $paymentRequest->checkout_request_id,
                    'result_code' => '0',
                    'result_desc' => 'Payment successful',
                    'amount' => $paymentRequest->amount,
                    'mpesa_receipt_number' => $paymentRequest->mpesa_receipt_number,
                    'transaction_date' => $paymentRequest->completed_at,
                    'phone_number' => $paymentRequest->phone_number,
                ], 'Payment status retrieved successfully');
            }

            // If payment is still pending, return pending status
            return $this->successResponse([
                'merchant_request_id' => $paymentRequest->merchant_request_id,
                'checkout_request_id' => $paymentRequest->checkout_request_id,
                'result_code' => '1',
                'result_desc' => 'Payment pending',
                'amount' => $paymentRequest->amount,
                'phone_number' => $paymentRequest->phone_number,
            ], 'Payment status retrieved successfully');
        } catch (\Exception $e) {
            Log::error('M-PESA payment status check error: ' . $e->getMessage());
            return $this->errorResponse('Failed to check payment status.', 500);
        }
    }

    public function getPaymentHistory(Request $request)
    {
        try {
            $user = $request->user();
            
            $payments = \App\Models\PaymentRequest::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            $formattedPayments = $payments->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'merchant_request_id' => $payment->merchant_request_id,
                    'checkout_request_id' => $payment->checkout_request_id,
                    'amount' => $payment->amount,
                    'phone_number' => $payment->phone_number,
                    'account_reference' => $payment->account_reference,
                    'transaction_desc' => $payment->transaction_desc,
                    'status' => $payment->status,
                    'mpesa_receipt_number' => $payment->mpesa_receipt_number,
                    'created_at' => $payment->created_at->toISOString(),
                    'completed_at' => $payment->completed_at?->toISOString(),
                ];
            });

            return $this->successResponse($formattedPayments, 'Payment history retrieved successfully');
        } catch (\Exception $e) {
            Log::error('M-PESA payment history error: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve payment history.', 500);
        }
    }

    public function handleCallback(Request $request)
    {
        try {
            $callbackData = $request->all();
            
            Log::info('M-PESA Callback received', $callbackData);

            // Find the payment request
            $paymentRequest = \App\Models\PaymentRequest::where('checkout_request_id', $callbackData['Body']['stkCallback']['CheckoutRequestID'])
                ->first();

            if ($paymentRequest) {
                $resultCode = $callbackData['Body']['stkCallback']['ResultCode'];
                
                if ($resultCode === 0) {
                    // Payment successful
                    $callbackMetadata = $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'];
                    
                    $paymentRequest->update([
                        'status' => 'completed',
                        'mpesa_receipt_number' => $callbackMetadata[1]['Value'] ?? null,
                        'completed_at' => now(),
                    ]);

                    // Trigger real-time update
                    broadcast(new \App\Events\PaymentCompleted($paymentRequest))->toOthers();
                } else {
                    // Payment failed
                    $paymentRequest->update([
                        'status' => 'failed',
                        'failure_reason' => $callbackData['Body']['stkCallback']['ResultDesc'],
                    ]);
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('M-PESA callback error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    private function getAccessToken()
    {
        try {
            $response = Http::withBasicAuth($this->config['consumer_key'], $this->config['consumer_secret'])
                ->get($this->config['base_url'] . '/oauth/v1/generate?grant_type=client_credentials');

            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'];
            } else {
                throw new \Exception('Failed to get access token');
            }
        } catch (\Exception $e) {
            Log::error('M-PESA access token error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function formatPhoneNumber($phoneNumber)
    {
        // Remove any non-digit characters
        $cleaned = preg_replace('/\D/', '', $phoneNumber);
        
        // Add 254 prefix if not present
        if (strpos($cleaned, '254') === 0) {
            return $cleaned;
        } elseif (strpos($cleaned, '0') === 0) {
            return '254' . substr($cleaned, 1);
        } elseif (strpos($cleaned, '7') === 0) {
            return '254' . $cleaned;
        } else {
            return '254' . $cleaned;
        }
    }
}
