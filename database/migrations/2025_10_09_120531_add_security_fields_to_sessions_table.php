<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("sessions", function (Blueprint $table) {
            // Security tracking fields
            $table->string("device_type")->nullable()->after("user_agent");
            $table->string("browser")->nullable()->after("device_type");
            $table->string("platform")->nullable()->after("browser");
            $table->string("location")->nullable()->after("platform");
            $table
                ->boolean("is_trusted_device")
                ->default(false)
                ->after("location");
            $table
                ->timestamp("last_activity_at")
                ->nullable()
                ->after("is_trusted_device");
            $table
                ->boolean("is_suspicious")
                ->default(false)
                ->after("last_activity_at");
            $table->integer("risk_score")->nullable()->after("is_suspicious");
            $table->json("security_flags")->nullable()->after("risk_score");
            $table
                ->timestamp("expires_at")
                ->nullable()
                ->after("security_flags");
            $table
                ->boolean("force_logout")
                ->default(false)
                ->after("expires_at");

            // Add indexes for better performance
            $table->index(
                ["user_id", "is_suspicious", "last_activity"],
                "sessions_user_suspicious_idx",
            );
            $table->index(
                ["is_trusted_device", "user_id"],
                "sessions_trusted_user_idx",
            );
            $table->index(
                ["force_logout", "user_id"],
                "sessions_logout_user_idx",
            );
            $table->index("expires_at", "sessions_expires_idx");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("sessions", function (Blueprint $table) {
            $table->dropIndex("sessions_user_suspicious_idx");
            $table->dropIndex("sessions_trusted_user_idx");
            $table->dropIndex("sessions_logout_user_idx");
            $table->dropIndex("sessions_expires_idx");

            $table->dropColumn([
                "device_type",
                "browser",
                "platform",
                "location",
                "is_trusted_device",
                "last_activity_at",
                "is_suspicious",
                "risk_score",
                "security_flags",
                "expires_at",
                "force_logout",
            ]);
        });
    }
};
