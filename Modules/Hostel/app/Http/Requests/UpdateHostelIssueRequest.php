<?php

namespace Modules\Hostel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHostelIssueRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'room_id' => 'nullable|integer|exists:rooms,id',
            'bed_id' => 'nullable|integer|exists:beds,id',
            'student_id' => 'nullable|integer|exists:users,id',
            'reported_by' => 'required|integer|exists:users,id',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'issue_type' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,in_progress,resolved,closed',
            'resolved_at' => 'nullable|date',
            'resolution_notes' => 'nullable|string',
        ];
    }
} 