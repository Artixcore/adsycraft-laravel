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
        Schema::create('workspaces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('subscription_tier', 30)->default('free');
            $table->string('subscription_status', 30)->default('active');
            $table->timestamp('subscription_expires_at')->nullable();
            $table->timestamps();
        });

        Schema::table('workspaces', function (Blueprint $table) {
            $table->index('slug');
            $table->index('subscription_tier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspaces');
    }
};
