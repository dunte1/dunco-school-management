<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\Academic\Models\OnlineClass;
use Modules\Academic\Models\AcademicClass;
use Modules\Academic\Models\Subject;
use App\Models\User;

class OnlineClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of online classes
     */
    public function index(Request $request): View
    {
        $query = OnlineClass::with(['teacher', 'academicClass', 'subject'])
            ->where('school_id', Auth::user()->school_id);

        // Filter by role
        if (Auth::user()->hasRole('student')) {
            $query->whereHas('academicClass.students', function($q) {
                $q->where('student_id', Auth::id());
            });
        } elseif (Auth::user()->hasRole('teacher')) {
            $query->where('teacher_id', Auth::id());
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('academicClass', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('subject', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'upcoming':
                    $query->where('start_time', '>', now());
                    break;
                case 'ongoing':
                    $query->where('start_time', '<=', now())
                          ->where('end_time', '>=', now());
                    break;
                case 'completed':
                    $query->where('end_time', '<', now());
                    break;
            }
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('start_time', $request->date);
        }

        $onlineClasses = $query->orderBy('start_time', 'desc')->paginate(15);

        return view('academic::online-classes.index', compact('onlineClasses'));
    }

    /**
     * Show the form for creating a new online class
     */
    public function create(): View
    {
        $academicClasses = AcademicClass::where('school_id', Auth::user()->school_id)
            ->where('is_active', true)
            ->get();

        $subjects = Subject::where('school_id', Auth::user()->school_id)
            ->where('is_active', true)
            ->get();

        return view('academic::online-classes.create', compact('academicClasses', 'subjects'));
    }

    /**
     * Store a newly created online class
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'meeting_link' => 'required|url',
            'meeting_id' => 'nullable|string|max:100',
            'meeting_password' => 'nullable|string|max:50',
            'max_participants' => 'nullable|integer|min:1',
            'is_recording_allowed' => 'boolean',
            'instructions' => 'nullable|string',
            'materials' => 'nullable|array',
            'materials.*' => 'nullable|string|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $onlineClass = OnlineClass::create([
                'school_id' => Auth::user()->school_id,
                'teacher_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'academic_class_id' => $request->academic_class_id,
                'subject_id' => $request->subject_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'meeting_link' => $request->meeting_link,
                'meeting_id' => $request->meeting_id,
                'meeting_password' => $request->meeting_password,
                'max_participants' => $request->max_participants,
                'is_recording_allowed' => $request->is_recording_allowed ?? false,
                'instructions' => $request->instructions,
                'materials' => $request->materials ?? [],
                'status' => 'scheduled'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Online class scheduled successfully',
                'onlineClass' => $onlineClass->load(['teacher', 'academicClass', 'subject'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule online class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified online class
     */
    public function show(OnlineClass $onlineClass): View
    {
        $onlineClass->load(['teacher', 'academicClass', 'subject', 'attendances.user']);

        // Check if user can access this class
        if (!Auth::user()->hasRole('admin') && 
            !Auth::user()->hasRole('teacher') && 
            !$onlineClass->academicClass->students->contains(Auth::id())) {
            abort(403, 'You do not have access to this online class');
        }

        return view('academic::online-classes.show', compact('onlineClass'));
    }

    /**
     * Show the form for editing the specified online class
     */
    public function edit(OnlineClass $onlineClass): View
    {
        // Only teacher who created the class or admin can edit
        if (!Auth::user()->hasRole('admin') && $onlineClass->teacher_id !== Auth::id()) {
            abort(403, 'You can only edit your own online classes');
        }

        $academicClasses = AcademicClass::where('school_id', Auth::user()->school_id)
            ->where('is_active', true)
            ->get();

        $subjects = Subject::where('school_id', Auth::user()->school_id)
            ->where('is_active', true)
            ->get();

        return view('academic::online-classes.edit', compact('onlineClass', 'academicClasses', 'subjects'));
    }

    /**
     * Update the specified online class
     */
    public function update(Request $request, OnlineClass $onlineClass): JsonResponse
    {
        // Only teacher who created the class or admin can update
        if (!Auth::user()->hasRole('admin') && $onlineClass->teacher_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You can only edit your own online classes'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'academic_class_id' => 'required|exists:academic_classes,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'meeting_link' => 'required|url',
            'meeting_id' => 'nullable|string|max:100',
            'meeting_password' => 'nullable|string|max:50',
            'max_participants' => 'nullable|integer|min:1',
            'is_recording_allowed' => 'boolean',
            'instructions' => 'nullable|string',
            'materials' => 'nullable|array',
            'materials.*' => 'nullable|string|url'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $onlineClass->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Online class updated successfully',
                'onlineClass' => $onlineClass->load(['teacher', 'academicClass', 'subject'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update online class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified online class
     */
    public function destroy(OnlineClass $onlineClass): JsonResponse
    {
        // Only teacher who created the class or admin can delete
        if (!Auth::user()->hasRole('admin') && $onlineClass->teacher_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete your own online classes'
            ], 403);
        }

        try {
            $onlineClass->delete();

            return response()->json([
                'success' => true,
                'message' => 'Online class deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete online class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Join an online class
     */
    public function join(OnlineClass $onlineClass): JsonResponse
    {
        // Check if user is enrolled in the class
        if (!Auth::user()->hasRole('admin') && 
            !Auth::user()->hasRole('teacher') && 
            !$onlineClass->academicClass->students->contains(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'You are not enrolled in this class'
            ], 403);
        }

        // Check if class is currently active
        $now = now();
        if ($now < $onlineClass->start_time || $now > $onlineClass->end_time) {
            return response()->json([
                'success' => false,
                'message' => 'This class is not currently active'
            ], 400);
        }

        try {
            // Record attendance
            $onlineClass->attendances()->updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'joined_at' => now(),
                    'status' => 'present'
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Successfully joined the online class',
                'meeting_link' => $onlineClass->meeting_link,
                'meeting_id' => $onlineClass->meeting_id,
                'meeting_password' => $onlineClass->meeting_password
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to join online class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start an online class (teacher only)
     */
    public function start(OnlineClass $onlineClass): JsonResponse
    {
        if ($onlineClass->teacher_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Only the assigned teacher can start this class'
            ], 403);
        }

        try {
            $onlineClass->update([
                'status' => 'ongoing',
                'started_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Online class started successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start online class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * End an online class (teacher only)
     */
    public function end(OnlineClass $onlineClass): JsonResponse
    {
        if ($onlineClass->teacher_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Only the assigned teacher can end this class'
            ], 403);
        }

        try {
            $onlineClass->update([
                'status' => 'completed',
                'ended_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Online class ended successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to end online class: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get upcoming online classes for dashboard
     */
    public function upcoming(): JsonResponse
    {
        $query = OnlineClass::with(['teacher', 'academicClass', 'subject'])
            ->where('school_id', Auth::user()->school_id)
            ->where('status', 'scheduled');

        if (Auth::user()->hasRole('student')) {
            $query->whereHas('academicClass.students', function($q) {
                $q->where('student_id', Auth::id());
            });
        } elseif (Auth::user()->hasRole('teacher')) {
            $query->where('teacher_id', Auth::id());
        }

        $upcomingClasses = $query->orderBy('start_time')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'upcomingClasses' => $upcomingClasses
        ]);
    }
} 