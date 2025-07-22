<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Staff;
use Modules\HR\Models\Contract;
use Modules\HR\Models\Department;
use Carbon\Carbon;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with('staff');
        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        $contracts = $query->orderBy('start_date', 'desc')->paginate(30);
        $staff = Staff::all();
        return view('hr::contract.index', compact('contracts', 'staff'));
    }

    public function create()
    {
        $staff = Staff::all();
        $departments = Department::all();
        return view('hr::contract.create', compact('staff', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'duration_months' => 'nullable|integer',
            'on_probation' => 'boolean',
            'probation_end' => 'nullable|date',
            'renewal_reminder' => 'nullable|date',
            'promotion_from' => 'nullable',
            'promotion_to' => 'nullable',
            'promotion_date' => 'nullable|date',
            'old_salary' => 'nullable|numeric',
            'new_salary' => 'nullable|numeric',
            'transfer_from_school' => 'nullable|integer',
            'transfer_to_school' => 'nullable|integer',
            'transfer_from_department' => 'nullable|integer',
            'transfer_to_department' => 'nullable|integer',
        ]);
        Contract::create($data);
        return redirect()->route('hr.contract.index')->with('success', 'Contract created.');
    }
} 