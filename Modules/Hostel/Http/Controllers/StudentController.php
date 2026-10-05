<?php

namespace Modules\Hostel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Hostel\Models\Student;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['roomAllocation', 'hostel']);

        // Filter by hostel
        if ($request->has('hostel_id') && $request->hostel_id) {
            $query->where('hostel_id', $request->hostel_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search by student name or ID
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('name', 'asc')->paginate(15);
        $hostels = \Modules\Hostel\Models\Hostel::all();

        return view('hostel::students.index', compact('students', 'hostels'));
    }

    public function create()
    {
        $hostels = \Modules\Hostel\Models\Hostel::all();
        $rooms = \Modules\Hostel\Models\Room::all();
        return view('hostel::students.create', compact('hostels', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'student_id' => 'required|string|max:50|unique:hostel_students,student_id',
            'email' => 'required|email|unique:hostel_students,email',
            'phone' => 'required|string|max:20',
            'hostel_id' => 'required|exists:hostels,id',
            'room_id' => 'nullable|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'nullable|date|after:check_in_date',
            'status' => 'required|in:active,inactive,graduated,transferred',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20'
        ]);

        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();

            Student::create($data);

            return redirect()->route('hostel.students.index')
                           ->with('success', 'Student registered successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to register student: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $student = Student::with(['roomAllocation', 'hostel', 'fees', 'issues'])->findOrFail($id);
        return view('hostel::students.show', compact('student'));
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $hostels = \Modules\Hostel\Models\Hostel::all();
        $rooms = \Modules\Hostel\Models\Room::all();
        return view('hostel::students.edit', compact('student', 'hostels', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'student_id' => 'required|string|max:50|unique:hostel_students,student_id,' . $id,
            'email' => 'required|email|unique:hostel_students,email,' . $id,
            'phone' => 'required|string|max:20',
            'hostel_id' => 'required|exists:hostels,id',
            'room_id' => 'nullable|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'nullable|date|after:check_in_date',
            'status' => 'required|in:active,inactive,graduated,transferred',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20'
        ]);

        try {
            $student = Student::findOrFail($id);
            $student->update($request->all());

            return redirect()->route('hostel.students.index')
                           ->with('success', 'Student updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update student: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();

            return redirect()->route('hostel.students.index')
                           ->with('success', 'Student removed successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to remove student: ' . $e->getMessage()]);
        }
    }
}
