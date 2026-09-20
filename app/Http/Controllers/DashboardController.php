<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\NavigationHelper;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Get user's primary role
        $primaryRole = $user->primaryRole;
        
        // Safely get user roles with error handling
        try {
            $userRoles = $user->roles ? $user->roles->pluck('name')->toArray() : [];
        } catch (Exception $e) {
            $userRoles = [];
        }

        // Role-based dashboard routing
        if (in_array('admin', $userRoles) || in_array('super_admin', $userRoles)) {
            return $this->adminDashboard();
        } elseif (in_array('teacher', $userRoles) || in_array('head_teacher', $userRoles)) {
            return $this->teacherDashboard();
        } elseif (in_array('student', $userRoles) || in_array('senior_student', $userRoles)) {
            return $this->studentDashboard();
        } elseif (in_array('parent', $userRoles) || in_array('guardian', $userRoles)) {
            return $this->parentDashboard();
        } elseif (in_array('hr_manager', $userRoles) || in_array('hr_officer', $userRoles)) {
            return $this->hrDashboard();
        } elseif (in_array('finance_manager', $userRoles) || in_array('accountant', $userRoles)) {
            return $this->financeDashboard();
        } elseif (in_array('librarian', $userRoles)) {
            return $this->libraryDashboard();
        } elseif (in_array('warden', $userRoles)) {
            return $this->hostelDashboard();
        } elseif (in_array('transport_manager', $userRoles)) {
            return $this->transportDashboard();
        } else {
            // Default dashboard for other roles
            return $this->defaultDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_students' => $this->safeCount('\Modules\Academic\Models\Student'),
            'total_teachers' => $this->safeCount('\Modules\HR\Models\Staff', function($query) {
                return $query->where('role', 'teacher');
            }),
            'total_schools' => \App\Models\School::count(),
            'recent_activities' => \App\Models\AuditLog::latest()->take(10)->get(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    private function teacherDashboard()
    {
        $user = Auth::user();
        $teacher = $this->safeGet('\Modules\HR\Models\Staff', ['user_id' => $user->id]);
        
        $stats = [
            'total_classes' => $this->safeCount('\Modules\Academic\Models\AcademicClass', function($query) use ($user) {
                return $query->where('teacher_id', $user->id);
            }),
            'total_students' => $this->safeCount('\Modules\Academic\Models\Student', function($query) use ($user) {
                return $query->whereHas('classes', function($q) use ($user) {
                    $q->where('teacher_id', $user->id);
                });
            }),
            'pending_exams' => $this->safeCount('\Modules\Examination\Models\Exam', function($query) use ($user) {
                return $query->where('teacher_id', $user->id)->where('status', 'pending');
            }),
            'recent_attendance' => $this->safeGet('\Modules\Attendance\Models\AttendanceRecord', ['teacher_id' => $user->id], 5),
        ];

        return view('dashboard.teacher', compact('stats', 'teacher'));
    }

    private function studentDashboard()
    {
        $user = Auth::user();
        $student = $this->safeGet('\Modules\Academic\Models\Student', ['user_id' => $user->id]);
        
        $stats = [
            'current_class' => $student ? $student->currentClass : null,
            'total_subjects' => $student ? $student->classes->flatMap->subjects->count() : 0,
            'attendance_rate' => $this->calculateAttendanceRate($student),
            'upcoming_exams' => $student ? $this->safeGet('\Modules\Examination\Models\Exam', function($query) use ($student) {
                return $query->whereHas('classes', function($q) use ($student) {
                    $q->where('student_id', $student->id);
                })->where('start_date', '>=', now());
            }, 5) : collect(),
        ];

        return view('dashboard.student', compact('stats', 'student'));
    }

    private function parentDashboard()
    {
        $user = Auth::user();
        $children = $this->safeGet('\Modules\Academic\Models\Student', function($query) use ($user) {
            return $query->whereHas('parents', function($q) use ($user) {
                $q->where('parent_id', $user->id);
            });
        });
        
        $stats = [
            'total_children' => $children ? $children->count() : 0,
            'total_fees_due' => $this->safeSum('\Modules\Finance\Models\Invoice', function($query) use ($children) {
                return $query->whereIn('student_id', $children ? $children->pluck('id') : [])->where('status', 'unpaid');
            }),
            'recent_activities' => $this->safeGet('\Modules\Academic\Models\AcademicRecord', function($query) use ($children) {
                return $query->whereIn('student_id', $children ? $children->pluck('id') : []);
            }, 5),
        ];

        return view('dashboard.parent', compact('stats', 'children'));
    }

    private function hrDashboard()
    {
        $stats = [
            'total_staff' => $this->safeCount('\Modules\HR\Models\Staff'),
            'active_staff' => $this->safeCount('\Modules\HR\Models\Staff', function($query) {
                return $query->where('status', 'active');
            }),
            'pending_leaves' => $this->safeCount('\Modules\HR\Models\Leave', function($query) {
                return $query->where('status', 'pending');
            }),
            'total_departments' => $this->safeCount('\Modules\HR\Models\Department'),
        ];

        return view('dashboard.hr', compact('stats'));
    }

    private function financeDashboard()
    {
        $stats = [
            'total_revenue' => $this->safeSum('\Modules\Finance\Models\Payment'),
            'pending_invoices' => $this->safeCount('\Modules\Finance\Models\Invoice', function($query) {
                return $query->where('status', 'unpaid');
            }),
            'total_fees' => $this->safeCount('\Modules\Finance\Models\Fee'),
            'monthly_revenue' => $this->safeSum('\Modules\Finance\Models\Payment', function($query) {
                return $query->whereMonth('payment_date', now()->month);
            }),
        ];

        return view('dashboard.finance', compact('stats'));
    }

    private function libraryDashboard()
    {
        $stats = [
            'total_books' => $this->safeCount('\Modules\Library\Models\Book'),
            'total_members' => $this->safeCount('\Modules\Library\Models\Member'),
            'active_borrowings' => $this->safeCount('\Modules\Library\Models\Borrowing', function($query) {
                return $query->where('status', 'borrowed');
            }),
            'overdue_books' => $this->safeCount('\Modules\Library\Models\Borrowing', function($query) {
                return $query->where('due_date', '<', now())->where('status', 'borrowed');
            }),
        ];

        return view('dashboard.library', compact('stats'));
    }

    private function hostelDashboard()
    {
        $stats = [
            'total_rooms' => $this->safeCount('\Modules\Hostel\Models\Room'),
            'occupied_rooms' => $this->safeCount('\Modules\Hostel\Models\RoomAllocation', function($query) {
                return $query->where('status', 'active');
            }),
            'total_wardens' => $this->safeCount('\Modules\Hostel\Models\Warden'),
            'pending_issues' => $this->safeCount('\Modules\Hostel\Models\HostelIssue', function($query) {
                return $query->where('status', 'pending');
            }),
        ];

        return view('dashboard.hostel', compact('stats'));
    }

    private function transportDashboard()
    {
        $stats = [
            'total_vehicles' => $this->safeCount('\Modules\Transport\Models\Vehicle'),
            'active_routes' => $this->safeCount('\Modules\Transport\Models\Route', function($query) {
                return $query->where('status', 'active');
            }),
            'total_drivers' => $this->safeCount('\Modules\Transport\Models\Driver'),
            'scheduled_trips' => $this->safeCount('\Modules\Transport\Models\Trip', function($query) {
                return $query->where('date', '>=', now()->toDateString());
            }),
        ];

        return view('dashboard.transport', compact('stats'));
    }

    private function defaultDashboard()
    {
        $user = Auth::user();
        $accessibleModules = NavigationHelper::getUserModules();
        
        return view('dashboard.default', compact('accessibleModules'));
    }

    /**
     * Global search across students, staff, books, fees, invoices, and payments.
     * Accessible via GET /search?q=keyword (web) or GET /api/v1/mobile/search?q=keyword (API).
     */
    public function globalSearch(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'q'     => 'required|string|min:2|max:100',
            'type'  => 'nullable|string|in:students,staff,books,fees,invoices,payments,all',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $query = $request->input('q');
        $type  = $request->input('type', 'all');
        $limit = $request->input('limit', 10);

        try {
            $results = [];

            if (in_array($type, ['all', 'students'], true)) {
                $results['students'] = $this->searchStudentsGlobal($query, $limit);
            }
            if (in_array($type, ['all', 'staff'], true)) {
                $results['staff'] = $this->searchStaffGlobal($query, $limit);
            }
            if (in_array($type, ['all', 'books'], true)) {
                $results['books'] = $this->searchBooksGlobal($query, $limit);
            }
            if (in_array($type, ['all', 'fees'], true)) {
                $results['fees'] = $this->searchFeesGlobal($query, $limit);
            }
            if (in_array($type, ['all', 'invoices'], true)) {
                $results['invoices'] = $this->searchInvoicesGlobal($query, $limit);
            }
            if (in_array($type, ['all', 'payments'], true)) {
                $results['payments'] = $this->searchPaymentsGlobal($query, $limit);
            }

            $totalCount = 0;
            foreach ($results as $items) {
                $totalCount += is_array($items) ? count($items) : $items->count();
            }

            return response()->json([
                'success' => true,
                'message' => 'Search completed successfully',
                'data'    => [
                    'query'   => $query,
                    'type'    => $type,
                    'total'   => $totalCount,
                    'results' => $results,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function searchStudentsGlobal(string $query, int $limit): \Illuminate\Support\Collection
    {
        try {
            return \Modules\Academic\Models\Student::with(['class', 'section'])
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('admission_number', 'like', "%{$query}%")
                      ->orWhere('student_id', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function searchStaffGlobal(string $query, int $limit): \Illuminate\Support\Collection
    {
        try {
            return \Modules\HR\Models\Staff::with(['department', 'role'])
                ->where(function ($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%")
                      ->orWhere('other_names', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('phone', 'like', "%{$query}%")
                      ->orWhere('staff_id', 'like', "%{$query}%");
                })
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function searchBooksGlobal(string $query, int $limit): \Illuminate\Support\Collection
    {
        try {
            return \App\Models\Modules\Library\Models\Book::with(['author', 'category'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('isbn', 'like', "%{$query}%")
                      ->orWhereHas('author', fn ($aq) => $aq->where('name', 'like', "%{$query}%"));
                })
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function searchFeesGlobal(string $query, int $limit): \Illuminate\Support\Collection
    {
        try {
            return \Modules\Finance\Models\Fee::with(['category', 'type'])
                ->where('name', 'like', "%{$query}%")
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function searchInvoicesGlobal(string $query, int $limit): \Illuminate\Support\Collection
    {
        try {
            return \Modules\Finance\Models\Invoice::with(['student'])
                ->where('invoice_number', 'like', "%{$query}%")
                ->orWhereHas('student', fn ($sq) => $sq->where('name', 'like', "%{$query}%"))
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function searchPaymentsGlobal(string $query, int $limit): \Illuminate\Support\Collection
    {
        try {
            return \Modules\Finance\Models\Payment::with(['invoice', 'invoice.student'])
                ->where('reference_number', 'like', "%{$query}%")
                ->orWhereHas('invoice', fn ($iq) => $iq->where('invoice_number', 'like', "%{$query}%"))
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function calculateAttendanceRate($student)
    {
        if (!$student) return 0;
        
        $totalSessions = $this->safeCount('\Modules\Attendance\Models\AttendanceRecord', function($query) use ($student) {
            return $query->where('student_id', $student->id);
        });
        
        $presentSessions = $this->safeCount('\Modules\Attendance\Models\AttendanceRecord', function($query) use ($student) {
            return $query->where('student_id', $student->id)->where('status', 'present');
        });
        
        return $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100, 2) : 0;
    }

    /**
     * Safely count records from a model class
     */
    private function safeCount($modelClass, $callback = null)
    {
        try {
            if (!class_exists($modelClass)) {
                return 0;
            }
            
            $query = $modelClass::query();
            
            if ($callback && is_callable($callback)) {
                $query = $callback($query);
            }
            
            return $query->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Safely get records from a model class
     */
    private function safeGet($modelClass, $conditions = null, $limit = null)
    {
        try {
            if (!class_exists($modelClass)) {
                return collect();
            }
            
            $query = $modelClass::query();
            
            if (is_array($conditions)) {
                foreach ($conditions as $key => $value) {
                    $query->where($key, $value);
                }
            } elseif (is_callable($conditions)) {
                $query = $conditions($query);
            }
            
            // If no limit is specified and we're looking for a unique record (like by user_id),
            // return the first result as a single model instance
            if (!$limit) {
                return $query->first();
            }
            
            // Otherwise, return a collection with the specified limit
            return $query->latest()->take($limit)->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Safely sum a column from a model class
     */
    private function safeSum($modelClass, $callback = null, $column = 'amount')
    {
        try {
            if (!class_exists($modelClass)) {
                return 0;
            }
            
            $query = $modelClass::query();
            
            if ($callback && is_callable($callback)) {
                $query = $callback($query);
            }
            
            return $query->sum($column);
        } catch (\Exception $e) {
            return 0;
        }
    }
} 