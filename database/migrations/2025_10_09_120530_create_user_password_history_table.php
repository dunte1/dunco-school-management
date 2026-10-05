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
        Schema::create("user_password_history", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table
                ->foreignId("school_id")
                ->nullable()
                ->constrained()
                ->onDelete("cascade");

            // Password tracking
            $table->string("password_hash"); // store the hashed password
            $table->timestamp("created_at")->nullable();
            $table->string("ip_address", 45)->nullable();
            $table->text("user_agent")->nullable();

            // Security metadata
            $table
                ->enum("change_reason", [
                    "user_initiated",
                    "admin_reset",
                    "forced_expiry",
                    "security_incident",
                    "first_login",
                    "migration",
                ])
                ->default("user_initiated");

            $table->boolean("was_compromised")->default(false);
            $table->json("strength_metrics")->nullable(); // store password strength analysis

            // Administrative tracking
            $table
                ->foreignId("changed_by")
                ->nullable()
                ->constrained("users")
                ->onDelete("set null");
            $table->text("admin_notes")->nullable();
            $table->timestamp("updated_at")->nullable();

            // Indexes for performance and security
            $table->index(["user_id", "created_at"]);
            $table->index(["school_id", "created_at"]);
            $table->index("password_hash"); // for checking against reuse
            $table->index(["user_id", "password_hash"]); // composite for reuse checks
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("user_password_history");
    }
};
