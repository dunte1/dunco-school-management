<?php

namespace Modules\Timetable\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Timetable\Models\ClassSchedule;

class ClassScheduleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'academic_class_id' => 'required|integer|exists:academic_classes,id',
            'teacher_id' => 'required|integer|exists:staff,id',
            'room_id' => 'required|integer|exists:rooms,id',
            'timetable_id' => 'required|integer|exists:timetables,id',
            'day_of_week' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();
            // Teacher conflict
            $teacherConflict = ClassSchedule::where('teacher_id', $data['teacher_id'])
                ->where('day_of_week', $data['day_of_week'])
                ->where(function($q) use ($data) {
                    $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                      ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                      ->orWhere(function($q2) use ($data) {
                          $q2->where('start_time', '<=', $data['start_time'])
                             ->where('end_time', '>=', $data['end_time']);
                      });
                })
                ->when($this->route('class_schedule'), function($q) {
                    $q->where('id', '!=', $this->route('class_schedule'));
                })
                ->exists();
            if ($teacherConflict) {
                $validator->errors()->add('teacher_id', 'This teacher is already scheduled for another class at this time.');
            }
            // Room conflict
            $roomConflict = ClassSchedule::where('room_id', $data['room_id'])
                ->where('day_of_week', $data['day_of_week'])
                ->where(function($q) use ($data) {
                    $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                      ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                      ->orWhere(function($q2) use ($data) {
                          $q2->where('start_time', '<=', $data['start_time'])
                             ->where('end_time', '>=', $data['end_time']);
                      });
                })
                ->when($this->route('class_schedule'), function($q) {
                    $q->where('id', '!=', $this->route('class_schedule'));
                })
                ->exists();
            if ($roomConflict) {
                $validator->errors()->add('room_id', 'This room is already scheduled for another class at this time.');
            }
        });
    }
} 