<?php

namespace App\Http\Controllers\Modules\Attendance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modules\Attendance\Models\SessionTemplate;
use App\Models\Modules\Attendance\Models\SessionTemplateRule;

class SessionTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = SessionTemplate::with('rules')->get();
        return response()->json($templates);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'school_id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_start_time' => 'nullable',
            'default_end_time' => 'nullable',
            'is_active' => 'boolean',
            'rules' => 'array',
            'rules.*.rule_type' => 'required_with:rules|string',
            'rules.*.value' => 'required_with:rules|string',
            'rules.*.description' => 'nullable|string',
        ]);
        $template = SessionTemplate::create($data);
        if (!empty($data['rules'])) {
            foreach ($data['rules'] as $rule) {
                $template->rules()->create($rule);
            }
        }
        return response()->json($template->load('rules'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $template = SessionTemplate::with('rules')->findOrFail($id);
        return response()->json($template);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $template = SessionTemplate::findOrFail($id);
        $data = $request->validate([
            'school_id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'default_start_time' => 'nullable',
            'default_end_time' => 'nullable',
            'is_active' => 'boolean',
            'rules' => 'array',
            'rules.*.id' => 'nullable|integer',
            'rules.*.rule_type' => 'required_with:rules|string',
            'rules.*.value' => 'required_with:rules|string',
            'rules.*.description' => 'nullable|string',
        ]);
        $template->update($data);
        // Sync rules
        if (isset($data['rules'])) {
            $existingRuleIds = $template->rules()->pluck('id')->toArray();
            $sentRuleIds = collect($data['rules'])->pluck('id')->filter()->toArray();
            // Delete removed rules
            $toDelete = array_diff($existingRuleIds, $sentRuleIds);
            if ($toDelete) {
                SessionTemplateRule::destroy($toDelete);
            }
            // Update or create rules
            foreach ($data['rules'] as $ruleData) {
                if (!empty($ruleData['id'])) {
                    $rule = SessionTemplateRule::find($ruleData['id']);
                    if ($rule) $rule->update($ruleData);
                } else {
                    $template->rules()->create($ruleData);
                }
            }
        }
        return response()->json($template->load('rules'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $template = SessionTemplate::findOrFail($id);
        $template->rules()->delete();
        $template->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
