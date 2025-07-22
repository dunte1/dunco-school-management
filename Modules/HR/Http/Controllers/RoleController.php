<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Role;
use Modules\HR\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('hr::roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('hr::roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:roles,name',
            'description' => 'nullable',
            'permissions' => 'array',
        ]);
        $role = Role::create($data);
        $role->permissions()->sync($request->permissions);
        return redirect()->route('hr.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();
        return view('hr::roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|unique:roles,name,'.$id,
            'description' => 'nullable',
            'permissions' => 'array',
        ]);
        $role->update($data);
        $role->permissions()->sync($request->permissions);
        return redirect()->route('hr.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('hr.roles.index')->with('success', 'Role deleted successfully.');
    }
} 