<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('core::dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('core::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'key' => 'required|string|max:255|unique:settings,key',
                'value' => 'nullable|string',
                'group' => 'nullable|string|max:255',
                'description' => 'nullable|string',
            ]);

            \DB::table('settings')->insert(array_merge($validated, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            return redirect()->route('core.index')
                ->with('success', 'Setting created successfully.');
        } catch (\Exception $e) {
            Log::error('CoreController: Failed to store setting - ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to create setting: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $setting = \DB::table('settings')->where('id', $id)->first();
            if (!$setting) {
                return redirect()->route('core.index')
                    ->with('error', 'Setting not found.');
            }
            return view('core::show', compact('setting'));
        } catch (\Exception $e) {
            Log::error('CoreController: Failed to show setting - ' . $e->getMessage());
            return redirect()->route('core.index')
                ->with('error', 'Failed to load setting details.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $setting = \DB::table('settings')->where('id', $id)->first();
            if (!$setting) {
                return redirect()->route('core.index')
                    ->with('error', 'Setting not found.');
            }
            return view('core::edit', compact('setting'));
        } catch (\Exception $e) {
            Log::error('CoreController: Failed to edit setting - ' . $e->getMessage());
            return redirect()->route('core.index')
                ->with('error', 'Failed to load setting for editing.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $setting = \DB::table('settings')->where('id', $id)->first();
            if (!$setting) {
                return redirect()->route('core.index')
                    ->with('error', 'Setting not found.');
            }

            $validated = $request->validate([
                'key' => 'required|string|max:255|unique:settings,key,' . $id,
                'value' => 'nullable|string',
                'group' => 'nullable|string|max:255',
                'description' => 'nullable|string',
            ]);

            $validated['updated_at'] = now();

            \DB::table('settings')->where('id', $id)->update($validated);

            return redirect()->route('core.index')
                ->with('success', 'Setting updated successfully.');
        } catch (\Exception $e) {
            Log::error('CoreController: Failed to update setting - ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Failed to update setting: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $setting = \DB::table('settings')->where('id', $id)->first();
            if (!$setting) {
                return redirect()->route('core.index')
                    ->with('error', 'Setting not found.');
            }

            \DB::table('settings')->where('id', $id)->delete();

            return redirect()->route('core.index')
                ->with('success', 'Setting deleted successfully.');
        } catch (\Exception $e) {
            Log::error('CoreController: Failed to delete setting - ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete setting: ' . $e->getMessage());
        }
    }
}
