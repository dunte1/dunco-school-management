<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Backup\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        Gate::authorize('backups.view');
        $service = app(BackupService::class);
        $list = $service->listBackups();
        return view('admin.backups.index', [
            'backups' => $list,
        ]);
    }

    public function create(Request $request)
    {
        Gate::authorize('backups.create');
        return view('admin.backups.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('backups.create');
        $validated = $request->validate([
            'type' => 'required|in:full,incremental,differential,custom',
            'modules' => 'nullable|array',
            'disk' => 'nullable|string',
            'encrypt' => 'nullable|boolean',
        ]);
        $service = app(BackupService::class);
        $job = $service->runBackup($validated['type'], $validated['modules'] ?? [], $validated['disk'] ?? null, (bool)($validated['encrypt'] ?? false));
        return redirect()->route('admin.backups.index')->with('status', 'Backup started.');
    }

    public function download(Request $request)
    {
        Gate::authorize('backups.download');
        $path = $request->query('path', $request->input('path'));
        if (!$path) {
            abort(400, 'Missing backup path');
        }
        $service = app(BackupService::class);
        return $service->streamBackup($path);
    }

    public function destroy(Request $request)
    {
        Gate::authorize('backups.delete');
        $path = $request->input('path');
        if (!$path) {
            return back()->with('status', 'Missing backup path.');
        }
        $service = app(BackupService::class);
        $service->deleteBackup($path);
        return back()->with('status', 'Backup deleted.');
    }

    public function restore(Request $request)
    {
        Gate::authorize('backups.restore');
        $validated = $request->validate([
            'path' => 'required|string',
            'mode' => 'required|in:full,partial',
            'modules' => 'nullable|array',
        ]);
        $service = app(BackupService::class);
        $service->restore($validated['path'], $validated['mode'], $validated['modules'] ?? []);
        return back()->with('status', 'Restore initiated.');
    }
}


