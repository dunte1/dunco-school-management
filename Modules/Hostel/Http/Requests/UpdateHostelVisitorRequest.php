<?php

namespace Modules\Hostel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHostelVisitorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'hostel_id' => 'required|integer|exists:hostels,id',
            'student_id' => 'required|integer|exists:users,id',
            'visitor_name' => 'required|string|max:255',
            'visitor_contact' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:255',
            'time_in' => 'required|date',
            'time_out' => 'nullable|date|after_or_equal:time_in',
            'pass_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }
} 