<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::paginate(10);
        return view('core::permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('core::permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
            'display_name' => 'nullable|string|max:255',
            'module' => 'nullable|string|max:255',
        ]);

        $permission = Permission::create($request->only(['name', 'display_name', 'module']));

        // Log the action
        AuditLog::log(
            'permission.created',
            "Permission '{$permission->display_name}' ({$permission->name}) was created",
            null,
            $permission->toArray()
        );

        return redirect()->route('core.permissions.index')->with('success', 'Permission created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $permission = Permission::findOrFail($id);
        return view('core::permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('core::permissions.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        $oldValues = $permission->toArray();

        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
            'display_name' => 'nullable|string|max:255',
            'module' => 'nullable|string|max:255',
        ]);

        $permission->update($request->only(['name', 'display_name', 'module']));

        // Log the action
        AuditLog::log(
            'permission.updated',
            "Permission '{$permission->display_name}' ({$permission->name}) was updated",
            $oldValues,
            $permission->toArray()
        );

        return redirect()->route('core.permissions.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $oldValues = $permission->toArray();
        
        $permission->delete();

        // Log the action
        AuditLog::log(
            'permission.deleted',
            "Permission '{$permission->display_name}' ({$permission->name}) was deleted",
            $oldValues,
            null
        );

        return redirect()->route('core.permissions.index')->with('success', 'Permission deleted successfully.');
    }

    public function matrix()
    {
        $roles = \App\Models\Role::with('permissions')->get();
        $permissions = \App\Models\Permission::all();
        return view('core::permissions.matrix', compact('roles', 'permissions'));
    }

    public function updateMatrix(Request $request)
    {
        $roles = \App\Models\Role::all();
        foreach ($roles as $role) {
            $permissionIds = $request->input('permissions.' . $role->id, []);
            $role->permissions()->sync($permissionIds);
        }
        return redirect()->route('core.permissions.matrix')->with('success', 'Permission matrix updated successfully.');
    }
} 