<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Models\ExamRemark;

class RemarkController extends Controller
{
    public function index(Request $request)
    {
        $query = ExamRemark::query();
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        return view('examination::results.remarks.index', ['remarks' => $query->orderByDesc('id')->paginate(20)]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required','integer'],
            'student_id' => ['required','integer'],
            'remark' => ['required','string'],
        ]);
        $data['requested_by'] = $request->user()->id;
        ExamRemark::create($data);
        return back()->with('success', 'Remark requested');
    }

    public function update(Request $request, ExamRemark $remark)
    {
        $data = $request->validate([
            'status' => ['required','in:requested,reviewing,resolved'],
            'assigned_to' => ['nullable','integer'],
            'resolution' => ['nullable','string']
        ]);
        $remark->fill($data)->save();
        // TODO: notify on status change
        return back()->with('success', 'Remark updated');
    }
}


