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
        Schema::create("user_trusted_devices", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table
                ->foreignId("school_id")
                ->nullable()
                ->constrained()
                ->onDelete("cascade");

            // Device identification
            $table->string("device_token", 255)->unique(); // unique token for this device
            $table->string("device_name")->nullable(); // user-friendly name
            $table->string("device_fingerprint", 255); // browser/device fingerprint
            $table->string("user_agent", 1000)->nullable();
            $table->string("ip_address", 45)->nullable();

            // Device details
            $table->string("browser")->nullable();
            $table->string("platform")->nullable(); // iOS, Android, Windows, etc.
            $table->string("device_type")->nullable(); // mobile, desktop, tablet
            $table->string("screen_resolution")->nullable();
            $table->string("timezone")->nullable();
            $table->string("language")->nullable();

            // Location information
            $table->string("country")->nullable();
            $table->string("city")->nullable();
            $table->decimal("latitude", 10, 8)->nullable();
            $table->decimal("longitude", 11, 8)->nullable();

            // Trust management
            $table->boolean("is_trusted")->default(true);
            $table->timestamp("trusted_at")->nullable();
            $table->timestamp("expires_at")->nullable(); // when trust expires
            $table->timestamp("last_used_at")->nullable();
            $table->integer("usage_count")->default(0);

            // Security flags
            $table->boolean("is_revoked")->default(false);
            $table->timestamp("revoked_at")->nullable();
            $table->string("revoked_reason")->nullable();
            $table->boolean("requires_verification")->default(false);

            // Metadata
            $table->json("device_metadata")->nullable(); // store additional device info
            $table->json("security_scores")->nullable(); // risk assessment scores
            $table->boolean("is_primary_device")->default(false);

            $table->timestamps();

            // Indexes for performance and security
            $table->index(["user_id", "is_trusted", "expires_at"]);
            $table->index(["device_fingerprint", "user_id"]);
            $table->index(["ip_address", "created_at"]);
            $table->index(["expires_at", "is_trusted"]);
            $table->index(["is_revoked", "revoked_at"]);
            $table->index("last_used_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("user_trusted_devices");
    }
};
