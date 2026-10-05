<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Models\Exam;
use Modules\Examination\Models\ExamSection;
use Modules\Examination\Models\Question;
use Modules\Examination\Models\ExamQuestion;

class ExamSectionController extends Controller
{
    public function index(Exam $exam)
    {
        try {
            $sections = $exam->sections()->orderBy('order')->get();
            return view('examination::sections.index', compact('exam', 'sections'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading exam sections: ' . $e->getMessage());
        }
    }

    public function create(Exam $exam)
    {
        return view('examination::sections.create', compact('exam'));
    }

    public function store(Request $request, Exam $exam)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_marks' => 'required|numeric|min:0',
            'is_optional' => 'boolean',
        ]);

        $exam->sections()->create([
            'name' => $request->name,
            'description' => $request->description,
            'total_marks' => $request->total_marks,
            'is_optional' => $request->boolean('is_optional'),
            'order' => $exam->sections()->count(), // Simple ordering
        ]);

        return redirect()->route('examination.sections.index', $exam)->with('success', 'Exam section created successfully.');
    }

    public function edit(Exam $exam, ExamSection $section)
    {
        return view('examination::sections.edit', compact('exam', 'section'));
    }

    public function update(Request $request, Exam $exam, ExamSection $section)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_marks' => 'required|numeric|min:0',
            'is_optional' => 'boolean',
        ]);

        $section->update([
            'name' => $request->name,
            'description' => $request->description,
            'total_marks' => $request->total_marks,
            'is_optional' => $request->boolean('is_optional'),
        ]);

        return redirect()->route('examination.sections.index', $exam)->with('success', 'Exam section updated successfully.');
    }

    public function destroy(Exam $exam, ExamSection $section)
    {
        $section->delete();
        return redirect()->route('examination.sections.index', $exam)->with('success', 'Exam section deleted successfully.');
    }

    public function reorder(Request $request, Exam $exam)
    {
        $request->validate([
            'section_ids' => 'required|array',
            'section_ids.*' => 'exists:exam_sections,id',
        ]);

        foreach ($request->section_ids as $index => $id) {
            ExamSection::where('id', $id)->where('exam_id', $exam->id)->update(['order' => $index]);
        }

        return response()->json(['message' => 'Sections reordered successfully.']);
    }

    public function addQuestions(Exam $exam, ExamSection $section)
    {
        $questions = Question::all(); // Or filter by category/difficulty
        $sectionQuestions = $section->questions->pluck('question_id')->toArray();
        return view('examination::sections.add-questions', compact('exam', 'section', 'questions', 'sectionQuestions'));
    }

    public function storeQuestions(Request $request, Exam $exam, ExamSection $section)
    {
        $request->validate([
            'question_ids' => 'array',
            'question_ids.*' => 'exists:questions,id',
        ]);

        // Detach existing questions not in the new list
        $section->questions()->whereNotIn('question_id', $request->question_ids ?? [])->delete();

        // Attach new questions
        foreach ($request->question_ids ?? [] as $questionId) {
            ExamQuestion::firstOrCreate([
                'exam_id' => $exam->id,
                'exam_section_id' => $section->id,
                'question_id' => $questionId,
            ]);
        }

        return redirect()->route('examination.sections.index', $exam)->with('success', 'Questions added to section successfully.');
    }
}