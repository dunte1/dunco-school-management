<?php
namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Models\FeeConfiguration;
use Illuminate\Http\Request;

class FeeConfigurationController extends Controller
{
    public function index()
    {
        $fees = FeeConfiguration::all();
        return view('admin.config.fees.index', compact('fees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'applies_to' => 'nullable|array',
            'due_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);
        $data['applies_to'] = $data['applies_to'] ?? [];
        $fee = FeeConfiguration::create($data);
        return response()->json(['success' => true, 'fee' => $fee]);
    }

    public function update(Request $request, FeeConfiguration $feeConfiguration)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'applies_to' => 'nullable|array',
            'due_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);
        $data['applies_to'] = $data['applies_to'] ?? [];
        $feeConfiguration->update($data);
        return response()->json(['success' => true, 'fee' => $feeConfiguration]);
    }

    public function destroy(FeeConfiguration $feeConfiguration)
    {
        $feeConfiguration->delete();
        return response()->json(['success' => true]);
    }
} 