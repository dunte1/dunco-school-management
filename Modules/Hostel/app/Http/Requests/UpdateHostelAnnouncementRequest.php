<?php

namespace Modules\Hostel\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHostelAnnouncementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'hostel_id' => 'required|integer|exists:hostels,id',
            'warden_id' => 'required|integer|exists:wardens,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|string|max:255',
            'audience' => 'required|in:all,residents,staff',
            'published_at' => 'nullable|date',
        ];
    }
} 