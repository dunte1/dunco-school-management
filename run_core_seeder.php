<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Run the CoreSeeder
$seeder = new Database\Seeders\CoreSeeder();
$seeder->run();

echo "CoreSeeder completed successfully!\n";
