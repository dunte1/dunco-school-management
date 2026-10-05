<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject ?? 'ChatBot Notification' }}</title>
</head>
<body>
    <h2>ChatBot Notification</h2>
    
    <p>{{ $messageContent }}</p>
    
    @if(isset($data) && !empty($data))
        <h3>Additional Information:</h3>
        <ul>
            @foreach($data as $key => $value)
                <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ is_array($value) ? json_encode($value) : $value }}</li>
            @endforeach
        </ul>
    @endif
    
    <p>
        <a href="{{ url('/chatbot/admin') }}">View ChatBot Dashboard</a>
    </p>
    
    <hr>
    
    <p>
        This is an automated message from the ChatBot system.
    </p>
</body>
</html>