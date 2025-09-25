<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Run migrations
$migrator = $app->make('Illuminate\Database\Migrations\Migrator');
$migrator->run();

echo "Migrations completed successfully!\n";
