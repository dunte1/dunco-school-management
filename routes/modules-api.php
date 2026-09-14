<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Module API Routes (opt-in)
|--------------------------------------------------------------------------
| Module API route files are only loaded when MODULES_LOAD_API_ROUTES=true.
| They remain disabled by default until the module API controllers are
| implemented (see gaps.md Phase 6), because many currently reference
| controllers/methods that do not exist yet.
|
| When enabled, this file is loaded inside the `api` prefix + middleware
| group from App\Providers\RouteServiceProvider, so module route files
| should declare only their own version prefix (e.g. `v1`).
*/

if (!filter_var(env('MODULES_LOAD_API_ROUTES', false), FILTER_VALIDATE_BOOLEAN)) {
    return;
}

$modulesPath = base_path('Modules');

if (is_dir($modulesPath)) {
    foreach (array_diff(scandir($modulesPath), ['.', '..']) as $module) {
        $modulePath = $modulesPath . DIRECTORY_SEPARATOR . $module;
        if (!is_dir($modulePath)) {
            continue;
        }

        $routesDir = null;
        foreach (['routes', 'Routes'] as $dir) {
            $candidate = $modulePath . DIRECTORY_SEPARATOR . $dir;
            if (is_dir($candidate)) {
                $routesDir = $candidate;
                break;
            }
        }

        if ($routesDir !== null && file_exists($routesDir . DIRECTORY_SEPARATOR . 'api.php')) {
            require $routesDir . DIRECTORY_SEPARATOR . 'api.php';
        }
    }
}
