<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentGatewayController extends Controller
{
    use ApiResponse;

    /**
     * Get payment methods available
     */
    public function getPaymentMethods(Request $request)
    {
        try {
            $paymentMethods = [
                [
                    'id' => 'mpesa',
                    'name' => 'M-Pesa',
                    'description' => 'Pay using M-Pesa mobile money',
                    'icon' => 'mpesa_icon.png',
                    'enabled' => true,
                    'fees' => 0,
                    'min_amount' => 1,
                    'max_amount' => 150000,
                ],
                [
                    'id' => 'bank_transfer',
                    'name' => 'Bank Transfer',
                    'description' => 'Direct bank transfer',
                    'icon' => 'bank_icon.png',
                    'enabled' => true,
                    'fees' => 0,
                    'min_amount' => 1,
                    'max_amount' => 1000000,
                ],
                [
                    'id' => 'card',
                    'name' => 'Credit/Debit Card',
                    'description' => 'Pay using Visa, Mastercard, or American Express',
                    'icon' => 'card_icon.png',
                    'enabled' => true,
                    'fees' => 3.5,
                    'min_amount' => 1,
                    'max_amount' => 500000,
                ],
                [
                    'id' => 'cash',
                    'name' => 'Cash Payment',
                    'description' => 'Pay at school office',
                    'icon' => 'cash_icon.png',
                    'enabled' => true,
                    'fees' => 0,
                    'min_amount' => 1,
                    'max_amount' => 1000000,
                ],
            ];

            return $this->successResponse($paymentMethods, 'Payment methods retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve payment methods: ' . $e->getMessage());
        }
    }

    /**
     * Initialize payment
     */
    public function initializePayment(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1',
                'payment_method' => 'required|string|in:mpesa,bank_transfer,card,cash',
                'fee_id' => 'nullable|integer|exists:fees,id',
                'student_id' => 'nullable|integer',
                'description' => 'nullable|string|max:255',
            ]);

            $user = $request->user();
            $amount = $request->amount;
            $paymentMethod = $request->payment_method;
            $feeId = $request->fee_id;
            $studentId = $request->student_id ?? $user->id;
            $description = $request->description ?? 'School Fee Payment';

            // Generate unique transaction reference
            $transactionRef = 'TXN' . strtoupper(Str::random(8)) . time();

            // Calculate fees
            $fees = $this->calculatePaymentFees($amount, $paymentMethod);
            $totalAmount = $amount + $fees;

            // Create payment record
            $payment = DB::table('payments')->insertGetId([
                'transaction_ref' => $transactionRef,
                'user_id' => $user->id,
                'student_id' => $studentId,
                'fee_id' => $feeId,
                'amount' => $amount,
                'fees' => $fees,
                'total_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'status' => 'pending',
                'description' => $description,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Initialize payment based on method
            $paymentData = $this->initializePaymentMethod($paymentMethod, $transactionRef, $totalAmount, $user);

            return $this->successResponse([
                'payment_id' => $payment,
                'transaction_ref' => $transactionRef,
                'amount' => $amount,
                'fees' => $fees,
                'total_amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'payment_data' => $paymentData,
                'expires_at' => now()->addMinutes(15)->toISOString(),
            ], 'Payment initialized successfully');

        } catch (\Exception $e) {
            Log::error('Payment initialization failed: ' . $e->getMessage());
            return $this->errorResponse('Failed to initialize payment: ' . $e->getMessage());
        }
    }

    /**
     * Process payment callback
     */
    public function processCallback(Request $request)
    {
        try {
            $request->validate([
                'transaction_ref' => 'required|string',
                'status' => 'required|string|in:success,failed,cancelled',
                'payment_reference' => 'nullable|string',
                'amount' => 'nullable|numeric',
            ]);

            $transactionRef = $request->transaction_ref;
            $status = $request->status;
            $paymentReference = $request->payment_reference;
            $amount = $request->amount;

            // Find payment record
            $payment = DB::table('payments')
                ->where('transaction_ref', $transactionRef)
                ->first();

            if (!$payment) {
                return $this->errorResponse('Payment not found', 404);
            }

            // Update payment status
            $updateData = [
                'status' => $status === 'success' ? 'completed' : 'failed',
                'payment_reference' => $paymentReference,
                'updated_at' => now(),
            ];

            if ($status === 'success') {
                $updateData['paid_at'] = now();
                $updateData['amount_received'] = $amount ?? $payment->total_amount;
            }

            DB::table('payments')
                ->where('id', $payment->id)
                ->update($updateData);

            // If payment successful, update fee status
            if ($status === 'success' && $payment->fee_id) {
                $this->updateFeeStatus($payment->fee_id, $payment->student_id, $payment->amount);
            }

            // Send notification
            $this->sendPaymentNotification($payment->user_id, $status, $payment->amount);

            return $this->successResponse([
                'transaction_ref' => $transactionRef,
                'status' => $status,
                'message' => $status === 'success' ? 'Payment completed successfully' : 'Payment failed',
            ], 'Payment status updated successfully');

        } catch (\Exception $e) {
            Log::error('Payment callback processing failed: ' . $e->getMessage());
            return $this->errorResponse('Failed to process payment callback: ' . $e->getMessage());
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(Request $request)
    {
        try {
            $request->validate([
                'transaction_ref' => 'required|string',
            ]);

            $payment = DB::table('payments')
                ->where('transaction_ref', $request->transaction_ref)
                ->first();

            if (!$payment) {
                return $this->errorResponse('Payment not found', 404);
            }

            return $this->successResponse([
                'transaction_ref' => $payment->transaction_ref,
                'status' => $payment->status,
                'amount' => $payment->amount,
                'total_amount' => $payment->total_amount,
                'payment_method' => $payment->payment_method,
                'created_at' => $payment->created_at,
                'paid_at' => $payment->paid_at,
            ], 'Payment status retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve payment status: ' . $e->getMessage());
        }
    }

    /**
     * Get payment history
     */
    public function getPaymentHistory(Request $request)
    {
        try {
            $user = $request->user();
            $perPage = $request->get('per_page', 20);
            $status = $request->get('status');
            $paymentMethod = $request->get('payment_method');

            $query = DB::table('payments')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc');

            if ($status) {
                $query->where('status', $status);
            }

            if ($paymentMethod) {
                $query->where('payment_method', $paymentMethod);
            }

            $payments = $query->paginate($perPage);

            $formattedPayments = $payments->getCollection()->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'transaction_ref' => $payment->transaction_ref,
                    'amount' => $payment->amount,
                    'fees' => $payment->fees,
                    'total_amount' => $payment->total_amount,
                    'payment_method' => $payment->payment_method,
                    'status' => $payment->status,
                    'description' => $payment->description,
                    'created_at' => $payment->created_at,
                    'paid_at' => $payment->paid_at,
                ];
            });

            return $this->successResponse([
                'payments' => $formattedPayments,
                'pagination' => [
                    'current_page' => $payments->currentPage(),
                    'last_page' => $payments->lastPage(),
                    'per_page' => $payments->perPage(),
                    'total' => $payments->total(),
                ],
            ], 'Payment history retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve payment history: ' . $e->getMessage());
        }
    }

    /**
     * Cancel payment
     */
    public function cancelPayment(Request $request)
    {
        try {
            $request->validate([
                'transaction_ref' => 'required|string',
            ]);

            $payment = DB::table('payments')
                ->where('transaction_ref', $request->transaction_ref)
                ->where('status', 'pending')
                ->first();

            if (!$payment) {
                return $this->errorResponse('Payment not found or cannot be cancelled', 404);
            }

            DB::table('payments')
                ->where('id', $payment->id)
                ->update([
                    'status' => 'cancelled',
                    'updated_at' => now(),
                ]);

            return $this->successResponse([
                'transaction_ref' => $payment->transaction_ref,
                'status' => 'cancelled',
                'message' => 'Payment cancelled successfully',
            ], 'Payment cancelled successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to cancel payment: ' . $e->getMessage());
        }
    }

    /**
     * Calculate payment fees
     */
    private function calculatePaymentFees($amount, $paymentMethod)
    {
        $fees = 0;

        switch ($paymentMethod) {
            case 'mpesa':
                // M-Pesa fees (example rates)
                if ($amount <= 100) {
                    $fees = 0;
                } elseif ($amount <= 500) {
                    $fees = 5;
                } elseif ($amount <= 1000) {
                    $fees = 15;
                } elseif ($amount <= 1500) {
                    $fees = 25;
                } elseif ($amount <= 2500) {
                    $fees = 35;
                } elseif ($amount <= 3500) {
                    $fees = 45;
                } elseif ($amount <= 5000) {
                    $fees = 55;
                } elseif ($amount <= 7500) {
                    $fees = 75;
                } elseif ($amount <= 10000) {
                    $fees = 85;
                } elseif ($amount <= 15000) {
                    $fees = 95;
                } elseif ($amount <= 20000) {
                    $fees = 100;
                } else {
                    $fees = 100; // Max fee
                }
                break;

            case 'card':
                // Card processing fees (percentage)
                $fees = round($amount * 0.035, 2); // 3.5%
                break;

            case 'bank_transfer':
            case 'cash':
                // No fees for bank transfer or cash
                $fees = 0;
                break;
        }

        return $fees;
    }

    /**
     * Initialize payment method
     */
    private function initializePaymentMethod($paymentMethod, $transactionRef, $amount, $user)
    {
        switch ($paymentMethod) {
            case 'mpesa':
                return $this->initializeMpesaPayment($transactionRef, $amount, $user);
            case 'card':
                return $this->initializeCardPayment($transactionRef, $amount, $user);
            case 'bank_transfer':
                return $this->initializeBankTransfer($transactionRef, $amount, $user);
            case 'cash':
                return $this->initializeCashPayment($transactionRef, $amount, $user);
            default:
                return [];
        }
    }

    /**
     * Initialize M-Pesa payment
     */
    private function initializeMpesaPayment($transactionRef, $amount, $user)
    {
        // This would integrate with actual M-Pesa API
        return [
            'type' => 'mpesa',
            'instructions' => 'You will receive an M-Pesa prompt on your phone. Please enter your PIN to complete the payment.',
            'phone_number' => $user->phone ?? 'N/A',
            'amount' => $amount,
            'transaction_ref' => $transactionRef,
        ];
    }

    /**
     * Initialize card payment
     */
    private function initializeCardPayment($transactionRef, $amount, $user)
    {
        // This would integrate with actual card payment gateway
        return [
            'type' => 'card',
            'checkout_url' => url("/payment/checkout/{$transactionRef}"),
            'amount' => $amount,
            'transaction_ref' => $transactionRef,
        ];
    }

    /**
     * Initialize bank transfer
     */
    private function initializeBankTransfer($transactionRef, $amount, $user)
    {
        return [
            'type' => 'bank_transfer',
            'bank_details' => [
                'bank_name' => 'Equity Bank',
                'account_name' => 'Smart School System',
                'account_number' => '1234567890',
                'branch_code' => '123',
            ],
            'amount' => $amount,
            'transaction_ref' => $transactionRef,
            'instructions' => 'Please include the transaction reference in your transfer description.',
        ];
    }

    /**
     * Initialize cash payment
     */
    private function initializeCashPayment($transactionRef, $amount, $user)
    {
        return [
            'type' => 'cash',
            'amount' => $amount,
            'transaction_ref' => $transactionRef,
            'instructions' => 'Please visit the school office to complete your payment. Bring this transaction reference.',
            'office_hours' => 'Monday - Friday: 8:00 AM - 5:00 PM',
        ];
    }

    /**
     * Update fee status
     */
    private function updateFeeStatus($feeId, $studentId, $amount)
    {
        try {
            // Update fee payment status
            DB::table('fee_payments')->insert([
                'fee_id' => $feeId,
                'student_id' => $studentId,
                'amount' => $amount,
                'payment_date' => now(),
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update fee status if fully paid
            $totalPaid = DB::table('fee_payments')
                ->where('fee_id', $feeId)
                ->where('student_id', $studentId)
                ->sum('amount');

            $feeAmount = DB::table('fees')
                ->where('id', $feeId)
                ->value('amount');

            if ($totalPaid >= $feeAmount) {
                DB::table('fees')
                    ->where('id', $feeId)
                    ->update(['status' => 'paid']);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update fee status: ' . $e->getMessage());
        }
    }

    /**
     * Send payment notification
     */
    private function sendPaymentNotification($userId, $status, $amount)
    {
        try {
            // This would integrate with notification system
            $message = $status === 'success' 
                ? "Payment of KSh {$amount} completed successfully."
                : "Payment of KSh {$amount} failed. Please try again.";

            // Send notification logic here
            Log::info("Payment notification sent to user {$userId}: {$message}");
        } catch (\Exception $e) {
            Log::error('Failed to send payment notification: ' . $e->getMessage());
        }
    }
}
