<?php

namespace Modules\Academic\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\StudentDocument;
use Modules\Academic\Models\EnrollmentHistory;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('class')->get();
        return view('academic::students.index', compact('students'));
    }

    public function create()
    {
        return view('academic::students.create');
    }

    private function generateAdmissionNumber($schoolId)
    {
        $school = \App\Models\School::find($schoolId);
        $code = $school ? $school->code : 'SCH';
        $year = date('Y');
        $count = \Modules\Academic\Models\Student::whereYear('created_at', $year)
            ->where('school_id', $schoolId)
            ->count() + 1;
        $sequence = str_pad($count, 3, '0', STR_PAD_LEFT);
        return strtoupper("{$code}/{$year}/{$sequence}");
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|string|unique:academic_students,student_id',
            'name' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'admission_date' => 'required|date',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'passport' => 'nullable|file|image|max:2048',
            'medical_conditions' => 'nullable|string',
            'disabilities' => 'nullable|string',
            'allergies' => 'nullable|string',
            'stream' => 'nullable|string',
            'house' => 'nullable|string',
            'group' => 'nullable|string',
            'is_transfer' => 'nullable|boolean',
            'previous_school' => 'nullable|string',
            'parent_name' => 'required|array',
            'parent_name.*' => 'required|string|max:255',
            'parent_contact' => 'required|array',
            'parent_contact.*' => 'required|string|max:255',
            'immunizations' => 'nullable|string',
            'chronic_conditions' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:32',
            'doctor_name' => 'nullable|string|max:255',
            'doctor_phone' => 'nullable|string|max:32',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_number' => 'nullable|string|max:64',
        ]);
        $data['school_id'] = auth()->user()->school_id;
        $data['admission_number'] = $this->generateAdmissionNumber($data['school_id']);

        // Automatically create a user for the student
        $email = strtolower(str_replace(' ', '.', $data['name'])) . '.' . uniqid() . '@school.local';
        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $email,
            'password' => bcrypt('password123'),
            'school_id' => $data['school_id'],
        ]);
        if (method_exists($user, 'roles')) {
            $studentRole = \App\Models\Role::where('name', 'student')->first();
            if ($studentRole) {
                $user->roles()->attach($studentRole->id);
            }
        }
        $data['user_id'] = $user->id;

        // Handle webcam photo (base64) or file upload
        if ($request->filled('webcam_photo')) {
            $webcamPhoto = $request->input('webcam_photo');
            if (preg_match('/^data:image\/(png|jpeg);base64,/', $webcamPhoto)) {
                $image = substr($webcamPhoto, strpos($webcamPhoto, ',') + 1);
                $image = base64_decode($image);
                $ext = (strpos($webcamPhoto, 'jpeg') !== false) ? 'jpg' : 'png';
                $filename = 'passports/' . uniqid('webcam_') . '.' . $ext;
                \Storage::disk('public')->put($filename, $image);
                $data['passport'] = $filename;
            }
        } elseif ($request->hasFile('passport')) {
            $data['passport'] = $request->file('passport')->store('passports', 'public');
        }
        $student = Student::create($data);
        \Modules\Academic\Models\EnrollmentHistory::create([
            'student_id' => $student->id,
            'class_id' => $student->class_id,
            'academic_year' => date('Y'),
            'status' => 'enrolled',
            'changed_at' => now(),
        ]);

        // --- Parent Portal Auto-Creation Logic ---
        $parentNames = $request->input('parent_name', []);
        $parentContacts = $request->input('parent_contact', []);
        $parentRole = \App\Models\Role::where('name', 'parent')->first();
        foreach ($parentNames as $idx => $pname) {
            $pcontact = $parentContacts[$idx] ?? null;
            if (!$pcontact) continue;
            // Use contact as email if valid, else generate pseudo-email
            $isEmail = filter_var($pcontact, FILTER_VALIDATE_EMAIL);
            $parentEmail = $isEmail ? $pcontact : strtolower(str_replace(' ', '.', $pname)) . '.' . uniqid() . '@parent.local';
            $parentUser = \App\Models\User::where('email', $parentEmail)->first();
            if (!$parentUser) {
                $parentUser = \App\Models\User::create([
                    'name' => $pname,
                    'email' => $parentEmail,
                    'password' => bcrypt('parent123'),
                    'school_id' => $data['school_id'],
                ]);
                if ($parentRole) {
                    $parentUser->roles()->attach($parentRole->id);
                }
                // Send notification with credentials
                $parentUser->sendParentAccountNotification('parent123');
            }
            // Link parent to student
            $student->parents()->syncWithoutDetaching([$parentUser->id => ['relationship' => 'Parent', 'is_primary' => $idx === 0]]);
        }
        // --- End Parent Portal Logic ---

        // --- Document Requirements Backend Enforcement ---
        $schoolType = $request->input('school_type', 'secondary');
        $intake = $request->input('intake', '');
        $isTransfer = $request->boolean('is_transfer');
        $documentRequirements = [
            'secondary' => [
                ['key' => 'birth_certificate', 'label' => 'Birth Certificate', 'required' => true],
                ['key' => 'passport_photo', 'label' => 'Passport-size Photo', 'required' => true],
                ['key' => 'admission_letter', 'label' => 'Admission Letter', 'required' => true],
                ['key' => 'kcpe_certificate', 'label' => 'KCPE Certificate', 'required' => true, 'intake' => 'form1'],
                ['key' => 'kcse_certificate', 'label' => 'KCSE Certificate', 'required' => true, 'intake' => 'form5'],
                ['key' => 'transfer_certificate', 'label' => 'Transfer/Leaving Certificate', 'required' => true, 'transfer' => true],
            ],
            'college' => [
                ['key' => 'birth_certificate', 'label' => 'Birth Certificate', 'required' => true],
                ['key' => 'passport_photo', 'label' => 'Passport-size Photo', 'required' => true],
                ['key' => 'admission_letter', 'label' => 'Admission Letter', 'required' => true],
                ['key' => 'national_id', 'label' => 'National ID / Passport', 'required' => true],
                ['key' => 'transfer_certificate', 'label' => 'Transfer/Leaving Certificate', 'required' => true, 'transfer' => true],
            ],
            'university' => [
                ['key' => 'birth_certificate', 'label' => 'Birth Certificate', 'required' => true],
                ['key' => 'passport_photo', 'label' => 'Passport-size Photo', 'required' => true],
                ['key' => 'admission_letter', 'label' => 'Admission Letter', 'required' => true],
                ['key' => 'national_id', 'label' => 'National ID / Passport', 'required' => true],
                ['key' => 'transfer_certificate', 'label' => 'Transfer/Leaving Certificate', 'required' => true, 'transfer' => true],
            ],
        ];
        $docsConfig = $documentRequirements[$schoolType] ?? $documentRequirements['secondary'];
        $requiredDocs = collect($docsConfig)->filter(function($doc) use ($intake, $isTransfer) {
            if (isset($doc['intake']) && $doc['intake'] !== $intake) return false;
            if (isset($doc['transfer']) && $doc['transfer'] && !$isTransfer) return false;
            return $doc['required'];
        });
        $uploadedDocs = $request->file('documents', []);
        $missingDocs = [];
        foreach ($requiredDocs as $doc) {
            if (!isset($uploadedDocs[$doc['key']])) {
                $missingDocs[] = $doc['label'];
            }
        }
        if (count($missingDocs)) {
            return back()->withErrors(['documents' => 'Missing required documents: ' . implode(', ', $missingDocs)])->withInput();
        }
        // --- End Document Requirements Backend Enforcement ---

        // Save uploaded documents
        if ($uploadedDocs) {
            foreach ($uploadedDocs as $type => $file) {
                if ($file) {
                    $path = $file->store('student_documents', 'public');
                    $student->documents()->create([
                        'type' => $type,
                        'file_path' => $path,
                        'uploaded_at' => now(),
                    ]);
                }
            }
        }

        // --- Automated Fee Assignment ---
        $feeConfig = [
            // Example: class_id => [ [category, amount, due_in_days], ... ]
            1 => [
                ['Tuition', 50000, 30],
                ['Boarding', 20000, 30],
                ['Activity', 5000, 60],
            ],
            2 => [
                ['Tuition', 52000, 30],
                ['Boarding', 21000, 30],
                ['Activity', 6000, 60],
            ],
            // Add more classes/programs as needed
        ];
        $studentClassId = $student->class_id;
        $fees = $feeConfig[$studentClassId] ?? [];
        foreach ($fees as $fee) {
            $feeModel = $student->fees()->create([
                'category' => $fee[0],
                'amount' => $fee[1],
                'status' => 'unpaid',
                'due_date' => now()->addDays($fee[2]),
            ]);
            // Notify all parents
            foreach ($student->parents as $parent) {
                $parent->notify(new \App\Notifications\NewFeeAssigned($feeModel));
            }
        }
        // --- End Automated Fee Assignment ---

        if ($request->ajax()) {
            $row = view('academic::students.partials.row', compact('student'))->render();
            return response()->json(['success' => true, 'row' => $row, 'student' => $student]);
        }
        return redirect()->route('academic.students.index')->with('success', 'Student enrolled successfully.');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $allParents = \App\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'parent');
        })->get();
        $student->load('documents');
        return view('academic::students.edit', compact('student', 'allParents'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $data = $request->validate([
            'student_id' => 'required|string|unique:academic_students,student_id,' . $id,
            'name' => 'required|string|max:255',
            'admission_number' => 'required|string|unique:academic_students,admission_number,' . $id,
            'class_id' => 'required|integer',
            'admission_date' => 'required|date',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'passport' => 'nullable|file|image|max:2048',
            'status' => 'required|in:active,suspended,transferred,graduated,dropped_out',
            'status_reason' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'disabilities' => 'nullable|string',
            'allergies' => 'nullable|string',
            'stream' => 'nullable|string',
            'house' => 'nullable|string',
            'group' => 'nullable|string',
            'is_transfer' => 'nullable|boolean',
            'previous_school' => 'nullable|string',
        ]);
        $statusChanged = $student->status !== $data['status'];
        if ($statusChanged) {
            $data['status_changed_at'] = now();
        }
        if ($request->hasFile('passport')) {
            $data['passport'] = $request->file('passport')->store('passports', 'public');
        }
        $student->update($data);
        // Log enrollment history if status or class changed
        if ($statusChanged || $student->class_id != $data['class_id']) {
            EnrollmentHistory::create([
                'student_id' => $student->id,
                'class_id' => $data['class_id'],
                'academic_year' => now()->year,
                'status' => $data['status'],
                'changed_at' => now(),
            ]);
        }
        return redirect()->route('academic.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->route('academic.students.index')->with('success', 'Student deleted successfully.');
    }

    // Bulk enrollment via Excel (scaffold)
    public function showBulkImport()
    {
        return view('academic::students.bulk_import');
    }

    public function handleBulkImport(Request $request)
    {
        $request->validate([
            'excel_file' => 'required_without:confirm|file|mimes:csv,txt,xlsx',
            'documents_zip' => 'nullable|file|mimes:zip',
        ]);
        if ($request->input('confirm')) {
            $validRows = session('bulk_import_valid', []);
            $imported = 0;
            $failed = 0;
            $docMap = [];
            $missingDocs = [];
            // Handle ZIP extraction if present
            if ($request->hasFile('documents_zip')) {
                $zip = new \ZipArchive;
                $zipPath = $request->file('documents_zip')->getRealPath();
                $extractPath = storage_path('app/tmp/bulk_docs_' . uniqid());
                mkdir($extractPath, 0777, true);
                if ($zip->open($zipPath) === true) {
                    $zip->extractTo($extractPath);
                    $zip->close();
                    // Map files by student_id and doc type
                    foreach (scandir($extractPath) as $file) {
                        if (in_array($file, ['.', '..'])) continue;
                        if (preg_match('/^(.*?)_(.*?)\.(.+)$/', $file, $m)) {
                            $sid = $m[1];
                            $dtype = $m[2];
                            $docMap[$sid][$dtype] = $extractPath . '/' . $file;
                        }
                    }
                }
            }
            foreach ($validRows as $row) {
                try {
                    $student = Student::create([
                        'student_id' => $row['student_id'],
                        'name' => $row['name'],
                        'class_id' => $row['class_id'],
                        'admission_date' => $row['admission_date'],
                        'date_of_birth' => $row['date_of_birth'],
                        'gender' => $row['gender'],
                        // Add other fields as needed
                    ]);
                    // Attach documents if available
                    if (!empty($docMap[$row['student_id']])) {
                        foreach ($docMap[$row['student_id']] as $dtype => $fpath) {
                            $dest = 'student_documents/' . basename($fpath);
                            \Storage::disk('public')->put($dest, file_get_contents($fpath));
                            $student->documents()->create([
                                'type' => $dtype,
                                'file_path' => $dest,
                                'uploaded_at' => now(),
                            ]);
                        }
                    } else {
                        $missingDocs[] = $row['student_id'];
                    }
                    $imported++;
                } catch (\Exception $e) {
                    $failed++;
                }
            }
            session()->forget(['bulk_import_valid', 'bulk_import_errors']);
            $msg = "$imported students imported, $failed failed.";
            if ($missingDocs) $msg .= ' Missing docs for: ' . implode(', ', $missingDocs);
            return redirect()->route('academic.students.index')->with('success', $msg);
        }
        $file = $request->file('excel_file');
        $ext = $file->getClientOriginalExtension();
        $rows = [];
        $errors = [];
        if ($ext === 'csv' || $ext === 'txt') {
            $handle = fopen($file->getRealPath(), 'r');
            $header = fgetcsv($handle);
            $rowNum = 1;
            while (($data = fgetcsv($handle)) !== false) {
                $rowNum++;
                $row = array_combine($header, $data);
                $row['__row'] = $rowNum;
                $rows[] = $row;
            }
            fclose($handle);
        } elseif ($ext === 'xlsx') {
            if (class_exists('Maatwebsite\\Excel\\Facades\\Excel')) {
                $rows = \Maatwebsite\Excel\Facades\Excel::toArray(null, $file)[0];
                $header = array_map('strtolower', array_map('trim', $rows[0]));
                unset($rows[0]);
                $rows = array_values($rows);
                foreach ($rows as $i => $data) {
                    $row = array_combine($header, $data);
                    $row['__row'] = $i + 2;
                    $rows[$i] = $row;
                }
            } else {
                return back()->withErrors(['excel_file' => 'Excel import not supported. Please install Laravel Excel.']);
            }
        }
        $required = ['student_id','name','class_id','admission_date','date_of_birth','gender','parent_name','parent_contact'];
        $validRows = [];
        foreach ($rows as $row) {
            $rowErrors = [];
            foreach ($required as $col) {
                if (empty($row[$col])) {
                    $rowErrors[] = "$col is required";
                }
            }
            if (!empty($row['student_id']) && Student::where('student_id', $row['student_id'])->exists()) {
                $rowErrors[] = 'Duplicate student_id';
            }
            if ($rowErrors) {
                $row['__errors'] = $rowErrors;
                $errors[] = $row;
            } else {
                $validRows[] = $row;
            }
        }
        session(['bulk_import_valid' => $validRows, 'bulk_import_errors' => $errors]);
        return view('academic::students.bulk_import_preview', [
            'validRows' => $validRows,
            'errors' => $errors,
        ]);
    }

    public function bulkImportErrors()
    {
        $errors = session('bulk_import_errors', []);
        $headers = array_keys($errors[0] ?? []);
        $callback = function() use ($errors, $headers) {
            $out = fopen('php://output', 'w');
            fputcsv($out, array_merge($headers, ['errors']));
            foreach ($errors as $row) {
                $rowData = [];
                foreach ($headers as $h) {
                    $rowData[] = $row[$h] ?? '';
                }
                $rowData[] = isset($row['__errors']) ? implode('; ', $row['__errors']) : '';
                fputcsv($out, $rowData);
            }
            fclose($out);
        };
        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bulk_import_errors.csv"',
        ]);
    }

    // Add methods for transfer, assign admission numbers, etc.

    public function addParent(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);
        $data = $request->validate([
            'parent_id' => 'required|exists:users,id',
            'relationship' => 'required|string|max:255',
            'is_primary' => 'nullable|boolean',
        ]);
        $student->parents()->attach($data['parent_id'], [
            'relationship' => $data['relationship'],
            'is_primary' => $request->has('is_primary'),
        ]);
        return redirect()->route('academic.students.edit', $studentId)->with('success', 'Parent/Guardian added.');
    }

    public function removeParent($studentId, $parentId)
    {
        $student = Student::findOrFail($studentId);
        $student->parents()->detach($parentId);
        return redirect()->route('academic.students.edit', $studentId)->with('success', 'Parent/Guardian removed.');
    }

    public function uploadDocument(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);
        $data = $request->validate([
            'type' => 'required|string',
            'document' => 'required|file|max:4096',
        ]);
        $filePath = $request->file('document')->store('student_documents', 'public');
        $student->documents()->create([
            'type' => $data['type'],
            'file_path' => $filePath,
            'uploaded_at' => now(),
        ]);
        return redirect()->route('academic.students.edit', $studentId)->with('success', 'Document uploaded successfully.');
    }

    public function deleteDocument($studentId, $docId)
    {
        $student = Student::findOrFail($studentId);
        $doc = $student->documents()->findOrFail($docId);
        \Storage::disk('public')->delete($doc->file_path);
        $doc->delete();
        return redirect()->route('academic.students.edit', $studentId)->with('success', 'Document deleted successfully.');
    }

    public function verifyDocument(Request $request, $studentId, $docId)
    {
        $doc = \Modules\Academic\Models\StudentDocument::findOrFail($docId);
        $action = $request->input('action');
        $note = $request->input('review_note');
        if ($action === 'verify') {
            $doc->status = \Modules\Academic\Models\StudentDocument::STATUS_VERIFIED;
        } elseif ($action === 'reject') {
            $doc->status = \Modules\Academic\Models\StudentDocument::STATUS_REJECTED;
        }
        $doc->review_note = $note;
        $doc->save();
        return redirect()->route('academic.students.edit', $studentId)->with('success', 'Document status updated.');
    }

    public function recordPayment(Request $request, $studentId, $feeId)
    {
        $fee = \Modules\Academic\Models\StudentFee::findOrFail($feeId);
        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'method' => 'nullable|string|max:64',
            'reference' => 'nullable|string|max:64',
        ]);
        $data['student_id'] = $studentId;
        $data['fee_id'] = $feeId;
        $data['note'] = $request->input('note');
        $payment = \Modules\Academic\Models\StudentPayment::create($data);
        // Update fee status
        $fee->refresh();
        $totalPaid = $fee->payments()->sum('amount');
        if ($totalPaid >= $fee->amount) {
            $fee->status = 'paid';
        } elseif ($totalPaid > 0) {
            $fee->status = 'partial';
        } else {
            $fee->status = 'unpaid';
        }
        $fee->save();
        // Notify all parents
        $student = $fee->student;
        foreach ($student->parents as $parent) {
            $parent->notify(new \App\Notifications\PaymentReceipt($payment));
        }
        return redirect()->route('academic.students.edit', $studentId)->with('success', 'Payment recorded.');
    }

    public function idCard($id, Request $request)
    {
        $student = Student::with('class')->findOrFail($id);
        $school = $student->school ?? (object)['name' => 'School Name', 'address' => ''];
        $view = view('academic::students.id_card', compact('student', 'school'));
        if ($request->query('pdf') && class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($view->render());
            return $pdf->download('student_id_' . $student->admission_number . '.pdf');
        }
        return $view;
    }
} 