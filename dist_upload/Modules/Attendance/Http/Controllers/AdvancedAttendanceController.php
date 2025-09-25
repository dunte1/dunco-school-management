<?php

namespace Modules\Attendance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Attendance\Models\AttendanceBiometricLog;
use Modules\Attendance\Models\AttendanceQrLog;
use Modules\Attendance\Models\AttendanceFaceLog;
use Modules\Attendance\Models\AttendanceAcknowledgment;

class AdvancedAttendanceController extends Controller
{
    public function biometricEvent(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|integer',
            'device_id' => 'nullable|string',
            'scanned_at' => 'required|date',
            'status' => 'required|string',
            'raw_data' => 'nullable|array',
        ]);
        $log = AttendanceBiometricLog::create($data);
        return response()->json(['success' => true, 'log' => $log]);
    }
    public function qrEvent(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|integer',
            'session_id' => 'required|integer',
            'scanned_at' => 'required|date',
            'status' => 'required|string',
        ]);
        $log = AttendanceQrLog::create($data);
        return response()->json(['success' => true, 'log' => $log]);
    }
    public function faceEvent(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|integer',
            'image_hash' => 'required|string',
            'scanned_at' => 'required|date',
            'status' => 'required|string',
        ]);
        $log = AttendanceFaceLog::create($data);
        return response()->json(['success' => true, 'log' => $log]);
    }
    public function parentAcknowledge(Request $request)
    {
        $data = $request->validate([
            'attendance_record_id' => 'required|integer',
            'parent_id' => 'required|integer',
            'acknowledged_at' => 'nullable|date',
            'channel' => 'nullable|string',
        ]);
        $data['acknowledged_at'] = $data['acknowledged_at'] ?? now();
        $ack = AttendanceAcknowledgment::create($data);
        return response()->json(['success' => true, 'acknowledgment' => $ack]);
    }
    public function getBiometricLogs(Request $request)
    {
        $query = \Modules\Attendance\Models\AttendanceBiometricLog::query();
        if ($request->filled('student_id')) $query->where('student_id', $request->student_id);
        if ($request->filled('device_id')) $query->where('device_id', $request->device_id);
        if ($request->filled('date')) $query->whereDate('scanned_at', $request->date);
        return response()->json($query->orderBy('scanned_at', 'desc')->paginate(30));
    }
    public function getQrLogs(Request $request)
    {
        $query = \Modules\Attendance\Models\AttendanceQrLog::query();
        if ($request->filled('student_id')) $query->where('student_id', $request->student_id);
        if ($request->filled('session_id')) $query->where('session_id', $request->session_id);
        if ($request->filled('date')) $query->whereDate('scanned_at', $request->date);
        return response()->json($query->orderBy('scanned_at', 'desc')->paginate(30));
    }
    public function getFaceLogs(Request $request)
    {
        $query = \Modules\Attendance\Models\AttendanceFaceLog::query();
        if ($request->filled('student_id')) $query->where('student_id', $request->student_id);
        if ($request->filled('image_hash')) $query->where('image_hash', $request->image_hash);
        if ($request->filled('date')) $query->whereDate('scanned_at', $request->date);
        return response()->json($query->orderBy('scanned_at', 'desc')->paginate(30));
    }
    public function getAcknowledgmentLogs(Request $request)
    {
        $query = \Modules\Attendance\Models\AttendanceAcknowledgment::query();
        if ($request->filled('attendance_record_id')) $query->where('attendance_record_id', $request->attendance_record_id);
        if ($request->filled('parent_id')) $query->where('parent_id', $request->parent_id);
        if ($request->filled('date')) $query->whereDate('acknowledged_at', $request->date);
        return response()->json($query->orderBy('acknowledged_at', 'desc')->paginate(30));
    }
} 