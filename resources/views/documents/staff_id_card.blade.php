<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff ID Card - {{ $staff->first_name ?? 'Staff' }} {{ $staff->last_name ?? 'Member' }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        
        * {
            page-break-inside: avoid;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f8f9fa;
        }
        
        .id-card {
            width: 340px;
            height: 210px;
            border: 3px solid #dc3545;
            border-radius: 15px;
            padding: 16px;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.15);
            position: relative;
            overflow: hidden;
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
                rgba(220, 53, 69, 0.02) 10px,
                rgba(220, 53, 69, 0.02) 20px
            );
            pointer-events: none;
            z-index: -1;
        }
        
        /* Top accent bar */
        .id-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #dc3545, #c82333, #dc3545);
            border-radius: 12px 12px 0 0;
        }
        
        /* Header section */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            position: relative;
        }
        
        .school-logo {
            width: 45px;
            height: 45px;
            margin: 0 auto 6px;
            display: block;
            border-radius: 8px;
            border: 2px solid #dc3545;
        }
        
        .school-name {
            font-size: 16px;
            font-weight: bold;
            color: #dc3545;
            margin: 0 0 3px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .school-motto {
            font-size: 10px;
            color: #6c757d;
            margin: 0;
            font-style: italic;
        }
        
        .card-type {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #dc3545;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        /* Main content area */
        .card-content {
            display: flex;
            gap: 15px;
            height: 120px;
        }
        
        /* Photo section */
        .photo-section {
            flex: 0 0 80px;
            position: relative;
        }
        
        .staff-photo {
            width: 80px;
            height: 100px;
            border: 3px solid #dc3545;
            border-radius: 10px;
            object-fit: cover;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 10px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.1);
        }
        
        .photo-label {
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            background: #dc3545;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        /* Information section */
        .info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .staff-info {
            font-size: 11px;
            line-height: 1.4;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 6px;
            align-items: center;
        }
        
        .info-label {
            font-weight: bold;
            color: #dc3545;
            width: 70px;
            flex-shrink: 0;
            font-size: 10px;
            text-transform: uppercase;
        }
        
        .info-value {
            color: #333;
            font-weight: 500;
            font-size: 11px;
        }
        
        .staff-name {
            font-size: 14px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* QR Code section */
        .qr-section {
            position: absolute;
            bottom: 10px;
            right: 10px;
            text-align: center;
        }
        
        .qr-code {
            width: 50px;
            height: 50px;
            border: 2px solid #dc3545;
            border-radius: 6px;
            background: white;
            padding: 2px;
        }
        
        .qr-label {
            font-size: 7px;
            color: #6c757d;
            margin-top: 3px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        /* Barcode section */
        .barcode-section {
            position: absolute;
            bottom: 10px;
            left: 10px;
            text-align: center;
        }
        
        .barcode {
            width: 80px;
            height: 25px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #6c757d;
            font-weight: bold;
        }
        
        .barcode-label {
            font-size: 7px;
            color: #6c757d;
            margin-top: 2px;
            text-transform: uppercase;
        }
        
        /* Validity section */
        .validity {
            position: absolute;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            background: #28a745;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        /* Serial number */
        .serial-number {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 8px;
            color: #6c757d;
            font-weight: bold;
            background: rgba(255, 255, 255, 0.9);
            padding: 2px 6px;
            border-radius: 4px;
        }
        
        /* Department badge */
        .department-badge {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: #ffc107;
            color: #333;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="id-card">
        <!-- Security watermark -->
        <div class="security-watermark"></div>
        
        <!-- Serial number -->
        <div class="serial-number">
            ID: {{ $serialNumber ?? 'STAFF-' . ($staff->id ?? '001') }}
        </div>
        
        <!-- Department badge -->
        <div class="department-badge">
            {{ $staff->department->name ?? 'Staff' }}
        </div>
        
        <!-- Card type badge -->
        <div class="card-type">Staff ID</div>
        
        <!-- Header -->
        <div class="header">
            @if($school && $school->logo)
            <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="school-logo">
            @endif
            <h1 class="school-name">{{ $school->name ?? 'School Name' }}</h1>
            @if($school && isset($school->motto) && $school->motto)
            <p class="school-motto">{{ $school->motto }}</p>
            @endif
        </div>
        
        <!-- Main content -->
        <div class="card-content">
            <!-- Photo section -->
            <div class="photo-section">
                @if($staff && $staff->photo)
                <img src="{{ asset('storage/' . $staff->photo) }}" alt="Staff Photo" class="staff-photo">
                @else
                <div class="staff-photo">
                    <div style="text-align: center;">
                        <div style="font-size: 20px; margin-bottom: 5px;">👤</div>
                        No Photo
                    </div>
                </div>
                @endif
                <div class="photo-label">Photo</div>
            </div>
            
            <!-- Information section -->
            <div class="info-section">
                <div class="staff-info">
                    <div class="staff-name">{{ $staff->first_name ?? 'Staff' }} {{ $staff->last_name ?? 'Member' }}</div>
                    
                    <div class="info-row">
                        <span class="info-label">Staff ID:</span>
                        <span class="info-value">{{ $staff->staff_id ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Role:</span>
                        <span class="info-value">{{ $staff->job_title ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Dept:</span>
                        <span class="info-value">{{ $staff->department->name ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $staff->email ?? 'N/A' }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Phone:</span>
                        <span class="info-value">{{ $staff->phone ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- QR Code -->
        <div class="qr-section">
            @if($qr_code)
                {!! $qr_code !!}
            @else
                <div class="qr-code" style="display: flex; align-items: center; justify-content: center; font-size: 8px; color: #999;">
                    QR
                </div>
            @endif
            <div class="qr-label">Scan for Portal</div>
        </div>
        
        <!-- Barcode -->
        <div class="barcode-section">
            @if($barcode)
                {!! $barcode !!}
            @else
                <div class="barcode">{{ $staff->staff_id ?? 'STAFF001' }}</div>
            @endif
            <div class="barcode-label">Barcode</div>
        </div>
        
        <!-- Validity -->
        <div class="validity">
            Valid until: {{ now()->addYear()->format('M Y') }}
        </div>
    </div>
</body>
</html>
