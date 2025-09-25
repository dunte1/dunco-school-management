<?php

namespace Modules\Hostel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHostelRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Add policy logic if needed
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'gender_restriction' => 'required|in:male,female,mixed',
            'school_id' => 'nullable|integer|exists:schools,id',
            'description' => 'nullable|string',
        ];
    }
} 