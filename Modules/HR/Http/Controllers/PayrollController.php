<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Staff;
use Modules\HR\Models\Payroll;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::with('staff');
        if ($request->filled('payroll_period')) {
            $query->where('payroll_period', $request->payroll_period);
        }
        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $payrolls = $query->orderBy('payroll_period', 'desc')->paginate(30);
        $staff = Staff::all();
        return view('hr::payroll.index', compact('payrolls', 'staff'));
    }

    public function create()
    {
        $staff = Staff::all();
        return view('hr::payroll.create', compact('staff'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'basic_salary' => 'required|numeric',
            'allowances' => 'nullable|numeric',
            'bonuses' => 'nullable|numeric',
            'deductions' => 'nullable|numeric',
            'payroll_period' => 'required',
        ]);
        $data['allowances'] = $data['allowances'] ?? 0;
        $data['bonuses'] = $data['bonuses'] ?? 0;
        $data['deductions'] = $data['deductions'] ?? 0;
        $data['net_salary'] = $data['basic_salary'] + $data['allowances'] + $data['bonuses'] - $data['deductions'];
        $data['status'] = 'pending';
        Payroll::create($data);
        return redirect()->route('hr.payroll.index')->with('success', 'Payroll entry created.');
    }

    public function markPaid($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->status = 'paid';
        $payroll->save();
        return redirect()->route('hr.payroll.index')->with('success', 'Payroll marked as paid.');
    }
} 