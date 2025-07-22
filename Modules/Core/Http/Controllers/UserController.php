<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('core::users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $schools = \App\Models\School::all();
        return view('core::users.create', compact('roles', 'schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array',
            'role_assignments' => 'array',
            'role_assignments.*.role_id' => 'required|exists:roles,id',
            'role_assignments.*.school_id' => 'nullable|exists:schools,id',
            'school_id' => 'nullable|exists:schools,id',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
            'force_password_reset' => 'nullable|boolean',
            'primary_role_id' => 'required|exists:roles,id',
        ]);
        $data = $request->only(['name','email','school_id','phone','address']);
        $data['password'] = bcrypt($request->password);
        $data['is_active'] = $request->has('is_active');
        $data['force_password_reset'] = $request->has('force_password_reset');
        $data['primary_role_id'] = $request->input('primary_role_id');
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        $user = User::create($data);
        // Assign roles per school
        if ($request->has('role_assignments')) {
            $syncData = [];
            foreach ($request->role_assignments as $assignment) {
                $syncData[$assignment['role_id']] = ['school_id' => $assignment['school_id'] ?? null];
            }
            $user->roles()->sync($syncData);
        }
        // Log the action
        AuditLog::log(
            'user.created',
            "User '{$user->name}' ({$user->email}) was created",
            null,
            $user->toArray()
        );
        return redirect()->route('core.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('core::users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $schools = \App\Models\School::all();
        $userRoleIds = $user->roles->pluck('id')->toArray();
        return view('core::users.edit', compact('user', 'roles', 'schools', 'userRoleIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $oldValues = $user->toArray();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'array',
            'role_assignments' => 'array',
            'role_assignments.*.role_id' => 'required|exists:roles,id',
            'role_assignments.*.school_id' => 'nullable|exists:schools,id',
            'school_id' => 'nullable|exists:schools,id',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean',
            'force_password_reset' => 'nullable|boolean',
            'primary_role_id' => 'required|exists:roles,id',
        ]);
        $data = $request->only(['name','email','school_id','phone','address']);
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $data['is_active'] = $request->has('is_active');
        $data['force_password_reset'] = $request->has('force_password_reset');
        $data['primary_role_id'] = $request->input('primary_role_id');
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        $user->update($data);
        // Assign roles per school
        if ($request->has('role_assignments')) {
            $syncData = [];
            foreach ($request->role_assignments as $assignment) {
                $syncData[$assignment['role_id']] = ['school_id' => $assignment['school_id'] ?? null];
            }
            $user->roles()->sync($syncData);
        }
        // Log the action
        AuditLog::log(
            'user.updated',
            "User '{$user->name}' ({$user->email}) was updated",
            $oldValues,
            $user->toArray()
        );
        return redirect()->route('core.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $oldValues = $user->toArray();
        
        $user->delete();

        // Log the action
        AuditLog::log(
            'user.deleted',
            "User '{$user->name}' ({$user->email}) was deleted",
            $oldValues,
            null
        );

        return redirect()->route('core.users.index')
            ->with('success', 'User deleted successfully.');
    }
} 