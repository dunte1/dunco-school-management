<?php

namespace Modules\Academic\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academic\Models\AcademicRecord;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\AcademicClass;
use Modules\Academic\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AcademicRecordController extends Controller
{
    /**
     * Display a listing of academic records.
     */
    public function index(Request $request)
    {
        $query = AcademicRecord::with(['student', 'class', 'subject']);

        // Filter by student
        if ($request->has('student_id') && $request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        // Filter by class
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        // Filter by subject
        if ($request->has('subject_id') && $request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by academic year
        if ($request->has('academic_year') && $request->academic_year) {
            $query->where('academic_year', $request->academic_year);
        }

        // Filter by term
        if ($request->has('term') && $request->term) {
            $query->where('term', $request->term);
        }

        // Search by student name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $academicRecords = $query->orderBy('exam_date', 'desc')->paginate(15);
        $students = Student::where('is_active', true)->get();
        $classes = AcademicClass::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();

        return view('academic::academic-records.index', compact('academicRecords', 'students', 'classes', 'subjects'));
    }

    /**
     * Show the form for creating a new academic record.
     */
    public function create()
    {
        $students = Student::where('is_active', true)->get();
        $classes = AcademicClass::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        
        return view('academic::academic-records.create', compact('students', 'classes', 'subjects'));
    }

    /**
     * Store a newly created academic record.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:academic_students,id',
            'class_id' => 'required|exists:academic_classes,id',
            'subject_id' => 'required|exists:academic_subjects,id',
            'academic_year' => 'required|string',
            'term' => 'required|string',
            'exam_type' => 'required|string',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'exam_date' => 'required|date',
            'remarks' => 'nullable|string'
        ]);

        // Calculate percentage
        $percentage = ($request->marks_obtained / $request->total_marks) * 100;

        // Calculate grade
        $grade = $this->calculateGrade($percentage);

        try {
            AcademicRecord::create([
                'school_id' => auth()->user()->school_id ?? 1,
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'academic_year' => $request->academic_year,
                'term' => $request->term,
                'exam_type' => $request->exam_type,
                'marks_obtained' => $request->marks_obtained,
                'total_marks' => $request->total_marks,
                'percentage' => $percentage,
                'grade' => $grade,
                'remarks' => $request->remarks,
                'exam_date' => $request->exam_date,
                'is_active' => true
            ]);

            return redirect()->route('academic.academic-records.index')
                           ->with('success', 'Academic record created successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create academic record: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified academic record.
     */
    public function show($id)
    {
        $academicRecord = AcademicRecord::with(['student', 'class', 'subject'])
                                       ->findOrFail($id);
        
        return view('academic::academic-records.show', compact('academicRecord'));
    }

    /**
     * Show the form for editing the specified academic record.
     */
    public function edit($id)
    {
        $academicRecord = AcademicRecord::findOrFail($id);
        $students = Student::where('is_active', true)->get();
        $classes = AcademicClass::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        
        return view('academic::academic-records.edit', compact('academicRecord', 'students', 'classes', 'subjects'));
    }

    /**
     * Update the specified academic record.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:academic_students,id',
            'class_id' => 'required|exists:academic_classes,id',
            'subject_id' => 'required|exists:academic_subjects,id',
            'academic_year' => 'required|string',
            'term' => 'required|string',
            'exam_type' => 'required|string',
            'marks_obtained' => 'required|numeric|min:0',
            'total_marks' => 'required|numeric|min:1',
            'exam_date' => 'required|date',
            'remarks' => 'nullable|string'
        ]);

        // Calculate percentage
        $percentage = ($request->marks_obtained / $request->total_marks) * 100;

        // Calculate grade
        $grade = $this->calculateGrade($percentage);

        try {
            $academicRecord = AcademicRecord::findOrFail($id);
            $academicRecord->update([
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'academic_year' => $request->academic_year,
                'term' => $request->term,
                'exam_type' => $request->exam_type,
                'marks_obtained' => $request->marks_obtained,
                'total_marks' => $request->total_marks,
                'percentage' => $percentage,
                'grade' => $grade,
                'remarks' => $request->remarks,
                'exam_date' => $request->exam_date
            ]);

            return redirect()->route('academic.academic-records.index')
                           ->with('success', 'Academic record updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update academic record: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified academic record.
     */
    public function destroy($id)
    {
        try {
            $academicRecord = AcademicRecord::findOrFail($id);
            $academicRecord->delete();

            return redirect()->route('academic.academic-records.index')
                           ->with('success', 'Academic record deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete academic record: ' . $e->getMessage()]);
        }
    }

    /**
     * Get academic records statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_records' => AcademicRecord::count(),
            'average_percentage' => AcademicRecord::avg('percentage'),
            'top_performers' => AcademicRecord::with('student')
                ->orderBy('percentage', 'desc')
                ->limit(10)
                ->get(),
            'subject_performance' => AcademicRecord::select('subject_id', DB::raw('AVG(percentage) as avg_percentage'))
                ->with('subject')
                ->groupBy('subject_id')
                ->get(),
            'class_performance' => AcademicRecord::select('class_id', DB::raw('AVG(percentage) as avg_percentage'))
                ->with('class')
                ->groupBy('class_id')
                ->get()
        ];

        return view('academic::academic-records.statistics', compact('stats'));
    }

    /**
     * Bulk import academic records.
     */
    public function bulkImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls',
            'academic_year' => 'required|string',
            'term' => 'required|string',
            'exam_type' => 'required|string'
        ]);

        try {
            // Handle file upload and processing
            // This would need to be implemented based on your file structure
            
            return redirect()->route('academic.academic-records.index')
                           ->with('success', 'Academic records imported successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to import academic records: ' . $e->getMessage()]);
        }
    }

    /**
     * Export academic records.
     */
    public function export(Request $request)
    {
        $query = AcademicRecord::with(['student', 'class', 'subject']);

        // Apply filters
        if ($request->has('student_id') && $request->student_id) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->has('academic_year') && $request->academic_year) {
            $query->where('academic_year', $request->academic_year);
        }

        $records = $query->get();

        // Generate CSV/Excel export
        // This would need to be implemented based on your export requirements

        return response()->download('academic_records_export.csv');
    }

    /**
     * Calculate grade based on percentage.
     */
    private function calculateGrade($percentage)
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 35) return 'D';
        return 'F';
    }
}
