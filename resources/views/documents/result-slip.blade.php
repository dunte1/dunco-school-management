<!DOCTYPE html>
<html>
<head>
    <title>Result Slip - {{ $school->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; padding-bottom: 10px; margin-bottom: 20px; }
        .school-name { font-size: 20px; font-weight: bold; color: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; }
        .student-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .info-row { margin: 5px 0; }
        .label { font-weight: bold; color: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; }
        .exam-info { background: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .results-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .results-table th, .results-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .results-table th { background: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; color: white; }
        .total-row { font-weight: bold; background: #f8f9fa; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ $school->name }}</div>
        <div>Student Result Slip</div>
    </div>
    
    <div class="student-info">
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
    
    @if($examResult)
    <div class="exam-info">
        <div class="info-row">
            <span class="label">Exam:</span> {{ $examResult->exam->name }}
        </div>
        <div class="info-row">
            <span class="label">Exam Code:</span> {{ $examResult->exam->code }}
        </div>
        <div class="info-row">
            <span class="label">Exam Date:</span> {{ $examResult->exam->start_date }}
        </div>
        <div class="info-row">
            <span class="label">Total Marks:</span> {{ $examResult->exam->total_marks }}
        </div>
    </div>
    
    <h3>Exam Results</h3>
    <table class="results-table">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Marks Obtained</th>
                <th>Total Marks</th>
                <th>Percentage</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $examResult->subject_name ?? 'Subject' }}</td>
                <td>{{ $examResult->marks_obtained ?? 'N/A' }}</td>
                <td>{{ $examResult->total_marks ?? 'N/A' }}</td>
                <td>{{ $examResult->percentage ?? 'N/A' }}%</td>
                <td>{{ $examResult->grade ?? 'N/A' }}</td>
            </tr>
            <tr class="total-row">
                <td><strong>Total</strong></td>
                <td><strong>{{ $examResult->marks_obtained ?? 'N/A' }}</strong></td>
                <td><strong>{{ $examResult->total_marks ?? 'N/A' }}</strong></td>
                <td><strong>{{ $examResult->percentage ?? 'N/A' }}%</strong></td>
                <td><strong>{{ $examResult->grade ?? 'N/A' }}</strong></td>
            </tr>
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 20px; color: #666;">
        <p>No exam results available for this student.</p>
        <p>Please check back later or contact the administration.</p>
    </div>
    @endif
    
    <div class="footer">
        <p>Generated: {{ $generatedAt }}</p>
        <p>{{ $school->name }} - {{ $school->settings['address'] ?? 'Address not available' }}</p>
        <p>Phone: {{ $school->settings['phone'] ?? 'Phone not available' }}</p>
    </div>
</body>
</html>
