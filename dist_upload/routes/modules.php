<?php

use Illuminate\Support\Facades\Route;

// Load all module routes
$modulesPath = base_path('Modules');
$modules = array_diff(scandir($modulesPath), ['.', '..']);

foreach ($modules as $module) {
    $modulePath = $modulesPath . '/' . $module;
    if (is_dir($modulePath)) {
        $routesPath = $modulePath . '/routes/web.php';
        if (file_exists($routesPath)) {
            require $routesPath;
        }
    }
} 