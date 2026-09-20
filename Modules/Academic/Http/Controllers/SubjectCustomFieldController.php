<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\Subject;
use Modules\Academic\Models\SubjectCustomField;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SubjectCustomFieldController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List custom fields for a subject
     */
    public function index(Request $request, $subjectId)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)->findOrFail($subjectId);
        $fields = $subject->customFields()->orderBy('field_name')->get();

        if ($request->ajax()) {
            return response()->json($fields);
        }

        return view('academic::subjects.custom-fields', compact('subject', 'fields'));
    }

    /**
     * Store a new custom field
     */
    public function store(Request $request, $subjectId)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)->findOrFail($subjectId);

        $validator = Validator::make($request->all(), [
            'field_name' => 'required|string|max:255',
            'field_value' => 'nullable|string|max:2000',
            'field_type' => 'required|string|in:text,textarea,number,boolean,date,json',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $exists = $subject->customFields()->where('field_name', $request->field_name)->exists();
        if ($exists) {
            $msg = 'A custom field with this name already exists for this subject.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        $field = $subject->customFields()->create([
            'field_name' => $request->field_name,
            'field_value' => $request->field_value,
            'field_type' => $request->field_type,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'field' => $field], 201);
        }

        return redirect()->route('academic.subjects.index')
            ->with('success', 'Custom field added successfully!');
    }

    /**
     * Display a single custom field
     */
    public function show($subjectId, $fieldId)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)->findOrFail($subjectId);
        $field = $subject->customFields()->findOrFail($fieldId);

        if (request()->ajax()) {
            return response()->json($field);
        }

        return view('academic::subjects.custom-field-show', compact('subject', 'field'));
    }

    /**
     * Update a custom field
     */
    public function update(Request $request, $subjectId, $fieldId)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)->findOrFail($subjectId);
        $field = $subject->customFields()->findOrFail($fieldId);

        $validator = Validator::make($request->all(), [
            'field_name' => 'required|string|max:255',
            'field_value' => 'nullable|string|max:2000',
            'field_type' => 'required|string|in:text,textarea,number,boolean,date,json',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $duplicate = $subject->customFields()
            ->where('field_name', $request->field_name)
            ->where('id', '!=', $fieldId)
            ->exists();

        if ($duplicate) {
            $msg = 'Another custom field with this name already exists.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        $field->update([
            'field_name' => $request->field_name,
            'field_value' => $request->field_value,
            'field_type' => $request->field_type,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'field' => $field]);
        }

        return redirect()->route('academic.subjects.index')
            ->with('success', 'Custom field updated successfully!');
    }

    /**
     * Remove a custom field
     */
    public function destroy($subjectId, $fieldId)
    {
        $subject = Subject::where('school_id', Auth::user()->school_id)->findOrFail($subjectId);
        $field = $subject->customFields()->findOrFail($fieldId);
        $field->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Custom field deleted.']);
        }

        return redirect()->route('academic.subjects.index')
            ->with('success', 'Custom field deleted successfully!');
    }
}
