<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\Subject;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Academic\Models\AcademicClass;
use App\Models\User;
use Modules\Academic\Models\SubjectGroup;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of subjects with search and filter
     */
    public function index(Request $request)
    {
        $query = Subject::with(['school', 'classes', 'teachers'])
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

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $subjects = $query->orderBy('name')->paginate(15);

        // Fetch all classes for the modal dropdown
        $classes = AcademicClass::where('school_id', Auth::user()->school_id)->orderBy('name')->get();
        $teachers = User::where('school_id', Auth::user()->school_id)
            ->whereHas('roles', function($q) { $q->where('name', 'teacher'); })
            ->orderBy('name')->get();
        $allSubjects = Subject::where('school_id', Auth::user()->school_id)->orderBy('name')->get();
        $allGroups = SubjectGroup::orderBy('name')->get();
        $groups = $allGroups;

        return view('academic::subjects.index', compact('subjects', 'classes', 'teachers', 'allSubjects', 'allGroups', 'groups'));
    }

    /**
     * Show the form for creating a new subject
     */
    public function create()
    {
        $classes = AcademicClass::where('school_id', Auth::user()->school_id)->orderBy('name')->get();
        $teachers = User::where('school_id', Auth::user()->school_id)
            ->whereHas('roles', function($q) { $q->where('name', 'teacher'); })
            ->orderBy('name')->get();
        $allSubjects = Subject::where('school_id', Auth::user()->school_id)->orderBy('name')->get();
        $allGroups = SubjectGroup::orderBy('name')->get();
        return view('academic::subjects.create', compact('classes', 'teachers', 'allSubjects', 'allGroups'));
    }

    /**
     * Store a newly created subject
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:academic_subjects,code',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:10',
            'class_ids' => 'array',
            'class_ids.*' => 'exists:academic_classes,id',
            'teacher_ids' => 'array',
            'teacher_ids.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $subject = Subject::create([
            'school_id' => Auth::user()->school_id,
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'credits' => $request->credits,
            'is_active' => true,
        ]);

        if ($request->has('class_ids')) {
            $subject->classes()->sync($request->class_ids);
        }

        if ($request->has('teacher_ids')) {
            $subject->teachers()->sync($request->teacher_ids);
        }

        $this->logAudit($subject->id, 'created', $subject->toArray());

        return redirect()->route('academic.subjects.index')
            ->with('success', 'Subject created successfully!');
    }

    /**
     * Display the specified subject
     */
    public function show($id, Request $request)
    {
        $subject = Subject::with(['school', 'classes', 'teachers'])
            ->where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        if ($request->ajax()) {
            return response()->json($subject);
        }

        return view('academic::subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified subject
     */
    public function edit($id, Request $request)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)
            ->with(['classes', 'teachers', 'prerequisites', 'groups'])
            ->findOrFail($id);
        $classes = AcademicClass::where('school_id', Auth::user()->school_id)->orderBy('name')->get();
        $teachers = User::where('school_id', Auth::user()->school_id)
            ->whereHas('roles', function($q) { $q->where('name', 'teacher'); })
            ->orderBy('name')->get();
        $allSubjects = Subject::where('school_id', Auth::user()->school_id)->where('id', '!=', $id)->orderBy('name')->get();
        $allGroups = SubjectGroup::orderBy('name')->get();
        if ($request->ajax()) {
            return response()->json($subject);
        }
        return view('academic::subjects.edit', compact('subject', 'classes', 'teachers', 'allSubjects', 'allGroups'));
    }

    /**
     * Update the specified subject
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:academic_subjects,code,' . $id,
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:10',
            'class_ids' => 'array',
            'class_ids.*' => 'exists:academic_classes,id',
            'teacher_ids' => 'array',
            'teacher_ids.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $old = $subject->getOriginal();
        $subject->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'credits' => $request->credits,
        ]);

        if ($request->has('class_ids')) {
            $subject->classes()->sync($request->class_ids);
        }

        if ($request->has('teacher_ids')) {
            $subject->teachers()->sync($request->teacher_ids);
        }

        $this->logAudit($subject->id, 'updated', ['old' => $old, 'new' => $subject->toArray()]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'subject' => $subject]);
        }

        return redirect()->route('academic.subjects.index')
            ->with('success', 'Subject updated successfully!');
    }

    /**
     * Remove the specified subject
     */
    public function destroy($id)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        // Check if subject is used in any classes
        if ($subject->classes()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete subject that is assigned to classes. Please remove from classes first.');
        }

        $this->logAudit($subject->id, 'deleted', $subject->toArray());
        $subject->delete();

        return redirect()->route('academic.subjects.index')
            ->with('success', 'Subject deleted successfully!');
    }

    /**
     * Toggle subject status
     */
    public function toggleStatus($id)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $subject->update(['is_active' => !$subject->is_active]);

        $status = $subject->is_active ? 'activated' : 'deactivated';
        return redirect()->back()
            ->with('success', "Subject {$status} successfully!");
    }

    /**
     * Get data for assignment modal (classes and teachers)
     */
    public function assignData($id, Request $request)
    {
        $subject = Subject::with(['classes', 'teachers', 'groups'])
            ->where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $assignedClassIds = $subject->classes->pluck('id')->toArray();
        $assignedTeacherIds = $subject->teachers->pluck('id')->toArray();
        $assignedGroupIds = $subject->groups->pluck('id')->toArray();

        $classes = \Modules\Academic\Models\AcademicClass::where('school_id', Auth::user()->school_id)
            ->orderBy('name')->get()
            ->map(function($cls) use ($assignedClassIds) {
                return [
                    'id' => $cls->id,
                    'name' => $cls->name,
                    'assigned' => in_array($cls->id, $assignedClassIds),
                ];
            });

        $teachers = \App\Models\User::where('school_id', Auth::user()->school_id)
            ->whereHas('roles', function($q) { $q->where('name', 'teacher'); })
            ->orderBy('name')->get()
            ->map(function($teacher) use ($assignedTeacherIds) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'assigned' => in_array($teacher->id, $assignedTeacherIds),
                ];
            });

        $groups = SubjectGroup::orderBy('name')->get()
            ->map(function($group) use ($assignedGroupIds) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'assigned' => in_array($group->id, $assignedGroupIds),
                ];
            });

        return response()->json([
            'classes' => $classes,
            'teachers' => $teachers,
            'groups' => $groups,
        ]);
    }

    /**
     * Assign subject to classes, teachers, and groups
     */
    public function assign($id, Request $request)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $classIds = $request->input('class_ids', []);
        $teacherIds = $request->input('teacher_ids', []);
        $groupIds = $request->input('group_ids', []);

        $subject->classes()->sync($classIds);
        $subject->teachers()->sync($teacherIds);
        $subject->groups()->sync($groupIds);

        $this->logAudit($subject->id, 'assigned_classes', [
            'class_ids' => $classIds,
            'teacher_ids' => $teacherIds,
            'group_ids' => $groupIds,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get subject performance data (mock)
     */
    public function performance($id, Request $request)
    {
        // TODO: Replace with real stats
        $data = [
            'average_score' => rand(60, 95),
            'pass_rate' => rand(70, 100),
            'top_performer' => 'Jane Doe',
        ];
        return response()->json($data);
    }

    /**
     * List prerequisites for a subject
     */
    public function prerequisites($id)
    {
        $subject = Subject::with('prerequisites')->findOrFail($id);
        return response()->json([
            'subject_id' => $subject->id,
            'prerequisites' => $subject->prerequisites,
        ]);
    }

    /**
     * Add a prerequisite to a subject
     */
    public function addPrerequisite(Request $request, $id)
    {
        $request->validate([
            'prerequisite_id' => 'required|exists:academic_subjects,id',
        ]);
        $subject = Subject::findOrFail($id);
        $subject->prerequisites()->attach($request->prerequisite_id);
        return response()->json(['success' => true, 'message' => 'Prerequisite added.']);
    }

    /**
     * Remove a prerequisite from a subject
     */
    public function removePrerequisite($id, $prereqId)
    {
        $subject = Subject::findOrFail($id);
        $subject->prerequisites()->detach($prereqId);
        return response()->json(['success' => true, 'message' => 'Prerequisite removed.']);
    }

    public function auditLogs($id)
    {
        $subject = Subject::findOrFail($id);
        $logs = $subject->auditLogs()->with('user')->latest()->get();
        return response()->json(['logs' => $logs]);
    }

    /**
     * List approvals for a subject
     */
    public function approvals($id)
    {
        $subject = Subject::with('approvals.approvedBy')->findOrFail($id);
        return response()->json(['approvals' => $subject->approvals]);
    }

    /**
     * Submit approval decision for a subject
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'reason' => 'nullable|string|max:255',
        ]);
        $subject = Subject::findOrFail($id);
        $approval = $subject->approvals()->create([
            'status' => $request->status,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'reason' => $request->reason,
        ]);
        $this->logAudit($subject->id, 'approval_' . $request->status, $approval->toArray());
        return response()->json(['success' => true, 'approval' => $approval]);
    }

    /**
     * Get capacity/enrollment limits for a subject
     */
    public function capacity($id)
    {
        $subject = Subject::with('capacityLimit')->findOrFail($id);
        return response()->json(['capacity' => $subject->capacityLimit]);
    }

    /**
     * Set capacity/enrollment limits for a subject
     */
    public function setCapacity(Request $request, $id)
    {
        $request->validate([
            'min_enrollment' => 'nullable|integer|min:0',
            'max_enrollment' => 'nullable|integer|min:1',
        ]);
        $subject = Subject::findOrFail($id);
        $limit = $subject->capacityLimit()->updateOrCreate(
            ['subject_id' => $id],
            ['min_enrollment' => $request->min_enrollment, 'max_enrollment' => $request->max_enrollment]
        );
        $this->logAudit($subject->id, 'set_capacity', $limit->toArray());
        return response()->json(['success' => true, 'capacity' => $limit]);
    }

    /**
     * Import subjects from Excel/CSV
     */
    public function importSubjects(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);
        // TODO: Implement import logic using Laravel Excel or similar
        // Example: \Maatwebsite\Excel\Facades\Excel::import(new SubjectImport, $request->file('file'));
        return response()->json(['success' => true, 'message' => 'Import not yet implemented.']);
    }

    /**
     * Export subjects to Excel/CSV
     */
    public function exportSubjects(Request $request)
    {
        // TODO: Implement export logic using Laravel Excel or similar
        // Example: return \Maatwebsite\Excel\Facades\Excel::download(new SubjectExport, 'subjects.xlsx');
        return response()->json(['success' => true, 'message' => 'Export not yet implemented.']);
    }

    /**
     * Return analytics for subjects (pass rates, averages, etc.)
     */
    public function analytics(Request $request)
    {
        // TODO: Implement analytics logic
        return response()->json([
            'average_score' => null,
            'pass_rate' => null,
            'top_performer' => null,
        ]);
    }

    protected function logAudit($subjectId, $action, $changes = null)
    {
        \Modules\Academic\Models\SubjectAuditLog::create([
            'subject_id' => $subjectId,
            'user_id' => auth()->id(),
            'action' => $action,
            'changes' => $changes ? json_encode($changes) : null,
        ]);
    }
}