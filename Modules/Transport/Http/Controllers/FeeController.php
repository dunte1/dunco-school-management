<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\Fee;

class FeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Fee::with(['student', 'route']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by month
        if ($request->has('month') && $request->month) {
            $query->whereMonth('due_date', $request->month);
        }

        // Filter by year
        if ($request->has('year') && $request->year) {
            $query->whereYear('due_date', $request->year);
        }

        // Search by student name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $fees = $query->orderBy('due_date', 'desc')->paginate(15);
        $students = \Modules\Transport\Models\Student::all();

        return view('transport::fees.index', compact('fees', 'students'));
    }

    public function create()
    {
        $students = \Modules\Transport\Models\Student::all();
        $routes = \Modules\Transport\Models\Route::all();
        return view('transport::fees.create', compact('students', 'routes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:transport_students,id',
            'route_id' => 'required|exists:transport_routes,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after:today',
            'fee_type' => 'required|in:monthly,quarterly,annual,special',
            'description' => 'nullable|string',
            'late_fee' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0'
        ]);

        try {
            $data = $request->all();
            $data['status'] = 'pending';
            $data['created_by'] = auth()->id();

            Fee::create($data);

            return redirect()->route('transport.fees.index')
                           ->with('success', 'Fee created successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create fee: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $fee = Fee::with(['student', 'route', 'payments'])->findOrFail($id);
        return view('transport::fees.show', compact('fee'));
    }

    public function edit($id)
    {
        $fee = Fee::findOrFail($id);
        $students = \Modules\Transport\Models\Student::all();
        $routes = \Modules\Transport\Models\Route::all();
        return view('transport::fees.edit', compact('fee', 'students', 'routes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:transport_students,id',
            'route_id' => 'required|exists:transport_routes,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'fee_type' => 'required|in:monthly,quarterly,annual,special',
            'status' => 'required|in:pending,paid,overdue,waived',
            'description' => 'nullable|string',
            'late_fee' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string'
        ]);

        try {
            $fee = Fee::findOrFail($id);
            $fee->update($request->all());

            return redirect()->route('transport.fees.index')
                           ->with('success', 'Fee updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update fee: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $fee = Fee::findOrFail($id);
            $fee->delete();

            return redirect()->route('transport.fees.index')
                           ->with('success', 'Fee deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete fee: ' . $e->getMessage()]);
        }
    }
}
