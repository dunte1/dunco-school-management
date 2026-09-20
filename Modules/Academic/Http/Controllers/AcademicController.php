<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\AcademicClass;
use Modules\Academic\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AcademicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the academic dashboard
     */
    public function index()
    {
        $schoolId = Auth::user()->school_id;

        $totalClasses = AcademicClass::where('school_id', $schoolId)->count();
        $activeClasses = AcademicClass::where('school_id', $schoolId)->where('is_active', true)->count();
        $totalSubjects = Subject::where('school_id', $schoolId)->count();
        $totalStudents = User::where('school_id', $schoolId)
            ->whereHas('roles', function($q) {
                $q->where('name', 'student');
            })->count();

        $recentClasses = AcademicClass::with(['teacher'])
            ->where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentSubjects = Subject::where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('academic::dashboard', compact(
            'totalClasses', 'activeClasses', 'totalSubjects', 'totalStudents',
            'recentClasses', 'recentSubjects'
        ));
    }

    /**
     * Show the form for creating a new academic class
     */
    public function create()
    {
        $schoolId = Auth::user()->school_id;
        $teachers = User::where('school_id', $schoolId)
            ->whereHas('roles', function($q) { $q->where('name', 'teacher'); })
            ->orderBy('name')->get();

        return view('academic::create', compact('teachers'));
    }

    /**
     * Store a newly created academic class in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:academic_classes,code',
            'description' => 'nullable|string|max:1000',
            'capacity' => 'nullable|integer|min:1',
            'teacher_id' => 'nullable|exists:users,id',
            'academic_year' => 'required|string|max:50',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:academic_subjects,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
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

        if ($request->has('subject_ids')) {
            $class->subjects()->sync($request->subject_ids);
        }

        return redirect()->route('academic.index')
            ->with('success', 'Academic class created successfully!');
    }

    /**
     * Display the specified academic class.
     */
    public function show($id)
    {
        $schoolId = Auth::user()->school_id;
        $class = AcademicClass::with(['teacher', 'subjects', 'students'])
            ->where('school_id', $schoolId)
            ->findOrFail($id);

        $studentCount = $class->students()->count();
        $subjectCount = $class->subjects()->count();

        return view('academic::show', compact('class', 'studentCount', 'subjectCount'));
    }

    /**
     * Show the form for editing the specified academic class.
     */
    public function edit($id)
    {
        $schoolId = Auth::user()->school_id;
        $class = AcademicClass::with(['subjects'])
            ->where('school_id', $schoolId)
            ->findOrFail($id);

        $teachers = User::where('school_id', $schoolId)
            ->whereHas('roles', function($q) { $q->where('name', 'teacher'); })
            ->orderBy('name')->get();

        $subjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();

        return view('academic::edit', compact('class', 'teachers', 'subjects'));
    }

    /**
     * Update the specified academic class in storage.
     */
    public function update(Request $request, $id)
    {
        $schoolId = Auth::user()->school_id;
        $class = AcademicClass::where('school_id', $schoolId)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:academic_classes,code,' . $id,
            'description' => 'nullable|string|max:1000',
            'capacity' => 'nullable|integer|min:1',
            'teacher_id' => 'nullable|exists:users,id',
            'academic_year' => 'required|string|max:50',
            'is_active' => 'boolean',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:academic_subjects,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $class->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'capacity' => $request->capacity,
            'teacher_id' => $request->teacher_id,
            'academic_year' => $request->academic_year,
            'is_active' => $request->boolean('is_active', $class->is_active),
        ]);

        if ($request->has('subject_ids')) {
            $class->subjects()->sync($request->subject_ids);
        }

        return redirect()->route('academic.index')
            ->with('success', 'Academic class updated successfully!');
    }

    /**
     * Remove the specified academic class from storage.
     */
    public function destroy($id)
    {
        $schoolId = Auth::user()->school_id;
        $class = AcademicClass::where('school_id', $schoolId)->findOrFail($id);

        if ($class->students()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete class with enrolled students. Remove students first.');
        }

        $class->subjects()->detach();
        $class->delete();

        return redirect()->route('academic.index')
            ->with('success', 'Academic class deleted successfully!');
    }
}
