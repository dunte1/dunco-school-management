<!DOCTYPE html>
<html>
<head>
    <title>Staff ID Card - {{ $school->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .card { width: 350px; border: 2px solid {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; padding-bottom: 10px; }
        .school-name { font-size: 18px; font-weight: bold; color: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; }
        .info { margin: 15px 0; }
        .info-row { margin: 5px 0; }
        .label { font-weight: bold; color: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; }
        .card-number { text-align: center; font-weight: bold; margin: 15px 0; }
        .role { text-align: center; font-style: italic; color: {{ $school->theme === 'blue' ? '#2563eb' : '#059669' }}; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="school-name">{{ $school->name }}</div>
            <div>Staff Identity Card</div>
        </div>
        
        <div class="info">
            <div class="info-row">
                <span class="label">Name:</span> {{ $user->name }}
            </div>
            <div class="info-row">
                <span class="label">Email:</span> {{ $user->email }}
            </div>
            <div class="info-row">
                <span class="label">Staff ID:</span> {{ $user->id }}
            </div>
            <div class="info-row">
                <span class="label">Role:</span> {{ $role->name ?? 'Not Assigned' }}
            </div>
        </div>
        
        <div class="card-number">{{ $cardNumber }}</div>
        <div class="role">{{ $role->name ?? 'Staff Member' }}</div>
        
        <div style="font-size: 12px; color: #666;">
            Valid Until: {{ $validUntil }}<br>
            Generated: {{ $generatedAt }}
        </div>
    </div>
</body>
</html>
