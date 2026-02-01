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
        Schema::create('ai_request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_account_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider', 50);
            $table->string('model', 100)->nullable();
            $table->string('request_type', 50)->nullable();
            $table->unsignedInteger('input_tokens')->nullable();
            $table->unsignedInteger('output_tokens')->nullable();
            $table->decimal('cost', 12, 6)->nullable();
            $table->string('status', 20)->default('success');
            $table->unsignedInteger('latency_ms')->nullable();
            $table->timestamps();
        });

        Schema::table('ai_request_logs', function (Blueprint $table) {
            $table->index(['business_account_id', 'created_at']);
            $table->index('provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_request_logs');
    }
};
