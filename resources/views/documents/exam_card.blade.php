<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Card - {{ $student->name }}</title>
    <style>
        @page {
            size: A4;
            margin: 8mm;
        }
        
        * {
            page-break-inside: avoid;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: white;
            color: #333;
            font-size: 12px;
            line-height: 1.3;
            max-height: 100vh;
            overflow: hidden;
        }
        
        .container {
            max-width: 100%;
            max-height: 100vh;
            padding: 15px;
            position: relative;
        }
        
        /* Security watermark */
        .security-watermark {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(0, 51, 102, 0.02) 10px,
                rgba(0, 51, 102, 0.02) 20px
            );
            pointer-events: none;
            z-index: -1;
        }
        
        /* Document metadata */
        .document-meta {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 10px;
            color: #666;
            text-align: right;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        
        /* Serial number */
        .serial-number {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 12px;
            font-weight: bold;
            color: #333;
            background: #f8f9fa;
            padding: 6px 10px;
            border-radius: 4px;
            border: 2px solid #dc3545;
            z-index: 10;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #dc3545;
            padding-bottom: 15px;
        }
        
        .school-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
            display: block;
        }
        
        .school-name {
            font-size: 22px;
            font-weight: bold;
            color: #dc3545;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        
        .school-motto {
            font-size: 12px;
            color: #666;
            margin: 0 0 10px 0;
            font-style: italic;
        }
        
        .exam-title {
            font-size: 20px;
            font-weight: bold;
            color: #dc3545;
            margin: 0;
            text-transform: uppercase;
        }
        
        .exam-subtitle {
            font-size: 14px;
            color: #666;
            margin: 5px 0 0 0;
            text-transform: uppercase;
        }
        
        /* Main content area */
        .main-content {
            display: flex;
            gap: 30px;
            margin-bottom: 20px;
        }
        
        /* Student Information */
        .student-info {
            flex: 1;
            padding: 15px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 8px;
            border-left: 4px solid #dc3545;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .info-row {
            display: flex;
            margin-bottom: 8px;
            align-items: center;
        }
        
        .info-label {
            font-weight: bold;
            color: #dc3545;
            width: 140px;
            font-size: 12px;
        }
        
        .info-value {
            font-weight: normal;
            color: #333;
            font-size: 12px;
        }
        
        /* Student Photo */
        .student-photo {
            width: 120px;
            height: 150px;
            background: #f0f0f0;
            border: 3px solid #dc3545;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #999;
            margin-left: 20px;
        }
        
        /* Exam Details */
        .exam-details {
            margin-bottom: 20px;
            padding: 15px;
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
        }
        
        .exam-details h3 {
            color: #856404;
            margin: 0 0 10px 0;
            font-size: 14px;
            text-transform: uppercase;
        }
        
        .exam-details p {
            margin: 5px 0;
            font-size: 12px;
            color: #333;
        }
        
        /* Subjects Table */
        .subjects-section {
            margin-bottom: 20px;
        }
        
        .subjects-title {
            font-size: 14px;
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        
        .subjects-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        
        .subjects-table th {
            background: #dc3545;
            color: white;
            padding: 8px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #c82333;
        }
        
        .subjects-table td {
            padding: 6px 4px;
            text-align: center;
            border: 1px solid #ddd;
        }
        
        .subjects-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        /* Special Notes */
        .special-notes {
            margin-bottom: 20px;
            padding: 15px;
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 8px;
        }
        
        .special-notes h3 {
            color: #155724;
            margin: 0 0 10px 0;
            font-size: 14px;
            text-transform: uppercase;
        }
        
        .special-notes ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .special-notes li {
            margin-bottom: 5px;
            font-size: 11px;
            color: #333;
        }
        
        /* Bottom section */
        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 30px;
        }
        
        /* QR Code Section */
        .qr-section {
            text-align: center;
            background: white;
            padding: 10px;
            border: 2px solid #dc3545;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .qr-title {
            font-size: 10px;
            color: #dc3545;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .qr-code {
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }
        
        .qr-label {
            font-size: 8px;
            color: #666;
            margin-top: 5px;
        }
        
        /* Signature Section */
        .signature-section {
            text-align: center;
        }
        
        .signature-line {
            width: 200px;
            height: 2px;
            background: #333;
            margin: 40px auto 10px;
        }
        
        .signature-name {
            font-weight: bold;
            font-size: 14px;
            color: #dc3545;
        }
        
        .signature-title {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        /* Footer */
        .footer {
            position: absolute;
            bottom: 20px;
            left: 20px;
            font-size: 10px;
            color: #666;
            max-width: 60%;
        }
        
        /* Warning text */
        .warning-text {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            color: #dc3545;
            margin: 15px 0;
            padding: 10px;
            background: #f8d7da;
            border: 2px solid #dc3545;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Security watermark -->
        <div class="security-watermark"></div>
        
        <!-- Document metadata -->
        <div class="document-meta">
            Issue Date: {{ now()->format('M d, Y') }}<br>
            Valid Until: {{ $exam->exam_date ? \Carbon\Carbon::parse($exam->exam_date)->addDays(7)->format('M d, Y') : now()->addDays(7)->format('M d, Y') }}<br>
            Doc ID: {{ $exam->id ?? 'N/A' }}
        </div>
        
        <!-- Serial Number -->
        <div class="serial-number">
            Serial No: {{ $serialNumber ?? 'EC-' . ($exam->id ?? '001') . '-' . ($student->id ?? '001') }}
        </div>
        
        <!-- Header -->
        <div class="header">
            @if($school && $school->logo)
            <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="school-logo">
            @endif
            <h1 class="school-name">{{ $school->name ?? 'School Name' }}</h1>
            @if($school && isset($school->motto) && $school->motto)
            <p class="school-motto">{{ $school->motto }}</p>
            @endif
            <h2 class="exam-title">{{ $exam->name ?? 'Examination' }}</h2>
            <p class="exam-subtitle">Entry Card</p>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Student Information -->
            <div class="student-info">
                <div class="info-row">
                    <span class="info-label">Student Name:</span>
                    <span class="info-value">{{ $student->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Admission No:</span>
                    <span class="info-value">{{ $student->admission_number ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Class:</span>
                    <span class="info-value">{{ $student->class->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Stream:</span>
                    <span class="info-value">{{ $student->stream ?: '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Gender:</span>
                    <span class="info-value">{{ $student->gender ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Exam Center:</span>
                    <span class="info-value">{{ $exam->exam_center ?? 'Main Hall' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Room:</span>
                    <span class="info-value">{{ $exam->exam_room ?? 'Room 1' }}</span>
                </div>
            </div>
            
            <!-- Student Photo -->
            <div class="student-photo">
                @if($student && $student->passport)
                <img src="{{ asset('storage/' . $student->passport) }}" alt="Student Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                @else
                No Photo
                @endif
            </div>
        </div>
        
        <!-- Exam Details -->
        <div class="exam-details">
            <h3>Examination Details</h3>
            <p><strong>Exam Name:</strong> {{ $exam->name ?? 'N/A' }}</p>
            <p><strong>Term:</strong> {{ $exam->term ?? 'N/A' }}</p>
            <p><strong>Academic Year:</strong> {{ $exam->academic_year ?? 'N/A' }}</p>
            <p><strong>Exam Date:</strong> {{ $exam->exam_date ? \Carbon\Carbon::parse($exam->exam_date)->format('F d, Y') : 'TBD' }}</p>
            <p><strong>Exam Time:</strong> {{ $exam->exam_time ?? '8:00 AM - 5:00 PM' }}</p>
            <p><strong>Duration:</strong> {{ $exam->duration ?? 'Full Day' }}</p>
        </div>
        
        <!-- Warning Text -->
        <div class="warning-text">
            ⚠️ NO ENTRY TO EXAM HALL WITHOUT THIS CARD ⚠️
        </div>
        
        <!-- Subjects Section -->
        <div class="subjects-section">
            <div class="subjects-title">Registered Subjects</div>
            <table class="subjects-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Paper</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($exam->subjects) && is_array($exam->subjects))
                        @foreach($exam->subjects as $index => $subject)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $subject['code'] ?? 'SUB' . ($index + 1) }}</td>
                            <td>{{ $subject['name'] ?? 'Subject ' . ($index + 1) }}</td>
                            <td>{{ $subject['paper'] ?? 'Paper 1' }}</td>
                            <td>{{ $subject['duration'] ?? '2 Hours' }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px; color: #666;">No subjects registered</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- Special Notes -->
        <div class="special-notes">
            <h3>Important Instructions</h3>
            <ul>
                <li>Arrive at least 30 minutes before exam time</li>
                <li>Bring only necessary stationery (pens, pencils, rulers)</li>
                <li>No electronic devices allowed in exam hall</li>
                <li>Present this card to invigilator before entering</li>
                <li>Follow all exam rules and regulations</li>
                <li>Any form of cheating will result in disqualification</li>
            </ul>
        </div>
        
        <!-- Bottom Section -->
        <div class="bottom-section">
            <!-- QR Code Section -->
            <div class="qr-section">
                <div class="qr-title">Verification QR Code</div>
                @if($qr_code)
                    {!! $qr_code !!}
                @else
                    <div class="qr-code" style="width: 80px; height: 80px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 8px; color: #999; border: 1px solid #ccc;">
                        QR Code
                    </div>
                @endif
                <div class="qr-label">Scan to verify</div>
            </div>
            
            <!-- Signature Section -->
            <div class="signature-section">
                <div class="signature-line"></div>
                <div class="signature-name">Exams Officer</div>
                <div class="signature-title">Exams Officer</div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            Generated on: {{ now()->format('F d, Y \a\t g:i A') }}<br>
            This is a computer generated document<br>
            <span style="font-size: 9px;">
                {{ $school->address ?? 'School Address' }} | 
                Tel: {{ $school->phone ?? 'N/A' }} | 
                Email: {{ $school->email ?? 'N/A' }}
            </span>
        </div>
    </div>
</body>
</html>
