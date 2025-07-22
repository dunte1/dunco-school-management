<?php

namespace Modules\Finance\Services;

use Illuminate\Support\Facades\Http;
use Modules\Finance\Entities\FinanceSetting;

class MpesaService
{
    protected $baseUrl;
    protected $consumerKey;
    protected $consumerSecret;

    public function __construct()
    {
        $settings = FinanceSetting::find(1)?->settings ?? [];
        $env = $settings['mpesa_env'] ?? 'sandbox';
        
        $this->baseUrl = $env === 'live' 
            ? 'https://api.safaricom.co.ke' 
            : 'https://sandbox.safaricom.co.ke';
            
        $this->consumerKey = $settings['mpesa_consumer_key'] ?? '';
        $this->consumerSecret = $settings['mpesa_consumer_secret'] ?? '';
    }

    public function getAccessToken()
    {
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get($this->baseUrl . '/oauth/v1/generate?grant_type=client_credentials');
            
        return $response->json()['access_token'] ?? null;
    }
    
    public function stkPush($amount, $phone, $shortcode, $passkey, $callbackUrl, $accountReference, $transactionDesc)
    {
        $token = $this->getAccessToken();
        if (!$token) {
            throw new \Exception("Could not get M-Pesa access token.");
        }
        
        $timestamp = now()->format('YmdHis');
        $password = base64_encode($shortcode . $passkey . $timestamp);
        
        $response = Http::withToken($token)
            ->post($this->baseUrl . '/mpesa/stkpush/v1/processrequest', [
                'BusinessShortCode' => $shortcode,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $amount,
                'PartyA' => $phone,
                'PartyB' => $shortcode,
                'PhoneNumber' => $phone,
                'CallBackURL' => $callbackUrl,
                'AccountReference' => $accountReference,
                'TransactionDesc' => $transactionDesc,
            ]);
            
        return $response->json();
    }
}
