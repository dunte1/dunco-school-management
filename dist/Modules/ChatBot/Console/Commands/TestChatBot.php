<?php

namespace Modules\ChatBot\Console\Commands;

use Illuminate\Console\Command;
use Modules\ChatBot\Services\ChatBotService;
use Modules\ChatBot\Services\OpenAIService;

class TestChatBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chatbot:test 
                            {--message= : Test message to send}
                            {--interactive : Run in interactive mode}
                            {--count=5 : Number of test messages to send}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the chatbot functionality';

    protected $chatBotService;
    protected $openAIService;

    public function __construct(ChatBotService $chatBotService, OpenAIService $openAIService)
    {
        parent::__construct();
        $this->chatBotService = $chatBotService;
        $this->openAIService = $openAIService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🤖 Starting ChatBot Test...');

        // Check if OpenAI is available
        if (!$this->openAIService->isAvailable()) {
            $this->error('❌ OpenAI API is not configured. Please set your OpenAI API key.');
            return 1;
        }

        // Test connection
        if (!$this->openAIService->testConnection()) {
            $this->error('❌ Cannot connect to OpenAI API. Please check your configuration.');
            return 1;
        }

        $this->info('✅ OpenAI connection successful');

        // Run tests based on options
        if ($this->option('interactive')) {
            return $this->runInteractiveTest();
        } else {
            return $this->runAutomatedTest();
        }
    }

    /**
     * Run interactive test mode
     */
    protected function runInteractiveTest()
    {
        $this->info('🎯 Interactive Test Mode');
        $this->info('Type "quit" or "exit" to end the test');
        $this->info('Type "help" for available commands');
        $this->line('');

        while (true) {
            $message = $this->ask('You: ');
            
            if (in_array(strtolower($message), ['quit', 'exit', 'q'])) {
                $this->info('👋 Goodbye!');
                break;
            }

            if (strtolower($message) === 'help') {
                $this->showHelp();
                continue;
            }

            if (empty($message)) {
                continue;
            }

            try {
                $response = $this->chatBotService->processMessage($message);
                $this->info('🤖 AI: ' . $response['ai_response']);
            } catch (\Exception $e) {
                $this->error('❌ Error: ' . $e->getMessage());
            }

            $this->line('');
        }

        return 0;
    }

    /**
     * Run automated test mode
     */
    protected function runAutomatedTest()
    {
        $message = $this->option('message');
        $count = (int) $this->option('count');

        if (!$message) {
            $message = 'Hello, how are you?';
        }

        $this->info("🧪 Running automated test with message: '{$message}'");
        $this->info("📊 Will send {$count} test messages");
        $this->line('');

        $successCount = 0;
        $errorCount = 0;
        $totalResponseTime = 0;

        $progressBar = $this->output->createProgressBar($count);
        $progressBar->start();

        for ($i = 1; $i <= $count; $i++) {
            try {
                $startTime = microtime(true);
                
                $response = $this->chatBotService->processMessage($message);
                
                $endTime = microtime(true);
                $responseTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
                $totalResponseTime += $responseTime;

                $successCount++;
                
                // Log the test result
                \Log::info("ChatBot test #{$i}", [
                    'message' => $message,
                    'response' => $response['ai_response'],
                    'response_time_ms' => round($responseTime, 2),
                    'conversation_id' => $response['conversation_id']
                ]);

            } catch (\Exception $e) {
                $errorCount++;
                \Log::error("ChatBot test #{$i} failed", [
                    'message' => $message,
                    'error' => $e->getMessage()
                ]);
            }

            $progressBar->advance();

            // Add small delay between requests
            usleep(500000); // 0.5 second
        }

        $progressBar->finish();
        $this->newLine(2);

        // Display results
        $this->displayTestResults($successCount, $errorCount, $totalResponseTime, $count);

        return $errorCount === 0 ? 0 : 1;
    }

    /**
     * Display test results
     */
    protected function displayTestResults($successCount, $errorCount, $totalResponseTime, $totalCount)
    {
        $this->info('📊 Test Results:');
        $this->line('   ✅ Successful: ' . $successCount);
        $this->line('   ❌ Errors: ' . $errorCount);
        $this->line('   📈 Success Rate: ' . round(($successCount / $totalCount) * 100, 2) . '%');
        
        if ($successCount > 0) {
            $avgResponseTime = $totalResponseTime / $successCount;
            $this->line('   ⏱️  Average Response Time: ' . round($avgResponseTime, 2) . 'ms');
        }

        $this->line('');

        if ($errorCount === 0) {
            $this->info('🎉 All tests passed successfully!');
        } else {
            $this->warn('⚠️  Some tests failed. Check the logs for details.');
        }
    }

    /**
     * Show help for interactive mode
     */
    protected function showHelp()
    {
        $this->line('');
        $this->info('📖 Available Commands:');
        $this->line('   quit, exit, q - Exit the test');
        $this->line('   help - Show this help message');
        $this->line('   stats - Show current statistics');
        $this->line('   clear - Clear the conversation');
        $this->line('');
    }

    /**
     * Get test messages for automated testing
     */
    protected function getTestMessages()
    {
        return [
            'Hello, how are you?',
            'What is the weather like today?',
            'Can you help me with my homework?',
            'Tell me a joke',
            'What time is it?',
            'How do I reset my password?',
            'What are the school rules?',
            'Can you explain algebra?',
            'What is the lunch menu today?',
            'How do I contact the teacher?'
        ];
    }
} 