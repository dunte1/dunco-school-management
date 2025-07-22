<?php

namespace Modules\Portal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Portal\app\Models\Announcement;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Modules\Portal\app\Models\Message;
use Modules\Portal\app\Notifications\GeneralAnnouncement;
use App\Models\User;
use App\Models\Modules\Library\app\Models\Book;
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
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];
        
        $user = auth()->user();
        $student = $students->first();

        // Fetch dashboard-specific data
        $events = $student ? \Modules\Academic\Models\SubjectCalendarEvent::orderBy('start_time')->where('start_time', '>=', now())->limit(5)->get() : collect();
        $notifications = $user->notifications()->latest()->limit(5)->get();
        $dueFees = $student ? $student->fees()->where('status', '!=', 'paid')->orderBy('due_date')->get() : collect();
        $recentGrades = $student ? $student->academicRecords()->latest('exam_date')->limit(5)->get() : collect();

        return view('portal::dashboard', compact('students', 'all_students', 'events', 'notifications', 'dueFees', 'recentGrades'));
    }

    public function academics(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];
        $student = $students->first();

        $examResults = $student ? $student->academicRecords()->with('subject')->orderByDesc('exam_date')->get() : collect();
        
        // Generate Demo Data if none exists
        if ($examResults->isEmpty()) {
            $examResults = collect([
                (object)[
                    'subject' => (object)['name' => 'Mathematics'],
                    'exam_type' => 'Final',
                    'term' => '1',
                    'academic_year' => date('Y'),
                    'exam_date' => now()->subMonths(1),
                    'marks_obtained' => 85,
                    'total_marks' => 100,
                    'grade' => 'A',
                    'remarks' => 'Excellent work.'
                ],
                (object)[
                    'subject' => (object)['name' => 'English'],
                    'exam_type' => 'Final',
                    'term' => '1',
                    'academic_year' => date('Y'),
                    'exam_date' => now()->subMonths(1),
                    'marks_obtained' => 78,
                    'total_marks' => 100,
                    'grade' => 'B+',
                    'remarks' => 'Good effort.'
                ],
                (object)[
                    'subject' => (object)['name' => 'Science'],
                    'exam_type' => 'Midterm',
                    'term' => '1',
                    'academic_year' => date('Y'),
                    'exam_date' => now()->subMonths(3),
                    'marks_obtained' => 92,
                    'total_marks' => 100,
                    'grade' => 'A+',
                    'remarks' => 'Outstanding performance.'
                ],
            ]);
        }
        
        $progressRecords = $examResults->where('academic_year', date('Y'));
        
        return view('portal::academics', compact('students', 'all_students', 'progressRecords', 'examResults'));
    }
    
    public function schedule(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];
        $student = $students->first();

        $classTimetable = collect();
        if ($student && $student->currentClass) {
            $classId = $student->currentClass->id;
            $classTimetable = \Modules\Timetable\Models\ClassSchedule::where('academic_class_id', $classId)->with(['teacher', 'room', 'subject'])->orderBy('day_of_week')->orderBy('start_time')->get();
        }
        
        $examTimetable = collect();
        if ($student && $student->currentClass) {
            $classId = $student->currentClass->id;
            $examTimetable = \Modules\Academic\Models\Exam::where('exam_type', '!=', 'assignment')->whereHas('classes', function($q) use ($classId) { $q->where('academic_classes.id', $classId); })->with('subjects')->orderBy('start_date')->get();
        }

        // Generate Demo Data if none exists
        if ($classTimetable->isEmpty()) {
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
            $times = ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00'];
            foreach ($days as $day) {
                foreach ($times as $time) {
                    $classTimetable->push((object)[
                        'day_of_week' => $day,
                        'start_time' => $time,
                        'end_time' => Carbon::parse($time)->addHour()->format('H:i'),
                        'subject' => (object)['name' => 'Sample Subject'],
                        'teacher' => (object)['name' => 'Sample Teacher'],
                        'room' => (object)['name' => 'Room ' . rand(101, 105)],
                    ]);
                }
            }
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
        if ($student && $student->currentClass) {
            $classSubjects = $student->currentClass->subjects()->with('teachers', 'resources')->get();
        }

        // Generate Demo Data if none exists
        if ($classSubjects->isEmpty()) {
            $classSubjects = collect([
                (object)[
                    'id' => 1,
                    'name' => 'Sample Mathematics',
                    'description' => 'This is a demo mathematics course.',
                    'teachers' => collect([(object)['name' => 'Mr. Smith']]),
                    'resources' => collect([
                        (object)['title' => 'Algebra Notes', 'type' => 'PDF', 'url' => null, 'file_path' => 'demo/algebra_notes.pdf'],
                        (object)['title' => 'Geometry Videos', 'type' => 'Link', 'url' => '#', 'file_path' => null],
                    ])
                ],
                (object)[
                    'id' => 2,
                    'name' => 'Sample English',
                    'description' => 'This is a demo English course.',
                    'teachers' => collect([(object)['name' => 'Ms. Doe']]),
                    'resources' => collect()
                ],
            ]);
        }

        $subjectResourcesBySubject = $classSubjects->keyBy('id')->map(function ($subject) {
            return $subject->resources;
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
        if ($student && $student->currentClass) {
            $classId = $student->currentClass->id;
            $assignments = \Modules\Academic\Models\Exam::where('exam_type', 'assignment')->whereHas('classes', function($q) use ($classId) { $q->where('academic_classes.id', $classId); })->with('subjects')->orderByDesc('end_date')->get();
        }

        // Generate Demo Data if none exists
        if ($assignments->isEmpty()) {
            $assignments = collect([
                (object)[
                    'name' => 'Sample Algebra Homework',
                    'subjects' => collect([(object)['name' => 'Mathematics']]),
                    'end_date' => now()->addDays(3),
                    'status' => 'ongoing',
                    'status_color' => 'warning'
                ],
                (object)[
                    'name' => 'Sample English Essay',
                    'subjects' => collect([(object)['name' => 'English']]),
                    'end_date' => now()->subDays(5),
                    'status' => 'completed',
                    'status_color' => 'success'
                ],
            ]);
        }

        return view('portal::assignments', compact('students', 'all_students', 'assignments'));
    }

    public function finance(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];
        $student = $students->first();

        $studentFees = $student ? $student->fees()->with('payments')->get() : collect();
        $studentPayments = $student ? \Modules\Academic\Models\StudentPayment::where('student_id', $student->id)->with('fee')->orderByDesc('payment_date')->get() : collect();
        $paymentSettings = FinanceSetting::find(1)?->settings ?? [];
        
        // Generate Demo Data if none exists
        if ($studentFees->isEmpty()) {
            $unpaidFee = (object)[
                'id' => 1,
                'category' => 'Tuition Fee',
                'amount' => 50000,
                'status' => 'unpaid',
                'due_date' => now()->addDays(15),
                'payments' => collect(),
                'total_paid' => 0,
                'outstanding_amount' => 50000,
            ];
            $paidFee = (object)[
                'id' => 2,
                'category' => 'Library Fee',
                'amount' => 2000,
                'status' => 'paid',
                'due_date' => now()->subMonths(1),
                'payments' => collect([(object)['amount' => 2000]]),
                'total_paid' => 2000,
                'outstanding_amount' => 0,
            ];
            $studentFees = collect([$unpaidFee, $paidFee]);

            $studentPayments = collect([
                (object)[
                    'payment_date' => now()->subMonths(1),
                    'fee' => (object)['category' => 'Library Fee'],
                    'amount' => 2000,
                    'method' => 'MPESA',
                    'reference' => 'ABC123XYZ',
                    'note' => 'Annual library fee.'
                ]
            ]);
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
            ->orderByDesc('published_at')->limit(10)->get();
            
        $messages = Message::where('receiver_id', $user->id)->orWhere('sender_id', $user->id)->with('sender')->orderBy('created_at')->get();
        $admin = User::whereHas('roles', function($q){ $q->where('name', 'admin'); })->first();

        // Generate Demo Data
        if ($announcements->isEmpty()) {
            $announcements->push((object)['title' => 'Sample Announcement', 'message' => 'This is a demo announcement.', 'published_at' => now()]);
        }
        if ($messages->isEmpty() && $admin) {
            $messages->push((object)[
                'sender_id' => $admin->id,
                'sender' => $admin, 
                'message' => 'Welcome to the portal! Let us know if you need anything.', 
                'created_at' => now()
            ]);
        }

        return view('portal::communication', compact('students', 'all_students', 'announcements', 'messages', 'admin'));
    }
    
    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->input('receiver_id'),
            'message' => $request->input('message')
        ]);
        return back()->with('success', 'Message sent!');
    }
    
    public function sendTestNotification(Request $request)
    {
        auth()->user()->notify(new GeneralAnnouncement('This is a test notification to check all your channels.'));
        return back()->with('success', 'Test notification sent!');
    }

    public function librarySearch(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];
        
        $query = $request->input('query');
        $books = collect();
        if ($query) {
            $books = Book::where('title', 'like', "%{$query}%")->orWhere('author', 'like', "%{$query}%")->limit(50)->get();
        }

        // Generate Demo Data if a search is performed but no results are found
        if ($query && $books->isEmpty()) {
            $books = collect([
                (object)[
                    'title' => 'Sample Book: The Adventures of Code',
                    'author' => 'Dev Writer',
                    'isbn' => '978-3-16-148410-0',
                    'is_available' => true,
                    'is_ebook' => true,
                    'ebook_url' => '#'
                ],
                 (object)[
                    'title' => 'Another Book: Mastering Laravel',
                    'author' => 'Jane Coder',
                    'isbn' => '978-1-49-190424-4',
                    'is_available' => false,
                    'is_ebook' => false,
                    'ebook_url' => null
                ],
            ]);
        }

        return view('portal::library_search', compact('students', 'all_students', 'books', 'query'));
    }

    public function lms(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];

        // In a real app, you would fetch this from LMS models
        $courses = collect([
            (object)[
                'title' => 'Introduction to Programming',
                'instructor' => 'Dr. Alan Turing',
                'progress' => 75,
                'thumbnail' => 'https://via.placeholder.com/400x225.png/007bff/ffffff?text=Code',
                'next_due' => 'Quiz 3: Functions',
                'due_date' => now()->addDays(4)
            ],
            (object)[
                'title' => 'Digital Marketing Fundamentals',
                'instructor' => 'Ms. Ada Lovelace',
                'progress' => 40,
                'thumbnail' => 'https://via.placeholder.com/400x225.png/28a745/ffffff?text=Marketing',
                'next_due' => 'Assignment 2: SEO Analysis',
                'due_date' => now()->addDays(8)
            ],
             (object)[
                'title' => 'History of Ancient Civilizations',
                'instructor' => 'Dr. Indiana Jones',
                'progress' => 95,
                'thumbnail' => 'https://via.placeholder.com/400x225.png/ffc107/000000?text=History',
                'next_due' => 'Final Exam',
                'due_date' => now()->addDays(15)
            ]
        ]);

        return view('portal::lms', compact('students', 'all_students', 'courses'));
    }

    public function hostel(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];

        // Demo data
        $hostelDetails = (object)[
            'name' => 'St. Patrick\'s Hostel',
            'room_number' => 'B-207',
            'room_type' => 'Double Occupancy',
            'warden' => 'Mr. John Doe',
            'is_allocated' => true
        ];

        $menu = [
            'Monday' => ['Breakfast' => 'Toast & Eggs', 'Lunch' => 'Rice & Chicken Curry', 'Dinner' => 'Chapati & Lentils'],
            'Tuesday' => ['Breakfast' => 'Pancakes', 'Lunch' => 'Pasta', 'Dinner' => 'Vegetable Stir-fry'],
            'Wednesday' => ['Breakfast' => 'Oatmeal', 'Lunch' => 'Fish & Chips', 'Dinner' => 'Noodles'],
            'Thursday' => ['Breakfast' => 'Cereal', 'Lunch' => 'Tacos', 'Dinner' => 'Pizza'],
            'Friday' => ['Breakfast' => 'Waffles', 'Lunch' => 'Burger', 'Dinner' => 'Biryani'],
            'Saturday' => ['Breakfast' => 'Idli & Sambar', 'Lunch' => 'Special Thali', 'Dinner' => 'Fried Rice'],
            'Sunday' => ['Breakfast' => 'Brunch', 'Lunch' => '-', 'Dinner' => 'Steak'],
        ];

        return view('portal::hostel', compact('students', 'all_students', 'hostelDetails', 'menu'));
    }

    public function transport(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];

        // Demo data
        $transportDetails = (object)[
            'is_allocated' => true,
            'route_name' => 'City Route A',
            'vehicle_number' => 'KMP-456J',
            'driver_name' => 'Mr. James',
            'driver_phone' => '0712345678',
            'pickup_point' => 'Uptown Junction',
            'pickup_time' => '07:15 AM',
            'dropoff_time' => '04:45 PM',
        ];

        return view('portal::transport', compact('students', 'all_students', 'transportDetails'));
    }

    public function welfare(Request $request)
    {
        $studentData = $this->getStudentData($request);
        $students = $studentData['students'];
        $all_students = $studentData['all_students'];

        // Demo data
        $counselor = (object)[
            'name' => 'Dr. Emily Carter',
            'email' => 'emily.carter@school.com',
            'phone' => '0787654321',
            'availability' => 'Mon, Wed, Fri (10am - 4pm)',
        ];

        $helplines = [
            (object)['name' => 'General Student Support', 'number' => '111-222-333'],
            (object)['name' => 'Academic Stress Helpline', 'number' => '444-555-666'],
            (object)['name' => 'Emergency Security', 'number' => '999-999-999'],
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
                // Delete old avatar if it exists
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
