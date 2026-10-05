<!DOCTYPE html>
<html>
<head>
    <title>Exam Card - {{ $school->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; padding-bottom: 10px; margin-bottom: 20px; }
        .school-name { font-size: 20px; font-weight: bold; color: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; }
        .exam-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .info-row { margin: 5px 0; }
        .label { font-weight: bold; color: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; }
        .students-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .students-table th, .students-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .students-table th { background: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ $school->name }}</div>
        <div>Exam Card</div>
    </div>
    
    <div class="exam-info">
        <div class="info-row">
            <span class="label">Exam:</span> {{ $exam->name }}
        </div>
        <div class="info-row">
            <span class="label">Code:</span> {{ $exam->code }}
        </div>
        <div class="info-row">
            <span class="label">Date:</span> {{ $exam->start_date }}
        </div>
        <div class="info-row">
            <span class="label">Duration:</span> {{ $exam->duration_minutes }} minutes
        </div>
        <div class="info-row">
            <span class="label">Total Marks:</span> {{ $exam->total_marks }}
        </div>
    </div>
    
    <h3>Students</h3>
    <table class="students-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Student ID</th>
                <th>Class</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->name }}</td>
                <td>{{ $student->student_id }}</td>
                <td>{{ $student->class->name ?? 'Not Assigned' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px; font-size: 12px; color: #666;">
        Generated: {{ $generatedAt }}
    </div>
</body>
</html>
