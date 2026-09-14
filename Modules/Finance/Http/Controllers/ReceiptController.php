<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\Payment;

class ReceiptController extends Controller
{
    public function index()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('payments')) {
                $receipts = Payment::all();
            } else {
                $receipts = collect();
            }
        } catch (\Exception $e) {
            $receipts = collect();
        }
        
        return view('finance::receipts.index', compact('receipts'));
    }

    public function create()
    {
        return view('finance::receipts.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.receipts.index');
    }

    public function show($id)
    {
        $receipt = Payment::findOrFail($id);

        return view('finance::receipts.show', compact('receipt'));
    }

    public function edit($id)
    {
        $receipt = Payment::findOrFail($id);

        return view('finance::receipts.edit', compact('receipt'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.receipts.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.receipts.index');
    }
}
