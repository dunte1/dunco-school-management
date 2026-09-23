<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Examination\Models\Exam;
use Modules\Examination\Models\ExamPayment;

class ExamPaymentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->academicStudent;

        if (!$student) {
            return redirect()->route('examination.student.exams')->with('error', 'No student profile found.');
        }

        $payments = ExamPayment::where('student_id', $student->id)
            ->with('exam')
            ->latest()
            ->paginate(15);

        return view('examination::payments.index', compact('payments', 'student'));
    }

    public function selectMethod(Exam $exam)
    {
        $user = Auth::user();
        $student = $user->academicStudent;

        if (!$student) {
            return redirect()->back()->with('error', 'No student profile found.');
        }

        if (!$exam->fee_required || !$exam->fee_amount) {
            return redirect()->route('examination.exams.show', $exam)->with('info', 'This exam does not require a fee.');
        }

        $existingPayment = ExamPayment::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->first();

        if ($existingPayment) {
            return redirect()->route('examination.exams.show', $exam)->with('info', 'You have already paid for this exam.');
        }

        return view('examination::payments.select-method', compact('exam', 'student'));
    }

    public function processPayment(Request $request, Exam $exam)
    {
        $user = Auth::user();
        $student = $user->academicStudent;

        if (!$student) {
            return redirect()->back()->with('error', 'No student profile found.');
        }

        $validated = $request->validate([
            'method' => 'required|in:cash,bank_transfer,mpesa,card',
        ]);

        $existingPayment = ExamPayment::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->first();

        if ($existingPayment) {
            return redirect()->route('examination.exams.show', $exam)->with('info', 'You have already paid for this exam.');
        }

        $payment = ExamPayment::create([
            'exam_id' => $exam->id,
            'student_id' => $student->id,
            'school_id' => Auth::user()->school_id,
            'amount' => $exam->fee_amount,
            'currency' => $exam->currency ?? 'KES',
            'method' => $validated['method'],
            'status' => 'pending',
            'reference' => 'EXAM-' . strtoupper(Str::random(8)),
        ]);

        switch ($validated['method']) {
            case 'mpesa':
                return view('examination::payments.mpesa', compact('exam', 'student', 'payment'));
            case 'bank_transfer':
                return view('examination::payments.bank-transfer', compact('exam', 'student', 'payment'));
            case 'card':
                return view('examination::payments.card', compact('exam', 'student', 'payment'));
            case 'cash':
                return view('examination::payments.cash', compact('exam', 'student', 'payment'));
            default:
                return redirect()->back()->with('error', 'Invalid payment method.');
        }
    }

    public function confirmMpesa(Request $request, ExamPayment $payment)
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:15',
            'transaction_code' => 'required|string|min:5|max:20',
        ]);

        $payment->update([
            'phone' => $request->phone,
            'transaction_code' => $request->transaction_code,
            'notes' => 'M-Pesa payment submitted for verification',
        ]);

        return view('examination::payments.pending', compact('payment'));
    }

    public function confirmBankTransfer(Request $request, ExamPayment $payment)
    {
        $request->validate([
            'reference' => 'required|string|min:3|max:100',
            'notes' => 'nullable|string',
        ]);

        $payment->update([
            'reference' => $request->reference,
            'notes' => $request->notes ?? 'Bank transfer submitted for verification',
        ]);

        return view('examination::payments.pending', compact('payment'));
    }

    public function confirmCard(Request $request, ExamPayment $payment)
    {
        $request->validate([
            'card_last_four' => 'required|string|size:4',
            'cardholder_name' => 'required|string|max:255',
        ]);

        $payment->update([
            'reference' => 'CARD-' . $request->card_last_four . '-' . strtoupper(Str::random(4)),
            'notes' => 'Card payment submitted for processing',
        ]);

        return view('examination::payments.pending', compact('payment'));
    }

    public function confirmCash(Request $request, ExamPayment $payment)
    {
        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
            'notes' => 'Cash payment confirmed',
        ]);

        return view('examination::payments.receipt', compact('payment'));
    }

    public function show(ExamPayment $payment)
    {
        $user = Auth::user();
        $student = $user->academicStudent;

        if ($student && $payment->student_id !== $student->id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $payment->load(['exam', 'student']);

        return view('examination::payments.show', compact('payment'));
    }

    public function receipt(ExamPayment $payment)
    {
        $user = Auth::user();
        $student = $user->academicStudent;

        if ($student && $payment->student_id !== $student->id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $payment->load(['exam', 'student']);

        return view('examination::payments.receipt', compact('payment'));
    }

    public function verify(Request $request, ExamPayment $payment)
    {
        $request->validate([
            'status' => 'required|in:completed,failed',
            'notes' => 'nullable|string',
        ]);

        $payment->update([
            'status' => $request->status,
            'paid_at' => $request->status === 'completed' ? now() : null,
            'notes' => $request->notes ?? $payment->notes,
        ]);

        return redirect()->back()->with('success', 'Payment status updated.');
    }
}
