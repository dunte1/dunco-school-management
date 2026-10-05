<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\Exam;
use Modules\Academic\Models\ExamResult;
use Modules\Academic\Models\Student;

class ExamController extends Controller
{
    public function results(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            // Android compatibility: return empty results instead of 404
            return response()->json(['results' => []]);
        }

        $results = ExamResult::where('student_id', $student->id)
            ->with(['exam', 'subject'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($result) {
                return [
                    'id' => $result->id,
                    'exam_name' => $result->exam->name ?? 'N/A',
                    'subject' => $result->subject->name ?? 'N/A',
                    'score' => $result->score,
                    'max_score' => $result->max_score ?? 100,
                    'percentage' => $result->max_score > 0 ? round(($result->score / $result->max_score) * 100, 2) : 0,
                    'grade' => $this->calculateGrade($result->score, $result->max_score ?? 100),
                    'exam_date' => $result->exam->start_date ?? $result->created_at->format('Y-m-d'),
                    'remarks' => $result->remarks,
                ];
            });

        return response()->json(['results' => $results]);
    }

    public function upcoming(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            // Android compatibility: return empty list instead of 404
            return response()->json(['exams' => []]);
        }

        $upcomingExams = Exam::where('school_id', $user->school_id)
            ->where('start_date', '>=', now())
            ->where('class_id', $student->class_id)
            ->with('subject')
            ->orderBy('start_date')
            ->get()
            ->map(function($exam) {
                return [
                    'id' => $exam->id,
                    'name' => $exam->name,
                    'subject' => $exam->subject->name ?? 'N/A',
                    'type' => $exam->type,
                    'start_date' => $exam->start_date,
                    'end_date' => $exam->end_date,
                    'duration' => $exam->duration ?? 'N/A',
                    'venue' => $exam->venue ?? 'TBD',
                    'instructions' => $exam->instructions,
                    'days_until' => now()->diffInDays($exam->start_date, false),
                ];
            });

        return response()->json(['exams' => $upcomingExams]);
    }

    public function schedule(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            // Android compatibility: return empty grouped schedule instead of 404
            return response()->json(['schedule' => []]);
        }

        $exams = Exam::where('school_id', $user->school_id)
            ->where('class_id', $student->class_id)
            ->with('subject')
            ->orderBy('start_date')
            ->get()
            ->groupBy(function($exam) {
                return $exam->start_date->format('Y-m');
            })
            ->map(function($monthExams) {
                return $monthExams->map(function($exam) {
                    return [
                        'id' => $exam->id,
                        'name' => $exam->name,
                        'subject' => $exam->subject->name ?? 'N/A',
                        'type' => $exam->type,
                        'start_date' => $exam->start_date,
                        'end_date' => $exam->end_date,
                        'venue' => $exam->venue ?? 'TBD',
                        'status' => $exam->start_date > now() ? 'upcoming' : ($exam->end_date < now() ? 'completed' : 'ongoing'),
                    ];
                });
            });

        return response()->json(['schedule' => $exams]);
    }

    private function calculateGrade($score, $maxScore)
    {
        $percentage = ($score / $maxScore) * 100;
        
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B';
        if ($percentage >= 60) return 'C';
        if ($percentage >= 50) return 'D';
        return 'F';
    }
}
