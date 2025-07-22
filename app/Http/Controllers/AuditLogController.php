<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\User;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $this->middleware('role:admin');
        $query = AuditLog::with('user');
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        $logs = $query->orderByDesc('created_at')->paginate(30);
        $users = User::orderBy('name')->get();
        $actions = AuditLog::select('action')->distinct()->pluck('action');
        return view('audit_logs.index', compact('logs', 'users', 'actions'));
    }
} 