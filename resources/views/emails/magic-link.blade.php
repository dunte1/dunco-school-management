<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login to DUNCO SMS</title>
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
            background: #1a237e;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            background: #1a237e;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
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
        <h1>DUNCO SMS</h1>
        <p>School Management System</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $user->name }}!</h2>
        
        <p>You requested a magic link to login to your DUNCO SMS account.</p>
        
        <p>Click the button below to login to your account:</p>
        
        <div style="text-align: center;">
            <a href="{{ $magicLink }}" class="button">Login to DUNCO SMS</a>
        </div>
        
        <p><strong>Important:</strong></p>
        <ul>
            <li>This link will expire in 15 minutes</li>
            <li>If you didn't request this link, please ignore this email</li>
            <li>For security, this link can only be used once</li>
        </ul>
        
        <p>If the button doesn't work, copy and paste this link into your browser:</p>
        <p style="word-break: break-all; color: #666;">{{ $magicLink }}</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from DUNCO School Management System.</p>
        <p>If you have any questions, please contact your system administrator.</p>
    </div>
</body>
</html> 