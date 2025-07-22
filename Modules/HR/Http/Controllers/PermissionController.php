<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('hr::permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('hr::permissions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:permissions,name',
            'description' => 'nullable',
        ]);
        Permission::create($data);
        return redirect()->route('hr.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('hr::permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|unique:permissions,name,'.$id,
            'description' => 'nullable',
        ]);
        $permission->update($data);
        return redirect()->route('hr.permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->route('hr.permissions.index')->with('success', 'Permission deleted successfully.');
    }
} 