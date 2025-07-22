<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\FinanceRole;

class FinanceRoleController extends Controller
{
    public function index()
    {
        $roles = FinanceRole::all();
        return view('finance::roles.index', compact('roles'));
    }

    public function create()
    {
        return view('finance::roles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        FinanceRole::create($data);
        return redirect()->route('finance.roles.index')->with('success', 'Role created.');
    }

    public function edit(FinanceRole $role)
    {
        return view('finance::roles.edit', compact('role'));
    }

    public function update(Request $request, FinanceRole $role)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $role->update($data);
        return redirect()->route('finance.roles.index')->with('success', 'Role updated.');
    }

    public function destroy(FinanceRole $role)
    {
        $role->delete();
        return redirect()->route('finance.roles.index')->with('success', 'Role deleted.');
    }

    public function users(FinanceRole $role)
    {
        $users = \App\Models\User::all();
        $assigned = $role->users;
        return view('finance::roles.users', compact('role', 'users', 'assigned'));
    }

    public function assignUser(Request $request, FinanceRole $role)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        $role->users()->syncWithoutDetaching([$data['user_id']]);
        return redirect()->route('finance.roles.users', $role)->with('success', 'User assigned.');
    }

    public function removeUser(FinanceRole $role, $userId)
    {
        $role->users()->detach($userId);
        return redirect()->route('finance.roles.users', $role)->with('success', 'User removed.');
    }
} 