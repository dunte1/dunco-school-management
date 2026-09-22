<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ModuleViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register view hint paths for modules
        $this->registerModuleViewHints();
    }

    /**
     * Register view hint paths for all modules
     */
    private function registerModuleViewHints(): void
    {
        $modules = [
            'core' => base_path('Modules/Core/resources/views'),
            'academic' => base_path('Modules/Academic/resources/views'),
            'attendance' => base_path('Modules/Attendance/resources/views'),
            'chatbot' => base_path('Modules/ChatBot/resources/views'),
            'communication' => base_path('Modules/Communication/resources/views'),
            'document' => base_path('Modules/Document/resources/views'),
            'examination' => base_path('Modules/Examination/resources/views'),
            'finance' => base_path('Modules/Finance/resources/views'),
            'hostel' => base_path('Modules/Hostel/resources/views'),
            'hr' => base_path('Modules/HR/resources/views'),
            'library' => base_path('Modules/Library/resources/views'),
            'notification' => base_path('Modules/Notification/resources/views'),
            'portal' => base_path('Modules/Portal/resources/views'),
            'settings' => base_path('Modules/Settings/resources/views'),
            'timetable' => base_path('Modules/Timetable/resources/views'),
            'transport' => base_path('Modules/Transport/resources/views'),
            'nursing' => base_path('Modules/Nursing/resources/views'),
        ];

        foreach ($modules as $hint => $path) {
            if (is_dir($path)) {
                View::addNamespace($hint, $path);
            }
        }
    }
} 