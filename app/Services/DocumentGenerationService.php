<?php

namespace App\Services;

use App\Models\School;
use App\Models\User;
use Modules\Academic\Models\Student;
use Modules\HR\Models\Staff;
use Modules\Academic\Models\ExamResult;
use Modules\Academic\Models\StudentPayment;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class DocumentGenerationService
{
    protected $school;
    protected $documentStandards;

    public function __construct()
    {
        // Initialize without school - will be set per method call
        $this->documentStandards = $this->getDocumentStandards();
    }

    /**
     * Document Standards Configuration
     */
    protected function getDocumentStandards($school = null)
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
     * Generate Student ID Card
     */
    public function generateStudentIdCard(Student $student, $school = null, $format = 'pdf')
    {
        $data = [
            'student' => $student,
            'school' => $school,
            'standards' => $this->getDocumentStandards($school),
            'qr_code' => $this->generateQRCode($this->getStudentPortalUrl($student)),
            'barcode' => $this->generateBarcode($student->admission_number),
            'serialNumber' => $this->generateSerialNumber('STU', $student->id),
        ];

        $view = 'documents.student_id_card';
        
        if ($format === 'pdf') {
            return $this->generatePDF($view, $data, 'A4', 'portrait');
        }
        
        return view($view, $data);
    }

    /**
     * Generate Staff ID Card
     */
    public function generateStaffIdCard(Staff $staff, $school = null, $format = 'pdf')
    {
        $data = [
            'staff' => $staff,
            'school' => $school,
            'standards' => $this->getDocumentStandards($school),
            'qr_code' => $this->generateQRCode($this->getStaffPortalUrl($staff)),
            'barcode' => $this->generateBarcode($staff->staff_id),
            'serialNumber' => $this->generateSerialNumber('STAFF', $staff->id),
        ];

        $view = 'documents.staff_id_card';
        
        if ($format === 'pdf') {
            return $this->generatePDF($view, $data, 'A4', 'portrait');
        }
        
        return view($view, $data);
    }

    /**
     * Generate Result Slip
     */
    public function generateResultSlip(ExamResult $result, $format = 'pdf')
    {
        $data = [
            'result' => $result,
            'student' => $result->student,
            'school' => $this->school,
            'standards' => $this->documentStandards,
            'qr_code' => $this->generateQRCode($this->getResultVerificationUrl($result)),
            'watermark' => $this->getSchoolLogoWatermark(),
        ];

        $view = 'documents.result_slip';
        
        if ($format === 'pdf') {
            return $this->generatePDF($view, $data, 'A4', 'portrait');
        }
        
        return view($view, $data);
    }

    /**
     * Generate Exam Card
     */
    public function generateExamCard(Student $student, $exam, $format = 'pdf')
    {
        $data = [
            'student' => $student,
            'exam' => $exam,
            'school' => $this->school,
            'standards' => $this->documentStandards,
            'timetable' => $this->getExamTimetable($exam),
        ];

        $view = 'documents.exam_card';
        
        if ($format === 'pdf') {
            return $this->generatePDF($view, $data, 'A4', 'portrait');
        }
        
        return view($view, $data);
    }

    /**
     * Generate Library Card
     */
    public function generateLibraryCard($user, $format = 'pdf')
    {
        $data = [
            'user' => $user,
            'school' => $this->school,
            'standards' => $this->documentStandards,
            'barcode' => $this->generateBarcode($user->library_id ?? $user->id),
            'qr_code' => $this->generateQRCode($this->getLibraryPortalUrl($user)),
        ];

        $view = 'documents.library_card';
        
        if ($format === 'pdf') {
            return $this->generatePDF($view, $data, 'CR80', 'landscape');
        }
        
        return view($view, $data);
    }

    /**
     * Generate Fee Receipt
     */
    public function generateFeeReceipt(StudentPayment $payment, $format = 'pdf')
    {
        $data = [
            'payment' => $payment,
            'student' => $payment->student,
            'school' => $this->school,
            'standards' => $this->documentStandards,
            'qr_code' => $this->generateQRCode($this->getPaymentVerificationUrl($payment)),
            'watermark' => $this->getSchoolLogoWatermark(),
        ];

        $view = 'documents.fee_receipt';
        
        if ($format === 'pdf') {
            return $this->generatePDF($view, $data, 'A5', 'portrait');
        }
        
        return view($view, $data);
    }

    /**
     * Generate Certificate
     */
    public function generateCertificate($data, $type = 'completion', $format = 'pdf')
    {
        $data['school'] = $this->school;
        $data['standards'] = $this->documentStandards;
        $data['qr_code'] = $this->generateQRCode($this->getCertificateVerificationUrl($data));
        $data['watermark'] = $this->getSchoolLogoWatermark();
        $data['certificate_type'] = $type;

        $view = "documents.certificates.{$type}";
        
        if ($format === 'pdf') {
            return $this->generatePDF($view, $data, 'A4', 'landscape');
        }
        
        return view($view, $data);
    }

    /**
     * Generate PDF Document
     */
    protected function generatePDF($view, $data, $pageSize = 'A4', $orientation = 'portrait')
    {
        try {
            \Log::info('Generating PDF', [
                'view' => $view,
                'pageSize' => $pageSize,
                'orientation' => $orientation
            ]);
            
            $html = view($view, $data)->render();
            
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper($pageSize, $orientation);
            
            // Apply document standards
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Roboto',
                'chroot' => public_path(),
            ]);

            \Log::info('PDF generated successfully');
            return $pdf;
            
        } catch (\Exception $e) {
            \Log::error('Error generating PDF', [
                'view' => $view,
                'pageSize' => $pageSize,
                'orientation' => $orientation,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Generate QR Code
     */
    protected function generateQRCode($data)
    {
        try {
            \Log::info('Generating QR code', ['data_length' => strlen($data)]);
            $qrCode = QrCode::size(100)
                ->format('svg')
                ->style('square')
                ->eye('circle')
                ->margin(1)
                ->generate($data);
            \Log::info('QR code generated successfully');
            return $qrCode;
        } catch (\Exception $e) {
            \Log::error('Error generating QR code', [
                'data_length' => strlen($data),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return '<div style="width: 100px; height: 100px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border: 1px solid #ccc; font-size: 8px;">QR Error</div>';
        }
    }

    /**
     * Generate Barcode
     */
    protected function generateBarcode($data)
    {
        try {
            \Log::info('Generating barcode', ['data' => $data]);
            $barcode = QrCode::size(80)
                ->format('svg')
                ->style('square')
                ->eye('square')
                ->margin(0)
                ->generate($data);
            \Log::info('Barcode generated successfully');
            return $barcode;
        } catch (\Exception $e) {
            \Log::error('Error generating barcode', [
                'data' => $data,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return '<div style="width: 80px; height: 20px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border: 1px solid #ccc; font-size: 8px;">Barcode Error</div>';
        }
    }

    /**
     * Get School Logo Watermark
     */
    protected function getSchoolLogoWatermark()
    {
        if (!$this->school->logo) {
            return null;
        }

        $logoPath = Storage::path('public/' . $this->school->logo);
        
        if (!file_exists($logoPath)) {
            return null;
        }

        return base64_encode(file_get_contents($logoPath));
    }

    /**
     * Get Student Portal URL
     */
    protected function getStudentPortalUrl(Student $student)
    {
        return route('student.portal', ['student_id' => $student->id]);
    }

    /**
     * Get Staff Portal URL
     */
    protected function getStaffPortalUrl(Staff $staff)
    {
        return route('staff.portal', ['staff_id' => $staff->id]);
    }

    /**
     * Get Result Verification URL
     */
    protected function getResultVerificationUrl(ExamResult $result)
    {
        return route('results.verify', ['result_id' => $result->id, 'token' => $result->verification_token]);
    }

    /**
     * Get Payment Verification URL
     */
    protected function getPaymentVerificationUrl(StudentPayment $payment)
    {
        return route('payments.verify', ['payment_id' => $payment->id, 'token' => $payment->verification_token]);
    }

    /**
     * Get Certificate Verification URL
     */
    protected function getCertificateVerificationUrl($data)
    {
        return route('certificates.verify', [
            'certificate_id' => $data['certificate_id'] ?? 'unknown',
            'token' => $data['verification_token'] ?? 'unknown'
        ]);
    }

    /**
     * Get Library Portal URL
     */
    protected function getLibraryPortalUrl($user)
    {
        return route('library.portal', ['user_id' => $user->id]);
    }

    /**
     * Get Exam Timetable
     */
    protected function getExamTimetable($exam)
    {
        // This would fetch the actual exam timetable from the database
        return $exam->timetable ?? collect();
    }

    /**
     * Get School Branding Data
     */
    public function getSchoolBranding()
    {
        return [
            'name' => $this->school->name,
            'motto' => $this->school->motto,
            'logo' => $this->school->logo,
            'address' => $this->school->settings['address'] ?? '',
            'phone' => $this->school->settings['phone'] ?? '',
            'email' => $this->school->settings['email'] ?? '',
            'website' => $this->school->settings['website'] ?? '',
            'colors' => $this->documentStandards['colors'],
        ];
    }

    /**
     * Validate Document Requirements
     */
    public function validateDocumentRequirements($documentType, $data)
    {
        $requirements = $this->getDocumentRequirements($documentType);
        $missing = [];

        foreach ($requirements as $field => $required) {
            if ($required && empty($data[$field])) {
                $missing[] = $field;
            }
        }

        return $missing;
    }

    /**
     * Get Document Requirements
     */
    protected function getDocumentRequirements($documentType)
    {
        $requirements = [
            'student_id_card' => [
                'student_name' => true,
                'admission_number' => true,
                'class' => true,
                'photo' => false,
            ],
            'result_slip' => [
                'student_name' => true,
                'exam_name' => true,
                'subjects' => true,
                'grades' => true,
            ],
            'fee_receipt' => [
                'student_name' => true,
                'payment_amount' => true,
                'payment_date' => true,
                'receipt_number' => true,
            ],
        ];

        return $requirements[$documentType] ?? [];
    }

    /**
     * Generate Bulk Documents
     */
    public function generateBulkDocuments($documentType, $items, $format = 'pdf')
    {
        $documents = [];
        
        foreach ($items as $item) {
            $method = 'generate' . str_replace('_', '', ucwords($documentType, '_'));
            
            if (method_exists($this, $method)) {
                $documents[] = $this->$method($item, $format);
            }
        }

        return $documents;
    }

    /**
     * Get Document Template
     */
    public function getDocumentTemplate($documentType)
    {
        $templates = [
            'student_id_card' => 'documents.student_id_card',
            'staff_id_card' => 'documents.staff_id_card',
            'result_slip' => 'documents.result_slip',
            'exam_card' => 'documents.exam_card',
            'library_card' => 'documents.library_card',
            'fee_receipt' => 'documents.fee_receipt',
            'certificate' => 'documents.certificates.completion',
        ];

        return $templates[$documentType] ?? null;
    }
}
