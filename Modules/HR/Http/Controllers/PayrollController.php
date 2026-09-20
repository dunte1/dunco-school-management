<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
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

        $summary = [
            'total_records' => (float) Payroll::count(),
            'total_paid' => (float) Payroll::where('status', 'paid')->sum('net_salary'),
            'total_pending' => (float) Payroll::where('status', 'pending')->sum('net_salary'),
            'total_gross' => (float) Payroll::sum('basic_salary'),
            'total_allowances' => (float) Payroll::sum('allowances'),
            'total_bonuses' => (float) Payroll::sum('bonuses'),
            'total_deductions' => (float) Payroll::sum('deductions'),
        ];

        return view('hr::payroll.index', compact('payrolls', 'staff', 'summary'));
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
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'payroll_period' => 'required|string|max:50',
        ]);

        $data['allowances'] = $data['allowances'] ?? 0;
        $data['bonuses'] = $data['bonuses'] ?? 0;
        $data['deductions'] = $data['deductions'] ?? 0;
        $data['net_salary'] = $data['basic_salary'] + $data['allowances'] + $data['bonuses'] - $data['deductions'];
        $data['status'] = 'pending';

        $existing = Payroll::where('staff_id', $data['staff_id'])
            ->where('payroll_period', $data['payroll_period'])
            ->exists();

        if ($existing) {
            return back()->withErrors([
                'payroll_period' => 'A payroll entry already exists for this staff member in the selected period.',
            ])->withInput();
        }

        Payroll::create($data);

        return redirect()->route('hr.payroll.index')->with('success', 'Payroll entry created.');
    }

    public function show($id)
    {
        $payroll = Payroll::with('staff')->findOrFail($id);

        return view('hr::payroll.show', compact('payroll'));
    }

    public function edit($id)
    {
        $payroll = Payroll::findOrFail($id);
        $staff = Staff::all();

        return view('hr::payroll.edit', compact('payroll', 'staff'));
    }

    public function update(Request $request, $id)
    {
        $payroll = Payroll::findOrFail($id);

        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'payroll_period' => 'required|string|max:50',
        ]);

        $data['allowances'] = $data['allowances'] ?? 0;
        $data['bonuses'] = $data['bonuses'] ?? 0;
        $data['deductions'] = $data['deductions'] ?? 0;
        $data['net_salary'] = $data['basic_salary'] + $data['allowances'] + $data['bonuses'] - $data['deductions'];

        $duplicate = Payroll::where('staff_id', $data['staff_id'])
            ->where('payroll_period', $data['payroll_period'])
            ->where('id', '!=', $id)
            ->exists();

        if ($duplicate) {
            return back()->withErrors([
                'payroll_period' => 'A payroll entry already exists for this staff member in the selected period.',
            ])->withInput();
        }

        $payroll->update($data);

        return redirect()->route('hr.payroll.show', $payroll->id)->with('success', 'Payroll entry updated.');
    }

    public function destroy($id)
    {
        $payroll = Payroll::findOrFail($id);

        if ($payroll->status === 'paid') {
            return back()->with('error', 'Cannot delete a paid payroll entry. Revert to pending first.');
        }

        $payroll->delete();

        return redirect()->route('hr.payroll.index')->with('success', 'Payroll entry deleted.');
    }

    public function markPaid($id)
    {
        $payroll = Payroll::findOrFail($id);

        if ($payroll->status === 'paid') {
            return back()->with('error', 'Payroll entry is already marked as paid.');
        }

        $payroll->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $staffMember = $payroll->staff;
        if ($staffMember && $staffMember->email) {
            try {
                Mail::raw(
                    "Dear {$staffMember->first_name},\n\n" .
                    "Your salary for period {$payroll->payroll_period} has been processed.\n\n" .
                    "Basic Salary: KES " . number_format($payroll->basic_salary, 2) . "\n" .
                    "Allowances: KES " . number_format($payroll->allowances, 2) . "\n" .
                    "Bonuses: KES " . number_format($payroll->bonuses, 2) . "\n" .
                    "Deductions: KES " . number_format($payroll->deductions, 2) . "\n" .
                    "Net Salary: KES " . number_format($payroll->net_salary, 2) . "\n\n" .
                    "Payment date: " . now()->format('d M Y') . "\n\n" .
                    "Regards,\nHR Department",
                    function ($message) use ($staffMember) {
                        $message->to($staffMember->email)
                            ->subject('Salary Payment Notification');
                    }
                );
            } catch (\Throwable $e) {
            }
        }

        return redirect()->route('hr.payroll.index')->with('success', 'Payroll marked as paid. Payslip notification sent.');
    }
}
