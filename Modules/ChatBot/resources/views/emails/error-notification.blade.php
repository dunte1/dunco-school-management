<!DOCTYPE html>
<html>
<head>
    <title>Critical ChatBot Error</title>
</head>
<body>
    <h2>Critical ChatBot Error</h2>
    
    <p>A critical error has occurred in the ChatBot module:</p>
    
    <h3>Error Details</h3>
    <ul>
        <li><strong>Message:</strong> {{ $errorData['message'] ?? 'N/A' }}</li>
        <li><strong>Code:</strong> {{ $errorData['code'] ?? 'N/A' }}</li>
        <li><strong>File:</strong> {{ $errorData['file'] ?? 'N/A' }}</li>
        <li><strong>Line:</strong> {{ $errorData['line'] ?? 'N/A' }}</li>
        <li><strong>Timestamp:</strong> {{ $errorData['timestamp'] ?? 'N/A' }}</li>
        <li><strong>URL:</strong> {{ $errorData['url'] ?? 'N/A' }}</li>
        <li><strong>User ID:</strong> {{ $errorData['user_id'] ?? 'N/A' }}</li>
    </ul>
    
    <h3>Context</h3>
    <p>{{ $errorData['context'] ?? 'N/A' }}</p>
    
    <h3>Trace</h3>
    <pre>{{ $errorData['trace'] ?? 'N/A' }}</pre>
    
    @if(isset($errorData['additional_data']) && !empty($errorData['additional_data']))
    <h3>Additional Data</h3>
    <pre>{{ json_encode($errorData['additional_data'], JSON_PRETTY_PRINT) }}</pre>
    @endif
    
    <hr>
    <p><small>This is an automated error notification from {{ config('app.name') }}.</small></p>
</body>
</html>