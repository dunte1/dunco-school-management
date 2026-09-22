<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Nursing\Models\Facility;
use Modules\Nursing\Models\FacilityDepartment;
use Modules\Nursing\Models\Ward;
use Modules\Nursing\Models\NursingAuditLog;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Facility::where('school_id', Auth::user()->school_id);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        $facilities = $query->withCount(['departments', 'wards'])->latest()->paginate(15);

        return Inertia::render('Nursing/Facilities/Index', compact('facilities'));
    }

    public function create()
    {
        return Inertia::render('Nursing/Facilities/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'type' => 'required|in:hospital,health_centre,clinic,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $validated['school_id'] = Auth::user()->school_id;

        $facility = Facility::create($validated);

        NursingAuditLog::log('facility_created', $facility, null, $validated);

        return redirect()->route('nursing.facilities.show', $facility)
            ->with('success', 'Facility created successfully.');
    }

    public function show(Facility $facility)
    {
        $this->authorize('view', $facility);

        $facility->load(['departments.wards', 'wards']);

        return Inertia::render('Nursing/Facilities/Show', compact('facility'));
    }

    public function edit(Facility $facility)
    {
        $this->authorize('update', $facility);

        return Inertia::render('Nursing/Facilities/Edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        $this->authorize('update', $facility);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'type' => 'required|in:hospital,health_centre,clinic,other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $oldValues = $facility->only(array_keys($validated));
        $facility->update($validated);

        NursingAuditLog::log('facility_updated', $facility, $oldValues, $validated);

        return redirect()->route('nursing.facilities.show', $facility)
            ->with('success', 'Facility updated successfully.');
    }

    public function destroy(Facility $facility)
    {
        $this->authorize('delete', $facility);

        $facility->delete();

        NursingAuditLog::log('facility_deleted', $facility);

        return redirect()->route('nursing.facilities.index')
            ->with('success', 'Facility deleted successfully.');
    }
}
