<?php

namespace Modules\Finance\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Finance\Entities\FinanceSetting;
use Modules\Finance\Entities\MpesaTransaction;

class MpesaService
{
    protected $baseUrl;
    protected $consumerKey;
    protected $consumerSecret;
    protected $shortcode;
    protected $passkey;
    protected $env;

    public function __construct()
    {
        $settings = FinanceSetting::find(1)?->settings ?? [];
        $this->env = $settings['mpesa_env'] ?? 'sandbox';
        
        $this->baseUrl = $this->env === 'live' 
            ? 'https://api.safaricom.co.ke' 
            : 'https://sandbox.safaricom.co.ke';
            
        $this->consumerKey = $settings['mpesa_consumer_key'] ?? '';
        $this->consumerSecret = $settings['mpesa_consumer_secret'] ?? '';
        $this->shortcode = $settings['mpesa_shortcode'] ?? '';
        $this->passkey = $settings['mpesa_passkey'] ?? '';
    }

    /**
     * Get Daraja API Access Token
     */
    public function getAccessToken()
    {
        try {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get($this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials');
            
            if ($response->successful()) {
                $data = $response->json();
                Log::info('M-Pesa Access Token Generated', ['env' => $this->env]);
                return $data['access_token'] ?? null;
            } else {
                Log::error('M-Pesa Access Token Failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return null;
            }
        } catch (\Exception $e) {
            Log::error('M-Pesa Access Token Exception', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    /**
     * STK Push for Customer to Business (C2B)
     */
    public function stkPush($amount, $phone, $accountReference, $transactionDesc, $callbackUrl = null)
    {
        try {
        $token = $this->getAccessToken();
        if (!$token) {
            throw new \Exception("Could not get M-Pesa access token.");
        }
            
            // Format phone number (remove +254 and add 254)
            $phone = $this->formatPhoneNumber($phone);
        
        $timestamp = now()->format('YmdHis');
            $password = base64_encode($this->shortcode . $this->passkey . $timestamp);
        
            $payload = [
                'BusinessShortCode' => $this->shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $amount,
                'PartyA' => $phone,
                'PartyB' => $this->shortcode,
                'PhoneNumber' => $phone,
                'CallBackURL' => $callbackUrl ?? route('finance.payment.mpesa-callback'),
                'AccountReference' => $accountReference,
                'TransactionDesc' => $transactionDesc,
            ];
            
            $response = Http::withToken($token)
                ->post($this->baseUrl . '/mpesa/stkpush/v1/processrequest', $payload);
                
            if ($response->successful()) {
                $data = $response->json();
                Log::info('M-Pesa STK Push Success', [
                    'phone' => $phone,
                    'amount' => $amount,
                    'checkout_request_id' => $data['CheckoutRequestID'] ?? null
                ]);
                return $data;
            } else {
                Log::error('M-Pesa STK Push Failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'payload' => $payload
                ]);
                throw new \Exception("STK Push failed: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('M-Pesa STK Push Exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * C2B Register URLs
     */
    public function registerC2BUrls($confirmationUrl, $validationUrl)
    {
        try {
            $token = $this->getAccessToken();
            if (!$token) {
                throw new \Exception("Could not get M-Pesa access token.");
            }
            
            $payload = [
                'ShortCode' => $this->shortcode,
                'ResponseType' => 'Completed',
                'ConfirmationURL' => $confirmationUrl,
                'ValidationURL' => $validationUrl,
            ];
            
            $response = Http::withToken($token)
                ->post($this->baseUrl . '/mpesa/c2b/v1/registerurl', $payload);
                
            if ($response->successful()) {
                Log::info('M-Pesa C2B URLs Registered', ['response' => $response->json()]);
        return $response->json();
            } else {
                Log::error('M-Pesa C2B URLs Registration Failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception("C2B URL registration failed: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('M-Pesa C2B URLs Registration Exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * B2C Payment (Business to Customer)
     */
    public function b2cPayment($amount, $phone, $remarks, $initiatorName, $securityCredential)
    {
        try {
            $token = $this->getAccessToken();
            if (!$token) {
                throw new \Exception("Could not get M-Pesa access token.");
            }
            
            $phone = $this->formatPhoneNumber($phone);
            
            $payload = [
                'InitiatorName' => $initiatorName,
                'SecurityCredential' => $securityCredential,
                'CommandID' => 'BusinessPayment',
                'Amount' => $amount,
                'PartyA' => $this->shortcode,
                'PartyB' => $phone,
                'Remarks' => $remarks,
                'QueueTimeOutURL' => route('finance.payment.mpesa-timeout'),
                'ResultURL' => route('finance.payment.mpesa-result'),
                'Occasion' => 'School Payment',
            ];
            
            $response = Http::withToken($token)
                ->post($this->baseUrl . '/mpesa/b2c/v1/paymentrequest', $payload);
                
            if ($response->successful()) {
                $data = $response->json();
                Log::info('M-Pesa B2C Payment Success', [
                    'phone' => $phone,
                    'amount' => $amount,
                    'conversation_id' => $data['ConversationID'] ?? null
                ]);
                return $data;
            } else {
                Log::error('M-Pesa B2C Payment Failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'payload' => $payload
                ]);
                throw new \Exception("B2C payment failed: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('M-Pesa B2C Payment Exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Check STK Push Status
     */
    public function checkStkStatus($checkoutRequestId)
    {
        try {
            $token = $this->getAccessToken();
            if (!$token) {
                throw new \Exception("Could not get M-Pesa access token.");
            }
            
            $timestamp = now()->format('YmdHis');
            $password = base64_encode($this->shortcode . $this->passkey . $timestamp);
            
            $payload = [
                'BusinessShortCode' => $this->shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'CheckoutRequestID' => $checkoutRequestId,
            ];
            
            $response = Http::withToken($token)
                ->post($this->baseUrl . '/mpesa/stkpushquery/v1/query', $payload);
                
            if ($response->successful()) {
                $data = $response->json();
                Log::info('M-Pesa STK Status Check', [
                    'checkout_request_id' => $checkoutRequestId,
                    'result_code' => $data['ResultCode'] ?? null
                ]);
                return $data;
            } else {
                Log::error('M-Pesa STK Status Check Failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception("STK status check failed: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('M-Pesa STK Status Check Exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Reverse Transaction
     */
    public function reverseTransaction($transactionId, $amount, $phone, $remarks)
    {
        try {
            $token = $this->getAccessToken();
            if (!$token) {
                throw new \Exception("Could not get M-Pesa access token.");
            }
            
            $phone = $this->formatPhoneNumber($phone);
            
            $payload = [
                'Initiator' => 'SchoolSystem',
                'SecurityCredential' => $this->generateSecurityCredential(),
                'CommandID' => 'TransactionReversal',
                'TransactionID' => $transactionId,
                'Amount' => $amount,
                'ReceiverParty' => $phone,
                'RecieverIdentifierType' => 1,
                'ResultURL' => route('finance.payment.mpesa-reverse-result'),
                'QueueTimeOutURL' => route('finance.payment.mpesa-reverse-timeout'),
                'Remarks' => $remarks,
                'Occasion' => 'School Payment Reversal',
            ];
            
            $response = Http::withToken($token)
                ->post($this->baseUrl . '/mpesa/reversal/v1/request', $payload);
                
            if ($response->successful()) {
                $data = $response->json();
                Log::info('M-Pesa Transaction Reversal Success', [
                    'transaction_id' => $transactionId,
                    'amount' => $amount
                ]);
                return $data;
            } else {
                Log::error('M-Pesa Transaction Reversal Failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception("Transaction reversal failed: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('M-Pesa Transaction Reversal Exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Get Account Balance
     */
    public function getAccountBalance()
    {
        try {
            $token = $this->getAccessToken();
            if (!$token) {
                throw new \Exception("Could not get M-Pesa access token.");
            }
            
            $payload = [
                'Initiator' => 'SchoolSystem',
                'SecurityCredential' => $this->generateSecurityCredential(),
                'CommandID' => 'AccountBalance',
                'PartyA' => $this->shortcode,
                'IdentifierType' => 4,
                'Remarks' => 'Account Balance Check',
                'QueueTimeOutURL' => route('finance.payment.mpesa-timeout'),
                'ResultURL' => route('finance.payment.mpesa-result'),
            ];
            
            $response = Http::withToken($token)
                ->post($this->baseUrl . '/mpesa/accountbalance/v1/query', $payload);
                
            if ($response->successful()) {
                $data = $response->json();
                Log::info('M-Pesa Account Balance Retrieved', ['response' => $data]);
                return $data;
            } else {
                Log::error('M-Pesa Account Balance Failed', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                throw new \Exception("Account balance check failed: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('M-Pesa Account Balance Exception', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    
    /**
     * Format phone number for M-Pesa
     */
    private function formatPhoneNumber($phone)
    {
        // Remove any non-digit characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If it starts with 0, replace with 254
        if (substr($phone, 0, 1) === '0') {
            $phone = '254' . substr($phone, 1);
        }
        
        // If it starts with +254, remove the +
        if (substr($phone, 0, 4) === '+254') {
            $phone = substr($phone, 1);
        }
        
        // If it doesn't start with 254, add it
        if (substr($phone, 0, 3) !== '254') {
            $phone = '254' . $phone;
        }
        
        return $phone;
    }
    
    /**
     * Generate Security Credential (for B2C and Reversal)
     */
    private function generateSecurityCredential()
    {
        // This should be generated using the certificate provided by Safaricom
        // For now, we'll use a placeholder
        return base64_encode('SchoolSystem' . $this->passkey);
    }
    
    /**
     * Validate M-Pesa Configuration
     */
    public function validateConfiguration()
    {
        $errors = [];
        
        if (empty($this->consumerKey)) {
            $errors[] = 'Consumer Key is required';
        }
        
        if (empty($this->consumerSecret)) {
            $errors[] = 'Consumer Secret is required';
        }
        
        if (empty($this->shortcode)) {
            $errors[] = 'Business Shortcode is required';
        }
        
        if (empty($this->passkey)) {
            $errors[] = 'Passkey is required';
        }
        
        return $errors;
    }
    
    /**
     * Test API Connection
     */
    public function testConnection()
    {
        try {
            $token = $this->getAccessToken();
            return !empty($token);
        } catch (\Exception $e) {
            Log::error('M-Pesa Connection Test Failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
