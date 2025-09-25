<?php

namespace Modules\ChatBot\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ChatBot\Services\OpenAIService;
use Modules\ChatBot\Services\ChatBotService;
use Modules\ChatBot\Services\ConversationService;

class ChatBotServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'ChatBot';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'chatbot';

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
        $this->registerFactories();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->registerRoutes();
        $this->registerCommands();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        
        // Register services
        $this->app->singleton(GeminiService::class);
        $this->app->singleton(ChatBotService::class);
        $this->app->singleton(ConversationService::class);
        
        // Register config
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
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

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
        
        // Explicitly register the view namespace
        $this->loadViewsFrom($sourcePath, 'chatbot');
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
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Register an additional directory for factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        // Factories are not needed for this module
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
        $this->loadRoutesFrom(module_path($this->moduleName, 'Routes/web.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'Routes/api.php'));
    }

    /**
     * Register commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Modules\ChatBot\Console\Commands\TrainChatBot::class,
                \Modules\ChatBot\Console\Commands\TestChatBot::class,
            ]);
        }
    }

    /**
     * Get publishable view paths.
     *
     * @return array
     */
    private function getPublishableViewPaths(): array
    {
        $paths = [];
        $viewPaths = config('view.paths', [resource_path('views')]);
        foreach ($viewPaths as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
} 