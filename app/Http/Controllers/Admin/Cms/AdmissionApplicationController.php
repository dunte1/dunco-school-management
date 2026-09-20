<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\AdmissionApplication;
use App\Models\AdmissionCycle;
use App\Models\AdmissionApplicationNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdmissionApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = AdmissionApplication::with('cycle');

        if ($status = $request->input('status')) {
            $query->byStatus($status);
        }

        if ($cycleId = $request->input('cycle_id')) {
            $query->byCycle($cycleId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(20);

        $statuses = [
            'draft', 'submitted', 'under_review', 'shortlisted',
            'interview_scheduled', 'interviewed', 'waitlisted',
            'accepted', 'enrolled', 'rejected', 'withdrawn',
        ];
        $counts = collect($statuses)->mapWithKeys(fn($s) => [$s => AdmissionApplication::byStatus($s)->count()])->toArray();

        $cycles = AdmissionCycle::latest()->get();
        $totalApplications = AdmissionApplication::count();

        return view('admin.cms.admissions.applications.index', compact(
            'applications', 'counts', 'cycles', 'totalApplications'
        ));
    }

    public function show(AdmissionApplication $application)
    {
        $application->load(['cycle', 'notes.user', 'logs.user']);

        $transitions = $this->getAllowedTransitions($application->status);

        return view('admin.cms.admissions.applications.show', [
            'application' => $application,
            'transitions' => $transitions,
        ]);
    }

    public function updateStatus(Request $request, AdmissionApplication $application)
    {
        $request->validate([
            'new_status' => 'required|string',
            'details'    => 'nullable|string',
        ]);

        $action = $request->input('new_status');
        $userId = Auth::id();

        $success = $application->transitionTo(
            $request->input('new_status'),
            $action,
            $userId,
            $request->input('details')
        );

        if ($success) {
            return redirect()->route('admin.cms.admissions.applications.show', $application)
                ->with('success', 'Application status updated to ' . $request->input('new_status') . '.');
        }

        return redirect()->route('admin.cms.admissions.applications.show', $application)
            ->with('error', 'Invalid status transition.');
    }

    public function addNote(Request $request, AdmissionApplication $application)
    {
        $request->validate(['note' => 'required|string']);

        $application->notes()->create([
            'user_id' => Auth::id(),
            'note'    => $request->input('note'),
        ]);

        $application->logs()->create([
            'user_id'    => Auth::id(),
            'action'     => 'note_added',
            'old_status' => null,
            'new_status' => null,
            'details'    => $request->input('note'),
        ]);

        return redirect()->route('admin.cms.admissions.applications.show', $application)
            ->with('success', 'Note added successfully.');
    }

    private function getAllowedTransitions(string $currentStatus): array
    {
        $transitions = [
            'draft'                => ['submitted'],
            'submitted'            => ['under_review', 'rejected', 'withdrawn'],
            'under_review'         => ['shortlisted', 'rejected', 'withdrawn'],
            'shortlisted'          => ['interview_scheduled', 'rejected', 'withdrawn'],
            'interview_scheduled'  => ['interviewed', 'rejected', 'withdrawn'],
            'interviewed'          => ['accepted', 'rejected', 'waitlisted', 'withdrawn'],
            'waitlisted'           => ['accepted', 'rejected', 'withdrawn'],
            'accepted'             => ['enrolled', 'rejected', 'withdrawn'],
        ];

        return $transitions[$currentStatus] ?? [];
    }
}
