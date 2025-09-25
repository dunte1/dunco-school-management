<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\DashboardController;

try {
    echo "Testing Dashboard Controller...\n";
    
    $controller = new DashboardController();
    
    // Test if the controller can be instantiated
    echo "✅ DashboardController instantiated successfully\n";
    
    // Test if we can access the index method
    if (method_exists($controller, 'index')) {
        echo "✅ DashboardController::index method exists\n";
    } else {
        echo "❌ DashboardController::index method does not exist\n";
    }
    
    echo "Dashboard test completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "🔍 Stack trace:\n" . $e->getTraceAsString() . "\n";
}

