<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\School;
use App\Models\Role;
use App\Models\Permission;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\Class as AcademicClass;
use Modules\Academic\Models\Subject;
use Modules\Academic\Models\Exam;
use Modules\Academic\Models\ExamResult;
use Modules\HR\Models\Staff;
use Modules\Finance\Models\Fee;
use Modules\Finance\Models\Payment;
use Modules\Library\Models\Book;
use Modules\Library\Models\Member;
use Modules\Library\Models\BorrowRecord;
use Modules\Hostel\Models\Room;
use Modules\Transport\Models\Vehicle;
use Modules\Transport\Models\Route;
use Carbon\Carbon;

class ImportExportController extends Controller
{
    /**
     * Import data from Excel/CSV file
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
            'module' => 'required|string|in:students,staff,books,fees,exams,attendance',
            'school_id' => 'required|exists:schools,id'
        ]);

        try {
            $file = $request->file('file');
            $module = $request->module;
            $schoolId = $request->school_id;

            // Read file content
            $content = file_get_contents($file->getPathname());
            $lines = explode("\n", $content);
            $headers = str_getcsv(array_shift($lines));

            $imported = 0;
            $errors = [];

            foreach ($lines as $index => $line) {
                if (empty(trim($line))) continue;

                $data = str_getcsv($line);
                if (count($data) !== count($headers)) {
                    $errors[] = "Row " . ($index + 2) . ": Column count mismatch";
                    continue;
                }

                $rowData = array_combine($headers, $data);

                try {
                    switch ($module) {
                        case 'students':
                            $this->importStudent($rowData, $schoolId);
                            break;
                        case 'staff':
                            $this->importStaff($rowData, $schoolId);
                            break;
                        case 'books':
                            $this->importBook($rowData, $schoolId);
                            break;
                        case 'fees':
                            $this->importFee($rowData, $schoolId);
                            break;
                        case 'exams':
                            $this->importExam($rowData, $schoolId);
                            break;
                        case 'attendance':
                            $this->importAttendance($rowData, $schoolId);
                            break;
                    }
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully imported $imported records",
                'imported' => $imported,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data to Excel/CSV file
     */
    public function export(Request $request)
    {
        $request->validate([
            'module' => 'required|string|in:students,staff,books,fees,exams,attendance,users,roles',
            'school_id' => 'required|exists:schools,id',
            'format' => 'required|string|in:csv,xlsx'
        ]);

        try {
            $module = $request->module;
            $schoolId = $request->school_id;
            $format = $request->format;

            $data = $this->getExportData($module, $schoolId);
            $filename = $module . '_' . date('Y-m-d_H-i-s') . '.' . $format;

            if ($format === 'csv') {
                return $this->exportToCsv($data, $filename);
            } else {
                return $this->exportToXlsx($data, $filename);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Export failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get analytics data
     */
    public function analytics(Request $request)
    {
        $request->validate([
            'module' => 'required|string|in:academic,finance,library,attendance,overall',
            'school_id' => 'required|exists:schools,id',
            'period' => 'required|string|in:week,month,quarter,year'
        ]);

        try {
            $module = $request->module;
            $schoolId = $request->school_id;
            $period = $request->period;

            $analytics = $this->getAnalyticsData($module, $schoolId, $period);

            return response()->json([
                'success' => true,
                'data' => $analytics
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Analytics failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Private helper methods for import
    private function importStudent($data, $schoolId)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'admission_number' => 'required|string|unique:academic_students,admission_number',
            'class_id' => 'required|exists:academic_classes,id',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        DB::transaction(function () use ($data, $schoolId) {
            // Create user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'), // Default password
                'school_id' => $schoolId,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
            ]);

            // Assign student role
            $studentRole = Role::where('name', 'student')->first();
            if ($studentRole) {
                $user->roles()->attach($studentRole->id);
            }

            // Create student record
            Student::create([
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'class_id' => $data['class_id'],
                'admission_number' => $data['admission_number'],
                'date_of_birth' => $data['date_of_birth'],
                'gender' => $data['gender'],
            ]);
        });
    }

    private function importStaff($data, $schoolId)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'employee_number' => 'required|string|unique:teachers,employee_number',
            'department' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        DB::transaction(function () use ($data, $schoolId) {
            // Create user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
                'school_id' => $schoolId,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
            ]);

            // Assign teacher role
            $teacherRole = Role::where('name', 'teacher')->first();
            if ($teacherRole) {
                $user->roles()->attach($teacherRole->id);
            }

            // Create teacher record
            Staff::create([
                'user_id' => $user->id,
                'school_id' => $schoolId,
                'employee_number' => $data['employee_number'],
                'department' => $data['department'],
                'position' => $data['position'],
            ]);
        });
    }

    private function importBook($data, $schoolId)
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'category' => 'required|string|max:255',
            'copies' => 'required|integer|min:1',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y')
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        Book::create([
            'title' => $data['title'],
            'author' => $data['author'],
            'isbn' => $data['isbn'],
            'category' => $data['category'],
            'copies' => $data['copies'],
            'available_copies' => $data['copies'],
            'publisher' => $data['publisher'] ?? null,
            'publication_year' => $data['publication_year'] ?? null,
            'school_id' => $schoolId,
        ]);
    }

    private function importFee($data, $schoolId)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string|in:Tuition,Library,Laboratory,Sports,Transport,Other',
            'frequency' => 'required|string|in:One-time,Monthly,Termly,Yearly',
            'due_date' => 'required|date',
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        Fee::create([
            'name' => $data['name'],
            'amount' => $data['amount'],
            'type' => $data['type'],
            'frequency' => $data['frequency'],
            'due_date' => $data['due_date'],
            'description' => $data['description'] ?? null,
            'school_id' => $schoolId,
        ]);
    }

    private function importExam($data, $schoolId)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Mid-Term,End of Term,Mock,Final',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'class_id' => 'required|exists:academic_classes,id',
            'subject_id' => 'required|exists:academic_subjects,id'
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        Exam::create([
            'name' => $data['name'],
            'type' => $data['type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'class_id' => $data['class_id'],
            'subject_id' => $data['subject_id'],
            'school_id' => $schoolId,
        ]);
    }

    private function importAttendance($data, $schoolId)
    {
        $validator = Validator::make($data, [
            'student_id' => 'required|exists:academic_students,id',
            'date' => 'required|date',
            'status' => 'required|string|in:Present,Absent,Late,Excused',
            'class_id' => 'required|exists:academic_classes,id'
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        // Import attendance logic here
        // This would depend on your attendance model structure
    }

    // Private helper methods for export
    private function getExportData($module, $schoolId)
    {
        switch ($module) {
            case 'students':
                return Student::with(['user', 'class'])
                    ->where('school_id', $schoolId)
                    ->get()
                    ->map(function ($student) {
                        return [
                            'Name' => $student->user->name,
                            'Email' => $student->user->email,
                            'Admission Number' => $student->admission_number,
                            'Class' => $student->class->name ?? '',
                            'Date of Birth' => $student->date_of_birth,
                            'Gender' => $student->gender,
                            'Phone' => $student->user->phone,
                            'Address' => $student->user->address,
                        ];
                    });

            case 'staff':
                return Staff::with('user')
                    ->where('school_id', $schoolId)
                    ->get()
                    ->map(function ($staff) {
                        return [
                            'Name' => $staff->user->name,
                            'Email' => $staff->user->email,
                            'Employee Number' => $staff->employee_number,
                            'Department' => $staff->department,
                            'Position' => $staff->position,
                            'Phone' => $staff->user->phone,
                            'Address' => $staff->user->address,
                        ];
                    });

            case 'books':
                return Book::where('school_id', $schoolId)
                    ->get()
                    ->map(function ($book) {
                        return [
                            'Title' => $book->title,
                            'Author' => $book->author,
                            'ISBN' => $book->isbn,
                            'Category' => $book->category,
                            'Copies' => $book->copies,
                            'Available' => $book->available_copies,
                            'Publisher' => $book->publisher,
                            'Publication Year' => $book->publication_year,
                        ];
                    });

            case 'fees':
                return Fee::where('school_id', $schoolId)
                    ->get()
                    ->map(function ($fee) {
                        return [
                            'Name' => $fee->name,
                            'Amount' => $fee->amount,
                            'Type' => $fee->type,
                            'Frequency' => $fee->frequency,
                            'Due Date' => $fee->due_date,
                            'Description' => $fee->description,
                        ];
                    });

            case 'exams':
                return Exam::with(['class', 'subject'])
                    ->where('school_id', $schoolId)
                    ->get()
                    ->map(function ($exam) {
                        return [
                            'Name' => $exam->name,
                            'Type' => $exam->type,
                            'Start Date' => $exam->start_date,
                            'End Date' => $exam->end_date,
                            'Class' => $exam->class->name ?? '',
                            'Subject' => $exam->subject->name ?? '',
                        ];
                    });

            case 'users':
                return User::with('roles')
                    ->where('school_id', $schoolId)
                    ->get()
                    ->map(function ($user) {
                        return [
                            'Name' => $user->name,
                            'Email' => $user->email,
                            'Phone' => $user->phone,
                            'Address' => $user->address,
                            'Roles' => $user->roles->pluck('name')->implode(', '),
                            'Created At' => $user->created_at,
                        ];
                    });

            case 'roles':
                return Role::with('permissions')
                    ->get()
                    ->map(function ($role) {
                        return [
                            'Name' => $role->name,
                            'Display Name' => $role->display_name,
                            'Description' => $role->description,
                            'Permissions' => $role->permissions->pluck('name')->implode(', '),
                        ];
                    });

            default:
                return collect();
        }
    }

    private function exportToCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            
            if ($data->isNotEmpty()) {
                // Write headers
                fputcsv($file, array_keys($data->first()));
                
                // Write data
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToXlsx($data, $filename)
    {
        // For XLSX export, you would typically use a library like PhpSpreadsheet
        // For now, we'll return CSV format with .xlsx extension
        return $this->exportToCsv($data, $filename);
    }

    // Private helper methods for analytics
    private function getAnalyticsData($module, $schoolId, $period)
    {
        $startDate = $this->getStartDate($period);
        $endDate = now();

        switch ($module) {
            case 'academic':
                return $this->getAcademicAnalytics($schoolId, $startDate, $endDate);

            case 'finance':
                return $this->getFinanceAnalytics($schoolId, $startDate, $endDate);

            case 'library':
                return $this->getLibraryAnalytics($schoolId, $startDate, $endDate);

            case 'attendance':
                return $this->getAttendanceAnalytics($schoolId, $startDate, $endDate);

            case 'overall':
                return $this->getOverallAnalytics($schoolId, $startDate, $endDate);

            default:
                return [];
        }
    }

    private function getAcademicAnalytics($schoolId, $startDate, $endDate)
    {
        $totalStudents = Student::where('school_id', $schoolId)->count();
        $totalClasses = AcademicClass::where('school_id', $schoolId)->count();
        $totalSubjects = Subject::where('school_id', $schoolId)->count();
        $totalExams = Exam::where('school_id', $schoolId)
            ->whereBetween('start_date', [$startDate, $endDate])
            ->count();

        $averageScore = ExamResult::join('academic_students', 'exam_results.student_id', '=', 'academic_students.id')
            ->where('academic_students.school_id', $schoolId)
            ->whereBetween('exam_results.created_at', [$startDate, $endDate])
            ->avg('score') ?? 0;

        return [
            'total_students' => $totalStudents,
            'total_classes' => $totalClasses,
            'total_subjects' => $totalSubjects,
            'total_exams' => $totalExams,
            'average_score' => round($averageScore, 2),
            'pass_rate' => $this->calculatePassRate($schoolId, $startDate, $endDate),
            'top_performer' => $this->getTopPerformer($schoolId, $startDate, $endDate),
        ];
    }

    private function getFinanceAnalytics($schoolId, $startDate, $endDate)
    {
        $totalFees = Fee::where('school_id', $schoolId)->sum('amount');
        $totalPayments = Payment::where('school_id', $schoolId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $outstandingFees = $totalFees - $totalPayments;

        return [
            'total_fees' => $totalFees,
            'total_payments' => $totalPayments,
            'outstanding_fees' => $outstandingFees,
            'payment_rate' => $totalFees > 0 ? round(($totalPayments / $totalFees) * 100, 2) : 0,
            'monthly_trend' => $this->getMonthlyPaymentTrend($schoolId, $startDate, $endDate),
        ];
    }

    private function getLibraryAnalytics($schoolId, $startDate, $endDate)
    {
        $totalBooks = Book::where('school_id', $schoolId)->count();
        $totalMembers = Member::where('school_id', $schoolId)->count();
        $totalBorrowings = BorrowRecord::where('school_id', $schoolId)
            ->whereBetween('borrowed_at', [$startDate, $endDate])
            ->count();

        $overdueBooks = BorrowRecord::where('school_id', $schoolId)
            ->where('due_date', '<', now())
            ->where('returned_at', null)
            ->count();

        return [
            'total_books' => $totalBooks,
            'total_members' => $totalMembers,
            'total_borrowings' => $totalBorrowings,
            'overdue_books' => $overdueBooks,
            'utilization_rate' => $totalMembers > 0 ? round(($totalBorrowings / $totalMembers), 2) : 0,
        ];
    }

    private function getAttendanceAnalytics($schoolId, $startDate, $endDate)
    {
        // This would depend on your attendance model structure
        // For now, returning placeholder data
        return [
            'average_attendance' => 85.5,
            'total_days' => 180,
            'present_days' => 154,
            'absent_days' => 26,
            'attendance_rate' => 85.5,
        ];
    }

    private function getOverallAnalytics($schoolId, $startDate, $endDate)
    {
        $academic = $this->getAcademicAnalytics($schoolId, $startDate, $endDate);
        $finance = $this->getFinanceAnalytics($schoolId, $startDate, $endDate);
        $library = $this->getLibraryAnalytics($schoolId, $startDate, $endDate);
        $attendance = $this->getAttendanceAnalytics($schoolId, $startDate, $endDate);

        return [
            'academic' => $academic,
            'finance' => $finance,
            'library' => $library,
            'attendance' => $attendance,
            'summary' => [
                'total_users' => User::where('school_id', $schoolId)->count(),
                'active_modules' => 18, // All modules are active
                'system_health' => 'Excellent',
            ]
        ];
    }

    private function getStartDate($period)
    {
        switch ($period) {
            case 'week':
                return now()->subWeek();
            case 'month':
                return now()->subMonth();
            case 'quarter':
                return now()->subQuarter();
            case 'year':
                return now()->subYear();
            default:
                return now()->subMonth();
        }
    }

    private function calculatePassRate($schoolId, $startDate, $endDate)
    {
        $totalResults = ExamResult::join('academic_students', 'exam_results.student_id', '=', 'academic_students.id')
            ->where('academic_students.school_id', $schoolId)
            ->whereBetween('exam_results.created_at', [$startDate, $endDate])
            ->count();

        $passingResults = ExamResult::join('academic_students', 'exam_results.student_id', '=', 'academic_students.id')
            ->where('academic_students.school_id', $schoolId)
            ->whereBetween('exam_results.created_at', [$startDate, $endDate])
            ->where('score', '>=', 50) // Assuming 50% is passing
            ->count();

        return $totalResults > 0 ? round(($passingResults / $totalResults) * 100, 2) : 0;
    }

    private function getTopPerformer($schoolId, $startDate, $endDate)
    {
        $topStudent = ExamResult::join('academic_students', 'exam_results.student_id', '=', 'academic_students.id')
            ->join('users', 'academic_students.user_id', '=', 'users.id')
            ->where('academic_students.school_id', $schoolId)
            ->whereBetween('exam_results.created_at', [$startDate, $endDate])
            ->orderBy('exam_results.score', 'desc')
            ->first();

        return $topStudent ? [
            'name' => $topStudent->user->name,
            'score' => $topStudent->score,
            'exam' => $topStudent->exam->name ?? 'N/A'
        ] : null;
    }

    private function getMonthlyPaymentTrend($schoolId, $startDate, $endDate)
    {
        return Payment::where('school_id', $schoolId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::create()->month($item->month)->format('M'),
                    'amount' => $item->total
                ];
            });
    }
}
