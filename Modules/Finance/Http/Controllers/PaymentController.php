<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('payments')) {
                $payments = Payment::all();
            } else {
                $payments = collect();
            }
        } catch (\Exception $e) {
            $payments = collect();
        }
        
        return view('finance::payments.index', compact('payments'));
    }

    public function create()
    {
        return view('finance::payments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'method' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
        ]);

        Payment::create($request->validated());
        return redirect()->route('finance.payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function show($id)
    {
        return view('finance::payments.show');
    }

    public function edit($id)
    {
        return view('finance::payments.edit');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'method' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'reference' => 'nullable|string|max:255',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update($request->validated());
        return redirect()->route('finance.payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->route('finance.payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function receipt($id)
    {
        return view('finance::payments.receipt');
    }

    public function report()
    {
        return view('finance::payments.report');
    }
}
