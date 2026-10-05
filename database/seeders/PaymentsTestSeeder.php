<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\StudentPayment;

class PaymentsTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Dunco school
        $school = School::where('code', 'DIS001')->first();
        if (!$school) {
            $this->command->error('Dunco school not found. Please run FinalDocumentTestSeeder first.');
            return;
        }

        // Get test students
        $students = Student::where('school_id', $school->id)
            ->where('admission_number', 'LIKE', 'STU2024%')
            ->get();

        if ($students->isEmpty()) {
            $this->command->error('No test students found. Please run FinalDocumentTestSeeder first.');
            return;
        }

        // Payment types
        $paymentTypes = [
            'Tuition Fee',
            'Library Fee',
            'Laboratory Fee',
            'Sports Fee',
            'Transport Fee'
        ];

        // Get existing fee IDs
        $feeIds = \Modules\Academic\Models\StudentFee::pluck('id')->toArray();
        if (empty($feeIds)) {
            $this->command->error('No student fees found. Please create fees first.');
            return;
        }

        // Create payments for each student
        foreach ($students as $student) {
            foreach ($paymentTypes as $index => $paymentType) {
                $amount = rand(5000, 25000); // Random amount between 5000-25000
                $reference = 'PAY' . date('Y') . str_pad($student->id, 3, '0', STR_PAD_LEFT) . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                
                // Use a random existing fee ID
                $feeId = $feeIds[array_rand($feeIds)];
                
                StudentPayment::firstOrCreate(
                    ['reference' => $reference],
                    [
                        'student_id' => $student->id,
                        'fee_id' => $feeId,
                        'amount' => $amount,
                        'payment_date' => now()->subDays(rand(1, 30))->format('Y-m-d'),
                        'method' => 'Bank Transfer',
                        'reference' => $reference,
                        'note' => $paymentType . ' payment for ' . $student->name
                    ]
                );
            }
        }

        $this->command->info('✅ Payment test data created successfully!');
        $this->command->info('');
        $this->command->info('📊 Created:');
        $this->command->info('   • ' . ($students->count() * count($paymentTypes)) . ' payments');
        $this->command->info('   • ' . count($paymentTypes) . ' payment types');
        $this->command->info('');
        $this->command->info('🎯 You can now test:');
        $this->command->info('   • Fee receipt generation');
        $this->command->info('   • Fee receipt template preview');
        $this->command->info('   • Finance documents section');
    }
}
