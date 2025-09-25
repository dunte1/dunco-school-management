<?php

namespace Modules\Hostel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'required|integer|exists:users,id',
            'warden_id' => 'nullable|integer|exists:users,id',
            'reason' => 'required|string|max:255',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'status' => 'required|in:pending,approved,rejected,cancelled',
            'emergency_contact' => 'nullable|string|max:255',
            'guardian_notified' => 'boolean',
            'notes' => 'nullable|string',
        ];
    }
} 