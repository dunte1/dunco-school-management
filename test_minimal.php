<?php

require_once 'vendor/autoload.php';

// Test minimal Laravel bootstrap
try {
    $app = require_once 'bootstrap/app.php';
    echo "Laravel application created successfully!\n";
    
    // Test if we can access the environment
    $env = $app->environment();
    echo "Environment: " . $env . "\n";
    
    // Test if we can access the base path
    $basePath = $app->basePath();
    echo "Base path: " . $basePath . "\n";
    
    echo "Minimal Laravel bootstrap works!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
} 