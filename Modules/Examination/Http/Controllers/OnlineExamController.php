<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Models\Exam;
use Modules\Examination\Models\ExamAttempt;

class OnlineExamController extends Controller
{
    public function index()
    {
        $exams = Exam::where('is_online', true)->orderByDesc('start_date')->get();

        return view('examination::online.index', compact('exams'));
    }

    public function startExam($exam)
    {
        $exam = Exam::findOrFail($exam);

        return view('examination::online.start', compact('exam'));
    }

    public function takeExam($exam)
    {
        $exam = Exam::findOrFail($exam);
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', auth()->id())
            ->latest()
            ->first();

        return view('examination::online.exam', compact('exam', 'attempt'));
    }

    public function getExamData($attempt)
    {
        $attempt = ExamAttempt::with('exam.questions')->find($attempt);

        return response()->json(['data' => $attempt]);
    }

    public function startAttempt($attempt)
    {
        return response()->json(['status' => 'started']);
    }

    public function saveAnswer($attempt)
    {
        return response()->json(['status' => 'saved']);
    }

    public function submitExam($attempt)
    {
        return response()->json(['status' => 'submitted']);
    }

    public function logProctoringEvent($attempt)
    {
        return response()->json(['status' => 'logged']);
    }

    public function getProctoringStatus($attempt)
    {
        return response()->json(['status' => 'active']);
    }

    public function examResult($attempt)
    {
        $attempt = ExamAttempt::with('exam')->findOrFail($attempt);
        $answers = $attempt->answers;
        $result = $attempt->result;
        $showCorrect = false;
        $studentFile = null;

        return view('examination::online.result', compact('attempt', 'answers', 'result', 'showCorrect', 'studentFile'));
    }

    public function downloadAnswerFile($answer)
    {
        $answer = \Modules\Examination\Models\ExamAnswer::findOrFail($answer);

        if (! $answer->file_path || ! \Illuminate\Support\Facades\Storage::disk('local')->exists($answer->file_path)) {
            abort(404, 'Answer file not found.');
        }

        return \Illuminate\Support\Facades\Storage::disk('local')->download($answer->file_path);
    }

    public function heartbeat()
    {
        return response()->json(['status' => 'alive']);
    }

    public function screenshot()
    {
        return response()->json(['status' => 'captured']);
    }

    public function autoSave($attempt)
    {
        return response()->json(['status' => 'saved']);
    }

    public function examStatus($attempt)
    {
        return response()->json(['status' => 'active']);
    }

    public function submit(Request $request, $examId)
    {
        return redirect()->route('examination.online.index');
    }

    public function result($examId)
    {
        return view('examination::online.result', [
            'attempt' => null,
            'answers' => collect(),
            'result' => null,
            'showCorrect' => false,
            'studentFile' => null,
        ]);
    }

    public function proctoring($examId)
    {
        $exam = Exam::find($examId);

        return view('examination::online.proctoring', compact('exam', 'examId'));
    }
}
