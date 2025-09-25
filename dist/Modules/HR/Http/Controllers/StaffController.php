<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HR\Models\Staff;
use Modules\HR\Models\Department;
use Modules\HR\Models\Role;
use Modules\HR\Models\StaffDocument;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Role as UserRole;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = Staff::query();
        if ($request->filled('search')) {
            $query->where('first_name', 'like', '%'.$request->search.'%')
                  ->orWhere('last_name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->role_id);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        $staff = $query->paginate(20);
        $roles = Role::all();
        $departments = Department::all();
        return view('hr::staff.index', compact('staff', 'roles', 'departments'));
    }

    public function create()
    {
        $roles = Role::all();
        $departments = Department::all();
        return view('hr::staff.create', compact('roles', 'departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:staff,email',
            'gender' => 'required|in:male,female',
            'role_id' => 'nullable|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            // ... other validation rules ...
        ]);
        // Staff ID generation
        $data['staff_id'] = strtoupper(Str::random(8));
        // Save photo if uploaded
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('staff_photos', 'public');
        }
        // Always create or update the linked user account robustly
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            $user = User::create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => bcrypt(Str::random(10)), // random password, can be reset later
                'school_id' => $data['school_id'] ?? null,
            ]);
        } else {
            // Always update name and school_id to match staff
            $user->name = $data['first_name'] . ' ' . $data['last_name'];
            $user->school_id = $data['school_id'] ?? $user->school_id;
            $user->save();
        }
        $data['user_id'] = $user->id;
        $staff = Staff::create($data);
        // Generate QR code for staff ID
        $qrPath = 'staff_qrcodes/'.$data['staff_id'].'.png';
        \Storage::disk('public')->put($qrPath, QrCode::format('png')->size(200)->generate($data['staff_id']));
        $staff->qr_code = $qrPath;
        $staff->save();
        // Handle document upload
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('staff_docs', 'public');
            StaffDocument::create([
                'staff_id' => $staff->id,
                'type' => $request->input('type', 'Document'),
                'file_path' => $filePath,
                'description' => $request->input('description'),
            ]);
        }
        // Assign 'teacher' role to linked user if staff HR role is teacher (case-insensitive)
        if ($staff->role && strtolower($staff->role->name) === 'teacher') {
            $teacherRole = UserRole::where('name', 'teacher')->first();
            if ($teacherRole && !$user->roles->contains($teacherRole->id)) {
                $user->roles()->attach($teacherRole->id);
            }
        }
        return redirect()->route('hr.staff.index')->with('success', 'Staff created successfully.');
    }

    public function show($id)
    {
        $staff = Staff::with(['department', 'role', 'documents'])->findOrFail($id);
        return view('hr::staff.show', compact('staff'));
    }

    public function edit($id)
    {
        $staff = Staff::findOrFail($id);
        $roles = Role::all();
        $departments = Department::all();
        return view('hr::staff.edit', compact('staff', 'roles', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:staff,email,'.$id,
            'gender' => 'required|in:male,female',
            'role_id' => 'nullable|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            // ... other validation rules ...
        ]);
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('staff_photos', 'public');
        }
        $staff->update($data);
        // Always update linked user's name and school_id to match staff
        if ($staff->user_id) {
            $user = User::find($staff->user_id);
            if ($user) {
                $user->name = $staff->first_name . ' ' . $staff->last_name;
                $user->school_id = $staff->school_id;
                $user->save();
            }
        }
        // Handle document upload
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('staff_docs', 'public');
            StaffDocument::create([
                'staff_id' => $staff->id,
                'type' => $request->input('type', 'Document'),
                'file_path' => $filePath,
                'description' => $request->input('description'),
            ]);
        }
        // Assign 'teacher' role to linked user if staff HR role is teacher (case-insensitive)
        if ($staff->role && strtolower($staff->role->name) === 'teacher' && $staff->user_id) {
            $user = User::find($staff->user_id);
            if ($user) {
                $teacherRole = UserRole::where('name', 'teacher')->first();
                if ($teacherRole && !$user->roles->contains($teacherRole->id)) {
                    $user->roles()->attach($teacherRole->id);
                }
            }
        }
        return redirect()->route('hr.staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();
        return redirect()->route('hr.staff.index')->with('success', 'Staff deleted successfully.');
    }
} 