<?php

namespace Modules\Hostel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'hostel_id' => 'required|integer|exists:hostels,id',
            'floor_id' => 'required|integer|exists:floors,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:single,double,triple',
            'ac' => 'boolean',
            'ensuite' => 'boolean',
            'capacity' => 'required|integer|min:1',
            'amenities' => 'nullable|array',
            'layout_image' => 'nullable|string|max:255',
            'price_per_bed' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance,reserved',
            'description' => 'nullable|string',
        ];
    }
} 