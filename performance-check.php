<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Performance Check Report\n";
echo "==========================\n\n";

// Memory Usage
$memoryUsage = memory_get_usage(true);
$peakMemory = memory_get_peak_usage(true);
$memoryLimit = ini_get('memory_limit');

echo "📊 Memory Usage:\n";
echo "   Current: " . number_format($memoryUsage / 1024 / 1024, 2) . " MB\n";
echo "   Peak: " . number_format($peakMemory / 1024 / 1024, 2) . " MB\n";
echo "   Limit: " . $memoryLimit . "\n\n";

// Cache Status
echo "💾 Cache Status:\n";
try {
    Cache::put('test_key', 'test_value', 60);
    $cacheWorks = Cache::get('test_key') === 'test_value';
    echo "   Status: " . ($cacheWorks ? "✅ Working" : "❌ Not Working") . "\n";
    echo "   Driver: " . config('cache.default') . "\n";
} catch (Exception $e) {
    echo "   Status: ❌ Error - " . $e->getMessage() . "\n";
}
echo "\n";

// Database Status
echo "🗄️ Database Status:\n";
try {
    $connection = config('database.default');
    echo "   Connection: " . $connection . "\n";
    
    if ($connection === 'sqlite') {
        $dbPath = config('database.connections.sqlite.database');
        echo "   Database File: " . $dbPath . "\n";
        echo "   File Size: " . (file_exists($dbPath) ? number_format(filesize($dbPath) / 1024 / 1024, 2) . " MB" : "Not found") . "\n";
    }
    
    // Test query
    $startTime = microtime(true);
    DB::select('SELECT 1');
    $queryTime = (microtime(true) - $startTime) * 1000;
    echo "   Query Test: " . round($queryTime, 2) . "ms\n";
    
} catch (Exception $e) {
    echo "   Status: ❌ Error - " . $e->getMessage() . "\n";
}
echo "\n";

// Configuration Status
echo "⚙️ Configuration Status:\n";
$configCached = file_exists('bootstrap/cache/config.php');
$routesCached = file_exists('bootstrap/cache/routes-v7.php');
$viewsCached = is_dir('storage/framework/cache/views');

echo "   Config Cached: " . ($configCached ? "✅ Yes" : "❌ No") . "\n";
echo "   Routes Cached: " . ($routesCached ? "✅ Yes" : "❌ No") . "\n";
echo "   Views Cached: " . ($viewsCached ? "✅ Yes" : "❌ No") . "\n\n";

// Performance Recommendations
echo "💡 Performance Recommendations:\n";
if (!$configCached) {
    echo "   • Run: php artisan config:cache\n";
}
if (!$routesCached) {
    echo "   • Run: php artisan route:cache\n";
}
if (!$viewsCached) {
    echo "   • Run: php artisan view:cache\n";
}
if ($memoryUsage > 50 * 1024 * 1024) { // 50MB
    echo "   • High memory usage detected\n";
}
if ($queryTime > 100) { // 100ms
    echo "   • Slow database queries detected\n";
}

echo "\n✅ Performance check completed!\n"; 