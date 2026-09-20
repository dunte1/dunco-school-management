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
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Subject::with(['school', 'classes', 'teachers'])
            ->where('school_id', Auth::user()->school_id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $subjects = $query->orderBy('name')->paginate(15);
        $classes = AcademicClass::where('school_id', Auth::user()->school_id)->orderBy('name')->get();
        $teachers = User::where('school_id', Auth::user()->school_id)
            ->whereHas('roles', function($q) { $q->where('name', 'teacher'); })
            ->orderBy('name')->get();
        $allSubjects = Subject::where('school_id', Auth::user()->school_id)->orderBy('name')->get();
        $groups = SubjectGroup::orderBy('name')->get();

        return view('academic::subjects.index', compact('subjects', 'classes', 'teachers', 'allSubjects', 'groups'));
    }

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:academic_subjects,code',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:10',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:academic_classes,id',
            'teacher_ids' => 'nullable|array',
            'teacher_ids.*' => 'exists:users,id',
            'group_ids' => 'nullable|array',
            'group_ids.*' => 'exists:subject_groups,id',
            'prerequisite_ids' => 'nullable|array',
            'prerequisite_ids.*' => 'exists:academic_subjects,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
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
        if ($request->has('group_ids')) {
            $subject->groups()->sync($request->group_ids);
        }
        if ($request->has('prerequisite_ids')) {
            $subject->prerequisites()->sync($request->prerequisite_ids);
        }

        $this->logAudit($subject->id, 'created', $subject->toArray());

        return redirect()->route('academic.subjects.index')
            ->with('success', 'Subject created successfully!');
    }

    public function show($id, Request $request)
    {
        $subject = Subject::with(['school', 'classes', 'teachers', 'groups', 'prerequisites', 'customFields'])
            ->where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        if ($request->ajax()) {
            return response()->json($subject);
        }

        return view('academic::subjects.show', compact('subject'));
    }

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

    public function update(Request $request, $id)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:academic_subjects,code,' . $id,
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:10',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'exists:academic_classes,id',
            'teacher_ids' => 'nullable|array',
            'teacher_ids.*' => 'exists:users,id',
            'group_ids' => 'nullable|array',
            'group_ids.*' => 'exists:subject_groups,id',
            'prerequisite_ids' => 'nullable|array',
            'prerequisite_ids.*' => 'exists:academic_subjects,id',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
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
        if ($request->has('group_ids')) {
            $subject->groups()->sync($request->group_ids);
        }
        if ($request->has('prerequisite_ids')) {
            $subject->prerequisites()->sync($request->prerequisite_ids);
        }

        $this->logAudit($subject->id, 'updated', ['old' => $old, 'new' => $subject->toArray()]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'subject' => $subject]);
        }

        return redirect()->route('academic.subjects.index')
            ->with('success', 'Subject updated successfully!');
    }

    public function destroy($id)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)->findOrFail($id);

        if ($subject->classes()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete subject that is assigned to classes. Remove from classes first.');
        }

        $subject->teachers()->detach();
        $subject->groups()->detach();
        $subject->prerequisites()->detach();
        $subject->customFields()->delete();

        $this->logAudit($subject->id, 'deleted', $subject->toArray());
        $subject->delete();

        return redirect()->route('academic.subjects.index')
            ->with('success', 'Subject deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)->findOrFail($id);
        $subject->update(['is_active' => !$subject->is_active]);

        $status = $subject->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Subject {$status} successfully!");
    }

    public function groups($id)
    {
        $subject = Subject::with('groups')->where('school_id', Auth::user()->school_id)->findOrFail($id);
        return response()->json([
            'subject_id' => $subject->id,
            'groups' => $subject->groups,
        ]);
    }

    public function assignGroups(Request $request, $id)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)->findOrFail($id);

        $request->validate([
            'group_ids' => 'required|array',
            'group_ids.*' => 'exists:subject_groups,id',
        ]);

        $subject->groups()->sync($request->group_ids);

        $this->logAudit($subject->id, 'assigned_groups', ['group_ids' => $request->group_ids]);

        return response()->json([
            'success' => true,
            'message' => 'Groups assigned successfully.',
            'groups' => $subject->groups()->get(),
        ]);
    }

    public function removeGroup($id, $groupId)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)->findOrFail($id);
        $subject->groups()->detach($groupId);

        $this->logAudit($subject->id, 'removed_group', ['group_id' => $groupId]);

        return response()->json(['success' => true, 'message' => 'Group removed.']);
    }

    public function assignData($id, Request $request)
    {
        $subject = Subject::with(['classes', 'teachers', 'groups'])
            ->where('school_id', Auth::user()->school_id)
            ->findOrFail($id);

        $assignedClassIds = $subject->classes->pluck('id')->toArray();
        $assignedTeacherIds = $subject->teachers->pluck('id')->toArray();
        $assignedGroupIds = $subject->groups->pluck('id')->toArray();

        $classes = AcademicClass::where('school_id', Auth::user()->school_id)
            ->orderBy('name')->get()
            ->map(function($cls) use ($assignedClassIds) {
                return [
                    'id' => $cls->id,
                    'name' => $cls->name,
                    'assigned' => in_array($cls->id, $assignedClassIds),
                ];
            });

        $teachers = User::where('school_id', Auth::user()->school_id)
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

    public function assign($id, Request $request)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)->findOrFail($id);

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

    public function performance($id, Request $request)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)->findOrFail($id);

        $classIds = $subject->classes()->pluck('academic_classes.id')->toArray();
        $studentIds = DB::table('academic_class_student')
            ->whereIn('class_id', $classIds)
            ->pluck('student_id')
            ->toArray();

        if (empty($studentIds)) {
            return response()->json([
                'average_score' => 0,
                'pass_rate' => 0,
                'total_students' => 0,
                'top_performer' => null,
                'total_exams' => 0,
            ]);
        }

        try {
            $results = \Modules\Examination\Models\ExamResult::whereIn('student_id', $studentIds)
                ->where('is_published', true)
                ->get();

            $averageScore = $results->count() > 0 ? round($results->avg('percentage'), 1) : 0;
            $passCount = $results->where('result_status', 'pass')->count();
            $passRate = $results->count() > 0 ? round(($passCount / $results->count()) * 100, 1) : 0;

            $topPerformer = null;
            if ($results->count() > 0) {
                $topResult = $results->sortByDesc('percentage')->first();
                $topUser = \App\Models\User::find($topResult->student_id);
                $topPerformer = $topUser ? $topUser->name : null;
            }

            return response()->json([
                'average_score' => $averageScore,
                'pass_rate' => $passRate,
                'total_students' => count($studentIds),
                'top_performer' => $topPerformer,
                'total_exams' => $results->count(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'average_score' => 0,
                'pass_rate' => 0,
                'total_students' => count($studentIds),
                'top_performer' => null,
                'total_exams' => 0,
            ]);
        }
    }

    public function prerequisites($id)
    {
        $subject = Subject::with('prerequisites')->findOrFail($id);
        return response()->json([
            'subject_id' => $subject->id,
            'prerequisites' => $subject->prerequisites,
        ]);
    }

    public function addPrerequisite(Request $request, $id)
    {
        $request->validate([
            'prerequisite_id' => 'required|exists:academic_subjects,id',
        ]);

        $subject = Subject::findOrFail($id);

        if ($subject->id == $request->prerequisite_id) {
            return response()->json(['success' => false, 'message' => 'Subject cannot be its own prerequisite.'], 422);
        }

        $subject->prerequisites()->attach($request->prerequisite_id);
        return response()->json(['success' => true, 'message' => 'Prerequisite added.']);
    }

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

    public function approvals($id)
    {
        $subject = Subject::with('approvals.approvedBy')->findOrFail($id);
        return response()->json(['approvals' => $subject->approvals]);
    }

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

    public function capacity($id)
    {
        $subject = Subject::with('capacityLimit')->findOrFail($id);
        return response()->json(['capacity' => $subject->capacityLimit]);
    }

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

    public function importSubjects(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');
        $header = fgetcsv($handle);
        $headerMap = array_map('strtolower', array_map('trim', $header));

        $imported = 0;
        $skipped = 0;
        $schoolId = Auth::user()->school_id;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) !== count($header)) {
                $skipped++;
                continue;
            }

            $record = array_combine($headerMap, $row);
            $name = $record['name'] ?? '';
            $code = $record['code'] ?? '';

            if (empty($name) || empty($code)) {
                $skipped++;
                continue;
            }

            $exists = Subject::where('school_id', $schoolId)
                ->where('code', $code)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Subject::create([
                'school_id' => $schoolId,
                'name' => $name,
                'code' => $code,
                'description' => $record['description'] ?? null,
                'credits' => (int)($record['credits'] ?? 1),
                'is_active' => true,
            ]);
            $imported++;
        }
        fclose($handle);

        return response()->json([
            'success' => true,
            'message' => "Imported {$imported} subjects. Skipped {$skipped} rows.",
            'imported' => $imported,
            'skipped' => $skipped,
        ]);
    }

    public function exportSubjects(Request $request)
    {
        $subjects = Subject::where('school_id', Auth::user()->school_id)
            ->with(['classes', 'teachers', 'groups'])
            ->orderBy('name')
            ->get();

        $callback = function () use ($subjects) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Code', 'Description', 'Credits', 'Active', 'Classes', 'Teachers', 'Groups']);

            foreach ($subjects as $subject) {
                fputcsv($out, [
                    $subject->id,
                    $subject->name,
                    $subject->code,
                    $subject->description ?? '',
                    $subject->credits,
                    $subject->is_active ? 'Yes' : 'No',
                    $subject->classes->pluck('name')->implode(', '),
                    $subject->teachers->pluck('name')->implode(', '),
                    $subject->groups->pluck('name')->implode(', '),
                ]);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, 'subjects-export.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subjects-export.csv"',
        ]);
    }

    public function analytics(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $subjects = Subject::where('school_id', $schoolId)->get();

        $analytics = $subjects->map(function ($subject) {
            $classIds = $subject->classes()->pluck('academic_classes.id')->toArray();
            $studentIds = DB::table('academic_class_student')
                ->whereIn('class_id', $classIds)
                ->pluck('student_id')
                ->toArray();

            $results = [];
            if (!empty($studentIds)) {
                try {
                    $results = \Modules\Examination\Models\ExamResult::whereIn('student_id', $studentIds)
                        ->where('is_published', true)
                        ->get();
                } catch (\Exception $e) {
                    $results = collect();
                }
            }

            return [
                'id' => $subject->id,
                'name' => $subject->name,
                'code' => $subject->code,
                'total_students' => count($studentIds),
                'average_score' => $results->count() > 0 ? round($results->avg('percentage'), 1) : null,
                'pass_rate' => $results->count() > 0
                    ? round(($results->where('result_status', 'pass')->count() / $results->count()) * 100, 1)
                    : null,
                'total_exams' => $results->count(),
            ];
        });

        $overallAverage = $analytics->where('average_score', '!=', null)->avg('average_score');
        $overallPassRate = $analytics->where('pass_rate', '!=', null)->avg('pass_rate');

        return response()->json([
            'subjects' => $analytics,
            'overall' => [
                'total_subjects' => $subjects->count(),
                'average_score' => $overallAverage ? round($overallAverage, 1) : null,
                'pass_rate' => $overallPassRate ? round($overallPassRate, 1) : null,
            ],
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
