<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\AcademicClass;
use Modules\Academic\Models\Subject;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of classes with search and filter
     */
    public function index(Request $request)
    {
        $query = AcademicClass::with(['teacher', 'school', 'students', 'subjects'])
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

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $classes = $query->orderBy('name')->paginate(15);

        // Get filter options
        $academicYears = AcademicClass::where('school_id', Auth::user()->school_id)
            ->distinct()->pluck('academic_year')->sort();
        
        $teachers = User::where('school_id', Auth::user()->school_id)
            ->whereHas('roles', function($q) {
                $q->where('name', 'teacher');
            })->get();

        return view('academic::classes.index', compact('classes', 'academicYears', 'teachers'));
    }

    /**
     * Show the form for creating a new class
     */
    public function create()
    {
        $teachers = User::where('school_id', Auth::user()->school_id)
            ->whereHas('roles', function($q) {
                $q->where('name', 'teacher');
            })->get();

        $subjects = Subject::where('school_id', Auth::user()->school_id)
            ->where('is_active', true)
            ->get();

        $academicYears = $this->getAcademicYears();

        return view('academic::classes.create', compact('teachers', 'subjects', 'academicYears'));
    }

    /**
     * Store a newly created class
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:academic_classes,code',
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1|max:100',
            'teacher_id' => 'nullable|exists:users,id',
            'academic_year' => 'required|string|max:20',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:academic_subjects,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $class = AcademicClass::create([
            'school_id' => Auth::user()->school_id,
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'capacity' => $request->capacity,
            'teacher_id' => $request->teacher_id,
            'academic_year' => $request->academic_year,
            'is_active' => true,
        ]);

        if ($request->filled('subjects')) {
            $class->subjects()->attach($request->subjects);
        }

        return redirect()->route('academic.classes.index')
            ->with('success', 'Class created successfully!');
    }

    /**
     * Display the specified class
     */
    public function show($id)
    {
        $class = AcademicClass::with(['teacher', 'school', 'students', 'subjects'])
            ->where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $enrolledStudents = $class->students()->paginate(10);
        $availableStudents = User::where('school_id', Auth::user()->school_id)
            ->whereHas('roles', function($q) {
                $q->where('name', 'student');
            })
            ->whereDoesntHave('classes', function($q) use ($class) {
                $q->where('class_id', $class->id);
            })
            ->get();

        return view('academic::classes.show', compact('class', 'enrolledStudents', 'availableStudents'));
    }

    /**
     * Show the form for editing the specified class
     */
    public function edit($id)
    {
        $class = AcademicClass::with(['subjects'])
            ->where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $teachers = User::where('school_id', Auth::user()->school_id)
            ->whereHas('roles', function($q) {
                $q->where('name', 'teacher');
            })->get();

        $subjects = Subject::where('school_id', Auth::user()->school_id)
            ->where('is_active', true)
            ->get();

        $academicYears = $this->getAcademicYears();

        return view('academic::classes.edit', compact('class', 'teachers', 'subjects', 'academicYears'));
    }

    /**
     * Update the specified class
     */
    public function update(Request $request, $id)
    {
        $class = AcademicClass::where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:academic_classes,code,' . $id,
            'description' => 'nullable|string',
            'capacity' => 'required|integer|min:1|max:100',
            'teacher_id' => 'nullable|exists:users,id',
            'academic_year' => 'required|string|max:20',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:academic_subjects,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $class->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'capacity' => $request->capacity,
            'teacher_id' => $request->teacher_id,
            'academic_year' => $request->academic_year,
        ]);

        // Sync subjects
        $class->subjects()->sync($request->subjects ?? []);

        return redirect()->route('academic.classes.index')
            ->with('success', 'Class updated successfully!');
    }

    /**
     * Remove the specified class
     */
    public function destroy($id)
    {
        $class = AcademicClass::where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        // Check if class has students
        if ($class->students()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete class with enrolled students. Please transfer students first.');
        }

        $class->delete();

        return redirect()->route('academic.classes.index')
            ->with('success', 'Class deleted successfully!');
    }

    /**
     * Toggle class status
     */
    public function toggleStatus($id)
    {
        $class = AcademicClass::where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $class->update(['is_active' => !$class->is_active]);

        $status = $class->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Class {$status} successfully!");
    }

    /**
     * Enroll student in class
     */
    public function enrollStudent(Request $request, $id)
    {
        $class = AcademicClass::where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // Check if student is already enrolled
        if ($class->students()->where('student_id', $request->student_id)->exists()) {
            return redirect()->back()->with('error', 'Student is already enrolled in this class.');
        }

        // Check class capacity
        if ($class->students()->count() >= $class->capacity) {
            return redirect()->back()->with('error', 'Class is at maximum capacity.');
        }

        $class->students()->attach($request->student_id, [
            'enrollment_date' => now(),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Student enrolled successfully!');
    }

    /**
     * Remove student from class
     */
    public function removeStudent($classId, $studentId)
    {
        $class = AcademicClass::where('school_id', Auth::user()->school_id)
            ->findOrFail($classId);

        $class->students()->detach($studentId);

        return redirect()->back()->with('success', 'Student removed from class successfully!');
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