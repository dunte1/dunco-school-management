<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Online Class Scheduled</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .class-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .detail-item {
            margin: 10px 0;
            padding: 5px 0;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .join-button {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .join-button:hover {
            background: #218838;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📚 New Online Class Scheduled</h1>
        <p>You have a new online class scheduled!</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $recipient->name }}!</h2>
        
        <p>A new online class has been scheduled for you. Here are the details:</p>
        
        <div class="class-details">
            <h3>{{ $onlineClass->title }}</h3>
            
            <div class="detail-item">
                <span class="detail-label">Teacher:</span> {{ $onlineClass->teacher->name }}
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Subject:</span> {{ $onlineClass->subject->name ?? 'General' }}
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Date & Time:</span> {{ $onlineClass->start_time->format('M d, Y \a\t g:i A') }}
            </div>
            
            <div class="detail-item">
                <span class="detail-label">Duration:</span> {{ $onlineClass->start_time->diffInMinutes($onlineClass->end_time) }} minutes
            </div>
            
            @if($onlineClass->meeting_password)
            <div class="detail-item">
                <span class="detail-label">Meeting Password:</span> {{ $onlineClass->meeting_password }}
            </div>
            @endif
            
            @if($onlineClass->instructions)
            <div class="detail-item">
                <span class="detail-label">Instructions:</span><br>
                {{ $onlineClass->instructions }}
            </div>
            @endif
        </div>
        
        <div style="text-align: center;">
            <a href="{{ $onlineClass->meeting_link }}" class="join-button">
                🎥 Join Class
            </a>
        </div>
        
        <p><strong>Meeting Link:</strong> <a href="{{ $onlineClass->meeting_link }}">{{ $onlineClass->meeting_link }}</a></p>
        
        <p>Please join the class on time. If you have any questions, contact your teacher.</p>
    </div>
    
    <div class="footer">
        <p>Best regards,<br>School Management System</p>
    </div>
</body>
</html>
