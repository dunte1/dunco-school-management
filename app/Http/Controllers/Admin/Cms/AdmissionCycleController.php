<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\AdmissionCycle;
use Illuminate\Http\Request;

class AdmissionCycleController extends Controller
{
    public function index()
    {
        $cycles = AdmissionCycle::withCount('applications')->latest()->get();
        return view('admin.cms.admissions.cycles.index', compact('cycles'));
    }

    public function create()
    {
        return view('admin.cms.admissions.cycles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'opening_date'  => 'required|date',
            'closing_date'  => 'required|date|after_or_equal:opening_date',
            'description'   => 'nullable|string',
            'status'        => 'required|in:open,closed,upcoming',
            'is_active'     => 'boolean',
        ]);

        $data['is_active'] = $data['is_active'] ?? true;

        AdmissionCycle::create($data);

        return redirect()->route('admin.cms.admissions.cycles.index')
            ->with('success', 'Admission cycle created successfully.');
    }

    public function edit(AdmissionCycle $cycle)
    {
        return view('admin.cms.admissions.cycles.edit', compact('cycle'));
    }

    public function update(Request $request, AdmissionCycle $cycle)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'opening_date'  => 'required|date',
            'closing_date'  => 'required|date|after_or_equal:opening_date',
            'description'   => 'nullable|string',
            'status'        => 'required|in:open,closed,upcoming',
            'is_active'     => 'boolean',
        ]);

        $data['is_active'] = $data['is_active'] ?? true;

        $cycle->update($data);

        return redirect()->route('admin.cms.admissions.cycles.index')
            ->with('success', 'Admission cycle updated successfully.');
    }

    public function destroy(AdmissionCycle $cycle)
    {
        if ($cycle->applications()->count() > 0) {
            return redirect()->route('admin.cms.admissions.cycles.index')
                ->with('error', 'Cannot delete cycle with existing applications.');
        }

        $cycle->delete();

        return redirect()->route('admin.cms.admissions.cycles.index')
            ->with('success', 'Admission cycle deleted successfully.');
    }
}
