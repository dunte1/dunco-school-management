<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Modules\Library\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $members = $query->withCount('borrowRecords')->orderBy('name')->paginate(15)->withQueryString();

        return view('library::members.index', compact('members'));
    }

    public function create()
    {
        return view('library::members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'membership_number' => 'required|string|max:50|unique:members,membership_number',
            'status' => 'required|in:active,inactive,suspended',
            'join_date' => 'required|date',
            'address' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        Member::create($validated);

        return redirect()->route('library.members.index')->with('success', 'Member created successfully!');
    }

    public function show(Member $member)
    {
        $member->load(['borrowRecords.book', 'user']);
        $member->loadCount('borrowRecords');

        $activeBorrows = $member->borrowRecords()->whereNull('returned_at')->count();
        $totalBorrows = $member->borrowRecords()->count();

        return view('library::members.show', compact('member', 'activeBorrows', 'totalBorrows'));
    }

    public function edit(Member $member)
    {
        return view('library::members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'membership_number' => 'required|string|max:50|unique:members,membership_number,' . $member->id,
            'status' => 'required|in:active,inactive,suspended',
            'join_date' => 'required|date',
            'address' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $member->update($validated);

        return redirect()->route('library.members.index')->with('success', 'Member updated successfully!');
    }

    public function destroy(Member $member)
    {
        $activeBorrows = $member->borrowRecords()->whereNull('returned_at')->count();
        if ($activeBorrows > 0) {
            return redirect()->route('library.members.index')
                ->with('error', 'Cannot delete member with active borrows. Return all books first.');
        }

        $member->delete();

        return redirect()->route('library.members.index')->with('success', 'Member deleted successfully!');
    }
}
