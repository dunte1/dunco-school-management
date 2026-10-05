<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Real-time Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/realtime-notifications.css') }}">
    <style>
        .test-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .status-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .test-button {
            margin: 10px;
            min-width: 150px;
        }
    </style>
</head>
<body>
    <div class="container test-container">
        <h1 class="mb-4">Real-time Notification Test</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Connection Status</h5>
                    </div>
                    <div class="card-body">
                        <div id="connection-status" class="badge bg-secondary">Disconnected</div>
                        <p class="mt-2">WebSocket connection status will be shown here.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test Notifications</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary test-button" onclick="sendTestNotification('info')">
                            <i class="fas fa-info-circle"></i> Info Notification
                        </button>
                        <button class="btn btn-success test-button" onclick="sendTestNotification('success')">
                            <i class="fas fa-check-circle"></i> Success Notification
                        </button>
                        <button class="btn btn-warning test-button" onclick="sendTestNotification('warning')">
                            <i class="fas fa-exclamation-triangle"></i> Warning Notification
                        </button>
                        <button class="btn btn-danger test-button" onclick="sendTestNotification('urgent')">
                            <i class="fas fa-exclamation-circle"></i> Urgent Notification
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Communication Test</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-info test-button" onclick="sendCommunicationTest('message')">
                            <i class="fas fa-envelope"></i> Test Message
                        </button>
                        <button class="btn btn-purple test-button" onclick="sendCommunicationTest('broadcast')">
                            <i class="fas fa-bullhorn"></i> Test Broadcast
                        </button>
                        <button class="btn btn-orange test-button" onclick="sendCommunicationTest('announcement')">
                            <i class="fas fa-megaphone"></i> Test Announcement
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Notification Log</h5>
                    </div>
                    <div class="card-body">
                        <div id="notification-log" style="max-height: 300px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 5px;">
                            <p class="text-muted">Notifications will appear here...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status indicator -->
    <div class="status-indicator">
        <div id="connection-status" class="badge bg-secondary">Disconnected</div>
    </div>

    <!-- Scripts -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="{{ asset('js/realtime-notifications.js') }}"></script>
    <script>
        // Pass user ID to JavaScript
        window.LARAVEL_USER_ID = {{ auth()->id() }};
        window.PUSHER_APP_KEY = '{{ env('PUSHER_APP_KEY') }}';
        window.PUSHER_APP_CLUSTER = '{{ env('PUSHER_APP_CLUSTER', 'ap2') }}';
        
        // Override the updateConnectionStatus method to update our test UI
        if (window.realTimeNotifications) {
            const originalUpdateStatus = window.realTimeNotifications.updateConnectionStatus;
            window.realTimeNotifications.updateConnectionStatus = function(connected) {
                originalUpdateStatus.call(this, connected);
                
                const statusElement = document.getElementById('connection-status');
                if (statusElement) {
                    statusElement.className = connected ? 'badge bg-success' : 'badge bg-danger';
                    statusElement.textContent = connected ? 'Connected' : 'Disconnected';
                }
            };
        }
        
        // Override the handleNotification method to log notifications
        if (window.realTimeNotifications) {
            const originalHandleNotification = window.realTimeNotifications.handleNotification;
            window.realTimeNotifications.handleNotification = function(data) {
                originalHandleNotification.call(this, data);
                logNotification('General', data);
            };
        }
        
        // Override the handleCommunicationNotification method to log notifications
        if (window.realTimeNotifications) {
            const originalHandleComm = window.realTimeNotifications.handleCommunicationNotification;
            window.realTimeNotifications.handleCommunicationNotification = function(data, type) {
                originalHandleComm.call(this, data, type);
                logNotification('Communication (' + type + ')', data);
            };
        }
        
        function logNotification(type, data) {
            const log = document.getElementById('notification-log');
            const timestamp = new Date().toLocaleTimeString();
            const logEntry = document.createElement('div');
            logEntry.className = 'mb-2 p-2 border rounded';
            logEntry.innerHTML = `
                <strong>${type}</strong> - ${timestamp}<br>
                <small>Title: ${data.title || 'N/A'}</small><br>
                <small>Message: ${data.message || data.body || 'N/A'}</small>
            `;
            
            if (log.children.length === 1 && log.children[0].classList.contains('text-muted')) {
                log.innerHTML = '';
            }
            
            log.insertBefore(logEntry, log.firstChild);
        }
        
        function sendTestNotification(type) {
            fetch('/api/test-notification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: type,
                    title: `${type.charAt(0).toUpperCase() + type.slice(1)} Test`,
                    message: `This is a test ${type} notification sent at ${new Date().toLocaleTimeString()}`
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Test notification sent:', data);
            })
            .catch(error => {
                console.error('Error sending test notification:', error);
            });
        }
        
        function sendCommunicationTest(type) {
            fetch('/api/test-communication', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: type,
                    title: `Test ${type.charAt(0).toUpperCase() + type.slice(1)}`,
                    subject: `Test ${type} subject`,
                    body: `This is a test ${type} sent at ${new Date().toLocaleTimeString()}`
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Test communication sent:', data);
            })
            .catch(error => {
                console.error('Error sending test communication:', error);
            });
        }
    </script>
</body>
</html>
