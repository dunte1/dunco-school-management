<?php

if (!function_exists('module_path')) {
    /**
     * Get the path to a module directory
     */
    function module_path($module, $path = '')
    {
        $modulePath = base_path('Modules/' . $module);
        
        if ($path) {
            return $modulePath . '/' . $path;
        }
        
        return $modulePath;
    }
}
