<?php

namespace Modules\Finance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Finance\app\Models\MpesaTransaction;
use Modules\Finance\Services\MpesaService;

class MpesaCallbackController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Handle STK Push Callback
     */
    public function stkCallback(Request $request)
    {
        try {
            Log::info('M-Pesa STK Callback Received', $request->all());
            
            $data = $request->all();
            $checkoutRequestId = $data['CheckoutRequestID'] ?? null;
            $resultCode = $data['ResultCode'] ?? null;
            $resultDesc = $data['ResultDesc'] ?? null;
            $amount = $data['Amount'] ?? null;
            $mpesaReceiptNumber = $data['MpesaReceiptNumber'] ?? null;
            $transactionDate = $data['TransactionDate'] ?? null;
            $phoneNumber = $data['PhoneNumber'] ?? null;
            
            // Find the transaction by checkout request ID
            $transaction = MpesaTransaction::where('checkout_request_id', $checkoutRequestId)->first();
            
            if (!$transaction) {
                Log::error('M-Pesa STK Callback: Transaction not found', ['checkout_request_id' => $checkoutRequestId]);
                return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
            }
            
            // Update transaction status
            if ($resultCode == 0) {
                // Success
                $transaction->update([
                    'status' => 'completed',
                    'mpesa_receipt_number' => $mpesaReceiptNumber,
                    'transaction_date' => $transactionDate,
                    'phone_number' => $phoneNumber,
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc,
                    'completed_at' => now(),
                ]);
                
                Log::info('M-Pesa STK Callback: Payment completed', [
                    'transaction_id' => $transaction->id,
                    'receipt_number' => $mpesaReceiptNumber
                ]);
                
                // Trigger payment success event
                event(new \Modules\Finance\Events\PaymentCompleted($transaction));
                
            } else {
                // Failed
                $transaction->update([
                    'status' => 'failed',
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc,
                    'failed_at' => now(),
                ]);
                
                Log::error('M-Pesa STK Callback: Payment failed', [
                    'transaction_id' => $transaction->id,
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc
                ]);
                
                // Trigger payment failed event
                event(new \Modules\Finance\Events\PaymentFailed($transaction));
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('M-Pesa STK Callback Exception', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Handle C2B Confirmation
     */
    public function c2bConfirmation(Request $request)
    {
        try {
            Log::info('M-Pesa C2B Confirmation Received', $request->all());
            
            $data = $request->all();
            $transactionType = $data['TransactionType'] ?? null;
            $transID = $data['TransID'] ?? null;
            $transTime = $data['TransTime'] ?? null;
            $transAmount = $data['TransAmount'] ?? null;
            $businessShortCode = $data['BusinessShortCode'] ?? null;
            $billReferenceNumber = $data['BillReferenceNumber'] ?? null;
            $invoiceNumber = $data['InvoiceNumber'] ?? null;
            $orgAccountBalance = $data['OrgAccountBalance'] ?? null;
            $msisdn = $data['MSISDN'] ?? null;
            
            // Find transaction by bill reference number
            $transaction = MpesaTransaction::where('account_reference', $billReferenceNumber)->first();
            
            if (!$transaction) {
                Log::error('M-Pesa C2B Confirmation: Transaction not found', ['bill_reference' => $billReferenceNumber]);
                return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
            }
            
            // Update transaction
            $transaction->update([
                'status' => 'completed',
                'mpesa_receipt_number' => $transID,
                'transaction_date' => $transTime,
                'phone_number' => $msisdn,
                'amount' => $transAmount,
                'completed_at' => now(),
            ]);
            
            Log::info('M-Pesa C2B Confirmation: Payment completed', [
                'transaction_id' => $transaction->id,
                'trans_id' => $transID
            ]);
            
            // Trigger payment success event
            event(new \Modules\Finance\Events\PaymentCompleted($transaction));
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('M-Pesa C2B Confirmation Exception', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Handle C2B Validation
     */
    public function c2bValidation(Request $request)
    {
        try {
            Log::info('M-Pesa C2B Validation Received', $request->all());
            
            $data = $request->all();
            $billReferenceNumber = $data['BillReferenceNumber'] ?? null;
            $amount = $data['TransAmount'] ?? null;
            $msisdn = $data['MSISDN'] ?? null;
            
            // Validate the transaction
            $transaction = MpesaTransaction::where('account_reference', $billReferenceNumber)->first();
            
            if (!$transaction) {
                Log::error('M-Pesa C2B Validation: Transaction not found', ['bill_reference' => $billReferenceNumber]);
                return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Transaction not found']);
            }
            
            // Check if amount matches
            if ($transaction->amount != $amount) {
                Log::error('M-Pesa C2B Validation: Amount mismatch', [
                    'expected' => $transaction->amount,
                    'received' => $amount
                ]);
                return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Amount mismatch']);
            }
            
            // Check if phone number matches
            if ($transaction->phone_number && $transaction->phone_number != $msisdn) {
                Log::error('M-Pesa C2B Validation: Phone number mismatch', [
                    'expected' => $transaction->phone_number,
                    'received' => $msisdn
                ]);
                return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Phone number mismatch']);
            }
            
            Log::info('M-Pesa C2B Validation: Transaction validated', [
                'transaction_id' => $transaction->id,
                'bill_reference' => $billReferenceNumber
            ]);
            
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Transaction validated successfully']);
            
        } catch (\Exception $e) {
            Log::error('M-Pesa C2B Validation Exception', ['error' => $e->getMessage()]);
            return response()->json(['ResultCode' => 1, 'ResultDesc' => 'Validation failed']);
        }
    }
    
    /**
     * Handle B2C Result
     */
    public function b2cResult(Request $request)
    {
        try {
            Log::info('M-Pesa B2C Result Received', $request->all());
            
            $data = $request->all();
            $resultCode = $data['Result']['ResultCode'] ?? null;
            $resultDesc = $data['Result']['ResultDesc'] ?? null;
            $transactionId = $data['Result']['TransactionID'] ?? null;
            $conversationId = $data['Result']['ConversationID'] ?? null;
            $originatorConversationId = $data['Result']['OriginatorConversationID'] ?? null;
            
            // Find transaction by conversation ID
            $transaction = MpesaTransaction::where('conversation_id', $conversationId)->first();
            
            if (!$transaction) {
                Log::error('M-Pesa B2C Result: Transaction not found', ['conversation_id' => $conversationId]);
                return response()->json(['status' => 'error', 'message' => 'Transaction not found'], 404);
            }
            
            // Update transaction status
            if ($resultCode == 0) {
                $transaction->update([
                    'status' => 'completed',
                    'mpesa_receipt_number' => $transactionId,
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc,
                    'completed_at' => now(),
                ]);
                
                Log::info('M-Pesa B2C Result: Payment completed', [
                    'transaction_id' => $transaction->id,
                    'mpesa_transaction_id' => $transactionId
                ]);
                
            } else {
                $transaction->update([
                    'status' => 'failed',
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc,
                    'failed_at' => now(),
                ]);
                
                Log::error('M-Pesa B2C Result: Payment failed', [
                    'transaction_id' => $transaction->id,
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc
                ]);
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('M-Pesa B2C Result Exception', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Handle Transaction Reversal Result
     */
    public function reversalResult(Request $request)
    {
        try {
            Log::info('M-Pesa Reversal Result Received', $request->all());
            
            $data = $request->all();
            $resultCode = $data['Result']['ResultCode'] ?? null;
            $resultDesc = $data['Result']['ResultDesc'] ?? null;
            $transactionId = $data['Result']['TransactionID'] ?? null;
            $conversationId = $data['Result']['ConversationID'] ?? null;
            
            // Find the original transaction
            $originalTransaction = MpesaTransaction::where('mpesa_receipt_number', $transactionId)->first();
            
            if (!$originalTransaction) {
                Log::error('M-Pesa Reversal Result: Original transaction not found', ['transaction_id' => $transactionId]);
                return response()->json(['status' => 'error', 'message' => 'Original transaction not found'], 404);
            }
            
            // Create reversal transaction record
            $reversalTransaction = MpesaTransaction::create([
                'transaction_type' => 'reversal',
                'amount' => $originalTransaction->amount,
                'phone_number' => $originalTransaction->phone_number,
                'account_reference' => 'REV_' . $originalTransaction->account_reference,
                'transaction_desc' => 'Reversal of transaction ' . $originalTransaction->mpesa_receipt_number,
                'status' => $resultCode == 0 ? 'completed' : 'failed',
                'mpesa_receipt_number' => $transactionId,
                'result_code' => $resultCode,
                'result_desc' => $resultDesc,
                'conversation_id' => $conversationId,
                'completed_at' => $resultCode == 0 ? now() : null,
                'failed_at' => $resultCode != 0 ? now() : null,
            ]);
            
            if ($resultCode == 0) {
                // Mark original transaction as reversed
                $originalTransaction->update(['status' => 'reversed']);
                
                Log::info('M-Pesa Reversal Result: Reversal completed', [
                    'original_transaction_id' => $originalTransaction->id,
                    'reversal_transaction_id' => $reversalTransaction->id
                ]);
            } else {
                Log::error('M-Pesa Reversal Result: Reversal failed', [
                    'original_transaction_id' => $originalTransaction->id,
                    'result_code' => $resultCode,
                    'result_desc' => $resultDesc
                ]);
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('M-Pesa Reversal Result Exception', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Handle Timeout URLs
     */
    public function timeout(Request $request)
    {
        Log::warning('M-Pesa Timeout Received', $request->all());
        return response()->json(['status' => 'timeout']);
    }
    
    /**
     * Test API Connection
     */
    public function testConnection()
    {
        try {
            $isConnected = $this->mpesaService->testConnection();
            $errors = $this->mpesaService->validateConfiguration();
            
            return response()->json([
                'connected' => $isConnected,
                'errors' => $errors,
                'message' => $isConnected ? 'Connection successful' : 'Connection failed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'connected' => false,
                'errors' => [$e->getMessage()],
                'message' => 'Connection test failed'
            ]);
        }
    }
} 