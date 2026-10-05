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
        Schema::create("failed_login_attempts", function (Blueprint $table) {
            $table->id();
            $table->string("email")->nullable();
            $table->string("ip_address", 45);
            $table->text("user_agent")->nullable();
            $table->string("attempted_password_hash")->nullable(); // for detecting common password attacks
            $table
                ->enum("failure_type", [
                    "invalid_email",
                    "invalid_password",
                    "account_locked",
                    "account_disabled",
                    "two_factor_failed",
                    "rate_limited",
                    "suspicious_activity",
                ])
                ->default("invalid_password");
            $table->integer("attempts_count")->default(1);
            $table->timestamp("first_attempt_at")->nullable();
            $table->timestamp("last_attempt_at")->nullable();
            $table->timestamp("blocked_until")->nullable(); // when the IP/email is blocked until
            $table->boolean("is_blocked")->default(false);
            $table->string("country")->nullable();
            $table->string("city")->nullable();
            $table->boolean("is_suspicious_pattern")->default(false); // detected by ML or rules
            $table->json("attack_patterns")->nullable(); // store detected attack patterns
            $table->timestamps();

            // Indexes for security monitoring and rate limiting
            $table->index(["ip_address", "last_attempt_at"]);
            $table->index(["email", "last_attempt_at"]);
            $table->index(["is_blocked", "blocked_until"]);
            $table->index(["failure_type", "created_at"]);
            $table->index(["is_suspicious_pattern", "created_at"]);
            $table->index("blocked_until");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("failed_login_attempts");
    }
};
