<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Run the TestDataSeeder
$seeder = new Database\Seeders\TestDataSeeder();
$seeder->run();

echo "TestDataSeeder completed successfully!\n";
