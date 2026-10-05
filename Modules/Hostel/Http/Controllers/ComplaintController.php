<?php

namespace Modules\Hostel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Hostel\Models\Complaint;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['student', 'room']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Search by description
        if ($request->has('search') && $request->search) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $complaints = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('hostel::complaints.index', compact('complaints'));
    }

    public function create()
    {
        $students = \Modules\Hostel\Models\Student::all();
        $rooms = \Modules\Hostel\Models\Room::all();
        return view('hostel::complaints.create', compact('students', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:hostel_students,id',
            'room_id' => 'nullable|exists:rooms,id',
            'type' => 'required|in:noise,cleanliness,maintenance,security,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high'
        ]);

        try {
            $data = $request->all();
            $data['status'] = 'pending';
            $data['reported_by'] = auth()->id();

            Complaint::create($data);

            return redirect()->route('hostel.complaints.index')
                           ->with('success', 'Complaint submitted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to submit complaint: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $complaint = Complaint::with(['student', 'room', 'reportedBy'])->findOrFail($id);
        return view('hostel::complaints.show', compact('complaint'));
    }

    public function edit($id)
    {
        $complaint = Complaint::findOrFail($id);
        $students = \Modules\Hostel\Models\Student::all();
        $rooms = \Modules\Hostel\Models\Room::all();
        return view('hostel::complaints.edit', compact('complaint', 'students', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:hostel_students,id',
            'room_id' => 'nullable|exists:rooms,id',
            'type' => 'required|in:noise,cleanliness,maintenance,security,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,investigating,resolved,closed',
            'resolution' => 'nullable|string'
        ]);

        try {
            $complaint = Complaint::findOrFail($id);
            $complaint->update($request->all());

            return redirect()->route('hostel.complaints.index')
                           ->with('success', 'Complaint updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update complaint: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $complaint = Complaint::findOrFail($id);
            $complaint->delete();

            return redirect()->route('hostel.complaints.index')
                           ->with('success', 'Complaint deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete complaint: ' . $e->getMessage()]);
        }
    }
}