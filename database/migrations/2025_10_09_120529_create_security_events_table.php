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
        Schema::create("security_events", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("user_id")
                ->nullable()
                ->constrained()
                ->onDelete("set null");
            $table
                ->foreignId("school_id")
                ->nullable()
                ->constrained()
                ->onDelete("cascade");

            // Event classification
            $table->enum("event_type", [
                "login_success",
                "login_failure",
                "logout",
                "password_change",
                "password_reset",
                "two_factor_enabled",
                "two_factor_disabled",
                "suspicious_login",
                "account_locked",
                "account_unlocked",
                "permission_change",
                "data_export",
                "api_access",
                "security_settings_change",
                "device_trusted",
                "device_revoked",
                "multiple_failed_attempts",
                "unusual_location",
                "tor_access_attempt",
                "vpn_access_attempt",
                "brute_force_detected",
                "session_hijack_attempt",
                "unauthorized_access_attempt",
            ]);

            $table
                ->enum("severity_level", ["low", "medium", "high", "critical"])
                ->default("medium");
            $table
                ->enum("status", [
                    "active",
                    "resolved",
                    "investigating",
                    "false_positive",
                ])
                ->default("active");

            // Event details
            $table->string("title")->nullable();
            $table->text("description")->nullable();
            $table->string("ip_address", 45)->nullable();
            $table->text("user_agent")->nullable();
            $table->string("location")->nullable();
            $table->json("event_data")->nullable(); // store additional event-specific data

            // Risk assessment
            $table->integer("risk_score")->nullable(); // 1-100 scale
            $table->boolean("requires_action")->default(false);
            $table->boolean("user_notified")->default(false);
            $table->boolean("admin_notified")->default(false);

            // Investigation and response
            $table->timestamp("detected_at")->nullable();
            $table->timestamp("acknowledged_at")->nullable();
            $table->timestamp("resolved_at")->nullable();
            $table
                ->foreignId("acknowledged_by")
                ->nullable()
                ->constrained("users")
                ->onDelete("set null");
            $table
                ->foreignId("resolved_by")
                ->nullable()
                ->constrained("users")
                ->onDelete("set null");
            $table->text("resolution_notes")->nullable();

            // Correlation and patterns
            $table->string("correlation_id")->nullable(); // group related events
            $table->boolean("is_part_of_campaign")->default(false);
            $table->string("attack_signature")->nullable();
            $table->json("threat_indicators")->nullable();

            // Automated response
            $table->boolean("auto_mitigated")->default(false);
            $table->json("mitigation_actions")->nullable();
            $table->timestamp("mitigation_applied_at")->nullable();

            $table->timestamps();

            // Indexes for performance and security monitoring
            $table->index(["user_id", "event_type", "detected_at"]);
            $table->index(["school_id", "severity_level", "detected_at"]);
            $table->index(["event_type", "detected_at"]);
            $table->index(["severity_level", "status", "detected_at"]);
            $table->index(["ip_address", "detected_at"]);
            $table->index(["requires_action", "status"]);
            $table->index(["correlation_id", "detected_at"]);
            $table->index("risk_score");
            $table->index("detected_at");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("security_events");
    }
};
