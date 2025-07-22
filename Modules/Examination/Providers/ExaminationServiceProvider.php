<?php

namespace Modules\Examination\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

class ExaminationServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Examination';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'examination';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->registerRoutes();
        $this->registerBladeComponents();
        $this->registerMenuItems();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->registerCommands();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            Factory::load(module_path($this->moduleName, 'database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Register routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::middleware('web')
            ->group(module_path($this->moduleName, '/routes/web.php'));
    }

    /**
     * Register blade components.
     *
     * @return void
     */
    protected function registerBladeComponents()
    {
        // Register custom blade components for examination module
        Blade::component('examination::components.exam-card', 'exam-card');
        Blade::component('examination::components.question-display', 'question-display');
        Blade::component('examination::components.timer', 'exam-timer');
        Blade::component('examination::components.progress-bar', 'exam-progress');
    }

    /**
     * Register menu items.
     *
     * @return void
     */
    protected function registerMenuItems()
    {
        // Add examination menu items to the main navigation
        View::composer('*', function ($view) {
            $view->with('examinationMenu', [
                [
                    'name' => 'Dashboard',
                    'url' => route('examination.dashboard'),
                    'icon' => 'fas fa-tachometer-alt'
                ],
                [
                    'name' => 'Exams',
                    'url' => route('examination.exams.index'),
                    'icon' => 'fas fa-file-alt'
                ],
                [
                    'name' => 'Question Bank',
                    'url' => route('examination.questions.index'),
                    'icon' => 'fas fa-question-circle'
                ],
                [
                    'name' => 'Schedules',
                    'url' => route('examination.schedules.index'),
                    'icon' => 'fas fa-calendar-alt'
                ],
                [
                    'name' => 'Results',
                    'url' => route('examination.results.index'),
                    'icon' => 'fas fa-chart-bar'
                ],
                [
                    'name' => 'Proctoring',
                    'url' => route('examination.proctoring.index'),
                    'icon' => 'fas fa-video'
                ]
            ]);
        });
    }

    /**
     * Register commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        // Commands will be registered here when they are created
        // $this->commands([
        //     \Modules\Examination\Console\Commands\GenerateExamReport::class,
        //     \Modules\Examination\Console\Commands\CleanupExpiredExams::class,
        //     \Modules\Examination\Console\Commands\SendExamReminders::class,
        // ]);
    }

    /**
     * Get publishable view paths.
     *
     * @return array
     */
    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
} 