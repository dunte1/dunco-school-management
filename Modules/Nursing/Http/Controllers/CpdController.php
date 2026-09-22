<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Nursing\Models\CpdActivity;
use Modules\Nursing\Models\NursingAuditLog;

class CpdController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->school_id;

        $query = CpdActivity::where('school_id', $schoolId)->with(['user', 'approvedBy']);

        if ($user->hasAnyRole(['student'])) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('year')) {
            $query->whereYear('activity_date', $request->year);
        }

        $activities = $query->latest('activity_date')->paginate(15);

        $totalHours = CpdActivity::where('school_id', $schoolId)
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereYear('activity_date', now()->year)
            ->sum('hours');

        $targetHours = config('nursing.cpd.target_hours_per_year', 40);

        return Inertia::render('Nursing/CPD/Index', compact('activities', 'totalHours', 'targetHours'));
    }

    public function create()
    {
        return Inertia::render('Nursing/CPD/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'provider' => 'nullable|string|max:255',
            'type' => 'required|in:conference,workshop,seminar,online_course,publication,mentoring,other',
            'activity_date' => 'required|date|before_or_equal:today',
            'hours' => 'required|numeric|min:0.5|max:40',
            'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $validated['school_id'] = $user->school_id;
        $validated['user_id'] = $user->id;
        $validated['status'] = 'pending';

        if ($request->hasFile('certificate')) {
            $validated['certificate_path'] = $request->file('certificate')->store('nursing/cpd/certificates', 'public');
        }

        unset($validated['certificate']);

        $activity = CpdActivity::create($validated);

        NursingAuditLog::log('cpd_activity_created', $activity, null, collect($validated)->except('certificate_path')->toArray());

        return redirect()->route('nursing.cpd.show', $activity)
            ->with('success', 'CPD activity submitted for approval.');
    }

    public function show(CpdActivity $activity)
    {
        $activity->load(['user', 'approvedBy']);

        return Inertia::render('Nursing/CPD/Show', compact('activity'));
    }

    public function approve(CpdActivity $activity)
    {
        $activity->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        NursingAuditLog::log('cpd_activity_approved', $activity, ['status' => 'pending'], ['status' => 'approved']);

        return back()->with('success', 'CPD activity approved.');
    }
}
