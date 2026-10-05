<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MaintenanceController extends Controller
{
    public function index()
    {
        $this->middleware(['auth','role:admin']);
        $isDown = app()->isDownForMaintenance();
        return view('admin.maintenance.index', compact('isDown'));
    }

    public function activate(Request $request)
    {
        $this->middleware(['auth','role:admin']);
        $secret = bin2hex(random_bytes(5));
        Artisan::call('down', ['--secret' => $secret]);
        return back()->with('success', 'Maintenance mode activated. Secret access URL: ' . url($secret));
    }

    public function deactivate()
    {
        $this->middleware(['auth','role:admin']);
        Artisan::call('up');
        return back()->with('success', 'Maintenance mode deactivated.');
    }

    public function updateAppSettings(\Illuminate\Http\Request $request)
    {
        $this->middleware(['auth','role:admin']);
        $pairs = $request->only([
            'app.enable_online_exams',
            'app.enable_payments',
            'app.min_version',
            'app.force_update',
            'notifications.birthday.enabled',
            'notifications.birthday.template',
        ]);
        foreach ($pairs as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], [
                'value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value,
                'type' => is_numeric($value) ? 'integer' : (in_array($value, ['0','1','true','false']) ? 'boolean' : 'string'),
                'description' => 'Mobile app setting',
            ]);
        }
        return back()->with('success', 'App settings updated.');
    }
}


