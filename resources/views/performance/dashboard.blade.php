<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">🚀 Performance Dashboard</h1>
        
        <!-- Performance Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Memory Usage</h3>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['memory_usage']['current'] / 1024 / 1024, 2) }} MB</p>
                <p class="text-sm text-gray-500">Current Usage</p>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Peak Memory</h3>
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['memory_usage']['peak'] / 1024 / 1024, 2) }} MB</p>
                <p class="text-sm text-gray-500">Peak Usage</p>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Slow Queries</h3>
                <p class="text-2xl font-bold text-red-600">{{ $stats['slow_queries'] }}</p>
                <p class="text-sm text-gray-500">Detected</p>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">DB Connections</h3>
                <p class="text-2xl font-bold text-purple-600">{{ $stats['database_connections']['active_connections'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Active</p>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <button onclick="optimize()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded">
                🚀 Optimize Performance
            </button>
            
            <button onclick="clearCaches()" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded">
                🗑️ Clear All Caches
            </button>
            
            <button onclick="refreshStats()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded">
                🔄 Refresh Stats
            </button>
        </div>
        
        <!-- Status Messages -->
        <div id="status" class="mb-4"></div>
        
        <!-- Performance Tips -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">💡 Performance Tips</h3>
            <ul class="space-y-2 text-gray-600">
                <li>• Use eager loading to prevent N+1 queries</li>
                <li>• Cache frequently accessed data</li>
                <li>• Optimize database indexes</li>
                <li>• Use queue jobs for heavy operations</li>
                <li>• Enable route and config caching in production</li>
            </ul>
        </div>
    </div>

    <script>
        function optimize() {
            fetch('/performance/optimize', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                showStatus(data.message, data.success ? 'success' : 'error');
                if (data.success) {
                    setTimeout(refreshStats, 1000);
                }
            })
            .catch(error => {
                showStatus('Error: ' + error.message, 'error');
            });
        }

        function clearCaches() {
            fetch('/performance/clear-caches', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                showStatus(data.message, data.success ? 'success' : 'error');
            })
            .catch(error => {
                showStatus('Error: ' + error.message, 'error');
            });
        }

        function refreshStats() {
            fetch('/performance/stats')
            .then(response => response.json())
            .then(data => {
                // Update stats display
                location.reload();
            })
            .catch(error => {
                showStatus('Error refreshing stats: ' + error.message, 'error');
            });
        }

        function showStatus(message, type) {
            const statusDiv = document.getElementById('status');
            const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            
            statusDiv.innerHTML = `
                <div class="border rounded px-4 py-3 ${bgColor}">
                    ${message}
                </div>
            `;
            
            setTimeout(() => {
                statusDiv.innerHTML = '';
            }, 5000);
        }
    </script>
</body>
</html> 