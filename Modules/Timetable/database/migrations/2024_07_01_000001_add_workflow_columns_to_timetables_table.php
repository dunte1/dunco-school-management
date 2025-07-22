<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('timetables', function (Blueprint $table) {
            $table->enum('status', ['draft', 'pending', 'published', 'archived'])->default('draft')->after('id');
            $table->unsignedBigInteger('submitted_by')->nullable()->after('status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('submitted_by');
            $table->unsignedBigInteger('published_by')->nullable()->after('approved_by');
            $table->unsignedBigInteger('archived_by')->nullable()->after('published_by');
            $table->timestamp('submitted_at')->nullable()->after('archived_by');
            $table->timestamp('approved_at')->nullable()->after('submitted_at');
            $table->timestamp('published_at')->nullable()->after('approved_at');
            $table->timestamp('archived_at')->nullable()->after('published_at');

            $table->foreign('submitted_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('published_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('archived_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('timetables', function (Blueprint $table) {
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['published_by']);
            $table->dropForeign(['archived_by']);
            $table->dropColumn([
                'status',
                'submitted_by',
                'approved_by',
                'published_by',
                'archived_by',
                'submitted_at',
                'approved_at',
                'published_at',
                'archived_at',
            ]);
        });
    }
}; 