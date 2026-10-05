<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - {{ $student_name ?? 'Student' }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20mm;
        }
        
        body {
            font-family: {{ $standards['fonts']['formal'] }};
            margin: 0;
            padding: 0;
            background: white;
            color: #333;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            opacity: 0.02;
            z-index: -1;
            pointer-events: none;
        }
        
        .watermark img {
            width: 500px;
            height: 500px;
        }
        
        .certificate {
            width: 100%;
            height: 100%;
            border: 8px solid {{ $standards['colors']['primary'] }};
            border-radius: 15px;
            padding: 40px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            position: relative;
            overflow: hidden;
        }
        
        .certificate::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23{{ substr($standards['colors']['primary'], 1) }}" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23{{ substr($standards['colors']['primary'], 1) }}" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
            z-index: -1;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .school-logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            display: block;
        }
        
        .school-name {
            font-size: 36px;
            font-weight: bold;
            color: {{ $standards['colors']['primary'] }};
            margin: 0 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .school-motto {
            font-size: 18px;
            color: {{ $standards['colors']['secondary'] }};
            margin: 0 0 20px 0;
            font-style: italic;
        }
        
        .certificate-title {
            font-size: 48px;
            font-weight: bold;
            color: {{ $standards['colors']['primary'] }};
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .certificate-subtitle {
            font-size: 20px;
            color: {{ $standards['colors']['secondary'] }};
            margin: 10px 0 0 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .content {
            text-align: center;
            margin: 60px 0;
        }
        
        .presentation-text {
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .student-name {
            font-size: 48px;
            font-weight: bold;
            color: {{ $standards['colors']['primary'] }};
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        
        .achievement-text {
            font-size: 20px;
            color: #333;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .course-details {
            font-size: 18px;
            color: {{ $standards['colors']['secondary'] }};
            margin-bottom: 40px;
            line-height: 1.5;
        }
        
        .date-section {
            font-size: 18px;
            color: #333;
            margin-bottom: 60px;
        }
        
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 80px;
        }
        
        .signature-box {
            text-align: center;
            flex: 1;
            margin: 0 40px;
        }
        
        .signature-line {
            width: 250px;
            height: 3px;
            background: #333;
            margin: 80px auto 20px;
            border-radius: 2px;
        }
        
        .signature-name {
            font-weight: bold;
            font-size: 20px;
            color: {{ $standards['colors']['primary'] }};
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .signature-title {
            font-size: 16px;
            color: {{ $standards['colors']['secondary'] }};
            margin-top: 10px;
            text-transform: uppercase;
        }
        
        .qr-section {
            position: absolute;
            bottom: 40px;
            right: 40px;
            text-align: center;
        }
        
        .qr-code {
            width: 100px;
            height: 100px;
        }
        
        .qr-label {
            font-size: 12px;
            color: {{ $standards['colors']['secondary'] }};
            margin-top: 8px;
            text-transform: uppercase;
        }
        
        .certificate-number {
            position: absolute;
            bottom: 40px;
            left: 40px;
            font-size: 14px;
            color: {{ $standards['colors']['secondary'] }};
        }
        
        .stamp {
            position: absolute;
            top: 50%;
            right: 100px;
            transform: translateY(-50%);
            opacity: 0.1;
            font-size: 80px;
            color: {{ $standards['colors']['primary'] }};
            font-weight: bold;
            transform: translateY(-50%) rotate(-15deg);
        }
        
        .border-decoration {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 2px solid {{ $standards['colors']['accent'] }};
            border-radius: 10px;
            opacity: 0.3;
            pointer-events: none;
        }
    </style>
</head>
<body>
    @if($watermark)
    <div class="watermark">
        <img src="data:image/png;base64,{{ $watermark }}" alt="School Logo">
    </div>
    @endif
    
    <div class="stamp">OFFICIAL</div>
    <div class="border-decoration"></div>
    
    <div class="certificate">
        <div class="header">
            @if($school->logo)
            <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="school-logo">
            @endif
            <h1 class="school-name">{{ $school->name }}</h1>
            @if($school->motto)
            <p class="school-motto">{{ $school->motto }}</p>
            @endif
            <h2 class="certificate-title">Certificate of Completion</h2>
            <p class="certificate-subtitle">This is to certify that</p>
        </div>
        
        <div class="content">
            <p class="presentation-text">
                This is to certify that the student has successfully completed the academic requirements
                and demonstrated outstanding performance throughout the course of study.
            </p>
            
            <div class="student-name">{{ $student_name ?? 'Student Name' }}</div>
            
            <p class="achievement-text">
                has successfully completed the {{ $course_name ?? 'Academic Program' }}
                with distinction and has demonstrated exceptional academic excellence,
                leadership qualities, and commitment to learning.
            </p>
            
            <div class="course-details">
                <strong>Program:</strong> {{ $program_name ?? 'Academic Program' }}<br>
                <strong>Duration:</strong> {{ $duration ?? 'Academic Year' }}<br>
                <strong>Grade:</strong> {{ $grade ?? 'Distinction' }}<br>
                <strong>Completion Date:</strong> {{ $completion_date ?? now()->format('F d, Y') }}
            </div>
            
            <div class="date-section">
                <strong>Date of Issue:</strong> {{ now()->format('F d, Y') }}
            </div>
        </div>
        
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">Academic Director</div>
                <div class="signature-title">Academic Director</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">Principal</div>
                <div class="signature-title">Principal</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">Board Chairman</div>
                <div class="signature-title">Board Chairman</div>
            </div>
        </div>
        
        <div class="qr-section">
            {!! $qr_code !!}
            <div class="qr-label">Scan to Verify</div>
        </div>
        
        <div class="certificate-number">
            Certificate No: {{ $certificate_id ?? 'CERT-' . strtoupper(uniqid()) }}
        </div>
    </div>
</body>
</html>
