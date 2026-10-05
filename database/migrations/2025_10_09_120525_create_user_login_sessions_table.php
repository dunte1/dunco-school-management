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
        Schema::create("user_login_sessions", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("user_id")
                ->nullable()
                ->constrained()
                ->onDelete("cascade");
            $table
                ->foreignId("school_id")
                ->nullable()
                ->constrained()
                ->onDelete("cascade");
            $table->string("session_id")->nullable();
            $table->string("ip_address", 45)->nullable();
            $table->text("user_agent")->nullable();
            $table->string("device_type")->nullable(); // mobile, desktop, tablet
            $table->string("browser")->nullable();
            $table->string("platform")->nullable(); // iOS, Android, Windows, etc.
            $table->string("country")->nullable();
            $table->string("city")->nullable();
            $table->decimal("latitude", 10, 8)->nullable();
            $table->decimal("longitude", 11, 8)->nullable();
            $table
                ->enum("login_method", [
                    "password",
                    "two_factor",
                    "social",
                    "api",
                ])
                ->default("password");
            $table->boolean("is_successful")->default(true);
            $table->string("failure_reason")->nullable();
            $table->timestamp("login_at")->nullable();
            $table->timestamp("logout_at")->nullable();
            $table->integer("duration_seconds")->nullable(); // calculated on logout
            $table->boolean("is_suspicious")->default(false);
            $table->json("extra_data")->nullable(); // for storing additional metadata
            $table->timestamps();

            // Indexes for better performance
            $table->index(["user_id", "login_at"]);
            $table->index(["school_id", "login_at"]);
            $table->index(["ip_address", "login_at"]);
            $table->index(["is_successful", "login_at"]);
            $table->index(["is_suspicious", "login_at"]);
            $table->index("session_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("user_login_sessions");
    }
};
