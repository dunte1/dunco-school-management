<?php

namespace Modules\Settings\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings::index');
    }

    public function general()
    {
        return view('settings::general');
    }

    public function academic()
    {
        return view('settings::academic');
    }

    public function finance()
    {
        return view('settings::finance');
    }

    public function notifications()
    {
        return view('settings::notifications');
    }

    public function security()
    {
        return view('settings::security');
    }

    public function backup()
    {
        return view('settings::backup');
    }

    public function update(Request $request)
    {
        // Settings update logic
        return redirect()->route('settings.index')->with('success', 'Settings updated successfully');
    }

    public function backupCreate()
    {
        // Backup creation logic
        return redirect()->route('settings.backup')->with('success', 'Backup created successfully');
    }

    public function backupRestore(Request $request)
    {
        // Backup restore logic
        return redirect()->route('settings.backup')->with('success', 'Backup restored successfully');
    }
}
