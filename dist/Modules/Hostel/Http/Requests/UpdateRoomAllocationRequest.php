<?php

namespace Modules\Hostel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomAllocationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'required|integer|exists:users,id',
            'allocation_type' => 'required|in:auto,manual,request',
            'bed_id' => 'required_if:allocation_type,manual|nullable|integer|exists:beds,id',
            'preferences' => 'nullable|array',
            'check_in' => 'nullable|date',
            'check_out' => 'nullable|date',
            'notes' => 'nullable|string',
            'document' => 'nullable|string|max:255',
            'status' => 'required|in:active,checked_out,swapped,cancelled',
        ];
    }
} 