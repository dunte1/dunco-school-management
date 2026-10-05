<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('exam_types')) {
        Schema::create('exam_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Midterm, Final, Quiz
            $table->text('description')->nullable();
            $table->unsignedBigInteger('school_id')->nullable(); // If linked to schools
            $table->timestamps();

            // Foreign key if schools table exists
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });
        } else {
            // Table exists, add school_id column if it doesn't exist
            if (!Schema::hasColumn('exam_types', 'school_id')) {
                Schema::table('exam_types', function (Blueprint $table) {
                    $table->unsignedBigInteger('school_id')->nullable()->after('description');
                    $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('exam_types', 'school_id')) {
            Schema::table('exam_types', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }
    }
};
