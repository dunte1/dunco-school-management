<?php

namespace Modules\API\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Academic\Models\Student;
use Modules\Finance\Models\Fee;
use Modules\Finance\Models\FeeCategory;
use Modules\Finance\Models\FeeType;
use Modules\Finance\Models\Invoice;
use Modules\Finance\Models\InvoiceItem;
use Modules\Finance\Models\Payment;
use Modules\Academic\Models\StudentFee;

class FinanceController extends Controller
{
    public function getFees(Request $request): JsonResponse
    {
        try {
            $query = Fee::with(['category', 'type']);

            if ($request->filled('category_id')) {
                $query->where('fee_category_id', $request->category_id);
            }

            if ($request->filled('type_id')) {
                $query->where('fee_type_id', $request->type_id);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%");
            }

            $fees = $query->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'message' => 'Fees retrieved successfully',
                'data' => $fees
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fees: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFee($id): JsonResponse
    {
        try {
            $fee = Fee::with(['category', 'type'])->find($id);

            if (!$fee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fee not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Fee retrieved successfully',
                'data' => $fee
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fee: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFeeCategories(): JsonResponse
    {
        try {
            $categories = FeeCategory::orderBy('name')->get();

            return response()->json([
                'success' => true,
                'message' => 'Fee categories retrieved successfully',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fee categories: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFeeStructures(Request $request): JsonResponse
    {
        try {
            $query = Fee::with(['category', 'type']);

            if ($request->filled('category_id')) {
                $query->where('fee_category_id', $request->category_id);
            }

            if ($request->filled('class_id')) {
                $query->where('academic_class_id', $request->class_id);
            }

            if ($request->filled('academic_session')) {
                $query->where('academic_session', $request->academic_session);
            }

            $structures = $query->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'message' => 'Fee structures retrieved successfully',
                'data' => $structures
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fee structures: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFeeStructure($id): JsonResponse
    {
        try {
            $structure = Fee::with(['category', 'type'])->find($id);

            if (!$structure) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fee structure not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Fee structure retrieved successfully',
                'data' => $structure
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve fee structure: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudentFees(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $student = Student::where('user_id', $userId)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student profile not found'
                ], 404);
            }

            $query = StudentFee::with(['fee', 'fee.category'])
                ->where('student_id', $student->id);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $fees = $query->orderByDesc('created_at')->paginate(25);

            $totalAmount = $fees->sum('amount');
            $paidAmount = $fees->where('status', 'paid')->sum('amount');
            $pendingAmount = $totalAmount - $paidAmount;

            return response()->json([
                'success' => true,
                'message' => 'Student fees retrieved successfully',
                'data' => [
                    'fees' => $fees,
                    'summary' => [
                        'total_amount' => $totalAmount,
                        'paid_amount' => $paidAmount,
                        'pending_amount' => $pendingAmount
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve student fees: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudentFee($id): JsonResponse
    {
        try {
            $studentFee = StudentFee::with(['fee', 'fee.category', 'student'])->find($id);

            if (!$studentFee) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student fee not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Student fee retrieved successfully',
                'data' => $studentFee
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve student fee: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPayments(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $student = Student::where('user_id', $userId)->first();

            $query = Payment::with(['invoice', 'invoice.student']);

            if ($student) {
                $query->whereHas('invoice', function ($q) use ($student) {
                    $q->where('student_id', $student->id);
                });
            }

            if ($request->filled('date_from')) {
                $query->where('payment_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('payment_date', '<=', $request->date_to);
            }

            if ($request->filled('method')) {
                $query->where('payment_method', $request->method);
            }

            $payments = $query->orderByDesc('payment_date')->paginate(25);

            return response()->json([
                'success' => true,
                'message' => 'Payments retrieved successfully',
                'data' => $payments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payments: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPayment($id): JsonResponse
    {
        try {
            $payment = Payment::with(['invoice', 'invoice.student'])->find($id);

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment retrieved successfully',
                'data' => $payment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createPayment(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'invoice_id' => 'required|exists:invoices,id',
                'amount' => 'required|numeric|min:0.01',
                'payment_method' => 'required|string|in:cash,bank_transfer,card,online,cheque',
                'payment_date' => 'required|date',
                'reference_number' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $payment = Payment::create([
                'invoice_id' => $request->invoice_id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_date' => $request->payment_date,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'status' => 'completed',
            ]);

            $invoice = Invoice::find($request->invoice_id);
            $totalPaid = Payment::where('invoice_id', $request->invoice_id)->sum('amount');
            $invoice->status = $totalPaid >= $invoice->total_amount ? 'paid' : 'partial';
            $invoice->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment created successfully',
                'data' => $payment
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePayment(Request $request, $id): JsonResponse
    {
        try {
            $payment = Payment::find($id);

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'amount' => 'sometimes|numeric|min:0.01',
                'payment_method' => 'sometimes|string|in:cash,bank_transfer,card,online,cheque',
                'payment_date' => 'sometimes|date',
                'reference_number' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'status' => 'sometimes|string|in:completed,pending,failed,refunded',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $payment->update($validator->validated());

            $totalPaid = Payment::where('invoice_id', $payment->invoice_id)->sum('amount');
            $invoice = Invoice::find($payment->invoice_id);
            $invoice->status = $totalPaid >= $invoice->total_amount ? 'paid' : 'partial';
            $invoice->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully',
                'data' => $payment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deletePayment($id): JsonResponse
    {
        try {
            $payment = Payment::find($id);

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            $invoiceId = $payment->invoice_id;
            $payment->delete();

            $totalPaid = Payment::where('invoice_id', $invoiceId)->sum('amount');
            $invoice = Invoice::find($invoiceId);
            if ($invoice) {
                $invoice->status = $totalPaid >= $invoice->total_amount ? 'paid' : ($totalPaid > 0 ? 'partial' : 'unpaid');
                $invoice->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getInvoice($id): JsonResponse
    {
        try {
            $invoice = Invoice::with(['items.fee', 'student', 'payments'])->find($id);

            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Invoice retrieved successfully',
                'data' => $invoice
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getInvoices(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $student = Student::where('user_id', $userId)->first();

            $query = Invoice::with(['items.fee', 'payments']);

            if ($student) {
                $query->where('student_id', $student->id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('date_from')) {
                $query->where('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('created_at', '<=', $request->date_to);
            }

            $invoices = $query->orderByDesc('created_at')->paginate(25);

            return response()->json([
                'success' => true,
                'message' => 'Invoices retrieved successfully',
                'data' => $invoices
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve invoices: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPaymentSummary(Request $request): JsonResponse
    {
        try {
            $userId = Auth::id();
            $student = Student::where('user_id', $userId)->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student profile not found'
                ], 404);
            }

            $totalInvoiced = Invoice::where('student_id', $student->id)->sum('total_amount');
            $totalPaid = Payment::whereHas('invoice', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })->sum('amount');
            $totalPending = $totalInvoiced - $totalPaid;

            return response()->json([
                'success' => true,
                'message' => 'Payment summary retrieved successfully',
                'data' => [
                    'total_invoiced' => $totalInvoiced,
                    'total_paid' => $totalPaid,
                    'total_pending' => $totalPending,
                    'currency' => 'NGN'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payment summary: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getReportsSummary(Request $request): JsonResponse
    {
        try {
            $query = Payment::with(['invoice']);

            if ($request->filled('date_from')) {
                $query->where('payment_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->where('payment_date', '<=', $request->date_to);
            }

            $totalCollected = $query->where('status', 'completed')->sum('amount');
            $totalPending = Invoice::where('status', '!=', 'paid')->sum('total_amount');
            $totalInvoiced = Invoice::sum('total_amount');

            $paymentCount = Payment::where('status', 'completed');

            if ($request->filled('date_from')) {
                $paymentCount->where('payment_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $paymentCount->where('payment_date', '<=', $request->date_to);
            }

            return response()->json([
                'success' => true,
                'message' => 'Finance summary retrieved successfully',
                'data' => [
                    'total_invoiced' => $totalInvoiced,
                    'total_collected' => $totalCollected,
                    'total_pending' => $totalPending,
                    'collection_rate' => $totalInvoiced > 0 ? round(($totalCollected / $totalInvoiced) * 100, 2) : 0,
                    'payment_count' => $paymentCount->count(),
                    'currency' => 'NGN'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve finance summary: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMonthlyReport(Request $request): JsonResponse
    {
        try {
            $year = $request->get('year', date('Y'));

            $monthlyData = [];
            for ($month = 1; $month <= 12; $month++) {
                $startDate = sprintf('%s-%02d-01', $year, $month);
                $endDate = sprintf('%s-%02d-t', $year, $month);

                $collected = Payment::where('status', 'completed')
                    ->where('payment_date', '>=', $startDate)
                    ->where('payment_date', '<', $endDate)
                    ->sum('amount');

                $invoiced = Invoice::where('created_at', '>=', $startDate)
                    ->where('created_at', '<', $endDate)
                    ->sum('total_amount');

                $monthlyData[] = [
                    'month' => $month,
                    'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                    'invoiced' => $invoiced,
                    'collected' => $collected,
                    'pending' => $invoiced - $collected,
                ];
            }

            return response()->json([
                'success' => true,
                'message' => 'Monthly report retrieved successfully',
                'data' => [
                    'year' => (int) $year,
                    'months' => $monthlyData,
                    'currency' => 'NGN'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve monthly report: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudentReport($studentId): JsonResponse
    {
        try {
            $student = Student::with(['class'])->find($studentId);

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            $invoices = Invoice::with(['items.fee'])
                ->where('student_id', $studentId)
                ->orderByDesc('created_at')
                ->get();

            $payments = Payment::whereHas('invoice', function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            })->orderByDesc('payment_date')->get();

            $totalInvoiced = $invoices->sum('total_amount');
            $totalPaid = $payments->where('status', 'completed')->sum('amount');

            return response()->json([
                'success' => true,
                'message' => 'Student finance report retrieved successfully',
                'data' => [
                    'student' => $student,
                    'invoices' => $invoices,
                    'payments' => $payments,
                    'summary' => [
                        'total_invoiced' => $totalInvoiced,
                        'total_paid' => $totalPaid,
                        'total_pending' => $totalInvoiced - $totalPaid,
                        'currency' => 'NGN'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve student report: ' . $e->getMessage()
            ], 500);
        }
    }
}
