<?php

namespace Modules\Hostel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomAllocationRequest extends FormRequest
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
            'notes' => 'nullable|string',
            'document' => 'nullable|string|max:255',
        ];
    }
} 