<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LicensesController extends Controller
{
    public function index(Request $request)
    {
        $query = License::query();
        if ($request->get('filter') === 'expiring_30d') {
            $query->where('status','active')->whereNotNull('expires_at')->whereDate('expires_at','<=', now()->addDays(30));
        }
        $licenses = $query->orderByDesc('expires_at')->paginate(15);
        return view('admin.licenses.index', compact('licenses'));
    }

    public function create()
    {
        return view('admin.licenses.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'school_id' => 'nullable|integer',
            'plan' => 'required|string',
            'status' => 'required|string',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'seats' => 'nullable|integer',
            'features' => 'nullable|array',
        ]);
        License::create($data);
        return redirect()->route('admin.licenses.index')->with('success', 'License created');
    }

    public function edit(License $license)
    {
        return view('admin.licenses.edit', compact('license'));
    }

    public function update(Request $request, License $license)
    {
        $data = $request->validate([
            'plan' => 'required|string',
            'status' => 'required|string',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'seats' => 'nullable|integer',
            'features' => 'nullable|array',
        ]);
        $license->update($data);
        return redirect()->route('admin.licenses.index')->with('success', 'License updated');
    }

    public function destroy(License $license)
    {
        $license->delete();
        return redirect()->route('admin.licenses.index')->with('success', 'License deleted');
    }
}
