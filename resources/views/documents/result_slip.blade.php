<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Slip - {{ $student->name }}</title>
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
            padding: 10px;
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
        
        /* Document metadata - Made more visible */
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
        
        /* Serial number - Made more visible */
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
            border: 2px solid #007bff;
            z-index: 10;
        }
        
        /* OFFICIAL stamp */
        .stamp {
            position: absolute;
            top: 50%;
            right: 50px;
            transform: translateY(-50%) rotate(-15deg);
            opacity: 0.08;
            font-size: 80px;
            color: #007bff;
            font-weight: bold;
            z-index: -1;
        }
        
        /* Header */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        
        .school-logo {
            width: 70px;
            height: 70px;
            margin: 0 auto 8px;
            display: block;
        }
        
        .school-name {
            font-size: 20px;
            font-weight: bold;
            color: #007bff;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        
        .school-motto {
            font-size: 12px;
            color: #666;
            margin: 0 0 8px 0;
            font-style: italic;
        }
        
        .exam-title {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            margin: 0;
            text-transform: uppercase;
        }
        
        .exam-details {
            font-size: 12px;
            color: #666;
            margin: 5px 0 0 0;
        }
        
        /* Student Information */
        .student-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 12px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 8px;
            border-left: 4px solid #007bff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .student-details {
            flex: 1;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 6px;
            align-items: center;
        }
        
        .info-label {
            font-weight: bold;
            color: #007bff;
            width: 120px;
            font-size: 12px;
        }
        
        .info-value {
            font-weight: normal;
            color: #333;
            font-size: 12px;
        }
        
        .student-photo {
            width: 80px;
            height: 100px;
            background: #f0f0f0;
            border: 2px solid #ddd;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #999;
            margin-left: 15px;
        }
        
        /* Section separator */
        .section-separator {
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #007bff 50%, transparent 100%);
            margin: 15px 0;
            opacity: 0.5;
        }
        
        /* Results Table */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 11px;
        }
        
        .results-table th {
            background: #007bff;
            color: white;
            padding: 8px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #0056b3;
        }
        
        .results-table td {
            padding: 6px 4px;
            text-align: center;
            border: 1px solid #ddd;
        }
        
        .results-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .results-table tr:hover {
            background: rgba(0, 123, 255, 0.05);
        }
        
        /* Summary Grid */
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #007bff;
        }
        
        .summary-label {
            font-weight: bold;
            color: #007bff;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .summary-value {
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }
        
        /* Performance Analysis */
        .performance-analysis {
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #007bff;
        }
        
        .performance-title {
            font-size: 12px;
            color: #007bff;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .performance-content {
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        
        /* Signatures - Row format with only Principal and Exams Officer */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            margin-bottom: 80px; /* Space for QR code */
        }
        
        .signature-box {
            text-align: center;
            flex: 1;
            margin: 0 30px;
        }
        
        .signature-line {
            width: 180px;
            height: 2px;
            background: #333;
            margin: 40px auto 10px;
        }
        
        .signature-name {
            font-weight: bold;
            font-size: 14px;
            color: #007bff;
        }
        
        .signature-title {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        /* QR Code Section - Made more visible */
        .qr-section {
            position: absolute;
            bottom: 20px;
            right: 20px;
            text-align: center;
            background: white;
            padding: 10px;
            border: 2px solid #007bff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .qr-title {
            font-size: 10px;
            color: #007bff;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .qr-code {
            width: 100px;
            height: 100px;
            margin: 0 auto;
        }
        
        .qr-label {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
        }
        
        .qr-serial {
            font-size: 8px;
            color: #999;
            margin-top: 3px;
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
        
        /* Performance indicators */
        .performance-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-left: 5px;
        }
        
        .performance-excellent { background-color: #28a745; }
        .performance-good { background-color: #17a2b8; }
        .performance-average { background-color: #ffc107; }
        .performance-poor { background-color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Security watermark -->
        <div class="security-watermark"></div>
        
        <!-- Document metadata -->
        <div class="document-meta">
            Issue Date: {{ now()->format('M d, Y') }}<br>
            Valid Until: {{ now()->addMonths(3)->format('M d, Y') }}<br>
            Doc ID: {{ $result->id ?? 'N/A' }}
        </div>
        
        <!-- Serial Number -->
        <div class="serial-number">
            Serial No: {{ $serialNumber ?? 'RS-2024-000001' }}
    </div>
    
        <!-- OFFICIAL stamp -->
    <div class="stamp">OFFICIAL</div>
    
        <!-- Header -->
    <div class="header">
            @if($school && $school->logo)
        <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="school-logo">
        @endif
            <h1 class="school-name">{{ $school->name ?? 'School Name' }}</h1>
            @if($school && isset($school->motto) && $school->motto)
        <p class="school-motto">{{ $school->motto }}</p>
        @endif
        <h2 class="exam-title">{{ $result->exam->name ?? 'Examination Result' }}</h2>
        <p class="exam-details">
            Term: {{ $result->exam->term ?? 'N/A' }} | 
            Academic Year: {{ $result->exam->academic_year ?? 'N/A' }} | 
                Date: {{ $result->created_at ? $result->created_at->format('F d, Y') : now()->format('F d, Y') }}
        </p>
    </div>
    
        <!-- Student Information -->
    <div class="student-info">
        <div class="student-details">
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
        </div>
        
        <div class="student-photo">
                @if($student && $student->passport)
                <img src="{{ asset('storage/' . $student->passport) }}" alt="Student Photo" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
            @else
            No Photo
            @endif
        </div>
    </div>
    
        <div class="section-separator"></div>
        
        <!-- Results Table -->
    <table class="results-table">
        <thead>
            <tr>
                    <th>No.</th>
                <th>Subject</th>
                <th>Marks Obtained</th>
                <th>Total Marks</th>
                <th>Percentage</th>
                <th>Grade</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalMarks = 0;
                $obtainedMarks = 0;
                    $allResults = collect();
            @endphp
            
                @if(isset($result->subjects) && is_array($result->subjects))
                    @foreach($result->subjects as $index => $subject)
                        @php
                            $marks = $subject['marks'] ?? 0;
                            $total = $subject['total'] ?? 100;
                            $percentage = $total > 0 ? ($marks / $total) * 100 : 0;
                            $totalMarks += $total;
                            $obtainedMarks += $marks;
                            
                            // Determine grade
                            if ($percentage >= 80) $grade = 'A';
                            elseif ($percentage >= 70) $grade = 'B';
                            elseif ($percentage >= 60) $grade = 'C';
                            elseif ($percentage >= 50) $grade = 'D';
                            else $grade = 'E';
                            
                            // Determine remarks
                            if ($percentage >= 80) $remarks = 'Excellent Performance';
                            elseif ($percentage >= 70) $remarks = 'Very Good Work';
                            elseif ($percentage >= 60) $remarks = 'Good Work';
                            elseif ($percentage >= 50) $remarks = 'Average';
                            else $remarks = 'Needs Improvement';
                            
                            $allResults->push((object)[
                                'subject' => (object)['name' => $subject['name'] ?? 'Subject ' . ($index + 1)],
                                'percentage' => $percentage
                            ]);
                @endphp
                <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $subject['name'] ?? 'Subject ' . ($index + 1) }}</td>
                            <td>{{ $marks }}</td>
                            <td>{{ $total }}</td>
                            <td>{{ round($percentage, 1) }}%</td>
                            <td>
                                {{ $grade }}
                                <span class="performance-indicator performance-{{ $percentage >= 80 ? 'excellent' : ($percentage >= 70 ? 'good' : ($percentage >= 50 ? 'average' : 'poor')) }}"></span>
                            </td>
                            <td>{{ $remarks }}</td>
                </tr>
            @endforeach
                @else
                <tr>
                        <td colspan="7" style="text-align: center; padding: 20px; color: #666;">No results available</td>
                </tr>
            @endif
        </tbody>
    </table>
    
        <!-- Summary Grid -->
        <div class="summary-grid">
            <div class="summary-item">
                <span class="summary-label">Total Marks:</span>
                <span class="summary-value">{{ $totalMarks }}</span>
        </div>
            <div class="summary-item">
                <span class="summary-label">Marks Obtained:</span>
                <span class="summary-value">{{ $obtainedMarks }}</span>
        </div>
            <div class="summary-item">
                <span class="summary-label">Percentage:</span>
                <span class="summary-value">{{ $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 1) : 0 }}%</span>
        </div>
            <div class="summary-item">
                <span class="summary-label">Grade:</span>
                <span class="summary-value">
                @php
                    $percentage = $totalMarks > 0 ? ($obtainedMarks / $totalMarks) * 100 : 0;
                    if ($percentage >= 80) echo 'A';
                    elseif ($percentage >= 70) echo 'B';
                    elseif ($percentage >= 60) echo 'C';
                    elseif ($percentage >= 50) echo 'D';
                    else echo 'E';
                @endphp
                </span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Position:</span>
                <span class="summary-value">15/40</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Status:</span>
                <span class="summary-value">{{ $percentage >= 50 ? 'PASS' : 'FAIL' }}</span>
            </div>
        </div>
        
        <!-- Performance Analysis -->
        <div class="performance-analysis">
            <div class="performance-title">Performance Analysis</div>
            <div class="performance-content">
                @php
                    $strongSubjects = $allResults->filter(function($result) {
                        return ($result->percentage ?? 0) >= 70;
                    })->pluck('subject.name')->implode(', ');
                    
                    $weakSubjects = $allResults->filter(function($result) {
                        return ($result->percentage ?? 0) < 50;
                    })->pluck('subject.name')->implode(', ');
                @endphp
                
                <strong>Strengths:</strong> {{ $strongSubjects ?: 'Consistent performance across subjects' }}<br>
                <strong>Areas for Improvement:</strong> {{ $weakSubjects ?: 'Continue current study habits' }}<br>
                <strong>Recommendation:</strong> {{ $percentage >= 70 ? 'Excellent work! Keep up the good performance.' : ($percentage >= 50 ? 'Good effort. Focus on improving weaker subjects.' : 'Additional support and practice needed.') }}
        </div>
    </div>
    
        <!-- Signatures - Only Principal and Exams Officer in row format -->
    <div class="signatures">
        <div class="signature-box">
            <div class="signature-line"></div>
                <div class="signature-name">Principal</div>
                <div class="signature-title">Principal</div>
        </div>
        <div class="signature-box">
            <div class="signature-line"></div>
                <div class="signature-name">Exams Officer</div>
                <div class="signature-title">Exams Officer</div>
        </div>
    </div>
    
        <!-- QR Code Section -->
    <div class="qr-section">
            <div class="qr-title">Verification QR Code</div>
            @if($qr_code)
        {!! $qr_code !!}
            @else
                <div class="qr-code" style="width: 100px; height: 100px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999; border: 1px solid #ccc;">
                    QR Code
                </div>
            @endif
            <div class="qr-label">Scan to verify authenticity</div>
            <div class="qr-serial">Serial: {{ $serialNumber ?? 'RS-2024-000001' }}</div>
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
