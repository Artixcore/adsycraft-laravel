<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_account_id')->constrained()->cascadeOnDelete();
            $table->enum('provider', ['openai', 'gemini', 'grok']);
            $table->text('api_key');
            $table->string('default_model')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_primary')->default(false);
            $table->timestamp('last_tested_at')->nullable();
            $table->timestamps();
            $table->unique(['business_account_id', 'provider']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_connections');
    }
};
