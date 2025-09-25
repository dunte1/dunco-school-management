<?php

require_once 'vendor/autoload.php';

// Test basic Laravel bootstrap
try {
    $app = require_once 'bootstrap/app.php';
    echo "Laravel application created successfully!\n";
    
    // Test if we can access the config
    $config = $app->get('config');
    echo "Config accessed successfully!\n";
    
    // Test if we can access the cache
    try {
        $cache = $app->get('cache');
        echo "Cache accessed successfully!\n";
    } catch (Exception $e) {
        echo "Cache error: " . $e->getMessage() . "\n";
    }
    
    echo "Basic Laravel bootstrap works!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
} 