<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = School::latest()->paginate(10);
        return view('core::schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = \App\Models\User::all();
        $levels = ['primary' => 'Primary', 'secondary' => 'Secondary', 'college' => 'College'];
        return view('core::schools.create', compact('users', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools',
            'motto' => 'nullable|string|max:255',
            'domain' => 'nullable|string|max:255|unique:schools',
            'level' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'admin_ids' => 'array',
            'admin_ids.*' => 'exists:users,id',
        ]);
        $school = School::create($request->only(['name','code','motto','domain','level','phone','email','address']));
        if ($request->has('admin_ids')) {
            $school->admins()->sync($request->admin_ids);
        }
        return redirect()->route('core.schools.index')
            ->with('success', 'School created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $school = School::findOrFail($id);
        return view('core::schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $school = School::findOrFail($id);
        $users = \App\Models\User::all();
        $levels = ['primary' => 'Primary', 'secondary' => 'Secondary', 'college' => 'College'];
        $adminIds = $school->admins ? $school->admins->pluck('id')->toArray() : [];
        return view('core::schools.edit', compact('school', 'users', 'levels', 'adminIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $school = School::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:schools,code,' . $id,
            'motto' => 'nullable|string|max:255',
            'domain' => 'nullable|string|max:255|unique:schools,domain,' . $id,
            'level' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'admin_ids' => 'array',
            'admin_ids.*' => 'exists:users,id',
        ]);
        $school->update($request->only(['name','code','motto','domain','level','phone','email','address']));
        if ($request->has('admin_ids')) {
            $school->admins()->sync($request->admin_ids);
        }
        return redirect()->route('core.schools.index')
            ->with('success', 'School updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $school = School::findOrFail($id);
        $school->delete();

        return redirect()->route('core.schools.index')
            ->with('success', 'School deleted successfully.');
    }
} 