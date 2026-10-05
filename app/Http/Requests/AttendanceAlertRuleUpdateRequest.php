<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceAlertRuleUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'school_id' => ['nullable', 'integer'],
            'name' => ['sometimes', 'string', 'max:255'],
            'type' => ['sometimes', 'in:absent_consecutive,late_count'],
            'threshold' => ['sometimes', 'integer', 'min:1'],
            'window_days' => ['sometimes', 'integer', 'min:1'],
            'channel' => ['sometimes', 'in:email,sms,both'],
            'template_name' => ['sometimes', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}


