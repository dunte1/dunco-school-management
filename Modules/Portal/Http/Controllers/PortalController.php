<?php

namespace Modules\Portal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Portal\Models\Announcement;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Modules\Portal\Models\Message;
use Modules\Portal\Notifications\GeneralAnnouncement;
use App\Models\User;
use Modules\Library\Models\Book;
use Modules\Finance\Entities\FinanceSetting;

class PortalController extends Controller
{
    private function getStudentData(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return ['students' => collect(), 'all_students' => collect()];
        }

        $all_students = collect();
        if ($user->hasRole('parent')) {
            $all_students = \Modules\Academic\Models\Student::whereHas('parents', function($q) use ($user) {
                $q->where('users.id', $user->id);
            })->get();
        }

        $selectedStudentId = $request->input('student_id');
        $selectedStudent = null;

        if ($user->hasRole('parent')) {
            if ($selectedStudentId) {
                $selectedStudent = $all_students->where('id', $selectedStudentId)->first();
            }
            if (!$selectedStudent && $all_students->count()) {
                $selectedStudent = $all_students->first();
            }
            return ['students' => collect([$selectedStudent]), 'all_students' => $all_students];
        }

        if ($user->hasRole('student')) {
            $student = \Modules\Academic\Models\Student::where('user_id', $user->id)->first();
            return ['students' => collect([$student]), 'all_students' => collect([$student])];
        }

        return ['students' => collect(), 'all_students' => collect()];
    }

    public function dashboard(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];

        $user = auth()->user();
        $student = $students->first();

        $events = collect();
        $notifications = collect();
        $dueFees = collect();
        $recentGrades = collect();

        try {
            if ($student) {
                $events = \Modules\Academic\Models\SubjectCalendarEvent::orderBy('start_time')
                    ->where('start_time', '>=', now())
                    ->limit(5)
                    ->get();
            }
        } catch (\Exception $e) {
            $events = collect();
        }

        try {
            if ($user) {
                $notifications = $user->notifications()->latest()->limit(5)->get();
            }
        } catch (\Exception $e) {
            $notifications = collect();
        }

        try {
            if ($student) {
                $dueFees = $student->fees()->where('status', '!=', 'paid')->orderBy('due_date')->get();
            }
        } catch (\Exception $e) {
            $dueFees = collect();
        }

        try {
            if ($student) {
                $recentGrades = $student->academicRecords()->latest('exam_date')->limit(5)->get();
            }
        } catch (\Exception $e) {
            $recentGrades = collect();
        }

        return view('portal::dashboard', compact('students', 'all_students', 'events', 'notifications', 'dueFees', 'recentGrades'));
    }

    public function academics(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];
        $student = $students->first();

        $examResults = collect();
        try {
            if ($student) {
                $examResults = $student->academicRecords()->with('subject')->orderByDesc('exam_date')->get();
            }
        } catch (\Exception $e) {
            $examResults = collect();
        }

        $progressRecords = $examResults->filter(function ($r) {
            return $r->academic_year == date('Y');
        });

        return view('portal::academics', compact('students', 'all_students', 'progressRecords', 'examResults'));
    }

    public function schedule(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];
        $student = $students->first();

        $classTimetable = collect();
        try {
            if ($student && $student->currentClass) {
                $classId = $student->currentClass->id;
                $classTimetable = \Modules\Timetable\Models\ClassSchedule::where('academic_class_id', $classId)
                    ->with(['teacher', 'room', 'subject'])
                    ->orderBy('day_of_week')
                    ->orderBy('start_time')
                    ->get();
            }
        } catch (\Exception $e) {
            $classTimetable = collect();
        }

        $examTimetable = collect();
        try {
            if ($student && $student->currentClass) {
                $classId = $student->currentClass->id;
                $examTimetable = \Modules\Academic\Models\Exam::where('exam_type', '!=', 'assignment')
                    ->whereHas('classes', function($q) use ($classId) {
                        $q->where('academic_classes.id', $classId);
                    })
                    ->with('subjects')
                    ->orderBy('start_date')
                    ->get();
            }
        } catch (\Exception $e) {
            $examTimetable = collect();
        }

        return view('portal::schedule', compact('students', 'all_students', 'classTimetable', 'examTimetable'));
    }

    public function materials(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];
        $student = $students->first();

        $classSubjects = collect();
        try {
            if ($student && $student->currentClass) {
                $classSubjects = $student->currentClass->subjects()->with('teachers', 'resources')->get();
            }
        } catch (\Exception $e) {
            $classSubjects = collect();
        }

        $subjectResourcesBySubject = $classSubjects->keyBy('id')->map(function ($subject) {
            return $subject->resources ?? collect();
        });

        return view('portal::materials', compact('students', 'all_students', 'classSubjects', 'subjectResourcesBySubject'));
    }

    public function assignments(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];
        $student = $students->first();

        $assignments = collect();
        try {
            if ($student && $student->currentClass) {
                $classId = $student->currentClass->id;
                $assignments = \Modules\Academic\Models\Exam::where('exam_type', 'assignment')
                    ->whereHas('classes', function($q) use ($classId) {
                        $q->where('academic_classes.id', $classId);
                    })
                    ->with('subjects')
                    ->orderByDesc('end_date')
                    ->get();
            }
        } catch (\Exception $e) {
            $assignments = collect();
        }

        return view('portal::assignments', compact('students', 'all_students', 'assignments'));
    }

    public function finance(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];
        $student = $students->first();

        $studentFees = collect();
        $studentPayments = collect();
        $paymentSettings = [];

        try {
            if ($student) {
                $studentFees = $student->fees()->with('payments')->get();
            }
        } catch (\Exception $e) {
            $studentFees = collect();
        }

        try {
            if ($student) {
                $studentPayments = \Modules\Finance\Models\Payment::where('student_id', $student->id)
                    ->with('fee')
                    ->orderByDesc('payment_date')
                    ->get();
            }
        } catch (\Exception $e) {
            $studentPayments = collect();
        }

        try {
            $paymentSettings = FinanceSetting::find(1)?->settings ?? [];
        } catch (\Exception $e) {
            $paymentSettings = [];
        }

        return view('portal::finance', compact('students', 'all_students', 'studentFees', 'studentPayments', 'paymentSettings'));
    }

    public function communication(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];

        $user = auth()->user();
        $audiences = ['all'];
        if ($user->hasRole('student')) $audiences[] = 'students';
        if ($user->hasRole('parent')) $audiences[] = 'parents';

        $announcements = Announcement::where('is_active', true)
            ->where(function($q) use ($audiences) { $q->whereIn('audience', $audiences); })
            ->where(function($q) { $q->whereNull('published_at')->orWhere('published_at', '<=', now()); })
            ->where(function($q) { $q->whereNull('expires_at')->orWhere('expires_at', '>', now()); })
            ->orderByDesc('published_at')
            ->limit(10)
            ->get();

        $messages = Message::where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        $admin = User::whereHas('roles', function($q){ $q->where('name', 'admin'); })->first();

        return view('portal::communication', compact('students', 'all_students', 'announcements', 'messages', 'admin'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string', 'receiver_id' => 'required|exists:users,id']);
        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->input('receiver_id'),
            'message' => $request->input('message'),
        ]);
        return back()->with('success', 'Message sent!');
    }

    public function sendTestNotification(Request $request)
    {
        return back()->with('info', 'Test notifications are disabled in production.');
    }

    public function librarySearch(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];

        $query = $request->input('query');
        $books = collect();
        try {
            if ($query) {
                $books = Book::where('title', 'like', "%{$query}%")
                    ->orWhere('author', 'like', "%{$query}%")
                    ->limit(50)
                    ->get();
            }
        } catch (\Exception $e) {
            $books = collect();
        }

        return view('portal::library_search', compact('students', 'all_students', 'books', 'query'));
    }

    public function lms(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];

        $courses = collect();
        try {
            $subjects = \Modules\Academic\Models\Subject::orderBy('name')->get();
            $courses = $subjects->map(function ($subject) {
                return (object)[
                    'title' => $subject->name,
                    'instructor' => $subject->description ?? 'Staff',
                    'progress' => rand(10, 95),
                    'thumbnail' => null,
                    'next_due' => 'Upcoming lesson',
                    'due_date' => now()->addDays(rand(1, 14)),
                ];
            });
        } catch (\Throwable $e) {
            $courses = collect();
        }

        return view('portal::lms', compact('students', 'all_students', 'courses'));
    }

    public function hostel(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];
        $student = $students->first();

        $hostelDetails = null;
        $hostelFees = collect();
        $hostelIssues = collect();
        $hostelAnnouncements = collect();
        $leaveRequests = collect();

        if ($student) {
            try {
                $roomAllocation = \Modules\Hostel\Models\RoomAllocation::where('student_id', $student->id)
                    ->where('status', 'active')
                    ->with(['bed.room.floor.hostel', 'bed.room'])
                    ->first();

                if ($roomAllocation && $roomAllocation->bed && $roomAllocation->bed->room) {
                    $hostelDetails = (object)[
                        'name' => $roomAllocation->bed->room->floor->hostel->name ?? 'Unknown Hostel',
                        'room_number' => $roomAllocation->bed->room->room_number ?? 'Unknown',
                        'room_type' => $roomAllocation->bed->room->type ?? 'Unknown',
                        'bed_number' => $roomAllocation->bed->bed_number ?? 'Unknown',
                        'warden' => 'Not Assigned',
                        'check_in' => $roomAllocation->check_in,
                        'is_allocated' => true,
                    ];
                }

                $hostelFees = \Modules\Hostel\Models\HostelFee::where('student_id', $student->id)
                    ->orderBy('due_date')
                    ->get();

                $hostelIssues = \Modules\Hostel\Models\HostelIssue::where('reported_by', $student->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();

                $hostelAnnouncements = \Modules\Hostel\Models\HostelAnnouncement::where('is_active', true)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();

                $leaveRequests = \Modules\Hostel\Models\LeaveRequest::where('student_id', $student->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                \Log::error('Error loading hostel data: ' . $e->getMessage());
            }
        }

        $menu = [];
        try {
            $menu = \Modules\Hostel\Models\CafeteriaMenu::where('is_active', true)
                ->where('date', '>=', now()->startOfWeek())
                ->where('date', '<=', now()->endOfWeek())
                ->get()
                ->groupBy(function ($item) {
                    return $item->date->format('l');
                })
                ->map(function ($items) {
                    return $items->pluck('meal_name', 'meal_type')->toArray();
                })
                ->toArray();
        } catch (\Exception $e) {
            $menu = [];
        }

        return view('portal::hostel', compact(
            'students',
            'all_students',
            'hostelDetails',
            'menu',
            'hostelFees',
            'hostelIssues',
            'hostelAnnouncements',
            'leaveRequests'
        ));
    }

    public function transport(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];
        $student = $students->first();

        $transportDetails = null;
        try {
            if ($student) {
                $tripPassenger = \Modules\Transport\Models\TripPassenger::with(['trip.vehicle', 'trip.driver', 'trip.route'])
                    ->where('student_id', $student->id)
                    ->latest()
                    ->first();

                if ($tripPassenger && $tripPassenger->trip) {
                    $trip = $tripPassenger->trip;
                    $transportDetails = (object)[
                        'is_allocated' => true,
                        'route_name' => $trip->route->name ?? 'N/A',
                        'vehicle_number' => $trip->vehicle->vehicle_number ?? 'N/A',
                        'driver_name' => $trip->driver->name ?? 'N/A',
                        'driver_phone' => $trip->driver->phone ?? 'N/A',
                        'pickup_point' => $trip->route->start_location ?? 'N/A',
                        'pickup_time' => $trip->start_time ?? 'N/A',
                        'dropoff_time' => $trip->end_time ?? 'N/A',
                    ];
                }
            }
        } catch (\Throwable $e) {
            $transportDetails = null;
        }

        if (!$transportDetails) {
            $transportDetails = (object)['is_allocated' => false];
        }

        return view('portal::transport', compact('students', 'all_students', 'transportDetails'));
    }

    public function welfare(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];

        $counselor = (object)[
            'name' => 'School Counsellor',
            'email' => config('mail.from.address', 'support@school.com'),
            'phone' => 'Contact the school office',
            'availability' => 'Mon-Fri (8:00 AM - 4:00 PM)',
        ];

        $helplines = [
            (object)['name' => 'Student Support', 'number' => config('app.name', 'School') . ' Support Line'],
            (object)['name' => 'Emergency', 'number' => '999'],
            (object)['name' => 'Counselling', 'number' => config('app.name', 'School') . ' Counselling'],
        ];

        return view('portal::welfare', compact('students', 'all_students', 'counselor', 'helplines'));
    }

    public function profile(Request $request)
    {
        $user = auth()->user();
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];

        return view('portal::profile', compact('user', 'students', 'all_students'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $formType = $request->input('form_type');

        if ($formType === 'personal') {
            $request->validate([
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $user->email = $request->input('email');
            $user->phone = $request->input('phone');

            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                }
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $path;
            }
        } elseif ($formType === 'security') {
            $request->validate([
                'current_password' => 'required|string|current_password',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user->password = \Illuminate\Support\Facades\Hash::make($request->input('password'));
        } elseif ($formType === 'preferences') {
            $user->setSetting('theme', $request->input('theme', 'light'));
            $user->setSetting('notifications.email', $request->has('notifications.email'));
            $user->setSetting('notifications.sms', $request->has('notifications.sms'));
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}
