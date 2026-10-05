<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Academic\Models\Student;
use Modules\HR\Models\Staff;
use Modules\Academic\Models\ExamResult;
use Modules\Academic\Models\Exam;
use Modules\Academic\Models\StudentPayment;
use App\Models\User;

class DocumentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Debug route to check user status
     */
    public function debug()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'No user logged in']);
        }
        
        $isSystemAdmin = $this->isSystemAdmin($user);
        
        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'school_id' => $user->school_id,
            'is_system_admin' => $isSystemAdmin,
            'roles' => $user->roles->pluck('name')->toArray(),
            'stats' => [
                'students' => \Modules\Academic\Models\Student::count(),
                'staff' => \Modules\HR\Models\Staff::count(),
                'results' => \Modules\Academic\Models\ExamResult::count(),
                'payments' => \Modules\Academic\Models\StudentPayment::count(),
            ]
        ]);
    }

    /**
     * Check if current user is a system administrator
     */
    private function isSystemAdmin($user)
    {
        return $user->school_id === null || 
               $user->roles()->where('name', 'super_admin')->exists() ||
               $user->roles()->where('name', 'system_admin')->exists();
    }

    /**
     * Get default school object with all required properties
     */
    private function getDefaultSchool()
    {
        return (object)[
            'name' => 'School Name', 
            'address' => 'School Address', 
            'logo' => null,
            'motto' => null,
            'phone' => '+254 XXX XXX XXX',
            'email' => 'info@schoolname.com',
            'website' => 'www.schoolname.com',
            'settings' => [
                'theme_colors' => [
                    'primary' => '#003366',
                    'secondary' => '#666666',
                    'accent' => '#ff6b35'
                ]
            ]
        ];
    }

    /**
     * Documents Dashboard - Main entry point
     */
    public function index()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login');
            }
            
            // Check if user is system admin
            $isSystemAdmin = $this->isSystemAdmin($user);
            
            // Debug logging
            \Log::info('DocumentsController Debug', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'school_id' => $user->school_id,
                'is_system_admin' => $isSystemAdmin,
                'user_roles' => $user->roles->pluck('name')->toArray()
            ]);
            
            $school = $user->school ?? $this->getDefaultSchool();
            $schoolId = $user->school_id;
            
            // Get counts with better error handling
            $stats = [
                'students' => 0,
                'staff' => 0,
                'results' => 0,
                'payments' => 0,
            ];
            
            try {
                // Count students - show all for system admin, filtered for school admin
                if ($isSystemAdmin) {
                    $stats['students'] = Student::count();
                    \Log::info('System admin - Total students: ' . $stats['students']);
                } else {
                    $stats['students'] = Student::where('school_id', $schoolId)->count();
                    \Log::info('School admin - Students for school ' . $schoolId . ': ' . $stats['students']);
                }
            } catch (\Exception $e) {
                \Log::warning('Error counting students: ' . $e->getMessage());
                $stats['students'] = 0;
            }
            
            try {
                // Count staff - show all for system admin, filtered for school admin
                if ($isSystemAdmin) {
                    $stats['staff'] = Staff::count();
                } else {
                    $stats['staff'] = Staff::where('school_id', $schoolId)->count();
                }
            } catch (\Exception $e) {
                \Log::warning('Error counting staff: ' . $e->getMessage());
                $stats['staff'] = 0;
            }
            
            try {
                // Count exam results - show all for system admin, filtered for school admin
                if ($isSystemAdmin) {
                    $stats['results'] = ExamResult::count();
                } else {
                    $stats['results'] = ExamResult::whereHas('student', function($q) use ($schoolId) {
                        $q->where('school_id', $schoolId);
                    })->count();
                }
            } catch (\Exception $e) {
                \Log::warning('Error counting exam results: ' . $e->getMessage());
                $stats['results'] = 0;
            }
            
            try {
                // Count student payments - show all for system admin, filtered for school admin
                if ($isSystemAdmin) {
                    $stats['payments'] = StudentPayment::count();
                } else {
                    $stats['payments'] = StudentPayment::whereHas('student', function($q) use ($schoolId) {
                        $q->where('school_id', $schoolId);
                    })->count();
                }
            } catch (\Exception $e) {
                \Log::warning('Error counting student payments: ' . $e->getMessage());
                $stats['payments'] = 0;
            }
            
            // Debug final stats
            \Log::info('Final stats for documents dashboard', $stats);

            return view('documents.dashboard', compact('school', 'stats', 'isSystemAdmin'));
        } catch (\Exception $e) {
            // Log the error and return a simple response
            \Log::error('DocumentsController index error: ' . $e->getMessage());
            return response('Documents dashboard error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Academic Documents Section
     */
    public function academic()
    {
        $user = Auth::user();
        $isSystemAdmin = $this->isSystemAdmin($user);
        
        $school = $user->school ?? $this->getDefaultSchool();
        $schoolId = $user->school_id;
        
        // Get students and results for academic documents
        if ($isSystemAdmin) {
            $students = Student::with('class')->paginate(10);
            $results = ExamResult::with(['student', 'exam'])->paginate(10);
        } else {
            $students = Student::with('class')->where('school_id', $schoolId)->paginate(10);
            $results = ExamResult::with(['student', 'exam'])->whereHas('student', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })->paginate(10);
        }
        
        // Get exams for exam card generation
        $exams = Exam::take(5)->get(); // Get first 5 exams for selection

        return view('documents.academic', compact('school', 'students', 'results', 'exams', 'isSystemAdmin'));
    }

    /**
     * Student Documents Section
     */
    public function students()
    {
        $user = Auth::user();
        $isSystemAdmin = $this->isSystemAdmin($user);
        
        $school = $user->school ?? $this->getDefaultSchool();
        $schoolId = $user->school_id;
        
        // Get students for student documents with optimized queries
        if ($isSystemAdmin) {
            $students = Student::with('class')
                ->select(['id', 'name', 'admission_number', 'class_id', 'stream', 'gender', 'date_of_birth', 'school_id'])
                ->orderBy('name')
                ->paginate(10);
        } else {
            $students = Student::with('class')
                ->select(['id', 'name', 'admission_number', 'class_id', 'stream', 'gender', 'date_of_birth', 'school_id'])
                ->where('school_id', $schoolId)
                ->orderBy('name')
                ->paginate(10);
        }

        return view('documents.students', compact('school', 'students', 'isSystemAdmin'));
    }

    /**
     * Staff Documents Section
     */
    public function staff()
    {
        $user = Auth::user();
        $isSystemAdmin = $this->isSystemAdmin($user);
        
        $school = $user->school ?? $this->getDefaultSchool();
        $schoolId = $user->school_id;
        
        // Get staff for staff documents with optimized queries
        if ($isSystemAdmin) {
            $staff = Staff::with(['department', 'position'])
                ->select(['id', 'first_name', 'last_name', 'staff_id', 'department_id', 'position_id', 'email', 'phone', 'school_id'])
                ->orderBy('first_name')
                ->paginate(10);
        } else {
            $staff = Staff::with(['department', 'position'])
                ->select(['id', 'first_name', 'last_name', 'staff_id', 'department_id', 'position_id', 'email', 'phone', 'school_id'])
                ->where('school_id', $schoolId)
                ->orderBy('first_name')
                ->paginate(10);
        }

        return view('documents.staff', compact('school', 'staff', 'isSystemAdmin'));
    }

    /**
     * Library Documents Section
     */
    public function library()
    {
        $user = Auth::user();
        $isSystemAdmin = $this->isSystemAdmin($user);
        
        $school = $user->school ?? $this->getDefaultSchool();
        $schoolId = $user->school_id;
        
        // Get users for library documents (students and staff) with optimized queries
        if ($isSystemAdmin) {
            $users = User::with(['student', 'staff'])
                ->select(['id', 'name', 'email', 'school_id', 'created_at'])
                ->orderBy('name')
                ->paginate(10);
        } else {
            $users = User::with(['student', 'staff'])
                ->select(['id', 'name', 'email', 'school_id', 'created_at'])
                ->where('school_id', $schoolId)
                ->orderBy('name')
                ->paginate(10);
        }

        return view('documents.library', compact('school', 'users', 'isSystemAdmin'));
    }

    /**
     * Financial Documents Section
     */
    public function finance()
    {
        $user = Auth::user();
        $isSystemAdmin = $this->isSystemAdmin($user);
        
        $school = $user->school ?? $this->getDefaultSchool();
        $schoolId = $user->school_id;
        
        // Get payments for financial documents with optimized queries
        if ($isSystemAdmin) {
            $payments = StudentPayment::with(['student:id,name,admission_number,school_id'])
                ->select(['id', 'student_id', 'amount', 'payment_date', 'reference', 'status', 'created_at'])
                ->orderBy('payment_date', 'desc')
                ->paginate(10);
        } else {
            $payments = StudentPayment::with(['student:id,name,admission_number,school_id'])
                ->select(['id', 'student_id', 'amount', 'payment_date', 'reference', 'status', 'created_at'])
                ->whereHas('student', function($q) use ($schoolId) {
                    $q->where('school_id', $schoolId);
                })
                ->orderBy('payment_date', 'desc')
                ->paginate(10);
        }

        return view('documents.finance', compact('school', 'payments', 'isSystemAdmin'));
    }

    /**
     * Generate Student ID Card
     */
    public function generateStudentIdCard($studentId)
    {
        try {
            // Validate input
            $this->validate(request(), [
                'studentId' => 'required|integer|exists:students,id'
            ]);
            
            $student = Student::with('class')->findOrFail($studentId);
            $school = Auth::user()->school ?? $this->getDefaultSchool();
            $standards = $this->getDocumentStandards($school);
            $watermark = $this->getSchoolLogoWatermark($school);
            
            // Generate QR code for student portal access
            $qr_code = $this->generateQRCode($this->getStudentPortalUrl($student));
            
            // Generate barcode for student ID card
            $barcode = $this->generateBarcode($student->admission_number);
            
            // Generate serial number
            $serialNumber = $this->generateSerialNumber('STU', $student->id);
            
            // Generate PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('documents.student_id_card', compact('student', 'school', 'standards', 'watermark', 'qr_code', 'barcode', 'serialNumber'));
            
            // Set paper size and orientation for ID card
            $pdf->setPaper('A4', 'portrait');
            
            // Generate filename
            $filename = 'Student_ID_Card_' . $student->name . '_' . date('Y-m-d') . '.pdf';
            $filename = str_replace([' ', '/', '\\'], '_', $filename);
            
            // Log successful generation
            \Log::info('Student ID card generated successfully', [
                'student_id' => $studentId,
                'user_id' => auth()->id(),
                'filename' => $filename
            ]);
            
            // Return PDF download
            return $pdf->download($filename);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation failed for student ID card generation', [
                'student_id' => $studentId,
                'user_id' => auth()->id(),
                'errors' => $e->errors()
            ]);
            return back()->withErrors($e->errors());
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Student not found for ID card generation', [
                'student_id' => $studentId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Student not found.');
            
        } catch (\Exception $e) {
            \Log::error('Error generating student ID card', [
                'student_id' => $studentId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error generating student ID card: ' . $e->getMessage());
        }
    }

    /**
     * Generate Staff ID Card
     */
    public function generateStaffIdCard($staffId)
    {
        try {
            // Validate input
            $this->validate(request(), [
                'staffId' => 'required|integer|exists:staff,id'
            ]);
            
            $staff = Staff::with(['department', 'position'])->findOrFail($staffId);
            $school = Auth::user()->school ?? $this->getDefaultSchool();
            $standards = $this->getDocumentStandards($school);
            $watermark = $this->getSchoolLogoWatermark($school);
            
            // Generate QR code for staff portal access
            $qr_code = $this->generateQRCode($this->getStaffPortalUrl($staff));
            
            // Generate barcode for staff ID card
            $barcode = $this->generateBarcode($staff->staff_id);
            
            // Generate serial number
            $serialNumber = $this->generateSerialNumber('STAFF', $staff->id);
            
            // Generate PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('documents.staff_id_card', compact('staff', 'school', 'standards', 'watermark', 'qr_code', 'barcode', 'serialNumber'));
            
            // Set paper size and orientation for ID card
            $pdf->setPaper('A4', 'portrait');
            
            // Generate filename
            $filename = 'Staff_ID_Card_' . $staff->first_name . '_' . $staff->last_name . '_' . date('Y-m-d') . '.pdf';
            $filename = str_replace([' ', '/', '\\'], '_', $filename);
            
            // Log successful generation
            \Log::info('Staff ID card generated successfully', [
                'staff_id' => $staffId,
                'user_id' => auth()->id(),
                'filename' => $filename
            ]);
            
            // Return PDF download
            return $pdf->download($filename);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation failed for staff ID card generation', [
                'staff_id' => $staffId,
                'user_id' => auth()->id(),
                'errors' => $e->errors()
            ]);
            return back()->withErrors($e->errors());
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Staff not found for ID card generation', [
                'staff_id' => $staffId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Staff member not found.');
            
        } catch (\Exception $e) {
            \Log::error('Error generating staff ID card', [
                'staff_id' => $staffId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error generating staff ID card: ' . $e->getMessage());
        }
    }

    /**
     * Generate Result Slip
     */
    public function generateResultSlip($resultId)
    {
        try {
            $result = ExamResult::with(['student', 'exam'])->findOrFail($resultId);
            $student = $result->student;
            $school = Auth::user()->school ?? $this->getDefaultSchool();
            $standards = $this->getDocumentStandards($school);
            $watermark = $this->getSchoolLogoWatermark($school);
            
            // Generate serial number and verification URL
            try {
                $serialNumber = $this->generateSerialNumber($result, $student);
                $verificationUrl = $this->generateVerificationUrl($result, $student);
                $qr_code = $this->generateQRCode($verificationUrl);
            } catch (\Exception $e) {
                $serialNumber = 'RS-2024-' . str_pad($result->id ?? '1', 6, '0', STR_PAD_LEFT);
                $qr_code = $this->generateQRCode('https://school.com/verify/result/' . $serialNumber);
            }
            
            // Generate PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('documents.result_slip', compact('result', 'student', 'school', 'standards', 'watermark', 'qr_code', 'serialNumber'));
            
            // Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');
            
            // Set PDF options for single page
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial',
                'chroot' => public_path(),
                'enable_font_subsetting' => true,
                'pdf_backend' => 'CPDF',
                'default_media_type' => 'screen',
                'default_paper_size' => 'a4',
                'default_font' => 'Arial',
                'dpi' => 150,
                'font_height_ratio' => 0.9,
            ]);
            
            // Generate filename
            $filename = 'Result_Slip_' . $student->name . '_' . $result->exam->name . '_' . date('Y-m-d') . '.pdf';
            $filename = str_replace([' ', '/', '\\'], '_', $filename);
            
            // Return PDF download
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating result slip: ' . $e->getMessage());
        }
    }

    /**
     * Generate Exam Card
     */
    public function generateExamCard($studentId, $examId)
    {
        try {
            $student = Student::with('class')->findOrFail($studentId);
            $exam = Exam::findOrFail($examId);
            $school = Auth::user()->school ?? $this->getDefaultSchool();
            $standards = $this->getDocumentStandards($school);
            $watermark = $this->getSchoolLogoWatermark($school);
            $qr_code = $this->generateQRCode($this->getExamVerificationUrl($exam, $student));
            
            // Create sample timetable data for exam card
            $timetable = collect([
                (object)[
                    'date' => now()->addDays(1)->format('Y-m-d'),
                    'start_time' => '09:00:00',
                    'end_time' => '11:00:00',
                    'subject' => 'Mathematics',
                    'venue' => 'Main Hall',
                    'duration' => 120
                ],
                (object)[
                    'date' => now()->addDays(2)->format('Y-m-d'),
                    'start_time' => '09:00:00',
                    'end_time' => '10:30:00',
                    'subject' => 'English',
                    'venue' => 'Room 101',
                    'duration' => 90
                ],
                (object)[
                    'date' => now()->addDays(3)->format('Y-m-d'),
                    'start_time' => '09:00:00',
                    'end_time' => '11:30:00',
                    'subject' => 'Science',
                    'venue' => 'Lab 1',
                    'duration' => 150
                ],
                (object)[
                    'date' => now()->addDays(4)->format('Y-m-d'),
                    'start_time' => '09:00:00',
                    'end_time' => '10:00:00',
                    'subject' => 'History',
                    'venue' => 'Room 102',
                    'duration' => 60
                ]
            ]);
            
            // Generate PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('documents.exam_card', compact('student', 'exam', 'school', 'standards', 'watermark', 'qr_code', 'timetable'));
            
            // Set paper size and orientation
            $pdf->setPaper('A4', 'portrait');
            
            // Generate filename
            $filename = 'Exam_Card_' . $student->name . '_' . $exam->name . '_' . date('Y-m-d') . '.pdf';
            $filename = str_replace([' ', '/', '\\'], '_', $filename);
            
            // Return PDF download
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating exam card: ' . $e->getMessage());
        }
    }

    /**
     * Generate Library Card
     */
    public function generateLibraryCard($userId)
    {
        try {
            $user = User::with(['student', 'staff'])->findOrFail($userId);
            $school = Auth::user()->school ?? $this->getDefaultSchool();
            $standards = $this->getDocumentStandards($school);
            $watermark = $this->getSchoolLogoWatermark($school);
            $qr_code = $this->generateQRCode($this->getLibraryPortalUrl($user));
            
            // Generate barcode for library card
            $barcode = $this->generateBarcode($user->library_id ?? $user->id ?? 'N/A');
            
            // Generate PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('documents.library_card', compact('user', 'school', 'standards', 'watermark', 'qr_code', 'barcode'));
            
            // Set paper size and orientation for ID card
            $pdf->setPaper('A4', 'portrait');
            
            // Generate filename
            $filename = 'Library_Card_' . $user->name . '_' . date('Y-m-d') . '.pdf';
            $filename = str_replace([' ', '/', '\\'], '_', $filename);
            
            // Return PDF download
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating library card: ' . $e->getMessage());
        }
    }

    /**
     * Generate Fee Receipt
     */
    public function generateFeeReceipt($paymentId)
    {
        try {
            $payment = StudentPayment::with('student')->findOrFail($paymentId);
            $student = $payment->student;
            $school = Auth::user()->school ?? $this->getDefaultSchool();
            $standards = $this->getDocumentStandards($school);
            $watermark = $this->getSchoolLogoWatermark($school);
            $qr_code = $this->generateQRCode($this->getPaymentVerificationUrl($payment));
            
            // Generate PDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('documents.fee_receipt', compact('payment', 'student', 'school', 'standards', 'watermark', 'qr_code'));
            
            // Set paper size and orientation
            $pdf->setPaper('A5', 'portrait');
            
            // Generate filename
            $filename = 'Fee_Receipt_' . $student->name . '_' . $payment->reference . '_' . date('Y-m-d') . '.pdf';
            $filename = str_replace([' ', '/', '\\'], '_', $filename);
            
            // Return PDF download
            return $pdf->download($filename);
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating fee receipt: ' . $e->getMessage());
        }
    }

    /**
     * Generate Certificate
     */
    public function generateCertificate(Request $request)
    {
        try {
            $data = $request->validate([
                'student_name' => 'required|string',
                'course_name' => 'required|string',
                'program_name' => 'required|string',
                'duration' => 'required|string',
                'grade' => 'required|string',
                'completion_date' => 'required|date',
                'certificate_id' => 'required|string',
            ]);

            $school = Auth::user()->school ?? (object)['name' => 'School Name', 'address' => '', 'logo' => null];
            
            // Generate QR code for certificate verification
            $qr_code = $this->generateQRCode($this->getCertificateVerificationUrl($data));
            
            // For now, just return a simple view
            return view('documents.certificates.completion', compact('data', 'school', 'qr_code'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error generating certificate: ' . $e->getMessage());
        }
    }

    /**
     * Get Certificate Verification URL
     */
    private function getCertificateVerificationUrl($data)
    {
        try {
            return route('certificates.verify', [
                'certificate_id' => $data['certificate_id'],
                'token' => hash('sha256', $data['certificate_id'] . config('app.key'))
            ]);
        } catch (\Exception $e) {
            return 'https://school.com/verify/certificate/' . $data['certificate_id'];
        }
    }

    /**
     * Bulk Generate Documents
     */
    public function bulkGenerate(Request $request)
    {
        try {
            $request->validate([
                'document_type' => 'required|string',
                'selection' => 'required|string',
            ]);

            $documentType = $request->input('document_type');
            $selection = $request->input('selection');

            // For now, redirect to the appropriate section based on document type
            switch ($documentType) {
                case 'student_id_cards':
                    return response()->json([
                        'success' => true,
                        'redirect' => route('documents.students'),
                        'message' => 'Redirecting to Students section for ID card generation'
                    ]);
                case 'staff_id_cards':
                    return response()->json([
                        'success' => true,
                        'redirect' => route('documents.staff'),
                        'message' => 'Redirecting to Staff section for ID card generation'
                    ]);
                case 'result_slips':
                    return response()->json([
                        'success' => true,
                        'redirect' => route('documents.academic'),
                        'message' => 'Redirecting to Academic section for result slip generation'
                    ]);
                case 'fee_receipts':
                    return response()->json([
                        'success' => true,
                        'redirect' => route('documents.finance'),
                        'message' => 'Redirecting to Finance section for fee receipt generation'
                    ]);
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Document type not supported yet'
                    ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error in bulk generation: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Preview Template
     */
    public function previewTemplate($documentType, Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please log in to preview templates.');
            }
            
            $school = $user->school ?? $this->getDefaultSchool();
            $standards = $this->getDocumentStandards($school);
            $watermark = $this->getSchoolLogoWatermark($school);
            
            // Get sample data based on document type
            $sampleData = $this->getSampleData($documentType, $request);
            
            // Generate QR code for sample data if available
            $qr_code = null;
            $exam = null;
            $timetable = null;
            $barcode = null;
            
            if ($sampleData) {
                switch ($documentType) {
                    case 'student_id_card':
                        $qr_code = $this->generateQRCode($this->getStudentPortalUrl($sampleData));
                        $barcode = $this->generateBarcode($sampleData->admission_number ?? 'N/A');
                        break;
                    case 'staff_id_card':
                        $qr_code = $this->generateQRCode($this->getStaffPortalUrl($sampleData));
                        $barcode = $this->generateBarcode($sampleData->staff_id ?? 'N/A');
                        break;
                    case 'result_slip':
                        try {
                            $serialNumber = $this->generateSerialNumber($sampleData, $sampleData->student);
                            $verificationUrl = $this->generateVerificationUrl($sampleData, $sampleData->student);
                            $qr_code = $this->generateQRCode($verificationUrl);
                        } catch (\Exception $e) {
                            $serialNumber = 'RS-2024-000001';
                            $qr_code = $this->generateQRCode('https://school.com/verify/result/' . $serialNumber);
                        }
                        $barcode = null;
                        break;
                    case 'exam_card':
                        // For exam card, we need both student and exam data
                        $exam = Exam::first(); // Get first available exam as sample
                        if (!$exam) {
                            // Create a sample exam if none exists
                            $exam = (object)[
                                'id' => 1,
                                'name' => 'Sample Midterm Exam',
                                'code' => 'MT001',
                                'academic_year' => '2024-2025',
                                'term' => 'First Term',
                                'exam_date' => now()->addDays(1),
                                'exam_time' => '8:00 AM - 5:00 PM',
                                'duration' => 'Full Day',
                                'exam_center' => 'Main Hall',
                                'exam_room' => 'Room 1',
                                'subjects' => [
                                    ['code' => 'MATH', 'name' => 'Mathematics', 'paper' => 'Paper 1', 'duration' => '2 Hours'],
                                    ['code' => 'ENG', 'name' => 'English', 'paper' => 'Paper 1', 'duration' => '2 Hours'],
                                    ['code' => 'SCI', 'name' => 'Science', 'paper' => 'Paper 1', 'duration' => '2 Hours'],
                                    ['code' => 'HIST', 'name' => 'History', 'paper' => 'Paper 1', 'duration' => '2 Hours'],
                                    ['code' => 'GEO', 'name' => 'Geography', 'paper' => 'Paper 1', 'duration' => '2 Hours']
                                ]
                            ];
                        }
                        
                        try {
                            $serialNumber = 'EC-' . ($exam->id ?? '001') . '-' . ($sampleData->id ?? '001');
                            $verificationUrl = url('/verify/exam/' . $serialNumber);
                            $qr_code = $this->generateQRCode($verificationUrl);
                        } catch (\Exception $e) {
                            $serialNumber = 'EC-001-001';
                            $qr_code = $this->generateQRCode('https://school.com/verify/exam/' . $serialNumber);
                        }
                        $barcode = null;
                        break;
                    case 'library_card':
                        $qr_code = $this->generateQRCode($this->getLibraryPortalUrl($sampleData));
                        $barcode = $this->generateBarcode($sampleData->library_id ?? $sampleData->id ?? 'N/A');
                        break;
                    case 'fee_receipt':
                        $qr_code = $this->generateQRCode($this->getPaymentVerificationUrl($sampleData));
                        $barcode = null;
                        break;
                }
            }
            
            return view('documents.preview', compact('documentType', 'sampleData', 'school', 'standards', 'watermark', 'qr_code', 'exam', 'timetable', 'serialNumber'));
        } catch (\Exception $e) {
            \Log::error('Document preview error: ' . $e->getMessage(), [
                'document_type' => $documentType,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Error previewing template: ' . $e->getMessage());
        }
    }

    /**
     * Get School Branding
     */
    public function getBranding()
    {
        try {
            $school = Auth::user()->school ?? $this->getDefaultSchool();
            
            return response()->json([
                'success' => true,
                'branding' => [
                    'name' => $school->name ?? 'School Name',
                    'logo' => $school->logo ?? null,
                    'motto' => $school->motto ?? '',
                    'theme_colors' => $school->settings['theme_colors'] ?? [
                        'primary' => '#003366',
                        'secondary' => '#666666',
                        'accent' => '#ff6b35'
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate Requirements
     */
    public function validateRequirements(Request $request)
    {
        try {
            $request->validate([
                'document_type' => 'required|string',
                'data' => 'required|array',
            ]);

            return response()->json([
                'success' => true,
                'valid' => true,
                'message' => 'Validation passed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sample data for template preview
     */
    private function getSampleData($documentType, Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return null;
            }
            
            $schoolId = $user->school_id;
        
        switch ($documentType) {
            case 'student_id_card':
                $studentId = $request->get('student_id');
                if ($studentId) {
                        $student = Student::with('class')->find($studentId);
                        if ($student && ($schoolId === null || $student->school_id === $schoolId)) {
                            return $student;
                        }
                }
                // Return first available student as sample
                return $schoolId ? Student::with('class')->where('school_id', $schoolId)->first() : Student::with('class')->first();
                
            case 'staff_id_card':
                $staffId = $request->get('staff_id');
                if ($staffId) {
                        $staff = Staff::find($staffId);
                        if ($staff && ($schoolId === null || $staff->school_id === $schoolId)) {
                            return $staff;
                        }
                }
                // Return first available staff as sample
                return $schoolId ? Staff::where('school_id', $schoolId)->first() : Staff::first();
                
            case 'result_slip':
                $resultId = $request->get('result_id');
                if ($resultId) {
                        $result = ExamResult::with(['student', 'exam', 'subject'])->find($resultId);
                        if ($result && $result->student && ($schoolId === null || $result->student->school_id === $schoolId)) {
                            return $result;
                        }
                }
                // Return first available result as sample
                return $schoolId ? ExamResult::with(['student', 'exam', 'subject'])->whereHas('student', function($q) use ($schoolId) {
                    $q->where('school_id', $schoolId);
                })->first() : ExamResult::with(['student', 'exam', 'subject'])->first();
                
            case 'exam_card':
                $studentId = $request->get('student_id');
                if ($studentId) {
                        $student = Student::with('class')->find($studentId);
                        if ($student && ($schoolId === null || $student->school_id === $schoolId)) {
                            return $student;
                        }
                }
                // Return first available student as sample
                return $schoolId ? Student::with('class')->where('school_id', $schoolId)->first() : Student::with('class')->first();
                
            case 'library_card':
                $userId = $request->get('user_id');
                if ($userId) {
                        $user = User::with(['student', 'staff'])->find($userId);
                        if ($user && ($schoolId === null || $user->school_id === $schoolId)) {
                            return $user;
                        }
                }
                // Return first available user as sample
                return $schoolId ? User::with(['student', 'staff'])->where('school_id', $schoolId)->first() : User::with(['student', 'staff'])->first();
                
            case 'fee_receipt':
                $paymentId = $request->get('payment_id');
                if ($paymentId) {
                        $payment = StudentPayment::with('student')->find($paymentId);
                        if ($payment && $payment->student && ($schoolId === null || $payment->student->school_id === $schoolId)) {
                            return $payment;
                        }
                }
                // Return first available payment as sample
                return $schoolId ? StudentPayment::with('student')->whereHas('student', function($q) use ($schoolId) {
                    $q->where('school_id', $schoolId);
                })->first() : StudentPayment::with('student')->first();
                
            default:
                    return null;
            }
        } catch (\Exception $e) {
            \Log::error('Error getting sample data: ' . $e->getMessage(), [
                'document_type' => $documentType,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
                return null;
        }
    }

    /**
     * Get document standards configuration
     */
    private function getDocumentStandards($school = null)
    {
        return [
            'page_sizes' => [
                'A4' => ['width' => 210, 'height' => 297, 'unit' => 'mm'],
                'A5' => ['width' => 148, 'height' => 210, 'unit' => 'mm'],
                'CR80' => ['width' => 86, 'height' => 54, 'unit' => 'mm'], // ID card size
            ],
            'margins' => [
                'standard' => ['top' => 20, 'right' => 20, 'bottom' => 20, 'left' => 20, 'unit' => 'mm'],
                'tight' => ['top' => 10, 'right' => 10, 'bottom' => 10, 'left' => 10, 'unit' => 'mm'],
            ],
            'fonts' => [
                'primary' => 'Roboto, Arial, sans-serif',
                'secondary' => 'Open Sans, Arial, sans-serif',
                'formal' => 'Times New Roman, serif',
            ],
            'colors' => [
                'primary' => $school->settings['theme_colors']['primary'] ?? '#003366',
                'secondary' => $school->settings['theme_colors']['secondary'] ?? '#666666',
                'accent' => $school->settings['theme_colors']['accent'] ?? '#ff6b35',
            ]
        ];
    }

    /**
     * Get school logo watermark
     */
    private function getSchoolLogoWatermark($school = null)
    {
        if (!$school || !$school->logo) {
            return null;
        }

        $logoPath = storage_path('app/public/' . $school->logo);
        
        if (!file_exists($logoPath)) {
            return null;
        }

        return base64_encode(file_get_contents($logoPath));
    }

    /**
     * Generate QR Code
     */
    private function generateQRCode($data)
    {
        try {
            // Use the simple-qrcode package to generate actual QR codes
            $qrCode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)
                ->format('svg')
                ->style('square')
                ->eye('circle')
                ->margin(1)
                ->generate($data);
            
            return $qrCode;
        } catch (\Exception $e) {
            // Fallback to placeholder if QR generation fails
        return '<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
                <rect width="100" height="100" fill="white" stroke="#ccc" stroke-width="1"/>
                <text x="50" y="50" text-anchor="middle" dy=".3em" font-family="Arial" font-size="10" fill="#666">QR Code</text>
                <text x="50" y="65" text-anchor="middle" dy=".3em" font-family="Arial" font-size="8" fill="#999">Verification</text>
        </svg>';
        }
    }

    /**
     * Generate Certificate Serial Number
     */
    private function generateSerialNumber($result, $student)
    {
        $year = date('Y');
        $term = $result->exam->term ?? '01';
        $termCode = str_pad($term, 2, '0', STR_PAD_LEFT);
        $studentId = $student->admission_number ?? 'STU000001';
        $resultId = str_pad($result->id ?? '1', 6, '0', STR_PAD_LEFT);
        
        return "RS-{$year}-{$resultId}";
    }

    /**
     * Generate Verification URL
     */
    private function generateVerificationUrl($result, $student)
    {
        try {
            $serialNumber = $this->generateSerialNumber($result, $student);
            return route('results.verify', ['serial' => $serialNumber]);
        } catch (\Exception $e) {
            // Fallback URL if route doesn't exist
            $serialNumber = $this->generateSerialNumber($result, $student);
            return url('/verify/result/' . $serialNumber);
        }
    }

    private function generateBarcode($data)
    {
        // For now, return a simple SVG barcode placeholder
        // In production, you would use a barcode library like milon/barcode
        return '<svg width="200" height="40" xmlns="http://www.w3.org/2000/svg">
            <rect width="200" height="40" fill="white" stroke="#000" stroke-width="1"/>
            <text x="100" y="25" text-anchor="middle" dy=".3em" font-size="12" font-family="monospace">' . $data . '</text>
            <text x="100" y="35" text-anchor="middle" dy=".3em" font-size="8">BARCODE</text>
        </svg>';
    }

    /**
     * Get Student Portal URL
     */
    private function getStudentPortalUrl($student)
    {
        // Use a simple portal URL since the route doesn't exist yet
        return url('/student/portal/' . $student->id) ?? 'https://school.com/student/' . $student->id;
    }

    /**
     * Get Staff Portal URL
     */
    private function getStaffPortalUrl($staff)
    {
        // Use a simple portal URL since the route doesn't exist yet
        return url('/staff/portal/' . $staff->id) ?? 'https://school.com/staff/' . $staff->id;
    }

    /**
     * Get Result Verification URL
     */
    private function getResultVerificationUrl($result)
    {
        // Use a simple verification URL since the route doesn't exist yet
        return url('/verify/result/' . $result->id) ?? 'https://school.com/results/' . $result->id;
    }

    /**
     * Get Payment Verification URL
     */
    private function getPaymentVerificationUrl($payment)
    {
        // Use a simple verification URL since the route doesn't exist yet
        return url('/verify/payment/' . $payment->id) ?? 'https://school.com/payments/' . $payment->id;
    }

    /**
     * Get Library Portal URL
     */
    private function getLibraryPortalUrl($user)
    {
        // Use a simple portal URL since the route doesn't exist yet
        return url('/library/portal/' . $user->id) ?? 'https://school.com/library/' . $user->id;
    }

    /**
     * Get Exam Verification URL
     */
    private function getExamVerificationUrl($exam, $student)
    {
        // Use a simple verification URL since the route doesn't exist yet
        return url('/verify/exam/' . $exam->id . '/student/' . $student->id) ?? 'https://school.com/exams/' . $exam->id . '/student/' . $student->id;
    }

    /**
     * Verify Result Slip
     */
    public function verifyResult($serial)
    {
        try {
            // Parse the serial number to extract information
            $parts = explode('-', $serial);
            if (count($parts) !== 3 || $parts[0] !== 'RS') {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid serial number format'
                ], 400);
            }

            $year = $parts[1];
            $resultId = intval($parts[2]);

            // Find the result
            $result = ExamResult::with(['student', 'exam', 'student.class'])
                ->where('id', $resultId)
                ->first();

            if (!$result) {
                return response()->json([
                    'success' => false,
                    'message' => 'Result not found'
                ], 404);
            }

            // Verify the serial number matches
            $expectedSerial = $this->generateSerialNumber($result, $result->student);
            if ($serial !== $expectedSerial) {
                return response()->json([
                    'success' => false,
                    'message' => 'Serial number verification failed'
                ], 400);
            }

            // Return verification data
            return response()->json([
                'success' => true,
                'message' => 'Result verified successfully',
                'data' => [
                    'serial_number' => $serial,
                    'student_name' => $result->student->name,
                    'admission_number' => $result->student->admission_number,
                    'class' => $result->student->class->name ?? 'N/A',
                    'exam_name' => $result->exam->name ?? 'N/A',
                    'exam_term' => $result->exam->term ?? 'N/A',
                    'academic_year' => $result->exam->academic_year ?? 'N/A',
                    'issue_date' => $result->created_at->format('F d, Y'),
                    'verified_at' => now()->format('F d, Y \a\t g:i A'),
                    'status' => 'AUTHENTIC'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Verification failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
