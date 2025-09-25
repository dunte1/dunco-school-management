<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

try {
    $app = Application::configure(basePath: dirname(__DIR__))
        ->withRouting(
            web: __DIR__.'/routes/web.php',
            commands: __DIR__.'/routes/console.php',
            health: '/up',
        )
        ->withMiddleware(function (Middleware $middleware): void {
            // Minimal middleware configuration
        })
        ->withExceptions(function (Exceptions $exceptions): void {
            //
        })
        ->withProviders([
            // Only essential providers
            Illuminate\Foundation\Providers\FoundationServiceProvider::class,
            Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
            Illuminate\Filesystem\FilesystemServiceProvider::class,
            Illuminate\View\ViewServiceProvider::class,
        ])
        ->create();

    echo "Laravel application created successfully!\n";
    echo "App name: " . $app->name() . "\n";
    echo "App version: " . $app->version() . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}










