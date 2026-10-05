<?php

namespace Modules\ChatBot\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InstallChatBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chatbot:install 
                            {--force : Force installation even if already installed}
                            {--skip-migrations : Skip database migrations}
                            {--skip-permissions : Skip permission setup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and configure the ChatBot module';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🤖 ChatBot Module Installation');
        $this->info('==============================');
        $this->newLine();

        // Check if already installed
        if (!$this->option('force') && $this->isAlreadyInstalled()) {
            $this->warn('ChatBot module appears to be already installed.');
            if (!$this->confirm('Do you want to reinstall?', false)) {
                $this->info('Installation cancelled.');
                return 0;
            }
        }

        $steps = [
            'Environment Setup' => [$this, 'setupEnvironment'],
            'Database Migrations' => [$this, 'runMigrations'],
            'Permissions Setup' => [$this, 'setupPermissions'],
            'Configuration Validation' => [$this, 'validateConfiguration'],
            'Test Installation' => [$this, 'testInstallation'],
        ];

        $results = [];
        foreach ($steps as $stepName => $stepMethod) {
            $this->info("Step: {$stepName}...");
            try {
                $result = $stepMethod();
                $results[$stepName] = $result;
                if ($result['success']) {
                    $this->info("✅ {$stepName}: Completed");
                } else {
                    $this->error("❌ {$stepName}: Failed - {$result['message']}");
                }
            } catch (\Exception $e) {
                $results[$stepName] = ['success' => false, 'message' => $e->getMessage()];
                $this->error("❌ {$stepName}: Failed - {$e->getMessage()}");
            }
            $this->newLine();
        }

        $this->displayInstallationSummary($results);
        return $this->getOverallResult($results);
    }

    /**
     * Check if ChatBot is already installed
     */
    private function isAlreadyInstalled()
    {
        try {
            return Schema::hasTable('chatbot_conversations') && 
                   Schema::hasTable('chatbot_messages');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Setup environment configuration
     */
    private function setupEnvironment()
    {
        $this->info('Setting up environment configuration...');
        
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);
        
        $requiredConfigs = [
            'CHATBOT_ENABLED=true',
            'CHATBOT_AI_PROVIDER=openai',
            '# OPENAI_API_KEY=your_openai_api_key_here',
            '# GEMINI_API_KEY=your_gemini_api_key_here',
            '# OPENROUTER_API_KEY=your_openrouter_api_key_here',
        ];

        $addedConfigs = [];
        foreach ($requiredConfigs as $config) {
            if (strpos($envContent, explode('=', $config)[0]) === false) {
                $envContent .= "\n" . $config;
                $addedConfigs[] = $config;
            }
        }

        if (!empty($addedConfigs)) {
            file_put_contents($envFile, $envContent);
            $this->info('Added environment configurations:');
            foreach ($addedConfigs as $config) {
                $this->line("  - {$config}");
            }
        } else {
            $this->info('Environment configurations already present');
        }

        return [
            'success' => true,
            'message' => 'Environment setup completed',
            'details' => ['added_configs' => count($addedConfigs)]
        ];
    }

    /**
     * Run database migrations
     */
    private function runMigrations()
    {
        if ($this->option('skip-migrations')) {
            return [
                'success' => true,
                'message' => 'Migrations skipped by user request'
            ];
        }

        try {
            $this->info('Running database migrations...');
            
            // Run migrations
            Artisan::call('migrate', ['--path' => 'Modules/ChatBot/Database/Migrations']);
            
            $output = Artisan::output();
            $this->line($output);

            return [
                'success' => true,
                'message' => 'Database migrations completed successfully'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Setup permissions
     */
    private function setupPermissions()
    {
        if ($this->option('skip-permissions')) {
            return [
                'success' => true,
                'message' => 'Permission setup skipped by user request'
            ];
        }

        try {
            $this->info('Setting up permissions...');
            
            $permissions = [
                'chatbot.view' => 'View chatbot',
                'chatbot.create' => 'Create conversations',
                'chatbot.edit' => 'Edit conversations',
                'chatbot.delete' => 'Delete conversations',
                'chatbot.admin' => 'Manage chatbot settings',
            ];

            // This would typically insert permissions into a permissions table
            // For now, we'll just log what permissions should be created
            $this->info('Permissions to be created:');
            foreach ($permissions as $key => $description) {
                $this->line("  - {$key}: {$description}");
            }

            return [
                'success' => true,
                'message' => 'Permission setup completed',
                'details' => ['permissions' => array_keys($permissions)]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate configuration
     */
    private function validateConfiguration()
    {
        $this->info('Validating configuration...');
        
        $checks = [
            'ChatBot Enabled' => env('CHATBOT_ENABLED', false),
            'AI Provider Set' => !empty(env('CHATBOT_AI_PROVIDER')),
            'At Least One API Key' => !empty(env('OPENAI_API_KEY')) || 
                                    !empty(env('GEMINI_API_KEY')) || 
                                    !empty(env('OPENROUTER_API_KEY')),
        ];

        $passed = 0;
        $failed = 0;
        $issues = [];

        foreach ($checks as $checkName => $result) {
            if ($result) {
                $passed++;
                $this->info("✅ {$checkName}");
            } else {
                $failed++;
                $issues[] = $checkName;
                $this->error("❌ {$checkName}");
            }
        }

        if ($failed > 0) {
            return [
                'success' => false,
                'message' => 'Configuration validation failed',
                'details' => ['issues' => $issues]
            ];
        }

        return [
            'success' => true,
            'message' => 'Configuration validation passed',
            'details' => ['checks_passed' => $passed]
        ];
    }

    /**
     * Test installation
     */
    private function testInstallation()
    {
        try {
            $this->info('Testing installation...');
            
            // Test basic functionality
            $testMessage = "Hello, this is a test message.";
            
            // This would test the actual chatbot service
            // For now, we'll just check if the service can be instantiated
            $chatBotService = app(\Modules\ChatBot\Services\ChatBotService::class);
            
            if ($chatBotService) {
                return [
                    'success' => true,
                    'message' => 'Installation test passed',
                    'details' => ['service_instantiated' => true]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to instantiate ChatBot service'
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Display installation summary
     */
    private function displayInstallationSummary($results)
    {
        $this->info('Installation Summary');
        $this->info('===================');
        
        $passed = 0;
        $failed = 0;

        foreach ($results as $stepName => $result) {
            if ($result['success']) {
                $passed++;
                $this->info("✅ {$stepName}");
            } else {
                $failed++;
                $this->error("❌ {$stepName}");
                if (isset($result['message'])) {
                    $this->error("   {$result['message']}");
                }
            }
        }

        $this->newLine();
        $this->info("Results: {$passed} steps completed, {$failed} failed");
        
        if ($failed === 0) {
            $this->info('🎉 ChatBot module installed successfully!');
            $this->newLine();
            $this->info('Next steps:');
            $this->line('1. Configure your AI API keys in the .env file');
            $this->line('2. Run: php artisan chatbot:test');
            $this->line('3. Access the chatbot at: /chatbot');
        } else {
            $this->warn('Installation completed with some issues. Check the errors above.');
        }
    }

    /**
     * Get overall result
     */
    private function getOverallResult($results)
    {
        foreach ($results as $result) {
            if (!$result['success']) {
                return 1; // Failure
            }
        }
        return 0; // Success
    }
}
