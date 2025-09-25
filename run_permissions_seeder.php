<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Run the SystemPermissionsSeeder
$seeder = new Database\Seeders\SystemPermissionsSeeder();
$seeder->run();

echo "SystemPermissionsSeeder completed successfully!\n";
