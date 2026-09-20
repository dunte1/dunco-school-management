<?php

namespace App\Http\Controllers\Admin\Cms;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::query();

        if ($status = $request->input('status')) {
            $query->byStatus($status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('organization', 'like', "%{$search}%");
            });
        }

        $leads = $query->latest()->paginate(20);

        $statuses = ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'converted', 'lost'];
        $counts = collect($statuses)->mapWithKeys(fn($s) => [$s => Lead::byStatus($s)->count()])->toArray();

        return view('admin.cms.leads.index', compact('leads', 'counts'));
    }

    public function create()
    {
        return view('admin.cms.leads.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'source'       => 'nullable|string|max:255',
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:50',
            'organization' => 'nullable|string|max:255',
            'status'       => 'nullable|string|in:new,contacted,qualified,proposal,negotiation,converted,lost',
            'notes'        => 'nullable|string',
        ]);

        $data['status'] = $data['status'] ?? 'new';

        $lead = Lead::create($data);
        $lead->logActivity('created', 'Lead created.', Auth::id());

        return redirect()->route('admin.cms.leads.show', $lead)
            ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $lead->load('activities.user');
        return view('admin.cms.leads.show', compact('lead'));
    }

    public function update(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'source'       => 'nullable|string|max:255',
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:50',
            'organization' => 'nullable|string|max:255',
            'status'       => 'nullable|string|in:new,contacted,qualified,proposal,negotiation,converted,lost',
            'notes'        => 'nullable|string',
        ]);

        $old = $lead->toArray();
        $lead->update($data);

        $changes = array_diff_keys($data, ['notes' => $data['notes'] ?? null]);
        if (!empty($changes)) {
            $desc = 'Updated: ' . implode(', ', array_keys($changes));
            $lead->logActivity('updated', $desc, Auth::id());
        }

        if (($data['status'] ?? null) && ($data['status'] !== $old['status'])) {
            $lead->logActivity('status_changed', "Status changed from {$old['status']} to {$data['status']}", Auth::id());
            if ($data['status'] === 'converted') {
                $lead->update(['converted_at' => now()]);
            }
        }

        return redirect()->route('admin.cms.leads.show', $lead)
            ->with('success', 'Lead updated successfully.');
    }

    public function addNote(Request $request, Lead $lead)
    {
        $request->validate(['note' => 'required|string']);

        $lead->logActivity('note', $request->input('note'), Auth::id());

        return redirect()->route('admin.cms.leads.show', $lead)
            ->with('success', 'Note added.');
    }

    public function destroy(Lead $lead)
    {
        $lead->activities()->delete();
        $lead->delete();

        return redirect()->route('admin.cms.leads.index')
            ->with('success', 'Lead deleted successfully.');
    }
}
