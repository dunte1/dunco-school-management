<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';

// Run the seeder
$seeder = new Database\Seeders\DatabaseSeeder();
$seeder->run();

echo "Database seeded successfully!\n";
