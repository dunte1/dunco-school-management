<?php

namespace Modules\Transport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Transport\Models\Student;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['route', 'vehicle']);

        // Filter by route
        if ($request->has('route_id') && $request->route_id) {
            $query->where('route_id', $request->route_id);
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
                  ->orWhere('parent_phone', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('name', 'asc')->paginate(15);
        $routes = \Modules\Transport\Models\Route::all();

        return view('transport::students.index', compact('students', 'routes'));
    }

    public function create()
    {
        $routes = \Modules\Transport\Models\Route::all();
        $vehicles = \Modules\Transport\Models\Vehicle::all();
        return view('transport::students.create', compact('routes', 'vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'student_id' => 'required|string|max:50|unique:transport_students,student_id',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'nullable|email',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'route_id' => 'required|exists:transport_routes,id',
            'vehicle_id' => 'nullable|exists:transport_vehicles,id',
            'pickup_time' => 'required|date_format:H:i',
            'dropoff_time' => 'required|date_format:H:i',
            'monthly_fee' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,suspended',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20'
        ]);

        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();

            Student::create($data);

            return redirect()->route('transport.students.index')
                           ->with('success', 'Student registered successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to register student: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $student = Student::with(['route', 'vehicle', 'payments'])->findOrFail($id);
        return view('transport::students.show', compact('student'));
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $routes = \Modules\Transport\Models\Route::all();
        $vehicles = \Modules\Transport\Models\Vehicle::all();
        return view('transport::students.edit', compact('student', 'routes', 'vehicles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'student_id' => 'required|string|max:50|unique:transport_students,student_id,' . $id,
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'nullable|email',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'route_id' => 'required|exists:transport_routes,id',
            'vehicle_id' => 'nullable|exists:transport_vehicles,id',
            'pickup_time' => 'required|date_format:H:i',
            'dropoff_time' => 'required|date_format:H:i',
            'monthly_fee' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,suspended',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20'
        ]);

        try {
            $student = Student::findOrFail($id);
            $student->update($request->all());

            return redirect()->route('transport.students.index')
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

            return redirect()->route('transport.students.index')
                           ->with('success', 'Student removed successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to remove student: ' . $e->getMessage()]);
        }
    }
}
