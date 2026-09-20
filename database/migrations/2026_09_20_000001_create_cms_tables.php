<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position')->nullable();
            $table->string('institution')->nullable();
            $table->text('content');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('image_path')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->default('general');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('currency', 10)->default('USD');
            $table->string('billing_period')->default('monthly');
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('pricing_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pricing_plan_id')->constrained()->cascadeOnDelete();
            $table->string('feature_text');
            $table->boolean('is_included')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('public_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('type')->default('text');
            $table->timestamps();
        });

        Schema::create('contact_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('organization')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('new');
            $table->text('notes')->nullable();
            $table->string('assigned_to')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('demo_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name');
            $table->string('institution');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('number_of_students')->nullable();
            $table->string('current_system')->nullable();
            $table->json('modules')->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('preferred_time')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('new');
            $table->text('notes')->nullable();
            $table->string('assigned_to')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('source')->default('website');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('organization')->nullable();
            $table->string('status')->default('new');
            $table->text('notes')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('source');
        });

        Schema::create('lead_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('activity_type');
            $table->text('description');
            $table->timestamps();

            $table->index('lead_id');
        });

        Schema::create('admission_cycles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('academic_year');
            $table->date('opening_date');
            $table->date('closing_date');
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('admission_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('admission_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_section_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('type')->default('text');
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->string('help_text')->nullable();
            $table->string('placeholder')->nullable();
            $table->string('validation_rules')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('admission_section_id');
        });

        Schema::create('admission_documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_required')->default(false);
            $table->string('allowed_types')->nullable();
            $table->unsignedInteger('max_size_kb')->default(5120);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('admission_applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->foreignId('admission_cycle_id')->constrained();
            $table->string('status')->default('draft');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('national_id')->nullable();
            $table->json('parent_data')->nullable();
            $table->json('academic_data')->nullable();
            $table->json('emergency_data')->nullable();
            $table->json('field_values')->nullable();
            $table->json('document_paths')->nullable();
            $table->string('desired_class')->nullable();
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('interview_date')->nullable();
            $table->timestamp('interview_completed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('enrolled_at')->nullable();
            $table->unsignedBigInteger('enrolled_student_id')->nullable();
            $table->timestamps();

            $table->index('admission_cycle_id');
            $table->index('status');
            $table->index('application_number');
        });

        Schema::create('admission_application_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('note');
            $table->timestamps();

            $table->index('admission_application_id');
        });

        Schema::create('admission_application_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admission_application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->text('details')->nullable();
            $table->timestamps();

            $table->index('admission_application_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_application_logs');
        Schema::dropIfExists('admission_application_notes');
        Schema::dropIfExists('admission_applications');
        Schema::dropIfExists('admission_documents');
        Schema::dropIfExists('admission_fields');
        Schema::dropIfExists('admission_sections');
        Schema::dropIfExists('admission_cycles');
        Schema::dropIfExists('lead_activities');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('demo_requests');
        Schema::dropIfExists('contact_enquiries');
        Schema::dropIfExists('public_settings');
        Schema::dropIfExists('pricing_features');
        Schema::dropIfExists('pricing_plans');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('testimonials');
    }
};
