<!DOCTYPE html>
<html>
<head>
    <title>Library Card - {{ $school->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .card { width: 350px; border: 2px solid {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; padding-bottom: 10px; }
        .school-name { font-size: 18px; font-weight: bold; color: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; }
        .info { margin: 15px 0; }
        .info-row { margin: 5px 0; }
        .label { font-weight: bold; color: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; }
        .card-number { text-align: center; font-weight: bold; margin: 15px 0; }
        .library-info { background: #f8f9fa; padding: 10px; border-radius: 5px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="school-name">{{ $school->name }}</div>
            <div>Library Membership Card</div>
        </div>
        
        <div class="info">
            <div class="info-row">
                <span class="label">Student Name:</span> {{ $student->name }}
            </div>
            <div class="info-row">
                <span class="label">Student ID:</span> {{ $student->student_id }}
            </div>
            <div class="info-row">
                <span class="label">Class:</span> {{ $student->class->name ?? 'Not Assigned' }}
            </div>
            <div class="info-row">
                <span class="label">Academic Year:</span> {{ $student->class->academic_year ?? '2024-2025' }}
            </div>
        </div>
        
        <div class="card-number">{{ $cardNumber }}</div>
        
        <div class="library-info">
            <div style="text-align: center; font-weight: bold; color: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }};">
                Library Rules:
            </div>
            <div style="font-size: 12px; margin-top: 5px;">
                • Maximum 3 books at a time<br>
                • Return within 14 days<br>
                • Keep books in good condition<br>
                • Report lost books immediately
            </div>
        </div>
        
        <div style="font-size: 12px; color: #666;">
            Valid Until: {{ $validUntil }}<br>
            Generated: {{ $generatedAt }}
        </div>
    </div>
</body>
</html>
