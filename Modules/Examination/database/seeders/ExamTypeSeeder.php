<?php

namespace Modules\Examination\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Examination\app\Models\ExamType;

class ExamTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Formative', 'description' => 'Formative Assessment', 'status' => 'active'],
            ['name' => 'Summative', 'description' => 'Summative Assessment', 'status' => 'active'],
            ['name' => 'CAT', 'description' => 'Continuous Assessment Test', 'status' => 'active'],
            ['name' => 'Midterm', 'description' => 'Midterm Exam', 'status' => 'active'],
            ['name' => 'Final', 'description' => 'Final Exam', 'status' => 'active'],
            ['name' => 'Makeup', 'description' => 'Makeup Exam', 'status' => 'active'],
        ];
        foreach ($types as $type) {
            ExamType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
} 