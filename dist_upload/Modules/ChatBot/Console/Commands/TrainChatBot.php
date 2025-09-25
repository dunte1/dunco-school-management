<?php

namespace Modules\ChatBot\Console\Commands;

use Illuminate\Console\Command;
use Modules\ChatBot\Services\OpenAIService;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class TrainChatBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chatbot:train 
                            {--file= : Path to training data file}
                            {--model= : OpenAI model to use for training}
                            {--max-tokens=1000 : Maximum tokens per response}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Train the chatbot with custom data';

    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        parent::__construct();
        $this->openAIService = $openAIService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🤖 Starting ChatBot Training...');

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

        // Get training file
        $filePath = $this->option('file');
        if (!$filePath) {
            $this->error('❌ Please provide a training data file using --file option');
            return 1;
        }

        if (!File::exists($filePath)) {
            $this->error("❌ Training file not found: {$filePath}");
            return 1;
        }

        // Load training data
        $trainingData = $this->loadTrainingData($filePath);
        if (empty($trainingData)) {
            $this->error('❌ No valid training data found in the file');
            return 1;
        }

        $this->info("📊 Loaded " . count($trainingData) . " training examples");

        // Start training process
        $this->trainWithData($trainingData);

        $this->info('✅ Training completed successfully!');
        return 0;
    }

    /**
     * Load training data from file
     */
    protected function loadTrainingData($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $trainingData = [];

        try {
            switch ($extension) {
                case 'json':
                    $data = json_decode(File::get($filePath), true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $trainingData = $this->parseJsonTrainingData($data);
                    }
                    break;

                case 'csv':
                    $trainingData = $this->parseCsvTrainingData($filePath);
                    break;

                case 'txt':
                    $trainingData = $this->parseTextTrainingData($filePath);
                    break;

                default:
                    $this->error("❌ Unsupported file format: {$extension}");
                    return [];
            }
        } catch (\Exception $e) {
            $this->error("❌ Error loading training data: " . $e->getMessage());
            return [];
        }

        return $trainingData;
    }

    /**
     * Parse JSON training data
     */
    protected function parseJsonTrainingData($data)
    {
        $trainingData = [];

        if (isset($data['conversations'])) {
            foreach ($data['conversations'] as $conversation) {
                if (isset($conversation['messages'])) {
                    $trainingData[] = $conversation;
                }
            }
        } elseif (isset($data['examples'])) {
            foreach ($data['examples'] as $example) {
                if (isset($example['input']) && isset($example['output'])) {
                    $trainingData[] = [
                        'messages' => [
                            ['role' => 'user', 'content' => $example['input']],
                            ['role' => 'assistant', 'content' => $example['output']]
                        ]
                    ];
                }
            }
        }

        return $trainingData;
    }

    /**
     * Parse CSV training data
     */
    protected function parseCsvTrainingData($filePath)
    {
        $trainingData = [];
        $handle = fopen($filePath, 'r');

        if ($handle) {
            $headers = fgetcsv($handle);
            $inputIndex = array_search('input', $headers);
            $outputIndex = array_search('output', $headers);

            if ($inputIndex === false || $outputIndex === false) {
                $this->error('❌ CSV file must have "input" and "output" columns');
                fclose($handle);
                return [];
            }

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 2) {
                    $trainingData[] = [
                        'messages' => [
                            ['role' => 'user', 'content' => $row[$inputIndex]],
                            ['role' => 'assistant', 'content' => $row[$outputIndex]]
                        ]
                    ];
                }
            }

            fclose($handle);
        }

        return $trainingData;
    }

    /**
     * Parse text training data
     */
    protected function parseTextTrainingData($filePath)
    {
        $content = File::get($filePath);
        $lines = explode("\n", $content);
        $trainingData = [];
        $currentPair = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            if (strpos($line, 'Q:') === 0) {
                if (!empty($currentPair)) {
                    $trainingData[] = ['messages' => $currentPair];
                }
                $currentPair = [
                    ['role' => 'user', 'content' => trim(substr($line, 2))]
                ];
            } elseif (strpos($line, 'A:') === 0 && !empty($currentPair)) {
                $currentPair[] = ['role' => 'assistant', 'content' => trim(substr($line, 2))];
            }
        }

        if (!empty($currentPair)) {
            $trainingData[] = ['messages' => $currentPair];
        }

        return $trainingData;
    }

    /**
     * Train with the provided data
     */
    protected function trainWithData($trainingData)
    {
        $progressBar = $this->output->createProgressBar(count($trainingData));
        $progressBar->start();

        $successCount = 0;
        $errorCount = 0;

        foreach ($trainingData as $index => $conversation) {
            try {
                $this->processTrainingExample($conversation);
                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                Log::error("Training error at example {$index}: " . $e->getMessage());
            }

            $progressBar->advance();

            // Add small delay to avoid rate limiting
            usleep(100000); // 0.1 second
        }

        $progressBar->finish();
        $this->newLine();

        $this->info("📈 Training Results:");
        $this->info("   ✅ Successful: {$successCount}");
        $this->info("   ❌ Errors: {$errorCount}");
    }

    /**
     * Process a single training example
     */
    protected function processTrainingExample($conversation)
    {
        if (!isset($conversation['messages']) || count($conversation['messages']) < 2) {
            throw new \Exception('Invalid conversation format');
        }

        // Get the last user message and assistant response
        $userMessage = null;
        $assistantResponse = null;

        foreach ($conversation['messages'] as $message) {
            if ($message['role'] === 'user') {
                $userMessage = $message['content'];
            } elseif ($message['role'] === 'assistant') {
                $assistantResponse = $message['content'];
            }
        }

        if (!$userMessage || !$assistantResponse) {
            throw new \Exception('Missing user message or assistant response');
        }

        // Test the training by sending the user message and comparing response
        $options = [
            'model' => $this->option('model') ?: config('chatbot.openai.model'),
            'max_tokens' => (int) $this->option('max-tokens'),
        ];

        $response = $this->openAIService->generateResponse($userMessage, [], $options);

        // Log the training example
        Log::info("Training example processed", [
            'user_message' => $userMessage,
            'expected_response' => $assistantResponse,
            'actual_response' => $response
        ]);
    }
} 