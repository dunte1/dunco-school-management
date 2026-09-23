<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->decimal('fee_amount', 8, 2)->nullable()->after('retake_reason');
            $table->string('fee_description')->nullable()->after('fee_amount');
            $table->boolean('fee_required')->default(false)->after('fee_description');
            $table->string('currency', 3)->default('KES')->after('fee_required');
        });
    }

    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['fee_amount', 'fee_description', 'fee_required', 'currency']);
        });
    }
};
