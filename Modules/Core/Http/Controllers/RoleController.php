<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Force fresh load of roles with permissions and clear any cache
        \Cache::forget('roles_index_view');
        
        $roles = Role::with(['permissions' => function($query) {
            $query->orderBy('name');
        }])->get();
        
        // Force refresh each role's permissions
        foreach ($roles as $role) {
            $role->load('permissions');
            // Clear any cached permissions for this role
            \Cache::forget("role_permissions_{$role->id}");
        }
        
        return view('core::roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('core::roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
        ]);

        $role = Role::create($request->only(['name', 'display_name', 'description']));
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        // Log the action
        AuditLog::log(
            'role.created',
            "Role '{$role->display_name}' ({$role->name}) was created",
            null,
            $role->toArray()
        );

        return redirect()->route('core.roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return view('core::roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();
        $rolePermissionIds = $role->permissions->pluck('id')->toArray();
        $roles = Role::all();
        return view('core::roles.edit', compact('role', 'permissions', 'rolePermissionIds', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $oldValues = $role->toArray();
        
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'array',
        ]);

        $role->update($request->only(['name', 'display_name', 'description']));
        $role->permissions()->sync($request->permissions ?? []);
        
        // Force refresh the role's permissions relationship
        $role->load('permissions');
        
        // Clear any cached queries for this role
        \DB::table('role_has_permissions')->where('role_id', $role->id)->get();

        // Clear permission cache for all users who have this role
        $usersWithRole = \App\Models\User::whereHas('roles', function($query) use ($role) {
            $query->where('roles.id', $role->id);
        })->get();

        $affectedUserIds = $usersWithRole->pluck('id')->toArray();

        foreach ($usersWithRole as $user) {
            $user->clearPermissionCache();
            // Update last permission update timestamp for this user
            cache()->put("user_permissions_last_update_{$user->id}", now()->toISOString(), 3600);
        }

        // Broadcast permission update event
        event(new \App\Events\PermissionsUpdated($role->id, $affectedUserIds));
        
        // Clear any cached views for roles
        \Cache::forget('roles_index_view');

        // Log the action
        AuditLog::log(
            'role.updated',
            "Role '{$role->display_name}' ({$role->name}) was updated",
            $oldValues,
            $role->toArray()
        );

        return redirect()->route('core.roles.index')->with('success', 'Role updated successfully. Users with this role will see changes immediately.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $oldValues = $role->toArray();
        
        $role->permissions()->detach();
        $role->delete();

        // Log the action
        AuditLog::log(
            'role.deleted',
            "Role '{$role->display_name}' ({$role->name}) was deleted",
            $oldValues,
            null
        );

        return redirect()->route('core.roles.index')->with('success', 'Role deleted successfully.');
    }

    public function clonePermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $request->validate([
            'source_role_id' => 'required|exists:roles,id',
        ]);
        $sourceRole = Role::findOrFail($request->source_role_id);
        $role->permissions()->sync($sourceRole->permissions->pluck('id')->toArray());

        // Clear permission cache for all users who have this role
        $usersWithRole = \App\Models\User::whereHas('roles', function($query) use ($role) {
            $query->where('roles.id', $role->id);
        })->get();

        $affectedUserIds = $usersWithRole->pluck('id')->toArray();

        foreach ($usersWithRole as $user) {
            $user->clearPermissionCache();
            // Update last permission update timestamp for this user
            cache()->put("user_permissions_last_update_{$user->id}", now()->toISOString(), 3600);
        }

        // Broadcast permission update event
        event(new \App\Events\PermissionsUpdated($role->id, $affectedUserIds));

        return redirect()->route('core.roles.edit', $role->id)->with('success', 'Permissions cloned from ' . ($sourceRole->display_name ?? $sourceRole->name) . '. Users with this role will see changes immediately.');
    }
} 