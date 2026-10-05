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
        Schema::table('staff_documents', function (Blueprint $table) {
            $table->string('title')->after('type');
            $table->text('description')->change();
            $table->date('expiry_date')->nullable()->after('description');
            $table->boolean('is_active')->default(true)->after('expiry_date');
            $table->unsignedBigInteger('uploaded_by')->nullable()->after('is_active');
            
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_documents', function (Blueprint $table) {
            $table->dropForeign(['uploaded_by']);
            $table->dropColumn(['title', 'expiry_date', 'is_active', 'uploaded_by']);
            $table->string('description')->change();
        });
    }
};
