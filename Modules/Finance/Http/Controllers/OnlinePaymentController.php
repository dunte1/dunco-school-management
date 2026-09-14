<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class OnlinePaymentController extends Controller
{
    public function index()
    {
        return view('finance::online_payments.index');
    }

    public function create()
    {
        return view('finance::online_payments.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.online-payments.index')
            ->with('info', 'Online payment gateway is not configured yet.');
    }

    public function show($id)
    {
        return view('finance::online_payments.show');
    }

    public function edit($id)
    {
        return view('finance::online_payments.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.online-payments.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.online-payments.index');
    }

    public function mpesa(Request $request)
    {
        $invoice = null;
        $invoiceId = $request->query('invoice', $request->query('invoice_id'));

        if ($invoiceId) {
            $invoice = \Modules\Finance\Models\Invoice::find($invoiceId);
        }

        if (! $invoice) {
            $invoice = (object) [
                'id' => '—',
                'student' => null,
                'total_amount' => 0,
                'status' => 'draft',
            ];
        }

        return view('finance::online_payments.mpesa', compact('invoice'));
    }

    public function mpesaCallback(Request $request)
    {
        Log::info('M-Pesa callback received', $request->all());

        if ($request->expectsJson()) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        return back()->with('info', 'M-Pesa payment submitted for verification.');
    }
}
