<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Examination\Imports\QuestionsImport;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Modules\Examination\Models\QuestionCategory;

class QuestionImportController extends Controller
{
    public function index()
    {
        return view('examination::questions.import.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB Max
        ]);

        try {
            Excel::import(new QuestionsImport, $request->file('file'));
            return redirect()->route('examination.questions.index')->with('success', 'Questions imported successfully!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing questions: ' . $e->getMessage());
        }
    }

    public function downloadTemplate($format = 'xlsx')
    {
        $headers = [
            'question_text', 'type', 'category_name', 'options', 'correct_answers',
            'explanation', 'marks', 'time_limit_seconds', 'difficulty', 'tags',
            'file_upload'
        ];

        if ($format === 'csv') {
            return response()->streamDownload(function () use ($headers) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $headers);
                fclose($file);
            }, 'question_import_template.csv', [
                'Content-Type' => 'text/csv',
            ]);
        }

        // For XLSX, a more complex library might be needed, or a simple CSV-like structure
        // For simplicity, we'll just provide a CSV for now or indicate XLSX is not fully supported without a package
        return response()->streamDownload(function () use ($headers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fclose($file);
        }, 'question_import_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function validateFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $path = $request->file('file')->store('temp');
        $fullPath = storage_path('app/' . $path);

        $rows = Excel::toCollection(new QuestionsImport, $fullPath)->first();
        $errors = [];

        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Skip header

            $data = $row->toArray();
            $validator = Validator::make($data, [
                'question_text' => 'required|string',
                'type' => 'required|in:mcq,fill_blank,essay,coding,matching,true_false,short_answer',
                'category_name' => 'required|string|exists:question_categories,name',
                'options' => 'nullable|string', // JSON string
                'correct_answers' => 'nullable|string', // JSON string
                'marks' => 'required|numeric|min:0',
                'difficulty' => 'required|in:easy,medium,hard',
            ]);

            if ($validator->fails()) {
                $errors['row_' . ($index + 1)] = $validator->errors()->all();
            }
        }

        unlink($fullPath); // Clean up temp file

        if (!empty($errors)) {
            return response()->json(['success' => false, 'errors' => $errors], 422);
        }

        return response()->json(['success' => true, 'message' => 'File validated successfully.']);
    }
}