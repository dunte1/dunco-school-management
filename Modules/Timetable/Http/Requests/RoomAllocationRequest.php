<?php

namespace Modules\Timetable\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomAllocationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'room_id' => 'required|integer|exists:rooms,id',
            'class_schedule_id' => 'required|integer|exists:class_schedules,id',
            'allocation_date' => 'required|date_format:Y-m-d',
        ];
    }
} 