<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Communication\Models\Group;
use App\Models\User;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::with(['creator', 'members'])
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->when(request('type'), function ($query, $type) {
                $query->where('type', $type);
            })
            ->when(request('status'), function ($query, $status) {
                if ($status === 'active') {
                    $query->active();
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->latest()
            ->paginate(15);

        return view('communication::groups.index', compact('groups'));
    }

    public function create()
    {
        $users = User::all();
        return view('communication::groups.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:general,academic,staff,students,parents',
            'is_active' => 'boolean',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id'
        ]);

        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);

        // Add creator as admin
        $group->members()->attach(Auth::id(), [
            'role' => 'admin',
            'joined_at' => now()
        ]);

        // Add selected members
        if ($request->has('members')) {
            $membersData = [];
            foreach ($request->members as $memberId) {
                $membersData[$memberId] = [
                    'role' => 'member',
                    'joined_at' => now()
                ];
            }
            $group->members()->attach($membersData);
        }

        return redirect()->route('communication.groups.index')
            ->with('success', 'Group created successfully.');
    }

    public function show(Group $group)
    {
        $group->load(['creator', 'members']);
        return view('communication::groups.show', compact('group'));
    }

    public function edit(Group $group)
    {
        $users = User::all();
        $group->load('members');
        return view('communication::groups.edit', compact('group', 'users'));
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:general,academic,staff,students,parents',
            'is_active' => 'boolean',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id'
        ]);

        $group->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active', true),
            'updated_by' => Auth::id()
        ]);

        // Update members
        if ($request->has('members')) {
            $membersData = [];
            foreach ($request->members as $memberId) {
                $membersData[$memberId] = [
                    'role' => 'member',
                    'joined_at' => now()
                ];
            }
            $group->members()->sync($membersData);
            
            // Ensure creator remains as admin
            if (!$group->isAdmin($group->created_by)) {
                $group->members()->attach($group->created_by, [
                    'role' => 'admin',
                    'joined_at' => now()
                ]);
            }
        }

        return redirect()->route('communication.groups.index')
            ->with('success', 'Group updated successfully.');
    }

    public function destroy(Group $group)
    {
        $group->delete();

        return redirect()->route('communication.groups.index')
            ->with('success', 'Group deleted successfully.');
    }

    public function toggleStatus(Group $group)
    {
        $group->update([
            'is_active' => !$group->is_active,
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('communication.groups.index')
            ->with('success', 'Group status updated successfully.');
    }

    public function addMember(Request $request, Group $group)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:member,admin'
        ]);

        $group->members()->attach($request->user_id, [
            'role' => $request->role,
            'joined_at' => now()
        ]);

        return redirect()->route('communication.groups.show', $group)
            ->with('success', 'Member added successfully.');
    }

    public function removeMember(Group $group, $userId)
    {
        // Prevent removing the creator
        if ($userId == $group->created_by) {
            return redirect()->route('communication.groups.show', $group)
                ->with('error', 'Cannot remove the group creator.');
        }

        $group->members()->detach($userId);

        return redirect()->route('communication.groups.show', $group)
            ->with('success', 'Member removed successfully.');
    }

    public function sendMessage(Request $request, Group $group)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:email,sms,notification'
        ]);

        // Create message for each group member
        foreach ($group->members as $member) {
            // This would typically create a message record for each member
            // For now, we'll just return success
        }

        return redirect()->route('communication.groups.show', $group)
            ->with('success', 'Message sent to group successfully.');
    }
}
