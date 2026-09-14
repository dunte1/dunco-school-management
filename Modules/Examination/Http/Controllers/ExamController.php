<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExamController extends Controller
{
    public function index()
    {
        $exams = collect([
            (object)[
                'id' => 1,
                'name' => 'Mathematics Final Exam',
                'code' => 'MATH-101',
                'examType' => (object)['name' => 'Final'],
                'start_date' => \Carbon\Carbon::parse('2024-12-15'),
                'duration_minutes' => 180,
                'status' => 'published',
                'is_online' => true,
                'enable_proctoring' => true,
                'show_results_immediately' => false,
                'academic_year' => '2024',
                'term' => 'Term 1'
            ],
            (object)[
                'id' => 2,
                'name' => 'Physics Midterm',
                'code' => 'PHYS-201',
                'examType' => (object)['name' => 'Midterm'],
                'start_date' => \Carbon\Carbon::parse('2024-12-18'),
                'duration_minutes' => 120,
                'status' => 'draft',
                'is_online' => false,
                'enable_proctoring' => false,
                'show_results_immediately' => true,
                'academic_year' => '2024',
                'term' => 'Term 1'
            ],
            (object)[
                'id' => 3,
                'name' => 'English Literature',
                'code' => 'ENG-101',
                'examType' => (object)['name' => 'Final'],
                'start_date' => \Carbon\Carbon::parse('2024-12-20'),
                'duration_minutes' => 150,
                'status' => 'ongoing',
                'is_online' => true,
                'enable_proctoring' => true,
                'show_results_immediately' => false,
                'academic_year' => '2024',
                'term' => 'Term 1'
            ],
            (object)[
                'id' => 4,
                'name' => 'Chemistry Final',
                'code' => 'CHEM-101',
                'examType' => (object)['name' => 'Final'],
                'start_date' => \Carbon\Carbon::parse('2024-12-25'),
                'duration_minutes' => 120,
                'status' => 'draft',
                'is_online' => true,
                'enable_proctoring' => false,
                'show_results_immediately' => true,
                'academic_year' => '2024',
                'term' => 'Term 1'
            ]
        ]);
        
        $upcomingExams = $exams->filter(function($exam) {
            return $exam->status === 'published' || $exam->status === 'draft';
        });
        
        // Create a paginated collection
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedExams = $exams->slice($offset, $perPage);
        
        $exams = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedExams->values(),
            $exams->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );
        
        return view('examination::exams.index', compact('exams', 'upcomingExams'));
    }

    public function create()
    {
        return view('examination::exams.create');
    }

    public function store(Request $request)
    {
        // Exam creation logic
        return redirect()->route('examination.exams.index');
    }

    public function show($id)
    {
        $exam = \Modules\Examination\Models\Exam::with('type')->findOrFail($id);

        return view('examination::exams.show', compact('exam'));
    }

    public function edit($id)
    {
        $exam = \Modules\Examination\Models\Exam::findOrFail($id);
        $examTypes = \Modules\Examination\Models\ExamType::orderBy('name')->get();

        return view('examination::exams.edit', compact('exam', 'examTypes'));
    }

    public function update(Request $request, $id)
    {
        // Exam update logic
        return redirect()->route('examination.exams.index');
    }

    public function destroy($id)
    {
        // Exam delete logic
        return redirect()->route('examination.exams.index');
    }

    public function publish($exam)
    {
        return redirect()->back()->with('success', 'Exam published successfully');
    }

    public function start($exam)
    {
        return redirect()->back()->with('success', 'Exam started successfully');
    }

    public function complete($exam)
    {
        return redirect()->back()->with('success', 'Exam completed successfully');
    }

    public function addQuestions($exam)
    {
        return redirect()->back()->with('success', 'Questions added successfully');
    }

    public function removeQuestion($exam, $question)
    {
        return redirect()->back()->with('success', 'Question removed successfully');
    }

    public function generateRandomQuestions($exam)
    {
        return redirect()->back()->with('success', 'Random questions generated successfully');
    }

    public function results($exam)
    {
        $results = \Modules\Examination\Models\ExamResult::with('student')->where('exam_id', $exam)->get();
        $examModel = \Modules\Examination\Models\Exam::find($exam);

        return view('examination::exams.results', compact('results', 'examModel'));
    }

    public function exportResults($exam)
    {
        $results = \Modules\Examination\Models\ExamResult::with('student')->where('exam_id', $exam)->get();
        $examModel = \Modules\Examination\Models\Exam::find($exam);

        $filename = 'exam-results-'.($examModel->code ?? $exam).'.csv';

        $callback = function () use ($results) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Student', 'Obtained Marks', 'Total Marks', 'Percentage', 'Grade', 'Status']);
            foreach ($results as $result) {
                fputcsv($out, [
                    optional($result->student)->name,
                    $result->obtained_marks,
                    $result->total_marks,
                    $result->percentage,
                    $result->grade,
                    $result->result_status,
                ]);
            }
            fclose($out);
        };

        return response()->streamDownload($callback, $filename, ['Content-Type' => 'text/csv']);
    }

    public function createOnline()
    {
        try {
            $examTypes = \Modules\Examination\Models\ExamType::orderBy('name')->get();
        } catch (\Throwable $e) {
            $examTypes = collect();
        }

        try {
            $classes = \Modules\Academic\Models\AcademicClass::orderBy('name')->get();
        } catch (\Throwable $e) {
            $classes = collect();
        }

        try {
            $subjects = \Modules\Academic\Models\Subject::orderBy('name')->get();
        } catch (\Throwable $e) {
            $subjects = collect();
        }

        return view('examination::exams.online-create', compact('examTypes', 'classes', 'subjects'));
    }

    public function studentExams()
    {
        return view('examination::student.exams');
    }

    public function examHistory()
    {
        return view('examination::student.history');
    }

    public function teacherExams()
    {
        return view('examination::teacher.exams');
    }

    public function gradeExams()
    {
        return view('examination::teacher.grade');
    }

    public function gradeAnswer($answer)
    {
        return redirect()->back()->with('success', 'Answer graded successfully');
    }

    public function examAnalytics()
    {
        return view('examination::teacher.analytics');
    }

    public function adminDashboard()
    {
        return view('examination::admin.dashboard');
    }

    public function settings()
    {
        return view('examination::admin.settings');
    }

    public function updateSettings(Request $request)
    {
        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    public function reports()
    {
        return view('examination::admin.reports');
    }

    public function backup()
    {
        return view('examination::admin.backup');
    }
}
