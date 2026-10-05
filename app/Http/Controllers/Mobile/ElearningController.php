<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ElearningController extends Controller
{
    use ApiResponse;

    public function getHomework(Request $request)
    {
        try {
            // Mock data for homework
            $homework = [
                ['id' => 1, 'title' => 'Math Problems', 'subject' => 'Mathematics', 'due_date' => '2024-01-20'],
                ['id' => 2, 'title' => 'Essay Writing', 'subject' => 'English', 'due_date' => '2024-01-25'],
                ['id' => 3, 'title' => 'Science Experiment', 'subject' => 'Science', 'due_date' => '2024-01-30'],
            ];
            return $this->successResponse($homework, 'Homework retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting homework: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve homework.', 500);
        }
    }

    public function getAssignments(Request $request)
    {
        try {
            // Mock data for assignments
            $assignments = [
                ['id' => 1, 'title' => 'Online Quiz', 'subject' => 'Mathematics', 'due_date' => '2024-01-20'],
                ['id' => 2, 'title' => 'Reading Assignment', 'subject' => 'English', 'due_date' => '2024-01-25'],
                ['id' => 3, 'title' => 'Lab Report', 'subject' => 'Science', 'due_date' => '2024-01-30'],
            ];
            return $this->successResponse($assignments, 'Assignments retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting assignments: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve assignments.', 500);
        }
    }

    public function getLessonPlans(Request $request)
    {
        try {
            // Mock data for lesson plans
            $lessonPlans = [
                ['id' => 1, 'title' => 'Introduction to Algebra', 'subject' => 'Mathematics', 'date' => '2024-01-15'],
                ['id' => 2, 'title' => 'Poetry Analysis', 'subject' => 'English', 'date' => '2024-01-16'],
                ['id' => 3, 'title' => 'Photosynthesis', 'subject' => 'Science', 'date' => '2024-01-17'],
            ];
            return $this->successResponse($lessonPlans, 'Lesson plans retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting lesson plans: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve lesson plans.', 500);
        }
    }

    public function getOnlineExams(Request $request)
    {
        try {
            // Mock data for online exams
            $exams = [
                ['id' => 1, 'title' => 'Math Quiz', 'subject' => 'Mathematics', 'duration' => '60 minutes'],
                ['id' => 2, 'title' => 'English Test', 'subject' => 'English', 'duration' => '90 minutes'],
                ['id' => 3, 'title' => 'Science Exam', 'subject' => 'Science', 'duration' => '120 minutes'],
            ];
            return $this->successResponse($exams, 'Online exams retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting online exams: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve online exams.', 500);
        }
    }

    public function getDownloadCenter(Request $request)
    {
        try {
            // Mock data for download center
            $downloads = [
                ['id' => 1, 'name' => 'Math Textbook', 'type' => 'PDF', 'size' => '2.5 MB'],
                ['id' => 2, 'name' => 'English Workbook', 'type' => 'PDF', 'size' => '1.8 MB'],
                ['id' => 3, 'name' => 'Science Lab Manual', 'type' => 'PDF', 'size' => '3.2 MB'],
            ];
            return $this->successResponse($downloads, 'Download center items retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting download center: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve download center items.', 500);
        }
    }

    public function getOnlineCourses(Request $request)
    {
        try {
            // Mock data for online courses
            $courses = [
                ['id' => 1, 'title' => 'Advanced Mathematics', 'instructor' => 'Dr. Smith', 'duration' => '12 weeks'],
                ['id' => 2, 'title' => 'Creative Writing', 'instructor' => 'Prof. Johnson', 'duration' => '8 weeks'],
                ['id' => 3, 'title' => 'Physics Fundamentals', 'instructor' => 'Dr. Brown', 'duration' => '10 weeks'],
            ];
            return $this->successResponse($courses, 'Online courses retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting online courses: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve online courses.', 500);
        }
    }

    public function getVideoCalls(Request $request)
    {
        try {
            // Mock data for video calls
            $videoCalls = [
                ['id' => 1, 'title' => 'Math Tutoring', 'instructor' => 'Mr. Davis', 'time' => '14:00'],
                ['id' => 2, 'title' => 'English Discussion', 'instructor' => 'Ms. Wilson', 'time' => '15:00'],
                ['id' => 3, 'title' => 'Science Lab', 'instructor' => 'Dr. Taylor', 'time' => '16:00'],
            ];
            return $this->successResponse($videoCalls, 'Video calls retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting video calls: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve video calls.', 500);
        }
    }
}
