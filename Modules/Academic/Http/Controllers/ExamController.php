<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\app\Models\Exam;
use Modules\Academic\Models\Subject;
use Modules\Academic\Models\AcademicClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of exams with search and filter
     */
    public function index(Request $request)
    {
        $query = Exam::with(['school', 'subjects', 'classes'])
            ->where('school_id', Auth::user()->school_id);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by exam type
        if ($request->filled('exam_type')) {
            $query->where('exam_type', $request->exam_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $exams = $query->orderBy('start_date', 'desc')->paginate(15);

        // Get filter options
        $examTypes = ['midterm', 'final', 'quiz', 'assignment', 'project'];
        $statuses = ['draft', 'published', 'ongoing', 'completed', 'archived'];
        $academicYears = Exam::where('school_id', Auth::user()->school_id)
            ->distinct()->pluck('academic_year')->sort();

        return view('academic::exams.index', compact('exams', 'examTypes', 'statuses', 'academicYears'));
    }

    /**
     * Show the form for creating a new exam
     */
    public function create()
    {
        $subjects = Subject::where('school_id', Auth::user()->school_id)
            ->where('is_active', true)
            ->get();

        $classes = AcademicClass::where('school_id', Auth::user()->school_id)
            ->where('is_active', true)
            ->get();

        $examTypes = ['midterm', 'final', 'quiz', 'assignment', 'project'];
        $terms = ['first', 'second', 'third', 'fourth', 'final'];
        $academicYears = $this->getAcademicYears();

        return view('academic::exams.create', compact('subjects', 'classes', 'examTypes', 'terms', 'academicYears'));
    }

    /**
     * Store a newly created exam
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:academic_exams,code',
            'description' => 'nullable|string',
            'exam_type' => 'required|in:midterm,final,quiz,assignment,project',
            'academic_year' => 'required|string|max:20',
            'term' => 'required|in:first,second,third,fourth,final',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'total_marks' => 'required|numeric|min:1|max:1000',
            'passing_marks' => 'required|numeric|min:1|max:total_marks',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:academic_subjects,id',
            'classes' => 'required|array|min:1',
            'classes.*' => 'exists:academic_classes,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $exam = Exam::create([
            'school_id' => Auth::user()->school_id,
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'exam_type' => $request->exam_type,
            'academic_year' => $request->academic_year,
            'term' => $request->term,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration_minutes' => $request->duration_minutes,
            'total_marks' => $request->total_marks,
            'passing_marks' => $request->passing_marks,
            'status' => 'draft',
            'is_active' => true,
        ]);

        // Attach subjects and classes
        $exam->subjects()->attach($request->subjects);
        $exam->classes()->attach($request->classes);

        return redirect()->route('academic.exams.index')
            ->with('success', 'Exam created successfully!');
    }

    /**
     * Display the specified exam
     */
    public function show($id)
    {
        $exam = Exam::with(['school', 'subjects', 'classes', 'results'])
            ->where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $results = $exam->results()->with(['student', 'subject'])->paginate(15);

        return view('academic::exams.show', compact('exam', 'results'));
    }

    /**
     * Show the form for editing the specified exam
     */
    public function edit($id)
    {
        $exam = Exam::with(['subjects', 'classes'])
            ->where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $subjects = Subject::where('school_id', Auth::user()->school_id)
            ->where('is_active', true)
            ->get();

        $classes = AcademicClass::where('school_id', Auth::user()->school_id)
            ->where('is_active', true)
            ->get();

        $examTypes = ['midterm', 'final', 'quiz', 'assignment', 'project'];
        $terms = ['first', 'second', 'third', 'fourth', 'final'];
        $academicYears = $this->getAcademicYears();

        return view('academic::exams.edit', compact('exam', 'subjects', 'classes', 'examTypes', 'terms', 'academicYears'));
    }

    /**
     * Update the specified exam
     */
    public function update(Request $request, $id)
    {
        $exam = Exam::where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:academic_exams,code,' . $id,
            'description' => 'nullable|string',
            'exam_type' => 'required|in:midterm,final,quiz,assignment,project',
            'academic_year' => 'required|string|max:20',
            'term' => 'required|in:first,second,third,fourth,final',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'total_marks' => 'required|numeric|min:1|max:1000',
            'passing_marks' => 'required|numeric|min:1|max:total_marks',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:academic_subjects,id',
            'classes' => 'required|array|min:1',
            'classes.*' => 'exists:academic_classes,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $exam->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'exam_type' => $request->exam_type,
            'academic_year' => $request->academic_year,
            'term' => $request->term,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'duration_minutes' => $request->duration_minutes,
            'total_marks' => $request->total_marks,
            'passing_marks' => $request->passing_marks,
        ]);

        // Sync subjects and classes
        $exam->subjects()->sync($request->subjects);
        $exam->classes()->sync($request->classes);

        return redirect()->route('academic.exams.index')
            ->with('success', 'Exam updated successfully!');
    }

    /**
     * Remove the specified exam
     */
    public function destroy($id)
    {
        $exam = Exam::where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        // Check if exam has results
        if ($exam->results()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete exam with existing results. Please archive it instead.');
        }

        $exam->delete();

        return redirect()->route('academic.exams.index')
            ->with('success', 'Exam deleted successfully!');
    }

    /**
     * Publish exam
     */
    public function publish($id)
    {
        $exam = Exam::where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $exam->update(['status' => 'published']);

        return redirect()->back()
            ->with('success', 'Exam published successfully!');
    }

    /**
     * Archive exam
     */
    public function archive($id)
    {
        $exam = Exam::where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $exam->update(['status' => 'archived']);

        return redirect()->back()
            ->with('success', 'Exam archived successfully!');
    }

    /**
     * Get academic years for dropdown
     */
    private function getAcademicYears()
    {
        $currentYear = date('Y');
        $years = [];
        
        for ($i = -2; $i <= 2; $i++) {
            $year = $currentYear + $i;
            $years[] = ($year - 1) . '-' . $year;
        }
        
        return $years;
    }
} 