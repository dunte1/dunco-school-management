<?php
require '/home/duncoweb/multischool.duncowebsolutions.co.ke/vendor/autoload.php';
$app = require '/home/duncoweb/multischool.duncowebsolutions.co.ke/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$modules = App\Models\PublicModule::all();
foreach ($modules as $m) {
    echo $m->slug . ': ' . substr($m->icon, 0, 120) . PHP_EOL;
}
