<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\Invoice;
use Modules\Finance\Models\InvoiceItem;
use Modules\Finance\Models\Fee;
use Modules\Academic\Models\Student;

class BillingController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['student', 'items.fee'])->get();
        return view('finance::billing.index', compact('invoices'));
    }

    public function create()
    {
        $students = Student::all();
        $fees = Fee::all();
        return view('finance::billing.create', compact('students', 'fees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'fees' => 'required|array',
            'fees.*' => 'exists:fees,id',
            'due_date' => 'required|date',
        ]);
        $total = Fee::whereIn('id', $data['fees'])->sum('amount');
        $invoice = Invoice::create([
            'student_id' => $data['student_id'],
            'total_amount' => $total,
            'due_date' => $data['due_date'],
            'status' => 'unpaid',
        ]);
        foreach ($data['fees'] as $feeId) {
            $fee = Fee::find($feeId);
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'fee_id' => $feeId,
                'amount' => $fee->amount,
            ]);
        }
        return redirect()->route('finance.billing.index')->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['student', 'items.fee']);
        return view('finance::billing.show', compact('invoice'));
    }
} 