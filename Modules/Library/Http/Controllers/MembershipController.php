<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Library\Models\Membership;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $query = Membership::with(['member']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Search by member name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('member', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('member_id', 'like', "%{$search}%");
            });
        }

        $memberships = $query->orderBy('created_at', 'desc')->paginate(15);
        $members = \Modules\Library\Models\Member::all();

        return view('library::memberships.index', compact('memberships', 'members'));
    }

    public function create()
    {
        $members = \Modules\Library\Models\Member::all();
        return view('library::memberships.create', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:library_members,id',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'fee' => 'required|numeric|min:0',
            'status' => 'required|in:active,expired,suspended',
            'notes' => 'nullable|string'
        ]);

        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();

            Membership::create($data);

            return redirect()->route('library.memberships.index')
                           ->with('success', 'Membership created successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create membership: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $membership = Membership::with(['member'])->findOrFail($id);
        return view('library::memberships.show', compact('membership'));
    }

    public function edit($id)
    {
        $membership = Membership::findOrFail($id);
        $members = \Modules\Library\Models\Member::all();
        return view('library::memberships.edit', compact('membership', 'members'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'member_id' => 'required|exists:library_members,id',
            'type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'fee' => 'required|numeric|min:0',
            'status' => 'required|in:active,expired,suspended',
            'notes' => 'nullable|string'
        ]);

        try {
            $membership = Membership::findOrFail($id);
            $membership->update($request->all());

            return redirect()->route('library.memberships.index')
                           ->with('success', 'Membership updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update membership: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $membership = Membership::findOrFail($id);
            $membership->delete();

            return redirect()->route('library.memberships.index')
                           ->with('success', 'Membership deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete membership: ' . $e->getMessage()]);
        }
    }
}
