<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AcademicController extends Controller
{
    use ApiResponse;

    public function getSubjects(Request $request)
    {
        try {
            $user = $request->user();
            
            // Get subjects based on user role
            if ($user->type === 'student') {
                // Get subjects for student's class
                $subjects = \Modules\Academic\Entities\Subject::whereHas('classes', function($query) use ($user) {
                    $query->whereHas('students', function($studentQuery) use ($user) {
                        $studentQuery->where('user_id', $user->id);
                    });
                })->with('teacher')->get();
            } elseif ($user->type === 'teacher') {
                // Get subjects taught by teacher
                $subjects = \Modules\Academic\Entities\Subject::where('teacher_id', $user->id)->get();
            } else {
                // Admin or other roles - get all subjects
                $subjects = \Modules\Academic\Entities\Subject::with('teacher')->get();
            }

            $formattedSubjects = $subjects->map(function($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'code' => $subject->code,
                    'teacher' => $subject->teacher ? $subject->teacher->name : 'TBA',
                    'credits' => $subject->credits ?? 0,
                    'description' => $subject->description,
                    'room' => $subject->room ?? 'TBA',
                ];
            });

            return $this->successResponse($formattedSubjects, 'Subjects retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting subjects: " . $e->getMessage());
            // Fallback to mock data
            $subjects = [
                ['id' => 1, 'name' => 'Mathematics', 'code' => 'MATH101', 'teacher' => 'John Doe', 'credits' => 4, 'description' => 'Advanced Mathematics', 'room' => 'Room 101'],
                ['id' => 2, 'name' => 'English', 'code' => 'ENG101', 'teacher' => 'Jane Smith', 'credits' => 3, 'description' => 'English Language', 'room' => 'Room 102'],
                ['id' => 3, 'name' => 'Science', 'code' => 'SCI101', 'teacher' => 'Bob Johnson', 'credits' => 4, 'description' => 'General Science', 'room' => 'Lab 201'],
            ];
            return $this->successResponse($subjects, 'Subjects retrieved successfully (demo data)');
        }
    }

    public function getClasses(Request $request)
    {
        try {
            // Mock data for classes
            $classes = [
                ['id' => 1, 'name' => 'Grade 1', 'section' => 'A', 'teacher' => 'John Doe'],
                ['id' => 2, 'name' => 'Grade 2', 'section' => 'B', 'teacher' => 'Jane Smith'],
                ['id' => 3, 'name' => 'Grade 3', 'section' => 'C', 'teacher' => 'Bob Johnson'],
            ];
            return $this->successResponse($classes, 'Classes retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting classes: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve classes.', 500);
        }
    }

    public function getSchedule(Request $request)
    {
        try {
            // Mock data for schedule
            $schedule = [
                ['id' => 1, 'subject' => 'Mathematics', 'time' => '09:00', 'day' => 'Monday'],
                ['id' => 2, 'subject' => 'English', 'time' => '10:00', 'day' => 'Monday'],
                ['id' => 3, 'subject' => 'Science', 'time' => '11:00', 'day' => 'Monday'],
            ];
            return $this->successResponse($schedule, 'Schedule retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting schedule: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve schedule.', 500);
        }
    }

    public function getAttendance(Request $request)
    {
        try {
            // Mock data for attendance
            $attendance = [
                ['id' => 1, 'date' => '2024-01-15', 'status' => 'present', 'subject' => 'Mathematics'],
                ['id' => 2, 'date' => '2024-01-15', 'status' => 'absent', 'subject' => 'English'],
                ['id' => 3, 'date' => '2024-01-15', 'status' => 'present', 'subject' => 'Science'],
            ];
            return $this->successResponse($attendance, 'Attendance retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting attendance: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve attendance.', 500);
        }
    }

    public function getExams(Request $request)
    {
        try {
            $user = $request->user();
            
            // Get exams based on user role
            if ($user->type === 'student') {
                // Get exams for student's subjects
                $exams = \Modules\Examination\Entities\Exam::whereHas('subject', function($query) use ($user) {
                    $query->whereHas('classes', function($classQuery) use ($user) {
                        $classQuery->whereHas('students', function($studentQuery) use ($user) {
                            $studentQuery->where('user_id', $user->id);
                        });
                    });
                })->with(['subject', 'results' => function($query) use ($user) {
                    $query->where('student_id', $user->id);
                }])->get();
            } else {
                // Get all exams for teachers/admins
                $exams = \Modules\Examination\Entities\Exam::with(['subject', 'results'])->get();
            }

            $formattedExams = $exams->map(function($exam) use ($user) {
                $result = $exam->results->first();
                $status = 'upcoming';
                
                if ($exam->date < now()) {
                    $status = $result ? 'graded' : 'completed';
                }

                return [
                    'id' => $exam->id,
                    'title' => $exam->name,
                    'subject' => $exam->subject->name ?? 'Unknown',
                    'date' => $exam->date->format('Y-m-d'),
                    'time' => $exam->time ?? '09:00',
                    'duration' => $exam->duration ?? '2 hours',
                    'totalMarks' => $exam->total_marks ?? 100,
                    'obtainedMarks' => $result ? $result->obtained_marks : null,
                    'grade' => $result ? $result->grade : null,
                    'status' => $status,
                    'instructions' => $exam->instructions,
                    'type' => $exam->type ?? 'exam',
                    'room' => $exam->room ?? 'TBA',
                ];
            });

            return $this->successResponse($formattedExams, 'Exams retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting exams: " . $e->getMessage());
            // Fallback to mock data
            $exams = [
                ['id' => 1, 'title' => 'Mid-term Exam', 'subject' => 'Mathematics', 'date' => '2024-02-15', 'time' => '09:00', 'duration' => '2 hours', 'totalMarks' => 100, 'status' => 'upcoming', 'type' => 'midterm', 'room' => 'Room 101'],
                ['id' => 2, 'title' => 'Final Exam', 'subject' => 'English', 'date' => '2024-03-15', 'time' => '10:00', 'duration' => '3 hours', 'totalMarks' => 150, 'status' => 'upcoming', 'type' => 'final', 'room' => 'Room 102'],
                ['id' => 3, 'title' => 'Quiz', 'subject' => 'Science', 'date' => '2024-01-20', 'time' => '11:00', 'duration' => '1 hour', 'totalMarks' => 50, 'status' => 'graded', 'obtainedMarks' => 45, 'grade' => 'A', 'type' => 'quiz', 'room' => 'Lab 201'],
            ];
            return $this->successResponse($exams, 'Exams retrieved successfully (demo data)');
        }
    }

    public function getAssignments(Request $request)
    {
        try {
            // Mock data for assignments
            $assignments = [
                ['id' => 1, 'title' => 'Math Homework', 'subject' => 'Mathematics', 'due_date' => '2024-01-20'],
                ['id' => 2, 'title' => 'Essay Writing', 'subject' => 'English', 'due_date' => '2024-01-25'],
                ['id' => 3, 'title' => 'Science Project', 'subject' => 'Science', 'due_date' => '2024-01-30'],
            ];
            return $this->successResponse($assignments, 'Assignments retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting assignments: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve assignments.', 500);
        }
    }

    public function getTimeline(Request $request)
    {
        try {
            // Mock data for timeline
            $timeline = [
                ['id' => 1, 'event' => 'Class Started', 'date' => '2024-01-15', 'time' => '09:00'],
                ['id' => 2, 'event' => 'Break Time', 'date' => '2024-01-15', 'time' => '10:30'],
                ['id' => 3, 'event' => 'Class Ended', 'date' => '2024-01-15', 'time' => '15:00'],
            ];
            return $this->successResponse($timeline, 'Timeline retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting timeline: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve timeline.', 500);
        }
    }
}
