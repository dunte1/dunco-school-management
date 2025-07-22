<?php

namespace Modules\Finance\Services;

use Illuminate\Support\Facades\Log;

class MpesaService
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Simulate sending a payment request to MPESA API.
     */
    public function sendPayment($phone, $amount, $reference)
    {
        // In real implementation, use Guzzle or Http client to call MPESA API
        Log::info('Simulating MPESA payment', [
            'phone' => $phone,
            'amount' => $amount,
            'reference' => $reference,
            'api_key' => $this->apiKey,
        ]);
        // Simulate a successful response
        return [
            'success' => true,
            'transaction_code' => 'MPESA' . rand(100000, 999999),
            'message' => 'Payment simulated successfully',
        ];
    }
} 