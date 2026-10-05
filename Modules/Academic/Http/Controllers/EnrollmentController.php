<?php

namespace Modules\Academic\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\AcademicClass;
use Modules\Academic\Models\EnrollmentHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of enrollments.
     */
    public function index(Request $request)
    {
        $query = Student::with(['class', 'enrollmentHistory'])
            ->whereNotNull('class_id');

        // Filter by class
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search by student name or ID
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $enrollments = $query->paginate(15);
        $classes = AcademicClass::where('is_active', true)->get();

        return view('academic::enrollments.index', compact('enrollments', 'classes'));
    }

    /**
     * Show the form for creating a new enrollment.
     */
    public function create()
    {
        $classes = AcademicClass::where('is_active', true)->get();
        $students = Student::whereNull('class_id')->get();
        
        return view('academic::enrollments.create', compact('classes', 'students'));
    }

    /**
     * Store a newly created enrollment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:academic_students,id',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'enrollment_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $student = Student::findOrFail($request->student_id);
            
            // Check if student is already enrolled
            if ($student->class_id) {
                return back()->withErrors(['student_id' => 'Student is already enrolled in a class.']);
            }

            // Update student enrollment
            $student->update([
                'class_id' => $request->academic_class_id,
                'admission_date' => $request->enrollment_date
            ]);

            // Create enrollment history record
            EnrollmentHistory::create([
                'student_id' => $student->id,
                'academic_class_id' => $request->academic_class_id,
                'enrollment_date' => $request->enrollment_date,
                'status' => 'enrolled',
                'notes' => $request->notes,
                'enrolled_by' => Auth::id()
            ]);

            DB::commit();
            return redirect()->route('academic.enrollments.index')
                           ->with('success', 'Student enrolled successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to enroll student: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified enrollment.
     */
    public function show($id)
    {
        $student = Student::with(['class', 'enrollmentHistory'])
                         ->findOrFail($id);
        
        return view('academic::enrollments.show', compact('student'));
    }

    /**
     * Show the form for editing the specified enrollment.
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $classes = AcademicClass::where('is_active', true)->get();
        
        return view('academic::enrollments.edit', compact('student', 'classes'));
    }

    /**
     * Update the specified enrollment.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'academic_class_id' => 'required|exists:academic_classes,id',
            'enrollment_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $student = Student::findOrFail($id);
            $oldClassId = $student->class_id;
            
            // Update student enrollment
            $student->update([
                'class_id' => $request->academic_class_id,
                'admission_date' => $request->enrollment_date
            ]);

            // Create enrollment history record for transfer
            if ($oldClassId != $request->academic_class_id) {
                EnrollmentHistory::create([
                    'student_id' => $student->id,
                    'academic_class_id' => $request->academic_class_id,
                    'enrollment_date' => $request->enrollment_date,
                    'status' => 'transferred',
                    'notes' => $request->notes,
                    'enrolled_by' => Auth::id()
                ]);
            }

            DB::commit();
            return redirect()->route('academic.enrollments.index')
                           ->with('success', 'Enrollment updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to update enrollment: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified enrollment.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $student = Student::findOrFail($id);
            
            // Create withdrawal record
            EnrollmentHistory::create([
                'student_id' => $student->id,
                'academic_class_id' => $student->class_id,
                'enrollment_date' => now(),
                'status' => 'withdrawn',
                'notes' => 'Student withdrawn from class',
                'enrolled_by' => Auth::id()
            ]);

            // Remove student from class
            $student->update([
                'class_id' => null,
                'admission_date' => null
            ]);

            DB::commit();
            return redirect()->route('academic.enrollments.index')
                           ->with('success', 'Student withdrawn successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Failed to withdraw student: ' . $e->getMessage()]);
        }
    }

    /**
     * Get enrollment statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_enrolled' => Student::whereNotNull('class_id')->count(),
            'total_students' => Student::count(),
            'enrollment_rate' => Student::whereNotNull('class_id')->count() / max(Student::count(), 1) * 100,
            'class_distribution' => Student::select('class_id', DB::raw('count(*) as count'))
                ->whereNotNull('class_id')
                ->groupBy('class_id')
                ->with('class')
                ->get()
        ];

        return view('academic::enrollments.statistics', compact('stats'));
    }
}
