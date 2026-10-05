<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceAlertRuleStoreRequest;
use App\Http\Requests\AttendanceAlertRuleUpdateRequest;
use App\Models\AttendanceAlertRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AttendanceAlertRuleController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendanceAlertRule::query();
        if ($request->filled('school_id')) {
            $query->where('school_id', (int) $request->input('school_id'));
        }
        return response()->json($query->orderByDesc('id')->paginate($request->integer('per_page', 15)));
    }

    public function store(AttendanceAlertRuleStoreRequest $request)
    {
        $rule = AttendanceAlertRule::create($request->validated());
        return response()->json($rule, 201);
    }

    public function show(AttendanceAlertRule $alertRule)
    {
        return response()->json($alertRule);
    }

    public function update(AttendanceAlertRuleUpdateRequest $request, AttendanceAlertRule $alertRule)
    {
        $alertRule->fill($request->validated());
        $alertRule->save();
        return response()->json($alertRule);
    }

    public function destroy(AttendanceAlertRule $alertRule)
    {
        $alertRule->delete();
        return response()->json(['deleted' => true]);
    }

    public function trigger(Request $request)
    {
        $schoolId = $request->input('school_id');
        $params = $schoolId ? ' '.(int)$schoolId : '';
        Artisan::call('attendance:eval-alert-rules'.$params);
        return response()->json(['message' => trim(Artisan::output()) ?: 'Triggered attendance alert rules evaluation']);
    }
}


