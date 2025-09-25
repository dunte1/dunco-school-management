<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        $auditLogs = AuditLog::latest()->paginate(20);
        return view('core::audit_logs.index', compact('auditLogs'));
    }

    public function show($id)
    {
        $log = AuditLog::findOrFail($id);
        return view('core::audit_logs.show', compact('log'));
    }

    public function fetch(Request $request)
    {
        $query = AuditLog::query();
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('action') && $request->action) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }
        $auditLogs = $query->latest()->paginate(20);
        return response()->json([
            'data' => $auditLogs->items(),
            'pagination' => [
                'current_page' => $auditLogs->currentPage(),
                'last_page' => $auditLogs->lastPage(),
                'per_page' => $auditLogs->perPage(),
                'total' => $auditLogs->total(),
            ],
        ]);
    }
} 