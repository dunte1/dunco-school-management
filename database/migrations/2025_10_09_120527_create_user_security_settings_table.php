<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_security_settings')) {
            Schema::create("user_security_settings", function (Blueprint $table) {
                $table->id();
                $table->foreignId("user_id")->constrained()->onDelete("cascade");
                $table->foreignId("school_id")->nullable()->constrained()->onDelete("cascade");

                // Password settings
                $table->boolean("require_password_change_on_next_login")->default(false);
                $table->integer("password_expiry_days")->nullable();
                $table->timestamp("password_last_changed")->nullable();
                $table->integer("password_history_count")->default(5);
                $table->json("password_history")->nullable();

                // Login security
                $table->boolean("enable_login_alerts")->default(true);
                $table->boolean("enable_new_device_alerts")->default(true);
                $table->boolean("enable_suspicious_activity_alerts")->default(true);
                $table->integer("max_concurrent_sessions")->default(5);
                $table->integer("session_timeout_minutes")->default(480);

                // IP and location restrictions
                $table->json("allowed_ip_addresses")->nullable();
                $table->json("blocked_ip_addresses")->nullable();
                $table->json("allowed_countries")->nullable();
                $table->boolean("block_tor_access")->default(false);
                $table->boolean("block_vpn_access")->default(false);

                // Two-factor authentication
                $table->boolean("require_2fa")->default(false);
                $table->boolean("allow_2fa_bypass_trusted_devices")->default(false);
                $table->integer("trusted_device_expiry_days")->default(30);
                $table->json("trusted_devices")->nullable();

                // Account security
                $table->boolean("enable_account_lockout")->default(true);
                $table->integer("max_failed_attempts")->default(5);
                $table->integer("lockout_duration_minutes")->default(15);
                $table->boolean("auto_logout_on_suspicious_activity")->default(true);

                // Notification preferences
                $table->boolean("email_security_alerts")->default(true);
                $table->boolean("sms_security_alerts")->default(false);
                $table->boolean("in_app_security_alerts")->default(true);
                $table->string("security_contact_email")->nullable();
                $table->string("security_contact_phone")->nullable();

                // Privacy settings
                $table->boolean("hide_last_login_info")->default(false);
                $table->boolean("anonymous_usage_tracking")->default(true);
                $table->integer("login_history_retention_days")->default(90);

                // API and integration security
                $table->boolean("enable_api_access")->default(false);
                $table->json("api_rate_limits")->nullable();
                $table->json("allowed_api_scopes")->nullable();

                // Compliance and audit
                $table->boolean("gdpr_compliance_mode")->default(false);
                $table->boolean("enhanced_audit_logging")->default(false);
                $table->timestamp("security_review_due_at")->nullable();
                $table->json("compliance_flags")->nullable();

                $table->timestamps();

                // Indexes
                $table->index(["user_id", "school_id"]);
                $table->index("password_last_changed", "uss_pwd_last_changed_idx");
                $table->index("require_password_change_on_next_login", "uss_req_pwd_change_idx");
                $table->index("security_review_due_at", "uss_security_review_idx");
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists("user_security_settings");
    }
};
