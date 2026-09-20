<?php

namespace Modules\Examination\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Examination\Models\Exam;

class ExaminationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('examination::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('examination::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:exams,code',
                'exam_type_id' => 'required|exists:exam_types,id',
                'description' => 'nullable|string',
                'academic_year' => 'required|string|max:50',
                'term' => 'required|string|max:50',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'total_marks' => 'required|numeric|min:0',
                'passing_marks' => 'required|numeric|min:0',
            ]);

            $validated['status'] = 'draft';
            $validated['is_active'] = true;

            $exam = Exam::create($validated);

            return redirect()->route('examination.index')
                ->with('success', 'Exam created successfully.');
        } catch (\Exception $e) {
            Log::error('ExaminationController: Failed to store exam - ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create exam: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $exam = Exam::findOrFail($id);
            return view('examination::show', compact('exam'));
        } catch (\Exception $e) {
            Log::error('ExaminationController: Failed to show exam - ' . $e->getMessage());
            return redirect()->route('examination.index')
                ->with('error', 'Exam not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $exam = Exam::findOrFail($id);
            return view('examination::edit', compact('exam'));
        } catch (\Exception $e) {
            Log::error('ExaminationController: Failed to edit exam - ' . $e->getMessage());
            return redirect()->route('examination.index')
                ->with('error', 'Exam not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $exam = Exam::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:exams,code,' . $id,
                'exam_type_id' => 'required|exists:exam_types,id',
                'description' => 'nullable|string',
                'academic_year' => 'required|string|max:50',
                'term' => 'required|string|max:50',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'total_marks' => 'required|numeric|min:0',
                'passing_marks' => 'required|numeric|min:0',
                'status' => 'required|in:draft,published,ongoing,completed,archived',
            ]);

            $exam->update($validated);

            return redirect()->route('examination.index')
                ->with('success', 'Exam updated successfully.');
        } catch (\Exception $e) {
            Log::error('ExaminationController: Failed to update exam - ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update exam: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $exam = Exam::findOrFail($id);

            if ($exam->attempts()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete exam with existing attempts.');
            }

            $exam->questions()->detach();
            $exam->delete();

            return redirect()->route('examination.index')
                ->with('success', 'Exam deleted successfully.');
        } catch (\Exception $e) {
            Log::error('ExaminationController: Failed to destroy exam - ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete exam: ' . $e->getMessage());
        }
    }
}
