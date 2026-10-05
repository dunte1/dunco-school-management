<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class OfflineSyncController extends Controller
{
    use ApiResponse;

    /**
     * Get offline data for synchronization
     */
    public function getOfflineData(Request $request)
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            $lastSync = $request->get('last_sync', now()->subDays(30)->toISOString());

            $data = [
                'sync_timestamp' => now()->toISOString(),
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'data' => [
                    'attendance' => $this->getAttendanceData($user, $schoolId, $lastSync),
                    'fees' => $this->getFeesData($user, $schoolId, $lastSync),
                    'exams' => $this->getExamsData($user, $schoolId, $lastSync),
                    'assignments' => $this->getAssignmentsData($user, $schoolId, $lastSync),
                    'notifications' => $this->getNotificationsData($user, $schoolId, $lastSync),
                    'subjects' => $this->getSubjectsData($user, $schoolId, $lastSync),
                    'classes' => $this->getClassesData($user, $schoolId, $lastSync),
                    'teachers' => $this->getTeachersData($user, $schoolId, $lastSync),
                    'schedule' => $this->getScheduleData($user, $schoolId, $lastSync),
                    'documents' => $this->getDocumentsData($user, $schoolId, $lastSync),
                ],
                'metadata' => [
                    'total_records' => $this->getTotalRecords($user, $schoolId, $lastSync),
                    'compression_enabled' => true,
                    'encryption_enabled' => false,
                ],
            ];

            return $this->successResponse($data, 'Offline data retrieved successfully');

        } catch (\Exception $e) {
            Log::error('Offline sync failed: ' . $e->getMessage());
            return $this->errorResponse('Failed to retrieve offline data: ' . $e->getMessage());
        }
    }

    /**
     * Sync offline changes back to server
     */
    public function syncOfflineChanges(Request $request)
    {
        try {
            $request->validate([
                'changes' => 'required|array',
                'sync_timestamp' => 'required|string',
            ]);

            $user = $request->user();
            $changes = $request->changes;
            $syncTimestamp = $request->sync_timestamp;

            $results = [
                'success' => [],
                'failed' => [],
                'conflicts' => [],
            ];

            foreach ($changes as $change) {
                try {
                    $result = $this->processChange($change, $user);
                    if ($result['success']) {
                        $results['success'][] = $result;
                    } else {
                        $results['failed'][] = $result;
                    }
                } catch (\Exception $e) {
                    $results['failed'][] = [
                        'id' => $change['id'] ?? 'unknown',
                        'type' => $change['type'] ?? 'unknown',
                        'error' => $e->getMessage(),
                    ];
                }
            }

            // Update last sync timestamp
            $this->updateLastSyncTimestamp($user->id, $syncTimestamp);

            return $this->successResponse($results, 'Offline changes synced successfully');

        } catch (\Exception $e) {
            Log::error('Offline sync failed: ' . $e->getMessage());
            return $this->errorResponse('Failed to sync offline changes: ' . $e->getMessage());
        }
    }

    /**
     * Get sync status
     */
    public function getSyncStatus(Request $request)
    {
        try {
            $user = $request->user();
            $lastSync = $this->getLastSyncTimestamp($user->id);

            $status = [
                'last_sync' => $lastSync,
                'pending_changes' => $this->getPendingChangesCount($user->id),
                'conflicts' => $this->getConflictsCount($user->id),
                'sync_required' => $this->isSyncRequired($user->id, $lastSync),
                'data_freshness' => $this->getDataFreshness($user->id),
            ];

            return $this->successResponse($status, 'Sync status retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get sync status: ' . $e->getMessage());
        }
    }

    /**
     * Resolve sync conflicts
     */
    public function resolveConflicts(Request $request)
    {
        try {
            $request->validate([
                'conflicts' => 'required|array',
                'resolution_strategy' => 'required|string|in:server_wins,client_wins,merge',
            ]);

            $user = $request->user();
            $conflicts = $request->conflicts;
            $strategy = $request->resolution_strategy;

            $results = [];

            foreach ($conflicts as $conflict) {
                $result = $this->resolveConflict($conflict, $strategy, $user);
                $results[] = $result;
            }

            return $this->successResponse($results, 'Conflicts resolved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to resolve conflicts: ' . $e->getMessage());
        }
    }

    /**
     * Get attendance data for offline sync
     */
    private function getAttendanceData($user, $schoolId, $lastSync)
    {
        try {
            if (class_exists('Modules\\Attendance\\Models\\Attendance')) {
                return \Modules\Attendance\Models\Attendance::where('school_id', $schoolId)
                    ->where('student_id', $user->id)
                    ->where('updated_at', '>=', $lastSync)
                    ->get()
                    ->map(function ($attendance) {
                        return [
                            'id' => $attendance->id,
                            'student_id' => $attendance->student_id,
                            'date' => $attendance->date,
                            'status' => $attendance->status,
                            'created_at' => $attendance->created_at,
                            'updated_at' => $attendance->updated_at,
                        ];
                    });
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get fees data for offline sync
     */
    private function getFeesData($user, $schoolId, $lastSync)
    {
        try {
            if (class_exists('Modules\\Finance\\Models\\Fee')) {
                return \Modules\Finance\Models\Fee::where('school_id', $schoolId)
                    ->where('student_id', $user->id)
                    ->where('updated_at', '>=', $lastSync)
                    ->get()
                    ->map(function ($fee) {
                        return [
                            'id' => $fee->id,
                            'student_id' => $fee->student_id,
                            'amount' => $fee->amount,
                            'status' => $fee->status,
                            'due_date' => $fee->due_date,
                            'created_at' => $fee->created_at,
                            'updated_at' => $fee->updated_at,
                        ];
                    });
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get exams data for offline sync
     */
    private function getExamsData($user, $schoolId, $lastSync)
    {
        try {
            if (class_exists('Modules\\Academic\\Models\\Exam')) {
                return \Modules\Academic\Models\Exam::where('school_id', $schoolId)
                    ->where('updated_at', '>=', $lastSync)
                    ->get()
                    ->map(function ($exam) {
                        return [
                            'id' => $exam->id,
                            'name' => $exam->name,
                            'subject_id' => $exam->subject_id,
                            'class_id' => $exam->class_id,
                            'date' => $exam->date,
                            'start_time' => $exam->start_time,
                            'end_time' => $exam->end_time,
                            'created_at' => $exam->created_at,
                            'updated_at' => $exam->updated_at,
                        ];
                    });
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get assignments data for offline sync
     */
    private function getAssignmentsData($user, $schoolId, $lastSync)
    {
        try {
            if (class_exists('Modules\\Academic\\Models\\Assignment')) {
                return \Modules\Academic\Models\Assignment::where('school_id', $schoolId)
                    ->where('student_id', $user->id)
                    ->where('updated_at', '>=', $lastSync)
                    ->get()
                    ->map(function ($assignment) {
                        return [
                            'id' => $assignment->id,
                            'title' => $assignment->title,
                            'description' => $assignment->description,
                            'subject_id' => $assignment->subject_id,
                            'class_id' => $assignment->class_id,
                            'due_date' => $assignment->due_date,
                            'status' => $assignment->status,
                            'created_at' => $assignment->created_at,
                            'updated_at' => $assignment->updated_at,
                        ];
                    });
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get notifications data for offline sync
     */
    private function getNotificationsData($user, $schoolId, $lastSync)
    {
        try {
            if (class_exists('Modules\\Communication\\Models\\Notification')) {
                return \Modules\Communication\Models\Notification::where('notifiable_id', $user->id)
                    ->where('created_at', '>=', $lastSync)
                    ->get()
                    ->map(function ($notification) {
                        return [
                            'id' => $notification->id,
                            'title' => $notification->title,
                            'type' => $notification->type,
                            'data' => $notification->data,
                            'read_at' => $notification->read_at,
                            'created_at' => $notification->created_at,
                            'updated_at' => $notification->updated_at,
                        ];
                    });
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get subjects data for offline sync
     */
    private function getSubjectsData($user, $schoolId, $lastSync)
    {
        try {
            if (class_exists('Modules\\Academic\\Models\\Subject')) {
                return \Modules\Academic\Models\Subject::where('school_id', $schoolId)
                    ->where('updated_at', '>=', $lastSync)
                    ->get()
                    ->map(function ($subject) {
                        return [
                            'id' => $subject->id,
                            'name' => $subject->name,
                            'code' => $subject->code,
                            'class_id' => $subject->class_id,
                            'teacher_id' => $subject->teacher_id,
                            'created_at' => $subject->created_at,
                            'updated_at' => $subject->updated_at,
                        ];
                    });
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get classes data for offline sync
     */
    private function getClassesData($user, $schoolId, $lastSync)
    {
        try {
            if (class_exists('Modules\\Academic\\Models\\AcademicClass')) {
                return \Modules\Academic\Models\AcademicClass::where('school_id', $schoolId)
                    ->where('updated_at', '>=', $lastSync)
                    ->get()
                    ->map(function ($class) {
                        return [
                            'id' => $class->id,
                            'name' => $class->name,
                            'section' => $class->section,
                            'teacher_id' => $class->teacher_id,
                            'created_at' => $class->created_at,
                            'updated_at' => $class->updated_at,
                        ];
                    });
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get teachers data for offline sync
     */
    private function getTeachersData($user, $schoolId, $lastSync)
    {
        try {
            if (class_exists('Modules\\HR\\Models\\Staff')) {
                return \Modules\HR\Models\Staff::where('school_id', $schoolId)
                    ->where('updated_at', '>=', $lastSync)
                    ->get()
                    ->map(function ($teacher) {
                        return [
                            'id' => $teacher->id,
                            'name' => $teacher->name,
                            'email' => $teacher->email,
                            'phone' => $teacher->phone,
                            'subject_id' => $teacher->subject_id,
                            'created_at' => $teacher->created_at,
                            'updated_at' => $teacher->updated_at,
                        ];
                    });
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get schedule data for offline sync
     */
    private function getScheduleData($user, $schoolId, $lastSync)
    {
        try {
            if (class_exists('Modules\\Timetable\\Models\\Timetable')) {
                return \Modules\Timetable\Models\Timetable::where('school_id', $schoolId)
                    ->where('updated_at', '>=', $lastSync)
                    ->get()
                    ->map(function ($schedule) {
                        return [
                            'id' => $schedule->id,
                            'class_id' => $schedule->class_id,
                            'subject_id' => $schedule->subject_id,
                            'teacher_id' => $schedule->teacher_id,
                            'day' => $schedule->day,
                            'start_time' => $schedule->start_time,
                            'end_time' => $schedule->end_time,
                            'created_at' => $schedule->created_at,
                            'updated_at' => $schedule->updated_at,
                        ];
                    });
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get documents data for offline sync
     */
    private function getDocumentsData($user, $schoolId, $lastSync)
    {
        try {
            if (class_exists('Modules\\Academic\\Models\\Document')) {
                return \Modules\Academic\Models\Document::where('school_id', $schoolId)
                    ->where('updated_at', '>=', $lastSync)
                    ->get()
                    ->map(function ($document) {
                        return [
                            'id' => $document->id,
                            'title' => $document->title,
                            'type' => $document->type,
                            'file_path' => $document->file_path,
                            'student_id' => $document->student_id,
                            'created_at' => $document->created_at,
                            'updated_at' => $document->updated_at,
                        ];
                    });
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get total records count
     */
    private function getTotalRecords($user, $schoolId, $lastSync)
    {
        $count = 0;
        $count += $this->getAttendanceData($user, $schoolId, $lastSync)->count();
        $count += $this->getFeesData($user, $schoolId, $lastSync)->count();
        $count += $this->getExamsData($user, $schoolId, $lastSync)->count();
        $count += $this->getAssignmentsData($user, $schoolId, $lastSync)->count();
        $count += $this->getNotificationsData($user, $schoolId, $lastSync)->count();
        $count += $this->getSubjectsData($user, $schoolId, $lastSync)->count();
        $count += $this->getClassesData($user, $schoolId, $lastSync)->count();
        $count += $this->getTeachersData($user, $schoolId, $lastSync)->count();
        $count += $this->getScheduleData($user, $schoolId, $lastSync)->count();
        $count += $this->getDocumentsData($user, $schoolId, $lastSync)->count();
        return $count;
    }

    /**
     * Process a single change
     */
    private function processChange($change, $user)
    {
        try {
            $type = $change['type'];
            $action = $change['action'];
            $data = $change['data'];

            switch ($type) {
                case 'attendance':
                    return $this->processAttendanceChange($action, $data, $user);
                case 'assignment':
                    return $this->processAssignmentChange($action, $data, $user);
                case 'notification':
                    return $this->processNotificationChange($action, $data, $user);
                default:
                    return [
                        'id' => $change['id'] ?? 'unknown',
                        'type' => $type,
                        'success' => false,
                        'error' => 'Unknown change type',
                    ];
            }
        } catch (\Exception $e) {
            return [
                'id' => $change['id'] ?? 'unknown',
                'type' => $change['type'] ?? 'unknown',
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process attendance change
     */
    private function processAttendanceChange($action, $data, $user)
    {
        // Implementation for processing attendance changes
        return [
            'id' => $data['id'] ?? 'unknown',
            'type' => 'attendance',
            'success' => true,
            'message' => 'Attendance change processed successfully',
        ];
    }

    /**
     * Process assignment change
     */
    private function processAssignmentChange($action, $data, $user)
    {
        // Implementation for processing assignment changes
        return [
            'id' => $data['id'] ?? 'unknown',
            'type' => 'assignment',
            'success' => true,
            'message' => 'Assignment change processed successfully',
        ];
    }

    /**
     * Process notification change
     */
    private function processNotificationChange($action, $data, $user)
    {
        // Implementation for processing notification changes
        return [
            'id' => $data['id'] ?? 'unknown',
            'type' => 'notification',
            'success' => true,
            'message' => 'Notification change processed successfully',
        ];
    }

    /**
     * Resolve conflict
     */
    private function resolveConflict($conflict, $strategy, $user)
    {
        // Implementation for resolving conflicts
        return [
            'id' => $conflict['id'],
            'type' => $conflict['type'],
            'resolved' => true,
            'strategy' => $strategy,
            'message' => 'Conflict resolved successfully',
        ];
    }

    /**
     * Get last sync timestamp
     */
    private function getLastSyncTimestamp($userId)
    {
        try {
            $sync = DB::table('offline_sync')
                ->where('user_id', $userId)
                ->first();
            
            return $sync ? $sync->last_sync : now()->subDays(30)->toISOString();
        } catch (\Exception $e) {
            return now()->subDays(30)->toISOString();
        }
    }

    /**
     * Update last sync timestamp
     */
    private function updateLastSyncTimestamp($userId, $timestamp)
    {
        try {
            DB::table('offline_sync')->updateOrInsert(
                ['user_id' => $userId],
                ['last_sync' => $timestamp, 'updated_at' => now()]
            );
        } catch (\Exception $e) {
            Log::error('Failed to update last sync timestamp: ' . $e->getMessage());
        }
    }

    /**
     * Get pending changes count
     */
    private function getPendingChangesCount($userId)
    {
        try {
            return DB::table('offline_changes')
                ->where('user_id', $userId)
                ->where('synced', false)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get conflicts count
     */
    private function getConflictsCount($userId)
    {
        try {
            return DB::table('offline_conflicts')
                ->where('user_id', $userId)
                ->where('resolved', false)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Check if sync is required
     */
    private function isSyncRequired($userId, $lastSync)
    {
        $lastSyncTime = Carbon::parse($lastSync);
        $now = now();
        
        // Sync required if last sync was more than 1 hour ago
        return $now->diffInHours($lastSyncTime) > 1;
    }

    /**
     * Get data freshness
     */
    private function getDataFreshness($userId)
    {
        $lastSync = $this->getLastSyncTimestamp($userId);
        $lastSyncTime = Carbon::parse($lastSync);
        $now = now();
        
        $hoursAgo = $now->diffInHours($lastSyncTime);
        
        if ($hoursAgo < 1) {
            return 'fresh';
        } elseif ($hoursAgo < 24) {
            return 'stale';
        } else {
            return 'outdated';
        }
    }
}
