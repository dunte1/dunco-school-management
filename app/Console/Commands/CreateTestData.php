<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class CreateTestData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-data 
                            {--fresh : Drop all tables and recreate them}
                            {--seed : Run the test data seeder}
                            {--mpesa : Test M-Pesa integration}
                            {--all : Run all test data creation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create comprehensive test data for Dunco School Management System';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Dunco School Management System Test Data Creation...');
        $this->newLine();

        // Check if fresh option is selected
        if ($this->option('fresh') || $this->option('all')) {
            $this->info('🔄 Fresh migration selected - dropping and recreating all tables...');
            $this->call('migrate:fresh');
            $this->newLine();
        }

        // Run test data seeder
        if ($this->option('seed') || $this->option('all')) {
            $this->info('🌱 Running test data seeder...');
            $this->call('db:seed', ['--class' => 'TestDataSeeder']);
            $this->newLine();
        }

        // Test M-Pesa integration
        if ($this->option('mpesa') || $this->option('all')) {
            $this->info('📱 Testing M-Pesa integration...');
            $this->testMpesaIntegration();
            $this->newLine();
        }

        // Display summary
        $this->displaySummary();

        $this->info('✅ Test data creation completed successfully!');
    }

    /**
     * Test M-Pesa integration
     */
    protected function testMpesaIntegration()
    {
        try {
            // Check if M-Pesa configuration exists
            if (!config('mpesa.consumer_key')) {
                $this->warn('⚠️  M-Pesa configuration not found. Please set up your .env file with M-Pesa credentials.');
                $this->displayMpesaConfigInstructions();
                return;
            }

            // Test M-Pesa service
            $mpesaService = new \Modules\Finance\Services\MpesaService();
            
            // Test access token generation
            $this->info('Testing M-Pesa access token generation...');
            $accessToken = $mpesaService->getAccessToken();
            
            if ($accessToken) {
                $this->info('✅ M-Pesa access token generated successfully');
            } else {
                $this->error('❌ Failed to generate M-Pesa access token');
            }

            // Test phone number formatting
            $this->info('Testing phone number formatting...');
            $testPhones = ['0712345678', '254712345678', '712345678'];
            foreach ($testPhones as $phone) {
                $formatted = $this->formatPhoneNumber($phone);
                $this->line("  {$phone} → {$formatted}");
            }

        } catch (\Exception $e) {
            $this->error('❌ M-Pesa integration test failed: ' . $e->getMessage());
        }
    }

    /**
     * Format phone number for testing
     */
    protected function formatPhoneNumber($phoneNumber)
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        if (strlen($phoneNumber) == 9) {
            return '254' . $phoneNumber;
        } elseif (strlen($phoneNumber) == 10 && substr($phoneNumber, 0, 1) == '0') {
            return '254' . substr($phoneNumber, 1);
        }

        return $phoneNumber;
    }

    /**
     * Display M-Pesa configuration instructions
     */
    protected function displayMpesaConfigInstructions()
    {
        $this->newLine();
        $this->info('📋 M-Pesa Configuration Instructions:');
        $this->newLine();
        $this->line('Add the following to your .env file:');
        $this->newLine();
        $this->line('MPESA_ENVIRONMENT=sandbox');
        $this->line('MPESA_BASE_URL=https://sandbox.safaricom.co.ke');
        $this->line('MPESA_CONSUMER_KEY=your_consumer_key');
        $this->line('MPESA_CONSUMER_SECRET=your_consumer_secret');
        $this->line('MPESA_SHORT_CODE=your_short_code');
        $this->line('MPESA_PASSKEY=your_passkey');
        $this->line('MPESA_CALLBACK_URL=' . env('APP_URL') . '/api/mpesa/callback');
        $this->newLine();
    }

    /**
     * Display summary of created data
     */
    protected function displaySummary()
    {
        $this->newLine();
        $this->info('📊 Test Data Summary:');
        $this->newLine();

        try {
            $users = DB::table('users')->count();
            $students = DB::table('students')->count();
            $classes = DB::table('classes')->count();
            $subjects = DB::table('subjects')->count();
            $fees = DB::table('fees')->count();
            $invoices = DB::table('invoices')->count();
            $payments = DB::table('payments')->count();
            $receipts = DB::table('receipts')->count();
            $bankAccounts = DB::table('bank_accounts')->count();
            $notifications = DB::table('notifications')->count();

            $this->table(
                ['Data Type', 'Count'],
                [
                    ['Users', $users],
                    ['Students', $students],
                    ['Classes', $classes],
                    ['Subjects', $subjects],
                    ['Fees', $fees],
                    ['Invoices', $invoices],
                    ['Payments', $payments],
                    ['Receipts', $receipts],
                    ['Bank Accounts', $bankAccounts],
                    ['Notifications', $notifications],
                ]
            );

        } catch (\Exception $e) {
            $this->warn('Could not retrieve data summary: ' . $e->getMessage());
        }

        $this->newLine();
        $this->info('🔑 Test Login Credentials:');
        $this->newLine();
        $this->line('Admin: admin@duncowebsolutions.co.ke / password123');
        $this->line('Finance: finance@duncowebsolutions.co.ke / password123');
        $this->line('Parent: john.kamau@gmail.com / password123');
        $this->line('Teacher: mary.wanjiku@duncowebsolutions.co.ke / password123');
        $this->newLine();
        $this->info('🌐 Access the system at: ' . env('APP_URL'));
    }
}
