<!DOCTYPE html>
<html>
<head>
    <title>Fee Receipt - {{ $school->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; padding-bottom: 10px; margin-bottom: 20px; }
        .school-name { font-size: 20px; font-weight: bold; color: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; }
        .receipt-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .info-row { margin: 5px 0; }
        .label { font-weight: bold; color: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; }
        .amount { font-size: 18px; font-weight: bold; color: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ $school->name }}</div>
        <div>Fee Receipt</div>
    </div>
    
    <div class="receipt-info">
        <div class="info-row">
            <span class="label">Receipt No:</span> {{ $receiptNumber }}
        </div>
        <div class="info-row">
            <span class="label">Student Name:</span> {{ $studentFee->student->name }}
        </div>
        <div class="info-row">
            <span class="label">Student ID:</span> {{ $studentFee->student->student_id }}
        </div>
        <div class="info-row">
            <span class="label">Fee Type:</span> {{ $studentFee->fee->name }}
        </div>
        <div class="info-row">
            <span class="label">Due Date:</span> {{ $studentFee->due_date }}
        </div>
        <div class="info-row">
            <span class="label">Status:</span> {{ ucfirst($studentFee->status) }}
        </div>
        <div class="info-row">
            <span class="label">Amount:</span> <span class="amount">${{ number_format($studentFee->amount, 2) }}</span>
        </div>
    </div>
    
    <div class="footer">
        <p>Generated: {{ $generatedAt }}</p>
        <p>{{ $school->name }} - {{ $school->settings['address'] ?? 'Address not available' }}</p>
        <p>Phone: {{ $school->settings['phone'] ?? 'Phone not available' }}</p>
    </div>
</body>
</html>
