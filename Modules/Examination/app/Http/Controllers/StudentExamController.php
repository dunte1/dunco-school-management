<?php

namespace Modules\Examination\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Modules\Examination\app\Models\Exam;
use Modules\Examination\app\Models\ExamAttempt;
use Modules\Examination\app\Models\ExamResult;

class StudentExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * Display student's exam dashboard
     */
    public function dashboard(): View
    {
        $student = Auth::user();
        
        // Get upcoming exams
        $upcomingExams = Exam::with(['examType'])
            ->where('is_online', true)
            ->where('status', 'published')
            ->where('start_date', '>=', now())
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        // Get ongoing exams
        $ongoingExams = Exam::with(['examType'])
            ->where('is_online', true)
            ->where('status', 'ongoing')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        // Get recent attempts
        $recentAttempts = ExamAttempt::with(['exam'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get exam results
        $recentResults = ExamResult::with(['exam'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get active attempt if any
        $activeAttempt = ExamAttempt::where('student_id', $student->id)
            ->whereIn('status', ['started', 'in_progress'])
            ->first();

        return view('examination::student.dashboard', compact(
            'upcomingExams',
            'ongoingExams',
            'recentAttempts',
            'recentResults',
            'activeAttempt'
        ));
    }

    /**
     * Display all available exams for student
     */
    public function exams(Request $request): View
    {
        $query = Exam::with(['examType'])
            ->where('is_online', true)
            ->where('status', 'published');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('examType', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'upcoming':
                    $query->where('start_date', '>', now());
                    break;
                case 'ongoing':
                    $query->where('start_date', '<=', now())
                          ->where('end_date', '>=', now());
                    break;
                case 'completed':
                    $query->where('end_date', '<', now());
                    break;
            }
        }

        $exams = $query->orderBy('start_date', 'desc')->paginate(12);

        return view('examination::student.exams', compact('exams'));
    }

    /**
     * Display exam details and start option
     */
    public function show(Exam $exam): View
    {
        $student = Auth::user();
        
        // Check if student can access this exam
        if (!$exam->canBeStarted()) {
            abort(403, 'This exam is not available for taking');
        }

        // Get student's attempts for this exam
        $attempts = $exam->attempts()
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get active attempt if any
        $activeAttempt = $attempts->whereIn('status', ['started', 'in_progress'])->first();

        // Get best result
        $bestResult = $exam->results()
            ->where('student_id', $student->id)
            ->orderBy('score', 'desc')
            ->first();

        return view('examination::student.show', compact('exam', 'attempts', 'activeAttempt', 'bestResult'));
    }

    /**
     * Start an exam
     */
    public function startExam(Exam $exam): JsonResponse
    {
        $student = Auth::user();

        // Check if exam can be started
        if (!$exam->canBeStarted()) {
            return response()->json([
                'success' => false,
                'message' => 'This exam is not available for taking'
            ], 400);
        }

        // Check if student already has an active attempt
        $activeAttempt = $exam->attempts()
            ->where('student_id', $student->id)
            ->whereIn('status', ['started', 'in_progress'])
            ->first();

        if ($activeAttempt) {
            return response()->json([
                'success' => true,
                'message' => 'You have an active attempt',
                'redirect' => route('examination.online.start', $exam)
            ]);
        }

        // Check if student has exceeded max attempts
        $attemptCount = $exam->attempts()
            ->where('student_id', $student->id)
            ->count();

        if ($exam->max_attempts && $attemptCount >= $exam->max_attempts) {
            return response()->json([
                'success' => false,
                'message' => 'You have exceeded the maximum number of attempts for this exam'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Redirecting to exam...',
            'redirect' => route('examination.online.start', $exam)
        ]);
    }

    /**
     * Display exam history
     */
    public function history(Request $request): View
    {
        $student = Auth::user();
        
        $query = ExamAttempt::with(['exam.examType'])
            ->where('student_id', $student->id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by exam
        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        $attempts = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get all exams for filter
        $exams = Exam::where('is_online', true)->get();

        return view('examination::student.history', compact('attempts', 'exams'));
    }

    /**
     * Display exam results
     */
    public function results(Request $request): View
    {
        $student = Auth::user();
        
        $query = ExamResult::with(['exam.examType'])
            ->where('student_id', $student->id);

        // Filter by exam
        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        $results = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get all exams for filter
        $exams = Exam::where('is_online', true)->get();

        return view('examination::student.results', compact('results', 'exams'));
    }

    /**
     * Get upcoming exams for dashboard
     */
    public function upcomingExams(): JsonResponse
    {
        $upcomingExams = Exam::with(['examType'])
            ->where('is_online', true)
            ->where('status', 'published')
            ->where('start_date', '>=', now())
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'exams' => $upcomingExams
        ]);
    }

    /**
     * Get ongoing exams for dashboard
     */
    public function ongoingExams(): JsonResponse
    {
        $ongoingExams = Exam::with(['examType'])
            ->where('is_online', true)
            ->where('status', 'ongoing')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'exams' => $ongoingExams
        ]);
    }
} 

namespace Modules\Examination\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Modules\Examination\app\Models\Exam;
use Modules\Examination\app\Models\ExamAttempt;
use Modules\Examination\app\Models\ExamResult;

class StudentExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:student');
    }

    /**
     * Display student's exam dashboard
     */
    public function dashboard(): View
    {
        $student = Auth::user();
        
        // Get upcoming exams
        $upcomingExams = Exam::with(['examType'])
            ->where('is_online', true)
            ->where('status', 'published')
            ->where('start_date', '>=', now())
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        // Get ongoing exams
        $ongoingExams = Exam::with(['examType'])
            ->where('is_online', true)
            ->where('status', 'ongoing')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        // Get recent attempts
        $recentAttempts = ExamAttempt::with(['exam'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get exam results
        $recentResults = ExamResult::with(['exam'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get active attempt if any
        $activeAttempt = ExamAttempt::where('student_id', $student->id)
            ->whereIn('status', ['started', 'in_progress'])
            ->first();

        return view('examination::student.dashboard', compact(
            'upcomingExams',
            'ongoingExams',
            'recentAttempts',
            'recentResults',
            'activeAttempt'
        ));
    }

    /**
     * Display all available exams for student
     */
    public function exams(Request $request): View
    {
        $query = Exam::with(['examType'])
            ->where('is_online', true)
            ->where('status', 'published');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('examType', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'upcoming':
                    $query->where('start_date', '>', now());
                    break;
                case 'ongoing':
                    $query->where('start_date', '<=', now())
                          ->where('end_date', '>=', now());
                    break;
                case 'completed':
                    $query->where('end_date', '<', now());
                    break;
            }
        }

        $exams = $query->orderBy('start_date', 'desc')->paginate(12);

        return view('examination::student.exams', compact('exams'));
    }

    /**
     * Display exam details and start option
     */
    public function show(Exam $exam): View
    {
        $student = Auth::user();
        
        // Check if student can access this exam
        if (!$exam->canBeStarted()) {
            abort(403, 'This exam is not available for taking');
        }

        // Get student's attempts for this exam
        $attempts = $exam->attempts()
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get active attempt if any
        $activeAttempt = $attempts->whereIn('status', ['started', 'in_progress'])->first();

        // Get best result
        $bestResult = $exam->results()
            ->where('student_id', $student->id)
            ->orderBy('score', 'desc')
            ->first();

        return view('examination::student.show', compact('exam', 'attempts', 'activeAttempt', 'bestResult'));
    }

    /**
     * Start an exam
     */
    public function startExam(Exam $exam): JsonResponse
    {
        $student = Auth::user();

        // Check if exam can be started
        if (!$exam->canBeStarted()) {
            return response()->json([
                'success' => false,
                'message' => 'This exam is not available for taking'
            ], 400);
        }

        // Check if student already has an active attempt
        $activeAttempt = $exam->attempts()
            ->where('student_id', $student->id)
            ->whereIn('status', ['started', 'in_progress'])
            ->first();

        if ($activeAttempt) {
            return response()->json([
                'success' => true,
                'message' => 'You have an active attempt',
                'redirect' => route('examination.online.start', $exam)
            ]);
        }

        // Check if student has exceeded max attempts
        $attemptCount = $exam->attempts()
            ->where('student_id', $student->id)
            ->count();

        if ($exam->max_attempts && $attemptCount >= $exam->max_attempts) {
            return response()->json([
                'success' => false,
                'message' => 'You have exceeded the maximum number of attempts for this exam'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Redirecting to exam...',
            'redirect' => route('examination.online.start', $exam)
        ]);
    }

    /**
     * Display exam history
     */
    public function history(Request $request): View
    {
        $student = Auth::user();
        
        $query = ExamAttempt::with(['exam.examType'])
            ->where('student_id', $student->id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by exam
        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        $attempts = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get all exams for filter
        $exams = Exam::where('is_online', true)->get();

        return view('examination::student.history', compact('attempts', 'exams'));
    }

    /**
     * Display exam results
     */
    public function results(Request $request): View
    {
        $student = Auth::user();
        
        $query = ExamResult::with(['exam.examType'])
            ->where('student_id', $student->id);

        // Filter by exam
        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        $results = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get all exams for filter
        $exams = Exam::where('is_online', true)->get();

        return view('examination::student.results', compact('results', 'exams'));
    }

    /**
     * Get upcoming exams for dashboard
     */
    public function upcomingExams(): JsonResponse
    {
        $upcomingExams = Exam::with(['examType'])
            ->where('is_online', true)
            ->where('status', 'published')
            ->where('start_date', '>=', now())
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'exams' => $upcomingExams
        ]);
    }

    /**
     * Get ongoing exams for dashboard
     */
    public function ongoingExams(): JsonResponse
    {
        $ongoingExams = Exam::with(['examType'])
            ->where('is_online', true)
            ->where('status', 'ongoing')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'exams' => $ongoingExams
        ]);
    }
} 