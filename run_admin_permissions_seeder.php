<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Run the AdminPermissionsSeeder
$seeder = new Database\Seeders\AdminPermissionsSeeder();
$seeder->run();

echo "AdminPermissionsSeeder completed successfully!\n";
