<?php

namespace Modules\Hostel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBedRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'room_id' => 'required|integer|exists:rooms,id',
            'bed_number' => 'required|string|max:255',
            'status' => 'required|in:available,occupied,maintenance,reserved',
            'student_id' => 'nullable|integer|exists:users,id',
            'price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ];
    }
} 