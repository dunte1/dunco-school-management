<?php

namespace Modules\Timetable\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Timetable\Models\TeacherAvailability;
use Modules\Timetable\Http\Requests\TeacherAvailabilityRequest;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\HR\Models\Staff;

class TeacherAvailabilityController extends Controller
{
    // Blade CRUD methods
    public function index()
    {
        $availabilities = TeacherAvailability::paginate(15);
        return view('timetable::teacher_availabilities.index', compact('availabilities'));
    }

    public function create()
    {
        $teachers = Staff::orderBy('first_name')->get();
        return view('Timetable::teacher_availabilities.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'teacher_id' => 'required|integer',
            'days_of_week' => 'required|array',
            'days_of_week.*' => 'string',
            'start_time' => 'required',
            'end_time' => 'required',
            'preferred' => 'nullable|boolean',
            'unavailable' => 'nullable|boolean',
        ]);
        // Prevent overlapping availabilities for the same teacher
        foreach ($data['days_of_week'] as $day) {
            $overlap = TeacherAvailability::where('teacher_id', $data['teacher_id'])
                ->where('day_of_week', $day)
                ->where(function($q) use ($data) {
                    $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                      ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                      ->orWhere(function($q2) use ($data) {
                          $q2->where('start_time', '<=', $data['start_time'])
                             ->where('end_time', '>=', $data['end_time']);
                      });
                })->exists();
            if ($overlap) {
                return back()->withInput()->withErrors(['teacher_id' => 'This teacher already has availability for ' . $day . ' at this time.']);
            }
        }
        // Bulk create for each selected day
        foreach ($data['days_of_week'] as $day) {
            TeacherAvailability::create([
                'teacher_id' => $data['teacher_id'],
                'day_of_week' => $day,
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'preferred' => $data['preferred'] ?? null,
                'unavailable' => $data['unavailable'] ?? null,
            ]);
        }
        return redirect()->route('teacher_availabilities.index')->with('success', 'Teacher availability created successfully.');
    }

    public function show($id)
    {
        $availability = TeacherAvailability::findOrFail($id);
        return view('Timetable::teacher_availabilities.show', compact('availability'));
    }

    public function edit($id)
    {
        $availability = TeacherAvailability::findOrFail($id);
        $teachers = Staff::orderBy('first_name')->get();
        return view('Timetable::teacher_availabilities.edit', compact('availability', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $availability = TeacherAvailability::findOrFail($id);
        $data = $request->validate([
            'teacher_id' => 'required|integer',
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'preferred' => 'nullable|boolean',
            'unavailable' => 'nullable|boolean',
        ]);
        $availability->update($data);
        return redirect()->route('teacher_availabilities.index')->with('success', 'Teacher availability updated successfully.');
    }

    public function destroy($id)
    {
        $availability = TeacherAvailability::findOrFail($id);
        $availability->delete();
        return redirect()->route('teacher_availabilities.index')->with('success', 'Teacher availability deleted successfully.');
    }

    public function exportCsv()
    {
        $availabilities = TeacherAvailability::all();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="teacher_availabilities.csv"',
        ];
        $columns = ['id', 'teacher_id', 'day_of_week', 'start_time', 'end_time'];
        $callback = function() use ($availabilities, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($availabilities as $a) {
                fputcsv($file, [
                    $a->id,
                    $a->teacher_id,
                    $a->day_of_week,
                    $a->start_time,
                    $a->end_time,
                ]);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $availabilities = TeacherAvailability::all();
        $pdf = Pdf::loadView('Timetable::teacher_availabilities_pdf', compact('availabilities'));
        return $pdf->download('teacher_availabilities.pdf');
    }
} 