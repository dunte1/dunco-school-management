<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Card - {{ $user->name ?? 'User' }}</title>
    <style>
        @page {
            size: CR80;
            margin: 5mm;
        }
        
        body {
            font-family: {{ $standards['fonts']['primary'] }};
            margin: 0;
            padding: 0;
            background: white;
            color: #333;
        }
        
        .library-card {
            width: 86mm;
            height: 54mm;
            border: 1px solid {{ $standards['colors']['primary'] }};
            border-radius: 8px;
            padding: 8px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .library-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, {{ $standards['colors']['primary'] }}, {{ $standards['colors']['accent'] }});
        }
        
        .header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 6px;
        }
        
        .school-logo {
            width: 20px;
            height: 20px;
            margin: 0 auto 2px;
            display: block;
        }
        
        .school-name {
            font-size: 8px;
            font-weight: bold;
            color: {{ $standards['colors']['primary'] }};
            margin: 0;
            text-transform: uppercase;
        }
        
        .card-title {
            font-size: 6px;
            color: {{ $standards['colors']['secondary'] }};
            margin: 1px 0 0 0;
            text-transform: uppercase;
        }
        
        .card-content {
            display: flex;
            gap: 8px;
            height: 35mm;
        }
        
        .photo-section {
            flex: 0 0 25mm;
        }
        
        .user-photo {
            width: 25mm;
            height: 30mm;
            border: 1px solid {{ $standards['colors']['primary'] }};
            border-radius: 4px;
            object-fit: cover;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #888;
            font-size: 6px;
        }
        
        .info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .user-info {
            font-size: 7px;
            line-height: 1.2;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 3px;
        }
        
        .info-label {
            font-weight: bold;
            color: {{ $standards['colors']['primary'] }};
            width: 20mm;
            flex-shrink: 0;
        }
        
        .info-value {
            color: #333;
        }
        
        .card-type {
            font-size: 6px;
            color: {{ $standards['colors']['accent'] }};
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin-top: 4px;
        }
        
        .qr-section {
            position: absolute;
            bottom: 4px;
            right: 4px;
            text-align: center;
        }
        
        .qr-code {
            width: 15mm;
            height: 15mm;
        }
        
        .qr-label {
            font-size: 4px;
            color: {{ $standards['colors']['secondary'] }};
            margin-top: 1px;
        }
        
        .barcode-section {
            position: absolute;
            bottom: 4px;
            left: 4px;
            text-align: center;
        }
        
        .barcode {
            width: 20mm;
            height: 8mm;
        }
        
        .barcode-label {
            font-size: 4px;
            color: {{ $standards['colors']['secondary'] }};
            margin-top: 1px;
        }
        
        .validity {
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 4px;
            color: {{ $standards['colors']['secondary'] }};
            text-align: center;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.03;
            z-index: -1;
        }
        
        .watermark img {
            width: 60mm;
            height: 60mm;
        }
        
        .library-rules {
            position: absolute;
            top: 4px;
            right: 4px;
            background: {{ $standards['colors']['accent'] }};
            color: white;
            padding: 1px 3px;
            border-radius: 2px;
            font-size: 4px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="library-card">
        @if($watermark)
        <div class="watermark">
            <img src="data:image/png;base64,{{ $watermark }}" alt="School Logo">
        </div>
        @endif
        
        <div class="library-rules">Library</div>
        
        <div class="header">
            @if($school->logo)
            <img src="{{ asset('storage/' . $school->logo) }}" alt="School Logo" class="school-logo">
            @endif
            <h1 class="school-name">{{ $school->name }}</h1>
            <p class="card-title">Library Card</p>
        </div>
        
        <div class="card-content">
            <div class="photo-section">
                @if($user->photo)
                <img src="{{ asset('storage/' . $user->photo) }}" alt="User Photo" class="user-photo">
                @else
                <div class="user-photo">No Photo</div>
                @endif
            </div>
            
            <div class="info-section">
                <div class="user-info">
                    <div class="info-row">
                        <span class="info-label">Name:</span>
                        <span class="info-value">{{ $user->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">ID:</span>
                        <span class="info-value">{{ $user->library_id ?? $user->id ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Type:</span>
                        <span class="info-value">{{ $user->role ?? 'Student' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Class:</span>
                        <span class="info-value">{{ $user->class->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{ $user->email ?? 'N/A' }}</span>
                    </div>
                </div>
                
                <div class="card-type">Library Card</div>
            </div>
        </div>
        
        <div class="qr-section">
            {!! $qr_code !!}
            <div class="qr-label">Scan for Portal</div>
        </div>
        
        <div class="barcode-section">
            {!! $barcode !!}
            <div class="barcode-label">{{ $user->library_id ?? $user->id ?? 'N/A' }}</div>
        </div>
        
        <div class="validity">
            Valid until: {{ now()->addYear()->format('M Y') }}
        </div>
    </div>
</body>
</html>
