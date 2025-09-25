<?php

namespace Modules\Finance\Services;

use Illuminate\Support\Facades\Log;

class CardPaymentService
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Simulate sending a card payment request.
     */
    public function sendPayment($cardNumber, $amount, $reference)
    {
        Log::info('Simulating Card payment', [
            'card_number' => substr($cardNumber, -4),
            'amount' => $amount,
            'reference' => $reference,
            'api_key' => $this->apiKey,
        ]);
        // Simulate a successful response
        return [
            'success' => true,
            'transaction_code' => 'CARD' . rand(100000, 999999),
            'message' => 'Card payment simulated successfully',
        ];
    }
} 