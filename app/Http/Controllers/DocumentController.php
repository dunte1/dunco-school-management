<?php

namespace App\Http\Controllers;

use App\Services\DocumentGenerationService;
use Modules\Academic\Models\Student;
use Modules\HR\Models\Staff;
use Modules\Academic\Models\ExamResult;
use Modules\Academic\Models\StudentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentGenerationService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Generate Student ID Card
     */
    public function generateStudentIdCard(Request $request, $studentId)
    {
        try {
            $this->authorize('generate_documents');
            
            // Validate input
            $request->validate([
                'format' => 'sometimes|in:pdf,html',
                'studentId' => 'required|integer|exists:students,id'
            ]);
            
            $student = Student::with('class')->findOrFail($studentId);
            $format = $request->get('format', 'pdf');
            
            $pdf = $this->documentService->generateStudentIdCard($student, $format);
            
            if ($format === 'pdf') {
                return $pdf->download('student_id_card_' . $student->admission_number . '.pdf');
            }
            
            return $pdf;
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \Log::warning('Unauthorized student ID card generation attempt', [
                'user_id' => auth()->id(),
                'student_id' => $studentId,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'You are not authorized to generate this document.');
            
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
    public function generateStaffIdCard(Request $request, $staffId)
    {
        try {
            $this->authorize('generate_documents');
            
            // Validate input
            $request->validate([
                'format' => 'sometimes|in:pdf,html',
                'staffId' => 'required|integer|exists:staff,id'
            ]);
            
            $staff = Staff::with(['department', 'position'])->findOrFail($staffId);
            $format = $request->get('format', 'pdf');
            
            // Generate QR code and barcode for staff ID card
            $qr_code = $this->documentService->generateQRCode($this->documentService->getStaffPortalUrl($staff));
            $barcode = $this->documentService->generateBarcode($staff->staff_id);
            $serialNumber = $this->documentService->generateSerialNumber('STAFF', $staff->id);
            
            $pdf = $this->documentService->generateStaffIdCard($staff, $format);
            
            if ($format === 'pdf') {
                return $pdf->download('staff_id_card_' . $staff->staff_id . '.pdf');
            }
            
            return $pdf;
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \Log::warning('Unauthorized document generation attempt', [
                'user_id' => auth()->id(),
                'staff_id' => $staffId,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'You are not authorized to generate this document.');
            
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
    public function generateResultSlip(Request $request, $resultId)
    {
        try {
            $this->authorize('generate_documents');
            
            // Validate input
            $request->validate([
                'format' => 'sometimes|in:pdf,html',
                'resultId' => 'required|integer|exists:exam_results,id'
            ]);
            
            $result = ExamResult::with('student')->findOrFail($resultId);
            $format = $request->get('format', 'pdf');
            
            $pdf = $this->documentService->generateResultSlip($result, $format);
            
            if ($format === 'pdf') {
                return $pdf->download('result_slip_' . $result->student->admission_number . '.pdf');
            }
            
            return $pdf;
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \Log::warning('Unauthorized result slip generation attempt', [
                'user_id' => auth()->id(),
                'result_id' => $resultId,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'You are not authorized to generate this document.');
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Exam result not found for slip generation', [
                'result_id' => $resultId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Exam result not found.');
            
        } catch (\Exception $e) {
            \Log::error('Error generating result slip', [
                'result_id' => $resultId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error generating result slip: ' . $e->getMessage());
        }
    }

    /**
     * Generate Exam Card
     */
    public function generateExamCard(Request $request, $studentId, $examId)
    {
        try {
            $this->authorize('generate_documents');
            
            // Validate input
            $request->validate([
                'format' => 'sometimes|in:pdf,html',
                'studentId' => 'required|integer|exists:students,id',
                'examId' => 'required|integer|exists:exams,id'
            ]);
            
            $student = Student::with('class')->findOrFail($studentId);
            $exam = \Modules\Academic\Models\Exam::findOrFail($examId);
            $format = $request->get('format', 'pdf');
            
            $pdf = $this->documentService->generateExamCard($student, $exam, $format);
            
            if ($format === 'pdf') {
                return $pdf->download('exam_card_' . $student->admission_number . '.pdf');
            }
            
            return $pdf;
            
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \Log::warning('Unauthorized exam card generation attempt', [
                'user_id' => auth()->id(),
                'student_id' => $studentId,
                'exam_id' => $examId,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'You are not authorized to generate this document.');
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Student or exam not found for exam card generation', [
                'student_id' => $studentId,
                'exam_id' => $examId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Student or exam not found.');
            
        } catch (\Exception $e) {
            \Log::error('Error generating exam card', [
                'student_id' => $studentId,
                'exam_id' => $examId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error generating exam card: ' . $e->getMessage());
        }
    }

    /**
     * Generate Library Card
     */
    public function generateLibraryCard(Request $request, $userId)
    {
        $this->authorize('generate_documents');
        
        $user = \App\Models\User::findOrFail($userId);
        $format = $request->get('format', 'pdf');
        
        $pdf = $this->documentService->generateLibraryCard($user, $format);
        
        if ($format === 'pdf') {
            return $pdf->download('library_card_' . $user->id . '.pdf');
        }
        
        return $pdf;
    }

    /**
     * Generate Fee Receipt
     */
    public function generateFeeReceipt(Request $request, $paymentId)
    {
        $this->authorize('generate_documents');
        
        $payment = StudentPayment::findOrFail($paymentId);
        $format = $request->get('format', 'pdf');
        
        $pdf = $this->documentService->generateFeeReceipt($payment, $format);
        
        if ($format === 'pdf') {
            return $pdf->download('fee_receipt_' . $payment->reference . '.pdf');
        }
        
        return $pdf;
    }

    /**
     * Generate Certificate
     */
    public function generateCertificate(Request $request)
    {
        $this->authorize('generate_documents');
        
        $data = $request->validate([
            'student_name' => 'required|string|max:255',
            'course_name' => 'required|string|max:255',
            'program_name' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'grade' => 'required|string|max:255',
            'completion_date' => 'required|date',
            'certificate_id' => 'nullable|string|max:255',
            'type' => 'nullable|string|in:completion,achievement,participation',
        ]);
        
        $format = $request->get('format', 'pdf');
        $type = $request->get('type', 'completion');
        
        $pdf = $this->documentService->generateCertificate($data, $type, $format);
        
        if ($format === 'pdf') {
            return $pdf->download('certificate_' . $data['student_name'] . '.pdf');
        }
        
        return $pdf;
    }

    /**
     * Bulk Generate Documents
     */
    public function bulkGenerate(Request $request)
    {
        $this->authorize('generate_documents');
        
        $request->validate([
            'document_type' => 'required|string|in:student_id_card,staff_id_card,result_slip,exam_card,library_card,fee_receipt',
            'item_ids' => 'required|array',
            'item_ids.*' => 'required|integer',
            'format' => 'nullable|string|in:pdf,html',
        ]);
        
        $documentType = $request->document_type;
        $itemIds = $request->item_ids;
        $format = $request->get('format', 'pdf');
        
        $items = $this->getItemsByType($documentType, $itemIds);
        
        if ($items->isEmpty()) {
            return response()->json(['error' => 'No items found'], 404);
        }
        
        // For now, generate individual PDFs and zip them
        // In a production environment, you might want to use a queue for bulk generation
        $zip = new \ZipArchive();
        $zipName = 'documents_' . $documentType . '_' . now()->format('Y_m_d_H_i_s') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipName);
        
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }
        
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($items as $item) {
                $method = 'generate' . str_replace('_', '', ucwords($documentType, '_'));
                
                if (method_exists($this->documentService, $method)) {
                    $pdf = $this->documentService->$method($item, 'pdf');
                    $filename = $this->getDocumentFilename($documentType, $item);
                    $zip->addFromString($filename, $pdf->output());
                }
            }
            $zip->close();
            
            return response()->download($zipPath)->deleteFileAfterSend();
        }
        
        return response()->json(['error' => 'Failed to create zip file'], 500);
    }

    /**
     * Get Document Template Preview
     */
    public function previewTemplate(Request $request, $documentType)
    {
        $this->authorize('generate_documents');
        
        $template = $this->documentService->getDocumentTemplate($documentType);
        
        if (!$template) {
            return response()->json(['error' => 'Template not found'], 404);
        }
        
        // Generate sample data for preview
        $sampleData = $this->getSampleData($documentType);
        
        return view($template, $sampleData);
    }

    /**
     * Get School Branding
     */
    public function getSchoolBranding()
    {
        return response()->json($this->documentService->getSchoolBranding());
    }

    /**
     * Validate Document Requirements
     */
    public function validateRequirements(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string',
            'data' => 'required|array',
        ]);
        
        $missing = $this->documentService->validateDocumentRequirements(
            $request->document_type,
            $request->data
        );
        
        return response()->json([
            'valid' => empty($missing),
            'missing_fields' => $missing,
        ]);
    }

    /**
     * Helper method to get items by type
     */
    protected function getItemsByType($documentType, $itemIds)
    {
        switch ($documentType) {
            case 'student_id_card':
                return Student::whereIn('id', $itemIds)->get();
            case 'staff_id_card':
                return Staff::whereIn('id', $itemIds)->get();
            case 'result_slip':
                return ExamResult::whereIn('id', $itemIds)->get();
            case 'exam_card':
                return Student::whereIn('id', $itemIds)->get();
            case 'library_card':
                return \App\Models\User::whereIn('id', $itemIds)->get();
            case 'fee_receipt':
                return StudentPayment::whereIn('id', $itemIds)->get();
            default:
                return collect();
        }
    }

    /**
     * Helper method to get document filename
     */
    protected function getDocumentFilename($documentType, $item)
    {
        switch ($documentType) {
            case 'student_id_card':
                return 'student_id_card_' . $item->admission_number . '.pdf';
            case 'staff_id_card':
                return 'staff_id_card_' . $item->staff_id . '.pdf';
            case 'result_slip':
                return 'result_slip_' . $item->student->admission_number . '.pdf';
            case 'exam_card':
                return 'exam_card_' . $item->admission_number . '.pdf';
            case 'library_card':
                return 'library_card_' . $item->id . '.pdf';
            case 'fee_receipt':
                return 'fee_receipt_' . $item->reference . '.pdf';
            default:
                return 'document_' . $item->id . '.pdf';
        }
    }

    /**
     * Helper method to get sample data for preview
     */
    protected function getSampleData($documentType)
    {
        $school = Auth::user()->school;
        $standards = $this->documentService->getDocumentStandards();
        
        switch ($documentType) {
            case 'student_id_card':
                return [
                    'student' => (object) [
                        'name' => 'John Doe',
                        'admission_number' => 'STU001',
                        'class' => (object) ['name' => 'Form 1A'],
                        'stream' => 'Science',
                        'date_of_birth' => now()->subYears(15),
                        'passport' => null,
                    ],
                    'school' => $school,
                    'standards' => $standards,
                    'qr_code' => '<svg>Sample QR Code</svg>',
                    'barcode' => '<svg>Sample Barcode</svg>',
                ];
            case 'result_slip':
                return [
                    'result' => (object) [
                        'exam' => (object) [
                            'name' => 'Mid-Term Examination',
                            'term' => 'Term 1',
                            'academic_year' => '2024/2025',
                        ],
                        'subjects' => [
                            ['name' => 'Mathematics', 'obtained' => 85, 'total' => 100, 'percentage' => 85, 'grade' => 'A'],
                            ['name' => 'English', 'obtained' => 78, 'total' => 100, 'percentage' => 78, 'grade' => 'B+'],
                        ],
                        'total_marks' => 1000,
                        'obtained_marks' => 850,
                        'percentage' => 85,
                        'grade' => 'A',
                        'position' => 5,
                    ],
                    'student' => (object) [
                        'name' => 'John Doe',
                        'admission_number' => 'STU001',
                        'class' => (object) ['name' => 'Form 1A'],
                        'stream' => 'Science',
                        'passport' => null,
                    ],
                    'school' => $school,
                    'standards' => $standards,
                    'qr_code' => '<svg>Sample QR Code</svg>',
                    'watermark' => null,
                ];
            default:
                return [
                    'school' => $school,
                    'standards' => $standards,
                ];
        }
    }
}

