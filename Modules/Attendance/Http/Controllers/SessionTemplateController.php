<?php

namespace Modules\Attendance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Modules\Attendance\Models\SessionTemplate;
use App\Models\Modules\Attendance\Models\SessionTemplateRule;

class SessionTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = SessionTemplate::with('rules')
            ->where('school_id', auth()->user()->school_id ?? 1)
            ->get();
        
        return response()->json($templates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_start_time' => 'nullable|date_format:H:i',
            'default_end_time' => 'nullable|date_format:H:i',
            'is_active' => 'boolean',
            'rules' => 'array'
        ]);

        $template = SessionTemplate::create([
            'school_id' => auth()->user()->school_id ?? 1,
            'name' => $request->name,
            'description' => $request->description,
            'default_start_time' => $request->default_start_time,
            'default_end_time' => $request->default_end_time,
            'is_active' => $request->is_active ?? true,
        ]);

        // Create rules if provided
        if ($request->has('rules') && is_array($request->rules)) {
            foreach ($request->rules as $rule) {
                $template->rules()->create([
                    'day_of_week' => $rule['day_of_week'] ?? null,
                    'start_time' => $rule['start_time'] ?? null,
                    'end_time' => $rule['end_time'] ?? null,
                    'is_active' => $rule['is_active'] ?? true,
                ]);
            }
        }

        return response()->json($template->load('rules'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SessionTemplate $sessionTemplate)
    {
        return response()->json($sessionTemplate->load('rules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SessionTemplate $sessionTemplate)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_start_time' => 'nullable|date_format:H:i',
            'default_end_time' => 'nullable|date_format:H:i',
            'is_active' => 'boolean',
            'rules' => 'array'
        ]);

        $sessionTemplate->update([
            'name' => $request->name,
            'description' => $request->description,
            'default_start_time' => $request->default_start_time,
            'default_end_time' => $request->default_end_time,
            'is_active' => $request->is_active ?? true,
        ]);

        // Update rules if provided
        if ($request->has('rules') && is_array($request->rules)) {
            // Delete existing rules
            $sessionTemplate->rules()->delete();
            
            // Create new rules
            foreach ($request->rules as $rule) {
                $sessionTemplate->rules()->create([
                    'day_of_week' => $rule['day_of_week'] ?? null,
                    'start_time' => $rule['start_time'] ?? null,
                    'end_time' => $rule['end_time'] ?? null,
                    'is_active' => $rule['is_active'] ?? true,
                ]);
            }
        }

        return response()->json($sessionTemplate->load('rules'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SessionTemplate $sessionTemplate)
    {
        // Delete associated rules first
        $sessionTemplate->rules()->delete();
        
        // Delete the template
        $sessionTemplate->delete();
        
        return response()->json(['message' => 'Session template deleted successfully']);
    }
}
