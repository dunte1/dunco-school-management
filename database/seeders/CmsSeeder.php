<?php

namespace Database\Seeders;

use App\Models\AdmissionCycle;
use App\Models\AdmissionDocumentType;
use App\Models\AdmissionField;
use App\Models\AdmissionSection;
use App\Models\Faq;
use App\Models\PricingFeature;
use App\Models\PricingPlan;
use App\Models\PublicSetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTestimonials();
        $this->seedFaqs();
        $this->seedPricingPlans();
        $this->seedPublicSettings();
        $this->seedAdmissionCycle();
        $this->seedAdmissionSectionsAndFields();
        $this->seedAdmissionDocumentTypes();
    }

    protected function seedTestimonials(): void
    {
        $testimonials = [
            [
                'name' => 'James Mwangi',
                'position' => 'Principal',
                'institution' => 'Greenfield Academy',
                'content' => 'Dunco SMS has completely transformed how we manage our school. From attendance tracking to fee collection, everything is streamlined and efficient. Our staff save hours every week.',
                'rating' => 5,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Sarah Achieng',
                'position' => 'Bursar',
                'institution' => 'Starlight International',
                'content' => 'The finance module is incredible. Fee tracking, payment reconciliation, and financial reporting have never been easier. I can generate reports in seconds that used to take days.',
                'rating' => 5,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Peter Kipchoge',
                'position' => 'Director',
                'institution' => 'Horizon Schools',
                'content' => 'Parents love the portal. They can track their children\'s progress, pay fees online, and communicate with teachers directly. It has improved parent engagement dramatically.',
                'rating' => 5,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $data) {
            Testimonial::updateOrCreate(
                ['name' => $data['name'], 'institution' => $data['institution']],
                $data
            );
        }
    }

    protected function seedFaqs(): void
    {
        $faqs = [
            [
                'question' => 'What is Dunco SMS?',
                'answer' => 'Dunco School Management System (Dunco SMS) is a comprehensive, all-in-one platform designed to manage every aspect of school administration including students, staff, academics, finances, attendance, examinations, and more.',
                'category' => 'general',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'question' => 'How many modules does it include?',
                'answer' => 'Dunco SMS includes 17 fully integrated modules: Academic, Examination, Finance, Attendance, Timetable, Library, Hostel, Transport, HR, Communication, Notifications, Portal, Documents, Settings, AI ChatBot, Core/Admin, and more. Each module works seamlessly with the others.',
                'category' => 'general',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'question' => 'Is it mobile-friendly?',
                'answer' => 'Yes. Dunco SMS is fully responsive and works on all devices including desktops, tablets, and smartphones. We also offer a dedicated mobile app for Android with push notifications and offline support.',
                'category' => 'general',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'question' => 'How long does setup take?',
                'answer' => 'Setup time depends on your school\'s size and complexity. Typically, a basic setup takes 1-2 weeks. Our onboarding team assists with data migration, configuration, and staff training to ensure a smooth transition.',
                'category' => 'general',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'question' => 'Is my data secure?',
                'answer' => 'Security is our top priority. Dunco SMS uses enterprise-grade encryption, role-based access control, comprehensive audit logging, and regular security audits. All data is backed up automatically and stored securely.',
                'category' => 'general',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'question' => 'Can I import existing data?',
                'answer' => 'Yes. Dunco SMS supports bulk import of student data, staff records, fee structures, and historical data via CSV/Excel files. Our onboarding team will help you migrate from your existing system.',
                'category' => 'general',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($faqs as $data) {
            Faq::updateOrCreate(
                ['question' => $data['question']],
                $data
            );
        }
    }

    protected function seedPricingPlans(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price' => 49.00,
                'currency' => '$',
                'billing_period' => 'month',
                'description' => 'Perfect for small schools getting started with digital management.',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 1,
                'features' => [
                    ['feature_text' => 'Up to 100 students', 'is_included' => true],
                    ['feature_text' => 'Basic academic module', 'is_included' => true],
                    ['feature_text' => 'Fee tracking', 'is_included' => true],
                    ['feature_text' => 'Attendance management', 'is_included' => true],
                    ['feature_text' => 'Email support', 'is_included' => true],
                ],
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'price' => 99.00,
                'currency' => '$',
                'billing_period' => 'month',
                'description' => 'Ideal for growing schools that need advanced features and reporting.',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
                'features' => [
                    ['feature_text' => 'Up to 500 students', 'is_included' => true],
                    ['feature_text' => 'All 17 modules included', 'is_included' => true],
                    ['feature_text' => 'Advanced reporting & analytics', 'is_included' => true],
                    ['feature_text' => 'Parent portal access', 'is_included' => true],
                    ['feature_text' => 'Priority support', 'is_included' => true],
                ],
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => 199.00,
                'currency' => '$',
                'billing_period' => 'month',
                'description' => 'For large institutions requiring unlimited access and dedicated support.',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
                'features' => [
                    ['feature_text' => 'Unlimited students', 'is_included' => true],
                    ['feature_text' => 'All modules + premium features', 'is_included' => true],
                    ['feature_text' => 'Custom integrations & API access', 'is_included' => true],
                    ['feature_text' => 'Dedicated account manager', 'is_included' => true],
                    ['feature_text' => '24/7 phone & email support', 'is_included' => true],
                ],
            ],
        ];

        foreach ($plans as $planData) {
            $features = $planData['features'];
            unset($planData['features']);

            $plan = PricingPlan::updateOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );

            foreach ($features as $index => $feature) {
                PricingFeature::updateOrCreate(
                    ['pricing_plan_id' => $plan->id, 'feature_text' => $feature['feature_text']],
                    array_merge($feature, ['sort_order' => $index + 1])
                );
            }
        }
    }

    protected function seedPublicSettings(): void
    {
        $settings = [
            ['key' => 'hero_title', 'value' => 'Transform the Way Your School Works', 'type' => 'text'],
            ['key' => 'hero_subtitle', 'value' => 'A comprehensive, all-in-one platform to manage students, staff, academics, finances, and everything in between. Built for the modern educational institution.', 'type' => 'text'],
            ['key' => 'trust_stat_modules', 'value' => '17', 'type' => 'text'],
            ['key' => 'trust_stat_students', 'value' => '2450', 'type' => 'text'],
            ['key' => 'trust_stat_institutions', 'value' => '50', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'info@duncosms.com', 'type' => 'text'],
            ['key' => 'contact_phone', 'value' => '+1 (555) 123-4567', 'type' => 'text'],
            ['key' => 'contact_address', 'value' => '123 Education Lane, Nairobi, Kenya', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            PublicSetting::setVal($setting['key'], $setting['value'], $setting['type']);
        }
    }

    protected function seedAdmissionCycle(): void
    {
        AdmissionCycle::updateOrCreate(
            ['name' => '2026/2027 Academic Year Intake'],
            [
                'academic_year' => '2026-2027',
                'opening_date' => '2026-01-15',
                'closing_date' => '2026-03-31',
                'description' => 'Admissions for the 2026/2027 academic year.',
                'status' => 'open',
                'is_active' => true,
            ]
        );
    }

    protected function seedAdmissionSectionsAndFields(): void
    {
        $sections = [
            [
                'name' => 'Applicant Information',
                'slug' => 'applicant-information',
                'description' => 'Personal details of the applicant.',
                'sort_order' => 1,
                'fields' => [
                    ['name' => 'First Name', 'slug' => 'first_name', 'type' => 'text', 'is_required' => true, 'placeholder' => 'Enter first name', 'sort_order' => 1],
                    ['name' => 'Last Name', 'slug' => 'last_name', 'type' => 'text', 'is_required' => true, 'placeholder' => 'Enter last name', 'sort_order' => 2],
                    ['name' => 'Email', 'slug' => 'email', 'type' => 'email', 'is_required' => true, 'placeholder' => 'Enter email address', 'sort_order' => 3],
                    ['name' => 'Phone', 'slug' => 'phone', 'type' => 'phone', 'is_required' => false, 'placeholder' => 'Enter phone number', 'sort_order' => 4],
                    ['name' => 'Date of Birth', 'slug' => 'date_of_birth', 'type' => 'date', 'is_required' => true, 'placeholder' => 'Select date of birth', 'sort_order' => 5],
                    ['name' => 'Gender', 'slug' => 'gender', 'type' => 'select', 'options' => ['Male', 'Female', 'Other'], 'is_required' => true, 'placeholder' => 'Select gender', 'sort_order' => 6],
                    ['name' => 'Nationality', 'slug' => 'nationality', 'type' => 'text', 'is_required' => false, 'placeholder' => 'Enter nationality', 'sort_order' => 7],
                ],
            ],
            [
                'name' => 'Parent/Guardian Information',
                'slug' => 'parent-guardian-information',
                'description' => 'Details of the parent or guardian.',
                'sort_order' => 2,
                'fields' => [
                    ['name' => 'Parent Name', 'slug' => 'parent_name', 'type' => 'text', 'is_required' => true, 'placeholder' => 'Enter parent/guardian name', 'sort_order' => 1],
                    ['name' => 'Parent Phone', 'slug' => 'parent_phone', 'type' => 'phone', 'is_required' => true, 'placeholder' => 'Enter parent/guardian phone', 'sort_order' => 2],
                    ['name' => 'Parent Email', 'slug' => 'parent_email', 'type' => 'email', 'is_required' => false, 'placeholder' => 'Enter parent/guardian email', 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Academic History',
                'slug' => 'academic-history',
                'description' => 'Previous academic background.',
                'sort_order' => 3,
                'fields' => [
                    ['name' => 'Previous School', 'slug' => 'previous_school', 'type' => 'text', 'is_required' => false, 'placeholder' => 'Enter previous school name', 'sort_order' => 1],
                    ['name' => 'Last Grade Completed', 'slug' => 'last_grade_completed', 'type' => 'text', 'is_required' => false, 'placeholder' => 'Enter last grade/class completed', 'sort_order' => 2],
                ],
            ],
            [
                'name' => 'Emergency Contact',
                'slug' => 'emergency-contact',
                'description' => 'Emergency contact details.',
                'sort_order' => 4,
                'fields' => [
                    ['name' => 'Emergency Contact Name', 'slug' => 'emergency_contact_name', 'type' => 'text', 'is_required' => true, 'placeholder' => 'Enter emergency contact name', 'sort_order' => 1],
                    ['name' => 'Emergency Contact Phone', 'slug' => 'emergency_contact_phone', 'type' => 'phone', 'is_required' => true, 'placeholder' => 'Enter emergency contact phone', 'sort_order' => 2],
                ],
            ],
            [
                'name' => 'Required Documents',
                'slug' => 'required-documents',
                'description' => 'Upload required admission documents.',
                'sort_order' => 5,
                'fields' => [],
            ],
            [
                'name' => 'Additional Information',
                'slug' => 'additional-information',
                'description' => 'Any other relevant information.',
                'sort_order' => 6,
                'fields' => [
                    ['name' => 'Additional Notes', 'slug' => 'additional_notes', 'type' => 'textarea', 'is_required' => false, 'placeholder' => 'Enter any additional information or special requirements', 'sort_order' => 1],
                ],
            ],
        ];

        foreach ($sections as $sectionData) {
            $fields = $sectionData['fields'];
            unset($sectionData['fields']);

            $section = AdmissionSection::updateOrCreate(
                ['slug' => $sectionData['slug']],
                array_merge($sectionData, ['is_active' => true])
            );

            foreach ($fields as $fieldData) {
                AdmissionField::updateOrCreate(
                    ['admission_section_id' => $section->id, 'slug' => $fieldData['slug']],
                    array_merge($fieldData, ['is_active' => true])
                );
            }
        }
    }

    protected function seedAdmissionDocumentTypes(): void
    {
        $documents = [
            ['name' => 'Birth Certificate', 'is_required' => true, 'allowed_types' => 'pdf,jpg,png', 'max_size_kb' => 5120, 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Passport Photo', 'is_required' => true, 'allowed_types' => 'jpg,png', 'max_size_kb' => 2048, 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Previous Report Card', 'is_required' => false, 'allowed_types' => 'pdf,jpg,png', 'max_size_kb' => 5120, 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Transfer Letter', 'is_required' => false, 'allowed_types' => 'pdf,jpg,png', 'max_size_kb' => 5120, 'is_active' => true, 'sort_order' => 4],
            ['name' => 'Parent/Guardian ID', 'is_required' => false, 'allowed_types' => 'pdf,jpg,png', 'max_size_kb' => 5120, 'is_active' => true, 'sort_order' => 5],
        ];

        foreach ($documents as $doc) {
            AdmissionDocumentType::updateOrCreate(
                ['name' => $doc['name']],
                $doc
            );
        }
    }
}
