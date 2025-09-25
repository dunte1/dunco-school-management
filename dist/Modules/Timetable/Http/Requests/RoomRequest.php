<?php

namespace Modules\Timetable\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
        ];
    }
} 