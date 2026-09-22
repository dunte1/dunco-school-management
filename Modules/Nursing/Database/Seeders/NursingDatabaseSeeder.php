<?php

namespace Modules\Nursing\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Nursing\Models\SkillCategory;
use Modules\Nursing\Models\Skill;
use Modules\Nursing\Models\ReferenceCategory;

class NursingDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSkillCategories();
        $this->seedSkills();
        $this->seedReferenceCategories();
    }

    private function seedSkillCategories(): void
    {
        $categories = [
            ['name' => 'Basic Nursing', 'code' => 'BN', 'sort_order' => 1],
            ['name' => 'Infection Prevention', 'code' => 'IPC', 'sort_order' => 2],
            ['name' => 'Vital Signs', 'code' => 'VS', 'sort_order' => 3],
            ['name' => 'Patient Assessment', 'code' => 'PA', 'sort_order' => 4],
            ['name' => 'Medication Administration', 'code' => 'MA', 'sort_order' => 5],
            ['name' => 'Wound Care', 'code' => 'WC', 'sort_order' => 6],
            ['name' => 'Maternal Health', 'code' => 'MH', 'sort_order' => 7],
            ['name' => 'Child Health', 'code' => 'CH', 'sort_order' => 8],
            ['name' => 'Community Health', 'code' => 'COH', 'sort_order' => 9],
            ['name' => 'Emergency Nursing', 'code' => 'EN', 'sort_order' => 10],
            ['name' => 'Medical-Surgical Nursing', 'code' => 'MSN', 'sort_order' => 11],
            ['name' => 'Theatre/Perioperative', 'code' => 'TP', 'sort_order' => 12],
            ['name' => 'Mental Health', 'code' => 'MH2', 'sort_order' => 13],
            ['name' => 'Geriatric Nursing', 'code' => 'GN', 'sort_order' => 14],
            ['name' => 'Professional Practice', 'code' => 'PP', 'sort_order' => 15],
        ];

        foreach ($categories as $category) {
            SkillCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }

    private function seedSkills(): void
    {
        $skills = [
            ['category' => 'Basic Nursing', 'name' => 'Hand Hygiene (5 Moments)', 'description' => 'Perform hand hygiene using the WHO 5 Moments framework.'],
            ['category' => 'Basic Nursing', 'name' => 'Vital Signs Measurement', 'description' => 'Measure and record temperature, pulse, respiration, blood pressure, and SpO2.'],
            ['category' => 'Infection Prevention', 'name' => 'Donning and Doffing PPE', 'description' => 'Correctly put on and remove personal protective equipment.'],
            ['category' => 'Vital Signs', 'name' => 'Blood Pressure Measurement', 'description' => 'Perform manual blood pressure measurement using a sphygmomanometer.'],
            ['category' => 'Medication Administration', 'name' => 'Oral Medication Administration', 'description' => 'Administer oral medications following the 10 rights.'],
            ['category' => 'Medication Administration', 'name' => 'Injection Techniques', 'description' => 'Perform intramuscular, subcutaneous, and intradermal injections.'],
            ['category' => 'Wound Care', 'name' => 'Wound Assessment and Dressing', 'description' => 'Assess wound characteristics and apply appropriate dressings.'],
            ['category' => 'Patient Assessment', 'name' => 'Head-to-Toe Assessment', 'description' => 'Perform a comprehensive head-to-toe patient assessment.'],
            ['category' => 'Emergency Nursing', 'name' => 'CPR (Basic Life Support)', 'description' => 'Perform cardiopulmonary resuscitation according to current guidelines.'],
            ['category' => 'Community Health', 'name' => 'Health Education', 'description' => 'Provide health education to individuals and communities.'],
        ];

        foreach ($skills as $skill) {
            $category = SkillCategory::where('name', $skill['category'])->first();
            if ($category) {
                Skill::updateOrCreate(
                    ['name' => $skill['name']],
                    [
                        'category_id' => $category->id,
                        'description' => $skill['description'],
                        'status' => 'published',
                        'is_active' => true,
                    ]
                );
            }
        }
    }

    private function seedReferenceCategories(): void
    {
        $categories = [
            ['name' => 'Anatomy & Physiology', 'slug' => 'anatomy-physiology', 'sort_order' => 1],
            ['name' => 'Nursing Procedures', 'slug' => 'nursing-procedures', 'sort_order' => 2],
            ['name' => 'Infection Prevention & Control', 'slug' => 'infection-prevention', 'sort_order' => 3],
            ['name' => 'Maternal Health', 'slug' => 'maternal-health', 'sort_order' => 4],
            ['name' => 'Child Health', 'slug' => 'child-health', 'sort_order' => 5],
            ['name' => 'Community Health', 'slug' => 'community-health', 'sort_order' => 6],
            ['name' => 'Medical-Surgical Nursing', 'slug' => 'medical-surgical', 'sort_order' => 7],
            ['name' => 'Mental Health', 'slug' => 'mental-health', 'sort_order' => 8],
            ['name' => 'Emergency Nursing', 'slug' => 'emergency-nursing', 'sort_order' => 9],
            ['name' => 'Nutrition', 'slug' => 'nutrition', 'sort_order' => 10],
            ['name' => 'Professional Practice', 'slug' => 'professional-practice', 'sort_order' => 11],
        ];

        foreach ($categories as $category) {
            ReferenceCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
