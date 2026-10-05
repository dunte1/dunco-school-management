<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Fee;
use App\Models\Attendance;
use App\Models\Assignment;
use App\Models\Announcement;
use App\Models\Notification;
use Carbon\Carbon;

class ScreenController extends Controller
{
    use ApiResponse;

    /**
     * Get homework/assignments for student
     */
    public function homework(Request $request)
    {
        try {
            $user = $request->user();
            $studentId = $user->student_id ?? $user->id;

            // Get homework from database
            $homework = DB::table('assignments')
                ->join('subjects', 'assignments.subject_id', '=', 'subjects.id')
                ->where('assignments.student_id', $studentId)
                ->select([
                    'assignments.*',
                    'subjects.name as subject_name',
                    'subjects.code as subject_code'
                ])
                ->orderBy('assignments.due_date', 'desc')
                ->get();

            $formattedHomework = $homework->map(function($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'description' => $item->description,
                    'subject' => $item->subject_name,
                    'subject_code' => $item->subject_code,
                    'due_date' => $item->due_date,
                    'submission_date' => $item->submission_date,
                    'status' => $item->status ?? 'pending',
                    'marks' => $item->marks ?? 0,
                    'attachment' => $item->attachment,
                    'created_at' => $item->created_at
                ];
            });

            return $this->successResponse($formattedHomework, 'Homework retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve homework: ' . $e->getMessage());
        }
    }

    /**
     * Submit homework
     */
    public function homeworkSubmit(Request $request)
    {
        try {
            $user = $request->user();
            $studentId = $user->student_id ?? $user->id;

            $request->validate([
                'assignment_id' => 'required|integer',
                'submission_text' => 'required|string',
                'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240'
            ]);

            $assignmentId = $request->assignment_id;
            $submissionText = $request->submission_text;

            // Handle file upload
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $attachmentPath = $file->storeAs('submissions', $filename, 'public');
            }

            // Update assignment with submission
            DB::table('assignments')
                ->where('id', $assignmentId)
                ->where('student_id', $studentId)
                ->update([
                    'submission_text' => $submissionText,
                    'attachment' => $attachmentPath,
                    'submission_date' => now(),
                    'status' => 'submitted',
                    'updated_at' => now()
                ]);

            return $this->successResponse([], 'Homework submitted successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to submit homework: ' . $e->getMessage());
        }
    }

    /**
     * Get current student language
     */
    public function getstudentcurrentlanguage(Request $request)
    {
        try {
            $user = $request->user();

            return $this->successResponse([
                'language' => $user->language ?? 'en',
                'language_code' => $user->language ?? 'en',
                'language_name' => $this->getLanguageName($user->language ?? 'en')
            ], 'Student language retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve language: ' . $e->getMessage());
        }
    }

    /**
     * Get currency list
     */
    public function getCurrencyList(Request $request)
    {
        try {
            $currencies = [
                ['code' => 'KES', 'name' => 'Kenyan Shilling', 'symbol' => 'KSh'],
                ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$'],
                ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€'],
                ['code' => 'GBP', 'name' => 'British Pound', 'symbol' => '£']
            ];

            return $this->successResponse($currencies, 'Currency list retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve currencies: ' . $e->getMessage());
        }
    }

    /**
     * Update student language
     */
    public function updatestudentlanguage(Request $request)
    {
        try {
            $user = $request->user();

            $request->validate([
                'language' => 'required|string|max:10'
            ]);

            $user->language = $request->language;
            $user->save();

            return $this->successResponse([
                'language' => $user->language,
                'message' => 'Language updated successfully'
            ], 'Language updated successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update language: ' . $e->getMessage());
        }
    }

    /**
     * Update student currency
     */
    public function updatestudentcurrency(Request $request)
    {
        try {
            $user = $request->user();

            $request->validate([
                'currency' => 'required|string|max:10'
            ]);

            $user->currency = $request->currency;
            $user->save();

            return $this->successResponse([
                'currency' => $user->currency,
                'message' => 'Currency updated successfully'
            ], 'Currency updated successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update currency: ' . $e->getMessage());
        }
    }

    /**
     * Get student subjects
     */
    public function getstudentsubject(Request $request)
    {
        try {
            $user = $request->user();
            $studentId = $user->student_id ?? $user->id;

            $subjects = DB::table('student_subjects')
                ->join('subjects', 'student_subjects.subject_id', '=', 'subjects.id')
                ->join('users as teachers', 'subjects.teacher_id', '=', 'teachers.id')
                ->where('student_subjects.student_id', $studentId)
                ->select([
                    'subjects.id',
                    'subjects.name',
                    'subjects.code',
                    'subjects.description',
                    'teachers.name as teacher_name',
                    'teachers.email as teacher_email'
                ])
                ->get();

            return $this->successResponse($subjects, 'Student subjects retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve subjects: ' . $e->getMessage());
        }
    }

    /**
     * Upload document
     */
    public function uploadDocument(Request $request)
    {
        try {
            $user = $request->user();

            $request->validate([
                'document' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            $file = $request->file('document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $filename, 'public');

            DB::table('documents')->insert([
                'user_id' => $user->id,
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $filePath,
                'file_name' => $filename,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return $this->successResponse([
                'file_path' => $filePath,
                'message' => 'Document uploaded successfully'
            ], 'Document uploaded successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to upload document: ' . $e->getMessage());
        }
    }

    /**
     * Get applied discounts
     */
    public function getAppliedDiscounts(Request $request)
    {
        try {
            $user = $request->user();
            $studentId = $user->student_id ?? $user->id;

            $discounts = DB::table('fee_discounts')
                ->join('discount_types', 'fee_discounts.discount_type_id', '=', 'discount_types.id')
                ->where('fee_discounts.student_id', $studentId)
                ->select([
                    'fee_discounts.*',
                    'discount_types.name as discount_name',
                    'discount_types.percentage',
                    'discount_types.amount'
                ])
                ->get();

            return $this->successResponse($discounts, 'Applied discounts retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve discounts: ' . $e->getMessage());
        }
    }

    /**
     * Get processing fees
     */
    public function getProcessingfees(Request $request)
    {
        try {
            $user = $request->user();
            $studentId = $user->student_id ?? $user->id;

            $processingFees = DB::table('processing_fees')
                ->where('student_id', $studentId)
                ->where('status', 'processing')
                ->orderBy('created_at', 'desc')
                ->get();

            return $this->successResponse($processingFees, 'Processing fees retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve processing fees: ' . $e->getMessage());
        }
    }

    /**
     * Lock student panel
     */
    public function lockStudentPanel(Request $request)
    {
        try {
            $user = $request->user();

            // Check if student panel should be locked based on business rules
            $isLocked = $this->shouldLockStudentPanel($user);

            return $this->successResponse([
                'is_lock' => $isLocked ? '1' : '0',
                'message' => $isLocked ? 'Student panel is locked' : 'Student panel is accessible'
            ], 'Lock status retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to check lock status: ' . $e->getMessage());
        }
    }

    /**
     * Get downloads by ID
     */
    public function getDownloadsLinksById(Request $request)
    {
        try {
            $user = $request->user();
            $downloadId = $request->input('download_id');

            $download = DB::table('downloads')
                ->where('id', $downloadId)
                ->where(function($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('is_public', true);
                })
                ->first();

            if (!$download) {
                return $this->errorResponse('Download not found or access denied');
            }

            return $this->successResponse($download, 'Download retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve download: ' . $e->getMessage());
        }
    }

    /**
     * Check student status
     */
    public function checkStudentStatus(Request $request)
    {
        try {
            $user = $request->user();
            $studentId = $user->student_id ?? $user->id;

            $student = DB::table('students')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->where('students.id', $studentId)
                ->select([
                    'students.*',
                    'users.is_active',
                    'users.email_verified_at'
                ])
                ->first();

            $status = [
                'is_active' => $student ? ($student->is_active ? '1' : '0') : '0',
                'is_enrolled' => $student ? '1' : '0',
                'is_verified' => $student && $student->email_verified_at ? '1' : '0',
                'status_message' => $this->getStudentStatusMessage($student)
            ];

            return $this->successResponse($status, 'Student status retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve student status: ' . $e->getMessage());
        }
    }

    /**
     * Get student timeline status
     */
    public function getStudentTimelineStatus(Request $request)
    {
        try {
            $user = $request->user();
            $studentId = $user->student_id ?? $user->id;

            $timelineCount = DB::table('timelines')
                ->where('student_id', $studentId)
                ->count();

            $status = [
                'timeline_enabled' => '1',
                'timeline_count' => $timelineCount,
                'can_add_timeline' => '1',
                'can_edit_timeline' => '1'
            ];

            return $this->successResponse($status, 'Timeline status retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve timeline status: ' . $e->getMessage());
        }
    }

    /**
     * Get offline bank payment status
     */
    public function getOfflineBankPaymentStatus(Request $request)
    {
        try {
            $user = $request->user();
            $studentId = $user->student_id ?? $user->id;

            $offlinePaymentEnabled = DB::table('settings')
                ->where('key', 'offline_payment_enabled')
                ->value('value') ?? '1';

            $pendingPayments = DB::table('offline_payments')
                ->where('student_id', $studentId)
                ->where('status', 'pending')
                ->count();

            $status = [
                'offline_payment_enabled' => $offlinePaymentEnabled,
                'pending_payments' => $pendingPayments,
                'bank_details_available' => '1'
            ];

            return $this->successResponse($status, 'Offline payment status retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve payment status: ' . $e->getMessage());
        }
    }

    /**
     * Get fees discount status
     */
    public function getfeesdiscountstatusStatus(Request $request)
    {
        try {
            $user = $request->user();
            $studentId = $user->student_id ?? $user->id;

            $activeDiscounts = DB::table('fee_discounts')
                ->where('student_id', $studentId)
                ->where('status', 'active')
                ->count();

            $totalDiscountAmount = DB::table('fee_discounts')
                ->where('student_id', $studentId)
                ->where('status', 'active')
                ->sum('amount');

            $status = [
                'discount_available' => $activeDiscounts > 0 ? '1' : '0',
                'active_discounts' => $activeDiscounts,
                'total_discount_amount' => $totalDiscountAmount ?? 0,
                'can_apply_discount' => '1'
            ];

            return $this->successResponse($status, 'Discount status retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve discount status: ' . $e->getMessage());
        }
    }

    /**
     * Get online course settings
     */
    public function getOnlineCourseSettings(Request $request)
    {
        try {
            $settings = [
                'course_enabled' => DB::table('settings')->where('key', 'online_courses_enabled')->value('value') ?? '1',
                'enrollment_enabled' => DB::table('settings')->where('key', 'course_enrollment_enabled')->value('value') ?? '1',
                'payment_required' => DB::table('settings')->where('key', 'course_payment_required')->value('value') ?? '0',
                'certificate_enabled' => DB::table('settings')->where('key', 'course_certificates_enabled')->value('value') ?? '1',
                'max_enrollments' => DB::table('settings')->where('key', 'max_course_enrollments')->value('value') ?? '10'
            ];

            return $this->successResponse($settings, 'Online course settings retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve course settings: ' . $e->getMessage());
        }
    }

    /**
     * Get offline bank payment instructions
     */
    public function getOfflineBankPaymentInstruction(Request $request)
    {
        try {
            $instructions = DB::table('payment_instructions')
                ->where('payment_type', 'offline_bank')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();

            $bankDetails = DB::table('bank_details')
                ->where('is_active', true)
                ->get();

            $data = [
                'instructions' => $instructions,
                'bank_details' => $bankDetails,
                'reference_format' => 'STU{student_id}-{fee_id}-{timestamp}'
            ];

            return $this->successResponse($data, 'Payment instructions retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve payment instructions: ' . $e->getMessage());
        }
    }

    /**
     * Get GMeet settings
     */
    public function getgmeetsettings(Request $request)
    {
        try {
            $settings = [
                'gmeet_enabled' => DB::table('settings')->where('key', 'gmeet_enabled')->value('value') ?? '1',
                'gmeet_api_key' => DB::table('settings')->where('key', 'gmeet_api_key')->value('value') ?? '',
                'auto_join_enabled' => DB::table('settings')->where('key', 'gmeet_auto_join')->value('value') ?? '0',
                'recording_enabled' => DB::table('settings')->where('key', 'gmeet_recording')->value('value') ?? '1'
            ];

            return $this->successResponse($settings, 'GMeet settings retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve GMeet settings: ' . $e->getMessage());
        }
    }

    /**
     * Get Zoom settings
     */
    public function getzoomsettings(Request $request)
    {
        try {
            $settings = [
                'zoom_enabled' => DB::table('settings')->where('key', 'zoom_enabled')->value('value') ?? '1',
                'zoom_api_key' => DB::table('settings')->where('key', 'zoom_api_key')->value('value') ?? '',
                'zoom_secret' => DB::table('settings')->where('key', 'zoom_secret')->value('value') ?? '',
                'waiting_room_enabled' => DB::table('settings')->where('key', 'zoom_waiting_room')->value('value') ?? '1'
            ];

            return $this->successResponse($settings, 'Zoom settings retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve Zoom settings: ' . $e->getMessage());
        }
    }

    /**
     * Get class schedule for today
     */
    public function getClassSchedule(Request $request)
    {
        try {
            $user = $request->user();
            $studentId = $user->student_id ?? $user->id;

            $today = Carbon::today();
            $dayName = $today->format('l'); // Monday, Tuesday, etc.

            $schedule = DB::table('timetables')
                ->join('subjects', 'timetables.subject_id', '=', 'subjects.id')
                ->join('users as teachers', 'subjects.teacher_id', '=', 'teachers.id')
                ->where('timetables.student_id', $studentId)
                ->where('timetables.day_of_week', $dayName)
                ->select([
                    'timetables.*',
                    'subjects.name as subject_name',
                    'subjects.code as subject_code',
                    'teachers.name as teacher_name'
                ])
                ->orderBy('timetables.start_time')
                ->get();

            return $this->successResponse($schedule, 'Today\'s class schedule retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve class schedule: ' . $e->getMessage());
        }
    }

    /**
     * Get course curriculum
     */
    public function coursecurriculum(Request $request)
    {
        try {
            $courseId = $request->input('course_id');

            $curriculum = DB::table('course_curriculum')
                ->join('subjects', 'course_curriculum.subject_id', '=', 'subjects.id')
                ->where('course_curriculum.course_id', $courseId)
                ->select([
                    'course_curriculum.*',
                    'subjects.name as subject_name',
                    'subjects.description as subject_description'
                ])
                ->orderBy('course_curriculum.sequence')
                ->get();

            return $this->successResponse($curriculum, 'Course curriculum retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve curriculum: ' . $e->getMessage());
        }
    }

    /**
     * Get course reviews
     */
    public function getCourseReviews(Request $request)
    {
        try {
            $courseId = $request->input('course_id');

            $reviews = DB::table('course_reviews')
                ->join('users', 'course_reviews.user_id', '=', 'users.id')
                ->where('course_reviews.course_id', $courseId)
                ->select([
                    'course_reviews.*',
                    'users.name as reviewer_name',
                    'users.avatar as reviewer_avatar'
                ])
                ->orderBy('course_reviews.created_at', 'desc')
                ->get();

            $averageRating = DB::table('course_reviews')
                ->where('course_id', $courseId)
                ->avg('rating');

            $data = [
                'reviews' => $reviews,
                'average_rating' => round($averageRating, 2),
                'total_reviews' => $reviews->count()
            ];

            return $this->successResponse($data, 'Course reviews retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve course reviews: ' . $e->getMessage());
        }
    }

    // Helper Methods
    private function getLanguageName($code)
    {
        $languages = [
            'en' => 'English',
            'sw' => 'Swahili',
            'fr' => 'French',
            'es' => 'Spanish'
        ];

        return $languages[$code] ?? 'English';
    }

    private function shouldLockStudentPanel($user)
    {
        // Check various conditions that might lock the student panel
        $studentId = $user->student_id ?? $user->id;

        // Check if student has overdue fees
        $overdueFeesCount = DB::table('fees')
            ->where('student_id', $studentId)
            ->where('due_date', '<', now())
            ->where('status', 'unpaid')
            ->count();

        if ($overdueFeesCount > 0) {
            return true;
        }

        // Check if student is suspended
        $isSuspended = DB::table('students')
            ->where('id', $studentId)
            ->where('status', 'suspended')
            ->exists();

        if ($isSuspended) {
            return true;
        }

        // Check if there are disciplinary issues
        $disciplinaryIssues = DB::table('disciplinary_actions')
            ->where('student_id', $studentId)
            ->where('status', 'active')
            ->where('restriction_type', 'panel_access')
            ->exists();

        return $disciplinaryIssues;
    }

    private function getStudentStatusMessage($student)
    {
        if (!$student) {
            return 'Student record not found';
        }

        if (!$student->is_active) {
            return 'Account is inactive';
        }

        if (!$student->email_verified_at) {
            return 'Email not verified';
        }

        return 'Active';
    }
}
